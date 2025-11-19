<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departemen extends Model
{
    use HasFactory;

    protected $table = 'departemen';
    protected $primaryKey = 'kode_dept';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'kode_dept',
        'nama_dept'
    ];

    // Relationships
    public function karyawan()
    {
        return $this->hasMany(Karyawan::class, 'kode_dept', 'kode_dept');
    }

    public function konfigurasiJkDept()
    {
        return $this->hasMany(KonfigurasiJkDept::class, 'kode_dept', 'kode_dept');
    }
}
