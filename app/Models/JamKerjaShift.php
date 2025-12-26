<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JamKerjaShift extends Model
{
    protected $table = 'jam_kerja_shifts';

    protected $fillable = [
        'kode_jam_kerja',
        'shift_ke',
        'nama_shift',
        'awal_jam_masuk',
        'jam_masuk',
        'akhir_jam_masuk',
        'jam_pulang',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'awal_jam_masuk' => 'datetime:H:i',
        'jam_masuk' => 'datetime:H:i',
        'akhir_jam_masuk' => 'datetime:H:i',
        'jam_pulang' => 'datetime:H:i',
    ];

    /**
     * Relationship: JamKerjaShift belongs to JamKerja
     */
    public function jamKerja()
    {
        return $this->belongsTo(JamKerja::class, 'kode_jam_kerja', 'kode_jam_kerja');
    }

    /**
     * Scope: Active shifts only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Order by shift
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('shift_ke', 'ASC');
    }
}
