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
                'ip_address' => Request::ip()
            ]);
        }
    }
}
