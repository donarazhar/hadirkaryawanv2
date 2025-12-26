<?php

namespace App\Http\Controllers\Admin;

use App\Exports\RekapPresensiFaceExport;
use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\Karyawan;
use App\Models\PresensiFace;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class PresensiFaceAdminController extends Controller
{
    /**
     * Display a listing of presensi face
     * Support: Regular & Multi-Shift
     */
    public function index(Request $request)
    {
        $query = PresensiFace::with([
            'karyawan' => function ($q) {
                $q->with(['cabang', 'departemen']);
            }
        ]);

        // Filter by tanggal
        if ($request->filled('tanggal_awal')) {
            $query->where('tanggal', '>=', $request->tanggal_awal);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->where('tanggal', '<=', $request->tanggal_akhir);
        }

        // Filter by cabang
        if ($request->filled('kode_cabang')) {
            $query->whereHas('karyawan', function ($q) use ($request) {
                $q->where('kode_cabang', $request->kode_cabang);
            });
        }

        // Filter by departemen
        if ($request->filled('kode_dept')) {
            $query->whereHas('karyawan', function ($q) use ($request) {
                $q->where('kode_dept', $request->kode_dept);
            });
        }

        // Filter by NIK
        if ($request->filled('nik')) {
            $query->where('nik', 'LIKE', '%' . $request->nik . '%');
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // ✅ FILTER BY SHIFT TYPE
        if ($request->filled('shift_type')) {
            if ($request->shift_type === 'multi') {
                $query->whereNotNull('shift_ke');
            } elseif ($request->shift_type === 'regular') {
                $query->whereNull('shift_ke');
            }
        }

        // ✅ FILTER BY SPECIFIC SHIFT
        if ($request->filled('shift_ke')) {
            $query->where('shift_ke', $request->shift_ke);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'tanggal');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        $query->orderBy('created_at', 'desc');

        // Paginate
        $perPage = $request->get('per_page', 20);
        $presensi = $query->paginate($perPage);

        // Get filter options
        $cabang = Cabang::all();
        $departemen = Departemen::all();

        // ✅ GET AVAILABLE SHIFTS (for filter)
        $availableShifts = DB::table('jam_kerja_shifts')
            ->where('is_active', true)
            ->orderBy('shift_ke')
            ->get();

        // ✅ NEW: Get all karyawan for export modal
        $karyawan = Karyawan::with(['departemen', 'cabang'])
            ->orderBy('nama_lengkap')
            ->get();

        return view('admin.presensi-face.index', compact(
            'presensi',
            'cabang',
            'departemen',
            'availableShifts',
            'karyawan'
        ));
    }

    /**
     * ✅ FIXED: Create form - Load karyawan with jam_kerja
     */
    public function create()
    {
        try {
            // Load all karyawan
            $karyawan = Karyawan::with(['departemen', 'cabang'])
                ->orderBy('nama_lengkap')
                ->get();

            // Attach jam_kerja info to each karyawan
            $karyawan = $karyawan->map(function ($k) {
                // Get jam kerja untuk hari ini
                $jam_kerja = $k->getJamKerjaHariIni();

                if ($jam_kerja) {
                    $k->jamKerja = (object)[
                        'kode_jam_kerja' => $jam_kerja->kode_jam_kerja,
                        'nama_jam_kerja' => $jam_kerja->nama_jam_kerja,
                        'tipe_jam_kerja' => $jam_kerja->tipe_jam_kerja,
                        'total_shift' => $jam_kerja->total_shift ?? 1,
                        'is_multi_shift' => ($jam_kerja->tipe_jam_kerja === 'multi_shift' && ($jam_kerja->total_shift ?? 0) >= 2),
                        'shifts' => []
                    ];

                    // Get shifts if multi-shift
                    if ($k->jamKerja->is_multi_shift) {
                        $shifts = DB::table('jam_kerja_shifts')
                            ->where('kode_jam_kerja', $jam_kerja->kode_jam_kerja)
                            ->where('is_active', true)
                            ->orderBy('shift_ke')
                            ->get();

                        $k->jamKerja->shifts = $shifts;

                        Log::info('Multi-shift loaded for karyawan', [
                            'nik' => $k->nik,
                            'nama' => $k->nama_lengkap,
                            'jam_kerja' => $jam_kerja->kode_jam_kerja,
                            'total_shifts' => $shifts->count()
                        ]);
                    }
                } else {
                    $k->jamKerja = null;

                    Log::warning('No jam_kerja found for karyawan', [
                        'nik' => $k->nik,
                        'nama' => $k->nama_lengkap
                    ]);
                }

                return $k;
            });

            Log::info('Create form loaded', [
                'total_karyawan' => $karyawan->count(),
                'multi_shift_karyawan' => $karyawan->filter(fn($k) => $k->jamKerja && $k->jamKerja->is_multi_shift)->count()
            ]);

            return view('admin.presensi-face.create', compact('karyawan'));
        } catch (Exception $e) {
            Log::error('Presensi Face Create Form Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('panel.presensi-face.index')
                ->with('error', 'Terjadi kesalahan saat memuat form: ' . $e->getMessage());
        }
    }

    /**
     * ✅ FIXED: Store - Handle both regular and multi-shift dengan validasi lengkap
     */
    public function store(Request $request)
    {
        Log::info('=== STORE REQUEST STARTED ===', [
            'all_data' => $request->all()
        ]);

        try {
            // Base validation rules
            $rules = [
                'nik' => 'required|exists:karyawan,nik',
                'tanggal' => 'required|date',
                'jam_masuk' => 'nullable|date_format:H:i',
                'jam_pulang' => 'nullable|date_format:H:i',
                'status' => 'required|in:verified,failed',
                'lokasi' => 'nullable|string'
            ];

            // ✅ CRITICAL FIX: Get karyawan and check jam_kerja for the SELECTED DATE
            $karyawan = Karyawan::find($request->nik);

            if (!$karyawan) {
                Log::error('Karyawan not found', ['nik' => $request->nik]);
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Karyawan tidak ditemukan.');
            }

            // ✅ Get jam_kerja based on the selected date (not today)
            $namaHari = Carbon::parse($request->tanggal)->locale('id')->isoFormat('dddd');
            $namaHariMap = [
                'Senin' => 'senin',
                'Selasa' => 'selasa',
                'Rabu' => 'rabu',
                'Kamis' => 'kamis',
                'Jumat' => 'jumat',
                'Sabtu' => 'sabtu',
                'Minggu' => 'minggu',
                'Ahad' => 'minggu'
            ];
            $namaHariLowercase = $namaHariMap[$namaHari] ?? strtolower($namaHari);

            Log::info('Getting jam_kerja for date', [
                'tanggal' => $request->tanggal,
                'hari' => $namaHari,
                'hari_lowercase' => $namaHariLowercase
            ]);

            // Get jam_kerja using the correct method
            $jam_kerja = DB::table('konfigurasi_jk_dept as kjd')
                ->join('konfigurasi_jk_dept_detail as kjdd', 'kjd.kode_jk_dept', '=', 'kjdd.kode_jk_dept')
                ->join('jam_kerja as jk', 'kjdd.kode_jam_kerja', '=', 'jk.kode_jam_kerja')
                ->where('kjd.kode_dept', $karyawan->kode_dept)
                ->where('kjd.kode_cabang', $karyawan->kode_cabang)
                ->where('kjdd.hari', $namaHariLowercase)
                ->select('jk.*')
                ->first();

            Log::info('Jam Kerja Check Result', [
                'jam_kerja_found' => $jam_kerja ? 'YES' : 'NO',
                'kode_jam_kerja' => $jam_kerja ? $jam_kerja->kode_jam_kerja : 'NULL',
                'tipe_jam_kerja' => $jam_kerja ? $jam_kerja->tipe_jam_kerja : 'NULL',
                'total_shift' => $jam_kerja ? $jam_kerja->total_shift : 'NULL'
            ]);

            // ✅ Add shift validation if multi-shift
            $isMultiShift = false;
            if ($jam_kerja && $jam_kerja->tipe_jam_kerja === 'multi_shift') {
                $rules['shift_ke'] = 'required|integer|min:1';
                $rules['nama_shift'] = 'required|string|max:50';
                $isMultiShift = true;

                Log::info('Multi-shift detected, shift validation added');
            } else {
                Log::info('Regular shift detected or no jam_kerja');
            }

            // Validate request
            $validated = $request->validate($rules);

            Log::info('Validation passed', [
                'validated_data' => $validated
            ]);

            DB::beginTransaction();

            // ✅ Prepare data
            $data = [
                'nik' => $validated['nik'],
                'tanggal' => $validated['tanggal'],
                'jam_masuk' => $validated['jam_masuk'] ?? null,
                'jam_pulang' => $validated['jam_pulang'] ?? null,
                'status' => $validated['status'],
                'lokasi' => $validated['lokasi'] ?? null,
                'shift_ke' => isset($validated['shift_ke']) ? $validated['shift_ke'] : null,
                'nama_shift' => isset($validated['nama_shift']) ? $validated['nama_shift'] : null,
            ];

            Log::info('Data prepared for insert', $data);

            // ✅ Check duplicate
            if ($data['shift_ke']) {
                // Check duplicate for multi-shift (same NIK, date, and shift)
                $exists = PresensiFace::where('nik', $data['nik'])
                    ->where('tanggal', $data['tanggal'])
                    ->where('shift_ke', $data['shift_ke'])
                    ->exists();

                if ($exists) {
                    DB::rollBack();
                    Log::warning('Duplicate multi-shift entry detected', $data);
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Data presensi untuk Shift ' . $data['shift_ke'] . ' (' . $data['nama_shift'] . ') sudah ada pada tanggal tersebut.');
                }
            } else {
                // Check duplicate for regular (same NIK and date, shift_ke is NULL)
                $exists = PresensiFace::where('nik', $data['nik'])
                    ->where('tanggal', $data['tanggal'])
                    ->whereNull('shift_ke')
                    ->exists();

                if ($exists) {
                    DB::rollBack();
                    Log::warning('Duplicate regular entry detected', $data);
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Data presensi regular sudah ada pada tanggal tersebut.');
                }
            }

            // ✅ Create presensi
            $presensi = PresensiFace::create($data);

            DB::commit();

            Log::info('=== PRESENSI CREATED SUCCESSFULLY ===', [
                'id' => $presensi->id,
                'nik' => $data['nik'],
                'tanggal' => $data['tanggal'],
                'shift_ke' => $data['shift_ke'] ?? 'regular',
                'nama_shift' => $data['nama_shift'] ?? '-'
            ]);

            return redirect()->route('panel.presensi-face.index')
                ->with('success', 'Data presensi berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', [
                'errors' => $e->errors()
            ]);
            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Store failed with exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $presensi = PresensiFace::with(['karyawan.departemen', 'karyawan.cabang'])
                ->findOrFail($id);

            // Get jam_kerja info for the presensi date
            $namaHari = $presensi->tanggal->locale('id')->isoFormat('dddd');
            $namaHariMap = [
                'Senin' => 'senin',
                'Selasa' => 'selasa',
                'Rabu' => 'rabu',
                'Kamis' => 'kamis',
                'Jumat' => 'jumat',
                'Sabtu' => 'sabtu',
                'Minggu' => 'minggu',
                'Ahad' => 'minggu'
            ];
            $namaHariLowercase = $namaHariMap[$namaHari] ?? strtolower($namaHari);

            $jam_kerja = DB::table('konfigurasi_jk_dept as kjd')
                ->join('konfigurasi_jk_dept_detail as kjdd', 'kjd.kode_jk_dept', '=', 'kjdd.kode_jk_dept')
                ->join('jam_kerja as jk', 'kjdd.kode_jam_kerja', '=', 'jk.kode_jam_kerja')
                ->where('kjd.kode_dept', $presensi->karyawan->kode_dept)
                ->where('kjd.kode_cabang', $presensi->karyawan->kode_cabang)
                ->where('kjdd.hari', $namaHariLowercase)
                ->select('jk.*')
                ->first();

            // Get shift detail if multi-shift
            $shift_detail = null;
            if ($presensi->shift_ke && $jam_kerja) {
                $shift_detail = DB::table('jam_kerja_shifts')
                    ->where('kode_jam_kerja', $jam_kerja->kode_jam_kerja)
                    ->where('shift_ke', $presensi->shift_ke)
                    ->first();
            }

            return view('admin.presensi-face.show', compact('presensi', 'jam_kerja', 'shift_detail'));
        } catch (Exception $e) {
            Log::error('Presensi Face Show Error: ' . $e->getMessage());
            return redirect()->route('panel.presensi-face.index')
                ->with('error', 'Data tidak ditemukan.');
        }
    }

    /**
     * ✅ FIXED: Edit form - Load shift info correctly
     */
    public function edit(string $id)
    {
        try {
            $presensi = PresensiFace::with(['karyawan.departemen', 'karyawan.cabang'])
                ->findOrFail($id);

            Log::info('=== EDIT FORM LOADING ===', [
                'id' => $id,
                'nik' => $presensi->nik,
                'tanggal' => $presensi->tanggal,
                'shift_ke' => $presensi->shift_ke,
                'nama_shift' => $presensi->nama_shift
            ]);

            // ✅ Get jam_kerja for the presensi date
            $namaHari = $presensi->tanggal->locale('id')->isoFormat('dddd');
            $namaHariMap = [
                'Senin' => 'senin',
                'Selasa' => 'selasa',
                'Rabu' => 'rabu',
                'Kamis' => 'kamis',
                'Jumat' => 'jumat',
                'Sabtu' => 'sabtu',
                'Minggu' => 'minggu',
                'Ahad' => 'minggu'
            ];
            $namaHariLowercase = $namaHariMap[$namaHari] ?? strtolower($namaHari);

            $jam_kerja = DB::table('konfigurasi_jk_dept as kjd')
                ->join('konfigurasi_jk_dept_detail as kjdd', 'kjd.kode_jk_dept', '=', 'kjdd.kode_jk_dept')
                ->join('jam_kerja as jk', 'kjdd.kode_jam_kerja', '=', 'jk.kode_jam_kerja')
                ->where('kjd.kode_dept', $presensi->karyawan->kode_dept)
                ->where('kjd.kode_cabang', $presensi->karyawan->kode_cabang)
                ->where('kjdd.hari', $namaHariLowercase)
                ->select('jk.*')
                ->first();

            Log::info('Jam Kerja for Edit', [
                'jam_kerja_found' => $jam_kerja ? 'YES' : 'NO',
                'kode_jam_kerja' => $jam_kerja ? $jam_kerja->kode_jam_kerja : 'NULL',
                'tipe_jam_kerja' => $jam_kerja ? $jam_kerja->tipe_jam_kerja : 'NULL'
            ]);

            // ✅ Attach jam_kerja dengan shifts
            if ($jam_kerja && $jam_kerja->tipe_jam_kerja === 'multi_shift') {
                $shifts = DB::table('jam_kerja_shifts')
                    ->where('kode_jam_kerja', $jam_kerja->kode_jam_kerja)
                    ->where('is_active', true)
                    ->orderBy('shift_ke')
                    ->get();

                $presensi->karyawan->jamKerja = (object)[
                    'kode_jam_kerja' => $jam_kerja->kode_jam_kerja,
                    'nama_jam_kerja' => $jam_kerja->nama_jam_kerja,
                    'tipe_jam_kerja' => $jam_kerja->tipe_jam_kerja,
                    'total_shift' => $jam_kerja->total_shift ?? $shifts->count(),
                    'shifts' => $shifts
                ];

                Log::info('Multi-Shift Data Attached for Edit', [
                    'total_shifts' => $shifts->count(),
                    'shifts' => $shifts->pluck('nama_shift')->toArray()
                ]);
            } else {
                $presensi->karyawan->jamKerja = null;
                Log::info('Regular shift or no jam_kerja for edit');
            }

            return view('admin.presensi-face.edit', compact('presensi'));
        } catch (Exception $e) {
            Log::error('Presensi Face Edit Form Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('panel.presensi-face.index')
                ->with('error', 'Data tidak ditemukan: ' . $e->getMessage());
        }
    }

    /**
     * ✅ FIXED: Update - Handle both regular and multi-shift dengan validasi lengkap
     */
    public function update(Request $request, string $id)
    {
        Log::info('=== UPDATE REQUEST STARTED ===', [
            'id' => $id,
            'all_data' => $request->all()
        ]);

        try {
            $presensi = PresensiFace::findOrFail($id);

            // Base validation rules
            $rules = [
                'tanggal' => 'required|date',
                'jam_masuk' => 'nullable|date_format:H:i',
                'jam_pulang' => 'nullable|date_format:H:i',
                'status' => 'required|in:verified,failed',
                'lokasi' => 'nullable|string'
            ];

            // ✅ Get karyawan and check jam_kerja for the NEW DATE
            $karyawan = Karyawan::find($presensi->nik);

            if (!$karyawan) {
                Log::error('Karyawan not found for update', ['nik' => $presensi->nik]);
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Karyawan tidak ditemukan.');
            }

            // ✅ Get jam_kerja based on the selected date
            $namaHari = Carbon::parse($request->tanggal)->locale('id')->isoFormat('dddd');
            $namaHariMap = [
                'Senin' => 'senin',
                'Selasa' => 'selasa',
                'Rabu' => 'rabu',
                'Kamis' => 'kamis',
                'Jumat' => 'jumat',
                'Sabtu' => 'sabtu',
                'Minggu' => 'minggu',
                'Ahad' => 'minggu'
            ];
            $namaHariLowercase = $namaHariMap[$namaHari] ?? strtolower($namaHari);

            $jam_kerja = DB::table('konfigurasi_jk_dept as kjd')
                ->join('konfigurasi_jk_dept_detail as kjdd', 'kjd.kode_jk_dept', '=', 'kjdd.kode_jk_dept')
                ->join('jam_kerja as jk', 'kjdd.kode_jam_kerja', '=', 'jk.kode_jam_kerja')
                ->where('kjd.kode_dept', $karyawan->kode_dept)
                ->where('kjd.kode_cabang', $karyawan->kode_cabang)
                ->where('kjdd.hari', $namaHariLowercase)
                ->select('jk.*')
                ->first();

            Log::info('Jam Kerja Check for Update', [
                'new_tanggal' => $request->tanggal,
                'hari' => $namaHari,
                'jam_kerja_found' => $jam_kerja ? 'YES' : 'NO',
                'tipe' => $jam_kerja ? $jam_kerja->tipe_jam_kerja : 'NULL'
            ]);

            // ✅ Add shift validation if multi-shift
            if ($jam_kerja && $jam_kerja->tipe_jam_kerja === 'multi_shift') {
                $rules['shift_ke'] = 'required|integer|min:1';
                $rules['nama_shift'] = 'required|string|max:50';
                Log::info('Multi-shift validation added for update');
            }

            $validated = $request->validate($rules);

            Log::info('Validation passed for update', [
                'validated_data' => $validated
            ]);

            DB::beginTransaction();

            // ✅ Check duplicate on update (if date or shift changed)
            $dateChanged = $request->tanggal != $presensi->tanggal->format('Y-m-d');
            $shiftChanged = ($request->shift_ke ?? null) != $presensi->shift_ke;

            if ($dateChanged || $shiftChanged) {
                Log::info('Checking duplicate (date or shift changed)', [
                    'date_changed' => $dateChanged,
                    'shift_changed' => $shiftChanged,
                    'old_tanggal' => $presensi->tanggal->format('Y-m-d'),
                    'new_tanggal' => $request->tanggal,
                    'old_shift_ke' => $presensi->shift_ke,
                    'new_shift_ke' => $request->shift_ke ?? null
                ]);

                if (isset($validated['shift_ke'])) {
                    $exists = PresensiFace::where('nik', $presensi->nik)
                        ->where('tanggal', $validated['tanggal'])
                        ->where('shift_ke', $validated['shift_ke'])
                        ->where('id', '!=', $id)
                        ->exists();

                    if ($exists) {
                        DB::rollBack();
                        Log::warning('Duplicate multi-shift entry on update', [
                            'nik' => $presensi->nik,
                            'tanggal' => $validated['tanggal'],
                            'shift_ke' => $validated['shift_ke']
                        ]);
                        return redirect()->back()
                            ->withInput()
                            ->with('error', 'Data presensi untuk Shift ' . $validated['shift_ke'] . ' sudah ada pada tanggal tersebut.');
                    }
                } else {
                    $exists = PresensiFace::where('nik', $presensi->nik)
                        ->where('tanggal', $validated['tanggal'])
                        ->whereNull('shift_ke')
                        ->where('id', '!=', $id)
                        ->exists();

                    if ($exists) {
                        DB::rollBack();
                        Log::warning('Duplicate regular entry on update', [
                            'nik' => $presensi->nik,
                            'tanggal' => $validated['tanggal']
                        ]);
                        return redirect()->back()
                            ->withInput()
                            ->with('error', 'Data presensi regular sudah ada pada tanggal tersebut.');
                    }
                }
            }

            // ✅ Update data
            $updateData = [
                'tanggal' => $validated['tanggal'],
                'jam_masuk' => $validated['jam_masuk'] ?? $presensi->jam_masuk,
                'jam_pulang' => $validated['jam_pulang'] ?? $presensi->jam_pulang,
                'status' => $validated['status'],
                'lokasi' => $validated['lokasi'] ?? $presensi->lokasi,
                'shift_ke' => isset($validated['shift_ke']) ? $validated['shift_ke'] : null,
                'nama_shift' => isset($validated['nama_shift']) ? $validated['nama_shift'] : null,
            ];

            $presensi->update($updateData);

            DB::commit();

            Log::info('=== PRESENSI UPDATED SUCCESSFULLY ===', [
                'id' => $id,
                'nik' => $presensi->nik,
                'tanggal' => $updateData['tanggal'],
                'shift_ke' => $updateData['shift_ke'] ?? 'regular',
                'nama_shift' => $updateData['nama_shift'] ?? '-'
            ]);

            return redirect()->route('panel.presensi-face.index')
                ->with('success', 'Data presensi berhasil diupdate.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed on update', [
                'errors' => $e->errors()
            ]);
            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Update failed with exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified presensi
     */
    public function destroy($id)
    {
        try {
            $presensi = PresensiFace::findOrFail($id);

            Log::info('Deleting presensi', [
                'id' => $id,
                'nik' => $presensi->nik,
                'tanggal' => $presensi->tanggal
            ]);

            // Delete foto if exists
            if ($presensi->foto && Storage::disk('public')->exists($presensi->foto)) {
                Storage::disk('public')->delete($presensi->foto);
                Log::info('Foto deleted', ['path' => $presensi->foto]);
            }

            $presensi->delete();

            Log::info('Presensi deleted successfully', ['id' => $id]);

            return redirect()->route('panel.presensi-face.index')
                ->with('success', 'Data presensi berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Delete failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Real-time monitoring with COMPLETE FILTERS
     * Support: Multi-shift display + All filters from index
     */
    public function monitoring(Request $request)
    {
        $tanggal = $request->get('tanggal', date('Y-m-d'));

        // Build query with filters
        $query = PresensiFace::with([
            'karyawan' => function ($q) {
                $q->with(['cabang', 'departemen', 'jamKerja.shifts']);
            }
        ])
            ->whereDate('tanggal', $tanggal);

        // Filter by NIK
        if ($request->filled('nik')) {
            $query->where('nik', 'LIKE', '%' . $request->nik . '%');
        }

        // Filter by cabang
        if ($request->filled('kode_cabang')) {
            $query->whereHas('karyawan', function ($q) use ($request) {
                $q->where('kode_cabang', $request->kode_cabang);
            });
        }

        // Filter by departemen
        if ($request->filled('kode_dept')) {
            $query->whereHas('karyawan', function ($q) use ($request) {
                $q->where('kode_dept', $request->kode_dept);
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by shift
        if ($request->filled('shift_ke')) {
            $query->byShift($request->shift_ke);
        }

        // Get filtered data
        $presensi = $query->orderBy('created_at', 'desc')->get();

        // Calculate stats from filtered data
        $stats = [
            'total' => $presensi->count(),
            'verified' => $presensi->where('status', 'verified')->count(),
            'failed' => $presensi->where('status', 'failed')->count(),
            'multi_shift' => $presensi->whereNotNull('shift_ke')->count(),
            'regular' => $presensi->whereNull('shift_ke')->count(),
            'by_shift' => []
        ];

        // Count by shift
        if ($stats['multi_shift'] > 0) {
            $stats['by_shift'] = $presensi->whereNotNull('shift_ke')
                ->groupBy('shift_ke')
                ->map(function ($group) {
                    return [
                        'count' => $group->count(),
                        'nama' => $group->first()->nama_shift
                    ];
                });
        }

        // Get filter options
        $cabang = \App\Models\Cabang::all();
        $departemen = \App\Models\Departemen::all();
        $availableShifts = DB::table('jam_kerja_shifts')
            ->where('is_active', true)
            ->orderBy('shift_ke')
            ->get();

        // Return AJAX table update
        if ($request->ajax()) {
            return view('admin.presensi-face.monitoring-table', compact('presensi', 'stats'));
        }

        // Return full page
        return view('admin.presensi-face.monitoring', compact(
            'presensi',
            'stats',
            'tanggal',
            'cabang',
            'departemen',
            'availableShifts'
        ));
    }

    public function rekap(Request $request)
    {
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));

        $namabulan = [
            '',
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];

        // ✅ BUILD QUERY WITH FILTERS
        $query = DB::table('presensi_face as pf')
            ->join('karyawan as k', 'pf.nik', '=', 'k.nik')
            ->leftJoin('departemen as d', 'k.kode_dept', '=', 'd.kode_dept')
            ->leftJoin('cabang as c', 'k.kode_cabang', '=', 'c.kode_cabang')
            ->select(
                'k.nik',
                'k.nama_lengkap',
                'k.jabatan',
                'd.nama_dept',
                'c.nama_cabang',
                'k.kode_cabang', // ✅ ADD for filtering
                'k.kode_dept',   // ✅ ADD for filtering
                DB::raw('COUNT(CASE WHEN pf.shift_ke IS NULL THEN 1 END) as total_hadir_regular'),
                DB::raw('COUNT(CASE WHEN pf.shift_ke IS NOT NULL THEN 1 END) as total_hadir_multi'),
                DB::raw('COUNT(*) as total_hadir'),
                DB::raw('SUM(CASE WHEN pf.status = "verified" THEN 1 ELSE 0 END) as total_verified'),
                DB::raw('SUM(CASE WHEN pf.status = "failed" THEN 1 ELSE 0 END) as total_failed')
            )
            ->whereYear('pf.tanggal', $tahun)
            ->whereMonth('pf.tanggal', $bulan);

        // ✅ APPLY FILTERS
        if ($request->filled('kode_cabang')) {
            $query->where('k.kode_cabang', $request->kode_cabang);
        }

        if ($request->filled('kode_dept')) {
            $query->where('k.kode_dept', $request->kode_dept);
        }

        // ✅ GET FILTERED DATA
        $rekap = $query
            ->groupBy('k.nik', 'k.nama_lengkap', 'k.jabatan', 'd.nama_dept', 'c.nama_cabang', 'k.kode_cabang', 'k.kode_dept')
            ->orderBy('k.nama_lengkap')
            ->get();

        // ✅ GET FILTER OPTIONS
        $cabang = \App\Models\Cabang::all();
        $departemen = \App\Models\Departemen::all();

        return view('admin.presensi-face.rekap', compact(
            'rekap',
            'bulan',
            'tahun',
            'namabulan',
            'cabang',      // ✅ ADD
            'departemen'   // ✅ ADD
        ));
    }

    public function exportRekap(Request $request)
    {
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));

        $namabulan = [
            '',
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];

        // ✅ BUILD QUERY WITH FILTERS
        $query = DB::table('presensi_face as pf')
            ->join('karyawan as k', 'pf.nik', '=', 'k.nik')
            ->leftJoin('departemen as d', 'k.kode_dept', '=', 'd.kode_dept')
            ->leftJoin('cabang as c', 'k.kode_cabang', '=', 'c.kode_cabang')
            ->select(
                'k.nik',
                'k.nama_lengkap',
                'k.jabatan',
                'd.nama_dept',
                'c.nama_cabang',
                DB::raw('COUNT(CASE WHEN pf.shift_ke IS NULL THEN 1 END) as total_hadir_regular'),
                DB::raw('COUNT(CASE WHEN pf.shift_ke IS NOT NULL THEN 1 END) as total_hadir_multi'),
                DB::raw('COUNT(*) as total_hadir'),
                DB::raw('SUM(CASE WHEN pf.status = "verified" THEN 1 ELSE 0 END) as total_verified'),
                DB::raw('SUM(CASE WHEN pf.status = "failed" THEN 1 ELSE 0 END) as total_failed')
            )
            ->whereYear('pf.tanggal', $tahun)
            ->whereMonth('pf.tanggal', $bulan);

        // ✅ APPLY FILTERS (same as rekap)
        if ($request->filled('kode_cabang')) {
            $query->where('k.kode_cabang', $request->kode_cabang);
        }

        if ($request->filled('kode_dept')) {
            $query->where('k.kode_dept', $request->kode_dept);
        }

        // ✅ GET FILTERED DATA
        $rekap = $query
            ->groupBy('k.nik', 'k.nama_lengkap', 'k.jabatan', 'd.nama_dept', 'c.nama_cabang')
            ->orderBy('k.nama_lengkap')
            ->get();

        // ✅ FILENAME WITH FILTER INFO
        $filename = 'Rekap_Presensi_Face_' . $namabulan[$bulan] . '_' . $tahun;

        if ($request->filled('kode_cabang')) {
            $filename .= '_' . $request->kode_cabang;
        }

        if ($request->filled('kode_dept')) {
            $filename .= '_' . $request->kode_dept;
        }

        $filename .= '.xlsx';

        // Export (Excel or CSV)
        return Excel::download(new RekapPresensiFaceExport($rekap, $bulan, $tahun), $filename);
    }

    /**
     * ✅ UPDATED: Export data presensi dengan detail per karyawan
     * Support: Multi-shift columns, Jam Kerja Configuration
     */
    public function exportData(Request $request)
    {
        try {
            Log::info('Export Data Started', $request->all());

            // ✅ Prepare filters
            $filters = [
                'tanggal_awal' => $request->filled('tanggal_awal') ? $request->tanggal_awal : null,
                'tanggal_akhir' => $request->filled('tanggal_akhir') ? $request->tanggal_akhir : null,
                'kode_cabang' => $request->filled('kode_cabang') ? $request->kode_cabang : null,
                'kode_dept' => $request->filled('kode_dept') ? $request->kode_dept : null,
                'nik_list' => $request->filled('nik_list') ? $request->nik_list : null, // ← NEW
                'nik' => $request->filled('nik') ? $request->nik : null,
                'status' => $request->filled('status') ? $request->status : null,
                'shift_type' => $request->filled('shift_type') ? $request->shift_type : null,
                'shift_ke' => $request->filled('shift_ke') ? $request->shift_ke : null,
            ];

            // ✅ Build query for statistics
            $query = PresensiFace::with([
                'karyawan' => function ($q) {
                    $q->with(['cabang', 'departemen']);
                }
            ]);

            // Apply filters to query
            if ($filters['tanggal_awal']) {
                $query->where('tanggal', '>=', $filters['tanggal_awal']);
            }

            if ($filters['tanggal_akhir']) {
                $query->where('tanggal', '<=', $filters['tanggal_akhir']);
            }

            if ($filters['kode_cabang']) {
                $query->whereHas('karyawan', function ($q) use ($filters) {
                    $q->where('kode_cabang', $filters['kode_cabang']);
                });
            }

            if ($filters['kode_dept']) {
                $query->whereHas('karyawan', function ($q) use ($filters) {
                    $q->where('kode_dept', $filters['kode_dept']);
                });
            }

            if ($filters['nik']) {
                $query->where('nik', 'LIKE', '%' . $filters['nik'] . '%');
            }

            if ($filters['status']) {
                $query->where('status', $filters['status']);
            }

            if ($filters['shift_type']) {
                if ($filters['shift_type'] === 'multi') {
                    $query->whereNotNull('shift_ke');
                } elseif ($filters['shift_type'] === 'regular') {
                    $query->whereNull('shift_ke');
                }
            }

            if ($filters['shift_ke']) {
                $query->where('shift_ke', $filters['shift_ke']);
            }

            $presensi = $query->orderBy('tanggal', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();

            // ✅ Calculate statistics
            $stats = [
                'total_data' => $presensi->count(),
                'total_hadir' => $presensi->whereNotNull('jam_masuk')->count(),
                'total_pulang' => $presensi->whereNotNull('jam_pulang')->count(),
                'total_verified' => $presensi->where('status', 'verified')->count(),
                'total_failed' => $presensi->where('status', 'failed')->count(),
                'total_multi_shift' => $presensi->whereNotNull('shift_ke')->count(),
                'total_regular' => $presensi->whereNull('shift_ke')->count(),
            ];

            Log::info('Export Statistics', $stats);

            // ✅ Determine export format
            $format = $request->get('format', 'excel');

            if ($format === 'excel') {
                // ✅ EXCEL EXPORT - Detailed with multiple sheets per employee
                $filename = $this->generateFilename($filters, 'xlsx');

                Log::info('Generating Excel Export', [
                    'filename' => $filename,
                    'filters' => $filters
                ]);

                return Excel::download(
                    new \App\Exports\PresensiFaceDetailedExport($filters),
                    $filename
                );
            } else {
                // ✅ PDF EXPORT - Summary view
                $filename = $this->generateFilename($filters, 'pdf');

                Log::info('Generating PDF Export', [
                    'filename' => $filename
                ]);

                $pdf = PDF::loadView('admin.presensi-face.export-data', compact(
                    'presensi',
                    'stats',
                    'request'
                ));

                $pdf->setPaper('A4', 'landscape');

                return $pdf->download($filename);
            }
        } catch (Exception $e) {
            Log::error('Export Data Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Gagal export data: ' . $e->getMessage());
        }
    }

    /**
     * ✅ Generate filename berdasarkan filter
     */
    private function generateFilename($filters, $extension)
    {
        $filename = 'Presensi_Face';

        if ($filters['kode_cabang']) {
            $cabang = \App\Models\Cabang::find($filters['kode_cabang']);
            if ($cabang) {
                $filename .= '_' . str_replace(' ', '_', $cabang->nama_cabang);
            }
        }

        if ($filters['kode_dept']) {
            $dept = \App\Models\Departemen::where('kode_dept', $filters['kode_dept'])->first();
            if ($dept) {
                $filename .= '_' . str_replace(' ', '_', $dept->nama_dept);
            }
        }

        if ($filters['tanggal_awal'] && $filters['tanggal_akhir']) {
            $filename .= '_' . date('Ymd', strtotime($filters['tanggal_awal']));
            $filename .= '-' . date('Ymd', strtotime($filters['tanggal_akhir']));
        }

        $filename .= '_' . date('YmdHis') . '.' . $extension;

        return $filename;
    }

    /**
     * Get dashboard statistics
     * Support: Multi-shift stats
     */
    public function getDashboardStats(Request $request)
    {
        $today = Carbon::today();

        // ✅ TODAY STATS WITH MULTI-SHIFT BREAKDOWN
        $todayStats = [
            'total' => PresensiFace::whereDate('tanggal', $today)->count(),
            'verified' => PresensiFace::whereDate('tanggal', $today)->verified()->count(),
            'failed' => PresensiFace::whereDate('tanggal', $today)->failed()->count(),
            'multi_shift' => PresensiFace::whereDate('tanggal', $today)->multiShift()->count(),
            'regular' => PresensiFace::whereDate('tanggal', $today)->regularShift()->count(),
        ];

        // ✅ THIS MONTH STATS
        $thisMonth = [
            'total' => PresensiFace::whereMonth('tanggal', $today->month)
                ->whereYear('tanggal', $today->year)
                ->count(),
            'verified' => PresensiFace::whereMonth('tanggal', $today->month)
                ->whereYear('tanggal', $today->year)
                ->verified()
                ->count(),
            'multi_shift' => PresensiFace::whereMonth('tanggal', $today->month)
                ->whereYear('tanggal', $today->year)
                ->multiShift()
                ->count(),
        ];

        // ✅ BY SHIFT BREAKDOWN (for multi-shift only)
        $byShift = [];
        if ($todayStats['multi_shift'] > 0) {
            $byShift = DB::table('presensi_face')
                ->select('shift_ke', 'nama_shift', DB::raw('COUNT(*) as count'))
                ->whereDate('tanggal', $today)
                ->whereNotNull('shift_ke')
                ->groupBy('shift_ke', 'nama_shift')
                ->orderBy('shift_ke')
                ->get()
                ->toArray();
        }

        // ✅ RECENT ACTIVITY (last 10)
        $recentActivity = PresensiFace::with([
            'karyawan' => function ($q) {
                $q->with(['cabang', 'departemen']);
            }
        ])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'nik' => $p->nik,
                    'nama' => $p->karyawan->nama_lengkap ?? 'N/A',
                    'tanggal' => $p->tanggal_formatted,
                    'shift_info' => $p->shift_info,
                    'is_multi_shift' => $p->isMultiShift(),
                    'jam_masuk' => $p->jam_masuk_formatted,
                    'jam_pulang' => $p->jam_pulang_formatted,
                    'status' => $p->status,
                    'created_at' => $p->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'today' => $todayStats,
            'this_month' => $thisMonth,
            'by_shift' => $byShift,
            'recent_activity' => $recentActivity,
        ]);
    }

    /**
     * Get statistics for specific date range
     * Support: Multi-shift analysis
     */
    public function getStats(Request $request)
    {
        $start = $request->get('start', Carbon::today()->startOfMonth());
        $end = $request->get('end', Carbon::today()->endOfMonth());

        // ✅ COMPREHENSIVE STATS
        $stats = [
            'total_records' => PresensiFace::dateRange($start, $end)->count(),
            'verified' => PresensiFace::dateRange($start, $end)->verified()->count(),
            'failed' => PresensiFace::dateRange($start, $end)->failed()->count(),

            // Shift type breakdown
            'multi_shift_total' => PresensiFace::dateRange($start, $end)->multiShift()->count(),
            'regular_total' => PresensiFace::dateRange($start, $end)->regularShift()->count(),

            // Unique employees
            'unique_employees' => PresensiFace::dateRange($start, $end)
                ->distinct('nik')
                ->count('nik'),

            // Daily averages
            'daily_average' => 0,

            // By shift detailed (for multi-shift)
            'by_shift_detail' => [],
        ];

        // Calculate daily average
        $days = Carbon::parse($start)->diffInDays(Carbon::parse($end)) + 1;
        $stats['daily_average'] = $days > 0 ? round($stats['total_records'] / $days, 2) : 0;

        // ✅ DETAILED SHIFT BREAKDOWN
        if ($stats['multi_shift_total'] > 0) {
            $stats['by_shift_detail'] = DB::table('presensi_face')
                ->select(
                    'shift_ke',
                    'nama_shift',
                    DB::raw('COUNT(*) as total'),
                    DB::raw('SUM(CASE WHEN status = "verified" THEN 1 ELSE 0 END) as verified'),
                    DB::raw('SUM(CASE WHEN status = "failed" THEN 1 ELSE 0 END) as failed')
                )
                ->whereBetween('tanggal', [$start, $end])
                ->whereNotNull('shift_ke')
                ->groupBy('shift_ke', 'nama_shift')
                ->orderBy('shift_ke')
                ->get()
                ->toArray();
        }

        return response()->json($stats);
    }
}
