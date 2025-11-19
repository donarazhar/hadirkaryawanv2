<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonfigurasiJkDeptDetail extends Model
{
    use HasFactory;

    protected $table = 'konfigurasi_jk_dept_detail';
    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = null;

    protected $fillable = [
        'kode_jk_dept',
        'kode_jam_kerja',
        'hari'
    ];

    // Relationships
    public function konfigurasiJkDept()
    {
        return $this->belongsTo(KonfigurasiJkDept::class, 'kode_jk_dept', 'kode_jk_dept');
    }

    public function jamKerja()
    {
        return $this->belongsTo(JamKerja::class, 'kode_jam_kerja', 'kode_jam_kerja');
    }
}
