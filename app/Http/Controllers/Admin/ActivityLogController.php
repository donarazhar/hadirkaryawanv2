<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index()
    {
        $logs = \App\Models\ActivityLog::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.activity_logs.index', compact('logs'));
    }
}
