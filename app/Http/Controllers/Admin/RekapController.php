<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;

class RekapController extends Controller
{
    public function index()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $user = auth('user')->user();

        if ($user && $user->role === 'admin') {
            $branches = Branch::where('id', $user->branch_id)->get();
            $units = Unit::where('branch_id', $user->branch_id)->orderBy('name')->get();
        } else {
            $branches = Branch::orderBy('name')->get();
            $units = Unit::orderBy('name')->get();
        }

        return view('admin.rekap.index', compact('namabulan', 'branches', 'units'));
    }

    public function getKaryawan(Request $request)
    {
        $user = auth('user')->user();
        $branch_id = $request->branch_id;
        
        if ($user && $user->role === 'admin') {
            $branch_id = $user->branch_id;
        }

        $unit_id = $request->unit_id;

        $query = DB::table('karyawan')
            ->join('organs', 'karyawan.organ_id', '=', 'organs.id')
            ->join('units', 'organs.unit_id', '=', 'units.id')
            ->select('karyawan.*')
            ->orderBy('karyawan.nama_lengkap');
        
        if (!empty($branch_id)) {
            $query->where('units.branch_id', $branch_id);
        }
        
        if (!empty($unit_id)) {
            $query->where('units.id', $unit_id);
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
        $branch_id = $request->branch_id;
        $unit_id = $request->unit_id;
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

        $user = auth('user')->user();
        
        $karyawanQuery = \App\Models\Karyawan::with(['organ.unit.branch']);

        if ($user && $user->role === 'admin') {
            $karyawanQuery->whereHas('organ.unit', function($q) use ($user) {
                $q->where('branch_id', $user->branch_id);
            });
        }

        if ($nik === 'ALL') {
            if (!empty($branch_id)) {
                $karyawanQuery->whereHas('organ.unit', function($q) use ($branch_id) {
                    $q->where('branch_id', $branch_id);
                });
            }
            if (!empty($unit_id)) {
                $karyawanQuery->whereHas('organ.unit', function($q) use ($unit_id) {
                    $q->where('id', $unit_id);
                });
            }
        } else {
            $karyawanQuery->where('nik', $nik);
        }
        
        $karyawans = $karyawanQuery->orderBy('nama_lengkap')->get();

        if ($nik !== 'ALL' && $user && $user->role === 'admin' && $karyawans->count() > 0) {
            foreach ($karyawans as $k) {
                if ($k->organ && $k->organ->unit && $k->organ->unit->branch_id !== $user->branch_id) {
                    abort(403, 'Unauthorized action.');
                }
            }
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
