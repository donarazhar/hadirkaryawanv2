<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengajuanCutiSeeder extends Seeder
{
    public function run(): void
    {
        $cuti = [
            [
                'kode_cuti' => 'CT001',
                'nama_cuti' => 'Cuti Tahunan',
                'jml_hari' => 12,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_cuti' => 'CT002',
                'nama_cuti' => 'Cuti Sakit Berkepanjangan',
                'jml_hari' => 14,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_cuti' => 'CT003',
                'nama_cuti' => 'Cuti Melahirkan',
                'jml_hari' => 90,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_cuti' => 'CT004',
                'nama_cuti' => 'Cuti Menikah',
                'jml_hari' => 3,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_cuti' => 'CT005',
                'nama_cuti' => 'Cuti Besar',
                'jml_hari' => 30,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('pengajuan_cuti')->insert($cuti);
    }
}
