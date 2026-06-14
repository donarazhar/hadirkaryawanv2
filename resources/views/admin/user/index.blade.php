@extends('admin.layouts.admin')

@section('title', 'Data User')
@section('page-title', 'Data User')

@section('content')
<!-- Page Header -->
<div class="page-header d-print-none mb-3">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Kelola Konfigurasi</div>
                <h2 class="page-title">Data User</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('panel.user.create') }}" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                    Tambah User
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Alert Messages -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <div class="d-flex">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M5 12l5 5l10 -10" />
                    </svg>
                </div>
                <div>{{ session('success') }}</div>
            </div>
            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            <div class="d-flex">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <circle cx="12" cy="12" r="9" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                </div>
                <div>{{ session('error') }}</div>
            </div>
            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
        @endif

        @if(session('warning'))
        <div class="alert alert-warning alert-dismissible" role="alert">
            <div class="d-flex">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 9v2m0 4v.01" />
                        <path d="M5 19h14a2 2 0 0 1 1.84 2.75l-7.1 12.25a2 2 0 0 1 -3.5 0l-7.1 -12.25a2 2 0 0 1 1.75 -2.75" />
                    </svg>
                </div>
                <div>{{ session('warning') }}</div>
            </div>
            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
        @endif

        <!-- Search Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pencarian</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('panel.user.index') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-10">
                            <label class="form-label">Cari User</label>
                            <input type="text" name="search" class="form-control"
                                placeholder="Cari berdasarkan nama, email, atau role..."
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <circle cx="10" cy="10" r="7" />
                                    <line x1="21" y1="21" x2="15" y2="15" />
                                </svg>
                                Cari
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Data Table Card -->
        <div class="card mt-3">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-vcenter card-table table-striped">
                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Cabang</th>
                                <th width="150" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $index => $item)
                            <tr>
                                <td>{{ $users->firstItem() + $index }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="avatar me-2 bg-blue-lt text-blue">
                                            {{ substr($item->name, 0, 2) }}
                                        </span>
                                        <div class="fw-bold">{{ $item->name }}</div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted">{{ $item->email }}</span>
                                </td>
                                <td>
                                    @if($item->role == 'superadmin')
                                        <span class="badge bg-danger">Superadmin</span>
                                    @elseif($item->role == 'admin')
                                        <span class="badge bg-primary">Admin</span>
                                    @else
                                        <span class="badge bg-info">Pimpinan</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->cabang)
                                        <div class="d-flex flex-column">
                                            <div class="d-flex align-items-start mb-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted me-1 mt-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <circle cx="12" cy="11" r="3" />
                                                    <path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z" />
                                                </svg>
                                                <span class="text-muted small">{{ $item->cabang->nama_cabang }}</span>
                                            </div>
                                            
                                            @if($item->role === 'pimpinan' && $item->departemen)
                                            <div class="d-flex align-items-start mt-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                                                    <path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1"></path>
                                                    <path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                                                    <path d="M17 10h2a2 2 0 0 1 2 2v1"></path>
                                                    <path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                                                    <path d="M3 13v-1a2 2 0 0 1 2 -2h2"></path>
                                                </svg>
                                                <span class="text-muted small">Dept: {{ $item->departemen->nama_dept }}</span>
                                            </div>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted small italic">Semua Cabang (Global)</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('panel.user.edit', $item->id) }}"
                                            class="btn btn-sm btn-warning"
                                            title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
                                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path>
                                                <path d="M16 5l3 3"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('panel.user.destroy', $item->id) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-sm btn-danger"
                                                title="Hapus"
                                                {{ auth('user')->id() === $item->id ? 'disabled' : '' }}>
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
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-muted mb-2" width="48" height="48" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <circle cx="12" cy="12" r="9"></circle>
                                        <line x1="9" y1="10" x2="9.01" y2="10"></line>
                                        <line x1="15" y1="10" x2="15.01" y2="10"></line>
                                        <path d="M9.5 15.25a3.5 3.5 0 0 1 5 0"></path>
                                    </svg>
                                    <div class="fw-bold">Tidak ada data user</div>
                                    <small class="text-muted">Silakan tambah user baru atau ubah filter pencarian</small>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($users->hasPages())
            <div class="card-footer d-flex align-items-center">
                <p class="m-0 text-muted">
                    Menampilkan
                    <span class="fw-bold">{{ $users->firstItem() ?? 0 }}</span>
                    sampai
                    <span class="fw-bold">{{ $users->lastItem() ?? 0 }}</span>
                    dari
                    <span class="fw-bold">{{ $users->total() }}</span>
                    user
                </p>
                <ul class="pagination m-0 ms-auto">
                    @if ($users->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">‹ Previous</span>
                    </li>
                    @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $users->previousPageUrl() }}" rel="prev">‹ Previous</a>
                    </li>
                    @endif

                    @foreach(range(1, $users->lastPage()) as $i)
                    @if($i == $users->currentPage())
                    <li class="page-item active">
                        <span class="page-link">{{ $i }}</span>
                    </li>
                    @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $users->url($i) }}">{{ $i }}</a>
                    </li>
                    @endif
                    @endforeach

                    @if ($users->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $users->nextPageUrl() }}" rel="next">Next ›</a>
                    </li>
                    @else
                    <li class="page-item disabled">
                        <span class="page-link">Next ›</span>
                    </li>
                    @endif
                </ul>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
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

    .table-striped tbody tr:hover {
        background-color: rgba(0, 83, 197, 0.05);
    }

    .avatar {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        text-transform: uppercase;
    }

    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .card-header {
        background: white;
        border-bottom: 1px solid #f0f0f0;
        padding: 20px;
    }

    .card-body {
        padding: 20px;
    }

    .alert {
        border-radius: 8px;
        border: none;
        padding: 15px 20px;
    }

    .alert-icon {
        width: 24px;
        height: 24px;
        margin-right: 12px;
    }

    .btn {
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.3s;
    }

    .btn-primary {
        background: linear-gradient(135deg, #0053C5 0%, #003d94 100%);
        border: none;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 83, 197, 0.3);
    }

    .form-label {
        font-weight: 500;
        color: #333;
        margin-bottom: 8px;
    }

    .form-control {
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 10px 15px;
        transition: all 0.3s;
    }

    .form-control:focus {
        border-color: #0053C5;
        box-shadow: 0 0 0 0.2rem rgba(0, 83, 197, 0.25);
    }

    .badge {
        font-weight: 500;
        padding: 0.4rem 0.75rem;
    }

    .pagination {
        margin: 0;
    }

    .page-link {
        color: #0053C5;
        border: 1px solid #ddd;
        padding: 8px 12px;
        margin: 0 2px;
        border-radius: 6px;
    }

    .page-link:hover {
        background: #0053C5;
        color: white;
        border-color: #0053C5;
    }

    .page-item.active .page-link {
        background: #0053C5;
        border-color: #0053C5;
        color: white;
    }

    .page-item.disabled .page-link {
        background: #f8f9fa;
        border-color: #ddd;
        color: #6c757d;
        cursor: not-allowed;
    }

    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.875rem;
        }

        .avatar {
            width: 2rem;
            height: 2rem;
        }

        .card-footer {
            flex-direction: column;
            gap: 15px;
        }

        .card-footer p {
            margin-bottom: 0 !important;
        }

        .pagination {
            margin: 0 auto !important;
        }
    }

    .page-header {
        margin-bottom: 1.5rem;
    }

    .page-pretitle {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #6c757d;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 600;
        color: #333;
        margin: 0;
    }

    .icon-lg {
        width: 48px;
        height: 48px;
    }

    .bg-blue-lt {
        background-color: rgba(0, 83, 197, 0.1) !important;
    }

    .text-blue {
        color: #0053C5 !important;
    }
</style>
@endsection
