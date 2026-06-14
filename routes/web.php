<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AuthAdminController;
use App\Http\Controllers\Admin\CabangController;
use App\Http\Controllers\Admin\CutiController;
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\DepartemenController;
use App\Http\Controllers\Admin\FaceVerificationController;
use App\Http\Controllers\Admin\IzinSakitController;
use App\Http\Controllers\Admin\JamKerjaController;
use App\Http\Controllers\Admin\KaryawanAdminController;
use App\Http\Controllers\Admin\KonfigurasiJkDeptController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\MonitoringController;
use App\Http\Controllers\Admin\PresensiFaceAdminController;
use App\Http\Controllers\Admin\RekapController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Karyawan\DashboardKaryawanController;
use App\Http\Controllers\Karyawan\FaceEnrollmentController;
use App\Http\Controllers\Karyawan\HistoryKaryawanController;
use App\Http\Controllers\Karyawan\IzinKaryawanController;
use App\Http\Controllers\Karyawan\PresensiKaryawanController;
use App\Http\Controllers\Karyawan\ProfileKaryawanController;
use App\Http\Controllers\Auth\GoogleController;
use Illuminate\Support\Facades\Route;


// Root redirect
Route::get('/', fn() => redirect()->route('login'));

// Google Auth Routes
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('auth.google.callback');

// ========================================
// KARYAWAN ROUTES
// ========================================

Route::middleware('guest:karyawan')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/proseslogin', [AuthController::class, 'proseslogin'])->name('proseslogin')->middleware('throttle:5,1');
});

Route::middleware('auth:karyawan')->group(function () {
    Route::post('/proseslogout', [AuthController::class, 'proseslogout'])->name('proseslogout');
    Route::get('/dashboard', [DashboardKaryawanController::class, 'index'])->name('dashboard');

    // Presensi
    Route::controller(PresensiKaryawanController::class)->prefix('presensi')->name('presensi.')->group(function () {
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store')->middleware('throttle:10,1');
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
        Route::post('/login', [AuthAdminController::class, 'proseslogin'])->name('login.process')->middleware('throttle:5,1');
    });

    // Authenticated routes
    Route::middleware('auth:user')->group(function () {
        Route::post('/logout', [AuthAdminController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('dashboard');

        // Master Data - admin & superadmin
        Route::middleware('role:superadmin,admin')->group(function () {
            Route::resource('cabang', CabangController::class);
            Route::resource('departemen', DepartemenController::class);
            Route::resource('jamkerja', JamKerjaController::class);
            Route::resource('karyawan', KaryawanAdminController::class);
            Route::resource('konfigurasi-jk-dept', KonfigurasiJkDeptController::class);
            Route::resource('cuti', CutiController::class)->except(['create', 'show', 'edit']);
        });

        // Superadmin only
        Route::middleware('role:superadmin')->group(function () {
            Route::resource('user', UserController::class);
            Route::get('/activity-logs', [App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('activity-logs.index');
        });

        // Monitoring
        Route::controller(MonitoringController::class)->prefix('monitoring')->name('monitoring.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/getpresensi', 'getpresensi')->name('getpresensi');
            Route::post('/showmap', 'showmap')->name('showmap');
        });

        // Laporan
        Route::controller(LaporanController::class)->prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/export-pdf', 'exportPdf')->name('export.pdf');
            Route::post('/export-excel', 'exportExcel')->name('export.excel');
            Route::post('/edit', 'edit')->name('edit');
            Route::post('/update', 'update')->name('update');
            Route::post('/getkaryawan', 'getKaryawan')->name('getkaryawan');
            Route::post('/store', 'store')->name('store');
        });

        // Rekap
        Route::controller(RekapController::class)->prefix('rekap')->name('rekap.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/cetak', 'cetak')->name('cetak');
            Route::post('/getkaryawan', 'getKaryawan')->name('getkaryawan');
        });

        // Izin/Sakit (Pimpinan juga butuh untuk Approval Berjenjang)
        Route::middleware('role:superadmin,admin,pimpinan')->group(function () {
            Route::controller(IzinSakitController::class)->prefix('izinsakit')->name('izinsakit.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/{kode_izin}/approve', 'approve')->name('approve');
                Route::post('/{kode_izin}/cancel', 'cancel')->name('cancel');
            });
        });

        // Face Verification Routes (Hanya Admin)
        Route::middleware('role:superadmin,admin')->group(function () {
            Route::prefix('face-verification')->name('face-verification.')->group(function () {

                Route::get('/', [FaceVerificationController::class, 'index'])
                    ->name('index');
                Route::get('/show/{nik}', [FaceVerificationController::class, 'show'])
                    ->name('show');
                Route::put('/activate/{nik}', [FaceVerificationController::class, 'activate'])
                    ->name('activate');
                Route::put('/deactivate/{nik}', [FaceVerificationController::class, 'deactivate'])
                    ->name('deactivate');
                Route::delete('/destroy/{nik}', [FaceVerificationController::class, 'destroy'])
                    ->name('destroy');
                Route::post('/bulk-activate', [FaceVerificationController::class, 'bulkActivate'])
                    ->name('bulk-activate');
                Route::post('/bulk-deactivate', [FaceVerificationController::class, 'bulkDeactivate'])
                    ->name('bulk-deactivate');
                Route::get('/export', [FaceVerificationController::class, 'export'])
                    ->name('export');
                Route::get('/api/stats', [FaceVerificationController::class, 'getStats'])
                    ->name('api.stats');
                Route::get('/image/{nik}', [FaceVerificationController::class, 'viewImage'])
                    ->name('view-image');
            });
        });
    });
});
