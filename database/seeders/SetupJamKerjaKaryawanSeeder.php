<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Karyawan;

class SetupJamKerjaKaryawanSeeder extends Seeder
{
    public function run(): void
    {
        $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        // Ambil semua kombinasi unik cabang + departemen
        $kombinasi = DB::table('karyawan')
            ->select('kode_cabang', 'kode_dept')
            ->distinct()
            ->get();

        echo "Membuat konfigurasi jam kerja...\n";

        foreach ($kombinasi as $k) {
            $kode_jk_dept = 'JKD' . strtoupper(substr($k->kode_cabang, 0, 3)) . strtoupper(substr($k->kode_dept, 0, 3));

            // Cek apakah sudah ada
            $cek = DB::table('konfigurasi_jk_dept')
                ->where('kode_cabang', $k->kode_cabang)
                ->where('kode_dept', $k->kode_dept)
                ->first();

            if (!$cek) {
                // Insert konfigurasi jk dept
                DB::table('konfigurasi_jk_dept')->insert([
                    'kode_jk_dept' => $kode_jk_dept,
                    'kode_cabang' => $k->kode_cabang,
                    'kode_dept' => $k->kode_dept,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // Insert detail untuk setiap hari
                foreach ($hari as $h) {
                    DB::table('konfigurasi_jk_dept_detail')->insert([
                        'kode_jk_dept' => $kode_jk_dept,
                        'kode_jam_kerja' => 'JK01', // Default Shift Pagi
                        'hari' => $h,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }

                echo "✓ Setup jam kerja untuk Cabang: {$k->kode_cabang}, Dept: {$k->kode_dept}\n";
            } else {
                echo "- Jam kerja untuk Cabang: {$k->kode_cabang}, Dept: {$k->kode_dept} sudah ada\n";
            }
        }

        echo "\nSelesai!\n";
    }
}
