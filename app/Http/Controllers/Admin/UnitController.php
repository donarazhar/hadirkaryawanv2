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
        $user = auth('user')->user();

        if ($user && $user->role === 'admin') {
            $query->where('branch_id', $user->branch_id);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }
        
        $units = $query->paginate(15);
        return view('admin.unit.index', compact('units'));
    }

    public function create()
    {
        $user = auth('user')->user();
        if ($user && $user->role === 'admin') {
            $branches = Branch::where('id', $user->branch_id)->get();
        } else {
            $branches = Branch::all();
        }

        $lastUnit = Unit::latest('id')->first();
        $nextNumber = $lastUnit ? $lastUnit->id + 1 : 1;
        $autoCode = 'UNT-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        
        return view('admin.unit.create', compact('branches', 'autoCode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'branch_id' => 'required|exists:branches,id',
            'is_sekretariat' => 'boolean'
        ]);

        $user = auth('user')->user();
        if ($user && $user->role === 'admin' && $request->branch_id != $user->branch_id) {
            abort(403, 'Unauthorized action.');
        }

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
        $user = auth('user')->user();
        if ($user && $user->role === 'admin' && $unit->branch_id != $user->branch_id) {
            abort(403, 'Unauthorized action.');
        }

        if ($user && $user->role === 'admin') {
            $branches = Branch::where('id', $user->branch_id)->get();
        } else {
            $branches = Branch::all();
        }

        return view('admin.unit.edit', compact('unit', 'branches'));
    }

    public function update(Request $request, Unit $unit)
    {
        $user = auth('user')->user();
        if ($user && $user->role === 'admin' && $unit->branch_id != $user->branch_id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'branch_id' => 'required|exists:branches,id',
            'is_sekretariat' => 'boolean'
        ]);

        if ($user && $user->role === 'admin' && $request->branch_id != $user->branch_id) {
            abort(403, 'Unauthorized action.');
        }

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
        $user = auth('user')->user();
        if ($user && $user->role === 'admin' && $unit->branch_id != $user->branch_id) {
            abort(403, 'Unauthorized action.');
        }

        $unit->delete();
        return redirect()->route('panel.unit.index')->with('success', 'Data unit berhasil dihapus!');
    }
}
