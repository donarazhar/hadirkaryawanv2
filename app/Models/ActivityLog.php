<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_name',
        'role',
        'action',
        'description',
        'ip_address',
        'location',
        'user_agent',
        'branch_id'
    ];
}
