<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FaceDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🎭 Seeding Face Data...');

        // Get semua karyawan
        $karyawan = DB::table('karyawan')->get();

        if ($karyawan->isEmpty()) {
            $this->command->warn('⚠️  Tidak ada data karyawan. Jalankan KaryawanSeeder terlebih dahulu!');
            return;
        }

        $count = 0;
        $skipped = 0;

        foreach ($karyawan as $k) {
            // Cek apakah sudah ada face data
            $existing = DB::table('face_data')
                ->where('nik', $k->nik)
                ->first();

            if ($existing) {
                $this->command->warn("  ⚠️  Face data untuk {$k->nama_lengkap} sudah ada, skip...");
                $skipped++;
                continue;
            }

            // Generate dummy face descriptor
            // Face descriptor biasanya array 128 float dari face-api.js
            $faceDescriptor = $this->generateDummyFaceDescriptor();

            // Random status: 90% active, 10% inactive
            $status = rand(1, 100) <= 90 ? 'active' : 'inactive';

            // Random enrollment count (1-3)
            $enrollmentCount = rand(1, 3);

            // Insert face data
            DB::table('face_data')->insert([
                'nik' => $k->nik,
                'face_descriptor' => json_encode($faceDescriptor),
                'face_image' => $k->foto, // Gunakan foto dari karyawan jika ada
                'status' => $status,
                'enrollment_count' => $enrollmentCount,
                'last_updated' => Carbon::now()->subDays(rand(0, 30)),
                'created_at' => Carbon::now()->subDays(rand(30, 60)),
                'updated_at' => Carbon::now()
            ]);

            $count++;
            
            $statusIcon = $status === 'active' ? '✓' : '✗';
            $this->command->info("  {$statusIcon} {$k->nama_lengkap} ({$k->nik}) - Status: {$status} - Enrollments: {$enrollmentCount}");
        }

        $this->command->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info("✅ Selesai! Total {$count} face data berhasil dibuat");
        if ($skipped > 0) {
            $this->command->warn("⚠️  {$skipped} data dilewati (sudah ada)");
        }
        $this->command->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

        // Show statistics
        $this->showStatistics();
    }

    /**
     * Generate dummy face descriptor
     * Face descriptor real adalah array 128 float dari face-api.js
     */
    private function generateDummyFaceDescriptor()
    {
        $descriptor = [];
        
        // Generate 128 random float values between -1 and 1
        for ($i = 0; $i < 128; $i++) {
            // Random float dengan 6 decimal places
            $descriptor[] = round((rand(-1000000, 1000000) / 1000000), 6);
        }

        return $descriptor;
    }

    /**
     * Show face data statistics
     */
    private function showStatistics()
    {
        $total = DB::table('face_data')->count();
        $active = DB::table('face_data')->where('status', 'active')->count();
        $inactive = DB::table('face_data')->where('status', 'inactive')->count();
        
        $avgEnrollment = DB::table('face_data')->avg('enrollment_count');

        $this->command->info("\n📊 Face Data Statistics:");
        $this->command->table(
            ['Metric', 'Value'],
            [
                ['Total Face Data', $total],
                ['Active', $active],
                ['Inactive', $inactive],
                ['Avg Enrollment Count', round($avgEnrollment, 2)],
            ]
        );

        // Show sample face data
        $sample = DB::table('face_data')
            ->join('karyawan', 'face_data.nik', '=', 'karyawan.nik')
            ->select('face_data.*', 'karyawan.nama_lengkap')
            ->limit(5)
            ->get();

        if ($sample->isNotEmpty()) {
            $this->command->info("\n📋 Sample Face Data:");
            $this->command->table(
                ['NIK', 'Nama', 'Status', 'Enrollments', 'Last Updated'],
                $sample->map(function ($item) {
                    return [
                        $item->nik,
                        $item->nama_lengkap,
                        $item->status,
                        $item->enrollment_count,
                        Carbon::parse($item->last_updated)->diffForHumans(),
                    ];
                })->toArray()
            );
        }
    }
}