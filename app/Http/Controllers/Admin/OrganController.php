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
        $user = auth('user')->user();

        if ($user && $user->role === 'admin') {
            $query->whereHas('unit', function($q) use ($user) {
                $q->where('branch_id', $user->branch_id);
            });
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }
        
        $organs = $query->paginate(15);
        return view('admin.organ.index', compact('organs'));
    }

    public function create()
    {
        $user = auth('user')->user();
        $query = Unit::with('branch');
        
        if ($user && $user->role === 'admin') {
            $query->where('branch_id', $user->branch_id);
        }
        
        $units = $query->get();
        return view('admin.organ.create', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit_id' => 'required|exists:units,id'
        ]);

        $user = auth('user')->user();
        if ($user && $user->role === 'admin') {
            $unit = Unit::findOrFail($request->unit_id);
            if ($unit->branch_id != $user->branch_id) {
                abort(403, 'Unauthorized action.');
            }
        }

        Organ::create($request->only(['name', 'unit_id']));

        return redirect()->route('panel.organ.index')->with('success', 'Data organ/jabatan berhasil ditambahkan!');
    }

    public function edit(Organ $organ)
    {
        $user = auth('user')->user();
        if ($user && $user->role === 'admin' && $organ->unit->branch_id != $user->branch_id) {
            abort(403, 'Unauthorized action.');
        }

        $query = Unit::with('branch');
        if ($user && $user->role === 'admin') {
            $query->where('branch_id', $user->branch_id);
        }
        $units = $query->get();

        return view('admin.organ.edit', compact('organ', 'units'));
    }

    public function update(Request $request, Organ $organ)
    {
        $user = auth('user')->user();
        if ($user && $user->role === 'admin' && $organ->unit->branch_id != $user->branch_id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'unit_id' => 'required|exists:units,id'
        ]);

        if ($user && $user->role === 'admin') {
            $unit = Unit::findOrFail($request->unit_id);
            if ($unit->branch_id != $user->branch_id) {
                abort(403, 'Unauthorized action.');
            }
        }

        $organ->update($request->only(['name', 'unit_id']));

        return redirect()->route('panel.organ.index')->with('success', 'Data organ/jabatan berhasil diperbarui!');
    }

    public function destroy(Organ $organ)
    {
        $user = auth('user')->user();
        if ($user && $user->role === 'admin' && $organ->unit->branch_id != $user->branch_id) {
            abort(403, 'Unauthorized action.');
        }

        $organ->delete();
        return redirect()->route('panel.organ.index')->with('success', 'Data organ/jabatan berhasil dihapus!');
    }
}
