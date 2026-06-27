<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Unit;
use App\Models\Branch;
use App\Models\Organ;

class MasterDataSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('📍 Seeding Master Data (Units & Organs) untuk YPI Al Azhar Pusat...');

        // Gunakan Branch yang sudah dibuat oleh DatabaseSeeder
        $pusat = Branch::firstOrCreate(
            ['name' => 'YPI Al Azhar Pusat'],
            [
                'lokasi_cabang' => '-6.234352072999432, 106.80019122741929',
                'radius_cabang' => 100,
                'qr_token'      => Str::random(32),
            ]
        );

        // Helper untuk buat organ
        $createOrgan = function ($unit, $organName) {
            return Organ::firstOrCreate(
                ['name' => $organName, 'unit_id' => $unit->id]
            );
        };

        // 1. Sekretariat
        $sekretariat = Unit::firstOrCreate(
            ['name' => 'Sekretariat YPI Al Azhar', 'branch_id' => $pusat->id],
            ['is_sekretariat' => true, 'code' => 'SEK']
        );
        $createOrgan($sekretariat, 'Kepala Sekretariat');
        $createOrgan($sekretariat, 'Bagian Tata Usaha');
        $createOrgan($sekretariat, 'Subag Rumah Tangga');
        $createOrgan($sekretariat, 'Subag Persuratan');
        $createOrgan($sekretariat, 'Subag Keamanan');

        // 2. Bagian Kepegawaian
        $kepegawaian = Unit::firstOrCreate(
            ['name' => 'Bagian Kepegawaian', 'branch_id' => $pusat->id],
            ['is_sekretariat' => false, 'code' => 'KEP']
        );
        $createOrgan($kepegawaian, 'Kepala Bagian Kepegawaian');
        $createOrgan($kepegawaian, 'Subag Kesejahteraan Pegawai');
        $createOrgan($kepegawaian, 'Subag Administrasi Kepegawaian');
        $createOrgan($kepegawaian, 'Subag Pembinaan, Perencanaan & Pengembangan Karir Pegawai');

        // 3. Bagian Humas
        $humas = Unit::firstOrCreate(
            ['name' => 'Bagian Humas', 'branch_id' => $pusat->id],
            ['is_sekretariat' => false, 'code' => 'HUMAS']
        );
        $createOrgan($humas, 'Kepala Bagian Humas');
        $createOrgan($humas, 'Subag Komunikasi & Publikasi');
        $createOrgan($humas, 'Subag Pemasaran');

        // 4. Bagian Umum
        $umum = Unit::firstOrCreate(
            ['name' => 'Bagian Umum', 'branch_id' => $pusat->id],
            ['is_sekretariat' => false, 'code' => 'UMUM']
        );
        $createOrgan($umum, 'Kepala Bagian Umum');
        $createOrgan($umum, 'Subag Pengadaan');
        $createOrgan($umum, 'Subag Pemeliharaan');
        $createOrgan($umum, 'Subag Inventaris & Aset');

        // 5. Direktorat ITTD
        $ittd = Unit::firstOrCreate(
            ['name' => 'Direktorat ITTD', 'branch_id' => $pusat->id],
            ['is_sekretariat' => false, 'code' => 'ITTD']
        );
        $createOrgan($ittd, 'Kepala Dirat ITTD');
        $createOrgan($ittd, 'Subag Teknologi Informasi');
        $createOrgan($ittd, 'Subag Transformasi Digital');

        // 6. Pusdiklat Anyer
        $anyer = Unit::firstOrCreate(
            ['name' => 'Pusdiklat Anyer', 'branch_id' => $pusat->id],
            ['is_sekretariat' => false, 'code' => 'ANYER']
        );
        $createOrgan($anyer, 'Kepala Pusdiklat Anyer');
        $createOrgan($anyer, 'Staff Pusdiklat');

        // 7. Bagian Keagamaan
        $keagamaan = Unit::firstOrCreate(
            ['name' => 'Bagian Keagamaan', 'branch_id' => $pusat->id],
            ['is_sekretariat' => false, 'code' => 'MAA']
        );
        $createOrgan($keagamaan, 'Kepala Bagian Keagamaan');
        $createOrgan($keagamaan, 'Imam Masjid');
        $createOrgan($keagamaan, 'Muazin');
        $createOrgan($keagamaan, 'Staff Masjid');

        $this->command->info('✅ Master Data Berhasil Dibuat! (1 Cabang: YPI Al Azhar Pusat)');
    }
}
