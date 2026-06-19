<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class RekapController extends Controller
{
    public function index()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $user = auth('user')->user();

        if ($user && $user->role === 'admin') {
            $cabang = DB::table('cabang')->where('kode_cabang', $user->kode_cabang)->get();
        } else {
            $cabang = DB::table('cabang')->orderBy('kode_cabang')->get();
        }

        $departemen = DB::table('departemen')->orderBy('kode_dept')->get();

        return view('admin.rekap.index', compact('namabulan', 'cabang', 'departemen'));
    }

    public function getKaryawan(Request $request)
    {
        $user = auth('user')->user();
        $kode_cabang = $request->kode_cabang;
        
        if ($user && $user->role === 'admin') {
            $kode_cabang = $user->kode_cabang;
        }

        $kode_dept = $request->kode_dept;

        $query = DB::table('karyawan')->orderBy('nama_lengkap');
        
        if (!empty($kode_cabang)) {
            $query->where('kode_cabang', $kode_cabang);
        }
        
        if (!empty($kode_dept)) {
            $query->where('kode_dept', $kode_dept);
        }

        $karyawan = $query->get();

        echo "<option value=''>Pilih Karyawan</option>";
        foreach ($karyawan as $k) {
            echo "<option value='$k->nik'>$k->nama_lengkap</option>";
        }
    }

    public function cetak(Request $request)
    {
        $nik = $request->nik;
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

        $user = auth('user')->user();
        $karyawan = DB::table('karyawan')
            ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->join('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang')
            ->where('nik', $nik)
            ->first();

        if ($user && $user->role === 'admin' && $karyawan && $karyawan->kode_cabang !== $user->kode_cabang) {
            abort(403, 'Unauthorized action.');
        }

        // Ambil data presensi
        $presensi = DB::table('presensi')
            ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->where('presensi.nik', $nik)
            ->whereRaw('MONTH(tgl_presensi)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahun . '"')
            ->orderBy('tgl_presensi')
            ->orderBy('jam_in')
            ->get();

        // Re-group by date for view (in case of multi-shift)
        $rekap = [];
        foreach ($presensi as $p) {
            $tgl = $p->tgl_presensi;
            if(!isset($rekap[$tgl])) {
                $rekap[$tgl] = [];
            }
            $rekap[$tgl][] = $p;
        }

        return view('admin.rekap.cetak', compact('bulan', 'tahun', 'namabulan', 'karyawan', 'rekap'));
    }
}
