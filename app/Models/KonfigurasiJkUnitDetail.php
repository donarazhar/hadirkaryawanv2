<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonfigurasiJkUnitDetail extends Model
{
    use HasFactory;

    protected $table = 'konfigurasi_jk_unit_detail';
    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = null;

    protected $fillable = [
        'kode_jk_unit',
        'kode_jam_kerja',
        'hari'
    ];

    // Relationships
    public function konfigurasi()
    {
        return $this->belongsTo(KonfigurasiJkUnit::class, 'kode_jk_unit', 'kode_jk_unit');
    }

    public function jamKerja()
    {
        return $this->belongsTo(JamKerja::class, 'kode_jam_kerja', 'kode_jam_kerja');
    }
}
