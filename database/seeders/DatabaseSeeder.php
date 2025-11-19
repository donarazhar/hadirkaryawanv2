<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CabangSeeder::class,
            DepartemenSeeder::class,
            UserSeeder::class,
            KaryawanSeeder::class,
            JamKerjaSeeder::class,
            SetupJamKerjaKaryawanSeeder::class,
            PresensiSeeder::class,
            PengajuanCutiSeeder::class,


        ]);
    }
}
