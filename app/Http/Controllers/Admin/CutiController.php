<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanCuti;
use Illuminate\Http\Request;

class CutiController extends Controller
{
    public function index()
    {
        $cuti = PengajuanCuti::orderBy('kode_cuti', 'asc')->get();
        return view('admin.cuti.index', compact('cuti'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_cuti' => 'required|string|max:5|unique:pengajuan_cuti',
            'nama_cuti' => 'required|string|max:50',
            'jml_hari' => 'required|integer|min:1',
            'status' => 'required|in:aktif,nonaktif'
        ]);

        PengajuanCuti::create($request->all());

        return redirect()->route('panel.cuti.index')->with('success', 'Data Cuti berhasil ditambahkan');
    }

    public function update(Request $request, $kode_cuti)
    {
        $request->validate([
            'nama_cuti' => 'required|string|max:50',
            'jml_hari' => 'required|integer|min:1',
            'status' => 'required|in:aktif,nonaktif'
        ]);

        $cuti = PengajuanCuti::findOrFail($kode_cuti);
        $cuti->update($request->except('kode_cuti'));

        return redirect()->route('panel.cuti.index')->with('success', 'Data Cuti berhasil diperbarui');
    }

    public function destroy($kode_cuti)
    {
        $cuti = PengajuanCuti::findOrFail($kode_cuti);
        
        if ($cuti->pengajuanIzin()->count() > 0) {
            return redirect()->route('panel.cuti.index')->with('error', 'Cuti tidak bisa dihapus karena sudah ada data pengajuan');
        }

        $cuti->delete();

        return redirect()->route('panel.cuti.index')->with('success', 'Data Cuti berhasil dihapus');
    }
}
