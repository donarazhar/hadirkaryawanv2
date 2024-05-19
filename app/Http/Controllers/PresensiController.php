<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PresensiController extends Controller
{
    public function create()
    {

        $hariini = date("Y-m-d");
        $nik = Auth::guard('karyawan')->user()->nik;

        // Cek karyawan sudah absen masuk atau belum pada hari ini
        $cek = DB::table('presensi')->where('tgl_presensi', $hariini)->where('nik', $nik)->count();

        return view('presensi.create', compact('cek'));
    }

    public function store(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $tgl_presensi = date("Y-m-d");
        $jam = date("H:i:s");
        $lokasi = $request->lokasi;
        $image = $request->image;

        // Mengetes image terkirim atau tidak
        // echo $image;
        // die;

        $folderPath = "public/uploads/absensi/";
        $formatName = $nik . "-" . $tgl_presensi;
        $image_parts = explode(";base64", $image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $formatName . ".png";
        $file = $folderPath . $fileName;

        // Mengupdate data ketika sudah absen masuk untuk mengisi absen pulang
        $cek = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->count();
        if ($cek > 0) {
            $data_pulang = [
                'jam_out' => $jam,
                'foto_out' => $fileName,
                'lokasi_out' => $lokasi
            ];
            $update = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->update($data_pulang);
            if ($update) {
                echo "success|Terimakasih, Hati-Hati Di Jalan|out ";
                Storage::put($file, $image_base64);
            } else {
                echo "error|Maaf Gagal Absen, Hubungi Admin|out";
            }
        } else {

            // Mengisi absen masuk ketika data masih kosongf
            $data = [
                'nik' => $nik,
                'tgl_presensi' => $tgl_presensi,
                'jam_in' => $jam,
                'foto_in' => $fileName,
                'lokasi_in' => $lokasi
            ];
            $simpan = DB::table('presensi')->insert($data);
            if ($simpan) {
                echo "success|Terimakasih, Selamat Bekerja|in";
                Storage::put($file, $image_base64);
            } else {
                echo "error|Maaf Gagal Absen, Hubungi Admin|in";
            }
        }
    }
}
