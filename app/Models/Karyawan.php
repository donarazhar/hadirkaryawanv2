<?php

namespace App\Models;

use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\PengajuanIzin;
use App\Models\Presensi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    // Relationships
    public function departemen()
    {
        return $this->belongsTo(Departemen::class, 'kode_dept', 'kode_dept');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'kode_cabang', 'kode_cabang');
    }

    public function presensi()
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
    public function pengajuanIzinPending()
    {
        return $this->hasMany(PengajuanIzin::class, 'nik', 'nik')
            ->where('status_approved', '0');
    }

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
     * Get jam kerja berdasarkan hari untuk karyawan ini
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
            ->first();
    }
}
