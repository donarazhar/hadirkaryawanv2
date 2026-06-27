<?php

namespace App\Models;

use App\Models\Branch;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonfigurasiJkUnit extends Model
{
    use HasFactory;

    protected $table = 'konfigurasi_jk_unit';
    protected $primaryKey = 'kode_jk_unit';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'kode_jk_unit',
        'branch_id',
        'unit_id'
    ];

    // Relationships
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function details()
    {
        return $this->hasMany(KonfigurasiJkUnitDetail::class, 'kode_jk_unit', 'kode_jk_unit');
    }
}
