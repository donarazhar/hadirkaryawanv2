<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'nik_karyawan',
        'password',
        'role',
        'branch_id',
        'google_id',
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
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
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
