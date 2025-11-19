<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PresensiSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚀 Memulai seeding data presensi...');

        $nik = '2024001';

        // Cek apakah karyawan ada
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();

        if (!$karyawan) {
            $this->command->error("❌ Karyawan dengan NIK {$nik} tidak ditemukan!");
            return;
        }

        $this->command->info("✓ Karyawan ditemukan: {$karyawan->nama_lengkap}");

        // Get lokasi kantor untuk generate lokasi presensi
        $cabang = DB::table('cabang')->where('kode_cabang', $karyawan->kode_cabang)->first();

        if (!$cabang) {
            $this->command->error("❌ Data cabang tidak ditemukan!");
            return;
        }

        $lokasi_kantor = explode(',', $cabang->lokasi_cabang);
        $lat_kantor = trim($lokasi_kantor[0]);
        $lng_kantor = trim($lokasi_kantor[1]);

        // Generate presensi untuk bulan ini
        $bulan_ini = date('m');
        $tahun_ini = date('Y');
        $hari_ini = date('d');

        $this->command->info("📅 Generate presensi untuk bulan {$bulan_ini}/{$tahun_ini}");

        // Get jam kerja default
        $jam_kerja = DB::table('jam_kerja')->where('kode_jam_kerja', 'JK01')->first();

        if (!$jam_kerja) {
            $this->command->error("❌ Jam kerja tidak ditemukan!");
            return;
        }

        $count = 0;

        // Loop dari tanggal 1 sampai hari ini
        for ($i = 1; $i <= $hari_ini; $i++) {
            $tanggal = Carbon::create($tahun_ini, $bulan_ini, $i);
            $nama_hari = $this->getNamaHari($tanggal->format('D'));

            // Skip Minggu (optional, sesuaikan dengan kebutuhan)
            if ($nama_hari == 'Minggu') {
                continue;
            }

            // Cek apakah sudah ada presensi di tanggal ini
            $cek = DB::table('presensi')
                ->where('nik', $nik)
                ->where('tgl_presensi', $tanggal->format('Y-m-d'))
                ->count();

            if ($cek > 0) {
                $this->command->warn("  ⚠ Presensi tanggal {$tanggal->format('d/m/Y')} sudah ada, skip...");
                continue;
            }

            // Randomize status presensi
            $rand = rand(1, 100);

            if ($rand <= 80) {
                // 80% Hadir
                $this->insertPresensiHadir($nik, $tanggal, $jam_kerja, $lat_kantor, $lng_kantor);
                $status_text = "Hadir";
            } elseif ($rand <= 90) {
                // 10% Izin
                $this->insertPresensiIzin($nik, $tanggal);
                $status_text = "Izin";
            } elseif ($rand <= 95) {
                // 5% Sakit
                $this->insertPresensiSakit($nik, $tanggal);
                $status_text = "Sakit";
            } else {
                // 5% Cuti
                $this->insertPresensiCuti($nik, $tanggal);
                $status_text = "Cuti";
            }

            $count++;
            $this->command->info("  ✓ {$tanggal->format('d/m/Y')} ({$nama_hari}) - {$status_text}");
        }

        $this->command->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info("✅ Selesai! Total {$count} data presensi berhasil dibuat");
        $this->command->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    }

    /**
     * Insert presensi hadir (dengan kemungkinan terlambat)
     */
    private function insertPresensiHadir($nik, $tanggal, $jam_kerja, $lat_kantor, $lng_kantor)
    {
        // Randomize apakah terlambat atau tidak
        $terlambat = rand(1, 100) <= 20; // 20% kemungkinan terlambat

        if ($terlambat) {
            // Terlambat 5-30 menit
            $menit_terlambat = rand(5, 30);
            $jam_masuk = Carbon::parse($jam_kerja->jam_masuk)->addMinutes($menit_terlambat);
        } else {
            // Tepat waktu atau lebih awal (5-15 menit lebih awal)
            $menit_awal = rand(-15, 0);
            $jam_masuk = Carbon::parse($jam_kerja->jam_masuk)->addMinutes($menit_awal);
        }

        // Jam pulang dengan random variasi
        $jam_pulang = Carbon::parse($jam_kerja->jam_pulang)->addMinutes(rand(-10, 30));

        // Generate lokasi presensi (dalam radius kantor)
        $lokasi_in = $this->generateLokasiDalamRadius($lat_kantor, $lng_kantor, 50);
        $lokasi_out = $this->generateLokasiDalamRadius($lat_kantor, $lng_kantor, 50);

        // Generate nama file foto
        $foto_in = $nik . "_" . $tanggal->format('Y-m-d') . "_in.png";
        $foto_out = $nik . "_" . $tanggal->format('Y-m-d') . "_out.png";

        DB::table('presensi')->insert([
            'nik' => $nik,
            'tgl_presensi' => $tanggal->format('Y-m-d'),
            'jam_in' => $jam_masuk->format('H:i:s'),
            'foto_in' => $foto_in,
            'lokasi_in' => $lokasi_in,
            'jam_out' => $jam_pulang->format('H:i:s'),
            'foto_out' => $foto_out,
            'lokasi_out' => $lokasi_out,
            'kode_jam_kerja' => $jam_kerja->kode_jam_kerja,
            'status' => 'h',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Insert presensi izin
     */
    private function insertPresensiIzin($nik, $tanggal)
    {
        // Generate kode izin
        $kode_izin = 'IZ' . $tanggal->format('Ymd') . rand(100, 999);

        // Alasan izin
        $alasan = [
            'Keperluan keluarga',
            'Ada urusan mendadak',
            'Keperluan pribadi',
            'Mengurus dokumen penting',
            'Acara keluarga'
        ];

        // Insert pengajuan izin
        DB::table('pengajuan_izin')->insert([
            'kode_izin' => $kode_izin,
            'nik' => $nik,
            'tgl_izin_dari' => $tanggal->format('Y-m-d'),
            'tgl_izin_sampai' => $tanggal->format('Y-m-d'),
            'status' => 'i',
            'keterangan' => $alasan[array_rand($alasan)],
            'status_approved' => 1, // Sudah disetujui
            'created_at' => $tanggal->subDays(1), // Diajukan sehari sebelumnya
            'updated_at' => now()
        ]);

        // Insert presensi dengan status izin
        DB::table('presensi')->insert([
            'nik' => $nik,
            'tgl_presensi' => $tanggal->format('Y-m-d'),
            'status' => 'i',
            'kode_izin' => $kode_izin,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Insert presensi sakit
     */
    private function insertPresensiSakit($nik, $tanggal)
    {
        // Generate kode izin
        $kode_izin = 'SK' . $tanggal->format('Ymd') . rand(100, 999);

        // Alasan sakit
        $alasan = [
            'Demam tinggi',
            'Flu dan batuk',
            'Sakit kepala',
            'Gangguan pencernaan',
            'Kurang enak badan'
        ];

        // Insert pengajuan izin sakit
        DB::table('pengajuan_izin')->insert([
            'kode_izin' => $kode_izin,
            'nik' => $nik,
            'tgl_izin_dari' => $tanggal->format('Y-m-d'),
            'tgl_izin_sampai' => $tanggal->format('Y-m-d'),
            'status' => 's',
            'keterangan' => $alasan[array_rand($alasan)],
            'status_approved' => 1,
            'created_at' => $tanggal,
            'updated_at' => now()
        ]);

        // Insert presensi dengan status sakit
        DB::table('presensi')->insert([
            'nik' => $nik,
            'tgl_presensi' => $tanggal->format('Y-m-d'),
            'status' => 's',
            'kode_izin' => $kode_izin,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Insert presensi cuti
     */
    private function insertPresensiCuti($nik, $tanggal)
    {
        // Generate kode izin
        $kode_izin = 'CT' . $tanggal->format('Ymd') . rand(100, 999);

        // Alasan cuti
        $alasan = [
            'Cuti tahunan',
            'Cuti pribadi',
            'Keperluan keluarga',
            'Liburan'
        ];

        // Insert pengajuan cuti
        DB::table('pengajuan_izin')->insert([
            'kode_izin' => $kode_izin,
            'nik' => $nik,
            'tgl_izin_dari' => $tanggal->format('Y-m-d'),
            'tgl_izin_sampai' => $tanggal->format('Y-m-d'),
            'status' => 'c',
            'keterangan' => $alasan[array_rand($alasan)],
            'status_approved' => 1,
            'created_at' => $tanggal->subDays(3), // Diajukan 3 hari sebelumnya
            'updated_at' => now()
        ]);

        // Insert presensi dengan status cuti
        DB::table('presensi')->insert([
            'nik' => $nik,
            'tgl_presensi' => $tanggal->format('Y-m-d'),
            'status' => 'c',
            'kode_izin' => $kode_izin,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Generate lokasi dalam radius tertentu dari titik pusat
     */
    private function generateLokasiDalamRadius($lat_pusat, $lng_pusat, $radius_meter)
    {
        // Convert radius dari meter ke derajat (approximation)
        $radius_deg = $radius_meter / 111320;

        // Random angle
        $angle = rand(0, 360) * (M_PI / 180);

        // Random distance dalam radius
        $distance = $radius_deg * sqrt(rand(0, 100) / 100);

        // Calculate new coordinates
        $lat_baru = $lat_pusat + ($distance * cos($angle));
        $lng_baru = $lng_pusat + ($distance * sin($angle));

        return round($lat_baru, 7) . "," . round($lng_baru, 7);
    }

    /**
     * Get nama hari dalam bahasa Indonesia
     */
    private function getNamaHari($hari)
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
}
