<?php

namespace App\Http\Controllers\Admin;

use App\Models\Karyawan;
use App\Models\Branch;
use App\Models\Unit;
use App\Models\Organ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class KaryawanAdminController extends Controller
{
    /**
     * Display a listing of karyawan
     */
    public function index(Request $request)
    {
        $query = Karyawan::with(['organ.unit.branch']);

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nik', 'like', '%' . $search . '%')
                    ->orWhere('nama_lengkap', 'like', '%' . $search . '%')
                    ->orWhere('jabatan', 'like', '%' . $search . '%');
            });
        }

        // Filter by organ
        if ($request->has('organ_id') && $request->organ_id != '') {
            $query->where('organ_id', $request->organ_id);
        }

        // Filter by unit
        if ($request->has('unit_id') && $request->unit_id != '') {
            $query->whereHas('organ', function($q) use ($request) {
                $q->where('unit_id', $request->unit_id);
            });
        }

        // Filter by branch
        if ($request->has('branch_id') && $request->branch_id != '') {
            $query->whereHas('organ.unit', function($q) use ($request) {
                $q->where('branch_id', $request->branch_id);
            });
        }

        $user = auth('user')->user();
        if ($user && $user->role === 'admin') {
            $query->whereHas('organ.unit', function($q) use ($user) {
                $q->where('branch_id', $user->branch_id);
            });
        }

        $karyawan = $query->orderBy('nik', 'ASC')->paginate(10);
        $organs = Organ::with('unit.branch')->orderBy('name')->get();
        
        if ($user && $user->role === 'admin') {
            $branches = Branch::where('id', $user->branch_id)->get();
            $units = Unit::where('branch_id', $user->branch_id)->orderBy('name')->get();
        } else {
            $branches = Branch::orderBy('name')->get();
            $units = Unit::orderBy('name')->get();
        }

        return view('admin.karyawan.index', compact('karyawan', 'organs', 'units', 'branches'));
    }

    /**
     * Show the form for creating a new karyawan
     */
    public function create()
    {
        $user = auth('user')->user();
        
        if ($user && $user->role === 'admin') {
            $organs = Organ::whereHas('unit', function($q) use ($user) {
                $q->where('branch_id', $user->branch_id);
            })->with('unit.branch')->orderBy('name')->get();
        } else {
            $organs = Organ::with('unit.branch')->orderBy('name')->get();
        }

        return view('admin.karyawan.create', compact('organs'));
    }

    /**
     * Store a newly created karyawan
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|string|max:10|unique:karyawan,nik',
            'email' => 'nullable|email|max:100|unique:karyawan,email',
            'nama_lengkap' => 'required|string|max:100',
            'jabatan' => 'required|string|max:20',
            'no_hp' => 'required|string|max:15',
            'password' => 'required|string|min:4',
            'organ_id' => 'required|exists:organs,id',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'nik.required' => 'NIK wajib diisi',
            'nik.unique' => 'NIK sudah terdaftar',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'nama_lengkap.required' => 'Nama Lengkap wajib diisi',
            'jabatan.required' => 'Jabatan wajib diisi',
            'no_hp.required' => 'No HP wajib diisi',
            'password.required' => 'Password wajib diisi',
            'organ_id.required' => 'Posisi (Organ) wajib dipilih',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format gambar harus jpeg, png, atau jpg',
            'foto.max' => 'Ukuran gambar maksimal 2MB'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = auth('user')->user();
        if ($user && $user->role === 'admin') {
            $organ = Organ::with('unit')->findOrFail($request->organ_id);
            if ($organ->unit->branch_id != $user->branch_id) {
                abort(403, 'Unauthorized action.');
            }
        }

        try {
            $data = [
                'nik' => $request->nik,
                'email' => $request->email,
                'nama_lengkap' => $request->nama_lengkap,
                'jabatan' => $request->jabatan,
                'no_hp' => $request->no_hp,
                'password' => Hash::make($request->password),
                'organ_id' => $request->organ_id
            ];

            // Handle foto upload
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $filename = $request->nik . '.' . $foto->getClientOriginalExtension();
                $foto->storeAs('public/uploads/karyawan', $filename);
                $data['foto'] = $filename;
            }

            Karyawan::create($data);

            \App\Helpers\LogHelper::record(
                'CREATE_KARYAWAN',
                "Menambahkan data karyawan baru dengan NIK: {$request->nik} ({$request->nama_lengkap})"
            );

            return redirect()->route('panel.karyawan.index')
                ->with(['success' => 'Data Karyawan berhasil ditambahkan']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Show the form for editing karyawan
     */
    public function edit($nik)
    {
        $karyawan = Karyawan::with('organ.unit')->findOrFail($nik);
        $user = auth('user')->user();

        if ($user && $user->role === 'admin' && $karyawan->branch_id !== $user->branch_id) {
            abort(403, 'Unauthorized action.');
        }

        if ($user && $user->role === 'admin') {
            $organs = Organ::whereHas('unit', function($q) use ($user) {
                $q->where('branch_id', $user->branch_id);
            })->with('unit.branch')->orderBy('name')->get();
        } else {
            $organs = Organ::with('unit.branch')->orderBy('name')->get();
        }

        return view('admin.karyawan.edit', compact('karyawan', 'organs'));
    }

    /**
     * Update the specified karyawan
     */
    public function update(Request $request, $nik)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['nullable', 'email', 'max:100', Rule::unique('karyawan')->ignore($nik, 'nik')],
            'nama_lengkap' => 'required|string|max:100',
            'jabatan' => 'required|string|max:20',
            'no_hp' => 'required|string|max:15',
            'password' => 'nullable|string|min:4',
            'organ_id' => 'required|exists:organs,id',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'nama_lengkap.required' => 'Nama Lengkap wajib diisi',
            'jabatan.required' => 'Jabatan wajib diisi',
            'no_hp.required' => 'No HP wajib diisi',
            'organ_id.required' => 'Posisi (Organ) wajib dipilih',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format gambar harus jpeg, png, atau jpg',
            'foto.max' => 'Ukuran gambar maksimal 2MB'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $karyawan = Karyawan::findOrFail($nik);
            $user = auth('user')->user();

            if ($user && $user->role === 'admin' && $karyawan->branch_id !== $user->branch_id) {
                abort(403, 'Unauthorized action.');
            }
            
            if ($user && $user->role === 'admin') {
                $organ = Organ::with('unit')->findOrFail($request->organ_id);
                if ($organ->unit->branch_id != $user->branch_id) {
                    abort(403, 'Unauthorized action.');
                }
            }

            $data = [
                'email' => $request->email,
                'nama_lengkap' => $request->nama_lengkap,
                'jabatan' => $request->jabatan,
                'no_hp' => $request->no_hp,
                'organ_id' => $request->organ_id
            ];

            // Update password if provided
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            // Handle foto upload
            if ($request->hasFile('foto')) {
                // Delete old foto
                if ($karyawan->foto) {
                    Storage::delete('public/uploads/karyawan/' . $karyawan->foto);
                }

                $foto = $request->file('foto');
                $filename = $nik . '.' . $foto->getClientOriginalExtension();
                $foto->storeAs('public/uploads/karyawan', $filename);
                $data['foto'] = $filename;
            }

            $oldEmail = $karyawan->getOriginal('email');
            $oldName = $karyawan->getOriginal('nama_lengkap');
            
            $karyawan->update($data);

            // Sync to persuratan.users
            try {
                $updated = 0;
                if ($oldEmail) {
                    $updated = \Illuminate\Support\Facades\DB::table('persuratan.users')
                        ->where('email', $oldEmail)
                        ->update([
                            'name' => $request->nama_lengkap,
                            'email' => $request->email,
                            'organ_id' => $request->organ_id
                        ]);
                }
                
                // Fallback: Jika di persuratan emailnya beda jauh sejak awal, coba sinkronisasi berdasarkan nama
                if ($updated == 0 && $oldName) {
                    \Illuminate\Support\Facades\DB::table('persuratan.users')
                        ->where('name', $oldName)
                        ->update([
                            'name' => $request->nama_lengkap,
                            'email' => $request->email,
                            'organ_id' => $request->organ_id
                        ]);
                }
            } catch (\Exception $syncEx) {
                \Illuminate\Support\Facades\Log::warning('Gagal sync ke db persuratan: ' . $syncEx->getMessage());
            }

            // Sync to todo.users
            try {
                if ($oldEmail) {
                    \Illuminate\Support\Facades\DB::table('todo.users')
                        ->where('email', $oldEmail)
                        ->update([
                            'nama' => $request->nama_lengkap,
                            'email' => $request->email
                        ]);
                }
            } catch (\Exception $syncEx) {
                \Illuminate\Support\Facades\Log::warning('Gagal sync ke db todo: ' . $syncEx->getMessage());
            }

            \App\Helpers\LogHelper::record(
                'UPDATE_KARYAWAN',
                "Mengubah data karyawan dengan NIK: {$nik}"
            );

            return redirect()->route('panel.karyawan.index')
                ->with(['success' => 'Data Karyawan berhasil diupdate']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified karyawan
     */
    public function destroy($nik)
    {
        try {
            $karyawan = Karyawan::findOrFail($nik);
            $user = auth('user')->user();

            if ($user && $user->role === 'admin' && $karyawan->branch_id !== $user->branch_id) {
                abort(403, 'Unauthorized action.');
            }

            // Delete foto if exists
            if ($karyawan->foto) {
                Storage::delete('public/uploads/karyawan/' . $karyawan->foto);
            }

            $karyawan->delete();

            \App\Helpers\LogHelper::record(
                'DELETE_KARYAWAN',
                "Menghapus data karyawan dengan NIK: {$nik}"
            );

            return redirect()->route('panel.karyawan.index')
                ->with(['success' => 'Data Karyawan berhasil dihapus']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Import Karyawan via Excel
     */
    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ], [
            'file.required' => 'File Excel/CSV wajib diupload',
            'file.mimes' => 'Format file harus .xlsx, .xls, atau .csv'
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\KaryawanImport, $request->file('file'));
            
            \App\Helpers\LogHelper::record(
                'IMPORT_KARYAWAN',
                "Melakukan import massal data karyawan via Excel"
            );

            return redirect()->route('panel.karyawan.index')
                ->with(['success' => 'Data Karyawan berhasil diimport. Data dengan NIK yang sudah ada akan dilewati.']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['error' => 'Terjadi kesalahan saat import: ' . $e->getMessage()]);
        }
    }

    /**
     * Download Excel Template
     */
    public function downloadTemplate()
    {
        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=Template_Karyawan.csv',
            'Expires'             => '0',
            'Pragma'              => 'public'
        ];

        $list = [
            ['nik', 'nama_lengkap', 'jabatan', 'no_hp', 'email', 'organ_id'],
            ['1234567890', 'Jhon Doe', 'Staff', '08123456789', 'jhon@example.com', '1'],
        ];

        $callback = function() use ($list) {
            $FH = fopen('php://output', 'w');
            foreach ($list as $row) {
                fputcsv($FH, $row);
            }
            fclose($FH);
        };

        return response()->stream($callback, 200, $headers);
    }
}
