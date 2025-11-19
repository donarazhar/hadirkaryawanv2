@extends('admin.layouts.admin')

@section('title', 'Data Jam Kerja')
@section('page-title', 'Data Jam Kerja')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Daftar Jam Kerja</h5>
            <a href="{{ route('panel.jamkerja.create') }}" class="btn btn-primary">
                <i class="mdi mdi-plus"></i> Tambah Jam Kerja
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
        <form action="{{ route('panel.jamkerja.index') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan kode atau nama jam kerja..." value="{{ request('search') }}">
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
                        <th width="12%">Kode</th>
                        <th width="15%">Nama Jam Kerja</th>
                        <th width="12%">Awal Jam Masuk</th>
                        <th width="12%">Jam Masuk</th>
                        <th width="12%">Akhir Jam Masuk</th>
                        <th width="12%">Jam Pulang</th>
                        <th width="10%">Lintas Hari</th>
                        <th width="10%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jamkerja as $index => $item)
                    <tr>
                        <td>{{ $jamkerja->firstItem() + $index }}</td>
                        <td><span class="badge bg-warning text-dark">{{ $item->kode_jam_kerja }}</span></td>
                        <td><strong>{{ $item->nama_jam_kerja }}</strong></td>
                        <td>
                            <i class="mdi mdi-clock-outline text-primary"></i>
                            {{ date('H:i', strtotime($item->awal_jam_masuk)) }}
                        </td>
                        <td>
                            <i class="mdi mdi-clock text-success"></i>
                            {{ date('H:i', strtotime($item->jam_masuk)) }}
                        </td>
                        <td>
                            <i class="mdi mdi-clock-alert text-warning"></i>
                            {{ date('H:i', strtotime($item->akhir_jam_masuk)) }}
                        </td>
                        <td>
                            <i class="mdi mdi-clock-end text-danger"></i>
                            {{ date('H:i', strtotime($item->jam_pulang)) }}
                        </td>
                        <td>
                            @if($item->lintashari == '1')
                            <span class="badge bg-info">
                                <i class="mdi mdi-weather-night"></i> Ya
                            </span>
                            @else
                            <span class="badge bg-secondary">Tidak</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('panel.jamkerja.edit', $item->kode_jam_kerja) }}" class="btn btn-warning btn-sm" title="Edit">
                                <i class="mdi mdi-pencil"></i>
                            </a>
                            <form id="delete-form-{{ $item->kode_jam_kerja }}" action="{{ route('panel.jamkerja.destroy', $item->kode_jam_kerja) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete('{{ $item->kode_jam_kerja }}')" class="btn btn-danger btn-sm" title="Hapus">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <i class="mdi mdi-information-outline" style="font-size: 48px; color: #ccc;"></i>
                            <p class="text-muted mt-2">Tidak ada data jam kerja</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                Menampilkan {{ $jamkerja->firstItem() ?? 0 }} - {{ $jamkerja->lastItem() ?? 0 }} dari {{ $jamkerja->total() }} data
            </div>
            <div>
                {{ $jamkerja->links() }}
            </div>
        </div>
    </div>
</div>
@endsection