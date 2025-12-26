<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ImamMuazinJamKerjaSeeder extends Seeder
{
    public function run()
    {
        DB::beginTransaction();

        try {
            // 1. Create Jam Kerja Master untuk Imam & Muazin
            DB::table('jam_kerja')->insert([
                'kode_jam_kerja' => 'IMAM01',
                'nama_jam_kerja' => 'Imam & Muazin',
                'awal_jam_masuk' => '04:00:00',  // Subuh awal
                'jam_masuk' => '04:30:00',       // Subuh normal
                'akhir_jam_masuk' => '06:00:00', // Subuh akhir
                'jam_pulang' => '20:00:00',      // Isya selesai
                'lintashari' => 0,
                'tipe_jam_kerja' => 'multi_shift',
                'total_shift' => 5,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

            // 2. Create Shifts Detail
            $shifts = [
                [
                    'kode_jam_kerja' => 'IMAM01',
                    'shift_ke' => 1,
                    'nama_shift' => 'Subuh',
                    'awal_jam_masuk' => '04:00:00',
                    'jam_masuk' => '04:30:00',
                    'akhir_jam_masuk' => '05:00:00',
                    'jam_pulang' => '06:00:00',
                    'is_active' => true
                ],
                [
                    'kode_jam_kerja' => 'IMAM01',
                    'shift_ke' => 2,
                    'nama_shift' => 'Zuhur',
                    'awal_jam_masuk' => '11:00:00',
                    'jam_masuk' => '11:30:00',
                    'akhir_jam_masuk' => '12:00:00',
                    'jam_pulang' => '13:00:00',
                    'is_active' => true
                ],
                [
                    'kode_jam_kerja' => 'IMAM01',
                    'shift_ke' => 3,
                    'nama_shift' => 'Ashar',
                    'awal_jam_masuk' => '14:00:00',
                    'jam_masuk' => '14:30:00',
                    'akhir_jam_masuk' => '15:00:00',
                    'jam_pulang' => '16:00:00',
                    'is_active' => true
                ],
                [
                    'kode_jam_kerja' => 'IMAM01',
                    'shift_ke' => 4,
                    'nama_shift' => 'Maghrib',
                    'awal_jam_masuk' => '17:00:00',
                    'jam_masuk' => '17:30:00',
                    'akhir_jam_masuk' => '18:00:00',
                    'jam_pulang' => '19:00:00',
                    'is_active' => true
                ],
                [
                    'kode_jam_kerja' => 'IMAM01',
                    'shift_ke' => 5,
                    'nama_shift' => 'Isya',
                    'awal_jam_masuk' => '18:00:00',
                    'jam_masuk' => '18:30:00',
                    'akhir_jam_masuk' => '19:00:00',
                    'jam_pulang' => '20:00:00',
                    'is_active' => true
                ]
            ];

            foreach ($shifts as $shift) {
                DB::table('jam_kerja_shifts')->insert(array_merge($shift, [
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]));
            }

            DB::commit();

            $this->command->info('✅ Jam Kerja Imam & Muazin berhasil dibuat dengan 5 waktu sholat!');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('❌ Error: ' . $e->getMessage());
        }
    }
}
