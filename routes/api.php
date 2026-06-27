<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Endpoint ini menggunakan guard 'api-user' yang berbasis model User (bukan Karyawan)
// karena OAuth token diterbitkan untuk User yang login via web guard
Route::middleware('auth:api-user')->get('/user', function (Request $request) {
    $user = $request->user(); // Ini adalah model User

    // Lookup Karyawan terkait melalui nik_karyawan
    $karyawan = \App\Models\Karyawan::with(['organ.unit.branch'])
        ->where('nik', $user->nik_karyawan)
        ->first();

    if (!$karyawan) {
        return response()->json([
            'id'           => $user->id,
            'nik_karyawan' => $user->nik_karyawan,
            'name'         => $user->name,
            'email'        => $user->email,
            'cabang'       => null,
            'organ'        => null,
        ]);
    }

    $organ  = $karyawan->organ;
    $unit   = $organ?->unit;
    $branch = $unit?->branch;

    return response()->json([
        'id'           => $karyawan->nik,
        'nik_karyawan' => $karyawan->nik,
        'name'         => $karyawan->nama_lengkap,
        'email'        => $user->email ?? ($karyawan->nik . '@alazhar.com'),
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

