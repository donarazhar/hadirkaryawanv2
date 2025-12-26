<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PresensiFace;
use App\Models\Karyawan;
use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\JamKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PresensiFaceExport;
use App\Exports\RekapPresensiFaceExport;

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
                $query->multiShift();
            } elseif ($request->shift_type === 'regular') {
                $query->regularShift();
            }
        }

        // ✅ FILTER BY SPECIFIC SHIFT
        if ($request->filled('shift_ke')) {
            $query->byShift($request->shift_ke);
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

        return view('admin.presensi-face.index', compact(
            'presensi',
            'cabang',
            'departemen',
            'availableShifts'
        ));
    }

    /**
     * Show the form for creating new presensi
     * Support: Shift selection for multi-shift
     */
    public function create()
    {
        $karyawan = Karyawan::with(['cabang', 'departemen', 'jamKerja.shifts'])
            ->orderBy('nama_lengkap')
            ->get();

        return view('admin.presensi-face.create', compact('karyawan'));
    }

    /**
     * Store a newly created presensi
     * Support: Multi-shift validation
     */
    public function store(Request $request)
    {
        // Validation
        $rules = [
            'nik' => 'required|exists:karyawan,nik',
            'tanggal' => 'required|date',
            'jam_masuk' => 'nullable|date_format:H:i',
            'jam_pulang' => 'nullable|date_format:H:i',
            'lokasi' => 'nullable|string',
            'status' => 'required|in:verified,failed',
            'similarity_score' => 'nullable|numeric|min:0|max:1',
            'foto' => 'nullable|image|max:2048',
            'keterangan' => 'nullable|string',
        ];

        // ✅ MULTI-SHIFT VALIDATION
        $karyawan = Karyawan::with('jamKerja')->find($request->nik);

        if ($karyawan && $karyawan->jamKerja && $karyawan->jamKerja->isMultiShift()) {
            // Multi-shift: shift_ke & nama_shift wajib
            $rules['shift_ke'] = 'required|integer|min:1|max:' . $karyawan->jamKerja->total_shift;
            $rules['nama_shift'] = 'required|string|max:50';

            // Validate shift uniqueness (NIK + Tanggal + Shift)
            $rules['shift_ke'] = [
                'required',
                'integer',
                function ($attribute, $value, $fail) use ($request) {
                    $exists = PresensiFace::where('nik', $request->nik)
                        ->where('tanggal', $request->tanggal)
                        ->where('shift_ke', $value)
                        ->exists();

                    if ($exists) {
                        $fail('Presensi untuk shift ini sudah ada pada tanggal tersebut.');
                    }
                },
            ];
        } else {
            // Regular: shift nullable
            $rules['shift_ke'] = 'nullable';
            $rules['nama_shift'] = 'nullable';

            // Validate uniqueness (NIK + Tanggal only)
            $exists = PresensiFace::where('nik', $request->nik)
                ->where('tanggal', $request->tanggal)
                ->whereNull('shift_ke')
                ->exists();

            if ($exists) {
                return back()->withErrors(['tanggal' => 'Presensi regular sudah ada pada tanggal tersebut.'])
                    ->withInput();
            }
        }

        $validated = $request->validate($rules);

        try {
            DB::beginTransaction();

            // Handle foto upload
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('uploads/presensi-face', 'public');
                $validated['foto'] = $fotoPath;
            }

            // Create presensi
            PresensiFace::create($validated);

            DB::commit();

            return redirect()->route('panel.presensi-face.index')
                ->with('success', 'Data presensi berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified presensi
     * Support: Multi-shift info
     */
    public function show($id)
    {
        $presensi = PresensiFace::with([
            'karyawan' => function ($q) {
                $q->with(['cabang', 'departemen', 'jamKerja.shifts']);
            }
        ])->findOrFail($id);

        // ✅ Get shift detail if multi-shift
        $shiftDetail = null;
        if ($presensi->isMultiShift() && $presensi->karyawan->jamKerja) {
            $shiftDetail = $presensi->karyawan->jamKerja->shifts()
                ->where('shift_ke', $presensi->shift_ke)
                ->first();
        }

        return view('admin.presensi-face.show', compact('presensi', 'shiftDetail'));
    }

    /**
     * Show the form for editing
     * Support: Shift editing
     */
    public function edit($id)
    {
        $presensi = PresensiFace::with([
            'karyawan' => function ($q) {
                $q->with(['cabang', 'departemen', 'jamKerja.shifts']);
            }
        ])->findOrFail($id);

        return view('admin.presensi-face.edit', compact('presensi'));
    }

    /**
     * Update the specified presensi
     * Support: Multi-shift update
     */
    public function update(Request $request, $id)
    {
        $presensi = PresensiFace::findOrFail($id);

        // Validation
        $rules = [
            'tanggal' => 'required|date',
            'jam_masuk' => 'nullable|date_format:H:i',
            'jam_pulang' => 'nullable|date_format:H:i',
            'lokasi' => 'nullable|string',
            'status' => 'required|in:verified,failed',
            'similarity_score' => 'nullable|numeric|min:0|max:1',
            'foto' => 'nullable|image|max:2048',
            'keterangan' => 'nullable|string',
        ];

        // ✅ MULTI-SHIFT UPDATE VALIDATION
        $karyawan = Karyawan::with('jamKerja')->find($presensi->nik);

        if ($karyawan && $karyawan->jamKerja && $karyawan->jamKerja->isMultiShift()) {
            $rules['shift_ke'] = [
                'required',
                'integer',
                function ($attribute, $value, $fail) use ($request, $presensi) {
                    // Check uniqueness excluding current record
                    $exists = PresensiFace::where('nik', $presensi->nik)
                        ->where('tanggal', $request->tanggal)
                        ->where('shift_ke', $value)
                        ->where('id', '!=', $presensi->id)
                        ->exists();

                    if ($exists) {
                        $fail('Presensi untuk shift ini sudah ada pada tanggal tersebut.');
                    }
                },
            ];
            $rules['nama_shift'] = 'required|string|max:50';
        }

        $validated = $request->validate($rules);

        try {
            DB::beginTransaction();

            // Handle foto upload
            if ($request->hasFile('foto')) {
                // Delete old foto
                if ($presensi->foto && Storage::disk('public')->exists($presensi->foto)) {
                    Storage::disk('public')->delete($presensi->foto);
                }

                $fotoPath = $request->file('foto')->store('uploads/presensi-face', 'public');
                $validated['foto'] = $fotoPath;
            }

            // Update presensi
            $presensi->update($validated);

            DB::commit();

            return redirect()->route('panel.presensi-face.index')
                ->with('success', 'Data presensi berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal update data: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified presensi
     */
    public function destroy($id)
    {
        try {
            $presensi = PresensiFace::findOrFail($id);

            // Delete foto if exists
            if ($presensi->foto && Storage::disk('public')->exists($presensi->foto)) {
                Storage::disk('public')->delete($presensi->foto);
            }

            $presensi->delete();

            return redirect()->route('panel.presensi-face.index')
                ->with('success', 'Data presensi berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menghapus data: ' . $e->getMessage()]);
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
        $availableShifts = \DB::table('jam_kerja_shifts')
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
        '', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
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
     * Export data presensi to PDF/Excel
     * Support: Multi-shift columns in export
     */
    public function exportData(Request $request)
    {
        $query = PresensiFace::with([
            'karyawan' => function ($q) {
                $q->with(['cabang', 'departemen']);
            }
        ]);

        // Apply all filters
        if ($request->filled('tanggal_awal')) {
            $query->where('tanggal', '>=', $request->tanggal_awal);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->where('tanggal', '<=', $request->tanggal_akhir);
        }

        if ($request->filled('kode_cabang')) {
            $query->whereHas('karyawan', function ($q) use ($request) {
                $q->where('kode_cabang', $request->kode_cabang);
            });
        }

        if ($request->filled('kode_dept')) {
            $query->whereHas('karyawan', function ($q) use ($request) {
                $q->where('kode_dept', $request->kode_dept);
            });
        }

        if ($request->filled('nik')) {
            $query->where('nik', 'LIKE', '%' . $request->nik . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // ✅ FILTER BY SHIFT
        if ($request->filled('shift_type')) {
            if ($request->shift_type === 'multi') {
                $query->multiShift();
            } elseif ($request->shift_type === 'regular') {
                $query->regularShift();
            }
        }

        if ($request->filled('shift_ke')) {
            $query->byShift($request->shift_ke);
        }

        $presensi = $query->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // ✅ CALCULATE STATS INCLUDING MULTI-SHIFT
        $stats = [
            'total_data' => $presensi->count(),
            'total_hadir' => $presensi->whereNotNull('jam_masuk')->count(),
            'total_pulang' => $presensi->whereNotNull('jam_pulang')->count(),
            'total_verified' => $presensi->where('status', 'verified')->count(),
            'total_failed' => $presensi->where('status', 'failed')->count(),
            'total_multi_shift' => $presensi->whereNotNull('shift_ke')->count(),
            'total_regular' => $presensi->whereNull('shift_ke')->count(),
        ];

        // Generate PDF or Excel based on request
        if ($request->get('format') === 'excel') {
            $filename = 'Data_Presensi_Face_' . date('Y-m-d_His') . '.xlsx';
            return Excel::download(new PresensiFaceExport($presensi, $stats, $request), $filename);
        } else {
            // Default: PDF
            $pdf = PDF::loadView('admin.presensi-face.export-data', compact('presensi', 'stats', 'request'));
            return $pdf->download('Data_Presensi_Face_' . date('Y-m-d_His') . '.pdf');
        }
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
