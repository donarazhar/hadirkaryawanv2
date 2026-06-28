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
use Illuminate\Support\Str;

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

            // 1. Cabang Global (Untuk Superadmin yang mobile)
            $globalBranch = Branch::firstOrCreate(
                ['name' => 'Cabang Global (Semua Lokasi)'],
                [
                    'lokasi_cabang' => '-6.234352072999432, 106.80019122741929', // Default Al Azhar Pusat
                    'radius_cabang' => 100, // Default 100m, bisa diedit via dashboard saat Superadmin pindah lokasi
                    'qr_token'      => Str::random(32),
                ]
            );
            $this->command->info('✅ Branch: Cabang Global (Semua Lokasi)');

            // 2. Unit & Organ untuk Global
            $globalUnit = Unit::firstOrCreate(
                ['name' => 'Management Global', 'branch_id' => $globalBranch->id],
                ['code' => 'GLOBAL', 'is_sekretariat' => false]
            );
            
            $globalOrgan = Organ::firstOrCreate(
                ['name' => 'Super Administrator', 'unit_id' => $globalUnit->id]
            );

            // 3. Karyawan: Donar Azhar (Dimasukkan ke Cabang Global)
            $karyawan = Karyawan::updateOrCreate(
                ['nik' => '203051967'],
                [
                    'nama_lengkap' => 'Donar Azhar',
                    'jabatan' => 'Super Administrator',
                    'password' => Hash::make('password123'),
                    'organ_id' => $globalOrgan->id,
                    'email' => 'donarazhar@gmail.com'
                ]
            );
            $this->command->info('✅ Karyawan: Donar Azhar (Cabang Global, NIK: 203051967)');

            // 5. User: Donar Azhar (Super Administrator)
            $user = User::updateOrCreate(
                ['email' => 'donarazhar@gmail.com'],
                [
                    'name' => 'Donar Azhar',
                    'password' => Hash::make('123456'),
                    'role' => 'superadmin',
                    'branch_id' => null, // Superadmin mengelola semua cabang
                    'nik_karyawan' => '203051967'
                ]
            );
            $this->command->info('✅ User: Donar Azhar (Super Administrator)');

            // 6. OAuth Client for Persuratan SSO
            DB::table('oauth_clients')->updateOrInsert(
                ['id' => 'a220af66-88b2-4055-a81a-49f61a6d0032'],
                [
                    'name' => 'Persuratan App',
                    'secret' => Hash::make('vL4aZegBiskAPiQJ2Hv0CBlJFdw5Z83996Kit8WJ'),
                    'provider' => null,
                    'redirect_uris' => json_encode(['http://localhost:8001/auth/presensi/callback', 'http://127.0.0.1:8001/auth/presensi/callback']),
                    'grant_types' => json_encode(['authorization_code', 'refresh_token']),
                    'revoked' => false,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
            $this->command->info('✅ OAuth Client: Persuratan App (SSO Integration)');

            DB::commit();

            // 6. Seed master data (Branches, Units, Organs)
            $this->call(MasterDataSeeder::class);
            $this->call(PdfKaryawanSeeder::class);
            $this->command->info("\n🎉 Seeding selesai! Anda bisa login dengan:");
            $this->command->info("Email: donarazhar@gmail.com");
            $this->command->info("Password: password123");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('❌ Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
