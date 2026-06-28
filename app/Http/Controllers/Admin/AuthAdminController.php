<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthAdminController extends Controller
{
    /**
     * Show admin login form
     */
    public function login()
    {
        // Jika sudah login sebagai admin, redirect ke dashboard admin
        if (Auth::guard('user')->check()) {
            return redirect('/panel/dashboard');
        }

        // AUTO-LOGIN: Jika sudah login sebagai karyawan (via Google SSO)
        // Cek apakah karyawan ini juga memiliki akses admin (terdaftar di tabel users)
        if (Auth::guard('karyawan')->check()) {
            $karyawan = Auth::guard('karyawan')->user();
            $adminUser = \App\Models\User::where('email', $karyawan->email)->first();
            
            if ($adminUser) {
                // Login otomatis sebagai admin
                Auth::guard('user')->login($adminUser);
                return redirect('/panel/dashboard');
            }
        }

        return view('admin.auth.login');
    }

    /**
     * Process admin login
     */
    public function proseslogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password harus diisi'
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::guard('user')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::guard('user')->user();

            Log::info('Admin logged in', [
                'email' => $user->email,
                'name' => $user->name
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
                'user_name' => $user->name,
                'role' => $user->role,
                'action' => 'Login',
                'description' => ucfirst($user->role) . ' berhasil login ke panel.',
                'ip_address' => $ip,
                'location' => $location,
                'user_agent' => substr($request->userAgent(), 0, 255),
                'branch_id' => $user->branch_id ?? null
            ]);

            return redirect()->intended(route('panel.dashboard'))
                ->with('success', 'Selamat datang, ' . $user->name);
        }

        Log::warning('Failed admin login attempt', ['email' => $request->email]);

        return back()
            ->withInput($request->only('email'))
            ->with('error', 'Email atau password salah');
    }

    /**
     * Logout admin
     */
    public function logout(Request $request)
    {
        $user = Auth::guard('user')->user();

        if ($user) {
            Log::info('Admin logged out', [
                'email' => $user->email,
                'name' => $user->name
            ]);
        }

        Auth::guard('user')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('panel.login')
            ->with('success', 'Anda telah logout');
    }
}
