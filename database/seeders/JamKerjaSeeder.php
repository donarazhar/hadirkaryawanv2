<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JamKerjaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Include JAM KERJA REGULAR + MULTI-SHIFT
     */
    public function run(): void
    {
        $this->command->info('⏰ Seeding Jam Kerja...');

        DB::beginTransaction();

        try {
            // ========================================
            // JAM KERJA REGULAR
            // ========================================
            $this->command->info('  📋 Creating Regular Jam Kerja...');
            
            $jamKerjaRegular = [
                [
                    'kode_jam_kerja' => 'JK01',
                    'nama_jam_kerja' => 'Shift Pagi',
                    'awal_jam_masuk' => '07:00:00',
                    'jam_masuk' => '08:00:00',
                    'akhir_jam_masuk' => '08:30:00',
                    'jam_pulang' => '16:00:00',
                    'lintashari' => '0',
                    'tipe_jam_kerja' => 'regular',
                    'total_shift' => 1
                ],
                [
                    'kode_jam_kerja' => 'JK02',
                    'nama_jam_kerja' => 'Shift Siang',
                    'awal_jam_masuk' => '11:00:00',
                    'jam_masuk' => '12:00:00',
                    'akhir_jam_masuk' => '12:30:00',
                    'jam_pulang' => '20:00:00',
                    'lintashari' => '0',
                    'tipe_jam_kerja' => 'regular',
                    'total_shift' => 1
                ],
                [
                    'kode_jam_kerja' => 'JK03',
                    'nama_jam_kerja' => 'Shift Malam',
                    'awal_jam_masuk' => '19:00:00',
                    'jam_masuk' => '20:00:00',
                    'akhir_jam_masuk' => '20:30:00',
                    'jam_pulang' => '04:00:00',
                    'lintashari' => '1',
                    'tipe_jam_kerja' => 'regular',
                    'total_shift' => 1
                ],
                [
                    'kode_jam_kerja' => 'JK04',
                    'nama_jam_kerja' => 'Non-Shift',
                    'awal_jam_masuk' => '07:30:00',
                    'jam_masuk' => '08:00:00',
                    'akhir_jam_masuk' => '09:00:00',
                    'jam_pulang' => '17:00:00',
                    'lintashari' => '0',
                    'tipe_jam_kerja' => 'regular',
                    'total_shift' => 1
                ],
                [
                    'kode_jam_kerja' => 'JK05',
                    'nama_jam_kerja' => 'Fleksibel',
                    'awal_jam_masuk' => '07:00:00',
                    'jam_masuk' => '09:00:00',
                    'akhir_jam_masuk' => '10:00:00',
                    'jam_pulang' => '18:00:00',
                    'lintashari' => '0',
                    'tipe_jam_kerja' => 'regular',
                    'total_shift' => 1
                ],
            ];

            foreach ($jamKerjaRegular as $jk) {
                DB::table('jam_kerja')->insert(array_merge($jk, [
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]));
                $this->command->info("    ✓ {$jk['kode_jam_kerja']} - {$jk['nama_jam_kerja']} (Regular)");
            }

            // ========================================
            // JAM KERJA MULTI-SHIFT
            // ========================================
            $this->command->info('  🕌 Creating Multi-Shift Jam Kerja...');

            // 1. Jam Kerja Master untuk Imam & Muazin
            DB::table('jam_kerja')->insert([
                'kode_jam_kerja' => 'IMAM01',
                'nama_jam_kerja' => 'Imam & Muazin',
                'awal_jam_masuk' => '04:00:00',  // Subuh awal
                'jam_masuk' => '04:30:00',       // Subuh normal
                'akhir_jam_masuk' => '06:00:00', // Subuh akhir
                'jam_pulang' => '20:00:00',      // Isya selesai
                'lintashari' => '0',
                'tipe_jam_kerja' => 'multi_shift',
                'total_shift' => 5,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            $this->command->info("    ✓ IMAM01 - Imam & Muazin (Multi-Shift, 5 waktu sholat)");

            // 2. Shifts Detail untuk IMAM01
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
                $this->command->info("      - Shift {$shift['shift_ke']}: {$shift['nama_shift']} ({$shift['jam_masuk']} - {$shift['jam_pulang']})");
            }

            DB::commit();

            $this->command->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            $this->command->info('✅ Selesai! Total 6 jam kerja berhasil dibuat');
            $this->command->info('   - 5 Regular (JK01-JK05)');
            $this->command->info('   - 1 Multi-Shift (IMAM01) dengan 5 shifts');
            $this->command->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('❌ Error: ' . $e->getMessage());
            throw $e;
        }
    }
}