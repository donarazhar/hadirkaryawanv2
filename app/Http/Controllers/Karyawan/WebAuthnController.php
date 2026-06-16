<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebAuthnController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:karyawan');
    }

    public function enrollment()
    {
        $karyawan = Auth::guard('karyawan')->user();
        return view('karyawan.biometric.enrollment', compact('karyawan'));
    }

    public function store(Request $request)
    {
        try {
            $nik = Auth::guard('karyawan')->user()->nik;
            $rawId = $request->input('rawId');

            if (empty($rawId)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Credential ID tidak ditemukan.'
                ], 400);
            }

            DB::table('karyawan')
                ->where('nik', $nik)
                ->update(['webauthn_id' => $rawId]);

            Log::info('WebAuthn registered', ['nik' => $nik]);

            return response()->json([
                'status' => 'success',
                'message' => 'Sidik jari / Biometrik berhasil didaftarkan.'
            ]);
        } catch (\Exception $e) {
            Log::error('WebAuthn Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan kredensial.'
            ], 500);
        }
    }

    public function delete()
    {
        try {
            $nik = Auth::guard('karyawan')->user()->nik;

            DB::table('karyawan')
                ->where('nik', $nik)
                ->update(['webauthn_id' => null]);

            return redirect()->back()->with('success', 'Data Sidik Jari / Biometrik berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem.');
        }
    }
}
