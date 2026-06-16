<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
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

            // Query dasar — select eksplisit agar kolom 'status' dari pengajuan_cuti
            // tidak menimpa kolom 'status' dari pengajuan_izin (i/s/c)
            $query = DB::table('pengajuan_izin')
                ->leftJoin('pengajuan_cuti', 'pengajuan_izin.kode_cuti', '=', 'pengajuan_cuti.kode_cuti')
                ->select(
                    'pengajuan_izin.*',
                    'pengajuan_cuti.nama_cuti',
                    'pengajuan_cuti.jml_hari'
                )
                ->where('pengajuan_izin.nik', $nik)
                ->orderBy('pengajuan_izin.tgl_izin_dari', 'desc');

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

            $karyawan = Auth::guard('karyawan')->user();
            $is_pimpinan = $karyawan->isPimpinan();

            Log::info('IzinKaryawan@index loaded', [
                'nik' => $nik,
                'is_pimpinan' => $is_pimpinan,
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

            return view('karyawan.izin.index', compact('dataizin', 'namabulan', 'is_pimpinan'));
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

            // Meneruskan variabel $cuti ke view
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
            $kode_izin = $prefix . date('Ymd') . strtoupper(\Illuminate\Support\Str::random(5));

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

            // Izin (i) dan Sakit (s) langsung disetujui otomatis, Cuti (c) perlu persetujuan
            $statusApproved = in_array($request->status, ['i', 's']) ? 1 : 0;

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
                'status_approved' => $statusApproved, // 1 = auto-approved (izin/sakit), 0 = pending (cuti)
                'status_approved_atasan' => in_array($request->status, ['i', 's']) ? 1 : 0, // sama, auto-approved untuk izin/sakit
                'created_at' => now(),
                'updated_at' => now()
            ];

            $simpan = DB::table('pengajuan_izin')->insert($data);

            DB::commit();

            if ($simpan) {
                Log::info('Pengajuan berhasil disimpan', [
                    'kode_izin' => $kode_izin,
                    'nik' => $nik,
                    'status_approved' => $statusApproved
                ]);

                $karyawan = \App\Models\Karyawan::where('nik', $nik)->first();
                $nama_karyawan = $karyawan ? $karyawan->nama_lengkap : 'Karyawan';

                $tipe = $request->status == 'i' ? 'Izin' : ($request->status == 's' ? 'Sakit' : 'Cuti');

                if (in_array($request->status, ['i', 's'])) {
                    // Izin/Sakit: notifikasi ke admin saja sebagai informasi
                    $pesan = "Halo Admin,\n\nTerdapat laporan *{$tipe}* baru (disetujui otomatis).\n\n*NIK:* {$nik}\n*Nama:* {$nama_karyawan}\n*Tgl:* {$request->tgl_izin_dari} s/d {$request->tgl_izin_sampai}\n*Alasan:* {$request->keterangan}\n\nTerima kasih.";
                    \App\Services\WhatsAppService::send('081234567890', $pesan);

                    return redirect('/presensi/izin')->with('success', "Pengajuan {$tipe} berhasil dikirim dan telah disetujui otomatis");
                } else {
                    // Cuti: notifikasi ke HRD/Admin untuk diproses
                    $pesan = "Halo Admin,\n\nTerdapat pengajuan *{$tipe}* baru yang perlu disetujui.\n\n*NIK:* {$nik}\n*Nama:* {$nama_karyawan}\n*Tgl:* {$request->tgl_izin_dari} s/d {$request->tgl_izin_sampai}\n*Alasan:* {$request->keterangan}\n\nMohon segera diproses di panel admin.\nTerima kasih.";
                    \App\Services\WhatsAppService::send('081234567890', $pesan);

                    return redirect('/presensi/izin')->with('success', 'Pengajuan Cuti berhasil dikirim dan sedang menunggu persetujuan');
                }
            } else {
                DB::rollBack();
                return redirect('/presensi/izin')->with('error', 'Gagal menyimpan pengajuan');
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
                ->select(
                    'pengajuan_izin.*',
                    'pengajuan_cuti.nama_cuti',
                    'pengajuan_cuti.jml_hari'
                )
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

            // Cek status: cuti hanya bisa dihapus jika masih pending (status_approved == 0)
            // Izin & Sakit (auto-approved) bisa dihapus kapan saja
            if ($cekdataizin->status == 'c' && $cekdataizin->status_approved != 0) {
                return redirect('/presensi/izin')->with('error', 'Tidak dapat menghapus cuti yang sudah diproses');
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
     * Cek apakah ada pengajuan aktif pada rentang tanggal (untuk validasi form AJAX)
     */
    public function cekPengajuan(Request $request)
    {
        try {
            $nik = Auth::guard('karyawan')->user()->nik;

            $request->validate([
                'tgl_izin_dari'   => 'required|date',
                'tgl_izin_sampai' => 'required|date|after_or_equal:tgl_izin_dari',
            ]);

            $ada = DB::table('pengajuan_izin')
                ->where('nik', $nik)
                ->where(function ($q) use ($request) {
                    $q->whereBetween('tgl_izin_dari',  [$request->tgl_izin_dari, $request->tgl_izin_sampai])
                      ->orWhereBetween('tgl_izin_sampai', [$request->tgl_izin_dari, $request->tgl_izin_sampai])
                      ->orWhere(function ($q2) use ($request) {
                          $q2->where('tgl_izin_dari',  '<=', $request->tgl_izin_dari)
                             ->where('tgl_izin_sampai', '>=', $request->tgl_izin_sampai);
                      });
                })
                ->whereIn('status_approved', [0, 1]) // pending atau approved
                ->exists();

            return response()->json([
                'success' => true,
                'ada'     => $ada,
                'pesan'   => $ada ? 'Anda sudah memiliki pengajuan pada rentang tanggal tersebut.' : null,
            ]);
        } catch (Exception $e) {
            Log::error('IzinKaryawan@cekPengajuan Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memeriksa pengajuan'
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

    /**
     * Print Surat Cuti (hanya jika sudah disetujui Pimpinan)
     */
    public function printSuratCuti($kode_izin)
    {
        try {
            $nik = Auth::guard('karyawan')->user()->nik;

            $izin = DB::table('pengajuan_izin')
                ->leftJoin('pengajuan_cuti', 'pengajuan_izin.kode_cuti', '=', 'pengajuan_cuti.kode_cuti')
                ->select('pengajuan_izin.*', 'pengajuan_cuti.nama_cuti', 'pengajuan_cuti.jml_hari')
                ->where('pengajuan_izin.kode_izin', $kode_izin)
                ->where('pengajuan_izin.nik', $nik)
                ->first();

            if (!$izin) {
                return redirect('/presensi/izin')->with('error', 'Data tidak ditemukan');
            }

            // Hanya cuti yang sudah disetujui yang bisa dicetak
            if ($izin->status !== 'c') {
                return redirect()->back()->with('error', 'Hanya pengajuan Cuti yang dapat dicetak suratnya');
            }

            if ($izin->status_approved != 1) {
                return redirect()->back()->with('error', 'Surat Cuti hanya dapat dicetak setelah disetujui Pimpinan');
            }

            // Ambil data karyawan + relasi departemen & cabang
            $karyawan = DB::table('karyawan')
                ->leftJoin('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
                ->leftJoin('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang')
                ->select(
                    'karyawan.*',
                    'departemen.nama_dept',
                    'cabang.nama_cabang'
                )
                ->where('karyawan.nik', $nik)
                ->first();

            return view('karyawan.izin.print_cuti', compact('izin', 'karyawan'));
        } catch (Exception $e) {
            Log::error('IzinKaryawan@printSuratCuti Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat surat cuti');
        }
    }

    /**
     * Menampilkan daftar cuti pegawai untuk Pimpinan
     */
    public function ajuanPegawai(Request $request)
    {
        $karyawan = Auth::guard('karyawan')->user();
        if (!$karyawan->isPimpinan()) {
            return redirect('/presensi/izin')->with(['error' => 'Akses ditolak. Anda bukan pimpinan.']);
        }

        $pimpinanData = $karyawan->getPimpinanDetail();

        // Ambil NIK karyawan di bawah pimpinan ini
        $bawahanNiks = \App\Models\Karyawan::where('kode_dept', $pimpinanData->kode_dept)
            ->where('kode_cabang', $pimpinanData->kode_cabang)
            ->where('nik', '!=', $karyawan->nik)
            ->pluck('nik');

        $query = DB::table('pengajuan_izin')
            ->join('karyawan', 'pengajuan_izin.nik', '=', 'karyawan.nik')
            ->leftJoin('pengajuan_cuti', 'pengajuan_izin.kode_cuti', '=', 'pengajuan_cuti.kode_cuti')
            ->select(
                'pengajuan_izin.*',
                'karyawan.nama_lengkap',
                'karyawan.jabatan',
                'pengajuan_cuti.nama_cuti',
                'pengajuan_cuti.jml_hari'
            )
            ->whereIn('pengajuan_izin.nik', $bawahanNiks)
            ->where('pengajuan_izin.status', 'c'); // Hanya Cuti

        if (!empty($request->bulan) && !empty($request->tahun)) {
            $query->whereRaw('MONTH(pengajuan_izin.tgl_izin_dari) = ?', [$request->bulan])
                ->whereRaw('YEAR(pengajuan_izin.tgl_izin_dari) = ?', [$request->tahun]);
        }

        $dataajuan = $query->orderBy('pengajuan_izin.tgl_izin_dari', 'desc')->get();

        return view('karyawan.izin.ajuan_pegawai', compact('dataajuan'));
    }

    /**
     * Setujui atau Tolak cuti pegawai oleh Pimpinan via Mobile
     */
    public function approvePegawai(Request $request, $kode_izin)
    {
        $karyawan = Auth::guard('karyawan')->user();
        if (!$karyawan->isPimpinan()) {
            return redirect('/presensi/izin')->with(['error' => 'Akses ditolak. Anda bukan pimpinan.']);
        }

        $request->validate([
            'status_approved' => 'required|in:1,2',
        ]);

        try {
            DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->update([
                'status_approved' => $request->status_approved, // 1: Disetujui, 2: Ditolak
                'status_approved_atasan' => $request->status_approved, // Pimpinan -> Final
            ]);

            return redirect()->back()->with(['success' => 'Status pengajuan cuti pegawai berhasil diperbarui.']);
        } catch (\Exception $e) {
            Log::error('Error approving pegawai cuti via mobile: ' . $e->getMessage());
            return redirect()->back()->with(['error' => 'Terjadi kesalahan saat menyimpan data.']);
        }
    }
}
