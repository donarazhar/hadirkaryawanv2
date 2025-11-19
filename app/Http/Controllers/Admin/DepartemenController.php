<?php

namespace App\Http\Controllers\Admin;

use App\Models\Departemen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class DepartemenController extends Controller
{
    /**
     * Display a listing of departemen
     */
    public function index(Request $request)
    {
        $query = Departemen::query();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_dept', 'like', '%' . $search . '%')
                    ->orWhere('nama_dept', 'like', '%' . $search . '%');
            });
        }

        $departemen = $query->orderBy('kode_dept', 'ASC')->paginate(10);

        return view('admin.departemen.index', compact('departemen'));
    }

    /**
     * Show the form for creating a new departemen
     */
    public function create()
    {
        return view('admin.departemen.create');
    }

    /**
     * Store a newly created departemen
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_dept' => 'required|string|max:10|unique:departemen,kode_dept',
            'nama_dept' => 'required|string|max:255',
        ], [
            'kode_dept.required' => 'Kode Departemen wajib diisi',
            'kode_dept.unique' => 'Kode Departemen sudah terdaftar',
            'nama_dept.required' => 'Nama Departemen wajib diisi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            Departemen::create([
                'kode_dept' => $request->kode_dept,
                'nama_dept' => $request->nama_dept,
            ]);

            return redirect()->route('panel.departemen.index')
                ->with(['success' => 'Data Departemen berhasil ditambahkan']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Show the form for editing departemen
     */
    public function edit($kode_dept)
    {
        $departemen = Departemen::with('karyawan')->findOrFail($kode_dept);
        return view('admin.departemen.edit', compact('departemen'));
    }

    /**
     * Update the specified departemen
     */
    public function update(Request $request, $kode_dept)
    {
        $validator = Validator::make($request->all(), [
            'nama_dept' => 'required|string|max:255',
        ], [
            'nama_dept.required' => 'Nama Departemen wajib diisi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $departemen = Departemen::findOrFail($kode_dept);

            $departemen->update([
                'nama_dept' => $request->nama_dept,
            ]);

            return redirect()->route('panel.departemen.index')
                ->with(['success' => 'Data Departemen berhasil diupdate']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified departemen
     */
    public function destroy($kode_dept)
    {
        try {
            $departemen = Departemen::findOrFail($kode_dept);

            // Check if departemen has related karyawan
            if ($departemen->karyawan()->count() > 0) {
                return redirect()->back()
                    ->with(['warning' => 'Departemen tidak dapat dihapus karena masih memiliki karyawan']);
            }

            $departemen->delete();

            return redirect()->route('panel.departemen.index')
                ->with(['success' => 'Data Departemen berhasil dihapus']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}
