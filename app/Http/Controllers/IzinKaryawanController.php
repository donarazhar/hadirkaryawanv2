<?php

namespace App\Http\Controllers;

use App\Models\PengajuanCuti;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class IzinKaryawanController extends Controller
{
    /**
     * Constructor - Middleware auth karyawan
     */
    public function __construct()
    {
        $this->middleware('auth:karyawan');
    }

    /**
     * Menampilkan daftar pengajuan izin/sakit/cuti karyawan
     */
    public function index(Request $request)
    {
        try {
            $nik = Auth::guard('karyawan')->user()->nik;

            // Query dasar
            $query = DB::table('pengajuan_izin')
                ->leftJoin('pengajuan_cuti', 'pengajuan_izin.kode_cuti', '=', 'pengajuan_cuti.kode_cuti')
                ->where('nik', $nik)
                ->orderBy('tgl_izin_dari', 'desc');

            // Filter berdasarkan bulan dan tahun jika ada
            if (!empty($request->bulan) && !empty($request->tahun)) {
                $query->whereRaw('MONTH(tgl_izin_dari) = ?', [$request->bulan])
                    ->whereRaw('YEAR(tgl_izin_dari) = ?', [$request->tahun]);
            } else {
                // Default: ambil semua data (bisa dibatasi dengan pagination jika perlu)
                // Atau limit 50 data terbaru
                $query->limit(50);
            }

            $dataizin = $query->get();

            Log::info('IzinKaryawan@index loaded', [
                'nik' => $nik,
                'count' => $dataizin->count()
            ]);

            // Array nama bulan
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

            return view('karyawan.izin.index', compact('dataizin', 'namabulan'));
        } catch (Exception $e) {
            Log::error('IzinKaryawan@index Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect('/dashboard')->with('error', 'Terjadi kesalahan saat memuat data izin.');
        }
    }

    public function create()
    {

        // Ambil cuti aktif
        $cuti = PengajuanCuti::aktif()->get();
        
        try {
            Log::info('=== IzinKaryawan@create START ===');

            $user = Auth::guard('karyawan')->user();

            // Untuk sementara, cuti diset kosong
            // Nanti bisa dikembangkan kalau fitur cuti sudah siap
            $cuti = collect([]);

            Log::info('=== IzinKaryawan@create SUCCESS ===');
            return view('karyawan.izin.create', compact('cuti'));
        } catch (\Exception $e) {
            Log::error('=== IzinKaryawan@create ERROR ===', [
                'message' => $e->getMessage()
            ]);

            return redirect('/presensi/izin')->with('error', 'Gagal memuat form');
        }
    }

    /**
     * Menyimpan pengajuan izin/sakit baru
     */
    public function store(Request $request)
    {
        try {
            Log::info('IzinKaryawan@store started', [
                'data' => $request->except(['doc_sid'])
            ]);

            // Validasi input
            $request->validate([
                'tgl_izin_dari' => 'required|date',
                'tgl_izin_sampai' => 'required|date|after_or_equal:tgl_izin_dari',
                'status' => 'required|in:i,s,c',
                'keterangan' => 'required|string|min:10|max:500',
                'doc_sid' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
            ], [
                'tgl_izin_dari.required' => 'Tanggal mulai harus diisi',
                'tgl_izin_sampai.required' => 'Tanggal selesai harus diisi',
                'tgl_izin_sampai.after_or_equal' => 'Tanggal selesai harus lebih besar atau sama dengan tanggal mulai',
                'status.required' => 'Tipe izin harus dipilih',
                'keterangan.required' => 'Keterangan harus diisi',
                'keterangan.min' => 'Keterangan minimal 10 karakter',
                'doc_sid.mimes' => 'Format file harus JPG, PNG, atau PDF',
                'doc_sid.max' => 'Ukuran file maksimal 2MB'
            ]);

            $nik = Auth::guard('karyawan')->user()->nik;

            // Generate kode izin
            $prefix = $request->status == 'i' ? 'IZ' : ($request->status == 's' ? 'SK' : 'CT');
            $kode_izin = $prefix . date('Ymd') . rand(1000, 9999);

            // Cek apakah sudah ada pengajuan di tanggal yang sama
            $cek = DB::table('pengajuan_izin')
                ->where('nik', $nik)
                ->where(function ($query) use ($request) {
                    $query->whereBetween('tgl_izin_dari', [$request->tgl_izin_dari, $request->tgl_izin_sampai])
                        ->orWhereBetween('tgl_izin_sampai', [$request->tgl_izin_dari, $request->tgl_izin_sampai])
                        ->orWhere(function ($q) use ($request) {
                            $q->where('tgl_izin_dari', '<=', $request->tgl_izin_dari)
                                ->where('tgl_izin_sampai', '>=', $request->tgl_izin_sampai);
                        });
                })
                ->whereIn('status_approved', [0, 1]) // Cek yang pending atau approved
                ->count();

            if ($cek > 0) {
                Log::warning('Duplicate izin attempt', [
                    'nik' => $nik,
                    'tanggal' => $request->tgl_izin_dari . ' - ' . $request->tgl_izin_sampai
                ]);
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Anda sudah mengajukan izin pada tanggal tersebut');
            }

            DB::beginTransaction();

            // Handle upload dokumen (jika ada)
            $doc_sid = null;
            if ($request->hasFile('doc_sid')) {
                $file = $request->file('doc_sid');
                $doc_sid = $kode_izin . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/uploads/sid', $doc_sid);

                Log::info('Document uploaded', ['filename' => $doc_sid]);
            }

            // Simpan data
            $data = [
                'kode_izin' => $kode_izin,
                'nik' => $nik,
                'tgl_izin_dari' => $request->tgl_izin_dari,
                'tgl_izin_sampai' => $request->tgl_izin_sampai,
                'status' => $request->status,
                'keterangan' => $request->keterangan,
                'doc_sid' => $doc_sid,
                'kode_cuti' => $request->kode_cuti ?? null,
                'status_approved' => 0, // 0 = pending
                'created_at' => now(),
                'updated_at' => now()
            ];

            $simpan = DB::table('pengajuan_izin')->insert($data);

            DB::commit();

            if ($simpan) {
                Log::info('Pengajuan izin berhasil disimpan', [
                    'kode_izin' => $kode_izin,
                    'nik' => $nik
                ]);

                return redirect('/presensi/izin')->with('success', 'Pengajuan izin berhasil dikirim dan menunggu persetujuan');
            } else {
                DB::rollBack();
                return redirect('/presensi/izin')->with('error', 'Gagal menyimpan pengajuan izin');
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation error', ['errors' => $e->errors()]);
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Data tidak valid. Periksa kembali form Anda.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('IzinKaryawan@store Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect('/presensi/izin')->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail pengajuan izin
     */
    public function show($kode_izin)
    {
        try {
            $nik = Auth::guard('karyawan')->user()->nik;

            $dataizin = DB::table('pengajuan_izin')
                ->leftJoin('pengajuan_cuti', 'pengajuan_izin.kode_cuti', '=', 'pengajuan_cuti.kode_cuti')
                ->where('pengajuan_izin.kode_izin', $kode_izin)
                ->where('pengajuan_izin.nik', $nik)
                ->first();

            if (!$dataizin) {
                Log::warning('Izin not found', [
                    'kode_izin' => $kode_izin,
                    'nik' => $nik
                ]);
                return redirect('/presensi/izin')->with('error', 'Data tidak ditemukan');
            }

            return view('karyawan.izin.show', compact('dataizin'));
        } catch (Exception $e) {
            Log::error('IzinKaryawan@show Error: ' . $e->getMessage());
            return redirect('/presensi/izin')->with('error', 'Terjadi kesalahan saat memuat detail');
        }
    }

    /**
     * Menghapus pengajuan izin (hanya jika status masih pending)
     */
    public function destroy($kode_izin)
    {
        try {
            $nik = Auth::guard('karyawan')->user()->nik;

            // Cek data izin
            $cekdataizin = DB::table('pengajuan_izin')
                ->where('kode_izin', $kode_izin)
                ->where('nik', $nik)
                ->first();

            if (!$cekdataizin) {
                return redirect('/presensi/izin')->with('error', 'Data tidak ditemukan');
            }

            // Cek status, hanya bisa hapus jika masih pending
            if ($cekdataizin->status_approved != 0) {
                return redirect('/presensi/izin')->with('error', 'Tidak dapat menghapus izin yang sudah diproses');
            }

            DB::beginTransaction();

            // Hapus file dokumen jika ada
            if (!empty($cekdataizin->doc_sid)) {
                Storage::delete('public/uploads/sid/' . $cekdataizin->doc_sid);
            }

            // Hapus data
            DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->delete();

            DB::commit();

            Log::info('Pengajuan izin berhasil dihapus', [
                'kode_izin' => $kode_izin,
                'nik' => $nik
            ]);

            return redirect('/presensi/izin')->with('success', 'Pengajuan izin berhasil dihapus');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('IzinKaryawan@destroy Error: ' . $e->getMessage());
            return redirect('/presensi/izin')->with('error', 'Gagal menghapus pengajuan izin');
        }
    }

    /**
     * Get statistik izin karyawan
     */
    public function getStatistik(Request $request)
    {
        try {
            $nik = Auth::guard('karyawan')->user()->nik;
            $tahun = $request->tahun ?? date('Y');

            $statistik = DB::table('pengajuan_izin')
                ->selectRaw('
                    COUNT(*) as total,
                    COUNT(CASE WHEN status = "i" THEN 1 END) as total_izin,
                    COUNT(CASE WHEN status = "s" THEN 1 END) as total_sakit,
                    COUNT(CASE WHEN status = "c" THEN 1 END) as total_cuti,
                    COUNT(CASE WHEN status_approved = 0 THEN 1 END) as pending,
                    COUNT(CASE WHEN status_approved = 1 THEN 1 END) as disetujui,
                    COUNT(CASE WHEN status_approved = 2 THEN 1 END) as ditolak
                ')
                ->where('nik', $nik)
                ->whereRaw('YEAR(tgl_izin_dari) = ?', [$tahun])
                ->first();

            return response()->json([
                'success' => true,
                'data' => $statistik
            ]);
        } catch (Exception $e) {
            Log::error('IzinKaryawan@getStatistik Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik'
            ], 500);
        }
    }

    /**
     * Download dokumen surat izin sakit
     */
    public function downloadDokumen($kode_izin)
    {
        try {
            $nik = Auth::guard('karyawan')->user()->nik;

            $izin = DB::table('pengajuan_izin')
                ->where('kode_izin', $kode_izin)
                ->where('nik', $nik)
                ->first();

            if (!$izin || empty($izin->doc_sid)) {
                return redirect()->back()->with('error', 'Dokumen tidak ditemukan');
            }

            $path = storage_path('app/public/uploads/sid/' . $izin->doc_sid);

            if (!file_exists($path)) {
                return redirect()->back()->with('error', 'File tidak ditemukan');
            }

            return response()->download($path);
        } catch (Exception $e) {
            Log::error('IzinKaryawan@downloadDokumen Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengunduh dokumen');
        }
    }
}
