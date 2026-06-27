<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardAdminController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function index()
    {
        $hariini = date('Y-m-d');
        $bulanIni = date('Y-m');

        $user = Auth::guard('user')->user();
        $isAdminCabang = $user && $user->role === 'admin';
        $branchId = $user ? $user->branch_id : null;

        // ========== MASTER DATA STATISTICS ==========
        $totalCabang = $isAdminCabang ? 1 : DB::table('branches')->count();
        $totalDepartemen = DB::table('units')->count();

        $karyawanQuery = DB::table('karyawan')
            ->join('organs', 'karyawan.organ_id', '=', 'organs.id')
            ->join('units', 'organs.unit_id', '=', 'units.id');
        if ($isAdminCabang) {
            $karyawanQuery->where('units.branch_id', $branchId);
        }
        $totalKaryawan = $karyawanQuery->count();

        $totalJamKerja = DB::table('jam_kerja')->count();

        // ========== PRESENSI GPS HARI INI ==========
        $presensiGPSQuery = DB::table('presensi')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->join('organs', 'karyawan.organ_id', '=', 'organs.id')
            ->join('units', 'organs.unit_id', '=', 'units.id')
            ->where('tgl_presensi', $hariini);
        if ($isAdminCabang) $presensiGPSQuery->where('units.branch_id', $branchId);
        $presensiGPSHariIni = $presensiGPSQuery->count();

        $hadirGPSQuery = DB::table('presensi')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->join('organs', 'karyawan.organ_id', '=', 'organs.id')
            ->join('units', 'organs.unit_id', '=', 'units.id')
            ->where('tgl_presensi', $hariini)
            ->where('status', 'h');
        if ($isAdminCabang) $hadirGPSQuery->where('units.branch_id', $branchId);
        $hadirGPS = $hadirGPSQuery->count();

        $izinGPSQuery = DB::table('presensi')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->join('organs', 'karyawan.organ_id', '=', 'organs.id')
            ->join('units', 'organs.unit_id', '=', 'units.id')
            ->where('tgl_presensi', $hariini)
            ->whereIn('status', ['i', 's', 'c']);
        if ($isAdminCabang) $izinGPSQuery->where('units.branch_id', $branchId);
        $izinGPS = $izinGPSQuery->count();

        $terlambatGPSQuery = DB::table('presensi')
            ->join('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->join('organs', 'karyawan.organ_id', '=', 'organs.id')
            ->join('units', 'organs.unit_id', '=', 'units.id')
            ->where('tgl_presensi', $hariini)
            ->where('status', 'h')
            ->whereRaw('CAST(jam_in AS TIME) > jam_kerja.jam_masuk');
        if ($isAdminCabang) $terlambatGPSQuery->where('units.branch_id', $branchId);
        $terlambatGPS = $terlambatGPSQuery->count();

        // ========== PRESENSI FACE HARI INI ==========
        $presensiFaceQuery = DB::table('presensi_face')
            ->join('karyawan', 'presensi_face.nik', '=', 'karyawan.nik')
            ->join('organs', 'karyawan.organ_id', '=', 'organs.id')
            ->join('units', 'organs.unit_id', '=', 'units.id')
            ->where('tanggal', $hariini);
        if ($isAdminCabang) $presensiFaceQuery->where('units.branch_id', $branchId);
        $presensiFaceHariIni = $presensiFaceQuery->count();

        $checkInFaceQuery = DB::table('presensi_face')
            ->join('karyawan', 'presensi_face.nik', '=', 'karyawan.nik')
            ->join('organs', 'karyawan.organ_id', '=', 'organs.id')
            ->join('units', 'organs.unit_id', '=', 'units.id')
            ->where('tanggal', $hariini)
            ->whereNotNull('jam_masuk');
        if ($isAdminCabang) $checkInFaceQuery->where('units.branch_id', $branchId);
        $checkInFace = $checkInFaceQuery->count();

        $checkOutFaceQuery = DB::table('presensi_face')
            ->join('karyawan', 'presensi_face.nik', '=', 'karyawan.nik')
            ->join('organs', 'karyawan.organ_id', '=', 'organs.id')
            ->join('units', 'organs.unit_id', '=', 'units.id')
            ->where('tanggal', $hariini)
            ->whereNotNull('jam_pulang');
        if ($isAdminCabang) $checkOutFaceQuery->where('units.branch_id', $branchId);
        $checkOutFace = $checkOutFaceQuery->count();

        $verifiedFaceQuery = DB::table('presensi_face')
            ->join('karyawan', 'presensi_face.nik', '=', 'karyawan.nik')
            ->join('organs', 'karyawan.organ_id', '=', 'organs.id')
            ->join('units', 'organs.unit_id', '=', 'units.id')
            ->where('tanggal', $hariini)
            ->where('presensi_face.status', 'verified');
        if ($isAdminCabang) $verifiedFaceQuery->where('units.branch_id', $branchId);
        $verifiedFace = $verifiedFaceQuery->count();

        $failedFaceQuery = DB::table('presensi_face')
            ->join('karyawan', 'presensi_face.nik', '=', 'karyawan.nik')
            ->join('organs', 'karyawan.organ_id', '=', 'organs.id')
            ->join('units', 'organs.unit_id', '=', 'units.id')
            ->where('tanggal', $hariini)
            ->where('presensi_face.status', 'failed');
        if ($isAdminCabang) $failedFaceQuery->where('units.branch_id', $branchId);
        $failedFace = $failedFaceQuery->count();

        // ========== VERIFIKASI WAJAH STATUS ==========
        $enrolledQuery = DB::table('face_data')
            ->join('karyawan', 'face_data.nik', '=', 'karyawan.nik')
            ->join('organs', 'karyawan.organ_id', '=', 'organs.id')
            ->join('units', 'organs.unit_id', '=', 'units.id');
        if ($isAdminCabang) $enrolledQuery->where('units.branch_id', $branchId);
        $totalEnrolled = $enrolledQuery->count();

        $enrolledActiveQuery = DB::table('face_data')
            ->join('karyawan', 'face_data.nik', '=', 'karyawan.nik')
            ->join('organs', 'karyawan.organ_id', '=', 'organs.id')
            ->join('units', 'organs.unit_id', '=', 'units.id')
            ->where('face_data.status', 'active');
        if ($isAdminCabang) $enrolledActiveQuery->where('units.branch_id', $branchId);
        $enrolledActive = $enrolledActiveQuery->count();

        $enrolledInactiveQuery = DB::table('face_data')
            ->join('karyawan', 'face_data.nik', '=', 'karyawan.nik')
            ->join('organs', 'karyawan.organ_id', '=', 'organs.id')
            ->join('units', 'organs.unit_id', '=', 'units.id')
            ->where('face_data.status', 'inactive');
        if ($isAdminCabang) $enrolledInactiveQuery->where('units.branch_id', $branchId);
        $enrolledInactive = $enrolledInactiveQuery->count();

        $belumEnroll = $totalKaryawan - $totalEnrolled;

        $avgEnrollmentCountQuery = DB::table('face_data')
            ->join('karyawan', 'face_data.nik', '=', 'karyawan.nik')
            ->join('organs', 'karyawan.organ_id', '=', 'organs.id')
            ->join('units', 'organs.unit_id', '=', 'units.id');
        if ($isAdminCabang) $avgEnrollmentCountQuery->where('units.branch_id', $branchId);
        $avgEnrollmentCount = $avgEnrollmentCountQuery->avg('enrollment_count');

        $highQualityQuery = DB::table('face_data')
            ->join('karyawan', 'face_data.nik', '=', 'karyawan.nik')
            ->join('organs', 'karyawan.organ_id', '=', 'organs.id')
            ->join('units', 'organs.unit_id', '=', 'units.id')
            ->where('enrollment_count', '>=', 3);
        if ($isAdminCabang) $highQualityQuery->where('units.branch_id', $branchId);
        $highQualityEnrollment = $highQualityQuery->count();

        $recentEnrollmentQuery = DB::table('face_data')
            ->join('karyawan', 'face_data.nik', '=', 'karyawan.nik')
            ->join('organs', 'karyawan.organ_id', '=', 'organs.id')
            ->join('units', 'organs.unit_id', '=', 'units.id')
            ->where('last_updated', '>=', date('Y-m-d', strtotime('-7 days')));
        if ($isAdminCabang) $recentEnrollmentQuery->where('units.branch_id', $branchId);
        $recentEnrollment = $recentEnrollmentQuery->count();

        // ========== STATISTIK BULAN INI ==========
        $pGpsBlnQuery = DB::table('presensi')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->join('organs', 'karyawan.organ_id', '=', 'organs.id')
            ->join('units', 'organs.unit_id', '=', 'units.id')
            ->where('tgl_presensi', 'like', $bulanIni . '%');
        if ($isAdminCabang) $pGpsBlnQuery->where('units.branch_id', $branchId);
        $presensiGPSBulanIni = $pGpsBlnQuery->count();

        $pFaceBlnQuery = DB::table('presensi_face')
            ->join('karyawan', 'presensi_face.nik', '=', 'karyawan.nik')
            ->join('organs', 'karyawan.organ_id', '=', 'organs.id')
            ->join('units', 'organs.unit_id', '=', 'units.id')
            ->where('tanggal', 'like', $bulanIni . '%');
        if ($isAdminCabang) $pFaceBlnQuery->where('units.branch_id', $branchId);
        $presensiFaceBulanIni = $pFaceBlnQuery->count();

        $hBlnQuery = DB::table('presensi')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->join('organs', 'karyawan.organ_id', '=', 'organs.id')
            ->join('units', 'organs.unit_id', '=', 'units.id')
            ->where('tgl_presensi', 'like', $bulanIni . '%')
            ->where('status', 'h');
        if ($isAdminCabang) $hBlnQuery->where('units.branch_id', $branchId);
        $hadirBulanIni = $hBlnQuery->count();

        $iBlnQuery = DB::table('presensi')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->join('organs', 'karyawan.organ_id', '=', 'organs.id')
            ->join('units', 'organs.unit_id', '=', 'units.id')
            ->where('tgl_presensi', 'like', $bulanIni . '%')
            ->whereIn('status', ['i', 's', 'c']);
        if ($isAdminCabang) $iBlnQuery->where('units.branch_id', $branchId);
        $izinBulanIni = $iBlnQuery->count();

        // ========== PRESENSI GPS TERBARU (10) ==========
        $pgpsTerbaruQuery = DB::table('presensi')
            ->select(
                'presensi.*',
                'karyawan.nama_lengkap',
                'karyawan.jabatan',
                'branches.name as nama_cabang',
                'units.name as nama_dept'
            )
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->join('organs', 'karyawan.organ_id', '=', 'organs.id')
            ->join('units', 'organs.unit_id', '=', 'units.id')
            ->join('branches', 'units.branch_id', '=', 'branches.id')
            ->where('tgl_presensi', $hariini);
        if ($isAdminCabang) $pgpsTerbaruQuery->where('units.branch_id', $branchId);
        $presensiGPSTerbaru = $pgpsTerbaruQuery->orderBy('jam_in', 'desc')->limit(10)->get();

        // ========== PRESENSI FACE TERBARU (10) ==========
        $pfaceTerbaruQuery = DB::table('presensi_face')
            ->select(
                'presensi_face.*',
                'karyawan.nama_lengkap',
                'karyawan.jabatan',
                'branches.name as nama_cabang',
                'units.name as nama_dept'
            )
            ->join('karyawan', 'presensi_face.nik', '=', 'karyawan.nik')
            ->join('organs', 'karyawan.organ_id', '=', 'organs.id')
            ->join('units', 'organs.unit_id', '=', 'units.id')
            ->join('branches', 'units.branch_id', '=', 'branches.id')
            ->where('presensi_face.tanggal', $hariini);
        if ($isAdminCabang) $pfaceTerbaruQuery->where('units.branch_id', $branchId);
        $presensiFaceTerbaru = $pfaceTerbaruQuery->orderBy('presensi_face.created_at', 'desc')->limit(10)->get();

        // ========== GRAFIK 7 HARI TERAKHIR ==========
        $last7Days = [];
        $presensiGPSChart = [];
        $presensiFaceChart = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $last7Days[] = Carbon::now()->subDays($i)->format('d M');

            // Presensi GPS
            $q1 = DB::table('presensi')
                ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
                ->join('organs', 'karyawan.organ_id', '=', 'organs.id')
                ->join('units', 'organs.unit_id', '=', 'units.id')
                ->where('tgl_presensi', $date);
            if ($isAdminCabang) $q1->where('units.branch_id', $branchId);
            $presensiGPSChart[] = $q1->count();

            // Presensi Face
            $q2 = DB::table('presensi_face')
                ->join('karyawan', 'presensi_face.nik', '=', 'karyawan.nik')
                ->join('organs', 'karyawan.organ_id', '=', 'organs.id')
                ->join('units', 'organs.unit_id', '=', 'units.id')
                ->where('tanggal', $date);
            if ($isAdminCabang) $q2->where('units.branch_id', $branchId);
            $presensiFaceChart[] = $q2->count();
        }

        // ========== RANKING CABANG (TOP 5) ==========
        $rankingCabangQuery = DB::table('presensi')
            ->select(
                'branches.name as nama_cabang',
                DB::raw('COUNT(*) as total_presensi')
            )
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->join('organs', 'karyawan.organ_id', '=', 'organs.id')
            ->join('units', 'organs.unit_id', '=', 'units.id')
            ->join('branches', 'units.branch_id', '=', 'branches.id')
            ->where('tgl_presensi', 'like', $bulanIni . '%');
        if ($isAdminCabang) $rankingCabangQuery->where('units.branch_id', $branchId);
        $rankingCabang = $rankingCabangQuery->groupBy('branches.name')
            ->orderBy('total_presensi', 'desc')
            ->limit(5)
            ->get();

        // ========== RANKING DEPARTEMEN (TOP 5) ==========
        $rankingDeptQuery = DB::table('presensi')
            ->select(
                'units.name as nama_dept',
                DB::raw('COUNT(*) as total_presensi')
            )
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->join('organs', 'karyawan.organ_id', '=', 'organs.id')
            ->join('units', 'organs.unit_id', '=', 'units.id')
            ->where('tgl_presensi', 'like', $bulanIni . '%');
        if ($isAdminCabang) $rankingDeptQuery->where('units.branch_id', $branchId);
        $rankingDepartemen = $rankingDeptQuery->groupBy('units.name')
            ->orderBy('total_presensi', 'desc')
            ->limit(5)
            ->get();

        // ========== KARYAWAN TERLAMBAT HARI INI (5) ==========
        $terlambatHariIniQuery = DB::table('presensi')
            ->select(
                'presensi.*',
                'karyawan.nama_lengkap',
                'karyawan.jabatan',
                'branches.name as nama_cabang'
            )
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->join('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->join('organs', 'karyawan.organ_id', '=', 'organs.id')
            ->join('units', 'organs.unit_id', '=', 'units.id')
            ->join('branches', 'units.branch_id', '=', 'branches.id')
            ->where('tgl_presensi', $hariini)
            ->where('status', 'h')
            ->whereRaw('CAST(jam_in AS TIME) > jam_kerja.jam_masuk');
        if ($isAdminCabang) $terlambatHariIniQuery->where('units.branch_id', $branchId);
        $karyawanTerlambat = $terlambatHariIniQuery->orderBy('jam_in', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            // Master Data
            'totalCabang',
            'totalDepartemen',
            'totalKaryawan',
            'totalJamKerja',

            // Presensi GPS Hari Ini
            'presensiGPSHariIni',
            'hadirGPS',
            'izinGPS',
            'terlambatGPS',

            // Presensi Face Hari Ini
            'presensiFaceHariIni',
            'checkInFace',
            'checkOutFace',
            'verifiedFace',
            'failedFace',

            // Verifikasi Wajah
            'totalEnrolled',
            'enrolledActive',
            'enrolledInactive',
            'belumEnroll',
            'avgEnrollmentCount',
            'highQualityEnrollment',
            'recentEnrollment',

            // Statistik Bulan Ini
            'presensiGPSBulanIni',
            'presensiFaceBulanIni',
            'hadirBulanIni',
            'izinBulanIni',

            // Data Terbaru
            'presensiGPSTerbaru',
            'presensiFaceTerbaru',

            // Chart Data
            'last7Days',
            'presensiGPSChart',
            'presensiFaceChart',

            // Rankings
            'rankingCabang',
            'rankingDepartemen',
            'karyawanTerlambat'
        ));
    }
}
