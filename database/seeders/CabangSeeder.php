<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cabang;

class CabangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cabang = [
            [
                'kode_cabang' => 'CBG001',
                'nama_cabang' => 'YPI Al Azhar Jakarta Pusat',
                'lokasi_cabang' => '-6.234870055835135,106.79965076374243',
                'radius_cabang' => 100
            ],
            [
                'kode_cabang' => 'CBG002',
                'nama_cabang' => 'YPI Al Azhar Bandung',
                'lokasi_cabang' => '-6.9175,107.6191',
                'radius_cabang' => 150
            ],
            [
                'kode_cabang' => 'CBG003',
                'nama_cabang' => 'YPI Al Azhar Surabaya',
                'lokasi_cabang' => '-7.2575,112.7521',
                'radius_cabang' => 100
            ],
            [
                'kode_cabang' => 'CBG004',
                'nama_cabang' => 'YPI Al Azhar Medan',
                'lokasi_cabang' => '3.5952,98.6722',
                'radius_cabang' => 120
            ],
            [
                'kode_cabang' => 'CBG005',
                'nama_cabang' => 'YPI Al Azhar Makassar',
                'lokasi_cabang' => '-5.1477,119.4327',
                'radius_cabang' => 100
            ],
        ];

        foreach ($cabang as $item) {
            Cabang::create($item);
        }
    }
}
