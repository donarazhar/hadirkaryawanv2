<?php

namespace App\Http\Controllers\Admin;

use App\Models\JamKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class JamKerjaController extends Controller
{
    /**
     * Display a listing of jam kerja
     */
    public function index(Request $request)
    {
        $query = JamKerja::query();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_jam_kerja', 'like', '%' . $search . '%')
                    ->orWhere('nama_jam_kerja', 'like', '%' . $search . '%');
            });
        }

        $jamkerja = $query->orderBy('kode_jam_kerja', 'ASC')->paginate(10);

        return view('admin.jamkerja.index', compact('jamkerja'));
    }

    /**
     * Show the form for creating a new jam kerja
     */
    public function create()
    {
        return view('admin.jamkerja.create');
    }

    /**
     * Store a newly created jam kerja
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_jam_kerja' => 'required|string|max:10|unique:jam_kerja,kode_jam_kerja',
            'nama_jam_kerja' => 'required|string|max:15',
            'awal_jam_masuk' => 'required|date_format:H:i',
            'jam_masuk' => 'required|date_format:H:i',
            'akhir_jam_masuk' => 'required|date_format:H:i',
            'jam_pulang' => 'required|date_format:H:i',
            'lintashari' => 'required|in:0,1'
        ], [
            'kode_jam_kerja.required' => 'Kode Jam Kerja wajib diisi',
            'kode_jam_kerja.unique' => 'Kode Jam Kerja sudah terdaftar',
            'nama_jam_kerja.required' => 'Nama Jam Kerja wajib diisi',
            'awal_jam_masuk.required' => 'Awal Jam Masuk wajib diisi',
            'jam_masuk.required' => 'Jam Masuk wajib diisi',
            'akhir_jam_masuk.required' => 'Akhir Jam Masuk wajib diisi',
            'jam_pulang.required' => 'Jam Pulang wajib diisi',
            'lintashari.required' => 'Lintas Hari wajib dipilih',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            JamKerja::create([
                'kode_jam_kerja' => $request->kode_jam_kerja,
                'nama_jam_kerja' => $request->nama_jam_kerja,
                'awal_jam_masuk' => $request->awal_jam_masuk,
                'jam_masuk' => $request->jam_masuk,
                'akhir_jam_masuk' => $request->akhir_jam_masuk,
                'jam_pulang' => $request->jam_pulang,
                'lintashari' => $request->lintashari
            ]);

            return redirect()->route('panel.jamkerja.index')
                ->with(['success' => 'Data Jam Kerja berhasil ditambahkan']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Show the form for editing jam kerja
     */
    public function edit($kode_jam_kerja)
    {
        $jamkerja = JamKerja::with('konfigurasiJkDeptDetail')
            ->findOrFail($kode_jam_kerja);

        // Hitung total penggunaan jam kerja
        $total_konfigurasi_dept = $jamkerja->konfigurasiJkDeptDetail->count();
        $total_penggunaan = $total_konfigurasi_dept;

        return view('admin.jamkerja.edit', compact('jamkerja', 'total_penggunaan', 'total_konfigurasi_dept'));
    }

    /**
     * Update the specified jam kerja
     */
    public function update(Request $request, $kode_jam_kerja)
    {
        $validator = Validator::make($request->all(), [
            'nama_jam_kerja' => 'required|string|max:15',
            'awal_jam_masuk' => 'required|date_format:H:i',
            'jam_masuk' => 'required|date_format:H:i',
            'akhir_jam_masuk' => 'required|date_format:H:i',
            'jam_pulang' => 'required|date_format:H:i',
            'lintashari' => 'required|in:0,1'
        ], [
            'nama_jam_kerja.required' => 'Nama Jam Kerja wajib diisi',
            'awal_jam_masuk.required' => 'Awal Jam Masuk wajib diisi',
            'jam_masuk.required' => 'Jam Masuk wajib diisi',
            'akhir_jam_masuk.required' => 'Akhir Jam Masuk wajib diisi',
            'jam_pulang.required' => 'Jam Pulang wajib diisi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $jamkerja = JamKerja::findOrFail($kode_jam_kerja);

            $jamkerja->update([
                'nama_jam_kerja' => $request->nama_jam_kerja,
                'awal_jam_masuk' => $request->awal_jam_masuk,
                'jam_masuk' => $request->jam_masuk,
                'akhir_jam_masuk' => $request->akhir_jam_masuk,
                'jam_pulang' => $request->jam_pulang,
                'lintashari' => $request->lintashari
            ]);

            return redirect()->route('panel.jamkerja.index')
                ->with(['success' => 'Data Jam Kerja berhasil diupdate']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified jam kerja
     */
    public function destroy($kode_jam_kerja)
    {
        try {
            $jamkerja = JamKerja::findOrFail($kode_jam_kerja);

            // Check if jam kerja is being used in department configuration
            $used_in_dept = $jamkerja->konfigurasiJkDeptDetail()->count();

            if ($used_in_dept > 0) {
                return redirect()->back()
                    ->with(['warning' => 'Jam Kerja tidak dapat dihapus karena sedang digunakan oleh ' . $used_in_dept . ' konfigurasi departemen']);
            }

            // Check if jam kerja is being used in presensi
            $used_in_presensi = $jamkerja->presensi()->count();

            if ($used_in_presensi > 0) {
                return redirect()->back()
                    ->with(['warning' => 'Jam Kerja tidak dapat dihapus karena sudah digunakan dalam ' . $used_in_presensi . ' data presensi']);
            }

            $jamkerja->delete();

            return redirect()->route('panel.jamkerja.index')
                ->with(['success' => 'Data Jam Kerja berhasil dihapus']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}
