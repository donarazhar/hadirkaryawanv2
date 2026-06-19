<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class LogHelper
{
    /**
     * Catat log aktivitas admin
     *
     * @param string $action
     * @param string $description
     * @return void
     */
    public static function record($action, $description)
    {
        if (Auth::guard('user')->check()) {
            $user = Auth::guard('user')->user();
            ActivityLog::create([
                'user_name' => $user->name,
                'role' => $user->role,
                'action' => $action,
                'description' => $description,
                'ip_address' => Request::ip(),
                'location' => 'Unknown', // Could implement location lookup here if desired, or leave null/Unknown
                'user_agent' => substr(Request::userAgent(), 0, 255),
                'kode_cabang' => $user->kode_cabang ?? null
            ]);
        }
    }
}
