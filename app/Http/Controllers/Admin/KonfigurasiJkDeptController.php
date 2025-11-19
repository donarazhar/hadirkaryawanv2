<?php

namespace App\Http\Controllers\Admin;

use App\Models\KonfigurasiJkDept;
use App\Models\KonfigurasiJkDeptDetail;
use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\JamKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class KonfigurasiJkDeptController extends Controller
{
    /**
     * Display a listing of konfigurasi
     */
    public function index(Request $request)
    {
        $query = KonfigurasiJkDept::with(['cabang', 'departemen', 'details.jamKerja']);

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('kode_jk_dept', 'like', '%' . $search . '%');
        }

        // Filter by cabang
        if ($request->has('kode_cabang') && $request->kode_cabang != '') {
            $query->where('kode_cabang', $request->kode_cabang);
        }

        $konfigurasi = $query->orderBy('kode_jk_dept', 'DESC')->paginate(10);
        $cabang = Cabang::orderBy('nama_cabang')->get();

        return view('admin.konfigurasi-jk-dept.index', compact('konfigurasi', 'cabang'));
    }

    /**
     * Show the form for creating a new konfigurasi
     */
    public function create()
    {
        $cabang = Cabang::orderBy('nama_cabang')->get();
        $departemen = Departemen::orderBy('nama_dept')->get();
        $jamkerja = JamKerja::orderBy('nama_jam_kerja')->get();

        return view('admin.konfigurasi-jk-dept.create', compact('cabang', 'departemen', 'jamkerja'));
    }

    /**
     * Store a newly created konfigurasi
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_jk_dept' => 'required|string|max:10|unique:konfigurasi_jk_dept,kode_jk_dept',
            'kode_cabang' => 'required|exists:cabang,kode_cabang',
            'kode_dept' => 'required|exists:departemen,kode_dept',
            'hari.*' => 'required|string',
            'kode_jam_kerja.*' => 'required|exists:jam_kerja,kode_jam_kerja'
        ], [
            'kode_jk_dept.required' => 'Kode Konfigurasi wajib diisi',
            'kode_jk_dept.unique' => 'Kode Konfigurasi sudah terdaftar',
            'kode_cabang.required' => 'Cabang wajib dipilih',
            'kode_dept.required' => 'Departemen wajib dipilih',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            // Create master konfigurasi
            $konfigurasi = KonfigurasiJkDept::create([
                'kode_jk_dept' => $request->kode_jk_dept,
                'kode_cabang' => $request->kode_cabang,
                'kode_dept' => $request->kode_dept
            ]);

            // Create detail konfigurasi
            if ($request->has('hari') && is_array($request->hari)) {
                foreach ($request->hari as $index => $hari) {
                    if (isset($request->kode_jam_kerja[$index])) {
                        KonfigurasiJkDeptDetail::create([
                            'kode_jk_dept' => $request->kode_jk_dept,
                            'hari' => $hari,
                            'kode_jam_kerja' => $request->kode_jam_kerja[$index]
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('panel.konfigurasi-jk-dept.index')
                ->with(['success' => 'Konfigurasi Jam Kerja Departemen berhasil ditambahkan']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Show the form for editing konfigurasi
     */
    public function edit($kode_jk_dept)
    {
        $konfigurasi = KonfigurasiJkDept::with('details')->findOrFail($kode_jk_dept);
        $cabang = Cabang::orderBy('nama_cabang')->get();
        $departemen = Departemen::orderBy('nama_dept')->get();
        $jamkerja = JamKerja::orderBy('nama_jam_kerja')->get();

        return view('admin.konfigurasi-jk-dept.edit', compact('konfigurasi', 'cabang', 'departemen', 'jamkerja'));
    }

    /**
     * Update the specified konfigurasi
     */
    public function update(Request $request, $kode_jk_dept)
    {
        $validator = Validator::make($request->all(), [
            'kode_cabang' => 'required|exists:cabang,kode_cabang',
            'kode_dept' => 'required|exists:departemen,kode_dept',
            'hari.*' => 'required|string',
            'kode_jam_kerja.*' => 'required|exists:jam_kerja,kode_jam_kerja'
        ], [
            'kode_cabang.required' => 'Cabang wajib dipilih',
            'kode_dept.required' => 'Departemen wajib dipilih',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $konfigurasi = KonfigurasiJkDept::findOrFail($kode_jk_dept);

            // Update master konfigurasi
            $konfigurasi->update([
                'kode_cabang' => $request->kode_cabang,
                'kode_dept' => $request->kode_dept
            ]);

            // Delete old details
            KonfigurasiJkDeptDetail::where('kode_jk_dept', $kode_jk_dept)->delete();

            // Create new details
            if ($request->has('hari') && is_array($request->hari)) {
                foreach ($request->hari as $index => $hari) {
                    if (isset($request->kode_jam_kerja[$index])) {
                        KonfigurasiJkDeptDetail::create([
                            'kode_jk_dept' => $kode_jk_dept,
                            'hari' => $hari,
                            'kode_jam_kerja' => $request->kode_jam_kerja[$index]
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('panel.konfigurasi-jk-dept.index')
                ->with(['success' => 'Konfigurasi Jam Kerja Departemen berhasil diupdate']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified konfigurasi
     */
    public function destroy($kode_jk_dept)
    {
        DB::beginTransaction();
        try {
            // Delete details first
            KonfigurasiJkDeptDetail::where('kode_jk_dept', $kode_jk_dept)->delete();

            // Delete master
            $konfigurasi = KonfigurasiJkDept::findOrFail($kode_jk_dept);
            $konfigurasi->delete();

            DB::commit();

            return redirect()->route('panel.konfigurasi-jk-dept.index')
                ->with(['success' => 'Konfigurasi Jam Kerja Departemen berhasil dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Show detail konfigurasi
     */
    public function show($kode_jk_dept)
    {
        $konfigurasi = KonfigurasiJkDept::with(['cabang', 'departemen', 'details.jamKerja'])
            ->findOrFail($kode_jk_dept);

        return view('admin.konfigurasi-jk-dept.show', compact('konfigurasi'));
    }
}
