<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Karyawan;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $karyawan = [
            // Jakarta - IT Department
            [
                'nik' => '2024001',
                'nama_lengkap' => 'Ahmad Rizki',
                'jabatan' => 'IT Manager',
                'no_hp' => '081234567890',
                'password' => Hash::make('password123'),
                'foto' => null,
                'kode_dept' => 'IT',
                'kode_cabang' => 'CBG001'
            ],
            [
                'nik' => '2024002',
                'nama_lengkap' => 'Budi Santoso',
                'jabatan' => 'Programmer',
                'no_hp' => '081234567891',
                'password' => Hash::make('password123'),
                'foto' => null,
                'kode_dept' => 'IT',
                'kode_cabang' => 'CBG001'
            ],

            // Jakarta - HRD Department
            [
                'nik' => '2024003',
                'nama_lengkap' => 'Siti Nurhaliza',
                'jabatan' => 'HRD Manager',
                'no_hp' => '081234567892',
                'password' => Hash::make('password123'),
                'foto' => null,
                'kode_dept' => 'HRD',
                'kode_cabang' => 'CBG001'
            ],
            [
                'nik' => '2024004',
                'nama_lengkap' => 'Dewi Lestari',
                'jabatan' => 'Recruitment Staff',
                'no_hp' => '081234567893',
                'password' => Hash::make('password123'),
                'foto' => null,
                'kode_dept' => 'HRD',
                'kode_cabang' => 'CBG001'
            ],

            // Jakarta - Finance
            [
                'nik' => '2024005',
                'nama_lengkap' => 'Eko Prasetyo',
                'jabatan' => 'Finance Manager',
                'no_hp' => '081234567894',
                'password' => Hash::make('password123'),
                'foto' => null,
                'kode_dept' => 'FIN',
                'kode_cabang' => 'CBG001'
            ],

            // Bandung - IT Department
            [
                'nik' => '2024006',
                'nama_lengkap' => 'Faisal Rahman',
                'jabatan' => 'Network Admin',
                'no_hp' => '081234567895',
                'password' => Hash::make('password123'),
                'foto' => null,
                'kode_dept' => 'IT',
                'kode_cabang' => 'CBG002'
            ],

            // Bandung - Education
            [
                'nik' => '2024007',
                'nama_lengkap' => 'Gita Savitri',
                'jabatan' => 'Teacher',
                'no_hp' => '081234567896',
                'password' => Hash::make('password123'),
                'foto' => null,
                'kode_dept' => 'EDU',
                'kode_cabang' => 'CBG002'
            ],
            [
                'nik' => '2024008',
                'nama_lengkap' => 'Hendra Kusuma',
                'jabatan' => 'Teacher',
                'no_hp' => '081234567897',
                'password' => Hash::make('password123'),
                'foto' => null,
                'kode_dept' => 'EDU',
                'kode_cabang' => 'CBG002'
            ],

            // Surabaya - Marketing
            [
                'nik' => '2024009',
                'nama_lengkap' => 'Indah Permata',
                'jabatan' => 'Marketing Manager',
                'no_hp' => '081234567898',
                'password' => Hash::make('password123'),
                'foto' => null,
                'kode_dept' => 'MKT',
                'kode_cabang' => 'CBG003'
            ],
            [
                'nik' => '2024010',
                'nama_lengkap' => 'Joko Widodo',
                'jabatan' => 'Marketing Staff',
                'no_hp' => '081234567899',
                'password' => Hash::make('password123'),
                'foto' => null,
                'kode_dept' => 'MKT',
                'kode_cabang' => 'CBG003'
            ],
        ];

        foreach ($karyawan as $item) {
            Karyawan::create($item);
        }
    }
}
