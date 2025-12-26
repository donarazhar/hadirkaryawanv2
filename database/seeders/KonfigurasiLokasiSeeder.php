<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KonfigurasiLokasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('📍 Seeding Konfigurasi Lokasi...');

        // Get semua cabang untuk membuat konfigurasi lokasi
        $cabang = DB::table('cabang')->get();

        if ($cabang->isEmpty()) {
            $this->command->warn('⚠️  Tidak ada data cabang. Jalankan CabangSeeder terlebih dahulu!');
            return;
        }

        $count = 0;

        foreach ($cabang as $c) {
            // Cek apakah sudah ada
            $existing = DB::table('konfigurasi_lokasi')
                ->where('lokasi_kantor', $c->lokasi_cabang)
                ->first();

            if ($existing) {
                $this->command->warn("  ⚠️  Konfigurasi untuk {$c->nama_cabang} sudah ada, skip...");
                continue;
            }

            // Insert konfigurasi lokasi
            DB::table('konfigurasi_lokasi')->insert([
                'lokasi_kantor' => $c->lokasi_cabang,
                'radius' => $c->radius_cabang,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

            $count++;
            $this->command->info("  ✓ {$c->nama_cabang} - Radius: {$c->radius_cabang}m");
        }

        // Tambah konfigurasi lokasi tambahan (opsional)
        $lokasiTambahan = [
            [
                'lokasi_kantor' => '-6.200000,106.816666', // Monas Jakarta
                'radius' => 200,
            ],
            [
                'lokasi_kantor' => '-6.914744,107.609810', // Gedung Sate Bandung
                'radius' => 150,
            ],
        ];

        foreach ($lokasiTambahan as $lokasi) {
            // Cek duplikat
            $existing = DB::table('konfigurasi_lokasi')
                ->where('lokasi_kantor', $lokasi['lokasi_kantor'])
                ->first();

            if (!$existing) {
                DB::table('konfigurasi_lokasi')->insert([
                    'lokasi_kantor' => $lokasi['lokasi_kantor'],
                    'radius' => $lokasi['radius'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);

                $count++;
                $this->command->info("  ✓ Lokasi Tambahan - Radius: {$lokasi['radius']}m");
            }
        }

        $this->command->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info("✅ Selesai! Total {$count} konfigurasi lokasi berhasil dibuat");
        $this->command->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    }
}
