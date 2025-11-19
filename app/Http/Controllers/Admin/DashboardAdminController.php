<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardAdminController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function index()
    {
        $hariini = date('Y-m-d');

        // Total karyawan
        $totalKaryawan = DB::table('karyawan')->count();

        // Hadir hari ini
        $hadirHariIni = DB::table('presensi')
            ->where('tgl_presensi', $hariini)
            ->where('status', 'h')
            ->count();

        // Izin hari ini
        $izinHariIni = DB::table('presensi')
            ->where('tgl_presensi', $hariini)
            ->whereIn('status', ['i', 's', 'c'])
            ->count();

        // Alpa hari ini (karyawan yang belum presensi)
        $alpaHariIni = $totalKaryawan - ($hadirHariIni + $izinHariIni);

        // Pending izin
        $pendingIzin = DB::table('pengajuan_izin')
            ->where('status_approved', 0)
            ->count();

        // Presensi hari ini (10 terbaru)
        $presensiHariIni = DB::table('presensi')
            ->select('presensi.*', 'karyawan.nama_lengkap', 'karyawan.jabatan')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->where('tgl_presensi', $hariini)
            ->orderBy('jam_in', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalKaryawan',
            'hadirHariIni',
            'izinHariIni',
            'alpaHariIni',
            'pendingIzin',
            'presensiHariIni'
        ));
    }
}
