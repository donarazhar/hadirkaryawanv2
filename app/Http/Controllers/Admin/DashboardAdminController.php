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

        // ========== MASTER DATA STATISTICS ==========
        $totalCabang = DB::table('cabang')->count();
        $totalDepartemen = DB::table('departemen')->count();
        $totalKaryawan = DB::table('karyawan')->count();
        $totalJamKerja = DB::table('jam_kerja')->count();

        // ========== PRESENSI GPS HARI INI ==========
        $presensiGPSHariIni = DB::table('presensi')
            ->where('tgl_presensi', $hariini)
            ->count();

        $hadirGPS = DB::table('presensi')
            ->where('tgl_presensi', $hariini)
            ->where('status', 'h')
            ->count();

        $izinGPS = DB::table('presensi')
            ->where('tgl_presensi', $hariini)
            ->whereIn('status', ['i', 's', 'c'])
            ->count();

        $terlambatGPS = DB::table('presensi')
            ->where('tgl_presensi', $hariini)
            ->where('status', 'h')
            ->whereRaw('TIME(jam_in) > (SELECT jam_masuk FROM jam_kerja LIMIT 1)')
            ->count();

        // ========== PRESENSI FACE HARI INI ==========
        $presensiFaceHariIni = DB::table('presensi_face')
            ->where('tanggal', $hariini)
            ->count();

        $checkInFace = DB::table('presensi_face')
            ->where('tanggal', $hariini)
            ->whereNotNull('jam_masuk')
            ->count();

        $checkOutFace = DB::table('presensi_face')
            ->where('tanggal', $hariini)
            ->whereNotNull('jam_pulang')
            ->count();

        $verifiedFace = DB::table('presensi_face')
            ->where('tanggal', $hariini)
            ->where('status', 'verified')
            ->count();

        $failedFace = DB::table('presensi_face')
            ->where('tanggal', $hariini)
            ->where('status', 'failed')
            ->count();

        // ========== VERIFIKASI WAJAH STATUS ==========
        // Total karyawan yang sudah enroll (ada di face_data)
        $totalEnrolled = DB::table('face_data')->count();

        // Enrolled Active (status = 'active')
        $enrolledActive = DB::table('face_data')
            ->where('status', 'active')
            ->count();

        // Enrolled Inactive (status = 'inactive')
        $enrolledInactive = DB::table('face_data')
            ->where('status', 'inactive')
            ->count();

        // Belum enroll (karyawan yang belum ada di face_data)
        $belumEnroll = $totalKaryawan - $totalEnrolled;

        // Statistik Enrollment Quality
        $avgEnrollmentCount = DB::table('face_data')
            ->avg('enrollment_count');

        $highQualityEnrollment = DB::table('face_data')
            ->where('enrollment_count', '>=', 3)
            ->count();

        $recentEnrollment = DB::table('face_data')
            ->where('last_updated', '>=', date('Y-m-d', strtotime('-7 days')))
            ->count();

        // ========== STATISTIK BULAN INI ==========
        $presensiGPSBulanIni = DB::table('presensi')
            ->where('tgl_presensi', 'like', $bulanIni . '%')
            ->count();

        $presensiFaceBulanIni = DB::table('presensi_face')
            ->where('tanggal', 'like', $bulanIni . '%')
            ->count();

        $hadirBulanIni = DB::table('presensi')
            ->where('tgl_presensi', 'like', $bulanIni . '%')
            ->where('status', 'h')
            ->count();

        $izinBulanIni = DB::table('presensi')
            ->where('tgl_presensi', 'like', $bulanIni . '%')
            ->whereIn('status', ['i', 's', 'c'])
            ->count();

        // ========== PRESENSI GPS TERBARU (10) ==========
        $presensiGPSTerbaru = DB::table('presensi')
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
            ->where('tgl_presensi', $hariini)
            ->orderBy('jam_in', 'desc')
            ->limit(10)
            ->get();

        // ========== PRESENSI FACE TERBARU (10) ==========
        $presensiFaceTerbaru = DB::table('presensi_face')
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
            ->where('presensi_face.tanggal', $hariini)
            ->orderBy('presensi_face.created_at', 'desc')
            ->limit(10)
            ->get();

        // ========== GRAFIK 7 HARI TERAKHIR ==========
        $last7Days = [];
        $presensiGPSChart = [];
        $presensiFaceChart = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $last7Days[] = Carbon::now()->subDays($i)->format('d M');

            // Presensi GPS
            $presensiGPSChart[] = DB::table('presensi')
                ->where('tgl_presensi', $date)
                ->count();

            // Presensi Face
            $presensiFaceChart[] = DB::table('presensi_face')
                ->where('tanggal', $date)
                ->count();
        }

        // ========== RANKING CABANG (TOP 5) ==========
        $rankingCabang = DB::table('presensi')
            ->select(
                'cabang.nama_cabang',
                DB::raw('COUNT(*) as total_presensi')
            )
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->join('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang')
            ->where('tgl_presensi', 'like', $bulanIni . '%')
            ->groupBy('cabang.nama_cabang')
            ->orderBy('total_presensi', 'desc')
            ->limit(5)
            ->get();

        // ========== RANKING DEPARTEMEN (TOP 5) ==========
        $rankingDepartemen = DB::table('presensi')
            ->select(
                'departemen.nama_dept',
                DB::raw('COUNT(*) as total_presensi')
            )
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->where('tgl_presensi', 'like', $bulanIni . '%')
            ->groupBy('departemen.nama_dept')
            ->orderBy('total_presensi', 'desc')
            ->limit(5)
            ->get();

        // ========== KARYAWAN TERLAMBAT HARI INI (5) ==========
        $karyawanTerlambat = DB::table('presensi')
            ->select(
                'presensi.*',
                'karyawan.nama_lengkap',
                'karyawan.jabatan',
                'cabang.nama_cabang'
            )
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->leftJoin('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang')
            ->where('tgl_presensi', $hariini)
            ->where('status', 'h')
            ->whereRaw('TIME(jam_in) > (SELECT jam_masuk FROM jam_kerja LIMIT 1)')
            ->orderBy('jam_in', 'desc')
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
