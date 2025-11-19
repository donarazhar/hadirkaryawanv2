<?php

namespace App\Models;

use App\Models\PengajuanIzin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PengajuanCuti extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_cuti';
    protected $primaryKey = 'kode_cuti';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_cuti',
        'nama_cuti',
        'jml_hari',
        'status',
    ];

    protected $casts = [
        'jml_hari' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the pengajuan izin for the cuti.
     */
    public function pengajuanIzin(): HasMany
    {
        return $this->hasMany(PengajuanIzin::class, 'kode_cuti', 'kode_cuti');
    }

    /**
     * Scope untuk cuti aktif.
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }
}
