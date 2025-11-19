<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JamKerja extends Model
{
    use HasFactory;

    protected $table = 'jam_kerja';
    protected $primaryKey = 'kode_jam_kerja';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'kode_jam_kerja',
        'nama_jam_kerja',
        'awal_jam_masuk',
        'jam_masuk',
        'akhir_jam_masuk',
        'jam_pulang',
        'lintashari'
    ];

    /**
     * Relasi ke konfigurasi jam kerja departemen
     */
    public function konfigurasiJkDeptDetail()
    {
        return $this->hasMany(KonfigurasiJkDeptDetail::class, 'kode_jam_kerja', 'kode_jam_kerja');
    }

    /**
     * Relasi ke presensi
     */
    public function presensi()
    {
        return $this->hasMany(Presensi::class, 'kode_jam_kerja', 'kode_jam_kerja');
    }
}
