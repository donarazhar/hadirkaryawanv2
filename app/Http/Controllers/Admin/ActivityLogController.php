<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index()
    {
        $user = auth('user')->user();
        
        $query = \App\Models\ActivityLog::orderBy('created_at', 'desc');

        if ($user && $user->role === 'admin') {
            $query->where('kode_cabang', $user->kode_cabang);
        }

        $logs = $query->paginate(20);
        return view('admin.activity_logs.index', compact('logs'));
    }
}
