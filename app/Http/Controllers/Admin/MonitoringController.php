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

        $user = auth('user')->user();

        $query = DB::table('presensi')
            ->select(
                'presensi.*',
                'karyawan.nama_lengkap',
                'units.name as nama_dept',
                'jam_kerja.nama_jam_kerja',
                'jam_kerja.jam_masuk',
                'jam_kerja.jam_pulang',
                'pengajuan_izin.keterangan'
            )
            ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->leftJoin('pengajuan_izin', 'presensi.kode_izin', '=', 'pengajuan_izin.kode_izin')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->join('organs', 'karyawan.organ_id', '=', 'organs.id')
            ->join('units', 'organs.unit_id', '=', 'units.id')
            ->where('tgl_presensi', $tanggal);

        if ($user && $user->role === 'admin') {
            $query->where('units.branch_id', $user->branch_id);
        }

        $presensi = $query->get();

        return view('admin.monitoring.getpresensi', compact('presensi'));
    }

    /**
     * Show map (AJAX)
     */
    public function showmap(Request $request)
    {
        $id = $request->id;

        $user = auth('user')->user();

        $query = DB::table('presensi')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->join('organs', 'karyawan.organ_id', '=', 'organs.id')
            ->join('units', 'organs.unit_id', '=', 'units.id')
            ->where('presensi.id', $id);

        if ($user && $user->role === 'admin') {
            $query->where('units.branch_id', $user->branch_id);
        }

        $presensi = $query->first();

        return view('admin.monitoring.showmap', compact('presensi'));
    }

    /**
     * Realtime monitoring
     */
    public function realtime()
    {
        $hariini = date('Y-m-d');

        $user = auth('user')->user();

        $query = DB::table('presensi')
            ->select('presensi.*', 'karyawan.nama_lengkap', 'karyawan.foto')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->join('organs', 'karyawan.organ_id', '=', 'organs.id')
            ->join('units', 'organs.unit_id', '=', 'units.id')
            ->where('tgl_presensi', $hariini);

        if ($user && $user->role === 'admin') {
            $query->where('units.branch_id', $user->branch_id);
        }

        $presensi = $query->orderBy('jam_in', 'desc')
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

    /**
     * Koreksi Presensi Manual
     */
    public function koreksi(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'tanggal' => 'required|date',
            'status' => 'required|in:h,i,s,a',
        ]);

        try {
            $user = auth('user')->user();
            $karyawan = DB::table('karyawan')
                ->join('organs', 'karyawan.organ_id', '=', 'organs.id')
                ->join('units', 'organs.unit_id', '=', 'units.id')
                ->select('karyawan.*', 'units.branch_id', 'units.id as unit_id')
                ->where('nik', $request->nik)->first();
            
            if ($user && $user->role === 'admin' && $karyawan && $karyawan->branch_id !== $user->branch_id) {
                return redirect()->back()->with('error', 'Unauthorized action.');
            }

            $presensi = DB::table('presensi')
                ->where('nik', $request->nik)
                ->where('tgl_presensi', $request->tanggal)
                ->first();

            $kode_jam_kerja = null;

            if (!$presensi) {
                // Buat presensi baru (misal karena lupa absen)
                // Cari jam kerja karyawan hari ini
                $nama_hari = \Carbon\Carbon::parse($request->tanggal)->translatedFormat('l');
                
                // 1. Cek konfigurasi individu
                $jamKerjaIndividu = DB::table('konfigurasi_jam_kerja')
                    ->where('nik', $request->nik)
                    ->where('hari', $nama_hari)
                    ->first();

                if ($jamKerjaIndividu) {
                    $kode_jam_kerja = $jamKerjaIndividu->kode_jam_kerja;
                } else {
                    // 2. Cek konfigurasi unit
                    if ($karyawan) {
                        $konfigurasiUnit = DB::table('konfigurasi_jk_unit')
                            ->where('branch_id', $karyawan->branch_id)
                            ->where('unit_id', $karyawan->unit_id)
                            ->first();

                        if ($konfigurasiUnit) {
                            $detail = DB::table('konfigurasi_jk_unit_detail')
                                ->where('kode_jk_unit', $konfigurasiUnit->kode_jk_unit)
                                ->where('hari', $nama_hari)
                                ->first();
                                
                            if ($detail) {
                                $kode_jam_kerja = $detail->kode_jam_kerja;
                            }
                        }
                    }
                }

                DB::table('presensi')->insert([
                    'nik' => $request->nik,
                    'tgl_presensi' => $request->tanggal,
                    'jam_in' => $request->jam_in ?: null,
                    'jam_out' => $request->jam_out ?: null,
                    'status' => $request->status,
                    'kode_jam_kerja' => $kode_jam_kerja,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                // Update presensi yang ada
                DB::table('presensi')
                    ->where('id', $presensi->id)
                    ->update([
                        'jam_in' => $request->jam_in ? $request->tanggal . ' ' . $request->jam_in : $presensi->jam_in,
                        'jam_out' => $request->jam_out ? $request->tanggal . ' ' . $request->jam_out : $presensi->jam_out,
                        'status' => $request->status,
                        'updated_at' => now(),
                    ]);
            }

            return redirect()->back()->with('success', 'Data presensi manual berhasil disimpan.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Koreksi Presensi Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data presensi manual.');
        }
    }
}
