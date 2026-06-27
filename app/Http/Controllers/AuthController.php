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
            'nik.required' => 'NIK atau Email harus diisi',
            'password.required' => 'Password harus diisi'
        ]);

        $loginField = filter_var($request->nik, FILTER_VALIDATE_EMAIL) ? 'email' : 'nik';

        $credentials = [
            $loginField => $request->nik,
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

            // Catat Log Aktivitas
            $ip = $request->ip();
            $location = 'Unknown';
            if ($ip !== '127.0.0.1' && $ip !== '::1') {
                try {
                    $ctx = stream_context_create(['http' => ['timeout' => 2]]);
                    $response = @file_get_contents("http://ip-api.com/json/{$ip}", false, $ctx);
                    if ($response) {
                        $data = json_decode($response);
                        if (isset($data->status) && $data->status === 'success') {
                            $location = $data->city . ', ' . $data->regionName . ', ' . $data->country;
                        }
                    }
                } catch (\Exception $e) {}
            } else {
                $location = 'Localhost';
            }

            \App\Models\ActivityLog::create([
                'user_name' => $user->nama_lengkap,
                'role' => 'karyawan',
                'action' => 'Login',
                'description' => 'Karyawan berhasil login ke aplikasi.',
                'ip_address' => $ip,
                'location' => $location,
                'user_agent' => substr($request->userAgent(), 0, 255),
                'branch_id' => $user->organ->unit->branch_id ?? null
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
