<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DashboardKaryawanController extends Controller
{
    public function index()
    {
        $hariini = date("Y-m-d");
        $bulanini = date("m") * 1;
        $tahunini = date("Y");
        $nik = Auth::guard('karyawan')->user()->nik;
        $kode_cabang = Auth::guard('karyawan')->user()->kode_cabang;

        // Presensi hari ini dengan join ke jam_kerja untuk mendapatkan jam_masuk
        $presensihariini = DB::table('presensi')
            ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->select('presensi.*', 'jam_kerja.jam_masuk', 'jam_kerja.jam_pulang', 'jam_kerja.nama_jam_kerja')
            ->where('presensi.tgl_presensi', $hariini)
            ->where('presensi.nik', $nik)
            ->first();

        // Histori bulan ini untuk user yang login
        $historibulanini = DB::table('presensi')
            ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->leftJoin('pengajuan_izin', 'presensi.kode_izin', '=', 'pengajuan_izin.kode_izin')
            ->select(
                'presensi.*',
                'jam_kerja.jam_masuk',
                'jam_kerja.jam_pulang',
                'jam_kerja.nama_jam_kerja',
                'pengajuan_izin.keterangan',
                'pengajuan_izin.doc_sid'
            )
            ->where('presensi.nik', $nik)
            ->whereRaw('MONTH(presensi.tgl_presensi) = ?', [$bulanini])
            ->whereRaw('YEAR(presensi.tgl_presensi) = ?', [$tahunini])
            ->orderBy('presensi.tgl_presensi', 'desc')
            ->get();

        // Rekap presensi dengan perhitungan terlambat
        $rekappresensi = DB::table('presensi')
            ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->selectRaw('COUNT(presensi.nik) as jmlhadir')
            ->selectRaw('SUM(IF(TIME(presensi.jam_in) > TIME(jam_kerja.jam_masuk), 1, 0)) as jmlterlambat')
            ->where('presensi.nik', $nik)
            ->whereRaw('MONTH(presensi.tgl_presensi) = ?', [$bulanini])
            ->whereRaw('YEAR(presensi.tgl_presensi) = ?', [$tahunini])
            ->first();

        // Rekap izin/sakit dari tabel pengajuan_izin
        $rekapizin = DB::table('pengajuan_izin')
            ->selectRaw('SUM(IF(status="i", 1, 0)) as jmlizin')
            ->selectRaw('SUM(IF(status="s", 1, 0)) as jmlsakit')
            ->where('nik', $nik)
            ->whereRaw('MONTH(tgl_izin_dari) = ?', [$bulanini])
            ->whereRaw('YEAR(tgl_izin_dari) = ?', [$tahunini])
            ->where('status_approved', 1)
            ->first();

        // Leaderboard presensi cabang hari ini
        $leaderboard = DB::table('presensi')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->select(
                'presensi.*',
                'karyawan.nama_lengkap',
                'karyawan.jabatan',
                'karyawan.foto'
            )
            ->where('presensi.tgl_presensi', $hariini)
            ->where('karyawan.kode_cabang', $kode_cabang)
            ->orderBy('presensi.jam_in', 'asc')
            ->get();

        // UPDATED: Riwayat presensi karyawan secabang HARI INI saja (bukan user yang login)
        $riwayattim = DB::table('presensi')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->select(
                'presensi.*',
                'karyawan.nama_lengkap',
                'karyawan.jabatan',
                'karyawan.foto',
                'jam_kerja.jam_masuk'
            )
            ->where('karyawan.kode_cabang', $kode_cabang)
            ->where('presensi.tgl_presensi', $hariini) // HANYA HARI INI
            ->where('presensi.nik', '!=', $nik) // Exclude user yang login
            ->orderBy('presensi.jam_in', 'asc')
            ->get();

        // Array nama bulan dalam Bahasa Indonesia
        $namabulan = [
            "",
            "Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember"
        ];

        return view('karyawan.dashboard.dashboard', compact(
            'presensihariini',
            'historibulanini',
            'rekappresensi',
            'rekapizin',
            'leaderboard',
            'riwayattim',
            'namabulan',
            'bulanini',
            'tahunini'
        ));
    }
}
