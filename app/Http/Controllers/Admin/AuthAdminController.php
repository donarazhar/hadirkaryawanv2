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
        // Jika sudah login, redirect ke dashboard admin
        if (Auth::guard('user')->check()) {
            return redirect('/panel/dashboardadmin');
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
