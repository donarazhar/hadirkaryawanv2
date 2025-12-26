<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PengajuanIzinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('📝 Seeding Pengajuan Izin...');

        // Get sample karyawan
        $karyawan = DB::table('karyawan')->limit(5)->get();

        if ($karyawan->isEmpty()) {
            $this->command->warn('⚠️  Tidak ada data karyawan. Jalankan KaryawanSeeder terlebih dahulu!');
            return;
        }

        $pengajuanIzin = [];
        $count = 0;

        // Generate sample izin untuk karyawan
        foreach ($karyawan as $index => $k) {
            $tglMulai = Carbon::now()->subDays(rand(1, 30));
            
            // Izin 1 hari
            $pengajuanIzin[] = [
                'kode_izin' => 'IZ' . date('Ym') . str_pad($count + 1, 3, '0', STR_PAD_LEFT),
                'nik' => $k->nik,
                'kode_cuti' => null,
                'tgl_izin_dari' => $tglMulai->format('Y-m-d'),
                'tgl_izin_sampai' => $tglMulai->copy()->format('Y-m-d'),
                'status' => 'i', // i=izin
                'keterangan' => 'Izin keperluan keluarga',
                'status_approved' => rand(0, 1), // Random approved/pending
                'doc_sid' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            $count++;

            // Sakit dengan surat dokter (setiap 2 karyawan)
            if ($index % 2 == 0) {
                $tglMulai = Carbon::now()->subDays(rand(5, 15));
                $pengajuanIzin[] = [
                    'kode_izin' => 'SK' . date('Ym') . str_pad($count + 1, 3, '0', STR_PAD_LEFT),
                    'nik' => $k->nik,
                    'kode_cuti' => 'CT002', // Cuti Sakit
                    'tgl_izin_dari' => $tglMulai->format('Y-m-d'),
                    'tgl_izin_sampai' => $tglMulai->copy()->addDays(2)->format('Y-m-d'),
                    'status' => 's', // s=sakit
                    'keterangan' => 'Sakit demam dan flu',
                    'status_approved' => '1', // Approved
                    'doc_sid' => 'surat_dokter_' . $k->nik . '.pdf',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
                $count++;
            }

            // Cuti tahunan (setiap 3 karyawan)
            if ($index % 3 == 0) {
                $tglMulai = Carbon::now()->subDays(rand(20, 40));
                $pengajuanIzin[] = [
                    'kode_izin' => 'CT' . date('Ym') . str_pad($count + 1, 3, '0', STR_PAD_LEFT),
                    'nik' => $k->nik,
                    'kode_cuti' => 'CT001', // Cuti Tahunan
                    'tgl_izin_dari' => $tglMulai->format('Y-m-d'),
                    'tgl_izin_sampai' => $tglMulai->copy()->addDays(4)->format('Y-m-d'),
                    'status' => 'c', // c=cuti
                    'keterangan' => 'Cuti tahunan - liburan akhir tahun',
                    'status_approved' => '1', // Approved
                    'doc_sid' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
                $count++;
            }
        }

        // Insert ke database
        foreach ($pengajuanIzin as $izin) {
            DB::table('pengajuan_izin')->insert($izin);
            
            $statusText = $izin['status'] === 'i' ? 'Izin' : ($izin['status'] === 's' ? 'Sakit' : 'Cuti');
            $approvedIcon = $izin['status_approved'] == '1' ? '✓' : '⏳';
            
            $namaKaryawan = DB::table('karyawan')->where('nik', $izin['nik'])->value('nama_lengkap');
            
            $this->command->info("  {$approvedIcon} {$izin['kode_izin']} - {$namaKaryawan} ({$statusText})");
        }

        $this->command->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info("✅ Selesai! Total {$count} pengajuan izin berhasil dibuat");
        $this->command->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

        // Show statistics
        $approved = DB::table('pengajuan_izin')->where('status_approved', '1')->count();
        $pending = DB::table('pengajuan_izin')->where('status_approved', '0')->count();
        
        $this->command->info("\n📊 Statistics:");
        $this->command->table(
            ['Status', 'Count'],
            [
                ['Approved', $approved],
                ['Pending', $pending],
                ['Total', $count],
            ]
        );
    }
}