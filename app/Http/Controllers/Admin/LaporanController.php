<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $cabang = DB::table('cabang')->orderBy('kode_cabang')->get();
        $departemen = DB::table('departemen')->orderBy('kode_dept')->get();

        $bulan = $request->bulan != null ? $request->bulan : date('m');
        $tahun = $request->tahun != null ? $request->tahun : date('Y');
        $kode_cabang = $request->kode_cabang != null ? $request->kode_cabang : '';
        $kode_dept = $request->kode_dept != null ? $request->kode_dept : '';

        $query = DB::table('presensi')
            ->select('presensi.*', 'karyawan.nama_lengkap', 'karyawan.kode_dept', 'jam_kerja.nama_jam_kerja')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->whereRaw('MONTH(tgl_presensi)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahun . '"');

        if (!empty($kode_cabang)) {
            $query->where('karyawan.kode_cabang', $kode_cabang);
        }
        
        if (!empty($kode_dept)) {
            $query->where('karyawan.kode_dept', $kode_dept);
        }

        $presensi = $query->orderBy('tgl_presensi', 'desc')->orderBy('karyawan.nama_lengkap')->get();

        return view('admin.laporan.index', compact('namabulan', 'cabang', 'departemen', 'bulan', 'tahun', 'kode_cabang', 'kode_dept', 'presensi'));
    }

    public function getKaryawan(Request $request)
    {
        $kode_cabang = $request->kode_cabang;
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

    public function edit(Request $request)
    {
        $id = $request->id;
        $presensi = DB::table('presensi')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->where('presensi.id', $id)
            ->first();

        return view('admin.laporan.edit', compact('presensi'));
    }

    public function update(Request $request)
    {
        $id = $request->id;
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
