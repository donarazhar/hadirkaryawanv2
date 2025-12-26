<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Departemen;

class DepartemenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('📦 Seeding Departemen...');

        $departemen = [
            [
                'kode_dept' => 'IT',
                'nama_dept' => 'Information Technology'
            ],
            [
                'kode_dept' => 'HRD',
                'nama_dept' => 'Human Resources Development'
            ],
            [
                'kode_dept' => 'FIN',
                'nama_dept' => 'Finance & Accounting'
            ],
            [
                'kode_dept' => 'MKT',
                'nama_dept' => 'Marketing & Public Relations'
            ],
            [
                'kode_dept' => 'OPS',
                'nama_dept' => 'Operations'
            ],
            [
                'kode_dept' => 'EDU',
                'nama_dept' => 'Education & Curriculum'
            ],
            [
                'kode_dept' => 'ADM',
                'nama_dept' => 'Administration'
            ],
            [
                'kode_dept' => 'SEC',
                'nama_dept' => 'Security'
            ],
            // Departemen baru untuk multi-shift
            [
                'kode_dept' => 'KEAG',
                'nama_dept' => 'Keagamaan'
            ],
        ];

        $count = 0;
        foreach ($departemen as $item) {
            Departemen::create($item);
            $count++;
            $this->command->info("  ✓ {$item['kode_dept']} - {$item['nama_dept']}");
        }

        $this->command->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info("✅ Selesai! Total {$count} departemen berhasil dibuat");
        $this->command->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    }
}