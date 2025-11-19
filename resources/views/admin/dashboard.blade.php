@extends('admin.layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <i class="mdi mdi-office-building text-primary" style="font-size: 48px;"></i>
                <h3 class="mt-3">{{ $totalCabang ?? 0 }}</h3>
                <p class="text-muted mb-0">Total Cabang</p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <i class="mdi mdi-file-tree text-success" style="font-size: 48px;"></i>
                <h3 class="mt-3">{{ $totalDepartemen ?? 0 }}</h3>
                <p class="text-muted mb-0">Total Departemen</p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <i class="mdi mdi-account-group text-info" style="font-size: 48px;"></i>
                <h3 class="mt-3">{{ $totalKaryawan ?? 0 }}</h3>
                <p class="text-muted mb-0">Total Karyawan</p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <i class="mdi mdi-clock-outline text-warning" style="font-size: 48px;"></i>
                <h3 class="mt-3">{{ $totalJamKerja ?? 0 }}</h3>
                <p class="text-muted mb-0">Total Jam Kerja</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Selamat Datang di Sistem Presensi YPI Al Azhar</h5>
            </div>
            <div class="card-body">
                <p>Anda login sebagai <strong>{{ Auth::guard('user')->user()->name }}</strong></p>
                <p>Sistem ini digunakan untuk mengelola data presensi karyawan YPI Al Azhar yang tersebar di seluruh Indonesia.</p>
            </div>
        </div>
    </div>
</div>
@endsection