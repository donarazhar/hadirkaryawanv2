<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensi';
    public $timestamps = false;

    protected $fillable = [
        'nik',
        'tgl_presensi',
        'jam_in',
        'jam_out',
        'foto_in',
        'foto_out',
        'lokasi_in',
        'lokasi_out',
        'kode_jam_kerja',
        'status',
        'kode_izin'
    ];

    protected $casts = [
        'tgl_presensi' => 'date',
    ];

    // Relationships
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'nik', 'nik');
    }

    public function jamKerja()
    {
        return $this->belongsTo(JamKerja::class, 'kode_jam_kerja', 'kode_jam_kerja');
    }

    // Scope untuk filter
    public function scopeToday($query)
    {
        return $query->whereDate('tgl_presensi', today());
    }

    public function scopeByNik($query, $nik)
    {
        return $query->where('nik', $nik);
    }
}
