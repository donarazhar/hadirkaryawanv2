<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Karyawan;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Include KARYAWAN REGULAR + IMAM & MUAZIN
     */
    public function run(): void
    {
        $this->command->info('🧑‍💼 Seeding Karyawan...');

        $karyawan = [
            // ========================================
            // JAKARTA - IT Department
            // ========================================
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

            // ========================================
            // JAKARTA - HRD Department
            // ========================================
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

            // ========================================
            // JAKARTA - Finance
            // ========================================
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

            // ========================================
            // BANDUNG - IT Department
            // ========================================
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

            // ========================================
            // BANDUNG - Education
            // ========================================
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

            // ========================================
            // SURABAYA - Marketing
            // ========================================
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

            // ========================================
            // JAKARTA - KEAGAMAAN (MULTI-SHIFT)
            // ========================================
            [
                'nik' => '2024101',
                'nama_lengkap' => 'Ustadz Ahmad Syahid',
                'jabatan' => 'Imam Masjid',
                'no_hp' => '081234567901',
                'password' => Hash::make('password123'),
                'foto' => null,
                'kode_dept' => 'KEAG',
                'kode_cabang' => 'CBG001'
            ],
            [
                'nik' => '2024102',
                'nama_lengkap' => 'Ustadz Muhammad Rizki',
                'jabatan' => 'Muazin',
                'no_hp' => '081234567902',
                'password' => Hash::make('password123'),
                'foto' => null,
                'kode_dept' => 'KEAG',
                'kode_cabang' => 'CBG001'
            ],

            // ========================================
            // BANDUNG - KEAGAMAAN (MULTI-SHIFT)
            // ========================================
            [
                'nik' => '2024103',
                'nama_lengkap' => 'Ustadz Abdullah Hakim',
                'jabatan' => 'Imam Masjid',
                'no_hp' => '081234567903',
                'password' => Hash::make('password123'),
                'foto' => null,
                'kode_dept' => 'KEAG',
                'kode_cabang' => 'CBG002'
            ],
            [
                'nik' => '2024104',
                'nama_lengkap' => 'Ustadz Fathur Rahman',
                'jabatan' => 'Muazin',
                'no_hp' => '081234567904',
                'password' => Hash::make('password123'),
                'foto' => null,
                'kode_dept' => 'KEAG',
                'kode_cabang' => 'CBG002'
            ],

            // ========================================
            // SURABAYA - KEAGAMAAN (MULTI-SHIFT)
            // ========================================
            [
                'nik' => '2024105',
                'nama_lengkap' => 'Ustadz Yusuf Ali',
                'jabatan' => 'Imam Masjid',
                'no_hp' => '081234567905',
                'password' => Hash::make('password123'),
                'foto' => null,
                'kode_dept' => 'KEAG',
                'kode_cabang' => 'CBG003'
            ],
        ];

        $regularCount = 0;
        $multiShiftCount = 0;

        foreach ($karyawan as $item) {
            Karyawan::create($item);
            
            if ($item['kode_dept'] === 'KEAG') {
                $multiShiftCount++;
                $this->command->info("  🕌 {$item['nama_lengkap']} ({$item['nik']}) - {$item['jabatan']} - Multi-Shift");
            } else {
                $regularCount++;
                $this->command->info("  ✓ {$item['nama_lengkap']} ({$item['nik']}) - {$item['jabatan']}");
            }
        }

        $total = $regularCount + $multiShiftCount;

        $this->command->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info("✅ Selesai! Total {$total} karyawan berhasil dibuat");
        $this->command->info("   - {$regularCount} Karyawan Regular");
        $this->command->info("   - {$multiShiftCount} Karyawan Multi-Shift (Imam & Muazin)");
        $this->command->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    }
}