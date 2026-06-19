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

        echo "<option value='ALL'>Semua Karyawan</option>";
        foreach ($karyawan as $k) {
            echo "<option value='$k->nik'>$k->nama_lengkap</option>";
        }
    }

    public function cetak(Request $request)
    {
        $nik = $request->nik;
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $kode_cabang = $request->kode_cabang;
        $kode_dept = $request->kode_dept;
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

        $user = auth('user')->user();
        
        $karyawanQuery = DB::table('karyawan')
            ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->join('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang');

        if ($user && $user->role === 'admin') {
            $karyawanQuery->where('karyawan.kode_cabang', $user->kode_cabang);
        }

        if ($nik === 'ALL') {
            if (!empty($kode_cabang)) {
                $karyawanQuery->where('karyawan.kode_cabang', $kode_cabang);
            }
            if (!empty($kode_dept)) {
                $karyawanQuery->where('karyawan.kode_dept', $kode_dept);
            }
        } else {
            $karyawanQuery->where('nik', $nik);
        }
        
        $karyawans = $karyawanQuery->orderBy('karyawan.nama_lengkap')->get();

        if ($nik !== 'ALL' && $user && $user->role === 'admin' && $karyawans->count() > 0 && $karyawans->first()->kode_cabang !== $user->kode_cabang) {
            abort(403, 'Unauthorized action.');
        }

        $rekapData = [];
        
        foreach ($karyawans as $karyawan) {
            $presensi = DB::table('presensi')
                ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
                ->where('presensi.nik', $karyawan->nik)
                ->whereRaw('MONTH(tgl_presensi)="' . $bulan . '"')
                ->whereRaw('YEAR(tgl_presensi)="' . $tahun . '"')
                ->orderBy('tgl_presensi')
                ->orderBy('jam_in')
                ->get();

            $rekap = [];
            foreach ($presensi as $p) {
                $tgl = $p->tgl_presensi;
                if(!isset($rekap[$tgl])) {
                    $rekap[$tgl] = [];
                }
                $rekap[$tgl][] = $p;
            }
            
            $rekapData[] = [
                'karyawan' => $karyawan,
                'rekap' => $rekap
            ];
        }

        return view('admin.rekap.cetak', compact('bulan', 'tahun', 'namabulan', 'rekapData'));
    }
}
