<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Exception;

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

            Log::info('Jam kerja ditemukan', [
                'nik' => $nik,
                'kode_jam_kerja' => $jamkerja->kode_jam_kerja,
                'nama_jam_kerja' => $jamkerja->nama_jam_kerja
            ]);

            return view('karyawan.presensi.create', compact(
                'cek',
                'lok_kantor',
                'jamkerja',
                'hariini',
                'namahari',
                'presensi_hari_ini',
                'nama_lengkap'
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

            // Get lokasi kantor
            $lok_kantor = DB::table('cabang')->where('kode_cabang', $kode_cabang)->first();

            if (!$lok_kantor) {
                Log::error('Cabang not found', ['kode_cabang' => $kode_cabang]);
                return response("error|Data cabang tidak ditemukan|system", 200);
            }

            // Parse lokasi kantor
            $lok = explode(",", $lok_kantor->lokasi_cabang);
            if (count($lok) < 2) {
                Log::error('Invalid cabang location format', ['lokasi' => $lok_kantor->lokasi_cabang]);
                return response("error|Format lokasi kantor tidak valid|system", 200);
            }

            $latitudekantor = trim($lok[0]);
            $longitudekantor = trim($lok[1]);

            // Parse lokasi user
            $lokasi = $request->lokasi;
            $lokasiuser = explode(",", $lokasi);
            if (count($lokasiuser) < 2) {
                Log::warning('Invalid user location format', ['lokasi' => $lokasi]);
                return response("error|Format lokasi tidak valid|system", 200);
            }

            $latitudeuser = trim($lokasiuser[0]);
            $longitudeuser = trim($lokasiuser[1]);

            // Hitung jarak
            $jarak = $this->distance($latitudekantor, $longitudekantor, $latitudeuser, $longitudeuser);
            $radius = round($jarak["meters"]);

            Log::info('Distance calculated', [
                'nik' => $nik,
                'radius' => $radius,
                'max_radius' => $lok_kantor->radius_cabang
            ]);

            // Validasi radius
            if ($radius > $lok_kantor->radius_cabang) {
                Log::warning('Outside radius', [
                    'nik' => $nik,
                    'distance' => $radius,
                    'max' => $lok_kantor->radius_cabang
                ]);
                return response("error|Maaf, Anda berada diluar radius kantor. Jarak Anda: {$radius}m dari kantor (Max: {$lok_kantor->radius_cabang}m)|radius", 200);
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

            // Check apakah sudah presensi
            $presensi = DB::table('presensi')
                ->where('tgl_presensi', $tgl_presensi)
                ->where('nik', $nik);

            $cek = $presensi->count();
            $datapresensi = $presensi->first();

            // Tentukan tipe presensi (in/out)
            $ket = $cek > 0 ? "out" : "in";

            // Process image
            $image = $request->image;
            $folderPath = "public/uploads/absensi/";
            $formatName = $nik . "_" . $tgl_presensi . "_" . $ket;

            $image_parts = explode(";base64,", $image);
            if (count($image_parts) < 2) {
                Log::error('Invalid image format', ['nik' => $nik]);
                return response("error|Format gambar tidak valid|system", 200);
            }

            $image_base64 = base64_decode($image_parts[1]);
            $fileName = $formatName . ".png";
            $file = $folderPath . $fileName;

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

                if ($jam_pulang < $jamkerja_pulang) {
                    DB::rollBack();
                    $waktu_pulang = date('H:i', strtotime($jamkerja->jam_pulang));
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
                    return response("error|Anda sudah melakukan absen pulang sebelumnya|out", 200);
                }

                $data_pulang = [
                    'jam_out' => $jam,
                    'foto_out' => $fileName,
                    'lokasi_out' => $lokasi,
                    'updated_at' => Carbon::now('Asia/Jakarta')
                ];

                $update = DB::table('presensi')
                    ->where('tgl_presensi', $tgl_presensi)
                    ->where('nik', $nik)
                    ->update($data_pulang);

                if ($update) {
                    Storage::put($file, $image_base64);

                    Log::info('Check-out success', [
                        'nik' => $nik,
                        'nama' => $nama_lengkap,
                        'jam' => $jam
                    ]);

                    // Send notification (optional)
                    $this->sendWhatsAppNotification($nik, "Presensi Pulang berhasil pada jam {$jam}", 'out');

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
                $awal_masuk = Carbon::createFromFormat('H:i:s', $jamkerja->awal_jam_masuk, 'Asia/Jakarta');
                $akhir_masuk = Carbon::createFromFormat('H:i:s', $jamkerja->akhir_jam_masuk, 'Asia/Jakarta');

                // Validasi: Belum waktunya presensi (terlalu cepat)
                if ($jam_sekarang->lt($awal_masuk)) {
                    DB::rollBack();
                    $waktu_mulai = Carbon::parse($jamkerja->awal_jam_masuk)->format('H:i');
                    Log::warning('Check-in too early', [
                        'nik' => $nik,
                        'jam_sekarang' => $jam,
                        'awal_jam_masuk' => $jamkerja->awal_jam_masuk
                    ]);
                    return response("error|Belum waktunya presensi. Awal jam masuk: {$waktu_mulai}|in", 200);
                }

                // Validasi: Waktu presensi sudah habis (terlalu telat)
                if ($jam_sekarang->gt($akhir_masuk)) {
                    DB::rollBack();
                    $waktu_akhir = Carbon::parse($jamkerja->akhir_jam_masuk)->format('H:i');
                    Log::warning('Check-in too late', [
                        'nik' => $nik,
                        'jam_sekarang' => $jam,
                        'akhir_jam_masuk' => $jamkerja->akhir_jam_masuk
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
                    'created_at' => Carbon::now('Asia/Jakarta'),
                    'updated_at' => Carbon::now('Asia/Jakarta')
                ];

                $simpan = DB::table('presensi')->insert($data);

                if ($simpan) {
                    Storage::put($file, $image_base64);

                    Log::info('Check-in success', [
                        'nik' => $nik,
                        'nama' => $nama_lengkap,
                        'jam' => $jam,
                        'kode_jam_kerja' => $jamkerja->kode_jam_kerja
                    ]);

                    // Send notification (optional)
                    $this->sendWhatsAppNotification($nik, "Presensi Masuk berhasil pada jam {$jam}", 'in');

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

    /**
     * Send WhatsApp notification (optional)
     */
    private function sendWhatsAppNotification($nik, $message, $type)
    {
        try {
            $karyawan = DB::table('karyawan')->where('nik', $nik)->first();

            if (!$karyawan || empty($karyawan->no_hp)) {
                return false;
            }

            // Skip jika nomor tidak valid
            if (strlen($karyawan->no_hp) < 10) {
                return false;
            }

            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://wag.masjidagungalazhar.com/send-message',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 5,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => [
                    'message' => $message,
                    'number' => $karyawan->no_hp,
                    'file_dikirim' => ''
                ],
            ]);

            $response = curl_exec($curl);
            $error = curl_error($curl);

            curl_close($curl);

            if ($error) {
                Log::warning('WhatsApp notification failed', [
                    'nik' => $nik,
                    'error' => $error
                ]);
                return false;
            }

            Log::info('WhatsApp notification sent', [
                'nik' => $nik,
                'type' => $type
            ]);

            return true;
        } catch (Exception $e) {
            Log::warning('WhatsApp notification error: ' . $e->getMessage());
            return false;
        }
    }
}
