<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Models\Branch;
use App\Models\Unit;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanPresensiExport;

class LaporanController extends Controller
{
    public function index(Request $request)
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

        $bulan = $request->bulan != null ? $request->bulan : date('m');
        $tahun = $request->tahun != null ? $request->tahun : date('Y');
        
        $branch_id = $request->branch_id != null ? $request->branch_id : '';
        if ($user && $user->role === 'admin') {
            $branch_id = $user->branch_id;
        }

        $unit_id = $request->unit_id != null ? $request->unit_id : '';

        $query = DB::table('presensi')
            ->select('presensi.*', 'karyawan.nama_lengkap', 'units.name as nama_dept', 'jam_kerja.nama_jam_kerja')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->join('organs', 'karyawan.organ_id', '=', 'organs.id')
            ->join('units', 'organs.unit_id', '=', 'units.id')
            ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->whereRaw('MONTH(tgl_presensi)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahun . '"');

        if (!empty($branch_id)) {
            $query->where('units.branch_id', $branch_id);
        }
        
        if (!empty($unit_id)) {
            $query->where('units.id', $unit_id);
        }

        $presensi = $query->orderBy('tgl_presensi', 'desc')->orderBy('karyawan.nama_lengkap')->get();

        return view('admin.laporan.index', compact('namabulan', 'branches', 'units', 'bulan', 'tahun', 'branch_id', 'unit_id', 'presensi'));
    }

    public function exportPdf(Request $request)
    {
        $user = auth('user')->user();
        $bulan = $request->bulan != null ? $request->bulan : date('m');
        $tahun = $request->tahun != null ? $request->tahun : date('Y');
        
        $branch_id = $request->branch_id != null ? $request->branch_id : '';
        if ($user && $user->role === 'admin') {
            $branch_id = $user->branch_id;
        }

        $unit_id = $request->unit_id != null ? $request->unit_id : '';

        $query = DB::table('presensi')
            ->select('presensi.*', 'karyawan.nama_lengkap', 'jam_kerja.nama_jam_kerja')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->join('organs', 'karyawan.organ_id', '=', 'organs.id')
            ->join('units', 'organs.unit_id', '=', 'units.id')
            ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->whereRaw('MONTH(tgl_presensi)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahun . '"');

        if (!empty($branch_id)) {
            $query->where('units.branch_id', $branch_id);
        }
        
        if (!empty($unit_id)) {
            $query->where('units.id', $unit_id);
        }

        $presensi = $query->orderBy('tgl_presensi', 'desc')->orderBy('karyawan.nama_lengkap')->get();
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $namabulan = $namabulan[(int)$bulan];

        $pdf = Pdf::loadView('admin.laporan.excel', compact('presensi', 'namabulan', 'tahun'));
        return $pdf->download("Laporan_Presensi_{$namabulan}_{$tahun}.pdf");
    }

    public function exportExcel(Request $request)
    {
        $user = auth('user')->user();
        $bulan = $request->bulan != null ? $request->bulan : date('m');
        $tahun = $request->tahun != null ? $request->tahun : date('Y');
        
        $branch_id = $request->branch_id != null ? $request->branch_id : '';
        if ($user && $user->role === 'admin') {
            $branch_id = $user->branch_id;
        }

        $unit_id = $request->unit_id != null ? $request->unit_id : '';

        $query = DB::table('presensi')
            ->select('presensi.*', 'karyawan.nama_lengkap', 'jam_kerja.nama_jam_kerja')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->join('organs', 'karyawan.organ_id', '=', 'organs.id')
            ->join('units', 'organs.unit_id', '=', 'units.id')
            ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->whereRaw('MONTH(tgl_presensi)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahun . '"');

        if (!empty($branch_id)) {
            $query->where('units.branch_id', $branch_id);
        }
        
        if (!empty($unit_id)) {
            $query->where('units.id', $unit_id);
        }

        $presensi = $query->orderBy('tgl_presensi', 'desc')->orderBy('karyawan.nama_lengkap')->get();

        return Excel::download(new LaporanPresensiExport($presensi, (int)$bulan, $tahun), "Laporan_Presensi_{$bulan}_{$tahun}.xlsx");
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
            ->orderBy('nama_lengkap');
        
        if (!empty($branch_id)) {
            $query->where('units.branch_id', $branch_id);
        }
        
        if (!empty($unit_id)) {
            $query->where('units.id', $unit_id);
        }

        $karyawan = $query->get();

        echo "<option value=''>Pilih Karyawan</option>";
        foreach ($karyawan as $k) {
            echo "<option value='$k->nik'>$k->nama_lengkap</option>";
        }
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $user = auth('user')->user();
        
        $query = DB::table('presensi')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->join('organs', 'karyawan.organ_id', '=', 'organs.id')
            ->join('units', 'organs.unit_id', '=', 'units.id')
            ->select('presensi.*', 'units.branch_id')
            ->where('presensi.id', $id);

        if ($user && $user->role === 'admin') {
            $query->where('units.branch_id', $user->branch_id);
        }

        $presensi = $query->first();

        if (!$presensi) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        return view('admin.laporan.edit', compact('presensi'));
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $user = auth('user')->user();
        $presensiCheck = DB::table('presensi')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->join('organs', 'karyawan.organ_id', '=', 'organs.id')
            ->join('units', 'organs.unit_id', '=', 'units.id')
            ->select('presensi.*', 'units.branch_id')
            ->where('presensi.id', $id)->first();

        if ($user && $user->role === 'admin' && $presensiCheck && $presensiCheck->branch_id !== $user->branch_id) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $jam_in = $request->jam_in;
        $jam_out = $request->jam_out;
        $status = $request->status;
        $keterangan = $request->keterangan;

        try {
            DB::table('presensi')->where('id', $id)->update([
                'jam_in' => $jam_in,
                'jam_out' => $jam_out,
                'status' => $status,
                'keterangan' => $keterangan
            ]);
            return Redirect::back()->with(['success' => 'Data presensi berhasil diupdate']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Data presensi gagal diupdate']);
        }
    }

    public function store(Request $request)
    {
        $nik = $request->nik;
        
        $user = auth('user')->user();
        $karyawan = DB::table('karyawan')
            ->join('organs', 'karyawan.organ_id', '=', 'organs.id')
            ->join('units', 'organs.unit_id', '=', 'units.id')
            ->select('karyawan.*', 'units.branch_id')
            ->where('nik', $nik)->first();

        if ($user && $user->role === 'admin' && $karyawan && $karyawan->branch_id !== $user->branch_id) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $tgl_presensi = $request->tgl_presensi;
        $jam_in = $request->jam_in;
        $jam_out = $request->jam_out;
        $status = $request->status;
        $keterangan = $request->keterangan;

        // Cek apakah sudah ada presensi di tanggal tersebut
        $cek = DB::table('presensi')->where('nik', $nik)->where('tgl_presensi', $tgl_presensi)->count();
        if($cek > 0) {
            return Redirect::back()->with(['warning' => 'Data presensi untuk karyawan dan tanggal tersebut sudah ada! Silakan edit data yang sudah ada.']);
        }

        try {
            DB::table('presensi')->insert([
                'nik' => $nik,
                'tgl_presensi' => $tgl_presensi,
                'jam_in' => $jam_in,
                'jam_out' => $jam_out,
                'status' => $status,
                'keterangan' => $keterangan
            ]);
            return Redirect::back()->with(['success' => 'Data presensi berhasil ditambahkan']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Data presensi gagal ditambahkan']);
        }
    }
}
