@extends('admin.layouts.admin')

@section('title', 'Data Departemen')
@section('page-title', 'Data Departemen')

@section('content')
<!-- Page Header -->
<div class="page-header d-print-none mb-3">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Kelola Data</div>
                <h2 class="page-title">Data Departemen</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('panel.departemen.create') }}" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                    Tambah Departemen
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
                <form action="{{ route('panel.departemen.index') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-10">
                            <label class="form-label">Cari Departemen</label>
                            <input type="text" name="search" class="form-control"
                                placeholder="Cari berdasarkan kode atau nama departemen..."
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
                                <th width="80">No</th>
                                <th width="200">Kode Departemen</th>
                                <th width="500">Nama Departemen</th>
                                <th width="150" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($departemen as $index => $item)
                            <tr>
                                <td>{{ $departemen->firstItem() + $index }}</td>
                                <td>
                                    <span class="badge bg-success">{{ $item->kode_dept }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="avatar me-2 bg-green-lt text-green">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <rect x="4" y="4" width="6" height="6" rx="1" />
                                                <rect x="14" y="4" width="6" height="6" rx="1" />
                                                <rect x="4" y="14" width="6" height="6" rx="1" />
                                                <rect x="14" y="14" width="6" height="6" rx="1" />
                                            </svg>
                                        </span>
                                        <div>
                                            <div class="fw-bold">{{ $item->nama_dept }}</div>
                                            <small class="text-muted">Kode: {{ $item->kode_dept }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('panel.departemen.edit', $item->kode_dept) }}"
                                            class="btn btn-sm btn-warning"
                                            title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
                                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path>
                                                <path d="M16 5l3 3"></path>
                                            </svg>
                                        </a>
                                        <form id="delete-form-{{ $item->kode_dept }}"
                                            action="{{ route('panel.departemen.destroy', $item->kode_dept) }}"
                                            method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                onclick="confirmDelete('{{ $item->kode_dept }}')"
                                                class="btn btn-sm btn-danger"
                                                title="Hapus">
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
                                <td colspan="4" class="text-center text-muted py-5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-muted mb-2" width="48" height="48" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <circle cx="12" cy="12" r="9"></circle>
                                        <line x1="9" y1="10" x2="9.01" y2="10"></line>
                                        <line x1="15" y1="10" x2="15.01" y2="10"></line>
                                        <path d="M9.5 15.25a3.5 3.5 0 0 1 5 0"></path>
                                    </svg>
                                    <div class="fw-bold">Tidak ada data departemen</div>
                                    <small class="text-muted">Silakan tambah departemen baru atau ubah filter pencarian</small>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($departemen->hasPages())
            <div class="card-footer d-flex align-items-center">
                <p class="m-0 text-muted">
                    Menampilkan
                    <span class="fw-bold">{{ $departemen->firstItem() ?? 0 }}</span>
                    sampai
                    <span class="fw-bold">{{ $departemen->lastItem() ?? 0 }}</span>
                    dari
                    <span class="fw-bold">{{ $departemen->total() }}</span>
                    departemen
                </p>
                <ul class="pagination m-0 ms-auto">
                    {{-- Previous Page Link --}}
                    @if ($departemen->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">‹ Previous</span>
                    </li>
                    @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $departemen->previousPageUrl() }}" rel="prev">‹ Previous</a>
                    </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach(range(1, $departemen->lastPage()) as $i)
                    @if($i == $departemen->currentPage())
                    <li class="page-item active">
                        <span class="page-link">{{ $i }}</span>
                    </li>
                    @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $departemen->url($i) }}">{{ $i }}</a>
                    </li>
                    @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($departemen->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $departemen->nextPageUrl() }}" rel="next">Next ›</a>
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

    /* Hover effect untuk row */
    .table-striped tbody tr:hover {
        background-color: rgba(0, 83, 197, 0.05);
    }

    /* Avatar styling */
    .avatar {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Card styling */
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

    /* Alert styling */
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

    /* Button styling */
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

    /* Form styling */
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

    /* Badge styling */
    .badge {
        font-weight: 500;
        padding: 0.4rem 0.75rem;
    }

    /* Pagination styling */
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
    }

    .page-item.disabled .page-link {
        background: #f8f9fa;
        border-color: #ddd;
        color: #6c757d;
        cursor: not-allowed;
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

    /* Page header styling */
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

    /* Empty state styling */
    .icon-lg {
        width: 48px;
        height: 48px;
    }

    /* Color utilities */
    .bg-green-lt {
        background-color: rgba(40, 167, 69, 0.1) !important;
    }

    .text-green {
        color: #28a745 !important;
    }
</style>
@endsection