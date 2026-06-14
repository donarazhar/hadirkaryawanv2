<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect to Google
     */
    public function redirect(Request $request)
    {
        // Menyimpan jenis login yang diinginkan (admin / karyawan) ke session
        $type = $request->query('type', 'karyawan');
        $request->session()->put('google_login_type', $type);

        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Callback from Google
     */
    public function callback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $type = $request->session()->pull('google_login_type', 'karyawan');

            if ($type === 'admin') {
                return $this->handleAdminLogin($googleUser);
            } else {
                return $this->handleKaryawanLogin($googleUser);
            }
        } catch (\Exception $e) {
            Log::error('Google Socialite Error: ' . $e->getMessage());
            
            // Redirect back to login with error
            $redirectRoute = session('google_login_type') === 'admin' ? 'panel.login' : 'login';
            return redirect()->route($redirectRoute)->with('error', 'Gagal login melalui Google.');
        }
    }

    private function handleAdminLogin($googleUser)
    {
        // Cari user admin berdasarkan google_id ATAU email
        $user = User::where('google_id', $googleUser->id)
                    ->orWhere('email', $googleUser->email)
                    ->first();

        if ($user) {
            // Update google_id jika belum ada tapi email cocok
            if (!$user->google_id) {
                $user->update(['google_id' => $googleUser->id]);
            }

            Auth::guard('user')->login($user);
            
            Log::info('Admin logged in via Google', ['email' => $user->email]);
            return redirect()->route('panel.dashboard')->with('success', 'Berhasil login sebagai ' . $user->name);
        }

        // Jika tidak ditemukan, admin tidak boleh daftar sembarangan via google
        return redirect()->route('panel.login')->with('error', 'Akun admin tidak ditemukan untuk email ini.');
    }

    private function handleKaryawanLogin($googleUser)
    {
        // Cari karyawan berdasarkan google_id ATAU email
        $karyawan = Karyawan::where('google_id', $googleUser->id)
                            ->orWhere('email', $googleUser->email)
                            ->first();

        if ($karyawan) {
            // Update google_id jika belum ada
            if (!$karyawan->google_id) {
                $karyawan->update(['google_id' => $googleUser->id]);
            }

            Auth::guard('karyawan')->login($karyawan);
            
            Log::info('Karyawan logged in via Google', ['nik' => $karyawan->nik]);
            return redirect()->route('dashboard')->with('success', 'Berhasil login sebagai ' . $karyawan->nama_lengkap);
        }

        // Jika karyawan tidak ada di sistem
        return redirect()->route('login')->with('error', 'Akun karyawan tidak ditemukan. Silakan hubungi HRD.');
    }
}
