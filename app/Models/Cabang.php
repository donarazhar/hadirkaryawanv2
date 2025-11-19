<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    use HasFactory;

    protected $table = 'cabang';
    protected $primaryKey = 'kode_cabang';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'kode_cabang',
        'nama_cabang',
        'lokasi_cabang',
        'radius_cabang'
    ];

    // Relationships
    public function karyawan()
    {
        return $this->hasMany(Karyawan::class, 'kode_cabang', 'kode_cabang');
    }

    public function konfigurasiJkDept()
    {
        return $this->hasMany(KonfigurasiJkDept::class, 'kode_cabang', 'kode_cabang');
    }
}
