<?php

namespace App\Http\Controllers\Admin;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class BranchController extends Controller
{
    /**
     * Display a listing of branches
     */
    public function index(Request $request)
    {
        $query = Branch::query();
        $user = auth('user')->user();

        if ($user && $user->role === 'admin') {
            $query->where('id', $user->branch_id);
        }

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('lokasi_cabang', 'like', '%' . $search . '%');
            });
        }

        $branches = $query->orderBy('name', 'ASC')->paginate(10);

        return view('admin.branch.index', compact('branches'));
    }

    /**
     * Show the form for creating a new branch
     */
    public function create()
    {
        return view('admin.branch.create');
    }

    /**
     * Store a newly created branch
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'           => 'required|string|max:255|unique:branches,name',
            'lokasi_cabang'  => 'required|string|max:255',
            'radius_cabang'  => 'required|integer|min:1|max:10000',
        ], [
            'name.required'          => 'Nama Cabang wajib diisi',
            'name.unique'            => 'Nama Cabang sudah terdaftar',
            'lokasi_cabang.required' => 'Lokasi Cabang wajib diisi',
            'radius_cabang.required' => 'Radius Cabang wajib diisi',
            'radius_cabang.integer'  => 'Radius harus berupa angka',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            Branch::create([
                'name'          => $request->name,
                'lokasi_cabang' => $request->lokasi_cabang,
                'radius_cabang' => $request->radius_cabang,
                'qr_token'      => \Illuminate\Support\Str::random(32),
            ]);

            return redirect()->route('panel.branch.index')
                ->with('success', 'Data Cabang baru berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing branch
     */
    public function edit($id)
    {
        $user = auth('user')->user();
        if ($user && $user->role === 'admin' && $id != $user->branch_id) {
            abort(403, 'Unauthorized action.');
        }

        $branch = Branch::findOrFail($id);
        return view('admin.branch.edit', compact('branch'));
    }

    /**
     * Update the specified branch
     */
    public function update(Request $request, $id)
    {
        $user = auth('user')->user();
        if ($user && $user->role === 'admin' && $id != $user->branch_id) {
            abort(403, 'Unauthorized action.');
        }

        $validator = Validator::make($request->all(), [
            'lokasi_cabang' => 'required|string|max:255',
            'radius_cabang' => 'required|integer|min:1|max:10000'
        ], [
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
            $branch = Branch::findOrFail($id);

            $branch->update([
                // Name should not be updated as it's from master data, but location and radius can be
                'lokasi_cabang' => $request->lokasi_cabang,
                'radius_cabang' => $request->radius_cabang
            ]);

            return redirect()->route('panel.branch.index')
                ->with(['success' => 'Data Lokasi Cabang berhasil diupdate']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Print QR Code for Branch
     */
    public function cetakQr($id)
    {
        $user = auth('user')->user();
        if ($user && $user->role === 'admin' && $id != $user->branch_id) {
            abort(403, 'Unauthorized action.');
        }

        $branch = Branch::findOrFail($id);
        
        // Ensure qr_token exists
        if (!$branch->qr_token) {
            $branch->update(['qr_token' => \Illuminate\Support\Str::random(32)]);
        }

        return view('admin.branch.qr', compact('branch'));
    }

    /**
     * Remove the specified branch
     */
    public function destroy($id)
    {
        $user = auth('user')->user();
        if ($user && $user->role !== 'superadmin') {
            abort(403, 'Unauthorized action.');
        }

        try {
            $branch = Branch::findOrFail($id);
            
            if ($branch->name === 'Cabang Global (Semua Lokasi)') {
                return redirect()->back()->with('error', 'Cabang Global tidak dapat dihapus karena merupakan cabang sistem khusus.');
            }
            
            $branch->delete();
            return redirect()->route('panel.branch.index')->with('success', 'Data Cabang berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus cabang: ' . $e->getMessage());
        }
    }
}
