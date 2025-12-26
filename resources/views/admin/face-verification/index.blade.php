@extends('admin.layouts.admin')

@section('title', 'Verifikasi Wajah Karyawan')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Manajemen</div>
                <h2 class="page-title">Verifikasi Wajah Karyawan</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <form action="{{ route('panel.face-verification.export') }}" method="GET" class="d-inline">
                        @if(request('kode_cabang'))
                        <input type="hidden" name="kode_cabang" value="{{ request('kode_cabang') }}">
                        @endif
                        @if(request('kode_dept'))
                        <input type="hidden" name="kode_dept" value="{{ request('kode_dept') }}">
                        @endif
                        @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif
                        <button type="submit" class="btn btn-success">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                            </svg>
                            Export Excel
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <div class="d-flex">
                <div>{{ session('success') }}</div>
            </div>
            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            <div class="d-flex">
                <div>{{ session('error') }}</div>
            </div>
            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
        @endif

        <!-- Statistics Cards -->
        <div class="row row-cards mb-3">
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Total Karyawan</div>
                        </div>
                        <div class="h1 mb-0">{{ $stats['total_karyawan'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Sudah Terdaftar</div>
                        </div>
                        <div class="d-flex align-items-baseline">
                            <div class="h1 mb-0 me-2 text-success">{{ $stats['enrolled'] }}</div>
                            <div class="me-auto">
                                <span class="badge bg-success-lt">{{ $stats['percentage'] }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Belum Terdaftar</div>
                        </div>
                        <div class="h1 mb-0 text-warning">{{ $stats['not_enrolled'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Non-aktif</div>
                        </div>
                        <div class="h1 mb-0 text-danger">{{ $stats['inactive'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Filter Data</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('panel.face-verification.index') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Cabang</label>
                            <select name="kode_cabang" class="form-select">
                                <option value="">Semua Cabang</option>
                                @foreach($cabang as $c)
                                <option value="{{ $c->kode_cabang }}"
                                    {{ request('kode_cabang') == $c->kode_cabang ? 'selected' : '' }}>
                                    {{ $c->nama_cabang }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Departemen</label>
                            <select name="kode_dept" class="form-select">
                                <option value="">Semua Departemen</option>
                                @foreach($departemen as $d)
                                <option value="{{ $d->kode_dept }}"
                                    {{ request('kode_dept') == $d->kode_dept ? 'selected' : '' }}>
                                    {{ $d->nama_dept }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status Enrollment</label>
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="enrolled" {{ request('status') == 'enrolled' ? 'selected' : '' }}>
                                    Sudah Terdaftar
                                </option>
                                <option value="not_enrolled" {{ request('status') == 'not_enrolled' ? 'selected' : '' }}>
                                    Belum Terdaftar
                                </option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                    Non-aktif
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Cari NIK/Nama</label>
                            <input type="text" name="search" class="form-control"
                                placeholder="Cari..."
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <circle cx="10" cy="10" r="7" />
                                    <line x1="21" y1="21" x2="15" y2="15" />
                                </svg>
                                Filter
                            </button>
                            <a href="{{ route('panel.face-verification.index') }}" class="btn btn-secondary">
                                Reset Filter
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card mt-3">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-vcenter card-table table-striped">
                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th width="250">Karyawan</th>
                                <th width="220">Info Penempatan</th>
                                <th width="180">Status Enrollment</th>
                                <th width="120">Terakhir Update</th>
                                <th width="200" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($karyawan as $index => $k)
                            <tr>
                                <td>{{ $karyawan->firstItem() + $index }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($k->foto)
                                        <span class="avatar me-2" style="background-image: url({{ Storage::url('uploads/karyawan/'.$k->foto) }})"></span>
                                        @else
                                        <span class="avatar me-2">{{ substr($k->nama_lengkap, 0, 2) }}</span>
                                        @endif
                                        <div>
                                            <div class="fw-bold">{{ $k->nama_lengkap }}</div>
                                            <small class="text-muted">NIK: {{ $k->nik }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div class="text-truncate" style="max-width: 220px;">
                                            <strong>{{ $k->cabang->nama_cabang ?? '-' }}</strong>
                                        </div>
                                        <small class="text-muted d-block">{{ $k->departemen->nama_dept ?? '-' }}</small>
                                        <small class="text-muted d-block">{{ $k->jabatan }}</small>
                                    </div>
                                </td>
                                <td>
                                    @if($k->faceData)
                                    @if($k->faceData->status == 'active')
                                    <span class="badge bg-success">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-check" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M5 12l5 5l10 -10"></path>
                                        </svg>
                                        Terdaftar
                                    </span>
                                    <br>
                                    <small class="text-muted">
                                        {{ $k->faceData->enrollment_count }}x enrollment
                                    </small>
                                    @else
                                    <span class="badge bg-danger">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                        Non-aktif
                                    </span>
                                    <br>
                                    <small class="text-muted">
                                        {{ $k->faceData->enrollment_count }}x enrollment
                                    </small>
                                    @endif
                                    @else
                                    <span class="badge bg-warning">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-alert-circle" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <circle cx="12" cy="12" r="9"></circle>
                                            <line x1="12" y1="8" x2="12" y2="12"></line>
                                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                        </svg>
                                        Belum Terdaftar
                                    </span>
                                    @endif
                                </td>
                                <td>
                                    @if($k->faceData && $k->faceData->last_updated)
                                    <div class="text-nowrap">
                                        {{ \Carbon\Carbon::parse($k->faceData->last_updated)->format('d/m/Y') }}
                                    </div>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($k->faceData->last_updated)->format('H:i') }}
                                    </small>
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('panel.face-verification.show', $k->nik) }}"
                                            class="btn btn-sm btn-primary"
                                            title="Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <circle cx="12" cy="12" r="2"></circle>
                                                <path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7"></path>
                                            </svg>
                                        </a>

                                        @if($k->faceData)
                                        @if($k->faceData->status == 'active')
                                        <form action="{{ route('panel.face-verification.deactivate', $k->nik) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-warning"
                                                title="Nonaktifkan"
                                                onclick="return confirm('Yakin ingin menonaktifkan data wajah ini?')">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-ban" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <circle cx="12" cy="12" r="9"></circle>
                                                    <line x1="5.7" y1="5.7" x2="18.3" y2="18.3"></line>
                                                </svg>
                                            </button>
                                        </form>
                                        @else
                                        <form action="{{ route('panel.face-verification.activate', $k->nik) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-success"
                                                title="Aktifkan"
                                                onclick="return confirm('Yakin ingin mengaktifkan data wajah ini?')">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-check" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M5 12l5 5l10 -10"></path>
                                                </svg>
                                            </button>
                                        </form>
                                        @endif

                                        <form action="{{ route('panel.face-verification.destroy', $k->nik) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                title="Hapus & Reset"
                                                onclick="return confirm('Yakin ingin menghapus data wajah? Karyawan harus mendaftar ulang.')">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <line x1="4" y1="7" x2="20" y2="7"></line>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                                                </svg>
                                            </button>
                                        </form>
                                        @else
                                        <button class="btn btn-sm btn-secondary" disabled title="Belum ada data">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-minus" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                            </svg>
                                        </button>
                                        <button class="btn btn-sm btn-secondary" disabled title="Belum ada data">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-minus" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                            </svg>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-muted mb-2" width="48" height="48" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <circle cx="12" cy="12" r="9"></circle>
                                        <line x1="9" y1="10" x2="9.01" y2="10"></line>
                                        <line x1="15" y1="10" x2="15.01" y2="10"></line>
                                        <path d="M9.5 15.25a3.5 3.5 0 0 1 5 0"></path>
                                    </svg>
                                    <div>Tidak ada data karyawan</div>
                                    <small class="text-muted">Silakan ubah filter atau coba parameter lain</small>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($karyawan->hasPages())
            <div class="card-footer d-flex align-items-center">
                <p class="m-0 text-muted">
                    Menampilkan
                    <span class="fw-bold">{{ $karyawan->firstItem() }}</span>
                    sampai
                    <span class="fw-bold">{{ $karyawan->lastItem() }}</span>
                    dari
                    <span class="fw-bold">{{ $karyawan->total() }}</span>
                    data
                </p>
                <ul class="pagination m-0 ms-auto">
                    {{ $karyawan->links() }}
                </ul>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    /* Custom styles untuk table yang lebih compact */
    .table-vcenter td {
        vertical-align: middle;
    }

    .btn-group .btn {
        border-radius: 0;
    }

    .btn-group .btn:first-child {
        border-top-left-radius: 0.25rem;
        border-bottom-left-radius: 0.25rem;
    }

    .btn-group .btn:last-child {
        border-top-right-radius: 0.25rem;
        border-bottom-right-radius: 0.25rem;
    }

    .text-truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Hover effect untuk row */
    .table-striped tbody tr:hover {
        background-color: rgba(0, 83, 197, 0.05);
    }

    /* Badge styling */
    .badge {
        font-weight: 500;
        padding: 0.35rem 0.65rem;
    }

    .badge svg {
        vertical-align: middle;
        margin-right: 2px;
    }

    /* Avatar styling */
    .avatar {
        width: 2.5rem;
        height: 2.5rem;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.875rem;
        }

        .avatar {
            width: 2rem;
            height: 2rem;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
    }
</style>
@endsection