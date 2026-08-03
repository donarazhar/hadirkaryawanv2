<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JamKerja extends Model
{
    protected $table = 'jam_kerja';
    protected $primaryKey = 'kode_jam_kerja';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_jam_kerja',
        'nama_jam_kerja',
        'awal_jam_masuk',
        'jam_masuk',
        'akhir_jam_masuk',
        'jam_pulang',
        'lintashari',
        'tipe_jam_kerja',    // NEW
        'total_shift'        // NEW
    ];

    protected $casts = [
        'lintashari' => 'boolean',
        'total_shift' => 'integer'
    ];

    /**
     * Relationship: JamKerja has many shifts
     */
    public function shifts()
    {
        return $this->hasMany(JamKerjaShift::class, 'kode_jam_kerja', 'kode_jam_kerja')
            ->ordered();
    }

    /**
     * Relationship: JamKerja has many KonfigurasiJkUnitDetail
     */
    public function KonfigurasiJkUnitDetail()
    {
        return $this->hasMany(KonfigurasiJkUnitDetail::class, 'kode_jam_kerja', 'kode_jam_kerja');
    }

    /**
     * Alias relationship untuk kompatibilitas dengan controller
     * (konfigurasiJkDeptDetail = KonfigurasiJkUnitDetail)
     */
    public function konfigurasiJkDeptDetail()
    {
        return $this->hasMany(KonfigurasiJkUnitDetail::class, 'kode_jam_kerja', 'kode_jam_kerja');
    }

    /**
     * Relationship: JamKerja has many Presensi
     */
    public function presensi()
    {
        return $this->hasMany(Presensi::class, 'kode_jam_kerja', 'kode_jam_kerja');
    }

    /**
     * Check if this is multi-shift work schedule
     */
    public function isMultiShift()
    {
        return $this->tipe_jam_kerja === 'multi_shift' && $this->total_shift > 1;
    }

    /**
     * Get active shifts
     */
    public function getActiveShifts()
    {
        return $this->shifts()->active()->get();
    }
}
