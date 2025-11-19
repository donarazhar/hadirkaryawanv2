@extends('admin.layouts.admin')

@section('title', 'Data Departemen')
@section('page-title', 'Data Departemen')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Daftar Departemen YPI Al Azhar</h5>
            <a href="{{ route('panel.departemen.create') }}" class="btn btn-primary">
                <i class="mdi mdi-plus"></i> Tambah Departemen
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Alert Messages -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="mdi mdi-alert-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="mdi mdi-alert me-2"></i>{{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- Search Form -->
        <form action="{{ route('panel.departemen.index') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan kode atau nama departemen..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="mdi mdi-magnify"></i> Cari
                    </button>
                </div>
            </div>
        </form>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="10%">No</th>
                        <th width="20%">Kode Departemen</th>
                        <th width="50%">Nama Departemen</th>
                        <th width="20%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departemen as $index => $item)
                    <tr>
                        <td>{{ $departemen->firstItem() + $index }}</td>
                        <td><span class="badge bg-success">{{ $item->kode_dept }}</span></td>
                        <td><strong>{{ $item->nama_dept }}</strong></td>
                        <td class="text-center">
                            <a href="{{ route('panel.departemen.edit', $item->kode_dept) }}" class="btn btn-warning btn-sm" title="Edit">
                                <i class="mdi mdi-pencil"></i>
                            </a>
                            <form id="delete-form-{{ $item->kode_dept }}" action="{{ route('panel.departemen.destroy', $item->kode_dept) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete('{{ $item->kode_dept }}')" class="btn btn-danger btn-sm" title="Hapus">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">
                            <i class="mdi mdi-information-outline" style="font-size: 48px; color: #ccc;"></i>
                            <p class="text-muted mt-2">Tidak ada data departemen</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                Menampilkan {{ $departemen->firstItem() ?? 0 }} - {{ $departemen->lastItem() ?? 0 }} dari {{ $departemen->total() }} data
            </div>
            <div>
                {{ $departemen->links() }}
            </div>
        </div>
    </div>
</div>
@endsection