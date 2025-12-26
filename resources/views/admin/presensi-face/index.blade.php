@extends('admin.layouts.admin')

@section('title', 'Presensi Face Recognition')
@section('page-title', 'Presensi Face Recognition')

@push('styles')
<style>
    /* Custom badge colors */
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

    /* Compact table */
    .table-compact td {
        padding: 0.5rem 0.35rem;
        vertical-align: middle;
    }

    .table-compact th {
        padding: 0.75rem 0.35rem;
        vertical-align: middle;
    }

    /* Stacked info */
    .stacked-info {
        display: flex;
        flex-direction: column;
        gap: 0.15rem;
    }

    .stacked-info .main-text {
        font-weight: 600;
        font-size: 0.875rem;
    }

    .stacked-info .sub-text {
        font-size: 0.75rem;
        color: #6c757d;
    }

    /* Badge styling */
    .badge-sm {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        font-weight: 500;
    }

    /* Shift badges grouped */
    .shift-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 0.25rem;
    }

    .shift-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .shift-badge.shift-1 {
        background-color: #e3f2fd;
        color: #1565c0;
    }

    .shift-badge.shift-2 {
        background-color: #f3e5f5;
        color: #6a1b9a;
    }

    .shift-badge.shift-3 {
        background-color: #e8f5e9;
        color: #2e7d32;
    }

    .shift-badge.shift-4 {
        background-color: #fff3e0;
        color: #e65100;
    }

    .shift-badge.shift-5 {
        background-color: #fce4ec;
        color: #c2185b;
    }

    /* Time display */
    .time-display {
        display: flex;
        flex-direction: column;
        gap: 0.15rem;
    }

    .time-item {
        font-size: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .time-icon {
        font-size: 0.875rem;
    }

    /* Avatar small */
    .avatar-sm {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.75rem;
        background-color: #e9ecef;
        color: #495057;
    }

    /* Filter collapse */
    .filter-section {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header bg-light">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="mdi mdi-face-recognition"></i> Data Presensi Face Recognition
            </h5>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#exportModal">
                    <i class="mdi mdi-download"></i> Export
                </button>
                <a href="{{ route('panel.presensi-face.create') }}" class="btn btn-primary btn-sm">
                    <i class="mdi mdi-plus"></i> Tambah Presensi
                </a>
            </div>
        </div>
    </div>

    <div class="card-body">
        <!-- Filters -->
        <div class="filter-section">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">
                    <i class="mdi mdi-filter"></i> Filter Data
                </h6>
                <button class="btn btn-sm btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                    <i class="mdi mdi-chevron-down"></i> Toggle
                </button>
            </div>

            <div class="collapse show" id="filterCollapse">
                <form method="GET" id="filterForm">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label class="form-label small">Tanggal Awal</label>
                            <input type="date" name="tanggal_awal" class="form-control form-control-sm"
                                value="{{ request('tanggal_awal') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Tanggal Akhir</label>
                            <input type="date" name="tanggal_akhir" class="form-control form-control-sm"
                                value="{{ request('tanggal_akhir') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">NIK</label>
                            <input type="text" name="nik" class="form-control form-control-sm"
                                value="{{ request('nik') }}" placeholder="Cari NIK...">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Cabang</label>
                            <select name="kode_cabang" class="form-select form-select-sm">
                                <option value="">Semua Cabang</option>
                                @foreach($cabang as $item)
                                <option value="{{ $item->kode_cabang }}" {{ request('kode_cabang') == $item->kode_cabang ? 'selected' : '' }}>
                                    {{ $item->nama_cabang }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Departemen</label>
                            <select name="kode_dept" class="form-select form-select-sm">
                                <option value="">Semua Departemen</option>
                                @foreach($departemen as $item)
                                <option value="{{ $item->kode_dept }}" {{ request('kode_dept') == $item->kode_dept ? 'selected' : '' }}>
                                    {{ $item->nama_dept }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="">Semua Status</option>
                                <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Tipe Shift</label>
                            <select name="shift_type" class="form-select form-select-sm">
                                <option value="">Semua Tipe</option>
                                <option value="multi" {{ request('shift_type') == 'multi' ? 'selected' : '' }}>Multi-Shift</option>
                                <option value="regular" {{ request('shift_type') == 'regular' ? 'selected' : '' }}>Regular</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Shift Ke</label>
                            <select name="shift_ke" class="form-select form-select-sm">
                                <option value="">Semua Shift</option>
                                @foreach($availableShifts as $shift)
                                <option value="{{ $shift->shift_ke }}" {{ request('shift_ke') == $shift->shift_ke ? 'selected' : '' }}>
                                    Shift {{ $shift->shift_ke }} - {{ $shift->nama_shift }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="mdi mdi-magnify"></i> Cari
                            </button>
                            <a href="{{ route('panel.presensi-face.index') }}" class="btn btn-secondary btn-sm">
                                <i class="mdi mdi-refresh"></i> Reset
                            </a>
                            <span class="ms-3 text-muted small">
                                <i class="mdi mdi-information"></i>
                                Menampilkan {{ $presensi->total() }} data
                            </span>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover table-compact">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="10%">Tanggal</th>
                        <th width="20%">Karyawan</th>
                        <th width="15%">Lokasi</th>
                        <th width="20%">Shift</th>
                        <th width="10%">Jam Masuk</th>
                        <th width="10%">Jam Pulang</th>
                        <th width="5%">Status</th>
                        <th width="5%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    // Group presensi by NIK and Tanggal
                    $groupedPresensi = [];
                    foreach($presensi as $item) {
                    $key = $item->nik . '_' . $item->tanggal;
                    if (!isset($groupedPresensi[$key])) {
                    $groupedPresensi[$key] = [
                    'main' => $item,
                    'shifts' => []
                    ];
                    }
                    $groupedPresensi[$key]['shifts'][] = $item;
                    }

                    $currentPage = $presensi->currentPage();
                    $perPage = $presensi->perPage();
                    $startNumber = ($currentPage - 1) * $perPage;
                    @endphp

                    @forelse($groupedPresensi as $key => $group)
                    @php
                    $mainItem = $group['main'];
                    $shifts = $group['shifts'];
                    $isMultiShift = count($shifts) > 1 || $mainItem->shift_ke !== null;
                    @endphp
                    <tr>
                        <!-- No -->
                        <td class="text-center">{{ $startNumber + $loop->iteration }}</td>

                        <!-- Tanggal -->
                        <td>
                            <div class="stacked-info">
                                <span class="main-text">{{ \Carbon\Carbon::parse($mainItem->tanggal)->format('d/m/Y') }}</span>
                                <span class="sub-text">{{ \Carbon\Carbon::parse($mainItem->tanggal)->isoFormat('dddd') }}</span>
                            </div>
                        </td>

                        <!-- Karyawan -->
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-sm">
                                    {{ strtoupper(substr($mainItem->karyawan->nama_lengkap ?? 'N', 0, 1)) }}
                                </div>
                                <div class="stacked-info flex-fill">
                                    <span class="main-text">{{ $mainItem->karyawan->nama_lengkap ?? 'N/A' }}</span>
                                    <span class="sub-text">
                                        NIK: {{ $mainItem->nik }}
                                        @if($mainItem->karyawan && $mainItem->karyawan->jabatan)
                                        · {{ $mainItem->karyawan->jabatan }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </td>

                        <!-- Lokasi (Cabang + Departemen) -->
                        <td>
                            <div class="d-flex flex-column gap-1">
                                @if($mainItem->karyawan && $mainItem->karyawan->cabang)
                                <span class="badge badge-sm bg-blue">
                                    <i class="mdi mdi-office-building"></i> {{ $mainItem->karyawan->cabang->nama_cabang }}
                                </span>
                                @endif
                                @if($mainItem->karyawan && $mainItem->karyawan->departemen)
                                <span class="badge badge-sm bg-purple">
                                    <i class="mdi mdi-account-group"></i> {{ $mainItem->karyawan->departemen->nama_dept }}
                                </span>
                                @endif
                            </div>
                        </td>

                        <!-- Shift (Grouped) -->
                        <td>
                            @if($isMultiShift)
                            <div class="shift-badges">
                                @foreach($shifts as $shiftItem)
                                <div class="shift-badge shift-{{ $shiftItem->shift_ke }}"
                                    title="{{ $shiftItem->nama_shift }}">
                                    <i class="mdi mdi-numeric-{{ $shiftItem->shift_ke }}-box"></i>
                                    <span>{{ $shiftItem->nama_shift }}</span>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <span class="badge badge-sm bg-secondary">
                                <i class="mdi mdi-clock-outline"></i> Regular
                            </span>
                            @endif
                        </td>

                        <!-- Jam Masuk (All Shifts) -->
                        <td>
                            <div class="time-display">
                                @foreach($shifts as $shiftItem)
                                <div class="time-item">
                                    @if($isMultiShift)
                                    <span class="badge badge-sm bg-light text-dark">{{ $shiftItem->shift_ke }}</span>
                                    @endif
                                    <i class="mdi mdi-login time-icon text-success"></i>
                                    <span>{{ $shiftItem->jam_masuk ? \Carbon\Carbon::parse($shiftItem->jam_masuk)->format('H:i') : '-' }}</span>
                                </div>
                                @endforeach
                            </div>
                        </td>

                        <!-- Jam Pulang (All Shifts) -->
                        <td>
                            <div class="time-display">
                                @foreach($shifts as $shiftItem)
                                <div class="time-item">
                                    @if($isMultiShift)
                                    <span class="badge badge-sm bg-light text-dark">{{ $shiftItem->shift_ke }}</span>
                                    @endif
                                    <i class="mdi mdi-logout time-icon text-danger"></i>
                                    <span>{{ $shiftItem->jam_pulang ? \Carbon\Carbon::parse($shiftItem->jam_pulang)->format('H:i') : '-' }}</span>
                                </div>
                                @endforeach
                            </div>
                        </td>

                        <!-- Status -->
                        <td class="text-center">
                            @php
                            $allVerified = collect($shifts)->every(fn($s) => $s->status == 'verified');
                            @endphp
                            @if($allVerified)
                            <span class="badge bg-success" title="All shifts verified">
                                <i class="mdi mdi-check-circle"></i>
                            </span>
                            @else
                            <span class="badge bg-warning" title="Some shifts failed">
                                <i class="mdi mdi-alert-circle"></i>
                            </span>
                            @endif
                        </td>

                        <!-- Aksi -->
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="mdi mdi-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    @foreach($shifts as $shiftItem)
                                    <li>
                                        <h6 class="dropdown-header">
                                            @if($isMultiShift)
                                            Shift {{ $shiftItem->shift_ke }} - {{ $shiftItem->nama_shift }}
                                            @else
                                            Presensi
                                            @endif
                                        </h6>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('panel.presensi-face.show', $shiftItem->id) }}">
                                            <i class="mdi mdi-eye"></i> Detail
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('panel.presensi-face.edit', $shiftItem->id) }}">
                                            <i class="mdi mdi-pencil"></i> Edit
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('panel.presensi-face.destroy', $shiftItem->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="mdi mdi-delete"></i> Hapus
                                            </button>
                                        </form>
                                    </li>
                                    @if(!$loop->last)
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    @endif
                                    @endforeach
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">
                            <i class="mdi mdi-information-outline" style="font-size: 2rem;"></i>
                            <div class="mt-2">Tidak ada data presensi</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($presensi->hasPages())
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    <small>
                        Menampilkan <strong>{{ $presensi->firstItem() }}</strong>
                        sampai <strong>{{ $presensi->lastItem() }}</strong>
                        dari <strong>{{ $presensi->total() }}</strong> data
                    </small>
                </div>
                <nav aria-label="Page navigation">
                    {{ $presensi->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
                </nav>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Export Modal - WITH NIK SELECTION -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('panel.presensi-face.export-data') }}" method="GET" id="exportForm">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="mdi mdi-download"></i> Export Data Presensi Face
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Export Info -->
                    <div class="alert alert-info">
                        <i class="mdi mdi-information"></i>
                        <strong>Info Export:</strong>
                        <ul class="mb-0 mt-2">
                            <li><strong>Excel:</strong> Export detail per karyawan (Multiple Sheets)</li>
                            <li><strong>PDF:</strong> Export ringkasan semua data presensi (Single Page)</li>
                            <li>Pilih karyawan spesifik atau export semua karyawan sesuai filter</li>
                        </ul>
                    </div>

                    <!-- Format Selection -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="mdi mdi-file-document"></i> Format Export <span class="text-danger">*</span>
                        </label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="format" id="formatExcel" value="excel" checked>
                                <label class="form-check-label" for="formatExcel">
                                    <i class="mdi mdi-microsoft-excel text-success"></i> Excel (.xlsx)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="format" id="formatPdf" value="pdf">
                                <label class="form-check-label" for="formatPdf">
                                    <i class="mdi mdi-file-pdf text-danger"></i> PDF (.pdf)
                                </label>
                            </div>
                        </div>
                        <small class="text-muted">Excel direkomendasikan untuk data detail per karyawan</small>
                    </div>

                    <hr>

                    <!-- Filters Section -->
                    <h6 class="mb-3">
                        <i class="mdi mdi-filter"></i> Filter Data Export
                    </h6>

                    <!-- Periode -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Awal</label>
                            <input type="date" name="tanggal_awal" class="form-control"
                                value="{{ request('tanggal_awal') }}">
                            <small class="text-muted">Kosongkan untuk semua tanggal</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="date" name="tanggal_akhir" class="form-control"
                                value="{{ request('tanggal_akhir') }}">
                            <small class="text-muted">Kosongkan untuk semua tanggal</small>
                        </div>
                    </div>

                    <!-- Cabang & Departemen -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="mdi mdi-office-building"></i> Cabang
                            </label>
                            <select name="kode_cabang" class="form-select" id="exportCabang">
                                <option value="">Semua Cabang</option>
                                @foreach($cabang as $item)
                                <option value="{{ $item->kode_cabang }}"
                                    {{ request('kode_cabang') == $item->kode_cabang ? 'selected' : '' }}>
                                    {{ $item->nama_cabang }}
                                </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Pilih cabang untuk export per cabang</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="mdi mdi-account-group"></i> Departemen
                            </label>
                            <select name="kode_dept" class="form-select" id="exportDepartemen">
                                <option value="">Semua Departemen</option>
                                @foreach($departemen as $item)
                                <option value="{{ $item->kode_dept }}"
                                    {{ request('kode_dept') == $item->kode_dept ? 'selected' : '' }}>
                                    {{ $item->nama_dept }}
                                </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Pilih departemen untuk export per departemen</small>
                        </div>
                    </div>

                    <!-- ✅ NEW: Pilih Karyawan Spesifik -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">
                                <i class="mdi mdi-account-multiple"></i> Pilih Karyawan Spesifik
                            </label>
                            <select name="nik_list[]" class="form-select" id="exportNikSelect" multiple>
                                <option value="">-- Pilih Karyawan (Kosongkan untuk semua) --</option>
                                @foreach($karyawan as $k)
                                <option value="{{ $k->nik }}"
                                    data-dept="{{ $k->departemen->nama_dept ?? '-' }}"
                                    data-cabang="{{ $k->cabang->nama_cabang ?? '-' }}">
                                    {{ $k->nik }} - {{ $k->nama_lengkap }}
                                    ({{ $k->departemen->nama_dept ?? '-' }})
                                </option>
                                @endforeach
                            </select>
                            <small class="text-muted">
                                <i class="mdi mdi-information-outline"></i>
                                Kosongkan untuk export semua karyawan sesuai filter cabang/departemen.
                                Pilih satu atau lebih untuk export karyawan tertentu saja.
                            </small>
                        </div>
                    </div>

                    <!-- Status & Shift -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">
                                <i class="mdi mdi-check-circle"></i> Status
                            </label>
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">
                                <i class="mdi mdi-layers"></i> Tipe Shift
                            </label>
                            <select name="shift_type" class="form-select">
                                <option value="">Semua Tipe</option>
                                <option value="multi" {{ request('shift_type') == 'multi' ? 'selected' : '' }}>Multi-Shift</option>
                                <option value="regular" {{ request('shift_type') == 'regular' ? 'selected' : '' }}>Regular</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">
                                <i class="mdi mdi-numeric"></i> Shift Tertentu
                            </label>
                            <select name="shift_ke" class="form-select">
                                <option value="">Semua Shift</option>
                                @foreach($availableShifts as $shift)
                                <option value="{{ $shift->shift_ke }}" {{ request('shift_ke') == $shift->shift_ke ? 'selected' : '' }}>
                                    Shift {{ $shift->shift_ke }} - {{ $shift->nama_shift }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Preview Info -->
                    <div class="alert alert-light border" id="exportPreview">
                        <strong><i class="mdi mdi-information"></i> Preview:</strong>
                        <ul class="mb-0 mt-2 small" id="exportPreviewList">
                            <li>Export akan mencakup semua data sesuai filter yang dipilih</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="mdi mdi-close"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="mdi mdi-download"></i> Export Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
    .select2-container--bootstrap-5 .select2-selection {
        min-height: 38px;
    }

    .select2-container {
        width: 100% !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // ✅ Initialize Select2 for NIK selection
        $('#exportNikSelect').select2({
            theme: 'bootstrap-5',
            placeholder: '-- Pilih Karyawan (Kosongkan untuk semua) --',
            allowClear: true,
            closeOnSelect: false,
            dropdownParent: $('#exportModal'),
            width: '100%'
        });

        // ✅ Filter karyawan based on cabang/dept selection
        function filterKaryawan() {
            const selectedCabang = $('#exportCabang').val();
            const selectedDept = $('#exportDepartemen').val();

            $('#exportNikSelect option').each(function() {
                const $option = $(this);
                if ($option.val() === '') return; // Skip placeholder

                const optionCabang = $option.data('cabang');
                const optionDept = $option.data('dept');

                let show = true;

                if (selectedCabang && optionCabang !== $('#exportCabang option:selected').text()) {
                    show = false;
                }

                if (selectedDept && optionDept !== $('#exportDepartemen option:selected').text()) {
                    show = false;
                }

                if (show) {
                    $option.show();
                } else {
                    $option.hide();
                }
            });

            // Refresh Select2
            $('#exportNikSelect').select2('destroy').select2({
                theme: 'bootstrap-5',
                placeholder: '-- Pilih Karyawan (Kosongkan untuk semua) --',
                allowClear: true,
                closeOnSelect: false,
                dropdownParent: $('#exportModal'),
                width: '100%'
            });
        }

        // Trigger filter when cabang/dept changes
        $('#exportCabang, #exportDepartemen').on('change', function() {
            filterKaryawan();
            updateExportPreview();
        });

        // ✅ Update preview saat filter berubah
        function updateExportPreview() {
            const format = $('input[name="format"]:checked').val();
            const cabang = $('#exportCabang option:selected').text();
            const dept = $('#exportDepartemen option:selected').text();
            const tanggalAwal = $('input[name="tanggal_awal"]').val();
            const tanggalAkhir = $('input[name="tanggal_akhir"]').val();
            const selectedNiks = $('#exportNikSelect').val();
            const status = $('select[name="status"] option:selected').text();
            const shiftType = $('select[name="shift_type"] option:selected').text();

            let preview = '<li>Format: <strong>' + (format === 'excel' ? 'Excel (.xlsx)' : 'PDF (.pdf)') + '</strong></li>';

            if (selectedNiks && selectedNiks.length > 0) {
                preview += '<li>Karyawan Terpilih: <strong>' + selectedNiks.length + ' karyawan</strong></li>';
                preview += '<ul class="mt-1">';
                selectedNiks.forEach(function(nik) {
                    const karyawanName = $('#exportNikSelect option[value="' + nik + '"]').text();
                    preview += '<li><small>' + karyawanName + '</small></li>';
                });
                preview += '</ul>';
            } else {
                if (cabang !== 'Semua Cabang') {
                    preview += '<li>Cabang: <strong>' + cabang + '</strong></li>';
                }

                if (dept !== 'Semua Departemen') {
                    preview += '<li>Departemen: <strong>' + dept + '</strong></li>';
                }

                preview += '<li class="text-warning"><i class="mdi mdi-alert"></i> Semua karyawan sesuai filter akan di-export</li>';
            }

            if (tanggalAwal && tanggalAkhir) {
                preview += '<li>Periode: <strong>' + tanggalAwal + ' s/d ' + tanggalAkhir + '</strong></li>';
            }

            if (status !== 'Semua Status') {
                preview += '<li>Status: <strong>' + status + '</strong></li>';
            }

            if (shiftType !== 'Semua Tipe') {
                preview += '<li>Tipe Shift: <strong>' + shiftType + '</strong></li>';
            }

            if (format === 'excel') {
                preview += '<li class="text-primary"><i class="mdi mdi-check"></i> Export akan membuat sheet terpisah untuk setiap karyawan</li>';
            }

            $('#exportPreviewList').html(preview);
        }

        // Update preview saat modal dibuka
        $('#exportModal').on('shown.bs.modal', function() {
            updateExportPreview();
        });

        // Update preview saat filter berubah
        $('#exportForm input, #exportForm select').on('change', function() {
            updateExportPreview();
        });

        // ✅ Validasi sebelum submit
        $('#exportForm').on('submit', function(e) {
            const format = $('input[name="format"]:checked').val();
            const selectedNiks = $('#exportNikSelect').val();
            const cabang = $('#exportCabang option:selected').text();
            const dept = $('#exportDepartemen option:selected').text();

            let confirmMsg = 'Export data dalam format ' + (format === 'excel' ? 'Excel' : 'PDF') + '?\n\n';

            if (selectedNiks && selectedNiks.length > 0) {
                confirmMsg += 'Karyawan yang dipilih: ' + selectedNiks.length + ' karyawan\n';
            } else {
                confirmMsg += 'Export SEMUA karyawan';
                if (cabang !== 'Semua Cabang' || dept !== 'Semua Departemen') {
                    confirmMsg += ' di:\n';
                    if (cabang !== 'Semua Cabang') confirmMsg += '- Cabang: ' + cabang + '\n';
                    if (dept !== 'Semua Departemen') confirmMsg += '- Departemen: ' + dept + '\n';
                } else {
                    confirmMsg += '\n';
                }
            }

            if (!confirm(confirmMsg)) {
                e.preventDefault();
                return false;
            }

            // Show loading
            const btn = $(this).find('button[type="submit"]');
            btn.prop('disabled', true);
            btn.html('<i class="mdi mdi-loading mdi-spin"></i> Generating...');

            // Close modal after 1 second
            setTimeout(function() {
                $('#exportModal').modal('hide');
                btn.prop('disabled', false);
                btn.html('<i class="mdi mdi-download"></i> Export Sekarang');
            }, 1000);
        });
    });
</script>
@endpush

@endsection