<?php

namespace Database\Seeders;

use Database\Seeders\CabangSeeder;
use Database\Seeders\DepartemenSeeder;
use Database\Seeders\FaceDataSeeder;
use Database\Seeders\JamKerjaSeeder;
use Database\Seeders\KaryawanSeeder;
use Database\Seeders\KonfigurasiLokasiSeeder;
use Database\Seeders\PengajuanCutiSeeder;
use Database\Seeders\PengajuanIzinSeeder;
use Database\Seeders\PresensiFaceSeeder;
use Database\Seeders\PresensiSeeder;
use Database\Seeders\SetupJamKerjaKaryawanSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * COMPLETE SEEDER - All Tables Covered
     * Support: Regular Shift + Multi-Shift (Imam & Muazin)
     */
    public function run(): void
    {
        $this->command->info('🚀 Memulai seeding database LENGKAP...');
        $this->command->info('📦 Support: Regular Shift + Multi-Shift (Imam & Muazin)');
        $this->command->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

        // ========================================
        // STEP 1: MASTER DATA
        // ========================================
        $this->command->info("\n📦 STEP 1: Seeding Master Data...");
        $this->call([
            CabangSeeder::class,              // 5 cabang
            DepartemenSeeder::class,          // 9 departemen (include KEAG)
            KonfigurasiLokasiSeeder::class,   // 7 lokasi GPS
        ]);

        // ========================================
        // STEP 2: USER MANAGEMENT
        // ========================================
        $this->command->info("\n👥 STEP 2: Seeding Users...");
        $this->call([
            UserSeeder::class,                // 6 users (superadmin + admin + pimpinan)
        ]);

        // ========================================
        // STEP 3: KARYAWAN (Include Imam & Muazin)
        // ========================================
        $this->command->info("\n🧑‍💼 STEP 3: Seeding Karyawan...");
        $this->call([
            KaryawanSeeder::class,            // 15 karyawan (10 regular + 5 Imam/Muazin)
        ]);

        // ========================================
        // STEP 4: JAM KERJA (Regular + Multi-Shift)
        // ========================================
        $this->command->info("\n⏰ STEP 4: Seeding Jam Kerja...");
        $this->call([
            JamKerjaSeeder::class,            // 6 jam kerja (5 regular + 1 multi-shift)
                                              // + 5 shifts (Subuh, Zuhur, Ashar, Maghrib, Isya)
        ]);

        // ========================================
        // STEP 5: JENIS CUTI
        // ========================================
        $this->command->info("\n📋 STEP 5: Seeding Jenis Cuti...");
        $this->call([
            PengajuanCutiSeeder::class,       // 6 jenis cuti
        ]);

        // ========================================
        // STEP 6: KONFIGURASI JAM KERJA
        // ========================================
        $this->command->info("\n⚙️  STEP 6: Setup Konfigurasi Jam Kerja...");
        $this->call([
            SetupJamKerjaKaryawanSeeder::class, // Konfigurasi JK per dept + cabang
        ]);

        // ========================================
        // STEP 7: FACE RECOGNITION DATA
        // ========================================
        $this->command->info("\n🎭 STEP 7: Seeding Face Recognition Data...");
        $this->call([
            FaceDataSeeder::class,            // 15 face data (untuk semua karyawan)
        ]);

        // ========================================
        // STEP 8: PENGAJUAN IZIN
        // ========================================
        $this->command->info("\n📝 STEP 8: Seeding Pengajuan Izin...");
        $this->call([
            PengajuanIzinSeeder::class,       // Sample izin/sakit/cuti
        ]);

        // ========================================
        // STEP 9: PRESENSI DATA
        // ========================================
        $this->command->info("\n📊 STEP 9: Seeding Presensi...");
        // $this->call([
        //     PresensiSeeder::class,            // Presensi regular (14 hari)
        //     PresensiFaceSeeder::class,        // Presensi face (multi-shift + regular, 7 hari)
        // ]);

        // ========================================
        // SUMMARY
        // ========================================
        $this->command->line("\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->command->info('✅ Seeding database LENGKAP selesai!');
        $this->command->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        
        $this->showCompleteSummary();
    }

    /**
     * Show complete summary of ALL tables
     */
    private function showCompleteSummary()
    {
        $this->command->info("\n📊 COMPLETE DATABASE SUMMARY:");
        
        // Master Data
        $this->command->info("\n🗂️  MASTER DATA:");
        $this->command->table(
            ['Table', 'Records'],
            [
                ['cabang', \DB::table('cabang')->count()],
                ['departemen', \DB::table('departemen')->count()],
                ['konfigurasi_lokasi', \DB::table('konfigurasi_lokasi')->count()],
                ['pengajuan_cuti (jenis)', \DB::table('pengajuan_cuti')->count()],
            ]
        );

        // User Management
        $this->command->info("\n👥 USER MANAGEMENT:");
        $superadminCount = \DB::table('users')->where('role', 'superadmin')->count();
        $adminCount = \DB::table('users')->where('role', 'admin')->count();
        $pimpinanCount = \DB::table('users')->where('role', 'pimpinan')->count();
        
        $this->command->table(
            ['Table', 'Records'],
            [
                ['users (total)', \DB::table('users')->count()],
                ['  - superadmin', $superadminCount],
                ['  - admin', $adminCount],
                ['  - pimpinan', $pimpinanCount],
            ]
        );

        // Karyawan
        $regularKaryawan = \DB::table('karyawan')->where('kode_dept', '!=', 'KEAG')->count();
        $multiShiftKaryawan = \DB::table('karyawan')->where('kode_dept', 'KEAG')->count();
        
        $this->command->info("\n🧑‍💼 KARYAWAN:");
        $this->command->table(
            ['Table', 'Records'],
            [
                ['karyawan (total)', \DB::table('karyawan')->count()],
                ['  - Regular Shift', $regularKaryawan],
                ['  - Multi-Shift (Imam/Muazin)', $multiShiftKaryawan],
            ]
        );

        // Jam Kerja
        $regularJK = \DB::table('jam_kerja')->where('tipe_jam_kerja', 'regular')->count();
        $multiShiftJK = \DB::table('jam_kerja')->where('tipe_jam_kerja', 'multi_shift')->count();
        $shiftsCount = \DB::table('jam_kerja_shifts')->count();
        
        $this->command->info("\n⏰ JAM KERJA:");
        $this->command->table(
            ['Table', 'Records'],
            [
                ['jam_kerja (total)', \DB::table('jam_kerja')->count()],
                ['  - Regular', $regularJK],
                ['  - Multi-Shift', $multiShiftJK],
                ['jam_kerja_shifts', $shiftsCount],
                ['konfigurasi_jk_dept', \DB::table('konfigurasi_jk_dept')->count()],
                ['konfigurasi_jk_dept_detail', \DB::table('konfigurasi_jk_dept_detail')->count()],
            ]
        );

        // Face Recognition
        $faceDataActive = \DB::table('face_data')->where('status', 'active')->count();
        $faceDataInactive = \DB::table('face_data')->where('status', 'inactive')->count();
        
        $this->command->info("\n🎭 FACE RECOGNITION:");
        $this->command->table(
            ['Table', 'Records'],
            [
                ['face_data (total)', \DB::table('face_data')->count()],
                ['  - Active', $faceDataActive],
                ['  - Inactive', $faceDataInactive],
            ]
        );

        // Izin & Cuti
        $izinApproved = \DB::table('pengajuan_izin')->where('status_approved', '1')->count();
        $izinPending = \DB::table('pengajuan_izin')->where('status_approved', '0')->count();
        
        $this->command->info("\n📝 IZIN & CUTI:");
        $this->command->table(
            ['Table', 'Records'],
            [
                ['pengajuan_izin (total)', \DB::table('pengajuan_izin')->count()],
                ['  - Approved', $izinApproved],
                ['  - Pending', $izinPending],
            ]
        );

        // Presensi
        $presensiHadir = \DB::table('presensi')->where('status', 'h')->count();
        $presensiIzin = \DB::table('presensi')->where('status', 'i')->count();
        $presensiAlpha = \DB::table('presensi')->where('status', 'a')->count();
        $presensiFaceMulti = \DB::table('presensi_face')->whereNotNull('shift_ke')->count();
        $presensiFaceRegular = \DB::table('presensi_face')->whereNull('shift_ke')->count();
        
        $this->command->info("\n📊 PRESENSI:");
        $this->command->table(
            ['Table', 'Records'],
            [
                ['presensi (total)', \DB::table('presensi')->count()],
                ['  - Hadir', $presensiHadir],
                ['  - Izin', $presensiIzin],
                ['  - Alpha', $presensiAlpha],
                ['presensi_face (total)', \DB::table('presensi_face')->count()],
                ['  - Multi-Shift', $presensiFaceMulti],
                ['  - Regular', $presensiFaceRegular],
            ]
        );

        // Multi-Shift Details
        $this->showMultiShiftDetails();

        // Credentials
        $this->showCredentials();
    }

    /**
     * Show multi-shift details
     */
    private function showMultiShiftDetails()
    {
        $this->command->info("\n🎯 MULTI-SHIFT FEATURES:");
        
        $multiShiftJK = \DB::table('jam_kerja')
            ->where('tipe_jam_kerja', 'multi_shift')
            ->get();

        foreach ($multiShiftJK as $jk) {
            $shiftsCount = \DB::table('jam_kerja_shifts')
                ->where('kode_jam_kerja', $jk->kode_jam_kerja)
                ->count();

            $karyawanCount = \DB::table('karyawan')
                ->where('kode_dept', 'KEAG')
                ->count();

            $presensiCount = \DB::table('presensi_face')
                ->whereNotNull('shift_ke')
                ->count();

            $this->command->info("  ✓ {$jk->nama_jam_kerja} ({$jk->kode_jam_kerja})");
            $this->command->info("    - Total Shifts: {$shiftsCount}");
            $this->command->info("    - Total Karyawan: {$karyawanCount}");
            $this->command->info("    - Total Presensi Multi-Shift: {$presensiCount}");

            // Show shift details
            $shifts = \DB::table('jam_kerja_shifts')
                ->where('kode_jam_kerja', $jk->kode_jam_kerja)
                ->orderBy('shift_ke')
                ->get();

            if ($shifts->isNotEmpty()) {
                $this->command->info("    - Shifts Detail:");
                foreach ($shifts as $shift) {
                    $this->command->info("      {$shift->shift_ke}. {$shift->nama_shift} ({$shift->jam_masuk} - {$shift->jam_pulang})");
                }
            }
        }
    }

    /**
     * Show login credentials
     */
    private function showCredentials()
    {
        $this->command->info("\n🔑 LOGIN CREDENTIALS:");
        $this->command->table(
            ['Role', 'Email/NIK', 'Password'],
            [
                ['Super Admin', 'superadmin@ypialazhar.com', 'superadmin123'],
                ['Admin Jakarta', 'admin.jakarta@ypialazhar.com', 'admin123'],
                ['Admin Bandung', 'admin.bandung@ypialazhar.com', 'admin123'],
                ['Pimpinan Jakarta', 'pimpinan.jakarta@ypialazhar.com', 'pimpinan123'],
                ['Karyawan Regular', '2024001 (NIK)', 'password123'],
                ['Imam/Muazin', '2024101 (NIK)', 'password123'],
            ]
        );

        $this->command->info("\n💡 Tips:");
        $this->command->info("  • Karyawan login: NIK sebagai username");
        $this->command->info("  • Admin/Pimpinan login: Email sebagai username");
        $this->command->info("  • Multi-shift: Imam & Muazin (KEAG dept) - 5 shifts/hari");
        $this->command->info("  • Regular shift: Karyawan lain - 1 shift/hari");
    }
}