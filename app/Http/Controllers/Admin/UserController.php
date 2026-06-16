<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::with(['cabang', 'departemen']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('role', 'like', '%' . $search . '%');
            });
        }

        $users = $query->orderBy('name', 'ASC')->paginate(10);

        return view('admin.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cabang = Cabang::orderBy('nama_cabang', 'ASC')->get();
        $departemen = Departemen::orderBy('nama_dept', 'ASC')->get();
        $karyawan = \App\Models\Karyawan::orderBy('nama_lengkap', 'ASC')->get();
        return view('admin.user.create', compact('cabang', 'departemen', 'karyawan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => $request->role === 'superadmin' ? 'required|min:6' : 'nullable',
            'role' => ['required', Rule::in(['superadmin', 'admin', 'pimpinan'])],
            'kode_cabang' => 'nullable|string|exists:cabang,kode_cabang',
            'kode_dept' => 'nullable|string|exists:departemen,kode_dept',
        ], [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'role.required' => 'Role wajib dipilih',
        ]);

        $validator->after(function ($validator) use ($request) {
            if (in_array($request->role, ['admin', 'pimpinan']) && empty($request->kode_cabang)) {
                $validator->errors()->add('kode_cabang', 'Cabang wajib dipilih untuk role Admin dan Pimpinan.');
            }
            if ($request->role === 'pimpinan' && empty($request->kode_dept)) {
                $validator->errors()->add('kode_dept', 'Departemen wajib dipilih untuk role Pimpinan.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $password = Hash::make($request->password);
            if ($request->role !== 'superadmin' && !empty($request->nik_karyawan)) {
                $karyawan = \App\Models\Karyawan::where('nik', $request->nik_karyawan)->first();
                if ($karyawan && $karyawan->password) {
                    $password = $karyawan->password;
                }
            }

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'nik_karyawan' => $request->nik_karyawan ?? null,
                'password' => $password,
                'role' => $request->role,
                'kode_cabang' => $request->role === 'superadmin' ? null : $request->kode_cabang,
                'kode_dept' => $request->role === 'pimpinan' ? $request->kode_dept : null,
            ]);

            return redirect()->route('panel.user.index')->with(['success' => 'Data User berhasil ditambahkan']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $cabang = Cabang::orderBy('nama_cabang', 'ASC')->get();
        $departemen = Departemen::orderBy('nama_dept', 'ASC')->get();
        $karyawan = \App\Models\Karyawan::orderBy('nama_lengkap', 'ASC')->get();
        return view('admin.user.edit', compact('user', 'cabang', 'departemen', 'karyawan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|min:6',
            'role' => ['required', Rule::in(['superadmin', 'admin', 'pimpinan'])],
            'kode_cabang' => 'nullable|string|exists:cabang,kode_cabang',
            'kode_dept' => 'nullable|string|exists:departemen,kode_dept',
        ], [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email sudah terdaftar',
            'password.min' => 'Password minimal 6 karakter',
            'role.required' => 'Role wajib dipilih',
        ]);

        $validator->after(function ($validator) use ($request) {
            if (in_array($request->role, ['admin', 'pimpinan']) && empty($request->kode_cabang)) {
                $validator->errors()->add('kode_cabang', 'Cabang wajib dipilih untuk role Admin dan Pimpinan.');
            }
            if ($request->role === 'pimpinan' && empty($request->kode_dept)) {
                $validator->errors()->add('kode_dept', 'Departemen wajib dipilih untuk role Pimpinan.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'nik_karyawan' => $request->nik_karyawan ?? null,
                'role' => $request->role,
                'kode_cabang' => $request->role === 'superadmin' ? null : $request->kode_cabang,
                'kode_dept' => $request->role === 'pimpinan' ? $request->kode_dept : null,
            ];

            if ($request->role !== 'superadmin' && !empty($request->nik_karyawan)) {
                $karyawan = \App\Models\Karyawan::where('nik', $request->nik_karyawan)->first();
                if ($karyawan && $karyawan->password) {
                    $data['password'] = $karyawan->password;
                }
            } elseif ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            return redirect()->route('panel.user.index')->with(['success' => 'Data User berhasil diperbarui']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Mencegah hapus diri sendiri jika user login sama dengan user yang dihapus
            if (auth('user')->id() === $user->id) {
                return redirect()->back()->with(['warning' => 'Anda tidak dapat menghapus akun yang sedang Anda gunakan.']);
            }

            $user->delete();

            return redirect()->route('panel.user.index')->with(['success' => 'Data User berhasil dihapus']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}
