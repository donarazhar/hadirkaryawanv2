@extends('admin.layouts.admin')

@section('title', 'Detail Konfigurasi Jam Kerja Departemen')
@section('page-title', 'Detail Konfigurasi Jam Kerja Departemen')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Detail Konfigurasi Jam Kerja Departemen</h5>
                    <div>
                        <a href="{{ route('panel.konfigurasi-jk-dept.edit', $konfigurasi->kode_jk_dept) }}" class="btn btn-warning btn-sm">
                            <i class="mdi mdi-pencil"></i> Edit
                        </a>
                        <a href="{{ route('panel.konfigurasi-jk-dept.index') }}" class="btn btn-secondary btn-sm">
                            <i class="mdi mdi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Master Info -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="text-muted mb-2">Kode Konfigurasi</h6>
                                <h4 class="mb-0">
                                    <span class="badge bg-info">{{ $konfigurasi->kode_jk_dept }}</span>
                                </h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="text-muted mb-2">Cabang</h6>
                                <h5 class="mb-0">
                                    <i class="mdi mdi-office-building text-primary"></i>
                                    {{ $konfigurasi->cabang->nama_cabang ?? 'N/A' }}
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="text-muted mb-2">Departemen</h6>
                                <h5 class="mb-0">
                                    <i class="mdi mdi-file-tree text-success"></i>
                                    {{ $konfigurasi->departemen->nama_dept ?? 'N/A' }}
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Detail Jam Kerja -->
                <h6 class="mb-3">Jadwal Jam Kerja Per Hari</h6>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th width="5%">No</th>
                                <th width="20%">Hari</th>
                                <th width="25%">Nama Jam Kerja</th>
                                <th width="15%">Jam Masuk</th>
                                <th width="15%">Jam Pulang</th>
                                <th width="20%">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($konfigurasi->details as $index => $detail)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <strong class="text-primary">
                                        <i class="mdi mdi-calendar"></i> {{ $detail->hari }}
                                    </strong>
                                </td>
                                <td>
                                    @if($detail->jamKerja)
                                    <span class="badge bg-warning text-dark">{{ $detail->jamKerja->kode_jam_kerja }}</span>
                                    <strong class="ms-2">{{ $detail->jamKerja->nama_jam_kerja }}</strong>
                                    @else
                                    <span class="badge bg-secondary">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($detail->jamKerja)
                                    <i class="mdi mdi-clock-in text-success"></i>
                                    {{ date('H:i', strtotime($detail->jamKerja->jam_masuk)) }}
                                    @else
                                    -
                                    @endif
                                </td>
                                <td>
                                    @if($detail->jamKerja)
                                    <i class="mdi mdi-clock-out text-danger"></i>
                                    {{ date('H:i', strtotime($detail->jamKerja->jam_pulang)) }}
                                    @else
                                    -
                                    @endif
                                </td>
                                <td>
                                    @if($detail->jamKerja)
                                    @if($detail->jamKerja->lintashari == '1')
                                    <span class="badge bg-info">
                                        <i class="mdi mdi-weather-night"></i> Lintas Hari
                                    </span>
                                    @else
                                    <span class="badge bg-secondary">Normal</span>
                                    @endif
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="mdi mdi-information-outline" style="font-size: 48px; color: #ccc;"></i>
                                    <p class="text-muted mt-2">Tidak ada detail konfigurasi</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Summary -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="mdi mdi-information-outline"></i>
                            <strong>Ringkasan:</strong> Konfigurasi ini memiliki
                            <strong>{{ $konfigurasi->details->count() }} hari kerja</strong> yang dikonfigurasi
                            untuk departemen <strong>{{ $konfigurasi->departemen->nama_dept ?? 'N/A' }}</strong>
                            di cabang <strong>{{ $konfigurasi->cabang->nama_cabang ?? 'N/A' }}</strong>.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection