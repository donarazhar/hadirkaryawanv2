<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Karyawan;
use App\Models\Organ;
use Illuminate\Support\Facades\Hash;

class PdfKaryawanSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('📍 Seeding Karyawan berdasarkan data PdfSeeder Persuratan...');

        $pass = Hash::make('123456');
        $nikCounter = 203051001;

        $createKaryawan = function($organName, $namaLengkap) use (&$nikCounter, $pass) {
            $organ = Organ::where('name', $organName)->first();
            
            if ($organ) {
                Karyawan::updateOrCreate(
                    ['nama_lengkap' => $namaLengkap],
                    [
                        'nik' => (string)$nikCounter,
                        'jabatan' => substr($organName, 0, 20), // Truncate to 20 chars due to migration limit
                        'no_hp' => '0812' . rand(10000000, 99999999),
                        'password' => $pass,
                        'organ_id' => $organ->id
                    ]
                );
                $nikCounter++;
            } else {
                $this->command->warn("Organ '$organName' tidak ditemukan. Karyawan $namaLengkap dilewati.");
            }
        };

        // 1. Sekretariat
        $createKaryawan('Kepala Sekretariat', 'Drs. H. Yayat Suyatna, M.M.');
        $createKaryawan('Bagian Tata Usaha', 'Zainal Arifin, S.Pd.');
        $createKaryawan('Subag Rumah Tangga', 'Bahruddin');
        $createKaryawan('Subag Persuratan', 'Ryan Ariska, S.H.');
        $createKaryawan('Subag Keamanan', 'Nasroni');

        // 2. Kepegawaian
        $createKaryawan('Kepala Bagian Kepegawaian', 'Ngadiman, M.Pd');
        $createKaryawan('Subag Kesejahteraan Pegawai', 'H. Alasri S.Kom');
        $createKaryawan('Subag Administrasi Kepegawaian', 'Winarto, S.Pd.');
        $createKaryawan('Subag Pembinaan, Perencanaan & Pengembangan Karir Pegawai', 'Hasan Umar, S.Pd');

        // 3. Humas
        $createKaryawan('Kepala Bagian Humas', 'Subari, S.Pd');
        $createKaryawan('Subag Komunikasi & Publikasi', 'Eman Suherman, S.Psi.');
        $createKaryawan('Subag Pemasaran', 'Teguh Budi Suswanto, S.E.');

        // 4. Umum
        $createKaryawan('Kepala Bagian Umum', 'Syamsul Arifin');
        $createKaryawan('Subag Pengadaan', 'Pandu Wijaya, S.E , M.H');
        $createKaryawan('Subag Pemeliharaan', 'Nursyamsi Atorida, S. Sos.');
        $createKaryawan('Subag Inventaris & Aset', 'Yana Hendarsah, S.E.');

        // 5. ITTD
        $createKaryawan('Kepala Dirat ITTD', 'Damarahmad Setiobudi, M.M');
        $createKaryawan('Subag Teknologi Informasi', 'Mohammad Noeseir, M.M.');
        $createKaryawan('Subag Transformasi Digital', 'Doni Sutrisno');

        // 6. Pusdiklat Anyer
        $createKaryawan('Kepala Pusdiklat Anyer', 'Subur Kurniawan, S.Si');

        $this->command->info('✅ Karyawan berhasil di-seed.');
    }
}
