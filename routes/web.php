<?php

use App\Http\Controllers\Admin\AuthAdminController;

// ==================== KARYAWAN CONTROLLERS ====================
use App\Http\Controllers\Admin\CabangController;
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\DepartemenController;
use App\Http\Controllers\Admin\IzinSakitController;
use App\Http\Controllers\Admin\JamKerjaController;
use App\Http\Controllers\Admin\KaryawanAdminController;

// ==================== ADMIN CONTROLLERS ====================
use App\Http\Controllers\Admin\KonfigurasiJkDeptController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\MonitoringController;
use App\Http\Controllers\Admin\RekapController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardKaryawanController;
use App\Http\Controllers\FaceEnrollmentController;
use App\Http\Controllers\HistoryKaryawanController;
use App\Http\Controllers\IzinKaryawanController;
use App\Http\Controllers\PresensiKaryawanController;
use App\Http\Controllers\ProfileKaryawanController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes - Hadir Karyawan YPI Al Azhar
|--------------------------------------------------------------------------
*/

// Root redirect
Route::get('/', fn() => redirect()->route('login'));

// ========================================
// KARYAWAN ROUTES
// ========================================

Route::middleware('guest:karyawan')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/proseslogin', [AuthController::class, 'proseslogin'])->name('proseslogin');
});

Route::middleware('auth:karyawan')->group(function () {
    Route::post('/proseslogout', [AuthController::class, 'proseslogout'])->name('proseslogout');
    Route::get('/dashboard', [DashboardKaryawanController::class, 'index'])->name('dashboard');

    // Presensi
    Route::controller(PresensiKaryawanController::class)->prefix('presensi')->name('presensi.')->group(function () {
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
    });

    // Show Map Presensi (bisa diakses karyawan dan admin)
    Route::post('/tampilkanpeta', [PresensiKaryawanController::class, 'showMap'])->name('presensi.showmap');


    // Histori
    Route::controller(HistoryKaryawanController::class)->prefix('presensi/histori')->name('histori.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/data', 'gethistori')->name('data'); // ROUTE BARU
        Route::get('/statistik', 'getStatistik')->name('statistik');
        Route::get('/export-excel', 'exportExcel')->name('export');
    });

    Route::post('/gethistori', [HistoryKaryawanController::class, 'gethistori']);



    // Izin
    Route::controller(IzinKaryawanController::class)->prefix('presensi/izin')->name('izin.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/buat', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/{kode_izin}/show', 'show')->name('show');
        Route::delete('/{kode_izin}', 'destroy')->name('delete');
        Route::get('/statistik', 'getStatistik')->name('statistik');
        Route::post('/cek-pengajuan', 'cekPengajuan')->name('cekPengajuan');
        Route::get('/{kode_izin}/download', 'downloadDokumen')->name('download');
    });

    Route::get('/presensi/buatizin', [IzinKaryawanController::class, 'create']);

    // Profile - PERBAIKI INI
    Route::get('/editprofile', [ProfileKaryawanController::class, 'edit'])->name('profile.edit');
    Route::post('/updateprofile', [ProfileKaryawanController::class, 'update'])->name('profile.update');
    Route::delete('/deleteprofilefoto', [ProfileKaryawanController::class, 'deleteFoto'])->name('profile.deleteFoto');
    Route::post('/changepassword', [ProfileKaryawanController::class, 'changePassword'])->name('profile.changePassword');
    Route::get('/getprofile', [ProfileKaryawanController::class, 'getProfile'])->name('profile.data');


    // Face Enrollment & Verification
    Route::prefix('face')->name('face.')->group(function () {
        Route::get('/enrollment', [FaceEnrollmentController::class, 'index'])->name('enrollment');
        Route::post('/enrollment/store', [FaceEnrollmentController::class, 'store'])->name('enrollment.store');
        Route::get('/descriptor', [FaceEnrollmentController::class, 'getDescriptor'])->name('descriptor');
        Route::delete('/delete', [FaceEnrollmentController::class, 'destroy'])->name('delete');
    });

   
});

// ========================================
// ADMIN PANEL ROUTES
// ========================================

Route::prefix('panel')->name('panel.')->group(function () {

    // Guest routes
    Route::middleware('guest:user')->group(function () {
        Route::get('/', [AuthAdminController::class, 'login'])->name('login');
        Route::post('/login', [AuthAdminController::class, 'proseslogin'])->name('login.process');
    });

    // Authenticated routes
    Route::middleware('auth:user')->group(function () {
        Route::post('/logout', [AuthAdminController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('dashboard');

        // Master Data - sudah ada controllernya
        Route::resource('cabang', CabangController::class);
        Route::resource('departemen', DepartemenController::class);
        Route::resource('jamkerja', JamKerjaController::class);
        Route::resource('karyawan', KaryawanAdminController::class);
        Route::resource('konfigurasi-jk-dept', KonfigurasiJkDeptController::class);

        // Monitoring
        Route::controller(MonitoringController::class)->prefix('monitoring')->name('monitoring.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/getpresensi', 'getpresensi')->name('getpresensi');
            Route::post('/showmap', 'showmap')->name('showmap');
        });

        // Laporan
        Route::controller(LaporanController::class)->prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/cetak', 'cetak')->name('cetak');
        });

        // Rekap
        Route::controller(RekapController::class)->prefix('rekap')->name('rekap.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/cetak', 'cetak')->name('cetak');
        });

        // Izin/Sakit
        Route::controller(IzinSakitController::class)->prefix('izinsakit')->name('izinsakit.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/{kode_izin}/approve', 'approve')->name('approve');
            Route::get('/{kode_izin}/cancel', 'cancel')->name('cancel');
        });
    });
});
