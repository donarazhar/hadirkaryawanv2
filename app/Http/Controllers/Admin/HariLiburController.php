<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HariLibur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HariLiburController extends Controller
{
    public function index(Request $request)
    {
        $query = HariLibur::query();
        if ($request->tahun) {
            $query->whereYear('tanggal', $request->tahun);
        } else {
            $query->whereYear('tanggal', date('Y'));
        }
        $harilibur = $query->orderBy('tanggal', 'desc')->get();
        return view('admin.harilibur.index', compact('harilibur'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date|unique:hari_libur,tanggal',
            'keterangan' => 'required|string|max:255'
        ], [
            'tanggal.unique' => 'Tanggal libur ini sudah terdaftar'
        ]);

        HariLibur::create([
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->back()->with('success', 'Hari libur berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date|unique:hari_libur,tanggal,'.$id,
            'keterangan' => 'required|string|max:255'
        ]);

        HariLibur::findOrFail($id)->update([
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->back()->with('success', 'Hari libur berhasil diupdate');
    }

    public function destroy($id)
    {
        HariLibur::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Hari libur berhasil dihapus');
    }
}
