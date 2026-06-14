<?php

namespace App\Http\Controllers\Admin;

use App\Models\Karyawan;
use App\Models\Departemen;
use App\Models\Cabang;
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
        $query = Karyawan::with(['departemen', 'cabang']);

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nik', 'like', '%' . $search . '%')
                    ->orWhere('nama_lengkap', 'like', '%' . $search . '%')
                    ->orWhere('jabatan', 'like', '%' . $search . '%');
            });
        }

        // Filter by departemen
        if ($request->has('kode_dept') && $request->kode_dept != '') {
            $query->where('kode_dept', $request->kode_dept);
        }

        // Filter by cabang
        if ($request->has('kode_cabang') && $request->kode_cabang != '') {
            $query->where('kode_cabang', $request->kode_cabang);
        }

        $karyawan = $query->orderBy('nik', 'ASC')->paginate(10);
        $departemen = Departemen::orderBy('nama_dept')->get();
        $cabang = Cabang::orderBy('nama_cabang')->get();

        return view('admin.karyawan.index', compact('karyawan', 'departemen', 'cabang'));
    }

    /**
     * Show the form for creating a new karyawan
     */
    public function create()
    {
        $departemen = Departemen::orderBy('nama_dept')->get();
        $cabang = Cabang::orderBy('nama_cabang')->get();
        return view('admin.karyawan.create', compact('departemen', 'cabang'));
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
            'kode_dept' => 'required|exists:departemen,kode_dept',
            'kode_cabang' => 'required|exists:cabang,kode_cabang',
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
            'kode_dept.required' => 'Departemen wajib dipilih',
            'kode_cabang.required' => 'Cabang wajib dipilih',
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
            $data = [
                'nik' => $request->nik,
                'email' => $request->email,
                'nama_lengkap' => $request->nama_lengkap,
                'jabatan' => $request->jabatan,
                'no_hp' => $request->no_hp,
                'password' => Hash::make($request->password),
                'kode_dept' => $request->kode_dept,
                'kode_cabang' => $request->kode_cabang
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
        $karyawan = Karyawan::findOrFail($nik);
        $departemen = Departemen::orderBy('nama_dept')->get();
        $cabang = Cabang::orderBy('nama_cabang')->get();
        return view('admin.karyawan.edit', compact('karyawan', 'departemen', 'cabang'));
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
            'kode_dept' => 'required|exists:departemen,kode_dept',
            'kode_cabang' => 'required|exists:cabang,kode_cabang',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'nama_lengkap.required' => 'Nama Lengkap wajib diisi',
            'jabatan.required' => 'Jabatan wajib diisi',
            'no_hp.required' => 'No HP wajib diisi',
            'kode_dept.required' => 'Departemen wajib dipilih',
            'kode_cabang.required' => 'Cabang wajib dipilih',
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

            $data = [
                'email' => $request->email,
                'nama_lengkap' => $request->nama_lengkap,
                'jabatan' => $request->jabatan,
                'no_hp' => $request->no_hp,
                'kode_dept' => $request->kode_dept,
                'kode_cabang' => $request->kode_cabang
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

            $karyawan->update($data);

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
        // Path ke template (kita akan menyediakannya atau generate secara dinamis)
        // Karena generate lebih cepat, mari buat temporary csv/excel
        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=Template_Karyawan.csv',
            'Expires'             => '0',
            'Pragma'              => 'public'
        ];

        $list = [
            ['nik', 'nama_lengkap', 'jabatan', 'no_hp', 'email', 'kode_dept', 'kode_cabang'],
            ['1234567890', 'Jhon Doe', 'Staff', '08123456789', 'jhon@example.com', 'IT', 'JKT'],
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
