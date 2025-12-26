<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\PresensiFace;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SimpleFacePresensiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:karyawan');
    }

    public function dashboard()
    {
        try {
            $karyawan = Auth::guard('karyawan')->user();
            $nik = $karyawan->nik;
            $nama_lengkap = $karyawan->nama_lengkap;

            // ✅ Load karyawan dengan relasi lengkap
            $karyawan_model = Karyawan::where('nik', $nik)
                ->with(['cabang', 'departemen'])
                ->first();

            // Check face data
            $faceData = DB::table('face_data')
                ->where('nik', $nik)
                ->where('status', 'active')
                ->first();

            $hariini = Carbon::now('Asia/Jakarta')->format('Y-m-d');

            // ✅ Get ALL presensi today
            $presensi_hari_ini = PresensiFace::where('nik', $nik)
                ->where('tanggal', $hariini)
                ->orderBy('shift_ke', 'asc')
                ->orderBy('created_at', 'asc')
                ->get();

            $histori = PresensiFace::where('nik', $nik)
                ->where('tanggal', '<', $hariini)
                ->orderBy('tanggal', 'DESC')
                ->orderBy('shift_ke', 'ASC')
                ->orderBy('created_at', 'DESC')
                ->limit(30)
                ->get();

            $bulan_ini = Carbon::now('Asia/Jakarta')->format('Y-m');
            $statistik = PresensiFace::where('nik', $nik)
                ->whereRaw('DATE_FORMAT(tanggal, "%Y-%m") = ?', [$bulan_ini])
                ->count();

            // ✅ MULTI-SHIFT DETECTION (NEW LOGIC)
            $jam_kerja = $karyawan_model->getJamKerjaHariIni();
            $is_multi_shift = false;
            $shifts_available = collect();
            $total_shifts = 0;

            Log::info('Dashboard - Jam Kerja Check', [
                'nik' => $nik,
                'kode_dept' => $karyawan_model->kode_dept,
                'kode_cabang' => $karyawan_model->kode_cabang,
                'jam_kerja_found' => $jam_kerja ? 'YES' : 'NO',
                'kode_jam_kerja' => $jam_kerja->kode_jam_kerja ?? 'NULL',
                'tipe_jam_kerja' => $jam_kerja->tipe_jam_kerja ?? 'NULL'
            ]);

            if ($jam_kerja) {
                // Check if multi-shift
                if ($jam_kerja->tipe_jam_kerja === 'multi_shift') {
                    $shifts_available = DB::table('jam_kerja_shifts')
                        ->where('kode_jam_kerja', $jam_kerja->kode_jam_kerja)
                        ->where('is_active', true)
                        ->orderBy('shift_ke')
                        ->get();

                    $total_shifts = $shifts_available->count();
                    $is_multi_shift = $total_shifts >= 2;

                    Log::info('Multi-Shift Detection', [
                        'nik' => $nik,
                        'total_shifts' => $total_shifts,
                        'is_multi_shift' => $is_multi_shift,
                        'shifts' => $shifts_available->pluck('nama_shift')->toArray()
                    ]);
                }
            } else {
                Log::warning('No Jam Kerja Configuration', [
                    'nik' => $nik,
                    'kode_dept' => $karyawan_model->kode_dept,
                    'kode_cabang' => $karyawan_model->kode_cabang
                ]);
            }

            // ✅ Check which shifts completed
            $completed_shifts = $presensi_hari_ini
                ->whereNotNull('shift_ke')
                ->whereNotNull('jam_pulang')
                ->pluck('shift_ke')
                ->toArray();

            $regular_done = $presensi_hari_ini
                ->whereNull('shift_ke')
                ->whereNotNull('jam_pulang')
                ->count() > 0;

            return view('karyawan.simple-face.dashboard', compact(
                'faceData',
                'presensi_hari_ini',
                'histori',
                'statistik',
                'nama_lengkap',
                'karyawan_model',
                'is_multi_shift',
                'shifts_available',
                'total_shifts',
                'completed_shifts',
                'regular_done',
                'jam_kerja'  // ← Pass jam_kerja untuk debugging
            ));
        } catch (Exception $e) {
            Log::error('SimpleFace Dashboard Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('dashboard')->with('error', 'Terjadi kesalahan sistem.');
        }
    }

    public function create(Request $request)
    {
        try {
            $karyawan = Auth::guard('karyawan')->user();
            $nik = $karyawan->nik;
            $nama_lengkap = $karyawan->nama_lengkap;
            $kode_cabang = $karyawan->kode_cabang;

            // Check face data
            $faceData = DB::table('face_data')
                ->where('nik', $nik)
                ->where('status', 'active')
                ->first();

            if (!$faceData) {
                return redirect()->route('face-presensi.enrollment')
                    ->with('info', 'Silakan daftarkan wajah Anda terlebih dahulu.');
            }

            // Get lokasi kantor
            $lok_kantor = DB::table('cabang')
                ->where('kode_cabang', $kode_cabang)
                ->first();

            if (!$lok_kantor || empty($lok_kantor->lokasi_cabang)) {
                return redirect()->route('face-presensi.dashboard')
                    ->with('error', 'Data lokasi cabang tidak ditemukan. Hubungi admin.');
            }

            // ✅ MULTI-SHIFT DETECTION (NEW LOGIC)
            $karyawan_model = Karyawan::where('nik', $nik)->first();
            $jam_kerja = $karyawan_model->getJamKerjaHariIni();
            $is_multi_shift = false;
            $shifts_available = collect();
            $shift_ke = null;
            $current_shift = null;

            if ($jam_kerja && $jam_kerja->tipe_jam_kerja === 'multi_shift') {
                $shifts_available = DB::table('jam_kerja_shifts')
                    ->where('kode_jam_kerja', $jam_kerja->kode_jam_kerja)
                    ->where('is_active', true)
                    ->orderBy('shift_ke')
                    ->get();

                $is_multi_shift = $shifts_available->count() >= 2;

                // Get shift_ke from request
                if ($is_multi_shift && $request->filled('shift_ke')) {
                    $shift_ke = $request->shift_ke;
                    $current_shift = $shifts_available->where('shift_ke', $shift_ke)->first();

                    if (!$current_shift) {
                        return redirect()->route('face-presensi.dashboard')
                            ->with('error', 'Shift tidak valid.');
                    }
                }
            }

            return view('karyawan.simple-face.create', compact(
                'nama_lengkap',
                'faceData',
                'lok_kantor',
                'is_multi_shift',
                'shifts_available',
                'shift_ke',
                'current_shift'
            ));
        } catch (Exception $e) {
            Log::error('SimpleFace Create Error: ' . $e->getMessage());
            return redirect()->route('face-presensi.dashboard')->with('error', 'Terjadi kesalahan.');
        }
    }

    public function store(Request $request)
    {
        try {
            $karyawan = Auth::guard('karyawan')->user();
            $nik = $karyawan->nik;
            $kode_cabang = $karyawan->kode_cabang;

            $tanggal = Carbon::now('Asia/Jakarta')->format('Y-m-d');
            $jam = Carbon::now('Asia/Jakarta')->format('H:i:s');

            // Validasi
            if (!$request->verified || $request->verified !== 'true') {
                return response("error|Presensi harus menggunakan verifikasi wajah|system", 200);
            }

            // Get cabang
            $cabang = DB::table('cabang')->where('kode_cabang', $kode_cabang)->first();
            if (!$cabang || empty($cabang->lokasi_cabang)) {
                return response("error|Data lokasi cabang tidak ditemukan|system", 200);
            }

            $lokasi_parts = explode(',', $cabang->lokasi_cabang);
            $lokasi_cabang = trim($lokasi_parts[0]) . ',' . trim($lokasi_parts[1]);

            // ✅ GET SHIFT INFO
            $shift_ke = $request->filled('shift_ke') ? $request->shift_ke : null;
            $nama_shift = null;

            if ($shift_ke) {
                $karyawan_model = Karyawan::where('nik', $nik)->first();
                $jam_kerja = $karyawan_model->getJamKerjaHariIni();

                if (!$jam_kerja) {
                    return response("error|Jam kerja tidak ditemukan|system", 200);
                }

                $shift_data = DB::table('jam_kerja_shifts')
                    ->where('kode_jam_kerja', $jam_kerja->kode_jam_kerja)
                    ->where('shift_ke', $shift_ke)
                    ->where('is_active', true)
                    ->first();

                if (!$shift_data) {
                    return response("error|Shift tidak valid|system", 200);
                }

                $nama_shift = $shift_data->nama_shift;
            }

            DB::beginTransaction();

            try {
                if ($shift_ke) {
                    // MULTI-SHIFT MODE
                    $presensi = PresensiFace::where('nik', $nik)
                        ->where('tanggal', $tanggal)
                        ->where('shift_ke', $shift_ke)
                        ->first();

                    if ($presensi) {
                        if (!empty($presensi->jam_pulang)) {
                            DB::rollBack();
                            return response("error|Shift {$shift_ke} sudah selesai|out", 200);
                        }

                        $presensi->update([
                            'jam_pulang' => $jam,
                            'lokasi' => $lokasi_cabang,
                        ]);

                        DB::commit();
                        return response("success|Absen Pulang Shift {$shift_ke} Berhasil! ✅ {$jam}|out", 200);
                    } else {
                        PresensiFace::create([
                            'nik' => $nik,
                            'tanggal' => $tanggal,
                            'jam_masuk' => $jam,
                            'jam_pulang' => null,
                            'lokasi' => $lokasi_cabang,
                            'status' => 'verified',
                            'shift_ke' => $shift_ke,
                            'nama_shift' => $nama_shift
                        ]);

                        DB::commit();
                        return response("success|Absen Masuk Shift {$shift_ke} Berhasil! ✅ {$jam}|in", 200);
                    }
                } else {
                    // REGULAR MODE
                    $presensi = PresensiFace::where('nik', $nik)
                        ->where('tanggal', $tanggal)
                        ->whereNull('shift_ke')
                        ->first();

                    if ($presensi) {
                        if (!empty($presensi->jam_pulang)) {
                            DB::rollBack();
                            return response("error|Sudah absen pulang hari ini|out", 200);
                        }

                        $presensi->update([
                            'jam_pulang' => $jam,
                            'lokasi' => $lokasi_cabang,
                        ]);

                        DB::commit();
                        return response("success|Absen Pulang Berhasil! ✅ {$jam}|out", 200);
                    } else {
                        PresensiFace::create([
                            'nik' => $nik,
                            'tanggal' => $tanggal,
                            'jam_masuk' => $jam,
                            'jam_pulang' => null,
                            'lokasi' => $lokasi_cabang,
                            'status' => 'verified',
                            'shift_ke' => null,
                            'nama_shift' => null
                        ]);

                        DB::commit();
                        return response("success|Absen Masuk Berhasil! ✅ {$jam}|in", 200);
                    }
                }
            } catch (Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('SimpleFace Store Error: ' . $e->getMessage());
            return response("error|Terjadi kesalahan sistem|system", 200);
        }
    }

    // ... (enrollment methods sama seperti sebelumnya)
    public function enrollment()
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $nama_lengkap = Auth::guard('karyawan')->user()->nama_lengkap;

        $faceData = DB::table('face_data')
            ->where('nik', $nik)
            ->where('status', 'active')
            ->first();

        return view('karyawan.simple-face.enrollment', compact('faceData', 'nama_lengkap'));
    }

    public function enrollmentStore(Request $request)
    {
        try {
            $request->validate([
                'face_descriptor' => 'required|string',
                'face_image' => 'required|string'
            ]);

            $nik = Auth::guard('karyawan')->user()->nik;

            DB::beginTransaction();

            $image = $request->face_image;
            $image_parts = explode(";base64,", $image);
            $image_base64 = base64_decode($image_parts[1]);
            $fileName = $nik . '_face_' . time() . '.png';
            $folderPath = "public/uploads/faces/";
            $file = $folderPath . $fileName;

            Storage::put($file, $image_base64);

            DB::table('face_data')->updateOrInsert(
                ['nik' => $nik],
                [
                    'face_descriptor' => $request->face_descriptor,
                    'face_image' => $fileName,
                    'status' => 'active',
                    'enrollment_count' => DB::raw('enrollment_count + 1'),
                    'last_updated' => now(),
                    'updated_at' => now()
                ]
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data wajah berhasil disimpan'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Enrollment Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data wajah'
            ], 500);
        }
    }

    public function getDescriptor()
    {
        try {
            $nik = Auth::guard('karyawan')->user()->nik;

            $faceData = DB::table('face_data')
                ->where('nik', $nik)
                ->where('status', 'active')
                ->first();

            if (!$faceData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data wajah belum terdaftar'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'descriptor' => json_decode($faceData->face_descriptor)
            ]);
        } catch (Exception $e) {
            Log::error('Get Descriptor Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data wajah'
            ], 500);
        }
    }

    public function deleteEnrollment()
    {
        try {
            $nik = Auth::guard('karyawan')->user()->nik;

            $faceData = DB::table('face_data')->where('nik', $nik)->first();

            if ($faceData && $faceData->face_image) {
                Storage::delete('public/uploads/faces/' . $faceData->face_image);
            }

            DB::table('face_data')->where('nik', $nik)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data wajah berhasil dihapus'
            ]);
        } catch (Exception $e) {
            Log::error('Delete Enrollment Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data wajah'
            ], 500);
        }
    }
}