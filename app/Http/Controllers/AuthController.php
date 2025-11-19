<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    // ========== KARYAWAN LOGIN ==========
    public function login()
    {
        // If already logged in, redirect to dashboard
        if (Auth::guard('karyawan')->check()) {
            return redirect()->route('dashboard');
        }

        return view('karyawan.auth.login');
    }

    public function proseslogin(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'password' => 'required'
        ], [
            'nik.required' => 'NIK harus diisi',
            'password.required' => 'Password harus diisi'
        ]);

        $credentials = [
            'nik' => $request->nik,
            'password' => $request->password
        ];

        // Attempt login
        if (Auth::guard('karyawan')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::guard('karyawan')->user();

            Log::info('User logged in successfully', [
                'nik' => $user->nik,
                'nama' => $user->nama_lengkap
            ]);

            // Redirect to intended URL or dashboard
            return redirect()->intended(route('dashboard'))
                ->with('success', 'Selamat datang, ' . $user->nama_lengkap);
        }

        Log::warning('Failed login attempt', ['nik' => $request->nik]);

        return back()
            ->withInput($request->only('nik'))
            ->with('error', 'NIK atau password salah');
    }

    public function proseslogout(Request $request)
    {
        $user = Auth::guard('karyawan')->user();

        if ($user) {
            Log::info('User logged out', [
                'nik' => $user->nik,
                'nama' => $user->nama_lengkap
            ]);
        }

        Auth::guard('karyawan')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah logout');
    }
}
