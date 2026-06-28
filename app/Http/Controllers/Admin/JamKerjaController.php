<?php

namespace App\Http\Controllers\Admin;

use App\Models\JamKerja;
use App\Models\JamKerjaShift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class JamKerjaController extends Controller
{
    /**
     * Display a listing of jam kerja
     */
    public function index(Request $request)
    {
        $query = JamKerja::with('shifts');

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_jam_kerja', 'like', '%' . $search . '%')
                    ->orWhere('nama_jam_kerja', 'like', '%' . $search . '%');
            });
        }

        // Filter by tipe
        if ($request->has('tipe') && $request->tipe != '') {
            $query->where('tipe_jam_kerja', $request->tipe);
        }

        $jamkerja = $query->orderBy('kode_jam_kerja', 'ASC')->paginate(10);

        return view('admin.jamkerja.index', compact('jamkerja'));
    }

    /**
     * Show the form for creating a new jam kerja
     */
    public function create()
    {
        // Get the latest JamKerja code to auto-generate the next one
        $lastJamKerja = JamKerja::orderBy('kode_jam_kerja', 'desc')->first();
        if ($lastJamKerja) {
            $lastCode = $lastJamKerja->kode_jam_kerja;
            // Assuming format is JK-XXX
            $number = (int) str_replace('JK-', '', $lastCode);
            $nextNumber = $number + 1;
        } else {
            $nextNumber = 1;
        }
        $autoCode = 'JK-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        return view('admin.jamkerja.create', compact('autoCode'));
    }

    /**
     * Store a newly created jam kerja
     */
    public function store(Request $request)
    {
        // Validation rules
        $rules = [
            'kode_jam_kerja' => 'required|string|max:10|unique:jam_kerja,kode_jam_kerja',
            'nama_jam_kerja' => 'required|string|max:50',
            'tipe_jam_kerja' => 'required|in:regular,multi_shift',
            'lintashari' => 'required|in:0,1'
        ];

        // Add rules for regular
        if ($request->tipe_jam_kerja == 'regular') {
            $rules['awal_jam_masuk'] = 'required|date_format:H:i';
            $rules['jam_masuk'] = 'required|date_format:H:i';
            $rules['akhir_jam_masuk'] = 'required|date_format:H:i';
            $rules['jam_pulang'] = 'required|date_format:H:i';
        }

        // Add rules for multi_shift
        if ($request->tipe_jam_kerja == 'multi_shift') {
            $rules['total_shift'] = 'required|integer|min:1|max:10';
            $rules['shifts.*.shift_ke'] = 'required|integer';
            $rules['shifts.*.nama_shift'] = 'required|string|max:50';
            $rules['shifts.*.awal_jam_masuk'] = 'required|date_format:H:i';
            $rules['shifts.*.jam_masuk'] = 'required|date_format:H:i';
            $rules['shifts.*.akhir_jam_masuk'] = 'required|date_format:H:i';
            $rules['shifts.*.jam_pulang'] = 'required|date_format:H:i';
        }

        $validator = Validator::make($request->all(), $rules, [
            'kode_jam_kerja.required' => 'Kode Jam Kerja wajib diisi',
            'kode_jam_kerja.unique' => 'Kode Jam Kerja sudah terdaftar',
            'nama_jam_kerja.required' => 'Nama Jam Kerja wajib diisi',
            'tipe_jam_kerja.required' => 'Tipe Jam Kerja wajib dipilih',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            // Create JamKerja master
            $jamkerja = JamKerja::create([
                'kode_jam_kerja' => $request->kode_jam_kerja,
                'nama_jam_kerja' => $request->nama_jam_kerja,
                'tipe_jam_kerja' => $request->tipe_jam_kerja,
                'lintashari' => $request->lintashari,
                'total_shift' => $request->tipe_jam_kerja == 'multi_shift' ? $request->total_shift : 1,
                'awal_jam_masuk' => $request->awal_jam_masuk ?? '00:00:00',
                'jam_masuk' => $request->jam_masuk ?? '00:00:00',
                'akhir_jam_masuk' => $request->akhir_jam_masuk ?? '00:00:00',
                'jam_pulang' => $request->jam_pulang ?? '23:59:59',
            ]);

            // If multi_shift, create shifts
            if ($request->tipe_jam_kerja == 'multi_shift' && $request->has('shifts')) {
                foreach ($request->shifts as $shift) {
                    JamKerjaShift::create([
                        'kode_jam_kerja' => $request->kode_jam_kerja,
                        'shift_ke' => $shift['shift_ke'],
                        'nama_shift' => $shift['nama_shift'],
                        'awal_jam_masuk' => $shift['awal_jam_masuk'],
                        'jam_masuk' => $shift['jam_masuk'],
                        'akhir_jam_masuk' => $shift['akhir_jam_masuk'],
                        'jam_pulang' => $shift['jam_pulang'],
                        'is_active' => true
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('panel.jamkerja.index')
                ->with(['success' => 'Data Jam Kerja berhasil ditambahkan']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Show the form for editing jam kerja
     */
    public function edit($kode_jam_kerja)
    {
        $jamkerja = JamKerja::with(['shifts', 'konfigurasiJkDeptDetail'])
            ->findOrFail($kode_jam_kerja);

        // Hitung total penggunaan jam kerja
        $total_konfigurasi_dept = $jamkerja->konfigurasiJkDeptDetail->count();
        $total_penggunaan = $total_konfigurasi_dept;

        return view('admin.jamkerja.edit', compact('jamkerja', 'total_penggunaan', 'total_konfigurasi_dept'));
    }

    /**
     * Update the specified jam kerja
     */
    public function update(Request $request, $kode_jam_kerja)
    {
        $jamkerja = JamKerja::findOrFail($kode_jam_kerja);

        // Validation rules
        $rules = [
            'nama_jam_kerja' => 'required|string|max:50',
            'tipe_jam_kerja' => 'required|in:regular,multi_shift',
            'lintashari' => 'required|in:0,1'
        ];

        // Add rules for regular
        if ($request->tipe_jam_kerja == 'regular') {
            $rules['awal_jam_masuk'] = 'required|date_format:H:i';
            $rules['jam_masuk'] = 'required|date_format:H:i';
            $rules['akhir_jam_masuk'] = 'required|date_format:H:i';
            $rules['jam_pulang'] = 'required|date_format:H:i';
        }

        // Add rules for multi_shift
        if ($request->tipe_jam_kerja == 'multi_shift') {
            $rules['total_shift'] = 'required|integer|min:1|max:10';
            $rules['shifts.*.shift_ke'] = 'required|integer';
            $rules['shifts.*.nama_shift'] = 'required|string|max:50';
            $rules['shifts.*.awal_jam_masuk'] = 'required|date_format:H:i';
            $rules['shifts.*.jam_masuk'] = 'required|date_format:H:i';
            $rules['shifts.*.akhir_jam_masuk'] = 'required|date_format:H:i';
            $rules['shifts.*.jam_pulang'] = 'required|date_format:H:i';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            // Update master
            $jamkerja->update([
                'nama_jam_kerja' => $request->nama_jam_kerja,
                'tipe_jam_kerja' => $request->tipe_jam_kerja,
                'lintashari' => $request->lintashari,
                'total_shift' => $request->tipe_jam_kerja == 'multi_shift' ? $request->total_shift : 1,
                'awal_jam_masuk' => $request->awal_jam_masuk ?? '00:00:00',
                'jam_masuk' => $request->jam_masuk ?? '00:00:00',
                'akhir_jam_masuk' => $request->akhir_jam_masuk ?? '00:00:00',
                'jam_pulang' => $request->jam_pulang ?? '23:59:59',
            ]);

            // Delete old shifts
            JamKerjaShift::where('kode_jam_kerja', $kode_jam_kerja)->delete();

            // If multi_shift, create new shifts
            if ($request->tipe_jam_kerja == 'multi_shift' && $request->has('shifts')) {
                foreach ($request->shifts as $shift) {
                    JamKerjaShift::create([
                        'kode_jam_kerja' => $kode_jam_kerja,
                        'shift_ke' => $shift['shift_ke'],
                        'nama_shift' => $shift['nama_shift'],
                        'awal_jam_masuk' => $shift['awal_jam_masuk'],
                        'jam_masuk' => $shift['jam_masuk'],
                        'akhir_jam_masuk' => $shift['akhir_jam_masuk'],
                        'jam_pulang' => $shift['jam_pulang'],
                        'is_active' => true
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('panel.jamkerja.index')
                ->with(['success' => 'Data Jam Kerja berhasil diupdate']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Show jam kerja detail
     */
    public function show($kode_jam_kerja)
    {
        $jamkerja = JamKerja::with(['shifts', 'konfigurasiJkDeptDetail'])
            ->findOrFail($kode_jam_kerja);

        $total_konfigurasi_dept = $jamkerja->konfigurasiJkDeptDetail->count();

        return view('admin.jamkerja.show', compact('jamkerja', 'total_konfigurasi_dept'));
    }

    /**
     * Remove the specified jam kerja
     */
    public function destroy($kode_jam_kerja)
    {
        DB::beginTransaction();
        try {
            $jamkerja = JamKerja::findOrFail($kode_jam_kerja);

            // Check if jam kerja is being used
            $used_in_dept = $jamkerja->konfigurasiJkDeptDetail()->count();

            if ($used_in_dept > 0) {
                return redirect()->back()
                    ->with(['warning' => 'Jam Kerja tidak dapat dihapus karena sedang digunakan oleh ' . $used_in_dept . ' konfigurasi departemen']);
            }

            // Delete shifts first
            JamKerjaShift::where('kode_jam_kerja', $kode_jam_kerja)->delete();

            // Delete jam kerja
            $jamkerja->delete();

            DB::commit();

            return redirect()->route('panel.jamkerja.index')
                ->with(['success' => 'Data Jam Kerja berhasil dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}
