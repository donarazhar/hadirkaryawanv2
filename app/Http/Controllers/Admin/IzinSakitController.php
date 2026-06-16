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

        $user = \Illuminate\Support\Facades\Auth::guard('user')->user();
        if ($user && $user->role == 'pimpinan') {
            $query->where('karyawan.kode_cabang', $user->kode_cabang);
        }

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

        // Filter Status Approved — gunakan status_approved sebagai final status untuk semua role
        // (pimpinan approval sekarang langsung update status_approved juga)
        if ($request->filled('status_approved')) {
            $query->where('status_approved', $request->status_approved);
        }

        // Filter Tipe Pengajuan (izin/sakit/cuti)
        if ($request->filled('tipe')) {
            $query->where('pengajuan_izin.status', $request->tipe);
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

            // Izin (i) dan Sakit (s) tidak perlu approval — sudah disetujui otomatis
            if (in_array($izin->status, ['i', 's'])) {
                return redirect()->back()->with('error', 'Izin dan Sakit tidak memerlukan persetujuan — sudah disetujui otomatis.');
            }

            $user = \Illuminate\Support\Facades\Auth::guard('user')->user();
            $userRole = $user ? $user->role : 'admin';

            // Hanya Pimpinan yang bisa approve Cuti
            if ($userRole !== 'pimpinan') {
                return redirect()->back()->with('error', 'Hanya Pimpinan yang berwenang menyetujui pengajuan Cuti.');
            }

            // Pimpinan approve Cuti → langsung final (tidak perlu HRD)
            $izin->update([
                'status_approved_atasan' => $request->status_approved,
                'catatan_atasan'         => $request->catatan_admin,
                'status_approved'        => $request->status_approved, // mirror ke status final
                'catatan_admin'          => $request->catatan_admin,
            ]);

            // Catat log
            $statusTeks = $request->status_approved == '1' ? 'Disetujui' : 'Ditolak';
            \App\Helpers\LogHelper::record(
                'APPROVE_PENGJUAN',
                "Melakukan approval pengajuan Cuti ($statusTeks) dengan kode: $kode_izin"
            );

            // Notifikasi WA ke Karyawan
            $karyawan = \App\Models\Karyawan::where('nik', $izin->nik)->first();
            if ($karyawan && $karyawan->no_hp) {
                $status_teks = $request->status_approved == '1' ? 'DISETUJUI' : 'DITOLAK';
                $pesan = "Halo {$karyawan->nama_lengkap},\n\nPengajuan *Cuti* Anda untuk tanggal {$izin->tgl_izin_dari} s/d {$izin->tgl_izin_sampai} telah *{$status_teks}* oleh Pimpinan.\n";
                if ($request->catatan_admin) {
                    $pesan .= "Catatan: {$request->catatan_admin}\n";
                }
                if ($request->status_approved == '1') {
                    $pesan .= "\nSilakan cetak Surat Cuti melalui aplikasi dan serahkan ke HRD.\n";
                }
                $pesan .= "\nTerima kasih.";

                \App\Services\WhatsAppService::send($karyawan->no_hp, $pesan);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Status pengajuan Cuti berhasil diupdate oleh Pimpinan');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function cancel($kode_izin)
    {
        try {
            $izin = PengajuanIzin::findOrFail($kode_izin);

            // Izin (i) dan Sakit (s) tidak perlu approval — tidak ada status yang perlu di-cancel
            if (in_array($izin->status, ['i', 's'])) {
                return redirect()->back()->with('error', 'Izin dan Sakit tidak memerlukan persetujuan, tidak ada yang perlu dibatalkan.');
            }

            $user = \Illuminate\Support\Facades\Auth::guard('user')->user();
            $userRole = $user ? $user->role : 'admin';

            // Hanya Pimpinan yang bisa cancel Cuti
            if ($userRole !== 'pimpinan') {
                return redirect()->back()->with('error', 'Hanya Pimpinan yang berwenang membatalkan status Cuti.');
            }

            // Reset kedua field status sekaligus (karena pimpinan adalah approver final)
            $izin->update([
                'status_approved_atasan' => '0',
                'catatan_atasan'         => null,
                'status_approved'        => '0',
                'catatan_admin'          => null,
            ]);

            \App\Helpers\LogHelper::record(
                'CANCEL_PENGJUAN',
                "Membatalkan persetujuan pengajuan Cuti dengan kode: $kode_izin"
            );

            return redirect()->back()->with('success', 'Status Cuti berhasil dikembalikan ke Menunggu Persetujuan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
