<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PengajuanCutiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('📋 Seeding Jenis Cuti...');

        $jenisCuti = [
            [
                'kode_cuti' => 'CT001',
                'nama_cuti' => 'Cuti Tahunan',
                'jml_hari' => 12,
                'status' => 'aktif',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'kode_cuti' => 'CT002',
                'nama_cuti' => 'Cuti Sakit',
                'jml_hari' => 0, // Unlimited dengan surat dokter
                'status' => 'aktif',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'kode_cuti' => 'CT003',
                'nama_cuti' => 'Cuti Melahirkan',
                'jml_hari' => 90,
                'status' => 'aktif',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'kode_cuti' => 'CT004',
                'nama_cuti' => 'Cuti Menikah',
                'jml_hari' => 3,
                'status' => 'aktif',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'kode_cuti' => 'CT005',
                'nama_cuti' => 'Cuti Keluarga Meninggal',
                'jml_hari' => 2,
                'status' => 'aktif',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'kode_cuti' => 'CT006',
                'nama_cuti' => 'Cuti Ibadah Haji/Umroh',
                'jml_hari' => 40,
                'status' => 'aktif',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($jenisCuti as $cuti) {
            DB::table('pengajuan_cuti')->insert($cuti);

            $hariText = $cuti['jml_hari'] > 0 ? "{$cuti['jml_hari']} hari" : "Unlimited";
            $this->command->info("  ✓ {$cuti['kode_cuti']} - {$cuti['nama_cuti']} ({$hariText})");
        }

        $this->command->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('✅ Selesai! Total 6 jenis cuti berhasil dibuat');
        $this->command->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    }
}
