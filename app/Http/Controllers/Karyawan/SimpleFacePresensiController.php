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

    /**
     * ✅ Get nama hari dalam bahasa Indonesia (lowercase for database)
     */
    private function getHari($hari)
    {
        $namaHari = [
            'Sun' => 'minggu',
            'Mon' => 'senin',
            'Tue' => 'selasa',
            'Wed' => 'rabu',
            'Thu' => 'kamis',
            'Fri' => 'jumat',
            'Sat' => 'sabtu'
        ];

        return $namaHari[$hari] ?? 'minggu';
    }

    /**
     * ✅ Menghitung jarak antara dua koordinat (Haversine formula)
     */
    private function distance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) +
            (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;

        return compact('meters');
    }

    /**
     * ✅ Get jam kerja karyawan berdasarkan cabang dan departemen
     */
    private function getJamKerja($kode_cabang, $kode_dept, $namahari)
    {
        $jamkerja = DB::table('konfigurasi_jk_dept_detail')
            ->select('jam_kerja.*')
            ->join('konfigurasi_jk_dept', 'konfigurasi_jk_dept_detail.kode_jk_dept', '=', 'konfigurasi_jk_dept.kode_jk_dept')
            ->join('jam_kerja', 'konfigurasi_jk_dept_detail.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->where('konfigurasi_jk_dept.kode_cabang', $kode_cabang)
            ->where('konfigurasi_jk_dept.kode_dept', $kode_dept)
            ->where('konfigurasi_jk_dept_detail.hari', $namahari)
            ->first();

        return $jamkerja;
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

            // ✅ MULTI-SHIFT DETECTION
            $jam_kerja = $karyawan_model->getJamKerjaHariIni();
            $is_multi_shift = false;
            $shifts_available = collect();
            $total_shifts = 0;

            Log::info('Dashboard - Jam Kerja Check', [
                'nik' => $nik,
                'jam_kerja_found' => $jam_kerja ? 'YES' : 'NO',
                'tipe_jam_kerja' => $jam_kerja->tipe_jam_kerja ?? 'NULL'
            ]);

            if ($jam_kerja) {
                if ($jam_kerja->tipe_jam_kerja === 'multi_shift') {
                    $shifts_available = DB::table('jam_kerja_shifts')
                        ->where('kode_jam_kerja', $jam_kerja->kode_jam_kerja)
                        ->where('is_active', true)
                        ->orderBy('shift_ke')
                        ->get();

                    $total_shifts = $shifts_available->count();
                    $is_multi_shift = $total_shifts >= 2;
                }
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
                'nama_lengkap',
                'faceData',
                'presensi_hari_ini',
                'histori',
                'statistik',
                'is_multi_shift',
                'shifts_available',
                'total_shifts',
                'jam_kerja',
                'completed_shifts',
                'regular_done'
            ));
        } catch (Exception $e) {
            Log::error('SimpleFace Dashboard Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan.');
        }
    }

    public function create(Request $request)
    {
        try {
            $karyawan = Auth::guard('karyawan')->user();
            $nik = $karyawan->nik;
            $kode_cabang = $karyawan->kode_cabang;
            $kode_dept = $karyawan->kode_dept;
            $nama_lengkap = $karyawan->nama_lengkap;

            $hariini = Carbon::now('Asia/Jakarta')->format('Y-m-d');
            $jamsekarang = Carbon::now('Asia/Jakarta')->format('H:i');

            // Check face data
            $faceData = DB::table('face_data')
                ->where('nik', $nik)
                ->where('status', 'active')
                ->first();

            if (!$faceData) {
                return redirect()->route('face-presensi.enrollment')
                    ->with('info', 'Silakan daftarkan wajah Anda terlebih dahulu.');
            }

            // ✅ Get lokasi kantor
            $lok_kantor = DB::table('cabang')
                ->where('kode_cabang', $kode_cabang)
                ->first();

            if (!$lok_kantor || empty($lok_kantor->lokasi_cabang)) {
                return redirect()->route('face-presensi.dashboard')
                    ->with('error', 'Data lokasi cabang tidak ditemukan. Hubungi admin.');
            }

            // ✅ Get jam kerja untuk hari ini
            $namahari = $this->getHari(date("D", strtotime($hariini)));
            $jamkerja = $this->getJamKerja($kode_cabang, $kode_dept, $namahari);

            if (!$jamkerja) {
                Log::warning('Jam kerja tidak ditemukan', [
                    'nik' => $nik,
                    'cabang' => $kode_cabang,
                    'dept' => $kode_dept,
                    'hari' => $namahari
                ]);

                return view('karyawan.simple-face.notifjadwal', [
                    'hari' => ucfirst($namahari),
                    'nik' => $nik,
                    'nama' => $nama_lengkap
                ]);
            }

            // ✅ MULTI-SHIFT DETECTION
            $is_multi_shift = false;
            $shifts_available = collect();
            $shift_ke = null;
            $current_shift = null;

            if ($jamkerja->tipe_jam_kerja === 'multi_shift') {
                $shifts_available = DB::table('jam_kerja_shifts')
                    ->where('kode_jam_kerja', $jamkerja->kode_jam_kerja)
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

            // ✅ Get presensi hari ini
            $presensi_hari_ini = PresensiFace::where('nik', $nik)
                ->where('tanggal', $hariini)
                ->orderBy('shift_ke')
                ->get();

            return view('karyawan.simple-face.create', compact(
                'nama_lengkap',
                'faceData',
                'lok_kantor',
                'jamkerja',
                'hariini',
                'namahari',
                'is_multi_shift',
                'shifts_available',
                'shift_ke',
                'current_shift',
                'presensi_hari_ini'
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
            $kode_dept = $karyawan->kode_dept;

            $tanggal = Carbon::now('Asia/Jakarta')->format('Y-m-d');
            $jam = Carbon::now('Asia/Jakarta')->format('H:i:s');
            $jamsekarang = Carbon::now('Asia/Jakarta')->format('H:i');

            Log::info('=== FACE PRESENSI STORE STARTED ===', [
                'nik' => $nik,
                'tanggal' => $tanggal,
                'jam' => $jam,
                'request' => $request->all()
            ]);

            // ✅ VALIDATION 1: Face verification
            if (!$request->verified || $request->verified !== 'true') {
                return response("error|Presensi harus menggunakan verifikasi wajah|system", 200);
            }

            // ✅ VALIDATION 2: Lokasi
            if (empty($request->lokasi)) {
                Log::warning('Lokasi tidak terdeteksi', ['nik' => $nik]);
                return response("error|Lokasi tidak terdeteksi. Aktifkan GPS Anda|system", 200);
            }

            // ✅ VALIDATION 3: Get lokasi kantor
            $lok_kantor = DB::table('cabang')->where('kode_cabang', $kode_cabang)->first();

            if (!$lok_kantor || empty($lok_kantor->lokasi_cabang)) {
                Log::error('Cabang not found', ['kode_cabang' => $kode_cabang]);
                return response("error|Data cabang tidak ditemukan|system", 200);
            }

            // ✅ VALIDATION 4: Parse dan validasi lokasi kantor
            $lok = explode(",", $lok_kantor->lokasi_cabang);
            if (count($lok) < 2) {
                Log::error('Invalid cabang location format', ['lokasi' => $lok_kantor->lokasi_cabang]);
                return response("error|Format lokasi kantor tidak valid|system", 200);
            }

            $latitudekantor = trim($lok[0]);
            $longitudekantor = trim($lok[1]);

            // ✅ VALIDATION 5: Parse dan validasi lokasi user
            $lokasi = $request->lokasi;
            $lokasiuser = explode(",", $lokasi);
            if (count($lokasiuser) < 2) {
                Log::warning('Invalid user location format', ['lokasi' => $lokasi]);
                return response("error|Format lokasi tidak valid|system", 200);
            }

            $latitudeuser = trim($lokasiuser[0]);
            $longitudeuser = trim($lokasiuser[1]);

            // ✅ VALIDATION 6: Hitung dan validasi jarak (RADIUS)
            $jarak = $this->distance($latitudekantor, $longitudekantor, $latitudeuser, $longitudeuser);
            $radius = round($jarak["meters"]);

            Log::info('Distance calculated', [
                'nik' => $nik,
                'radius' => $radius,
                'max_radius' => $lok_kantor->radius_cabang
            ]);

            if ($radius > $lok_kantor->radius_cabang) {
                Log::warning('Outside radius', [
                    'nik' => $nik,
                    'distance' => $radius,
                    'max' => $lok_kantor->radius_cabang
                ]);
                return response("error|Maaf, Anda berada diluar radius kantor. Jarak Anda: {$radius}m dari kantor (Max: {$lok_kantor->radius_cabang}m)|radius", 200);
            }

            // ✅ VALIDATION 7: Get jam kerja
            $namahari = $this->getHari(date("D", strtotime($tanggal)));
            $jamkerja = $this->getJamKerja($kode_cabang, $kode_dept, $namahari);

            if (!$jamkerja) {
                Log::error('Jam kerja not found', [
                    'nik' => $nik,
                    'cabang' => $kode_cabang,
                    'dept' => $kode_dept,
                    'hari' => $namahari
                ]);
                return response("error|Jam kerja tidak ditemukan untuk hari ini|system", 200);
            }

            // ✅ GET SHIFT INFO
            $shift_ke = $request->filled('shift_ke') ? $request->shift_ke : null;
            $nama_shift = null;

            DB::beginTransaction();

            try {
                if ($jamkerja->tipe_jam_kerja === 'multi_shift') {
                    // ✅ MULTI-SHIFT MODE
                    if (!$shift_ke) {
                        DB::rollBack();
                        return response("error|Pilih shift terlebih dahulu|system", 200);
                    }

                    $shift_data = DB::table('jam_kerja_shifts')
                        ->where('kode_jam_kerja', $jamkerja->kode_jam_kerja)
                        ->where('shift_ke', $shift_ke)
                        ->where('is_active', true)
                        ->first();

                    if (!$shift_data) {
                        DB::rollBack();
                        return response("error|Shift tidak valid|system", 200);
                    }

                    $nama_shift = $shift_data->nama_shift;

                    Log::info('Multi-Shift Processing', [
                        'nik' => $nik,
                        'shift_ke' => $shift_ke,
                        'nama_shift' => $nama_shift,
                        'jam_masuk_shift' => $shift_data->jam_masuk,
                        'jam_pulang_shift' => $shift_data->jam_pulang
                    ]);

                    // Check presensi untuk shift ini
                    $presensi = PresensiFace::where('nik', $nik)
                        ->where('tanggal', $tanggal)
                        ->where('shift_ke', $shift_ke)
                        ->first();

                    $ket = $presensi ? "out" : "in";

                    // ✅ VALIDATION 8: Validasi waktu untuk IN
                    if ($ket == "in") {
                        // Toleransi 60 menit sebelum jam masuk
                        $jam_masuk_shift = Carbon::parse($tanggal . ' ' . $shift_data->jam_masuk);
                        $toleransi_masuk = $jam_masuk_shift->copy()->subMinutes(60);
                        $jam_sekarang_full = Carbon::parse($tanggal . ' ' . $jamsekarang);

                        if ($jam_sekarang_full->lt($toleransi_masuk)) {
                            DB::rollBack();
                            $waktu_buka = $toleransi_masuk->format('H:i');
                            Log::warning('Check-in too early for shift', [
                                'nik' => $nik,
                                'shift_ke' => $shift_ke,
                                'current' => $jamsekarang,
                                'earliest' => $waktu_buka
                            ]);
                            return response("error|Belum waktunya absen masuk untuk {$nama_shift}. Mulai jam: {$waktu_buka}|in", 200);
                        }
                    }

                    // ✅ VALIDATION 9: Validasi waktu untuk OUT
                    if ($ket == "out") {
                        $jam_pulang_shift = Carbon::parse($tanggal . ' ' . $shift_data->jam_pulang);
                        $jam_sekarang_full = Carbon::parse($tanggal . ' ' . $jamsekarang);

                        if ($jam_sekarang_full->lt($jam_pulang_shift)) {
                            DB::rollBack();
                            $waktu_pulang = $jam_pulang_shift->format('H:i');
                            Log::warning('Check-out too early for shift', [
                                'nik' => $nik,
                                'shift_ke' => $shift_ke,
                                'current' => $jamsekarang,
                                'required' => $waktu_pulang
                            ]);
                            return response("error|Belum waktunya absen pulang untuk {$nama_shift}. Jam pulang: {$waktu_pulang}|out", 200);
                        }

                        if (!empty($presensi->jam_pulang)) {
                            DB::rollBack();
                            Log::warning('Already checked out', ['nik' => $nik, 'shift_ke' => $shift_ke]);
                            return response("error|Anda sudah absen pulang untuk {$nama_shift}|out", 200);
                        }
                    }

                    // ✅ SAVE PRESENSI MULTI-SHIFT
                    if ($ket == "in") {
                        PresensiFace::create([
                            'nik' => $nik,
                            'tanggal' => $tanggal,
                            'jam_masuk' => $jam,
                            'jam_pulang' => null,
                            'lokasi' => $lokasi,
                            'status' => 'verified',
                            'shift_ke' => $shift_ke,
                            'nama_shift' => $nama_shift
                        ]);

                        DB::commit();
                        Log::info('Multi-Shift Check-in Success', ['nik' => $nik, 'shift_ke' => $shift_ke]);
                        return response("success|Absen Masuk {$nama_shift} Berhasil! ✅ {$jam}|in", 200);
                    } else {
                        $presensi->update([
                            'jam_pulang' => $jam,
                            'lokasi' => $lokasi,
                        ]);

                        DB::commit();
                        Log::info('Multi-Shift Check-out Success', ['nik' => $nik, 'shift_ke' => $shift_ke]);
                        return response("success|Absen Pulang {$nama_shift} Berhasil! ✅ {$jam}|out", 200);
                    }
                } else {
                    // ✅ REGULAR MODE
                    $presensi = PresensiFace::where('nik', $nik)
                        ->where('tanggal', $tanggal)
                        ->whereNull('shift_ke')
                        ->first();

                    $ket = $presensi ? "out" : "in";

                    // ✅ VALIDATION 8: Validasi waktu untuk IN
                    if ($ket == "in") {
                        // Toleransi 60 menit sebelum jam masuk
                        $jam_masuk_kerja = Carbon::parse($tanggal . ' ' . $jamkerja->jam_masuk);
                        $toleransi_masuk = $jam_masuk_kerja->copy()->subMinutes(60);
                        $jam_sekarang_full = Carbon::parse($tanggal . ' ' . $jamsekarang);

                        if ($jam_sekarang_full->lt($toleransi_masuk)) {
                            DB::rollBack();
                            $waktu_buka = $toleransi_masuk->format('H:i');
                            Log::warning('Check-in too early', [
                                'nik' => $nik,
                                'current' => $jamsekarang,
                                'earliest' => $waktu_buka
                            ]);
                            return response("error|Belum waktunya absen masuk. Mulai jam: {$waktu_buka}|in", 200);
                        }
                    }

                    // ✅ VALIDATION 9: Validasi waktu untuk OUT
                    if ($ket == "out") {
                        $jam_pulang_kerja = Carbon::parse($tanggal . ' ' . $jamkerja->jam_pulang);
                        $jam_sekarang_full = Carbon::parse($tanggal . ' ' . $jamsekarang);

                        if ($jam_pulang_kerja->lt($jam_sekarang_full)) {
                            DB::rollBack();
                            $waktu_pulang = $jam_pulang_kerja->format('H:i');
                            Log::warning('Check-out too early', [
                                'nik' => $nik,
                                'current' => $jamsekarang,
                                'required' => $waktu_pulang
                            ]);
                            return response("error|Belum waktunya absen pulang. Jam pulang: {$waktu_pulang}|out", 200);
                        }

                        if (!empty($presensi->jam_pulang)) {
                            DB::rollBack();
                            Log::warning('Already checked out', ['nik' => $nik]);
                            return response("error|Anda sudah absen pulang hari ini|out", 200);
                        }
                    }

                    // ✅ SAVE PRESENSI REGULAR
                    if ($ket == "in") {
                        PresensiFace::create([
                            'nik' => $nik,
                            'tanggal' => $tanggal,
                            'jam_masuk' => $jam,
                            'jam_pulang' => null,
                            'lokasi' => $lokasi,
                            'status' => 'verified'
                        ]);

                        DB::commit();
                        Log::info('Regular Check-in Success', ['nik' => $nik]);
                        return response("success|Absen Masuk Berhasil! ✅ {$jam}|in", 200);
                    } else {
                        $presensi->update([
                            'jam_pulang' => $jam,
                            'lokasi' => $lokasi,
                        ]);

                        DB::commit();
                        Log::info('Regular Check-out Success', ['nik' => $nik]);
                        return response("success|Absen Pulang Berhasil! ✅ {$jam}|out", 200);
                    }
                }
            } catch (Exception $e) {
                DB::rollBack();
                Log::error('Transaction Error: ' . $e->getMessage());
                return response("error|Terjadi kesalahan saat menyimpan presensi|system", 200);
            }
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Face Presensi Store Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response("error|Terjadi kesalahan sistem: {$e->getMessage()}|system", 200);
        }
    }

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

    /**
     * View face image
     */
    public function viewImage()
    {
        try {
            $nik = Auth::guard('karyawan')->user()->nik;
            $faceData = DB::table('face_data')->where('nik', $nik)->where('status', 'active')->first();

            if (!$faceData || !$faceData->face_image) {
                abort(404, 'Gambar tidak ditemukan');
            }

            $path = storage_path('app/public/uploads/faces/' . $faceData->face_image);

            if (!file_exists($path)) {
                abort(404, 'File gambar tidak ditemukan');
            }

            return response()->file($path);
        } catch (\Exception $e) {
            Log::error('SimpleFacePresensiController@viewImage Error: ' . $e->getMessage());
            abort(404);
        }
    }
}
