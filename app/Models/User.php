<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'kode_cabang',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationship
    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'kode_cabang', 'kode_cabang');
    }

    // Check if user is superadmin
    public function isSuperAdmin()
    {
        return $this->role === 'superadmin';
    }

    // Check if user is admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // Check if user is pimpinan
    public function isPimpinan()
    {
        return $this->role === 'pimpinan';
    }
}
