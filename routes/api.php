<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    $karyawan = $request->user()->load(['cabang', 'organ.unit']);
    
    return response()->json([
        'id' => $karyawan->nik, // Map NIK as ID
        'nik_karyawan' => $karyawan->nik,
        'name' => $karyawan->nama_lengkap, // Map nama_lengkap as name
        'email' => $karyawan->email,
        'cabang' => $karyawan->cabang,
        'organ' => $karyawan->organ,
    ]);
});
