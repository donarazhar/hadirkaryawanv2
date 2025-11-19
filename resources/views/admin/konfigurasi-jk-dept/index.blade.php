@extends('admin.layouts.admin')

@section('title', 'Konfigurasi Jam Kerja Departemen')
@section('page-title', 'Konfigurasi Jam Kerja Departemen')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Daftar Konfigurasi Jam Kerja Departemen</h5>
            <a href="{{ route('panel.konfigurasi-jk-dept.create') }}" class="btn btn-primary">
                <i class="mdi mdi-plus"></i> Tambah Konfigurasi
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

        <!-- Search & Filter Form -->
        <form action="{{ route('panel.konfigurasi-jk-dept.index') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan kode konfigurasi..." value="{{ request('search') }}">
                </div>
                <div class="col-md-5">
                    <select name="kode_cabang" class="form-select">
                        <option value="">-- Semua Cabang --</option>
                        @foreach($cabang as $cbg)
                        <option value="{{ $cbg->kode_cabang }}" {{ request('kode_cabang') == $cbg->kode_cabang ? 'selected' : '' }}>
                            {{ $cbg->nama_cabang }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="mdi mdi-magnify"></i> Filter
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
                        <th width="15%">Kode Konfigurasi</th>
                        <th width="25%">Cabang</th>
                        <th width="25%">Departemen</th>
                        <th width="15%">Jumlah Hari</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($konfigurasi as $index => $item)
                    <tr>
                        <td>{{ $konfigurasi->firstItem() + $index }}</td>
                        <td><span class="badge bg-info">{{ $item->kode_jk_dept }}</span></td>
                        <td>
                            @if($item->cabang)
                            <i class="mdi mdi-office-building text-primary"></i>
                            <strong>{{ $item->cabang->nama_cabang }}</strong>
                            @else
                            <span class="badge bg-secondary">N/A</span>
                            @endif
                        </td>
                        <td>
                            @if($item->departemen)
                            <i class="mdi mdi-file-tree text-success"></i>
                            <strong>{{ $item->departemen->nama_dept }}</strong>
                            @else
                            <span class="badge bg-secondary">N/A</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-warning text-dark">
                                <i class="mdi mdi-calendar"></i> {{ $item->details->count() }} Hari
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('panel.konfigurasi-jk-dept.show', $item->kode_jk_dept) }}" class="btn btn-info btn-sm" title="Detail">
                                <i class="mdi mdi-eye"></i>
                            </a>
                            <a href="{{ route('panel.konfigurasi-jk-dept.edit', $item->kode_jk_dept) }}" class="btn btn-warning btn-sm" title="Edit">
                                <i class="mdi mdi-pencil"></i>
                            </a>
                            <form id="delete-form-{{ $item->kode_jk_dept }}" action="{{ route('panel.konfigurasi-jk-dept.destroy', $item->kode_jk_dept) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete('{{ $item->kode_jk_dept }}')" class="btn btn-danger btn-sm" title="Hapus">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="mdi mdi-information-outline" style="font-size: 48px; color: #ccc;"></i>
                            <p class="text-muted mt-2">Tidak ada data konfigurasi jam kerja departemen</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                Menampilkan {{ $konfigurasi->firstItem() ?? 0 }} - {{ $konfigurasi->lastItem() ?? 0 }} dari {{ $konfigurasi->total() }} data
            </div>
            <div>
                {{ $konfigurasi->links() }}
            </div>
        </div>
    </div>
</div>
@endsection