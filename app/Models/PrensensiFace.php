<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class PrensensiFace extends Model
{
    use HasFactory;

    protected $table = 'presensi_face';

    public $timestamps = true; // ✅ Pastikan timestamps aktif

    protected $fillable = [
        'nik',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'lokasi',
        'status'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jam_masuk' => 'string', // atau 'datetime:H:i:s'
        'jam_pulang' => 'string', // atau 'datetime:H:i:s'
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relationship dengan Karyawan
     */
    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class, 'nik', 'nik');
    }

    /**
     * Check apakah sudah absen masuk
     */
    public function hasCheckedIn()
    {
        return !empty($this->jam_masuk);
    }

    /**
     * Check apakah sudah absen pulang
     */
    public function hasCheckedOut()
    {
        return !empty($this->jam_pulang);
    }

    /**
     * Get jam masuk formatted
     */
    public function getJamMasukFormattedAttribute()
    {
        return $this->jam_masuk ? Carbon::parse($this->jam_masuk)->format('H:i') : '-';
    }

    /**
     * Get jam pulang formatted
     */
    public function getJamPulangFormattedAttribute()
    {
        return $this->jam_pulang ? Carbon::parse($this->jam_pulang)->format('H:i') : '-';
    }
}