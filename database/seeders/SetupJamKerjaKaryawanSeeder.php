<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SetupJamKerjaKaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('⚙️  Seeding Konfigurasi Jam Kerja per Departemen...');

        $cabang = DB::table('cabang')->get();
        $departemen = DB::table('departemen')->get();
        $jamKerja = DB::table('jam_kerja')->where('tipe_jam_kerja', 'regular')->get();

        if ($cabang->isEmpty() || $departemen->isEmpty() || $jamKerja->isEmpty()) {
            $this->command->warn('⚠️  Data master belum lengkap. Jalankan seeder lain terlebih dahulu!');
            return;
        }

        $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $countKonfig = 0;
        $countDetail = 0;

        foreach ($cabang as $c) {
            foreach ($departemen as $d) {
                // Skip departemen KEAG (pake multi-shift)
                if ($d->kode_dept === 'KEAG') {
                    continue;
                }

                $kodeJkDept = $c->kode_cabang . '-' . $d->kode_dept;

                // Insert konfigurasi
                DB::table('konfigurasi_jk_dept')->insert([
                    'kode_jk_dept' => $kodeJkDept,
                    'kode_cabang' => $c->kode_cabang,
                    'kode_dept' => $d->kode_dept,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                $countKonfig++;

                // Pilih jam kerja random untuk dept ini
                $selectedJK = $jamKerja->random();

                // Insert detail untuk setiap hari
                foreach ($hari as $h) {
                    DB::table('konfigurasi_jk_dept_detail')->insert([
                        'kode_jk_dept' => $kodeJkDept,
                        'kode_jam_kerja' => $selectedJK->kode_jam_kerja,
                        'hari' => $h,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);

                    $countDetail++;
                }

                $this->command->info("  ✓ {$kodeJkDept} - {$d->nama_dept} @ {$c->nama_cabang} ({$selectedJK->nama_jam_kerja})");
            }
        }

        // Setup khusus untuk KEAG (multi-shift)
        $this->setupMultiShiftDepartment();

        $this->command->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info("✅ Selesai! Total {$countKonfig} konfigurasi dan {$countDetail} detail berhasil dibuat");
        $this->command->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    }

    /**
     * Setup konfigurasi khusus untuk departemen KEAG (multi-shift)
     */
    private function setupMultiShiftDepartment()
    {
        $this->command->info("\n🕌 Setup Konfigurasi Multi-Shift (KEAG)...");

        $cabang = DB::table('cabang')->get();
        $deptKeag = DB::table('departemen')->where('kode_dept', 'KEAG')->first();
        
        if (!$deptKeag) {
            $this->command->warn('  ⚠️  Departemen KEAG tidak ditemukan, skip multi-shift setup');
            return;
        }

        $jamKerjaImam = DB::table('jam_kerja')
            ->where('tipe_jam_kerja', 'multi_shift')
            ->first();

        if (!$jamKerjaImam) {
            $this->command->warn('  ⚠️  Jam kerja multi-shift tidak ditemukan, skip');
            return;
        }

        $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Ahad'];

        foreach ($cabang as $c) {
            $kodeJkDept = $c->kode_cabang . '-KEAG';

            // Insert konfigurasi
            DB::table('konfigurasi_jk_dept')->insert([
                'kode_jk_dept' => $kodeJkDept,
                'kode_cabang' => $c->kode_cabang,
                'kode_dept' => 'KEAG',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // Insert detail untuk setiap hari (termasuk Ahad)
            foreach ($hari as $h) {
                DB::table('konfigurasi_jk_dept_detail')->insert([
                    'kode_jk_dept' => $kodeJkDept,
                    'kode_jam_kerja' => $jamKerjaImam->kode_jam_kerja,
                    'hari' => $h,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            $this->command->info("  🕌 {$kodeJkDept} - Keagamaan @ {$c->nama_cabang} (Multi-Shift: {$jamKerjaImam->nama_jam_kerja})");
        }
    }
}