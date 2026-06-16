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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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
        'email',
        'jabatan',
        'no_hp',
        'password',
        'foto',
        'kode_dept',
        'kode_cabang',
        'google_id'
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
    // ✅ ROLE CHECKS (NEW)
    // ========================================
    
    /**
     * Cek apakah karyawan adalah pimpinan di tabel users
     */
    public function isPimpinan()
    {
        return \App\Models\User::where(function($q) {
            $q->where('nik_karyawan', $this->nik)
              ->orWhere('email', $this->email);
        })->where('role', 'pimpinan')->exists();
    }

    /**
     * Mendapatkan detail pimpinan dari tabel users
     */
    public function getPimpinanDetail()
    {
        return \App\Models\User::where(function($q) {
            $q->where('nik_karyawan', $this->nik)
              ->orWhere('email', $this->email);
        })->where('role', 'pimpinan')->first();
    }

    // ========================================
    // ✅ JAM KERJA RELATIONSHIPS (IMPROVED)
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
     * IMPROVED: Menggunakan accessor pattern
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
            ->join('konfigurasi_jk_dept', function ($join) {
                $join->on('konfigurasi_jk_dept_detail.kode_jk_dept', '=', 'konfigurasi_jk_dept.kode_jk_dept')
                    ->where('konfigurasi_jk_dept.kode_cabang', '=', $this->kode_cabang)
                    ->where('konfigurasi_jk_dept.kode_dept', '=', $this->kode_dept);
            })
            ->where('konfigurasi_jk_dept_detail.hari', $namaHari);
    }

    // ========================================
    // ✅ JAM KERJA HELPER METHODS (NEW & IMPROVED)
    // ========================================

    /**
     * ✅ Get jam kerja hari ini (RECOMMENDED METHOD)
     * Metode yang lebih reliable menggunakan raw query
     */
    public function getJamKerjaHariIni()
    {
        $hari = $this->getNamaHariIni();

        Log::info('Getting Jam Kerja', [
            'nik' => $this->nik,
            'kode_dept' => $this->kode_dept,
            'kode_cabang' => $this->kode_cabang,
            'hari' => $hari
        ]);

        // Query kompleks untuk mendapatkan jam kerja
        $jam_kerja = DB::table('konfigurasi_jk_dept as kjd')
            ->join('konfigurasi_jk_dept_detail as kjdd', 'kjd.kode_jk_dept', '=', 'kjdd.kode_jk_dept')
            ->join('jam_kerja as jk', 'kjdd.kode_jam_kerja', '=', 'jk.kode_jam_kerja')
            ->where('kjd.kode_dept', $this->kode_dept)
            ->where('kjd.kode_cabang', $this->kode_cabang)
            ->where('kjdd.hari', $hari)
            ->select('jk.*')
            ->first();

        if ($jam_kerja) {
            Log::info('Jam Kerja Found', [
                'kode_jam_kerja' => $jam_kerja->kode_jam_kerja,
                'nama_jam_kerja' => $jam_kerja->nama_jam_kerja,
                'tipe_jam_kerja' => $jam_kerja->tipe_jam_kerja,
                'total_shift' => $jam_kerja->total_shift
            ]);
        } else {
            Log::warning('Jam Kerja Not Found', [
                'nik' => $this->nik,
                'kode_dept' => $this->kode_dept,
                'kode_cabang' => $this->kode_cabang,
                'hari' => $hari
            ]);
        }

        return $jam_kerja;
    }

    /**
     * ✅ Get jam kerja by specific hari
     */
    public function getJamKerjaByHari($hari)
    {
        $jam_kerja = DB::table('konfigurasi_jk_dept as kjd')
            ->join('konfigurasi_jk_dept_detail as kjdd', 'kjd.kode_jk_dept', '=', 'kjdd.kode_jk_dept')
            ->join('jam_kerja as jk', 'kjdd.kode_jam_kerja', '=', 'jk.kode_jam_kerja')
            ->where('kjd.kode_dept', $this->kode_dept)
            ->where('kjd.kode_cabang', $this->kode_cabang)
            ->where('kjdd.hari', $hari)
            ->select('jk.*')
            ->first();

        return $jam_kerja;
    }

    /**
     * ✅ Get current jam kerja (alias for getJamKerjaHariIni)
     */
    public function getCurrentJamKerja()
    {
        return $this->getJamKerjaHariIni();
    }

    /**
     * ✅ Get ALL shifts untuk hari ini (if multi-shift)
     * Returns Collection of shifts or empty collection
     */
    public function getShiftsHariIni()
    {
        $jam_kerja = $this->getJamKerjaHariIni();

        if (!$jam_kerja) {
            Log::warning('No Jam Kerja for Shifts', ['nik' => $this->nik]);
            return collect();
        }

        // Cek apakah multi-shift
        if ($jam_kerja->tipe_jam_kerja !== 'multi_shift') {
            Log::info('Not Multi-Shift', [
                'nik' => $this->nik,
                'tipe_jam_kerja' => $jam_kerja->tipe_jam_kerja
            ]);
            return collect();
        }

        // Get shifts
        $shifts = DB::table('jam_kerja_shifts')
            ->where('kode_jam_kerja', $jam_kerja->kode_jam_kerja)
            ->where('is_active', true)
            ->orderBy('shift_ke')
            ->get();

        Log::info('Shifts Retrieved', [
            'nik' => $this->nik,
            'kode_jam_kerja' => $jam_kerja->kode_jam_kerja,
            'total_shifts' => $shifts->count(),
            'shifts' => $shifts->pluck('nama_shift')->toArray()
        ]);

        return $shifts;
    }

    /**
     * ✅ Alias for getShiftsHariIni (backward compatibility)
     */
    public function getShifts()
    {
        return $this->getShiftsHariIni();
    }

    /**
     * ✅ Check if karyawan uses multi-shift (RECOMMENDED)
     * Returns boolean
     */
    public function isMultiShift()
    {
        $jam_kerja = $this->getJamKerjaHariIni();

        if (!$jam_kerja) {
            return false;
        }

        $is_multi = $jam_kerja->tipe_jam_kerja === 'multi_shift' && $jam_kerja->total_shift >= 2;

        Log::info('Multi-Shift Check', [
            'nik' => $this->nik,
            'is_multi_shift' => $is_multi,
            'tipe_jam_kerja' => $jam_kerja->tipe_jam_kerja,
            'total_shift' => $jam_kerja->total_shift
        ]);

        return $is_multi;
    }

    /**
     * ✅ Get total shifts count for today
     */
    public function getTotalShiftsHariIni()
    {
        $shifts = $this->getShiftsHariIni();
        return $shifts->count();
    }

    /**
     * ✅ Check if specific shift exists for this karyawan
     */
    public function hasShift($shift_ke)
    {
        $shifts = $this->getShiftsHariIni();
        return $shifts->where('shift_ke', $shift_ke)->count() > 0;
    }

    /**
     * ✅ Get specific shift detail
     */
    public function getShiftDetail($shift_ke)
    {
        $shifts = $this->getShiftsHariIni();
        return $shifts->where('shift_ke', $shift_ke)->first();
    }

    // ========================================
    // HELPER METHODS
    // ========================================

    /**
     * ✅ Get nama hari dalam bahasa Indonesia (IMPROVED)
     * Support multiple formats and timezone
     */
    private function getNamaHariIni()
    {
        // Gunakan Carbon untuk lebih reliable
        $hari = Carbon::now('Asia/Jakarta')->locale('id')->isoFormat('dddd');

        // Mapping to database format (lowercase)
        $namaHari = [
            'Senin' => 'senin',
            'Selasa' => 'selasa',
            'Rabu' => 'rabu',
            'Kamis' => 'kamis',
            'Jumat' => 'jumat',
            'Sabtu' => 'sabtu',
            'Minggu' => 'minggu',
            'Ahad' => 'minggu' // Alternative for Sunday
        ];

        $hari_db = $namaHari[$hari] ?? strtolower($hari);

        Log::debug('Hari Conversion', [
            'hari_indonesia' => $hari,
            'hari_database' => $hari_db
        ]);

        return $hari_db;
    }

    /**
     * ✅ Get nama hari by specific date
     */
    public function getNamaHariByDate($date)
    {
        $hari = Carbon::parse($date)->locale('id')->isoFormat('dddd');

        $namaHari = [
            'Senin' => 'senin',
            'Selasa' => 'selasa',
            'Rabu' => 'rabu',
            'Kamis' => 'kamis',
            'Jumat' => 'jumat',
            'Sabtu' => 'sabtu',
            'Minggu' => 'minggu',
            'Ahad' => 'minggu'
        ];

        return $namaHari[$hari] ?? strtolower($hari);
    }

    // ========================================
    // ✅ DEBUGGING METHODS (for development)
    // ========================================

    /**
     * Get comprehensive info about karyawan's shift configuration
     */
    public function getShiftInfo()
    {
        $jam_kerja = $this->getJamKerjaHariIni();
        $shifts = $this->getShiftsHariIni();

        return [
            'nik' => $this->nik,
            'nama_lengkap' => $this->nama_lengkap,
            'kode_dept' => $this->kode_dept,
            'nama_dept' => $this->departemen->nama_dept ?? 'N/A',
            'kode_cabang' => $this->kode_cabang,
            'nama_cabang' => $this->cabang->nama_cabang ?? 'N/A',
            'hari' => $this->getNamaHariIni(),
            'jam_kerja' => $jam_kerja ? [
                'kode_jam_kerja' => $jam_kerja->kode_jam_kerja,
                'nama_jam_kerja' => $jam_kerja->nama_jam_kerja,
                'tipe_jam_kerja' => $jam_kerja->tipe_jam_kerja,
                'total_shift' => $jam_kerja->total_shift,
                'jam_masuk' => $jam_kerja->jam_masuk,
                'jam_pulang' => $jam_kerja->jam_pulang,
            ] : null,
            'is_multi_shift' => $this->isMultiShift(),
            'total_shifts' => $shifts->count(),
            'shifts' => $shifts->map(function ($shift) {
                return [
                    'shift_ke' => $shift->shift_ke,
                    'nama_shift' => $shift->nama_shift,
                    'jam_masuk' => $shift->jam_masuk,
                    'jam_pulang' => $shift->jam_pulang,
                ];
            })->toArray()
        ];
    }
}
