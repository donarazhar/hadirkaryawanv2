<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class PresensiFace extends Model
{
    use HasFactory;

    protected $table = 'presensi_face';

    public $timestamps = true;

    protected $fillable = [
        'nik',
        'tanggal',
        'shift_ke',        // ✅ ADDED for multi-shift
        'nama_shift',      // ✅ ADDED for multi-shift
        'jam_masuk',
        'jam_pulang',
        'lokasi',
        'status',
        'similarity_score',
        'foto',
        'keterangan'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jam_masuk' => 'string',
        'jam_pulang' => 'string',
        'shift_ke' => 'integer',
        'similarity_score' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // ========================================
    // RELATIONSHIPS
    // ========================================

    /**
     * Relationship dengan Karyawan
     */
    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class, 'nik', 'nik')
            ->with(['organ.unit.branch']);
    }

    /**
     * Get jam kerja shift detail (for multi-shift)
     */
    public function jamKerjaShift(): BelongsTo
    {
        return $this->belongsTo(JamKerjaShift::class, 'shift_ke', 'shift_ke')
            ->where('kode_jam_kerja', function ($query) {
                // Get jam kerja from karyawan
                $query->select('kode_jam_kerja')
                    ->from('karyawan')
                    ->join('organs', 'karyawan.organ_id', '=', 'organs.id')
                    ->join('konfigurasi_jk_unit', 'organs.unit_id', '=', 'konfigurasi_jk_unit.unit_id')
                    ->join('konfigurasi_jk_unit_detail', 'konfigurasi_jk_unit.kode_jk_unit', '=', 'konfigurasi_jk_unit_detail.kode_jk_unit')
                    ->where('karyawan.nik', $this->nik)
                    ->limit(1);
            });
    }

    // ========================================
    // ACCESSORS & HELPERS
    // ========================================

    /**
     * Check apakah sudah absen masuk
     */
    public function hasCheckedIn(): bool
    {
        return !empty($this->jam_masuk);
    }

    /**
     * Check apakah sudah absen pulang
     */
    public function hasCheckedOut(): bool
    {
        return !empty($this->jam_pulang);
    }

    /**
     * Check apakah presensi multi-shift
     */
    public function isMultiShift(): bool
    {
        return !is_null($this->shift_ke);
    }

    /**
     * Get jam masuk formatted
     */
    public function getJamMasukFormattedAttribute(): string
    {
        return $this->jam_masuk ? Carbon::parse($this->jam_masuk)->format('H:i') : '-';
    }

    /**
     * Get jam pulang formatted
     */
    public function getJamPulangFormattedAttribute(): string
    {
        return $this->jam_pulang ? Carbon::parse($this->jam_pulang)->format('H:i') : '-';
    }

    /**
     * Get shift badge HTML
     */
    public function getShiftBadgeAttribute(): string
    {
        if ($this->isMultiShift()) {
            return '<span class="badge bg-info"><i class="mdi mdi-layers"></i> Shift ' .
                $this->shift_ke . ' - ' . $this->nama_shift . '</span>';
        }

        return '<span class="badge bg-secondary">Regular</span>';
    }

    /**
     * Get status badge HTML
     */
    public function getStatusBadgeAttribute(): string
    {
        if ($this->status === 'verified') {
            return '<span class="badge bg-success">✓ Verified</span>';
        }

        return '<span class="badge bg-danger">✗ Failed</span>';
    }

    /**
     * Get tanggal formatted
     */
    public function getTanggalFormattedAttribute(): string
    {
        return Carbon::parse($this->tanggal)->format('d/m/Y');
    }

    /**
     * Get shift info text
     */
    public function getShiftInfoAttribute(): string
    {
        if ($this->isMultiShift()) {
            return "Shift {$this->shift_ke} - {$this->nama_shift}";
        }

        return 'Regular';
    }

    // ========================================
    // SCOPES
    // ========================================

    /**
     * Scope untuk filter multi-shift
     */
    public function scopeMultiShift($query)
    {
        return $query->whereNotNull('shift_ke');
    }

    /**
     * Scope untuk filter regular shift
     */
    public function scopeRegularShift($query)
    {
        return $query->whereNull('shift_ke');
    }

    /**
     * Scope untuk filter by shift
     */
    public function scopeByShift($query, $shiftKe)
    {
        return $query->where('shift_ke', $shiftKe);
    }

    /**
     * Scope untuk filter verified
     */
    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    /**
     * Scope untuk filter failed
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope untuk filter by date range
     */
    public function scopeDateRange($query, $start, $end)
    {
        return $query->whereBetween('tanggal', [$start, $end]);
    }

    /**
     * Scope untuk filter today
     */
    public function scopeToday($query)
    {
        return $query->whereDate('tanggal', Carbon::today());
    }

    /**
     * Scope untuk filter by NIK
     */
    public function scopeByNik($query, $nik)
    {
        return $query->where('nik', $nik);
    }

    /**
     * Scope untuk with full relations
     */
    public function scopeWithFull($query)
    {
        return $query->with([
            'karyawan' => function ($q) {
                $q->with(['organ.unit.branch']);
            }
        ]);
    }
}
