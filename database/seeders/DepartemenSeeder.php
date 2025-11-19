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
        ];

        foreach ($departemen as $item) {
            Departemen::create($item);
        }
    }
}
