<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;

class ProfileKaryawanController extends Controller
{
    /**
     * Constructor - Middleware auth karyawan
     */
    public function __construct()
    {
        $this->middleware('auth:karyawan');
    }

    public function edit()
    {
        try {
            $nik = Auth::guard('karyawan')->user()->nik;

            // Get data karyawan lengkap dengan relasi
            $karyawan = DB::table('karyawan')
                ->select('karyawan.*', 'departemen.nama_dept', 'cabang.nama_cabang')
                ->leftJoin('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
                ->leftJoin('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang')
                ->where('karyawan.nik', $nik)
                ->first();

            if (!$karyawan) {
                return redirect('/dashboard')->with('error', 'Data karyawan tidak ditemukan');
            }

            return view('karyawan.profile.edit', compact('karyawan')); // UPDATED PATH
        } catch (Exception $e) {
            Log::error('ProfileKaryawan@edit Error: ' . $e->getMessage());
            return redirect('/dashboard')->with('error', 'Terjadi kesalahan saat memuat halaman profile');
        }
    }

    /**
     * Update profile karyawan
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        try {
            $nik = Auth::guard('karyawan')->user()->nik;

            // Validasi input
            $request->validate([
                'nama_lengkap' => 'required|string|max:255',
                'no_hp' => 'required|string|max:20',
                'foto' => 'nullable|image|mimes:png,jpg,jpeg|max:2048', // max 2MB
                'password' => 'nullable|min:6|confirmed'
            ], [
                'nama_lengkap.required' => 'Nama lengkap harus diisi',
                'no_hp.required' => 'Nomor HP harus diisi',
                'foto.image' => 'File harus berupa gambar',
                'foto.mimes' => 'Format foto harus PNG, JPG, atau JPEG',
                'foto.max' => 'Ukuran foto maksimal 2MB',
                'password.min' => 'Password minimal 6 karakter',
                'password.confirmed' => 'Konfirmasi password tidak cocok'
            ]);

            // Get data karyawan saat ini
            $karyawan = DB::table('karyawan')->where('nik', $nik)->first();

            if (!$karyawan) {
                return redirect('/dashboard')->with('error', 'Data karyawan tidak ditemukan');
            }

            DB::beginTransaction();

            // Handle foto
            $foto = $karyawan->foto;
            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada dan bukan foto default
                if (!empty($foto) && $foto != 'default.png') {
                    Storage::delete('public/uploads/karyawan/' . $foto);
                }

                // Upload foto baru
                $file = $request->file('foto');
                $foto = $nik . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/uploads/karyawan', $foto);

                Log::info('Foto profile berhasil diupload', [
                    'nik' => $nik,
                    'filename' => $foto
                ]);
            }

            // Handle password
            $password = $karyawan->password;
            if ($request->filled('password')) {
                $password = Hash::make($request->password);

                Log::info('Password berhasil diubah', ['nik' => $nik]);
            }

            // Update data
            $data = [
                'nama_lengkap' => $request->nama_lengkap,
                'no_hp' => $request->no_hp,
                'password' => $password,
                'foto' => $foto,
                'updated_at' => now()
            ];

            $update = DB::table('karyawan')
                ->where('nik', $nik)
                ->update($data);

            DB::commit();

            if ($update) {
                Log::info('Profile berhasil diupdate', ['nik' => $nik]);
                return redirect()->back()->with('success', 'Profil berhasil diperbarui');
            } else {
                return redirect()->back()->with('info', 'Tidak ada perubahan data');
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Data tidak valid. Periksa kembali form Anda.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('ProfileKaryawan@update Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Gagal memperbarui profil. Silakan coba lagi.');
        }
    }

    /**
     * Hapus foto profile (set ke default)
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteFoto()
    {
        try {
            $nik = Auth::guard('karyawan')->user()->nik;
            $karyawan = DB::table('karyawan')->where('nik', $nik)->first();

            if (!$karyawan) {
                return redirect()->back()->with('error', 'Data tidak ditemukan');
            }

            // Hapus foto jika bukan default
            if (!empty($karyawan->foto) && $karyawan->foto != 'default.png') {
                Storage::delete('public/uploads/karyawan/' . $karyawan->foto);
            }

            // Set ke null atau default
            DB::table('karyawan')
                ->where('nik', $nik)
                ->update([
                    'foto' => null,
                    'updated_at' => now()
                ]);

            Log::info('Foto profile berhasil dihapus', ['nik' => $nik]);

            return redirect()->back()->with('success', 'Foto profil berhasil dihapus');
        } catch (Exception $e) {
            Log::error('ProfileKaryawan@deleteFoto Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus foto');
        }
    }

    /**
     * Get data profile untuk API
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProfile()
    {
        try {
            $nik = Auth::guard('karyawan')->user()->nik;

            $karyawan = DB::table('karyawan')
                ->select(
                    'karyawan.nik',
                    'karyawan.nama_lengkap',
                    'karyawan.jabatan',
                    'karyawan.no_hp',
                    'karyawan.foto',
                    'departemen.nama_dept',
                    'cabang.nama_cabang',
                    'cabang.lokasi_cabang'
                )
                ->leftJoin('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
                ->leftJoin('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang')
                ->where('karyawan.nik', $nik)
                ->first();

            if (!$karyawan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            // Add foto URL
            if ($karyawan->foto) {
                $karyawan->foto_url = Storage::url('uploads/karyawan/' . $karyawan->foto);
            } else {
                $karyawan->foto_url = 'https://ui-avatars.com/api/?name=' . urlencode($karyawan->nama_lengkap) . '&background=0053C5&color=fff';
            }

            return response()->json([
                'success' => true,
                'data' => $karyawan
            ]);
        } catch (Exception $e) {
            Log::error('ProfileKaryawan@getProfile Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan'
            ], 500);
        }
    }

    /**
     * Change password only
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePassword(Request $request)
    {
        try {
            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|min:6|confirmed'
            ], [
                'current_password.required' => 'Password lama harus diisi',
                'new_password.required' => 'Password baru harus diisi',
                'new_password.min' => 'Password baru minimal 6 karakter',
                'new_password.confirmed' => 'Konfirmasi password tidak cocok'
            ]);

            $nik = Auth::guard('karyawan')->user()->nik;
            $karyawan = DB::table('karyawan')->where('nik', $nik)->first();

            // Cek password lama
            if (!Hash::check($request->current_password, $karyawan->password)) {
                return redirect()->back()->with('error', 'Password lama tidak sesuai');
            }

            // Update password
            DB::table('karyawan')
                ->where('nik', $nik)
                ->update([
                    'password' => Hash::make($request->new_password),
                    'updated_at' => now()
                ]);

            Log::info('Password berhasil diubah', ['nik' => $nik]);

            return redirect()->back()->with('success', 'Password berhasil diubah');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->with('error', 'Data tidak valid');
        } catch (Exception $e) {
            Log::error('ProfileKaryawan@changePassword Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengubah password');
        }
    }
}
