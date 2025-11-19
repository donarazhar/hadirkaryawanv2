<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin
        User::create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@ypialazhar.com',
            'password' => Hash::make('superadmin123'),
            'role' => 'superadmin',
            'kode_cabang' => null,
        ]);

        // Admin Cabang Jakarta
        User::create([
            'name' => 'Admin Jakarta Pusat',
            'email' => 'admin.jakarta@ypialazhar.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'kode_cabang' => 'CBG001',
        ]);

        // Admin Cabang Bandung
        User::create([
            'name' => 'Admin Bandung',
            'email' => 'admin.bandung@ypialazhar.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'kode_cabang' => 'CBG002',
        ]);

        // Admin Cabang Surabaya
        User::create([
            'name' => 'Admin Surabaya',
            'email' => 'admin.surabaya@ypialazhar.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'kode_cabang' => 'CBG003',
        ]);

        // Pimpinan Cabang Jakarta
        User::create([
            'name' => 'Pimpinan Jakarta Pusat',
            'email' => 'pimpinan.jakarta@ypialazhar.com',
            'password' => Hash::make('pimpinan123'),
            'role' => 'pimpinan',
            'kode_cabang' => 'CBG001',
        ]);

        // Pimpinan Cabang Bandung
        User::create([
            'name' => 'Pimpinan Bandung',
            'email' => 'pimpinan.bandung@ypialazhar.com',
            'password' => Hash::make('pimpinan123'),
            'role' => 'pimpinan',
            'kode_cabang' => 'CBG002',
        ]);
    }
}
