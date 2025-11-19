<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JamKerja;

class JamKerjaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jamkerja = [
            [
                'kode_jam_kerja' => 'JK01',
                'nama_jam_kerja' => 'Shift Pagi',
                'awal_jam_masuk' => '07:00:00',
                'jam_masuk' => '08:00:00',
                'akhir_jam_masuk' => '08:30:00',
                'jam_pulang' => '16:00:00',
                'lintashari' => '0'
            ],
            [
                'kode_jam_kerja' => 'JK02',
                'nama_jam_kerja' => 'Shift Siang',
                'awal_jam_masuk' => '11:00:00',
                'jam_masuk' => '12:00:00',
                'akhir_jam_masuk' => '12:30:00',
                'jam_pulang' => '20:00:00',
                'lintashari' => '0'
            ],
            [
                'kode_jam_kerja' => 'JK03',
                'nama_jam_kerja' => 'Shift Malam',
                'awal_jam_masuk' => '19:00:00',
                'jam_masuk' => '20:00:00',
                'akhir_jam_masuk' => '20:30:00',
                'jam_pulang' => '04:00:00',
                'lintashari' => '1'
            ],
            [
                'kode_jam_kerja' => 'JK04',
                'nama_jam_kerja' => 'Non-Shift',
                'awal_jam_masuk' => '07:30:00',
                'jam_masuk' => '08:00:00',
                'akhir_jam_masuk' => '09:00:00',
                'jam_pulang' => '17:00:00',
                'lintashari' => '0'
            ],
            [
                'kode_jam_kerja' => 'JK05',
                'nama_jam_kerja' => 'Fleksibel',
                'awal_jam_masuk' => '07:00:00',
                'jam_masuk' => '09:00:00',
                'akhir_jam_masuk' => '10:00:00',
                'jam_pulang' => '18:00:00',
                'lintashari' => '0'
            ],
        ];

        foreach ($jamkerja as $item) {
            JamKerja::create($item);
        }
    }
}
