<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonfigurasiJkDept extends Model
{
    use HasFactory;

    protected $table = 'konfigurasi_jk_dept';
    protected $primaryKey = 'kode_jk_dept';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'kode_jk_dept',
        'kode_cabang',
        'kode_dept'
    ];

    // Relationships
    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'kode_cabang', 'kode_cabang');
    }

    public function departemen()
    {
        return $this->belongsTo(Departemen::class, 'kode_dept', 'kode_dept');
    }

    public function details()
    {
        return $this->hasMany(KonfigurasiJkDeptDetail::class, 'kode_jk_dept', 'kode_jk_dept');
    }
}
