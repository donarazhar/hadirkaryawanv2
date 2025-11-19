<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class HistoryKaryawanController extends Controller
{
    /**
     * Constructor - Middleware auth karyawan
     */
    public function __construct()
    {
        $this->middleware('auth:karyawan');
    }

    /**
     * Menampilkan halaman histori presensi
     */
    public function index()
    {
        try {
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

            return view('karyawan.histori.index', compact('namabulan'));
        } catch (\Exception $e) {
            Log::error('HistoryKaryawan@index Error: ' . $e->getMessage());
            return redirect('/dashboard')->with('error', 'Terjadi kesalahan saat memuat halaman histori.');
        }
    }

    public function gethistori(Request $request)
    {
        try {
            // Log raw request
            Log::info('GetHistori RAW Request', [
                'all_data' => $request->all(),
                'method' => $request->method(),
                'url' => $request->url()
            ]);

            // Validasi input
            $validated = $request->validate([
                'dari' => 'required|date',
                'sampai' => 'required|date|after_or_equal:dari'
            ]);

            $dari = $request->dari;
            $sampai = $request->sampai;
            $nik = Auth::guard('karyawan')->user()->nik;

            Log::info('GetHistori Validated', [
                'nik' => $nik,
                'dari' => $dari,
                'sampai' => $sampai
            ]);

            // Cek maksimal range 93 hari (3 bulan)
            $daysDiff = Carbon::parse($dari)->diffInDays(Carbon::parse($sampai));

            Log::info('Date range check', [
                'days_diff' => $daysDiff,
                'max_days' => 93
            ]);

            if ($daysDiff > 93) {
                Log::warning('Date range too long', ['days' => $daysDiff]);
                return response()->view('karyawan.histori.gethistori', [
                    'histori' => collect([]),
                    'error' => 'Maksimal rentang tanggal adalah 3 bulan'
                ]);
            }

            // Query histori presensi dengan join ke tabel terkait
            $histori = DB::table('presensi')
                ->select(
                    'presensi.*',
                    'jam_kerja.nama_jam_kerja',
                    'jam_kerja.jam_masuk',
                    'jam_kerja.jam_pulang',
                    'jam_kerja.lintashari',
                    'pengajuan_izin.keterangan',
                    'pengajuan_izin.doc_sid',
                    'pengajuan_izin.kode_izin',
                    'pengajuan_cuti.nama_cuti'
                )
                ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
                ->leftJoin('pengajuan_izin', 'presensi.kode_izin', '=', 'pengajuan_izin.kode_izin')
                ->leftJoin('pengajuan_cuti', 'pengajuan_izin.kode_cuti', '=', 'pengajuan_cuti.kode_cuti')
                ->where('presensi.nik', $nik)
                ->whereBetween('tgl_presensi', [$dari, $sampai])
                ->orderBy('tgl_presensi', 'desc')
                ->get();

            // Log hasil query dengan detail
            Log::info('GetHistori Query Result', [
                'nik' => $nik,
                'count' => $histori->count(),
                'dari' => $dari,
                'sampai' => $sampai,
                'first_record' => $histori->first(),
                'sql' => DB::getQueryLog()
            ]);

            // Jika tidak ada data
            if ($histori->isEmpty()) {
                Log::info('No data found for range', [
                    'nik' => $nik,
                    'dari' => $dari,
                    'sampai' => $sampai
                ]);
            }

            // Return view dengan data
            return view('karyawan.histori.gethistori', compact('histori'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('GetHistori Validation Error', [
                'errors' => $e->errors(),
                'message' => $e->getMessage()
            ]);

            return response()->view('karyawan.histori.gethistori', [
                'histori' => collect([]),
                'error' => 'Data tidak valid: ' . json_encode($e->errors())
            ]);
        } catch (\Exception $e) {
            Log::error('GetHistori Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return response()->view('karyawan.histori.gethistori', [
                'histori' => collect([]),
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get statistik presensi untuk periode tertentu
     */
    public function getStatistik(Request $request)
    {
        try {
            $dari = $request->dari;
            $sampai = $request->sampai;
            $nik = Auth::guard('karyawan')->user()->nik;

            // Validasi
            if (!$dari || !$sampai) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parameter tidak lengkap'
                ], 400);
            }

            // Hitung statistik
            $statistik = DB::table('presensi')
                ->selectRaw('
                    COUNT(*) as total_hari,
                    COUNT(CASE WHEN status = "h" THEN 1 END) as total_hadir,
                    COUNT(CASE WHEN status = "i" THEN 1 END) as total_izin,
                    COUNT(CASE WHEN status = "s" THEN 1 END) as total_sakit,
                    COUNT(CASE WHEN status = "c" THEN 1 END) as total_cuti,
                    COUNT(CASE WHEN status = "a" THEN 1 END) as total_alpa
                ')
                ->where('nik', $nik)
                ->whereBetween('tgl_presensi', [$dari, $sampai])
                ->first();

            // Hitung total terlambat
            $terlambat = DB::table('presensi')
                ->join('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
                ->where('presensi.nik', $nik)
                ->where('presensi.status', 'h')
                ->whereBetween('tgl_presensi', [$dari, $sampai])
                ->whereRaw('TIME(presensi.jam_in) > TIME(jam_kerja.jam_masuk)')
                ->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_hari' => $statistik->total_hari ?? 0,
                    'total_hadir' => $statistik->total_hadir ?? 0,
                    'total_izin' => $statistik->total_izin ?? 0,
                    'total_sakit' => $statistik->total_sakit ?? 0,
                    'total_cuti' => $statistik->total_cuti ?? 0,
                    'total_alpa' => $statistik->total_alpa ?? 0,
                    'total_terlambat' => $terlambat,
                    'dari' => $dari,
                    'sampai' => $sampai
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('GetStatistik Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik'
            ], 500);
        }
    }

    /**
     * Export histori ke Excel
     */
    public function exportExcel(Request $request)
    {
        try {
            $dari = $request->dari ?? date('Y-m-01');
            $sampai = $request->sampai ?? date('Y-m-t');
            $nik = Auth::guard('karyawan')->user()->nik;

            // Get data karyawan
            $karyawan = DB::table('karyawan')
                ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
                ->join('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang')
                ->where('nik', $nik)
                ->first();

            // Get histori
            $histori = DB::table('presensi')
                ->select(
                    'presensi.*',
                    'jam_kerja.nama_jam_kerja',
                    'jam_kerja.jam_masuk',
                    'jam_kerja.jam_pulang',
                    'pengajuan_izin.keterangan'
                )
                ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
                ->leftJoin('pengajuan_izin', 'presensi.kode_izin', '=', 'pengajuan_izin.kode_izin')
                ->where('presensi.nik', $nik)
                ->whereBetween('tgl_presensi', [$dari, $sampai])
                ->orderBy('tgl_presensi')
                ->get();

            // Set headers untuk Excel
            $filename = 'Histori_Presensi_' . $nik . '_' . date('d-m-Y', strtotime($dari)) . '_to_' . date('d-m-Y', strtotime($sampai)) . '.xls';

            header("Content-type: application/vnd-ms-excel");
            header("Content-Disposition: attachment; filename={$filename}");

            return view('karyawan.histori.export-histori-excel', compact(
                'karyawan',
                'histori',
                'dari',
                'sampai'
            ));
        } catch (\Exception $e) {
            Log::error('ExportExcel Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal export data');
        }
    }
}
