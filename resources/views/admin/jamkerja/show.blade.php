@extends('admin.layouts.admin')

@section('title', 'Detail Jam Kerja')
@section('page-title', 'Detail Jam Kerja')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Detail Jam Kerja</h5>
                    <div>
                        <a href="{{ route('panel.jamkerja.edit', $jamkerja->kode_jam_kerja) }}" class="btn btn-warning btn-sm">
                            <i class="mdi mdi-pencil"></i> Edit
                        </a>
                        <a href="{{ route('panel.jamkerja.index') }}" class="btn btn-secondary btn-sm">
                            <i class="mdi mdi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Master Info -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h6 class="text-muted mb-2">Kode Jam Kerja</h6>
                                <h4 class="mb-0">
                                    <span class="badge bg-warning text-dark">{{ $jamkerja->kode_jam_kerja }}</span>
                                </h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h6 class="text-muted mb-2">Nama Jam Kerja</h6>
                                <h5 class="mb-0">{{ $jamkerja->nama_jam_kerja }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h6 class="text-muted mb-2">Tipe</h6>
                                <h5 class="mb-0">
                                    @if($jamkerja->tipe_jam_kerja == 'multi_shift')
                                    <span class="badge bg-info">
                                        <i class="mdi mdi-layers"></i> Multi Shift
                                    </span>
                                    @else
                                    <span class="badge bg-secondary">
                                        <i class="mdi mdi-clock-outline"></i> Regular
                                    </span>
                                    @endif
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h6 class="text-muted mb-2">Lintas Hari</h6>
                                <h5 class="mb-0">
                                    @if($jamkerja->lintashari == '1')
                                    <span class="badge bg-info">
                                        <i class="mdi mdi-weather-night"></i> Ya
                                    </span>
                                    @else
                                    <span class="badge bg-secondary">Tidak</span>
                                    @endif
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Detail Jadwal -->
                @if($jamkerja->tipe_jam_kerja == 'multi_shift')
                <!-- Multi Shift Display -->
                <h6 class="mb-3">
                    <i class="mdi mdi-layers text-info"></i>
                    Jadwal Multi Shift ({{ $jamkerja->shifts->count() }} Shift per Hari)
                </h6>

                <div class="row">
                    @foreach($jamkerja->shifts as $shift)
                    <div class="col-md-6 mb-3">
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">
                                    <span class="badge bg-light text-primary me-2">{{ $shift->shift_ke }}</span>
                                    {{ $shift->nama_shift }}
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm table-borderless mb-0">
                                        <tr>
                                            <td width="40%">
                                                <i class="mdi mdi-clock-outline text-primary"></i>
                                                <strong>Awal Jam Masuk</strong>
                                            </td>
                                            <td>:</td>
                                            <td class="text-end">
                                                <span class="badge bg-primary">
                                                    {{ date('H:i', strtotime($shift->awal_jam_masuk)) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <i class="mdi mdi-clock text-success"></i>
                                                <strong>Jam Masuk</strong>
                                            </td>
                                            <td>:</td>
                                            <td class="text-end">
                                                <span class="badge bg-success">
                                                    {{ date('H:i', strtotime($shift->jam_masuk)) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <i class="mdi mdi-clock-alert text-warning"></i>
                                                <strong>Akhir Jam Masuk</strong>
                                            </td>
                                            <td>:</td>
                                            <td class="text-end">
                                                <span class="badge bg-warning text-dark">
                                                    {{ date('H:i', strtotime($shift->akhir_jam_masuk)) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <i class="mdi mdi-clock-end text-danger"></i>
                                                <strong>Jam Pulang</strong>
                                            </td>
                                            <td>:</td>
                                            <td class="text-end">
                                                <span class="badge bg-danger">
                                                    {{ date('H:i', strtotime($shift->jam_pulang)) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <div class="mt-2 p-2 bg-light rounded">
                                                    <small class="text-muted">
                                                        <i class="mdi mdi-information-outline"></i>
                                                        Durasi:
                                                        <strong>
                                                            {{ \Carbon\Carbon::parse($shift->jam_masuk)->diffInHours(\Carbon\Carbon::parse($shift->jam_pulang)) }} jam
                                                            {{ \Carbon\Carbon::parse($shift->jam_masuk)->diffInMinutes(\Carbon\Carbon::parse($shift->jam_pulang)) % 60 }} menit
                                                        </strong>
                                                    </small>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Summary for Multi Shift -->
                <div class="alert alert-info mt-3">
                    <i class="mdi mdi-information-outline"></i>
                    <strong>Informasi:</strong>
                    Jam kerja ini memiliki <strong>{{ $jamkerja->shifts->count() }} shift</strong> dalam 1 hari.
                    Karyawan yang menggunakan jam kerja ini harus melakukan presensi di setiap shift yang terjadwal.
                </div>

                @else
                <!-- Regular Display -->
                <h6 class="mb-3">
                    <i class="mdi mdi-clock-outline text-secondary"></i>
                    Jadwal Jam Kerja Regular
                </h6>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Waktu Masuk</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless mb-0">
                                    <tr>
                                        <td width="50%">
                                            <i class="mdi mdi-clock-outline text-primary"></i>
                                            <strong>Awal Jam Masuk</strong>
                                        </td>
                                        <td>:</td>
                                        <td class="text-end">
                                            <span class="badge bg-primary">
                                                {{ date('H:i', strtotime($jamkerja->awal_jam_masuk)) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <i class="mdi mdi-clock text-success"></i>
                                            <strong>Jam Masuk</strong>
                                        </td>
                                        <td>:</td>
                                        <td class="text-end">
                                            <span class="badge bg-success">
                                                {{ date('H:i', strtotime($jamkerja->jam_masuk)) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <i class="mdi mdi-clock-alert text-warning"></i>
                                            <strong>Akhir Jam Masuk</strong>
                                        </td>
                                        <td>:</td>
                                        <td class="text-end">
                                            <span class="badge bg-warning text-dark">
                                                {{ date('H:i', strtotime($jamkerja->akhir_jam_masuk)) }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Waktu Pulang</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless mb-0">
                                    <tr>
                                        <td width="50%">
                                            <i class="mdi mdi-clock-end text-danger"></i>
                                            <strong>Jam Pulang</strong>
                                        </td>
                                        <td>:</td>
                                        <td class="text-end">
                                            <span class="badge bg-danger">
                                                {{ date('H:i', strtotime($jamkerja->jam_pulang)) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <div class="mt-2 p-2 bg-light rounded">
                                                <small class="text-muted">
                                                    <i class="mdi mdi-information-outline"></i>
                                                    Total Durasi:
                                                    <strong>
                                                        {{ \Carbon\Carbon::parse($jamkerja->jam_masuk)->diffInHours(\Carbon\Carbon::parse($jamkerja->jam_pulang)) }} jam
                                                    </strong>
                                                </small>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Usage Info -->
                @if($total_konfigurasi_dept > 0)
                <hr>
                <div class="alert alert-warning">
                    <i class="mdi mdi-alert-circle-outline"></i>
                    <strong>Penggunaan:</strong>
                    Jam kerja ini sedang digunakan oleh <strong>{{ $total_konfigurasi_dept }}</strong> konfigurasi departemen.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection