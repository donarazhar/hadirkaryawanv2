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
        $kodeCabang = $user ? $user->kode_cabang : null;

        // ========== MASTER DATA STATISTICS ==========
        $totalCabang = $isAdminCabang ? 1 : DB::table('cabang')->count();
        $totalDepartemen = DB::table('departemen')->count();

        $karyawanQuery = DB::table('karyawan');
        if ($isAdminCabang) {
            $karyawanQuery->where('kode_cabang', $kodeCabang);
        }
        $totalKaryawan = $karyawanQuery->count();

        $totalJamKerja = DB::table('jam_kerja')->count();

        // ========== PRESENSI GPS HARI INI ==========
        $presensiGPSQuery = DB::table('presensi')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->where('tgl_presensi', $hariini);
        if ($isAdminCabang) $presensiGPSQuery->where('karyawan.kode_cabang', $kodeCabang);
        $presensiGPSHariIni = $presensiGPSQuery->count();

        $hadirGPSQuery = DB::table('presensi')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->where('tgl_presensi', $hariini)
            ->where('status', 'h');
        if ($isAdminCabang) $hadirGPSQuery->where('karyawan.kode_cabang', $kodeCabang);
        $hadirGPS = $hadirGPSQuery->count();

        $izinGPSQuery = DB::table('presensi')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->where('tgl_presensi', $hariini)
            ->whereIn('status', ['i', 's', 'c']);
        if ($isAdminCabang) $izinGPSQuery->where('karyawan.kode_cabang', $kodeCabang);
        $izinGPS = $izinGPSQuery->count();

        $terlambatGPSQuery = DB::table('presensi')
            ->join('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->where('tgl_presensi', $hariini)
            ->where('status', 'h')
            ->whereRaw('CAST(jam_in AS TIME) > jam_kerja.jam_masuk');
        if ($isAdminCabang) $terlambatGPSQuery->where('karyawan.kode_cabang', $kodeCabang);
        $terlambatGPS = $terlambatGPSQuery->count();

        // ========== PRESENSI FACE HARI INI ==========
        $presensiFaceQuery = DB::table('presensi_face')
            ->join('karyawan', 'presensi_face.nik', '=', 'karyawan.nik')
            ->where('tanggal', $hariini);
        if ($isAdminCabang) $presensiFaceQuery->where('karyawan.kode_cabang', $kodeCabang);
        $presensiFaceHariIni = $presensiFaceQuery->count();

        $checkInFaceQuery = DB::table('presensi_face')
            ->join('karyawan', 'presensi_face.nik', '=', 'karyawan.nik')
            ->where('tanggal', $hariini)
            ->whereNotNull('jam_masuk');
        if ($isAdminCabang) $checkInFaceQuery->where('karyawan.kode_cabang', $kodeCabang);
        $checkInFace = $checkInFaceQuery->count();

        $checkOutFaceQuery = DB::table('presensi_face')
            ->join('karyawan', 'presensi_face.nik', '=', 'karyawan.nik')
            ->where('tanggal', $hariini)
            ->whereNotNull('jam_pulang');
        if ($isAdminCabang) $checkOutFaceQuery->where('karyawan.kode_cabang', $kodeCabang);
        $checkOutFace = $checkOutFaceQuery->count();

        $verifiedFaceQuery = DB::table('presensi_face')
            ->join('karyawan', 'presensi_face.nik', '=', 'karyawan.nik')
            ->where('tanggal', $hariini)
            ->where('presensi_face.status', 'verified');
        if ($isAdminCabang) $verifiedFaceQuery->where('karyawan.kode_cabang', $kodeCabang);
        $verifiedFace = $verifiedFaceQuery->count();

        $failedFaceQuery = DB::table('presensi_face')
            ->join('karyawan', 'presensi_face.nik', '=', 'karyawan.nik')
            ->where('tanggal', $hariini)
            ->where('presensi_face.status', 'failed');
        if ($isAdminCabang) $failedFaceQuery->where('karyawan.kode_cabang', $kodeCabang);
        $failedFace = $failedFaceQuery->count();

        // ========== VERIFIKASI WAJAH STATUS ==========
        $enrolledQuery = DB::table('face_data')
            ->join('karyawan', 'face_data.nik', '=', 'karyawan.nik');
        if ($isAdminCabang) $enrolledQuery->where('karyawan.kode_cabang', $kodeCabang);
        $totalEnrolled = $enrolledQuery->count();

        $enrolledActiveQuery = DB::table('face_data')
            ->join('karyawan', 'face_data.nik', '=', 'karyawan.nik')
            ->where('face_data.status', 'active');
        if ($isAdminCabang) $enrolledActiveQuery->where('karyawan.kode_cabang', $kodeCabang);
        $enrolledActive = $enrolledActiveQuery->count();

        $enrolledInactiveQuery = DB::table('face_data')
            ->join('karyawan', 'face_data.nik', '=', 'karyawan.nik')
            ->where('face_data.status', 'inactive');
        if ($isAdminCabang) $enrolledInactiveQuery->where('karyawan.kode_cabang', $kodeCabang);
        $enrolledInactive = $enrolledInactiveQuery->count();

        $belumEnroll = $totalKaryawan - $totalEnrolled;

        $avgEnrollmentCountQuery = DB::table('face_data')
            ->join('karyawan', 'face_data.nik', '=', 'karyawan.nik');
        if ($isAdminCabang) $avgEnrollmentCountQuery->where('karyawan.kode_cabang', $kodeCabang);
        $avgEnrollmentCount = $avgEnrollmentCountQuery->avg('enrollment_count');

        $highQualityQuery = DB::table('face_data')
            ->join('karyawan', 'face_data.nik', '=', 'karyawan.nik')
            ->where('enrollment_count', '>=', 3);
        if ($isAdminCabang) $highQualityQuery->where('karyawan.kode_cabang', $kodeCabang);
        $highQualityEnrollment = $highQualityQuery->count();

        $recentEnrollmentQuery = DB::table('face_data')
            ->join('karyawan', 'face_data.nik', '=', 'karyawan.nik')
            ->where('last_updated', '>=', date('Y-m-d', strtotime('-7 days')));
        if ($isAdminCabang) $recentEnrollmentQuery->where('karyawan.kode_cabang', $kodeCabang);
        $recentEnrollment = $recentEnrollmentQuery->count();

        // ========== STATISTIK BULAN INI ==========
        $pGpsBlnQuery = DB::table('presensi')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->where('tgl_presensi', 'like', $bulanIni . '%');
        if ($isAdminCabang) $pGpsBlnQuery->where('karyawan.kode_cabang', $kodeCabang);
        $presensiGPSBulanIni = $pGpsBlnQuery->count();

        $pFaceBlnQuery = DB::table('presensi_face')
            ->join('karyawan', 'presensi_face.nik', '=', 'karyawan.nik')
            ->where('tanggal', 'like', $bulanIni . '%');
        if ($isAdminCabang) $pFaceBlnQuery->where('karyawan.kode_cabang', $kodeCabang);
        $presensiFaceBulanIni = $pFaceBlnQuery->count();

        $hBlnQuery = DB::table('presensi')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->where('tgl_presensi', 'like', $bulanIni . '%')
            ->where('status', 'h');
        if ($isAdminCabang) $hBlnQuery->where('karyawan.kode_cabang', $kodeCabang);
        $hadirBulanIni = $hBlnQuery->count();

        $iBlnQuery = DB::table('presensi')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->where('tgl_presensi', 'like', $bulanIni . '%')
            ->whereIn('status', ['i', 's', 'c']);
        if ($isAdminCabang) $iBlnQuery->where('karyawan.kode_cabang', $kodeCabang);
        $izinBulanIni = $iBlnQuery->count();

        // ========== PRESENSI GPS TERBARU (10) ==========
        $pgpsTerbaruQuery = DB::table('presensi')
            ->select(
                'presensi.*',
                'karyawan.nama_lengkap',
                'karyawan.jabatan',
                'cabang.nama_cabang',
                'departemen.nama_dept'
            )
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->leftJoin('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang')
            ->leftJoin('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->where('tgl_presensi', $hariini);
        if ($isAdminCabang) $pgpsTerbaruQuery->where('karyawan.kode_cabang', $kodeCabang);
        $presensiGPSTerbaru = $pgpsTerbaruQuery->orderBy('jam_in', 'desc')->limit(10)->get();

        // ========== PRESENSI FACE TERBARU (10) ==========
        $pfaceTerbaruQuery = DB::table('presensi_face')
            ->select(
                'presensi_face.*',
                'karyawan.nama_lengkap',
                'karyawan.jabatan',
                'cabang.nama_cabang',
                'departemen.nama_dept'
            )
            ->join('karyawan', 'presensi_face.nik', '=', 'karyawan.nik')
            ->leftJoin('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang')
            ->leftJoin('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->where('presensi_face.tanggal', $hariini);
        if ($isAdminCabang) $pfaceTerbaruQuery->where('karyawan.kode_cabang', $kodeCabang);
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
                ->where('tgl_presensi', $date);
            if ($isAdminCabang) $q1->where('karyawan.kode_cabang', $kodeCabang);
            $presensiGPSChart[] = $q1->count();

            // Presensi Face
            $q2 = DB::table('presensi_face')
                ->join('karyawan', 'presensi_face.nik', '=', 'karyawan.nik')
                ->where('tanggal', $date);
            if ($isAdminCabang) $q2->where('karyawan.kode_cabang', $kodeCabang);
            $presensiFaceChart[] = $q2->count();
        }

        // ========== RANKING CABANG (TOP 5) ==========
        $rankingCabangQuery = DB::table('presensi')
            ->select(
                'cabang.nama_cabang',
                DB::raw('COUNT(*) as total_presensi')
            )
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->join('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang')
            ->where('tgl_presensi', 'like', $bulanIni . '%');
        if ($isAdminCabang) $rankingCabangQuery->where('karyawan.kode_cabang', $kodeCabang);
        $rankingCabang = $rankingCabangQuery->groupBy('cabang.nama_cabang')
            ->orderBy('total_presensi', 'desc')
            ->limit(5)
            ->get();

        // ========== RANKING DEPARTEMEN (TOP 5) ==========
        $rankingDeptQuery = DB::table('presensi')
            ->select(
                'departemen.nama_dept',
                DB::raw('COUNT(*) as total_presensi')
            )
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->where('tgl_presensi', 'like', $bulanIni . '%');
        if ($isAdminCabang) $rankingDeptQuery->where('karyawan.kode_cabang', $kodeCabang);
        $rankingDepartemen = $rankingDeptQuery->groupBy('departemen.nama_dept')
            ->orderBy('total_presensi', 'desc')
            ->limit(5)
            ->get();

        // ========== KARYAWAN TERLAMBAT HARI INI (5) ==========
        $terlambatHariIniQuery = DB::table('presensi')
            ->select(
                'presensi.*',
                'karyawan.nama_lengkap',
                'karyawan.jabatan',
                'cabang.nama_cabang'
            )
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->join('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->leftJoin('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang')
            ->where('tgl_presensi', $hariini)
            ->where('status', 'h')
            ->whereRaw('CAST(jam_in AS TIME) > jam_kerja.jam_masuk');
        if ($isAdminCabang) $terlambatHariIniQuery->where('karyawan.kode_cabang', $kodeCabang);
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
