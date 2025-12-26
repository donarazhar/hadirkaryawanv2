@extends('admin.layouts.admin')

@section('title', 'Rekap Presensi')
@section('page-title', 'Rekap Bulanan Presensi Face Recognition')

@push('styles')
<style>
    .rekap-table {
        font-size: 0.875rem;
    }

    .rekap-table th {
        background-color: #f8f9fa;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }

    .rekap-table td {
        padding: 0.75rem 0.5rem;
        vertical-align: middle;
    }

    .number-cell {
        text-align: center;
        font-weight: 600;
    }

    .badge-count {
        font-size: 0.875rem;
        padding: 0.35rem 0.6rem;
    }

    .filter-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
        padding: 1.5rem;
    }

    .summary-card {
        border-left: 4px solid;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .summary-card.total { border-left-color: #667eea; }
    .summary-card.verified { border-left-color: #28a745; }
    .summary-card.multi { border-left-color: #17a2b8; }
    .summary-card.regular { border-left-color: #6c757d; }
</style>
@endpush

@section('content')
<!-- Filter Header -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="filter-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">
                    <i class="mdi mdi-calendar-month"></i> Filter Rekap Presensi
                </h5>
                <button class="btn btn-sm btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                    <i class="mdi mdi-chevron-down"></i> Toggle
                </button>
            </div>
            
            <div class="collapse show" id="filterCollapse">
                <form method="GET" class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label">Bulan</label>
                        <select name="bulan" class="form-select">
                            @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                                {{ $namabulan[$i] }}
                            </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tahun</label>
                        <select name="tahun" class="form-select">
                            @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Cabang</label>
                        <select name="kode_cabang" class="form-select">
                            <option value="">Semua Cabang</option>
                            @if(isset($cabang))
                            @foreach($cabang as $item)
                            <option value="{{ $item->kode_cabang }}" {{ request('kode_cabang') == $item->kode_cabang ? 'selected' : '' }}>
                                {{ $item->nama_cabang }}
                            </option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Departemen</label>
                        <select name="kode_dept" class="form-select">
                            <option value="">Semua Departemen</option>
                            @if(isset($departemen))
                            @foreach($departemen as $item)
                            <option value="{{ $item->kode_dept }}" {{ request('kode_dept') == $item->kode_dept ? 'selected' : '' }}>
                                {{ $item->nama_dept }}
                            </option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-light">
                            <i class="mdi mdi-magnify"></i> Tampilkan
                        </button>
                        <a href="{{ route('panel.presensi-face.rekap') }}" class="btn btn-secondary">
                            <i class="mdi mdi-refresh"></i> Reset
                        </a>
                        <a href="{{ route('panel.presensi-face.export-rekap', array_merge(['bulan' => $bulan, 'tahun' => $tahun], request()->only(['kode_cabang', 'kode_dept']))) }}" 
                           class="btn btn-success">
                            <i class="mdi mdi-download"></i> Export Excel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Summary Statistics -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card summary-card total">
            <h3 class="mb-1">{{ $rekap->count() }}</h3>
            <div class="text-muted">Total Karyawan</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card summary-card verified">
            <h3 class="mb-1">{{ $rekap->sum('total_verified') }}</h3>
            <div class="text-muted">Total Verified</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card summary-card multi">
            <h3 class="mb-1">{{ $rekap->sum('total_hadir_multi') }}</h3>
            <div class="text-muted">Total Multi-Shift</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card summary-card regular">
            <h3 class="mb-1">{{ $rekap->sum('total_hadir_regular') }}</h3>
            <div class="text-muted">Total Regular</div>
        </div>
    </div>
</div>

<!-- Rekap Table -->
<div class="card">
    <div class="card-header bg-light">
        <h6 class="mb-0">
            <i class="mdi mdi-table"></i> 
            Rekap Presensi - {{ $namabulan[$bulan] }} {{ $tahun }}
        </h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover rekap-table mb-0">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="10%">NIK</th>
                        <th width="20%">Nama</th>
                        <th width="15%">Jabatan</th>
                        <th width="10%">Cabang</th>
                        <th width="10%">Departemen</th>
                        <th width="8%" class="text-center">Regular</th>
                        <th width="8%" class="text-center">Multi</th>
                        <th width="7%" class="text-center">Total</th>
                        <th width="7%" class="text-center">Verified</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rekap as $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td><strong>{{ $item->nik }}</strong></td>
                        <td>{{ $item->nama_lengkap }}</td>
                        <td>{{ $item->jabatan ?? '-' }}</td>
                        <td>
                            <small>{{ $item->nama_cabang ?? '-' }}</small>
                        </td>
                        <td>
                            <small>{{ $item->nama_dept ?? '-' }}</small>
                        </td>
                        <td class="number-cell">
                            @if($item->total_hadir_regular > 0)
                            <span class="badge bg-secondary badge-count">
                                {{ $item->total_hadir_regular }}
                            </span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="number-cell">
                            @if($item->total_hadir_multi > 0)
                            <span class="badge bg-info badge-count">
                                {{ $item->total_hadir_multi }}
                            </span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="number-cell">
                            <span class="badge bg-primary badge-count">
                                {{ $item->total_hadir }}
                            </span>
                        </td>
                        <td class="number-cell">
                            <span class="badge bg-success badge-count">
                                {{ $item->total_verified }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-4 text-muted">
                            <i class="mdi mdi-information-outline" style="font-size: 2rem;"></i>
                            <div class="mt-2">
                                Tidak ada data presensi untuk periode {{ $namabulan[$bulan] }} {{ $tahun }}
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($rekap->count() > 0)
                <tfoot class="table-light">
                    <tr>
                        <th colspan="6" class="text-end">TOTAL:</th>
                        <th class="number-cell">
                            <span class="badge bg-secondary badge-count">
                                {{ $rekap->sum('total_hadir_regular') }}
                            </span>
                        </th>
                        <th class="number-cell">
                            <span class="badge bg-info badge-count">
                                {{ $rekap->sum('total_hadir_multi') }}
                            </span>
                        </th>
                        <th class="number-cell">
                            <span class="badge bg-primary badge-count">
                                {{ $rekap->sum('total_hadir') }}
                            </span>
                        </th>
                        <th class="number-cell">
                            <span class="badge bg-success badge-count">
                                {{ $rekap->sum('total_verified') }}
                            </span>
                        </th>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>

<!-- Legend -->
<div class="card mt-3">
    <div class="card-body">
        <h6 class="mb-3">Keterangan:</h6>
        <div class="row">
            <div class="col-md-3">
                <span class="badge bg-secondary">Regular</span> = Presensi jam kerja regular
            </div>
            <div class="col-md-3">
                <span class="badge bg-info">Multi</span> = Presensi multi-shift (shift 1-5)
            </div>
            <div class="col-md-3">
                <span class="badge bg-primary">Total</span> = Total semua presensi
            </div>
            <div class="col-md-3">
                <span class="badge bg-success">Verified</span> = Presensi terverifikasi
            </div>
        </div>
    </div>
</div>
@endsection