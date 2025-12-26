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

<!-- Export Modal - COMPACT VERSION -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">
                    <i class="mdi mdi-download"></i> Export Data Presensi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('panel.presensi-face.export-data') }}" method="GET">
                <div class="modal-body">
                    <!-- Format Export -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Format Export</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="format" id="formatExcel" value="excel" checked>
                            <label class="btn btn-outline-success" for="formatExcel">
                                <i class="mdi mdi-file-excel"></i> Excel
                            </label>

                            <input type="radio" class="btn-check" name="format" id="formatPdf" value="pdf">
                            <label class="btn btn-outline-danger" for="formatPdf">
                                <i class="mdi mdi-file-pdf-box"></i> PDF
                            </label>
                        </div>
                    </div>

                    <!-- Date Range - Compact -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Periode (Opsional)</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="date"
                                    name="tanggal_awal"
                                    class="form-control form-control-sm"
                                    placeholder="Dari"
                                    value="{{ request('tanggal_awal') }}">
                                <small class="text-muted">Dari</small>
                            </div>
                            <div class="col-6">
                                <input type="date"
                                    name="tanggal_akhir"
                                    class="form-control form-control-sm"
                                    placeholder="Sampai"
                                    value="{{ request('tanggal_akhir') }}">
                                <small class="text-muted">Sampai</small>
                            </div>
                        </div>
                    </div>

                    <!-- Preserve Active Filters - COMPACT LOOP -->
                    @php
                    $filterParams = ['nik', 'kode_cabang', 'kode_dept', 'status', 'shift_type', 'shift_ke'];
                    @endphp
                    @foreach($filterParams as $param)
                    @if(request($param))
                    <input type="hidden" name="{{ $param }}" value="{{ request($param) }}">
                    @endif
                    @endforeach

                    <!-- Active Filters Info - COMPACT -->
                    @php
                    $activeFilters = collect($filterParams)->filter(fn($p) => request($p))->count();
                    @endphp
                    @if($activeFilters > 0)
                    <div class="alert alert-success alert-dismissible fade show small mb-0">
                        <i class="mdi mdi-check-circle"></i>
                        <strong>{{ $activeFilters }}</strong> filter aktif akan diterapkan
                        <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
                    </div>
                    @else
                    <div class="alert alert-info small mb-0">
                        <i class="mdi mdi-information-outline"></i>
                        Semua data akan di-export
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-sm btn-success">
                        <i class="mdi mdi-download"></i> Download
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection