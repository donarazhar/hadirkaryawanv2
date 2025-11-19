<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\PrensensiFace;
use Exception;

class SimpleFacePresensiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:karyawan');
    }

    public function dashboard()
    {
        try {
            $nik = Auth::guard('karyawan')->user()->nik;
            $nama_lengkap = Auth::guard('karyawan')->user()->nama_lengkap;

            // Check face data
            $faceData = DB::table('face_data')
                ->where('nik', $nik)
                ->where('status', 'active')
                ->first();

            $hariini = Carbon::now('Asia/Jakarta')->format('Y-m-d');

            // ✅ PERBAIKAN: Ubah orderBy('jam') menjadi orderBy('created_at')
            $presensi_hari_ini = PrensensiFace::where('nik', $nik)
                ->where('tanggal', $hariini)
                ->orderBy('created_at', 'desc') // ✅ Ganti dari 'jam' ke 'created_at'
                ->get();

            // ✅ PERBAIKAN: Hapus orderBy('jam')
            $histori = PrensensiFace::where('nik', $nik)
                ->where('tanggal', '<', $hariini)
                ->orderBy('tanggal', 'DESC')
                ->orderBy('created_at', 'DESC') // ✅ Ganti dari 'jam' ke 'created_at'
                ->limit(10)
                ->get();

            $bulan_ini = Carbon::now('Asia/Jakarta')->format('Y-m');
            $statistik = PrensensiFace::where('nik', $nik)
                ->whereRaw('DATE_FORMAT(tanggal, "%Y-%m") = ?', [$bulan_ini])
                ->count();

            return view('karyawan.simple-face.dashboard', compact(
                'faceData',
                'presensi_hari_ini',
                'histori',
                'statistik',
                'nama_lengkap'
            ));
        } catch (Exception $e) {
            Log::error('SimpleFace Dashboard Error: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Terjadi kesalahan sistem.');
        }
    }

    public function create()
    {
        try {
            $nik = Auth::guard('karyawan')->user()->nik;
            $nama_lengkap = Auth::guard('karyawan')->user()->nama_lengkap;
            $kode_cabang = Auth::guard('karyawan')->user()->kode_cabang;

            // Check face data
            $faceData = DB::table('face_data')
                ->where('nik', $nik)
                ->where('status', 'active')
                ->first();

            // Jika belum daftar face, redirect ke enrollment
            if (!$faceData) {
                return redirect()->route('face-presensi.enrollment')
                    ->with('info', 'Silakan daftarkan wajah Anda terlebih dahulu.');
            }

            // ✅ Get lokasi kantor dari cabang
            $lok_kantor = DB::table('cabang')
                ->where('kode_cabang', $kode_cabang)
                ->first();

            // ✅ Validasi lokasi cabang
            if (!$lok_kantor || empty($lok_kantor->lokasi_cabang)) {
                return redirect()->route('face-presensi.dashboard')
                    ->with('error', 'Data lokasi cabang tidak ditemukan. Hubungi admin.');
            }

            return view('karyawan.simple-face.create', compact(
                'nama_lengkap',
                'faceData',
                'lok_kantor'
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
            $nama_lengkap = $karyawan->nama_lengkap;
            $kode_cabang = $karyawan->kode_cabang;

            $tanggal = Carbon::now('Asia/Jakarta')->format('Y-m-d');
            $jam = Carbon::now('Asia/Jakarta')->format('H:i:s');

            Log::info('Simple Face Presensi Store Started', [
                'nik' => $nik,
                'tanggal' => $tanggal,
                'jam' => $jam
            ]);

            // Validasi verified flag
            if (!$request->verified || $request->verified !== 'true') {
                return response("error|Presensi harus menggunakan verifikasi wajah|system", 200);
            }

            // ✅ Ambil lokasi dari cabang (bukan dari GPS request)
            $cabang = DB::table('cabang')
                ->where('kode_cabang', $kode_cabang)
                ->first();

            if (!$cabang || empty($cabang->lokasi_cabang)) {
                return response("error|Data lokasi cabang tidak ditemukan|system", 200);
            }

            // ✅ Tambahkan validasi format lokasi
            $lokasi_parts = explode(',', $cabang->lokasi_cabang);
            if (count($lokasi_parts) != 2) {
                return response("error|Format lokasi cabang tidak valid|system", 200);
            }

            $lokasi_cabang = trim($lokasi_parts[0]) . ',' . trim($lokasi_parts[1]);

            DB::beginTransaction();

            try {
                // Cek apakah sudah ada presensi hari ini
                $presensi = PrensensiFace::where('nik', $nik)
                    ->where('tanggal', $tanggal)
                    ->first();

                if ($presensi) {
                    // Sudah ada, update jam pulang
                    if (!empty($presensi->jam_pulang)) {
                        DB::rollBack();
                        return response("error|Anda sudah melakukan absen pulang hari ini|out", 200);
                    }

                    $presensi->update([
                        'jam_pulang' => $jam,
                        'lokasi' => $lokasi_cabang, // ✅ Lokasi dari cabang
                    ]);

                    Log::info('Simple Face Presensi OUT Success', [
                        'id' => $presensi->id,
                        'nik' => $nik,
                        'nama' => $nama_lengkap,
                        'jam_pulang' => $jam,
                        'lokasi' => $lokasi_cabang
                    ]);

                    DB::commit();
                    return response("success|Absen Pulang Berhasil! ✅ {$jam}|out", 200);
                } else {
                    // Belum ada, buat baru dengan jam masuk
                    $presensi = PrensensiFace::create([
                        'nik' => $nik,
                        'tanggal' => $tanggal,
                        'jam_masuk' => $jam,
                        'jam_pulang' => null,
                        'lokasi' => $lokasi_cabang, // ✅ Lokasi dari cabang
                        'status' => 'verified'
                    ]);

                    Log::info('Simple Face Presensi IN Success', [
                        'id' => $presensi->id,
                        'nik' => $nik,
                        'nama' => $nama_lengkap,
                        'jam_masuk' => $jam,
                        'lokasi' => $lokasi_cabang
                    ]);

                    DB::commit();
                    return response("success|Absen Masuk Berhasil! ✅ {$jam}|in", 200);
                }
            } catch (Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('SimpleFace Store Error: ' . $e->getMessage(), [
                'nik' => $nik ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);

            return response("error|Terjadi kesalahan sistem. Silakan coba lagi|system", 200);
        }
    }

    /**
     * Enrollment Khusus Simple Face
     */
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

    /**
     * Store Enrollment
     */
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

            $faceData = DB::table('face_data')->updateOrInsert(
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

            Log::info('Simple Face Enrollment Success', ['nik' => $nik]);

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

    /**
     * Get Descriptor untuk Verifikasi
     */
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

    /**
     * Delete Enrollment
     */
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
