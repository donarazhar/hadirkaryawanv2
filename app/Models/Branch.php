<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = ['name', 'lokasi_cabang', 'radius_cabang', 'qr_token'];

    public function units()
    {
        return $this->hasMany(Unit::class);
    }
}
