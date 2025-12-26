<?php

namespace App\Models;

use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\PengajuanIzin;
use App\Models\Presensi;
use App\Models\JamKerja;
use App\Models\KonfigurasiJkDept;
use App\Models\KonfigurasiJkDeptDetail;
use App\Models\FaceData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Karyawan extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'karyawan';
    protected $primaryKey = 'nik';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'nik',
        'nama_lengkap',
        'jabatan',
        'no_hp',
        'password',
        'foto',
        'kode_dept',
        'kode_cabang'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    // ========================================
    // RELATIONSHIPS
    // ========================================

    /**
     * Get departemen
     */
    public function departemen(): BelongsTo
    {
        return $this->belongsTo(Departemen::class, 'kode_dept', 'kode_dept');
    }

    /**
     * Get cabang
     */
    public function cabang(): BelongsTo
    {
        return $this->belongsTo(Cabang::class, 'kode_cabang', 'kode_cabang');
    }

    /**
     * Get all presensi
     */
    public function presensi(): HasMany
    {
        return $this->hasMany(Presensi::class, 'nik', 'nik');
    }

    /**
     * Get the pengajuan izin for the karyawan.
     */
    public function pengajuanIzin(): HasMany
    {
        return $this->hasMany(PengajuanIzin::class, 'nik', 'nik');
    }

    /**
     * Get pending pengajuan izin.
     */
    public function pengajuanIzinPending(): HasMany
    {
        return $this->hasMany(PengajuanIzin::class, 'nik', 'nik')
            ->where('status_approved', '0');
    }

    /**
     * Get face data
     */
    public function faceData(): HasOne
    {
        return $this->hasOne(FaceData::class, 'nik', 'nik');
    }

    // ========================================
    // ✅ JAM KERJA RELATIONSHIPS (ADDED)
    // ========================================

    /**
     * Get konfigurasi jam kerja departemen untuk karyawan ini
     */
    public function konfigurasiJkDept()
    {
        return $this->hasOneThrough(
            KonfigurasiJkDept::class,
            Departemen::class,
            'kode_dept', // Foreign key di departemen
            'kode_dept', // Foreign key di konfigurasi_jk_dept
            'kode_dept', // Local key di karyawan
            'kode_dept'  // Local key di departemen
        )->where('konfigurasi_jk_dept.kode_cabang', $this->kode_cabang);
    }

    /**
     * ✅ Get jam kerja untuk karyawan ini (MAIN RELATIONSHIP)
     * This is the missing relationship that caused the error!
     */
    public function jamKerja()
    {
        // Get hari saat ini
        $namaHari = $this->getNamaHariIni();

        return $this->hasOneThrough(
            JamKerja::class,                    // Target model
            KonfigurasiJkDeptDetail::class,     // Intermediate model
            'kode_jk_dept',                     // Foreign key on intermediate (konfigurasi_jk_dept_detail)
            'kode_jam_kerja',                   // Foreign key on target (jam_kerja)
            'kode_dept',                        // Local key on this model (karyawan)
            'kode_jam_kerja'                    // Local key on intermediate
        )
        ->join('konfigurasi_jk_dept', function($join) {
            $join->on('konfigurasi_jk_dept_detail.kode_jk_dept', '=', 'konfigurasi_jk_dept.kode_jk_dept')
                 ->where('konfigurasi_jk_dept.kode_cabang', '=', $this->kode_cabang)
                 ->where('konfigurasi_jk_dept.kode_dept', '=', $this->kode_dept);
        })
        ->where('konfigurasi_jk_dept_detail.hari', $namaHari)
        ->with('shifts'); // Eager load shifts untuk multi-shift
    }

    /**
     * ✅ Alternative: Get jam kerja by specific hari
     */
    public function getJamKerjaByHari($hari)
    {
        return KonfigurasiJkDeptDetail::query()
            ->select('jam_kerja.*')
            ->join('konfigurasi_jk_dept', 'konfigurasi_jk_dept_detail.kode_jk_dept', '=', 'konfigurasi_jk_dept.kode_jk_dept')
            ->join('jam_kerja', 'konfigurasi_jk_dept_detail.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->where('konfigurasi_jk_dept.kode_cabang', $this->kode_cabang)
            ->where('konfigurasi_jk_dept.kode_dept', $this->kode_dept)
            ->where('konfigurasi_jk_dept_detail.hari', $hari)
            ->with('shifts') // Include shifts
            ->first();
    }

    /**
     * ✅ Get current jam kerja (today)
     * Simpler method for current day
     */
    public function getCurrentJamKerja()
    {
        $namaHari = $this->getNamaHariIni();
        return $this->getJamKerjaByHari($namaHari);
    }

    // ========================================
    // HELPER METHODS
    // ========================================

    /**
     * Get nama hari dalam bahasa Indonesia
     */
    private function getNamaHariIni()
    {
        $hariInggris = date('l');
        $namaHari = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Ahad'
        ];

        return $namaHari[$hariInggris] ?? 'Senin';
    }

    /**
     * ✅ Check if karyawan uses multi-shift
     */
    public function isMultiShift()
    {
        $jamKerja = $this->getCurrentJamKerja();
        return $jamKerja && $jamKerja->tipe_jam_kerja === 'multi_shift';
    }

    /**
     * ✅ Get all shifts for this karyawan (if multi-shift)
     */
    public function getShifts()
    {
        $jamKerja = $this->getCurrentJamKerja();
        
        if ($jamKerja && $jamKerja->tipe_jam_kerja === 'multi_shift') {
            return $jamKerja->shifts;
        }

        return collect();
    }
}