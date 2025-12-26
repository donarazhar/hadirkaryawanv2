<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('👥 Seeding Users...');

        $users = [
            // Super Admin
            [
                'name' => 'Super Administrator',
                'email' => 'superadmin@ypialazhar.com',
                'password' => Hash::make('superadmin123'),
                'role' => 'superadmin',
                'kode_cabang' => null, // Akses semua cabang
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Admin Jakarta
            [
                'name' => 'Admin Jakarta',
                'email' => 'admin.jakarta@ypialazhar.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'kode_cabang' => 'CBG001',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Admin Bandung
            [
                'name' => 'Admin Bandung',
                'email' => 'admin.bandung@ypialazhar.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'kode_cabang' => 'CBG002',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Admin Surabaya
            [
                'name' => 'Admin Surabaya',
                'email' => 'admin.surabaya@ypialazhar.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'kode_cabang' => 'CBG003',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Pimpinan Jakarta
            [
                'name' => 'Pimpinan Jakarta',
                'email' => 'pimpinan.jakarta@ypialazhar.com',
                'password' => Hash::make('pimpinan123'),
                'role' => 'pimpinan',
                'kode_cabang' => 'CBG001',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Pimpinan Bandung
            [
                'name' => 'Pimpinan Bandung',
                'email' => 'pimpinan.bandung@ypialazhar.com',
                'password' => Hash::make('pimpinan123'),
                'role' => 'pimpinan',
                'kode_cabang' => 'CBG002',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert($user);
            
            $roleIcon = match($user['role']) {
                'superadmin' => '👑',
                'admin' => '🔧',
                'pimpinan' => '📊',
                default => '👤'
            };
            
            $this->command->info("  {$roleIcon} {$user['name']} ({$user['email']}) - {$user['role']}");
        }

        $this->command->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('✅ Selesai! Total 6 users berhasil dibuat');
        $this->command->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

        // Show credentials
        $this->command->info("\n🔑 LOGIN CREDENTIALS:");
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Super Admin', 'superadmin@ypialazhar.com', 'superadmin123'],
                ['Admin Jakarta', 'admin.jakarta@ypialazhar.com', 'admin123'],
                ['Admin Bandung', 'admin.bandung@ypialazhar.com', 'admin123'],
                ['Pimpinan', 'pimpinan.jakarta@ypialazhar.com', 'pimpinan123'],
            ]
        );
    }
}