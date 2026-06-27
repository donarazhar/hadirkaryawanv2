<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\Branch;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $query = Unit::with('branch');
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
        }
        
        $units = $query->paginate(15);
        return view('admin.unit.index', compact('units'));
    }

    public function create()
    {
        $branches = Branch::all();
        return view('admin.unit.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'branch_id' => 'required|exists:branches,id',
            'is_sekretariat' => 'boolean'
        ]);

        Unit::create([
            'name' => $request->name,
            'code' => $request->code,
            'branch_id' => $request->branch_id,
            'is_sekretariat' => $request->is_sekretariat ?? false
        ]);

        return redirect()->route('panel.unit.index')->with('success', 'Data unit berhasil ditambahkan!');
    }

    public function edit(Unit $unit)
    {
        $branches = Branch::all();
        return view('admin.unit.edit', compact('unit', 'branches'));
    }

    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'branch_id' => 'required|exists:branches,id',
            'is_sekretariat' => 'boolean'
        ]);

        $unit->update([
            'name' => $request->name,
            'code' => $request->code,
            'branch_id' => $request->branch_id,
            'is_sekretariat' => $request->is_sekretariat ?? false
        ]);

        return redirect()->route('panel.unit.index')->with('success', 'Data unit berhasil diperbarui!');
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();
        return redirect()->route('panel.unit.index')->with('success', 'Data unit berhasil dihapus!');
    }
}
