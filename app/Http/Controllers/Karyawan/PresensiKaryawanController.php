<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PresensiKaryawanController extends Controller
{
    /**
     * Constructor - Middleware auth karyawan
     */
    public function __construct()
    {
        $this->middleware('auth:karyawan');
    }

    /**
     * Get nama hari dalam bahasa Indonesia
     */
    private function getHari($hari)
    {
        $namaHari = [
            'Sun' => 'Minggu',
            'Mon' => 'Senin',
            'Tue' => 'Selasa',
            'Wed' => 'Rabu',
            'Thu' => 'Kamis',
            'Fri' => 'Jumat',
            'Sat' => 'Sabtu'
        ];

        return $namaHari[$hari] ?? 'Tidak diketahui';
    }

    /**
     * Menghitung jarak antara dua koordinat (Haversine formula)
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
     * Get jam kerja karyawan berdasarkan cabang dan departemen
     */
    private function getJamKerja($kode_cabang, $kode_dept, $namahari)
    {
        // Jam kerja departemen di cabang tertentu
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

    /**
     * Tampilkan halaman presensi
     */
    public function create()
    {
        try {
            $nik = Auth::guard('karyawan')->user()->nik;
            $kode_dept = Auth::guard('karyawan')->user()->kode_dept;
            $kode_cabang = Auth::guard('karyawan')->user()->kode_cabang;
            $nama_lengkap = Auth::guard('karyawan')->user()->nama_lengkap;

            $hariini = Carbon::now('Asia/Jakarta')->format('Y-m-d');
            $jamsekarang = Carbon::now('Asia/Jakarta')->format('H:i');

            // 1. Wajib memiliki data wajah
            $faceData = DB::table('face_data')
                ->where('nik', $nik)
                ->where('status', 'active')
                ->first();

            if (!$faceData) {
                return redirect('/face/enrollment')->with('error', 'Anda wajib mendaftarkan wajah (Face ID) sebelum bisa absen.');
            }

            Log::info('Presensi Create Access', [
                'nik' => $nik,
                'kode_cabang' => $kode_cabang,
                'kode_dept' => $kode_dept,
                'tanggal' => $hariini,
                'jam' => $jamsekarang
            ]);

            // Check presensi lintas hari
            $tgl_sebelumnya = Carbon::now('Asia/Jakarta')->subDay()->format('Y-m-d');
            $cekpresensi_sebelumnya = DB::table('presensi')
                ->join('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
                ->where('tgl_presensi', $tgl_sebelumnya)
                ->where('nik', $nik)
                ->first();

            $ceklintashari = $cekpresensi_sebelumnya != null ? $cekpresensi_sebelumnya->lintashari : 0;

            // Jika shift lintas hari dan sekarang masih di bawah jam 08:00, gunakan tanggal kemarin
            if ($ceklintashari == 1 && $jamsekarang < "08:00") {
                $hariini = $tgl_sebelumnya;
            }

            // Get nama hari
            $namahari = $this->getHari(date("D", strtotime($hariini)));

            // Cek apakah sudah presensi hari ini
            $presensi_hari_ini = DB::table('presensi')
                ->where('tgl_presensi', $hariini)
                ->where('nik', $nik)
                ->first();

            $cek = $presensi_hari_ini ? 1 : 0;

            // Get lokasi kantor
            $lok_kantor = DB::table('cabang')
                ->where('kode_cabang', $kode_cabang)
                ->first();

            if (!$lok_kantor) {
                Log::error('Cabang not found', ['kode_cabang' => $kode_cabang]);
                return redirect('/dashboard')->with('error', 'Data cabang tidak ditemukan. Hubungi admin.');
            }

            // Get jam kerja berdasarkan cabang dan departemen
            $jamkerja = $this->getJamKerja($kode_cabang, $kode_dept, $namahari);

            // Jika masih tidak ada jam kerja
            if ($jamkerja == null) {
                Log::warning('Jam kerja tidak ditemukan', [
                    'nik' => $nik,
                    'cabang' => $kode_cabang,
                    'dept' => $kode_dept,
                    'hari' => $namahari
                ]);

                return view('karyawan.presensi.notifjadwal', [
                    'hari' => $namahari,
                    'nik' => $nik,
                    'nama' => $nama_lengkap
                ]);
            }

            // MULTI-SHIFT DETECTION
            $is_multi_shift = false;
            $shifts_available = collect();
            $shift_ke = request()->get('shift_ke', 1);
            $current_shift = null;

            if (isset($jamkerja->tipe_jam_kerja) && $jamkerja->tipe_jam_kerja === 'multi_shift') {
                $shifts_available = DB::table('jam_kerja_shifts')
                    ->where('kode_jam_kerja', $jamkerja->kode_jam_kerja)
                    ->where('is_active', true)
                    ->orderBy('shift_ke')
                    ->get();
                
                $is_multi_shift = $shifts_available->count() > 1;

                // Tentukan shift saat ini berdasarkan request atau default 1
                $current_shift = $shifts_available->where('shift_ke', $shift_ke)->first();
            }

            Log::info('Jam kerja ditemukan', [
                'nik' => $nik,
                'kode_jam_kerja' => $jamkerja->kode_jam_kerja,
                'nama_jam_kerja' => $jamkerja->nama_jam_kerja,
                'is_multi_shift' => $is_multi_shift,
                'shift_ke' => $shift_ke
            ]);

            // Ulangi cek presensi jika ini multi-shift (cek per shift_ke)
            if ($is_multi_shift) {
                $presensi_hari_ini = DB::table('presensi')
                    ->where('tgl_presensi', $hariini)
                    ->where('nik', $nik)
                    ->where('shift_ke', $shift_ke)
                    ->first();
                $cek = $presensi_hari_ini ? 1 : 0;
            }

            return view('karyawan.presensi.create', compact(
                'cek',
                'lok_kantor',
                'jamkerja',
                'hariini',
                'namahari',
                'presensi_hari_ini',
                'nama_lengkap',
                'faceData',
                'is_multi_shift',
                'shifts_available',
                'shift_ke',
                'current_shift'
            ));
        } catch (Exception $e) {
            Log::error('PresensiKaryawan@create Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect('/dashboard')->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }

    /**
     * Simpan data presensi (masuk/pulang)
     */
    public function store(Request $request)
    {
        try {
            $nik = Auth::guard('karyawan')->user()->nik;
            $nama_lengkap = Auth::guard('karyawan')->user()->nama_lengkap;
            $kode_dept = Auth::guard('karyawan')->user()->kode_dept;
            $kode_cabang = Auth::guard('karyawan')->user()->kode_cabang;

            $hariini = Carbon::now('Asia/Jakarta')->format('Y-m-d');
            $jam = Carbon::now('Asia/Jakarta')->format('H:i:s');
            $jamsekarang = Carbon::now('Asia/Jakarta')->format('H:i');

            Log::info('Presensi Store Started', [
                'nik' => $nik,
                'kode_cabang' => $kode_cabang,
                'kode_dept' => $kode_dept,
                'tanggal' => $hariini,
                'jam' => $jam
            ]);

            // Validasi input
            if (empty($request->lokasi)) {
                Log::warning('Lokasi tidak terdeteksi', ['nik' => $nik]);
                return response("error|Lokasi tidak terdeteksi. Aktifkan GPS Anda|system", 200);
            }

            if (empty($request->image)) {
                Log::warning('Foto tidak terdeteksi', ['nik' => $nik]);
                return response("error|Foto tidak terdeteksi. Izinkan akses kamera|system", 200);
            }

            // Check lintas hari
            $tgl_sebelumnya = Carbon::now('Asia/Jakarta')->subDay()->format('Y-m-d');
            $cekpresensi_sebelumnya = DB::table('presensi')
                ->join('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
                ->where('tgl_presensi', $tgl_sebelumnya)
                ->where('nik', $nik)
                ->first();

            $ceklintashari = $cekpresensi_sebelumnya != null ? $cekpresensi_sebelumnya->lintashari : 0;
            $tgl_presensi = ($ceklintashari == 1 && $jamsekarang < "08:00") ? $tgl_sebelumnya : $hariini;

            // Parse lokasi user
            $lokasi = $request->lokasi;
            $lokasiuser = explode(",", $lokasi);
            if (count($lokasiuser) < 2) {
                Log::warning('Invalid user location format', ['lokasi' => $lokasi]);
                return response("error|Format lokasi tidak valid|system", 200);
            }

            $latitudeuser = trim($lokasiuser[0]);
            $longitudeuser = trim($lokasiuser[1]);

            // Geofencing — hanya periksa cabang milik karyawan sendiri
            $lok_kantor = DB::table('cabang')->where('kode_cabang', $kode_cabang)->first();

            if (!$lok_kantor) {
                Log::error('Cabang karyawan tidak ditemukan', [
                    'nik'         => $nik,
                    'kode_cabang' => $kode_cabang
                ]);
                return response("error|Data cabang Anda tidak ditemukan. Hubungi admin.|system", 200);
            }

            $lok_split = explode(",", $lok_kantor->lokasi_cabang);
            if (count($lok_split) < 2) {
                Log::error('Format lokasi cabang tidak valid', [
                    'nik'          => $nik,
                    'kode_cabang'  => $kode_cabang,
                    'lokasi_cabang'=> $lok_kantor->lokasi_cabang
                ]);
                return response("error|Data lokasi cabang tidak valid. Hubungi admin.|system", 200);
            }

            $latitudekantor  = trim($lok_split[0]);
            $longitudekantor = trim($lok_split[1]);
            $jarak           = $this->distance($latitudekantor, $longitudekantor, $latitudeuser, $longitudeuser);
            $radius          = round($jarak['meters']);

            Log::info('Geofencing check', [
                'nik'          => $nik,
                'kode_cabang'  => $kode_cabang,
                'nama_cabang'  => $lok_kantor->nama_cabang,
                'jarak_meter'  => $radius,
                'radius_izin'  => $lok_kantor->radius_cabang
            ]);

            if ($radius > $lok_kantor->radius_cabang) {
                Log::warning('Outside branch radius', [
                    'nik'         => $nik,
                    'kode_cabang' => $kode_cabang,
                    'jarak'       => $radius,
                    'radius_izin' => $lok_kantor->radius_cabang
                ]);
                return response("error|Anda berada di luar radius kantor {$lok_kantor->nama_cabang} ({$radius}m dari {$lok_kantor->radius_cabang}m yang diizinkan).|radius", 200);
            }

            // Get jam kerja berdasarkan cabang dan departemen
            $namahari = $this->getHari(date("D", strtotime($tgl_presensi)));
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

            Log::info('Jam kerja found for presensi', [
                'nik' => $nik,
                'kode_jam_kerja' => $jamkerja->kode_jam_kerja,
                'nama_jam_kerja' => $jamkerja->nama_jam_kerja
            ]);

            // Validasi Face Verification (Server-Side)
            $is_face_verified = $request->filled('verified') && $request->verified == 'true';
            if (!$is_face_verified) {
                return response("error|Face Verification diwajibkan! Silakan gunakan tombol Absen + Verifikasi Wajah.|system", 200);
            }

            // Menerima descriptor dari client
            $client_descriptor_json = $request->input('face_descriptor');
            if (!$client_descriptor_json) {
                return response("error|Data wajah tidak lengkap. Pastikan kamera mendeteksi wajah dengan jelas.|system", 200);
            }

            $client_descriptor = json_decode($client_descriptor_json, true);
            
            // Ambil referensi wajah dari database
            $faceData = \App\Models\FaceData::where('nik', $nik)->where('status', 'active')->first();
            
            if (!$faceData) {
                return response("error|Data wajah referensi tidak ditemukan. Silakan hubungi admin.|system", 200);
            }

            $reference_descriptor = json_decode($faceData->face_descriptor, true);

            // Kalkulasi Euclidean Distance
            if (is_array($client_descriptor) && is_array($reference_descriptor) && count($client_descriptor) === count($reference_descriptor)) {
                $sum = 0;
                for ($i = 0; $i < count($client_descriptor); $i++) {
                    $sum += pow($client_descriptor[$i] - $reference_descriptor[$i], 2);
                }
                $distance = sqrt($sum);

                // Threshold jarak wajah
                $threshold = 0.60; 
                
                Log::info('Server-side face verification', [
                    'nik' => $nik,
                    'distance' => $distance,
                    'threshold' => $threshold
                ]);

                if ($distance > $threshold) {
                    Log::warning('Face spoofing attempt detected', ['nik' => $nik, 'distance' => $distance]);
                    return response("error|Verifikasi wajah gagal! Identitas tidak cocok.|system", 200);
                }
            } else {
                return response("error|Format data wajah tidak valid.|system", 200);
            }

            // Menerima parameter multi-shift
            $shift_ke = $request->input('shift_ke');
            $shift_nama = $request->input('shift_nama');
            $shift_jam_masuk = $request->input('shift_jam_masuk');
            $shift_jam_pulang = $request->input('shift_jam_pulang');
            $is_multi_shift = !empty($shift_ke) && !empty($shift_nama);

            if ($is_multi_shift) {
                // Gunakan jam dari shift, set toleransi awal/akhir secara dinamis
                $jam_masuk_ref = $shift_jam_masuk;
                $jam_pulang_ref = $shift_jam_pulang;
                $awal_masuk_ref = Carbon::parse($shift_jam_masuk)->subMinutes(60)->format('H:i:s');
                $akhir_masuk_ref = Carbon::parse($shift_jam_masuk)->addMinutes(120)->format('H:i:s');
            } else {
                $jam_masuk_ref = $jamkerja->jam_masuk;
                $jam_pulang_ref = $jamkerja->jam_pulang;
                $awal_masuk_ref = $jamkerja->awal_jam_masuk;
                $akhir_masuk_ref = $jamkerja->akhir_jam_masuk;
            }

            // Check apakah sudah presensi
            $query_presensi = DB::table('presensi')
                ->where('tgl_presensi', $tgl_presensi)
                ->where('nik', $nik);

            if ($is_multi_shift) {
                $query_presensi->where('shift_ke', $shift_ke);
            } else {
                $query_presensi->whereNull('shift_ke');
            }

            $cek = $query_presensi->count();
            $datapresensi = $query_presensi->first();

            // Tentukan tipe presensi (in/out)
            $ket = $cek > 0 ? "out" : "in";

            // Disable physical photo saving for storage optimization
            // The identity is already verified by face-api on the client side
            $fileName = "face_api";
            // $file = $folderPath . $fileName;

            // Proses presensi
            DB::beginTransaction();

            if ($cek > 0) {
                // PRESENSI PULANG
                $tgl_pulang = $ceklintashari == 1
                    ? Carbon::parse($tgl_presensi)->addDay()->format('Y-m-d')
                    : $tgl_presensi;
                $jam_pulang = $hariini . " " . $jam;
                $jamkerja_pulang = $tgl_pulang . " " . $jamkerja->jam_pulang;

                Log::info('Attempting check-out', [
                    'nik' => $nik,
                    'jam_pulang' => $jam_pulang,
                    'jamkerja_pulang' => $jamkerja_pulang
                ]);

                // Cek Libur Nasional
                $isLibur = \App\Models\HariLibur::where('tanggal', $tgl_presensi)->exists();

                if (!$isLibur && $jam_pulang < $jamkerja_pulang) {
                    DB::rollBack();
                    $waktu_pulang = date('H:i', strtotime($jam_pulang_ref));
                    Log::warning('Check-out too early', [
                        'nik' => $nik,
                        'current' => $jam_pulang,
                        'required' => $jamkerja_pulang
                    ]);
                    return response("error|Belum waktunya absen pulang. Jam pulang: {$waktu_pulang}|out", 200);
                }

                if (!empty($datapresensi->jam_out)) {
                    DB::rollBack();
                    Log::warning('Already checked out', ['nik' => $nik]);
                    $nama_shift_msg = $is_multi_shift ? " untuk {$shift_nama}" : "";
                    return response("error|Anda sudah melakukan absen pulang sebelumnya{$nama_shift_msg}|out", 200);
                }

                $data_pulang = [
                    'jam_out'    => $jam,
                    'foto_out'   => $fileName,
                    'lokasi_out' => $lokasi,
                    'updated_at' => Carbon::now('Asia/Jakarta')
                ];

                $query_update = DB::table('presensi')
                    ->where('tgl_presensi', $tgl_presensi)
                    ->where('nik', $nik);

                // Filter per shift jika multi-shift
                if ($is_multi_shift) {
                    $query_update->where('shift_ke', $shift_ke);
                } else {
                    $query_update->whereNull('shift_ke');
                }

                $update = $query_update->update($data_pulang);

                if ($update) {
                    // Storage::put($file, $image_base64); // Disabled to save storage

                    Log::info('Check-out success', [
                        'nik' => $nik,
                        'nama' => $nama_lengkap,
                        'jam' => $jam
                    ]);

                    // Send notification (optional)
                    // $this->sendWhatsAppNotification($nik, "Presensi Pulang berhasil pada jam {$jam}", 'out');

                    DB::commit();
                    return response("success|Terima Kasih, Hati-Hati Dijalan!|out", 200);
                } else {
                    DB::rollBack();
                    Log::error('Failed to update check-out', ['nik' => $nik]);
                    return response("error|Gagal menyimpan data. Hubungi admin|out", 200);
                }
            } else {
                // PRESENSI MASUK
                Log::info('Attempting check-in', [
                    'nik' => $nik,
                    'jam' => $jam,
                    'awal_jam_masuk' => $jamkerja->awal_jam_masuk,
                    'jam_masuk' => $jamkerja->jam_masuk,
                    'akhir_jam_masuk' => $jamkerja->akhir_jam_masuk
                ]);

                // Konversi ke Carbon untuk perbandingan yang akurat
                $jam_sekarang = Carbon::createFromFormat('H:i:s', $jam, 'Asia/Jakarta');
                $awal_masuk = Carbon::createFromFormat('H:i:s', $awal_masuk_ref, 'Asia/Jakarta');
                $akhir_masuk = Carbon::createFromFormat('H:i:s', $akhir_masuk_ref, 'Asia/Jakarta');

                // Cek Libur Nasional
                $isLibur = \App\Models\HariLibur::where('tanggal', $tgl_presensi)->exists();

                // Validasi: Belum waktunya presensi (terlalu cepat)
                if (!$isLibur && $jam_sekarang->lt($awal_masuk)) {
                    DB::rollBack();
                    $waktu_mulai = Carbon::parse($awal_masuk_ref)->format('H:i');
                    Log::warning('Check-in too early', [
                        'nik' => $nik,
                        'jam_sekarang' => $jam,
                        'awal_jam_masuk' => $awal_masuk_ref
                    ]);
                    return response("error|Belum waktunya presensi. Awal jam masuk: {$waktu_mulai}|in", 200);
                }

                // Validasi: Waktu presensi sudah habis (terlalu telat)
                if (!$isLibur && $jam_sekarang->gt($akhir_masuk)) {
                    DB::rollBack();
                    $waktu_akhir = Carbon::parse($akhir_masuk_ref)->format('H:i');
                    Log::warning('Check-in too late', [
                        'nik' => $nik,
                        'jam_sekarang' => $jam,
                        'akhir_jam_masuk' => $akhir_masuk_ref
                    ]);
                    return response("error|Waktu presensi sudah habis. Akhir jam masuk: {$waktu_akhir}|in", 200);
                }

                $data = [
                    'nik' => $nik,
                    'tgl_presensi' => $tgl_presensi,
                    'jam_in' => $jam,
                    'foto_in' => $fileName,
                    'lokasi_in' => $lokasi,
                    'kode_jam_kerja' => $jamkerja->kode_jam_kerja,
                    'status' => 'h',
                    'shift_ke' => $is_multi_shift ? $shift_ke : null,
                    'nama_shift' => $is_multi_shift ? $shift_nama : null,
                    'created_at' => Carbon::now('Asia/Jakarta'),
                    'updated_at' => Carbon::now('Asia/Jakarta')
                ];

                $simpan = DB::table('presensi')->insert($data);

                if ($simpan) {
                    // Storage::put($file, $image_base64); // Disabled to save storage

                    Log::info('Check-in success', [
                        'nik' => $nik,
                        'nama' => $nama_lengkap,
                        'jam' => $jam,
                        'kode_jam_kerja' => $jamkerja->kode_jam_kerja
                    ]);

                    // Send notification (optional)
                    // $this->sendWhatsAppNotification($nik, "Presensi Masuk berhasil pada jam {$jam}", 'in');

                    DB::commit();
                    return response("success|Selamat Bekerja, {$nama_lengkap}!|in", 200);
                } else {
                    DB::rollBack();
                    Log::error('Failed to insert check-in', ['nik' => $nik]);
                    return response("error|Gagal menyimpan data. Hubungi admin|in", 200);
                }
            }
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('PresensiKaryawan@store Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            return response("error|Terjadi kesalahan sistem. Silakan coba lagi|system", 200);
        }
    }

    public function qrScan()
    {
        $hariini = date("Y-m-d");
        $nik = Auth::guard('karyawan')->user()->nik;
        
        $cek = DB::table('presensi')->where('tgl_presensi', $hariini)->where('nik', $nik)->count();
        $lok_kantor = DB::table('cabang')->where('kode_cabang', Auth::guard('karyawan')->user()->kode_cabang)->first();
        
        return view('karyawan.presensi.qr-scan', compact('cek', 'lok_kantor'));
    }

    public function storeQr(Request $request)
    {
        try {
            $qr_code = $request->qr_code;
            $nik = Auth::guard('karyawan')->user()->nik;

            // Dapatkan cabang karyawan saat ini
            $karyawan = \App\Models\Karyawan::with('cabang')->where('nik', $nik)->first();

            // Validasi apakah QR yang di-scan cocok dengan token cabang karyawan
            if (!$karyawan->cabang || $qr_code !== $karyawan->cabang->qr_token) {
                Log::warning('QR Code mismatch', [
                    'nik' => $nik,
                    'scanned_qr' => $qr_code,
                    'expected_token' => $karyawan->cabang ? $karyawan->cabang->qr_token : 'null'
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'QR Code tidak valid atau bukan dari cabang Anda'
                ], 400);
            }

            $tgl_presensi = date("Y-m-d");
            $jam = date("H:i:s");
            
            // Simplified check-in logic for QR
            $cek = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->count();
            
            if ($cek > 0) {
                // Pulang
                DB::table('presensi')
                    ->where('tgl_presensi', $tgl_presensi)
                    ->where('nik', $nik)
                    ->update([
                        'jam_out' => $jam,
                        'lokasi_out' => $request->lokasi ?? 'QR_SCAN',
                        'updated_at' => \Carbon\Carbon::now('Asia/Jakarta')
                    ]);
                return response()->json(['success' => true, 'message' => "Absen Pulang QR Berhasil!"]);
            } else {
                // Masuk (default jam kerja)
                $namahari = $this->getHari(date("D", strtotime($tgl_presensi)));
                $jamkerja = $this->getJamKerja($karyawan->kode_cabang, $karyawan->kode_dept, $namahari);

                if(!$jamkerja) {
                     $jamkerja = DB::table('jam_kerja')->first(); // fallback
                }

                DB::table('presensi')->insert([
                    'nik' => $nik,
                    'tgl_presensi' => $tgl_presensi,
                    'jam_in' => $jam,
                    'lokasi_in' => $request->lokasi ?? 'QR_SCAN',
                    'kode_jam_kerja' => $jamkerja ? $jamkerja->kode_jam_kerja : '01',
                    'status' => 'h',
                    'created_at' => \Carbon\Carbon::now('Asia/Jakarta'),
                    'updated_at' => \Carbon\Carbon::now('Asia/Jakarta')
                ]);
                return response()->json(['success' => true, 'message' => "Absen Masuk QR Berhasil!"]);
            }
            
        } catch (\Exception $e) {
            Log::error('QR Store Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem.']);
        }
    }
}
