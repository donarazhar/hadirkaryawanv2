<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PresensiController;
use Illuminate\Support\Facades\Route;


Route::middleware(['guest:karyawan'])->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/proseslogin', [AuthController::class, 'proseslogin']);
});

// Middleware membuka halaman harus dengan LOGIN dahulu
Route::middleware(['auth:karyawan'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/proseslogout', [AuthController::class, 'proseslogout']);

    // Presensi
    Route::get('/presensi/create', [PresensiController::class, 'create'])->name('presensi.create');
    Route::post('/presensi/store', [PresensiController::class, 'store'])->name('presensi.store');

    // Edit Profile
    Route::get('/editprofile', [PresensiController::class, 'editprofile'])->name('presensi.editprofile');
    Route::post('/presensi/{nik}/updateprofile', [PresensiController::class, 'updateprofile']);

    // Histori
    Route::get('/presensi/histori', [PresensiController::class, 'histori'])->name('presensi.histori');
    Route::post('/gethistori', [PresensiController::class, 'gethistori'])->name('presensi.gethistori');

    // Izin
    Route::get('/presensi/izin', [PresensiController::class, 'izin'])->name('presensi.izin');
    Route::get('/presensi/buatizin', [PresensiController::class, 'buatizin'])->name('presensi.buatizin');
    Route::post('/presensi/storeizin', [PresensiController::class, 'storeizin']);
});

Route::middleware(['guest:user'])->group(function () {
    Route::get('/panel', function () {
        return view('auth.loginadmin');
    })->name('panel');
    Route::post('/prosesloginadmin', [AuthController::class, 'prosesloginadmin']);
});

Route::middleware(['auth:user'])->group(function () {
    Route::get('/panel/proseslogoutadmin', [AuthController::class, 'proseslogoutadmin']);
    Route::get('/panel/dashboardadmin', [DashboardController::class, 'dashboardadmin'])->name('panel.dashboardadmin');
});
