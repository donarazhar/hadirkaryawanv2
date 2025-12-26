@extends('admin.layouts.admin')

@section('title', 'Detail Presensi Face')
@section('page-title', 'Detail Presensi Face Recognition')

@push('styles')
<style>
    .badge.bg-blue {
        background-color: #0054a6 !important;
        color: #ffffff !important;
    }

    .badge.bg-purple {
        background-color: #7c3aed !important;
        color: #ffffff !important;
    }

    .badge.bg-teal {
        background-color: #0d9488 !important;
        color: #ffffff !important;
    }

    .info-section {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .info-row {
        display: flex;
        padding: 0.5rem 0;
        border-bottom: 1px solid #e9ecef;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: #495057;
        width: 150px;
        flex-shrink: 0;
    }

    .info-value {
        flex: 1;
        color: #212529;
    }

    .shift-badge-large {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .shift-badge-large.multi {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }

    .shift-badge-large.regular {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        color: white;
    }

    .time-badge-large {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        font-size: 1.25rem;
        font-weight: 700;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="mdi mdi-file-document"></i> Informasi Presensi
                </h5>
            </div>
            <div class="card-body">
                <!-- Shift Badge -->
                @if($presensi->shift_ke)
                <div class="shift-badge-large multi">
                    <i class="mdi mdi-layers"></i>
                    <div>
                        <div style="font-size: 0.75rem; opacity: 0.9;">Multi-Shift</div>
                        <div>Shift {{ $presensi->shift_ke }} - {{ $presensi->nama_shift }}</div>
                    </div>
                </div>

                @if($shift_detail)
                <div class="info-section">
                    <div class="d-flex justify-content-between">
                        <div>
                            <i class="mdi mdi-clock-in text-success"></i>
                            <strong>Jadwal Masuk:</strong> {{ date('H:i', strtotime($shift_detail->jam_masuk)) }}
                        </div>
                        <div>
                            <i class="mdi mdi-clock-out text-danger"></i>
                            <strong>Jadwal Pulang:</strong> {{ date('H:i', strtotime($shift_detail->jam_pulang)) }}
                        </div>
                    </div>
                </div>
                @endif
                @else
                <div class="shift-badge-large regular">
                    <i class="mdi mdi-clock-outline"></i>
                    <div>Shift Regular</div>
                </div>
                @endif

                <!-- Karyawan Info -->
                <h6 class="mb-3"><i class="mdi mdi-account"></i> Data Karyawan</h6>
                <div class="info-section">
                    <div class="info-row">
                        <div class="info-label">NIK</div>
                        <div class="info-value"><code>{{ $presensi->nik }}</code></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Nama Lengkap</div>
                        <div class="info-value">{{ $presensi->karyawan->nama_lengkap ?? '-' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Jabatan</div>
                        <div class="info-value">{{ $presensi->karyawan->jabatan ?? '-' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Departemen</div>
                        <div class="info-value">
                            @if($presensi->karyawan && $presensi->karyawan->departemen)
                            <span class="badge bg-purple">{{ $presensi->karyawan->departemen->nama_dept }}</span>
                            @else - @endif
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Cabang</div>
                        <div class="info-value">
                            @if($presensi->karyawan && $presensi->karyawan->cabang)
                            <span class="badge bg-blue">{{ $presensi->karyawan->cabang->nama_cabang }}</span>
                            @else - @endif
                        </div>
                    </div>
                </div>

                <!-- Waktu Presensi -->
                <h6 class="mb-3 mt-4"><i class="mdi mdi-clock-check"></i> Waktu Presensi</h6>
                <div class="info-section">
                    <div class="info-row">
                        <div class="info-label">Tanggal</div>
                        <div class="info-value">
                            <strong>{{ \Carbon\Carbon::parse($presensi->tanggal)->isoFormat('dddd, D MMMM Y') }}</strong>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Jam Masuk</div>
                        <div class="info-value">
                            @if($presensi->jam_masuk)
                            <span class="time-badge-large bg-success text-white">
                                <i class="mdi mdi-login"></i>
                                {{ \Carbon\Carbon::parse($presensi->jam_masuk)->format('H:i') }}
                            </span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Jam Pulang</div>
                        <div class="info-value">
                            @if($presensi->jam_pulang)
                            <span class="time-badge-large bg-danger text-white">
                                <i class="mdi mdi-logout"></i>
                                {{ \Carbon\Carbon::parse($presensi->jam_pulang)->format('H:i') }}
                            </span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Status & Lokasi -->
                <h6 class="mb-3 mt-4"><i class="mdi mdi-information"></i> Status & Lokasi</h6>
                <div class="info-section">
                    <div class="info-row">
                        <div class="info-label">Status</div>
                        <div class="info-value">
                            @if($presensi->status == 'verified')
                            <span class="badge bg-success">
                                <i class="mdi mdi-check-circle"></i> Verified
                            </span>
                            @else
                            <span class="badge bg-danger">
                                <i class="mdi mdi-close-circle"></i> Failed
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Lokasi GPS</div>
                        <div class="info-value">
                            @if($presensi->lokasi)
                            <code>{{ $presensi->lokasi }}</code>
                            <a href="https://www.google.com/maps?q={{ $presensi->lokasi }}" target="_blank" class="btn btn-sm btn-primary ms-2">
                                <i class="mdi mdi-map-marker"></i> Buka Maps
                            </a>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Actions -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="mdi mdi-cog"></i> Aksi
                </h5>
            </div>
            <div class="card-body">
                <a href="{{ route('panel.presensi-face.edit', $presensi->id) }}" class="btn btn-warning w-100 mb-2">
                    <i class="mdi mdi-pencil"></i> Edit Data
                </a>
                <form action="{{ route('panel.presensi-face.destroy', $presensi->id) }}" method="POST"
                    onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100 mb-2">
                        <i class="mdi mdi-delete"></i> Hapus Data
                    </button>
                </form>
                <a href="{{ route('panel.presensi-face.index') }}" class="btn btn-secondary w-100">
                    <i class="mdi mdi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Metadata -->
        <div class="card mt-3">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="mdi mdi-information"></i> Metadata
                </h5>
            </div>
            <div class="card-body">
                <div class="info-section">
                    <div class="info-row">
                        <div class="info-label">ID</div>
                        <div class="info-value"><code>#{{ $presensi->id }}</code></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Dibuat</div>
                        <div class="info-value">
                            <small>{{ $presensi->created_at ? $presensi->created_at->isoFormat('D MMM Y, HH:mm') : '-' }}</small>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Diupdate</div>
                        <div class="info-value">
                            <small>{{ $presensi->updated_at ? $presensi->updated_at->isoFormat('D MMM Y, HH:mm') : '-' }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection