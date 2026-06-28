<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Passport dikonfigurasi menggunakan guard 'karyawan', sehingga token diterbitkan untuk model Karyawan
Route::middleware('auth:api')->get('/user', function (Request $request) {
    $karyawan = $request->user('api'); // Explicitly use API guard, otherwise it falls back to session

    if (!$karyawan) {
        return response()->json(['message' => 'Unauthenticated.'], 401);
    }

    $karyawan->load(['organ.unit.branch']);

    $organ  = $karyawan->organ;
    $unit   = $organ?->unit;
    $branch = $unit?->branch;

    return response()->json([
        'id'           => $karyawan->nik,
        'nik_karyawan' => $karyawan->nik,
        'name'         => $karyawan->nama_lengkap,
        'email'        => $karyawan->email ?? ($karyawan->nik . '@alazhar.com'),
        'foto'         => $karyawan->foto,
        'cabang'       => $branch ? [
            'id'   => $branch->id,
            'name' => $branch->name,
        ] : null,
        'organ'        => $organ ? [
            'id'      => $organ->id,
            'name'    => $organ->name,
            'unit_id' => $organ->unit_id,
            'unit'    => $unit ? [
                'id'             => $unit->id,
                'name'           => $unit->name,
                'code'           => $unit->code ?? 'UNIT',
                'is_sekretariat' => $unit->is_sekretariat ?? false,
                'branch_id'      => $unit->branch_id,
            ] : null,
        ] : null,
    ]);
});

// Endpoint untuk menarik daftar karyawan (digunakan oleh aplikasi terhubung seperti Persuratan)
Route::get('/karyawan-list', function () {
    return \Illuminate\Support\Facades\DB::table('karyawan')
        ->select('nik', 'nama_lengkap', 'email')
        ->whereNotNull('email')
        ->where('email', '!=', '')
        ->orderBy('nama_lengkap')
        ->get();
});


