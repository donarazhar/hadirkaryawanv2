<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Branch;
use App\Models\Unit;
use App\Models\Organ;
use App\Models\Karyawan;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with a clean slate for Donar Azhar.
     */
    public function run(): void
    {
        $this->command->info('🚀 Memulai seeding database...');

        DB::beginTransaction();
        try {
            // 1. Branch: YPI Al Azhar Pusat
            $branch = Branch::firstOrCreate(
                ['name' => 'YPI Al Azhar Pusat'],
                [
                    'lokasi_cabang' => '-6.234352072999432, 106.80019122741929',
                    'radius_cabang' => 100,
                    'qr_token' => \Illuminate\Support\Str::random(32)
                ]
            );
            $this->command->info('✅ Branch: YPI Al Azhar Pusat');

            // 2. Unit: Masjid
            $unit = Unit::firstOrCreate(
                ['name' => 'Masjid', 'branch_id' => $branch->id]
            );
            $this->command->info('✅ Unit: Masjid');

            // 3. Organ: Staff Masjid
            $organ = Organ::firstOrCreate(
                ['name' => 'Staff Masjid', 'unit_id' => $unit->id]
            );
            $this->command->info('✅ Organ: Staff Masjid');

            // 4. Karyawan: Donar Azhar
            $karyawan = Karyawan::updateOrCreate(
                ['nik' => '203051967'],
                [
                    'nama_lengkap' => 'Donar Azhar',
                    'jabatan' => 'Staff Masjid',
                    'password' => Hash::make('password123'),
                    'organ_id' => $organ->id,
                    'email' => 'donarazhar@gmail.com'
                ]
            );
            $this->command->info('✅ Karyawan: Donar Azhar (NIK: 203051967)');

            // 5. User: Donar Azhar (Super Administrator)
            $user = User::updateOrCreate(
                ['email' => 'donarazhar@gmail.com'],
                [
                    'name' => 'Donar Azhar',
                    'password' => Hash::make('123456'),
                    'role' => 'superadmin',
                    'branch_id' => $branch->id,
                    'nik_karyawan' => '203051967'
                ]
            );
            $this->command->info('✅ User: Donar Azhar (Super Administrator)');

            DB::commit();

            // 6. Seed master data (Branches, Units, Organs)
            $this->call(MasterDataSeeder::class);
            $this->command->info("\n🎉 Seeding selesai! Anda bisa login dengan:");
            $this->command->info("Email: donarazhar@gmail.com");
            $this->command->info("Password: password123");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('❌ Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
