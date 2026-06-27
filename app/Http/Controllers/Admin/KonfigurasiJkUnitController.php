<?php

namespace App\Http\Controllers\Admin;

use App\Models\KonfigurasiJkUnit;
use App\Models\KonfigurasiJkUnitDetail;
use App\Models\Branch;
use App\Models\Unit;
use App\Models\JamKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class KonfigurasiJkUnitController extends Controller
{
    /**
     * Display a listing of konfigurasi
     */
    public function index(Request $request)
    {
        $query = KonfigurasiJkUnit::with(['branch', 'unit', 'details.jamKerja']);

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('kode_jk_unit', 'like', '%' . $search . '%');
        }

        // Filter by branch
        if ($request->has('branch_id') && $request->branch_id != '') {
            $query->where('branch_id', $request->branch_id);
        }

        $konfigurasi = $query->orderBy('kode_jk_unit', 'DESC')->paginate(10);
        $branches = Branch::orderBy('name')->get();

        return view('admin.konfigurasi-jk-unit.index', compact('konfigurasi', 'branches'));
    }

    /**
     * Show the form for creating a new konfigurasi
     */
    public function create()
    {
        $branches = Branch::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        $jamkerja = JamKerja::orderBy('nama_jam_kerja')->get();

        return view('admin.konfigurasi-jk-unit.create', compact('branches', 'units', 'jamkerja'));
    }

    /**
     * Store a newly created konfigurasi
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_jk_unit' => 'required|string|max:20|unique:konfigurasi_jk_unit,kode_jk_unit',
            'branch_id' => 'required|exists:branches,id',
            'unit_id' => 'required|exists:units,id',
            'hari.*' => 'required|string',
            'kode_jam_kerja.*' => 'required|exists:jam_kerja,kode_jam_kerja'
        ], [
            'kode_jk_unit.required' => 'Kode Konfigurasi wajib diisi',
            'kode_jk_unit.unique' => 'Kode Konfigurasi sudah terdaftar',
            'branch_id.required' => 'Cabang wajib dipilih',
            'unit_id.required' => 'Unit wajib dipilih',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            // Create master konfigurasi
            $konfigurasi = KonfigurasiJkUnit::create([
                'kode_jk_unit' => $request->kode_jk_unit,
                'branch_id' => $request->branch_id,
                'unit_id' => $request->unit_id
            ]);

            // Create detail konfigurasi
            if ($request->has('hari') && is_array($request->hari)) {
                foreach ($request->hari as $index => $hari) {
                    if (isset($request->kode_jam_kerja[$index])) {
                        KonfigurasiJkUnitDetail::create([
                            'kode_jk_unit' => $request->kode_jk_unit,
                            'hari' => $hari,
                            'kode_jam_kerja' => $request->kode_jam_kerja[$index]
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('panel.konfigurasi-jk-unit.index')
                ->with(['success' => 'Konfigurasi Jam Kerja Unit berhasil ditambahkan']);
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
    public function edit($kode_jk_unit)
    {
        $konfigurasi = KonfigurasiJkUnit::with('details')->findOrFail($kode_jk_unit);
        $branches = Branch::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        $jamkerja = JamKerja::orderBy('nama_jam_kerja')->get();

        return view('admin.konfigurasi-jk-unit.edit', compact('konfigurasi', 'branches', 'units', 'jamkerja'));
    }

    /**
     * Update the specified konfigurasi
     */
    public function update(Request $request, $kode_jk_unit)
    {
        $validator = Validator::make($request->all(), [
            'branch_id' => 'required|exists:branches,id',
            'unit_id' => 'required|exists:units,id',
            'hari.*' => 'required|string',
            'kode_jam_kerja.*' => 'required|exists:jam_kerja,kode_jam_kerja'
        ], [
            'branch_id.required' => 'Cabang wajib dipilih',
            'unit_id.required' => 'Unit wajib dipilih',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $konfigurasi = KonfigurasiJkUnit::findOrFail($kode_jk_unit);

            // Update master konfigurasi
            $konfigurasi->update([
                'branch_id' => $request->branch_id,
                'unit_id' => $request->unit_id
            ]);

            // Delete old details
            KonfigurasiJkUnitDetail::where('kode_jk_unit', $kode_jk_unit)->delete();

            // Create new details
            if ($request->has('hari') && is_array($request->hari)) {
                foreach ($request->hari as $index => $hari) {
                    if (isset($request->kode_jam_kerja[$index])) {
                        KonfigurasiJkUnitDetail::create([
                            'kode_jk_unit' => $kode_jk_unit,
                            'hari' => $hari,
                            'kode_jam_kerja' => $request->kode_jam_kerja[$index]
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('panel.konfigurasi-jk-unit.index')
                ->with(['success' => 'Konfigurasi Jam Kerja Unit berhasil diupdate']);
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
    public function destroy($kode_jk_unit)
    {
        DB::beginTransaction();
        try {
            // Delete details first
            KonfigurasiJkUnitDetail::where('kode_jk_unit', $kode_jk_unit)->delete();

            // Delete master
            $konfigurasi = KonfigurasiJkUnit::findOrFail($kode_jk_unit);
            $konfigurasi->delete();

            DB::commit();

            return redirect()->route('panel.konfigurasi-jk-unit.index')
                ->with(['success' => 'Konfigurasi Jam Kerja Unit berhasil dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Show detail konfigurasi
     */
    public function show($kode_jk_unit)
    {
        $konfigurasi = KonfigurasiJkUnit::with(['branch', 'unit', 'details.jamKerja'])
            ->findOrFail($kode_jk_unit);

        return view('admin.konfigurasi-jk-unit.show', compact('konfigurasi'));
    }
}
