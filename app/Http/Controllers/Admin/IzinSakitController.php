<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanIzin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IzinSakitController extends Controller
{
    public function index(Request $request)
    {
        $query = PengajuanIzin::with('karyawan.departemen')
            ->select('pengajuan_izin.*')
            ->join('karyawan', 'pengajuan_izin.nik', '=', 'karyawan.nik');

        // Filter Tanggal
        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('tgl_izin_dari', [$request->dari, $request->sampai]);
        }

        // Filter NIK/Nama
        if ($request->filled('nik_nama')) {
            $query->where(function($q) use ($request) {
                $q->where('karyawan.nik', 'like', '%' . $request->nik_nama . '%')
                  ->orWhere('karyawan.nama_lengkap', 'like', '%' . $request->nik_nama . '%');
            });
        }

        // Filter Status Approved
        if ($request->filled('status_approved')) {
            $query->where('status_approved', $request->status_approved);
        }

        $izinsakit = $query->orderBy('tgl_izin_dari', 'desc')->paginate(15);

        return view('admin.izinsakit.index', compact('izinsakit'));
    }

    public function approve(Request $request, $kode_izin)
    {
        $request->validate([
            'status_approved' => 'required|in:1,2',
            'catatan_admin' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $izin = PengajuanIzin::findOrFail($kode_izin);
            
            // Logika khusus Cuti
            if ($izin->status == 'c' && $request->status_approved == '1') {
                // Kurangi jatah cuti jika disetujui (opsional/tergantung business logic yang ada)
                // Sementara dibiarkan mengikuti logic yang sudah ada di aplikasi
            }

            $izin->update([
                'status_approved' => $request->status_approved,
                'catatan_admin' => $request->catatan_admin
            ]);

            // Notifikasi WA ke Karyawan
            $karyawan = \App\Models\Karyawan::where('nik', $izin->nik)->first();
            if ($karyawan && $karyawan->no_hp) {
                $status_teks = $request->status_approved == '1' ? 'DISETUJUI' : 'DITOLAK';
                $tipe = $izin->status == 'i' ? 'Izin' : ($izin->status == 's' ? 'Sakit' : 'Cuti');
                $pesan = "Halo {$karyawan->nama_lengkap},\n\nPengajuan *{$tipe}* Anda untuk tanggal {$izin->tgl_izin_dari} s/d {$izin->tgl_izin_sampai} telah *{$status_teks}* oleh Admin.\n";
                if ($request->catatan_admin) {
                    $pesan .= "Catatan: {$request->catatan_admin}\n";
                }
                $pesan .= "\nTerima kasih.";

                \App\Services\WhatsAppService::send($karyawan->no_hp, $pesan);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Status pengajuan berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function cancel($kode_izin)
    {
        try {
            $izin = PengajuanIzin::findOrFail($kode_izin);
            
            $izin->update([
                'status_approved' => '0',
                'catatan_admin' => null
            ]);

            return redirect()->back()->with('success', 'Status pengajuan berhasil dibatalkan (dikembalikan ke status Menunggu)');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
