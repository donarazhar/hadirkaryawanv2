<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PresensiFaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 Memulai seeding data presensi face recognition...');

        // Get karyawan dengan jam kerja multi-shift (Imam & Muazin)
        $imamMuazin = DB::table('karyawan')
            ->where('jabatan', 'LIKE', '%Imam%')
            ->orWhere('jabatan', 'LIKE', '%Muazin%')
            ->get();

        if ($imamMuazin->isEmpty()) {
            $this->command->warn('⚠️  Tidak ada karyawan Imam/Muazin. Membuat sample karyawan...');

            // Buat sample karyawan Imam & Muazin
            $this->createImamMuazinKaryawan();

            $imamMuazin = DB::table('karyawan')
                ->where('jabatan', 'LIKE', '%Imam%')
                ->orWhere('jabatan', 'LIKE', '%Muazin%')
                ->get();
        }

        // Get jam kerja multi-shift
        $jamKerjaImam = DB::table('jam_kerja')
            ->where('kode_jam_kerja', 'IMAM01')
            ->first();

        if (!$jamKerjaImam) {
            $this->command->error('❌ Jam kerja IMAM01 tidak ditemukan!');
            $this->command->info('💡 Jalankan: php artisan db:seed --class=ImamMuazinJamKerjaSeeder');
            return;
        }

        // Get shifts
        $shifts = DB::table('jam_kerja_shifts')
            ->where('kode_jam_kerja', 'IMAM01')
            ->orderBy('shift_ke')
            ->get();

        if ($shifts->isEmpty()) {
            $this->command->error('❌ Shifts untuk IMAM01 tidak ditemukan!');
            return;
        }

        $this->command->info("✓ Ditemukan " . $shifts->count() . " shifts");

        // Generate presensi untuk 7 hari terakhir
        $tanggalAwal = Carbon::now()->subDays(7);
        $tanggalAkhir = Carbon::now();

        $totalPresensi = 0;

        foreach ($imamMuazin as $karyawan) {
            $this->command->info("\n📍 Generate presensi untuk: {$karyawan->nama_lengkap} ({$karyawan->nik})");

            // Get lokasi cabang
            $cabang = DB::table('cabang')
                ->where('kode_cabang', $karyawan->kode_cabang)
                ->first();

            if (!$cabang) {
                $this->command->warn("  ⚠️  Cabang tidak ditemukan, skip...");
                continue;
            }

            $lokasi = explode(',', $cabang->lokasi_cabang);
            $lat = trim($lokasi[0]);
            $lng = trim($lokasi[1]);

            // Loop untuk setiap hari
            for ($date = clone $tanggalAwal; $date <= $tanggalAkhir; $date->addDay()) {
                $hari = $this->getNamaHari($date->format('D'));

                // Skip minggu (opsional)
                if ($hari == 'Minggu') {
                    continue;
                }

                $this->command->info("  📅 {$date->format('d/m/Y')} ({$hari})");

                // Generate presensi untuk setiap shift
                foreach ($shifts as $shift) {
                    // Random: 90% hadir, 10% tidak absen shift tertentu
                    if (rand(1, 100) <= 90) {
                        $this->insertPresensiFace(
                            $karyawan->nik,
                            $date,
                            $shift,
                            $lat,
                            $lng
                        );

                        $totalPresensi++;
                        $this->command->info("    ✓ Shift {$shift->shift_ke} - {$shift->nama_shift}");
                    } else {
                        $this->command->warn("    ⚠️  Shift {$shift->shift_ke} - {$shift->nama_shift} - Tidak absen");
                    }
                }
            }
        }

        // Generate presensi regular untuk karyawan biasa
        $this->generateRegularPresensi();

        $this->command->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info("✅ Selesai! Total {$totalPresensi} data presensi face berhasil dibuat");
        $this->command->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    }

    /**
     * Insert presensi face dengan multi-shift
     */
    private function insertPresensiFace($nik, $tanggal, $shift, $lat, $lng)
    {
        // Cek duplikat
        $cek = DB::table('presensi_face')
            ->where('nik', $nik)
            ->where('tanggal', $tanggal->format('Y-m-d'))
            ->where('shift_ke', $shift->shift_ke)
            ->count();

        if ($cek > 0) {
            return;
        }

        // Random jam masuk (bisa tepat waktu atau terlambat)
        $jamMasukShift = Carbon::parse($shift->jam_masuk);
        $terlambat = rand(1, 100) <= 15; // 15% kemungkinan terlambat

        if ($terlambat) {
            $menitTerlambat = rand(1, 10);
            $jamMasuk = $jamMasukShift->addMinutes($menitTerlambat);
        } else {
            $menitAwal = rand(-5, 0);
            $jamMasuk = $jamMasukShift->addMinutes($menitAwal);
        }

        // Random jam pulang
        $jamPulangShift = Carbon::parse($shift->jam_pulang);
        $jamPulang = $jamPulangShift->addMinutes(rand(-5, 10));

        // Generate lokasi dalam radius
        $lokasi = $this->generateLokasiDalamRadius($lat, $lng, 50);

        // Status: 95% verified, 5% failed
        $status = rand(1, 100) <= 95 ? 'verified' : 'failed';

        DB::table('presensi_face')->insert([
            'nik' => $nik,
            'tanggal' => $tanggal->format('Y-m-d'),
            'shift_ke' => $shift->shift_ke,
            'nama_shift' => $shift->nama_shift,
            'jam_masuk' => $jamMasuk->format('H:i:s'),
            'jam_pulang' => $jamPulang->format('H:i:s'),
            'lokasi' => $lokasi,
            'status' => $status,
            'created_at' => $tanggal->setTime($jamMasuk->hour, $jamMasuk->minute),
            'updated_at' => now()
        ]);
    }

    /**
     * Generate presensi regular untuk karyawan biasa
     */
    private function generateRegularPresensi()
    {
        $this->command->info("\n📍 Generate presensi regular untuk karyawan biasa...");

        // Get karyawan selain Imam/Muazin
        $karyawanRegular = DB::table('karyawan')
            ->where('jabatan', 'NOT LIKE', '%Imam%')
            ->where('jabatan', 'NOT LIKE', '%Muazin%')
            ->limit(5) // Ambil 5 karyawan sebagai sample
            ->get();

        $tanggalAwal = Carbon::now()->subDays(7);
        $tanggalAkhir = Carbon::now();

        foreach ($karyawanRegular as $karyawan) {
            $this->command->info("  📍 {$karyawan->nama_lengkap} ({$karyawan->nik})");

            // Get lokasi cabang
            $cabang = DB::table('cabang')
                ->where('kode_cabang', $karyawan->kode_cabang)
                ->first();

            if (!$cabang) continue;

            $lokasi = explode(',', $cabang->lokasi_cabang);
            $lat = trim($lokasi[0]);
            $lng = trim($lokasi[1]);

            for ($date = clone $tanggalAwal; $date <= $tanggalAkhir; $date->addDay()) {
                $hari = $this->getNamaHari($date->format('D'));

                if ($hari == 'Minggu') continue;

                // Random: 85% hadir
                if (rand(1, 100) <= 85) {
                    $this->insertPresensiFaceRegular($karyawan->nik, $date, $lat, $lng);
                    $this->command->info("    ✓ {$date->format('d/m/Y')} - Regular");
                }
            }
        }
    }

    /**
     * Insert presensi face regular (tanpa shift)
     */
    private function insertPresensiFaceRegular($nik, $tanggal, $lat, $lng)
    {
        // Cek duplikat
        $cek = DB::table('presensi_face')
            ->where('nik', $nik)
            ->where('tanggal', $tanggal->format('Y-m-d'))
            ->whereNull('shift_ke')
            ->count();

        if ($cek > 0) return;

        // Jam kerja regular (08:00 - 16:00)
        $jamMasuk = Carbon::parse('08:00:00')->addMinutes(rand(-10, 20));
        $jamPulang = Carbon::parse('16:00:00')->addMinutes(rand(-10, 30));

        $lokasi = $this->generateLokasiDalamRadius($lat, $lng, 50);
        $status = rand(1, 100) <= 95 ? 'verified' : 'failed';

        DB::table('presensi_face')->insert([
            'nik' => $nik,
            'tanggal' => $tanggal->format('Y-m-d'),
            'shift_ke' => null, // NULL untuk regular
            'nama_shift' => null,
            'jam_masuk' => $jamMasuk->format('H:i:s'),
            'jam_pulang' => $jamPulang->format('H:i:s'),
            'lokasi' => $lokasi,
            'status' => $status,
            'created_at' => $tanggal->setTime($jamMasuk->hour, $jamMasuk->minute),
            'updated_at' => now()
        ]);
    }

    /**
     * Buat sample karyawan Imam & Muazin
     */
    private function createImamMuazinKaryawan()
    {
        $karyawan = [
            [
                'nik' => '2024101',
                'nama_lengkap' => 'Ustadz Ahmad Syahid',
                'jabatan' => 'Imam Masjid',
                'no_hp' => '081234567901',
                'password' => bcrypt('password123'),
                'foto' => null,
                'kode_dept' => 'ADM',
                'kode_cabang' => 'CBG001',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nik' => '2024102',
                'nama_lengkap' => 'Ustadz Muhammad Rizki',
                'jabatan' => 'Muazin',
                'no_hp' => '081234567902',
                'password' => bcrypt('password123'),
                'foto' => null,
                'kode_dept' => 'ADM',
                'kode_cabang' => 'CBG001',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        foreach ($karyawan as $item) {
            DB::table('karyawan')->insert($item);
        }

        $this->command->info('  ✓ Sample karyawan Imam & Muazin berhasil dibuat');
    }

    /**
     * Generate lokasi dalam radius
     */
    private function generateLokasiDalamRadius($lat_pusat, $lng_pusat, $radius_meter)
    {
        $radius_deg = $radius_meter / 111320;
        $angle = rand(0, 360) * (M_PI / 180);
        $distance = $radius_deg * sqrt(rand(0, 100) / 100);

        $lat_baru = $lat_pusat + ($distance * cos($angle));
        $lng_baru = $lng_pusat + ($distance * sin($angle));

        return round($lat_baru, 7) . "," . round($lng_baru, 7);
    }

    /**
     * Get nama hari
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
