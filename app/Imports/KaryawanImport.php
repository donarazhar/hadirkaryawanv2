<?php

namespace App\Imports;

use App\Models\Karyawan;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KaryawanImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Skip jika NIK kosong
        if (!isset($row['nik'])) {
            return null;
        }

        // Cek jika Karyawan sudah ada
        $karyawan = Karyawan::where('nik', $row['nik'])->first();
        if ($karyawan) {
            // Opsional: Update data yang sudah ada, atau dilewati
            return null;
        }

        return new Karyawan([
            'nik'          => $row['nik'],
            'nama_lengkap' => $row['nama_lengkap'],
            'jabatan'      => $row['jabatan'],
            'no_hp'        => $row['no_hp'],
            'email'        => $row['email'] ?? null,
            'kode_dept'    => $row['kode_dept'],
            'kode_cabang'  => $row['kode_cabang'],
            'password'     => Hash::make($row['nik']), // Default password adalah NIK
        ]);
    }
}
