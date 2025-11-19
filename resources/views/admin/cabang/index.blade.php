@extends('admin.layouts.admin')

@section('title', 'Data Cabang')
@section('page-title', 'Data Cabang')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Daftar Cabang YPI Al Azhar</h5>
            <a href="{{ route('panel.cabang.create') }}" class="btn btn-primary">
                <i class="mdi mdi-plus"></i> Tambah Cabang
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
        <form action="{{ route('panel.cabang.index') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan kode, nama, atau lokasi cabang..." value="{{ request('search') }}">
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
                        <th width="5%">No</th>
                        <th width="15%">Kode Cabang</th>
                        <th width="25%">Nama Cabang</th>
                        <th width="35%">Lokasi</th>
                        <th width="10%">Radius (m)</th>
                        <th width="10%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cabang as $index => $item)
                    <tr>
                        <td>{{ $cabang->firstItem() + $index }}</td>
                        <td><span class="badge bg-primary">{{ $item->kode_cabang }}</span></td>
                        <td><strong>{{ $item->nama_cabang }}</strong></td>
                        <td>{{ $item->lokasi_cabang }}</td>
                        <td>{{ number_format($item->radius_cabang) }} m</td>
                        <td class="text-center">
                            <a href="{{ route('panel.cabang.edit', $item->kode_cabang) }}" class="btn btn-warning btn-sm" title="Edit">
                                <i class="mdi mdi-pencil"></i>
                            </a>
                            <form id="delete-form-{{ $item->kode_cabang }}" action="{{ route('panel.cabang.destroy', $item->kode_cabang) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete('{{ $item->kode_cabang }}')" class="btn btn-danger btn-sm" title="Hapus">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="mdi mdi-information-outline" style="font-size: 48px; color: #ccc;"></i>
                            <p class="text-muted mt-2">Tidak ada data cabang</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                Menampilkan {{ $cabang->firstItem() ?? 0 }} - {{ $cabang->lastItem() ?? 0 }} dari {{ $cabang->total() }} data
            </div>
            <div>
                {{ $cabang->links() }}
            </div>
        </div>
    </div>
</div>
@endsection