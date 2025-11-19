<?php

namespace App\Http\Controllers\Admin;


use App\Models\Cabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class CabangController extends Controller
{
    /**
     * Display a listing of cabang
     */
    public function index(Request $request)
    {
        $query = Cabang::query();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_cabang', 'like', '%' . $search . '%')
                    ->orWhere('nama_cabang', 'like', '%' . $search . '%')
                    ->orWhere('lokasi_cabang', 'like', '%' . $search . '%');
            });
        }

        $cabang = $query->orderBy('kode_cabang', 'ASC')->paginate(10);

        return view('admin.cabang.index', compact('cabang'));
    }

    /**
     * Show the form for creating a new cabang
     */
    public function create()
    {
        return view('admin.cabang.create');
    }

    /**
     * Store a newly created cabang
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_cabang' => 'required|string|max:10|unique:cabang,kode_cabang',
            'nama_cabang' => 'required|string|max:50',
            'lokasi_cabang' => 'required|string|max:255',
            'radius_cabang' => 'required|integer|min:1|max:10000'
        ], [
            'kode_cabang.required' => 'Kode Cabang wajib diisi',
            'kode_cabang.unique' => 'Kode Cabang sudah terdaftar',
            'nama_cabang.required' => 'Nama Cabang wajib diisi',
            'lokasi_cabang.required' => 'Lokasi Cabang wajib diisi',
            'radius_cabang.required' => 'Radius Cabang wajib diisi',
            'radius_cabang.integer' => 'Radius harus berupa angka',
            'radius_cabang.min' => 'Radius minimal 1 meter',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            Cabang::create([
                'kode_cabang' => $request->kode_cabang,
                'nama_cabang' => $request->nama_cabang,
                'lokasi_cabang' => $request->lokasi_cabang,
                'radius_cabang' => $request->radius_cabang
            ]);

            return redirect()->route('panel.cabang.index')
                ->with(['success' => 'Data Cabang berhasil ditambahkan']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Show the form for editing cabang
     */
    public function edit($kode_cabang)
    {
        $cabang = Cabang::findOrFail($kode_cabang);
        return view('admin.cabang.edit', compact('cabang'));
    }

    /**
     * Update the specified cabang
     */
    public function update(Request $request, $kode_cabang)
    {
        $validator = Validator::make($request->all(), [
            'nama_cabang' => 'required|string|max:50',
            'lokasi_cabang' => 'required|string|max:255',
            'radius_cabang' => 'required|integer|min:1|max:10000'
        ], [
            'nama_cabang.required' => 'Nama Cabang wajib diisi',
            'lokasi_cabang.required' => 'Lokasi Cabang wajib diisi',
            'radius_cabang.required' => 'Radius Cabang wajib diisi',
            'radius_cabang.integer' => 'Radius harus berupa angka',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $cabang = Cabang::findOrFail($kode_cabang);

            $cabang->update([
                'nama_cabang' => $request->nama_cabang,
                'lokasi_cabang' => $request->lokasi_cabang,
                'radius_cabang' => $request->radius_cabang
            ]);

            return redirect()->route('panel.cabang.index')
                ->with(['success' => 'Data Cabang berhasil diupdate']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified cabang
     */
    public function destroy($kode_cabang)
    {
        try {
            $cabang = Cabang::findOrFail($kode_cabang);

            // Check if cabang has related karyawan
            if ($cabang->karyawan()->count() > 0) {
                return redirect()->back()
                    ->with(['warning' => 'Cabang tidak dapat dihapus karena masih memiliki karyawan']);
            }

            $cabang->delete();

            return redirect()->route('panel.cabang.index')
                ->with(['success' => 'Data Cabang berhasil dihapus']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}
