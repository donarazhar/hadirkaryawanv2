<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    $karyawan = $request->user()->load(['organ.unit.branch']);

    $organ = $karyawan->organ;
    $unit = $organ?->unit;
    $branch = $unit?->branch;

    return response()->json([
        'id'           => $karyawan->nik,
        'nik_karyawan' => $karyawan->nik,
        'name'         => $karyawan->nama_lengkap,
        'email'        => $karyawan->email ?? ($karyawan->nik . '@alazhar.com'),
        'cabang'       => $branch ? [
            'id'   => $branch->id,
            'name' => $branch->name,
        ] : null,
        'organ'        => $organ ? [
            'id'      => $organ->id,
            'name'    => $organ->name,
            'unit_id' => $organ->unit_id,
            'unit'    => $unit ? [
                'id'           => $unit->id,
                'name'         => $unit->name,
                'code'         => $unit->code ?? 'UNIT',
                'is_sekretariat' => $unit->is_sekretariat ?? false,
                'branch_id'    => $unit->branch_id,
            ] : null,
        ] : null,
    ]);
});

