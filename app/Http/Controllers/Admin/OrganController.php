<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Organ;
use App\Models\Unit;

class OrganController extends Controller
{
    public function index(Request $request)
    {
        $query = Organ::with('unit.branch');
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }
        
        $organs = $query->paginate(15);
        return view('admin.organ.index', compact('organs'));
    }

    public function create()
    {
        $units = Unit::with('branch')->get();
        return view('admin.organ.create', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit_id' => 'required|exists:units,id'
        ]);

        Organ::create($request->only(['name', 'unit_id']));

        return redirect()->route('panel.organ.index')->with('success', 'Data organ/jabatan berhasil ditambahkan!');
    }

    public function edit(Organ $organ)
    {
        $units = Unit::with('branch')->get();
        return view('admin.organ.edit', compact('organ', 'units'));
    }

    public function update(Request $request, Organ $organ)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit_id' => 'required|exists:units,id'
        ]);

        $organ->update($request->only(['name', 'unit_id']));

        return redirect()->route('panel.organ.index')->with('success', 'Data organ/jabatan berhasil diperbarui!');
    }

    public function destroy(Organ $organ)
    {
        $organ->delete();
        return redirect()->route('panel.organ.index')->with('success', 'Data organ/jabatan berhasil dihapus!');
    }
}
