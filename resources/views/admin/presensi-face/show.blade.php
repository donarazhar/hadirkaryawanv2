@extends('admin.layouts.admin')

@section('title', 'Detail Presensi Face')
@section('page-title', 'Detail Presensi Face Recognition')

@push('styles')
<style>
    /* Custom badge colors - Fix white text issue */
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

    /* Info card styling */
    .info-label {
        font-weight: 600;
        color: #495057;
        min-width: 180px;
    }

    .info-value {
        color: #212529;
    }

    /* Card icons */
    .card-icon {
        font-size: 1.5rem;
        opacity: 0.8;
    }

    /* Badge with icon */
    .badge-with-icon {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.875rem;
        padding: 0.375rem 0.75rem;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-md-8">
        <!-- Main Info Card -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="mdi mdi-information-outline card-icon"></i> Informasi Presensi
                </h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tbody>
                        <tr>
                            <td class="info-label">
                                <i class="mdi mdi-calendar text-primary"></i> Tanggal
                            </td>
                            <td class="info-value">
                                : <strong>{{ \Carbon\Carbon::parse($presensi->tanggal)->format('d F Y') }}</strong>
                                <small class="text-muted ms-2">({{ \Carbon\Carbon::parse($presensi->tanggal)->isoFormat('dddd') }})</small>
                            </td>
                        </tr>
                        <tr>
                            <td class="info-label">
                                <i class="mdi mdi-card-account-details text-primary"></i> NIK
                            </td>
                            <td class="info-value">
                                : <strong class="text-primary">{{ $presensi->nik }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td class="info-label">
                                <i class="mdi mdi-account text-primary"></i> Nama Karyawan
                            </td>
                            <td class="info-value">
                                : <strong>{{ $presensi->karyawan->nama_lengkap ?? 'N/A' }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td class="info-label">
                                <i class="mdi mdi-briefcase text-primary"></i> Jabatan
                            </td>
                            <td class="info-value">
                                : {{ $presensi->karyawan->jabatan ?? '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="info-label">
                                <i class="mdi mdi-office-building text-primary"></i> Cabang
                            </td>
                            <td class="info-value">
                                : @if($presensi->karyawan && $presensi->karyawan->cabang)
                                <span class="badge badge-with-icon bg-blue">
                                    <i class="mdi mdi-office-building"></i>
                                    {{ $presensi->karyawan->cabang->nama_cabang }}
                                </span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="info-label">
                                <i class="mdi mdi-account-group text-primary"></i> Departemen
                            </td>
                            <td class="info-value">
                                : @if($presensi->karyawan && $presensi->karyawan->departemen)
                                <span class="badge badge-with-icon bg-purple">
                                    <i class="mdi mdi-account-group"></i>
                                    {{ $presensi->karyawan->departemen->nama_dept }}
                                </span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ✅ SHIFT INFO CARD -->
        <div class="card mt-3">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="mdi mdi-clock-outline card-icon"></i> Informasi Shift
                </h5>
            </div>
            <div class="card-body">
                @if($presensi->shift_ke)
                <!-- Multi-Shift -->
                <div class="alert alert-info mb-3">
                    <div class="d-flex align-items-center">
                        <i class="mdi mdi-layers me-2" style="font-size: 24px;"></i>
                        <div>
                            <strong>Multi-Shift Terdeteksi</strong>
                            <div class="small">Karyawan menggunakan jam kerja multi-shift</div>
                        </div>
                    </div>
                </div>

                <table class="table table-borderless mb-0">
                    <tbody>
                        <tr>
                            <td class="info-label">
                                <i class="mdi mdi-view-dashboard text-info"></i> Tipe Shift
                            </td>
                            <td class="info-value">
                                : <span class="badge badge-with-icon bg-info">
                                    <i class="mdi mdi-layers"></i> Multi-Shift
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="info-label">
                                <i class="mdi mdi-numeric text-info"></i> Shift Ke
                            </td>
                            <td class="info-value">
                                : <span class="badge badge-with-icon bg-primary">
                                    <i class="mdi mdi-numeric-{{ $presensi->shift_ke }}-box"></i>
                                    Shift {{ $presensi->shift_ke }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="info-label">
                                <i class="mdi mdi-tag text-info"></i> Nama Shift
                            </td>
                            <td class="info-value">
                                : <strong>{{ $presensi->nama_shift }}</strong>
                            </td>
                        </tr>
                        
                        @if($shiftDetail)
                        <tr>
                            <td class="info-label">
                                <i class="mdi mdi-clock-time-four text-info"></i> Jadwal Shift
                            </td>
                            <td class="info-value">
                                : <span class="badge bg-light text-dark">
                                    {{ \Carbon\Carbon::parse($shiftDetail->jam_masuk)->format('H:i') }} - 
                                    {{ \Carbon\Carbon::parse($shiftDetail->jam_pulang)->format('H:i') }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="info-label">
                                <i class="mdi mdi-timer-sand text-info"></i> Batas Masuk
                            </td>
                            <td class="info-value">
                                : <span class="badge bg-light text-dark">
                                    {{ \Carbon\Carbon::parse($shiftDetail->awal_jam_masuk)->format('H:i') }} - 
                                    {{ \Carbon\Carbon::parse($shiftDetail->akhir_jam_masuk)->format('H:i') }}
                                </span>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>

                @if($presensi->karyawan && $presensi->karyawan->jamKerja && $presensi->karyawan->jamKerja->shifts->count() > 1)
                <!-- Show all shifts for this karyawan -->
                <hr>
                <p class="fw-bold mb-3">
                    <i class="mdi mdi-format-list-bulleted"></i> Semua Shift Karyawan:
                </p>
                <div class="row g-2">
                    @foreach($presensi->karyawan->jamKerja->shifts as $shift)
                    <div class="col-md-6">
                        <div class="card {{ $shift->shift_ke == $presensi->shift_ke ? 'border-primary shadow-sm' : 'border' }}">
                            <div class="card-body py-2 px-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>
                                            <i class="mdi mdi-numeric-{{ $shift->shift_ke }}-box"></i>
                                            Shift {{ $shift->shift_ke }}
                                        </strong> - {{ $shift->nama_shift }}
                                        <div class="small text-muted">
                                            <i class="mdi mdi-clock-outline"></i>
                                            {{ \Carbon\Carbon::parse($shift->jam_masuk)->format('H:i') }} - 
                                            {{ \Carbon\Carbon::parse($shift->jam_pulang)->format('H:i') }}
                                        </div>
                                    </div>
                                    @if($shift->shift_ke == $presensi->shift_ke)
                                    <span class="badge bg-primary">
                                        <i class="mdi mdi-check"></i> Current
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

                @else
                <!-- Regular Shift -->
                <div class="alert alert-secondary mb-3">
                    <div class="d-flex align-items-center">
                        <i class="mdi mdi-clock-outline me-2" style="font-size: 24px;"></i>
                        <div>
                            <strong>Jam Kerja Regular</strong>
                            <div class="small">Karyawan menggunakan jam kerja regular (tidak multi-shift)</div>
                        </div>
                    </div>
                </div>

                <table class="table table-borderless mb-0">
                    <tbody>
                        <tr>
                            <td class="info-label">
                                <i class="mdi mdi-view-dashboard text-secondary"></i> Tipe Shift
                            </td>
                            <td class="info-value">
                                : <span class="badge badge-with-icon bg-secondary">
                                    <i class="mdi mdi-clock-outline"></i> Regular
                                </span>
                            </td>
                        </tr>
                        @if($presensi->karyawan && $presensi->karyawan->jamKerja)
                        <tr>
                            <td class="info-label">
                                <i class="mdi mdi-briefcase-clock text-secondary"></i> Jam Kerja
                            </td>
                            <td class="info-value">
                                : {{ $presensi->karyawan->jamKerja->nama_jam_kerja }}
                            </td>
                        </tr>
                        <tr>
                            <td class="info-label">
                                <i class="mdi mdi-clock-time-four text-secondary"></i> Jadwal
                            </td>
                            <td class="info-value">
                                : <span class="badge bg-light text-dark">
                                    {{ \Carbon\Carbon::parse($presensi->karyawan->jamKerja->jam_masuk)->format('H:i') }} - 
                                    {{ \Carbon\Carbon::parse($presensi->karyawan->jamKerja->jam_pulang)->format('H:i') }}
                                </span>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
                @endif
            </div>
        </div>

        <!-- Presensi Time Card -->
        <div class="card mt-3">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="mdi mdi-clock-check card-icon"></i> Waktu Presensi
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card bg-success bg-gradient text-white border-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-lg bg-white text-success me-3">
                                        <i class="mdi mdi-login"></i>
                                    </div>
                                    <div class="flex-fill">
                                        <div class="small opacity-75">Jam Masuk</div>
                                        <div class="h3 mb-0">
                                            @if($presensi->jam_masuk)
                                            {{ \Carbon\Carbon::parse($presensi->jam_masuk)->format('H:i:s') }}
                                            @else
                                            <span class="opacity-50">-</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-success bg-gradient text-white border-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-lg bg-white text-success me-3">
                                        <i class="mdi mdi-logout"></i>
                                    </div>
                                    <div class="flex-fill">
                                        <div class="small opacity-75">Jam Pulang</div>
                                        <div class="h3 mb-0">
                                            @if($presensi->jam_pulang)
                                            {{ \Carbon\Carbon::parse($presensi->jam_pulang)->format('H:i:s') }}
                                            @else
                                            <span class="opacity-50">-</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="card mt-3">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="mdi mdi-information-variant card-icon"></i> Informasi Tambahan
                </h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tbody>
                        <tr>
                            <td class="info-label">
                                <i class="mdi mdi-check-decagram text-success"></i> Status Verifikasi
                            </td>
                            <td class="info-value">
                                : @if($presensi->status == 'verified')
                                <span class="badge badge-with-icon bg-success">
                                    <i class="mdi mdi-check-circle"></i> Verified
                                </span>
                                @else
                                <span class="badge badge-with-icon bg-danger">
                                    <i class="mdi mdi-alert-circle"></i> Failed
                                </span>
                                @endif
                            </td>
                        </tr>
                        @if($presensi->similarity_score)
                        <tr>
                            <td class="info-label">
                                <i class="mdi mdi-percent text-info"></i> Similarity Score
                            </td>
                            <td class="info-value">
                                : <span class="badge bg-light text-dark">
                                    {{ number_format($presensi->similarity_score * 100, 2) }}%
                                </span>
                            </td>
                        </tr>
                        @endif
                        @if($presensi->lokasi)
                        <tr>
                            <td class="info-label">
                                <i class="mdi mdi-map-marker text-danger"></i> Lokasi GPS
                            </td>
                            <td class="info-value">
                                : <code class="text-muted">{{ $presensi->lokasi }}</code>
                            </td>
                        </tr>
                        @endif
                        @if($presensi->keterangan)
                        <tr>
                            <td class="info-label">
                                <i class="mdi mdi-note-text text-warning"></i> Keterangan
                            </td>
                            <td class="info-value">
                                : {{ $presensi->keterangan }}
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <td class="info-label">
                                <i class="mdi mdi-clock-plus text-muted"></i> Dibuat
                            </td>
                            <td class="info-value">
                                : {{ $presensi->created_at->format('d/m/Y H:i:s') }}
                                <small class="text-muted">({{ $presensi->created_at->diffForHumans() }})</small>
                            </td>
                        </tr>
                        <tr>
                            <td class="info-label">
                                <i class="mdi mdi-clock-edit text-muted"></i> Terakhir Update
                            </td>
                            <td class="info-value">
                                : {{ $presensi->updated_at->format('d/m/Y H:i:s') }}
                                <small class="text-muted">({{ $presensi->updated_at->diffForHumans() }})</small>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-md-4">
        <!-- Photo Card -->
        @if($presensi->foto)
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="mdi mdi-camera card-icon"></i> Foto Presensi
                </h5>
            </div>
            <div class="card-body text-center">
                <img src="{{ asset('storage/' . $presensi->foto) }}" 
                    alt="Foto Presensi" 
                    class="img-fluid rounded shadow"
                    style="max-height: 400px;">
            </div>
        </div>
        @endif

        <!-- Actions Card -->
        <div class="card {{ $presensi->foto ? 'mt-3' : '' }}">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="mdi mdi-cog card-icon"></i> Aksi
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('panel.presensi-face.index') }}" class="btn btn-secondary">
                        <i class="mdi mdi-arrow-left"></i> Kembali
                    </a>
                    <a href="{{ route('panel.presensi-face.edit', $presensi->id) }}" class="btn btn-warning">
                        <i class="mdi mdi-pencil"></i> Edit
                    </a>
                    <form action="{{ route('panel.presensi-face.destroy', $presensi->id) }}" 
                        method="POST" 
                        onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="mdi mdi-delete"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Map (if lokasi available) -->
        @if($presensi->lokasi)
        <div class="card mt-3">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="mdi mdi-map-marker card-icon"></i> Lokasi Presensi
                </h5>
            </div>
            <div class="card-body">
                @php
                $coords = explode(',', $presensi->lokasi);
                $lat = $coords[0] ?? 0;
                $lng = $coords[1] ?? 0;
                @endphp
                <div id="map" style="height: 300px; border-radius: 8px;"></div>
                <div class="mt-3 p-2 bg-light rounded">
                    <small class="text-muted">
                        <strong>Koordinat:</strong><br>
                        <code>{{ $lat }}, {{ $lng }}</code>
                    </small>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@if($presensi->lokasi)
@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script>
    @php
    $coords = explode(',', $presensi->lokasi);
    $lat = $coords[0] ?? 0;
    $lng = $coords[1] ?? 0;
    @endphp
    
    var map = L.map('map').setView([{{ $lat }}, {{ $lng }}], 15);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    L.marker([{{ $lat }}, {{ $lng }}]).addTo(map)
        .bindPopup('<strong>Lokasi Presensi</strong><br>{{ $presensi->karyawan->nama_lengkap ?? "" }}')
        .openPopup();
</script>
@endpush
@endif

@endsection