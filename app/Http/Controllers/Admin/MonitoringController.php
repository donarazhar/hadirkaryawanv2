<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class MonitoringController extends Controller
{
    /**
     * Display monitoring page
     */
    public function index()
    {
        return view('admin.monitoring.index');
    }

    /**
     * Get presensi data (AJAX)
     */
    public function getpresensi(Request $request)
    {
        $tanggal = $request->tanggal;

        $presensi = DB::table('presensi')
            ->select(
                'presensi.*',
                'karyawan.nama_lengkap',
                'karyawan.kode_dept',
                'departemen.nama_dept',
                'jam_kerja.nama_jam_kerja',
                'jam_kerja.jam_masuk',
                'jam_kerja.jam_pulang',
                'pengajuan_izin.keterangan'
            )
            ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->leftJoin('pengajuan_izin', 'presensi.kode_izin', '=', 'pengajuan_izin.kode_izin')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->where('tgl_presensi', $tanggal)
            ->get();

        return view('admin.monitoring.getpresensi', compact('presensi'));
    }

    /**
     * Show map (AJAX)
     */
    public function showmap(Request $request)
    {
        $id = $request->id;

        $presensi = DB::table('presensi')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->where('presensi.id', $id)
            ->first();

        return view('admin.monitoring.showmap', compact('presensi'));
    }

    /**
     * Realtime monitoring
     */
    public function realtime()
    {
        $hariini = date('Y-m-d');

        $presensi = DB::table('presensi')
            ->select('presensi.*', 'karyawan.nama_lengkap', 'karyawan.foto')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->where('tgl_presensi', $hariini)
            ->orderBy('jam_in', 'desc')
            ->get();

        return response()->json(['data' => $presensi]);
    }

    /**
     * Export monitoring
     */
    public function export(Request $request)
    {
        // Implementation
    }
}
