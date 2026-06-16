<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    // 1. Tampilkan form untuk minta link reset
    public function showLinkRequestForm()
    {
        return view('karyawan.auth.passwords.email');
    }

    // 2. Kirim link reset via email
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $karyawan = DB::table('karyawan')->where('email', $request->email)->first();

        if (!$karyawan) {
            return back()->withErrors(['email' => 'Email tidak ditemukan dalam sistem kami.']);
        }

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now('Asia/Jakarta')
            ]
        );

        $action_link = route('password.reset', ['token' => $token, 'email' => $request->email]);
        $body = "Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.\n\nKlik link di bawah ini untuk mereset password Anda:\n" . $action_link . "\n\nJika Anda tidak meminta reset password, abaikan email ini.";

        // Mengirim email sederhana (menggunakan raw)
        Mail::raw($body, function ($message) use ($request) {
            $message->to($request->email)
                    ->subject('Reset Password Notification');
        });

        return back()->with('success', 'Kami telah mengirimkan link reset password ke email Anda!');
    }

    // 3. Tampilkan form reset password (dari link email)
    public function showResetForm(Request $request, $token = null)
    {
        return view('karyawan.auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    // 4. Proses update password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
            'token' => 'required'
        ]);

        $check_token = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$check_token) {
            return back()->withErrors(['email' => 'Token tidak valid.']);
        }

        DB::table('karyawan')->where('email', $request->email)->update([
            'password' => Hash::make($request->password)
        ]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Password Anda berhasil direset! Silakan login dengan password baru.');
    }
}
