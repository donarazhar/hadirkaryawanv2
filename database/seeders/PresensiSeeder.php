<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PresensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('📋 Seeding Presensi Regular...');

        // Get karyawan non-KEAG (regular shift)
        $karyawan = DB::table('karyawan')
            ->where('kode_dept', '!=', 'KEAG')
            ->get();

        if ($karyawan->isEmpty()) {
            $this->command->warn('⚠️  Tidak ada karyawan regular. Jalankan KaryawanSeeder terlebih dahulu!');
            return;
        }

        $count = 0;
        $days = 14; // Generate 14 hari terakhir

        foreach ($karyawan as $k) {
            // Get jam kerja karyawan
            $jamKerja = DB::table('konfigurasi_jk_dept_detail')
                ->join('konfigurasi_jk_dept', 'konfigurasi_jk_dept_detail.kode_jk_dept', '=', 'konfigurasi_jk_dept.kode_jk_dept')
                ->join('jam_kerja', 'konfigurasi_jk_dept_detail.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
                ->where('konfigurasi_jk_dept.kode_cabang', $k->kode_cabang)
                ->where('konfigurasi_jk_dept.kode_dept', $k->kode_dept)
                ->select('jam_kerja.*')
                ->first();

            if (!$jamKerja) {
                continue;
            }

            // Generate presensi untuk 14 hari terakhir
            for ($i = $days; $i >= 0; $i--) {
                $tanggal = Carbon::now()->subDays($i);
                
                // Skip Minggu
                if ($tanggal->dayOfWeek === 0) {
                    continue;
                }

                // 90% hadir, 5% izin, 5% alpha
                $random = rand(1, 100);
                
                if ($random <= 90) {
                    // Hadir
                    $jamMasuk = Carbon::parse($jamKerja->jam_masuk);
                    $jamPulang = Carbon::parse($jamKerja->jam_pulang);
                    
                    // Random terlambat (20% chance, max 30 menit)
                    if (rand(1, 100) <= 20) {
                        $jamMasuk->addMinutes(rand(5, 30));
                    } else {
                        // Tepat waktu atau lebih awal
                        $jamMasuk->subMinutes(rand(0, 15));
                    }

                    // Random pulang (normal ±30 menit)
                    $jamPulang->addMinutes(rand(-30, 30));

                    DB::table('presensi')->insert([
                        'nik' => $k->nik,
                        'tgl_presensi' => $tanggal->format('Y-m-d'),
                        'jam_in' => $jamMasuk->format('H:i:s'),
                        'jam_out' => $jamPulang->format('H:i:s'),
                        'foto_in' => 'uploads/absensi/' . $k->nik . '_in_' . $tanggal->format('Ymd') . '.jpg',
                        'foto_out' => 'uploads/absensi/' . $k->nik . '_out_' . $tanggal->format('Ymd') . '.jpg',
                        'lokasi_in' => $this->generateLokasi(),
                        'lokasi_out' => $this->generateLokasi(),
                        'kode_jam_kerja' => $jamKerja->kode_jam_kerja,
                        'shift_ke' => null, // Regular, bukan multi-shift
                        'nama_shift' => null,
                        'status' => 'h', // h = hadir
                        'kode_izin' => null,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);

                    $count++;
                } elseif ($random <= 95) {
                    // Izin - reference ke pengajuan_izin yang sudah ada
                    $izin = DB::table('pengajuan_izin')
                        ->where('nik', $k->nik)
                        ->where('status_approved', '1')
                        ->first();

                    DB::table('presensi')->insert([
                        'nik' => $k->nik,
                        'tgl_presensi' => $tanggal->format('Y-m-d'),
                        'jam_in' => null,
                        'jam_out' => null,
                        'foto_in' => null,
                        'foto_out' => null,
                        'lokasi_in' => null,
                        'lokasi_out' => null,
                        'kode_jam_kerja' => $jamKerja->kode_jam_kerja,
                        'shift_ke' => null,
                        'nama_shift' => null,
                        'status' => 'i', // i = izin
                        'kode_izin' => $izin ? $izin->kode_izin : null,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);

                    $count++;
                } else {
                    // Alpha (5%)
                    DB::table('presensi')->insert([
                        'nik' => $k->nik,
                        'tgl_presensi' => $tanggal->format('Y-m-d'),
                        'jam_in' => null,
                        'jam_out' => null,
                        'foto_in' => null,
                        'foto_out' => null,
                        'lokasi_in' => null,
                        'lokasi_out' => null,
                        'kode_jam_kerja' => $jamKerja->kode_jam_kerja,
                        'shift_ke' => null,
                        'nama_shift' => null,
                        'status' => 'a', // a = alpha
                        'kode_izin' => null,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);

                    $count++;
                }
            }

            $this->command->info("  ✓ {$k->nama_lengkap} - {$days} hari presensi");
        }

        $this->command->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info("✅ Selesai! Total {$count} presensi regular berhasil dibuat");
        $this->command->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

        // Show statistics
        $hadir = DB::table('presensi')->where('status', 'h')->count();
        $izin = DB::table('presensi')->where('status', 'i')->count();
        $alpha = DB::table('presensi')->where('status', 'a')->count();

        $this->command->info("\n📊 Presensi Regular Statistics:");
        $this->command->table(
            ['Status', 'Count', 'Percentage'],
            [
                ['Hadir', $hadir, round(($hadir / $count) * 100, 1) . '%'],
                ['Izin', $izin, round(($izin / $count) * 100, 1) . '%'],
                ['Alpha', $alpha, round(($alpha / $count) * 100, 1) . '%'],
                ['Total', $count, '100%'],
            ]
        );
    }

    /**
     * Generate random lokasi GPS
     */
    private function generateLokasi()
    {
        // Base: Jakarta Pusat coordinates
        $baseLat = -6.234870055835135;
        $baseLng = 106.79965076374243;

        // Random offset dalam radius 100m
        $latOffset = (rand(-100, 100) / 1000000);
        $lngOffset = (rand(-100, 100) / 1000000);

        return ($baseLat + $latOffset) . ',' . ($baseLng + $lngOffset);
    }
}