@extends('admin.layouts.admin')

@section('title', 'Data Karyawan')
@section('page-title', 'Data Karyawan')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Daftar Karyawan YPI Al Azhar</h5>
            <a href="{{ route('panel.karyawan.create') }}" class="btn btn-primary">
                <i class="mdi mdi-plus"></i> Tambah Karyawan
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
        <form action="{{ route('panel.karyawan.index') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari NIK, Nama, atau Jabatan..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="kode_dept" class="form-select">
                        <option value="">-- Semua Departemen --</option>
                        @foreach($departemen as $dept)
                        <option value="{{ $dept->kode_dept }}" {{ request('kode_dept') == $dept->kode_dept ? 'selected' : '' }}>
                            {{ $dept->nama_dept }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
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
                        <th width="8%">Foto</th>
                        <th width="10%">NIK</th>
                        <th width="20%">Nama Lengkap</th>
                        <th width="12%">Jabatan</th>
                        <th width="15%">Departemen</th>
                        <th width="15%">Cabang</th>
                        <th width="10%">No HP</th>
                        <th width="10%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($karyawan as $index => $item)
                    <tr>
                        <td>{{ $karyawan->firstItem() + $index }}</td>
                        <td>
                            @if($item->foto)
                            <img src="{{ asset('storage/uploads/karyawan/' . $item->foto) }}"
                                alt="{{ $item->nama_lengkap }}"
                                class="rounded-circle"
                                style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                style="width: 40px; height: 40px; font-size: 18px; font-weight: bold;">
                                {{ substr($item->nama_lengkap, 0, 1) }}
                            </div>
                            @endif
                        </td>
                        <td><span class="badge bg-info">{{ $item->nik }}</span></td>
                        <td><strong>{{ $item->nama_lengkap }}</strong></td>
                        <td>{{ $item->jabatan }}</td>
                        <td>
                            @if($item->departemen)
                            <span class="badge bg-success">{{ $item->departemen->nama_dept }}</span>
                            @else
                            <span class="badge bg-secondary">N/A</span>
                            @endif
                        </td>
                        <td>
                            @if($item->cabang)
                            <span class="badge bg-primary">{{ $item->cabang->nama_cabang }}</span>
                            @else
                            <span class="badge bg-secondary">N/A</span>
                            @endif
                        </td>
                        <td>{{ $item->no_hp }}</td>
                        <td class="text-center">
                            <a href="{{ route('panel.karyawan.edit', $item->nik) }}" class="btn btn-warning btn-sm" title="Edit">
                                <i class="mdi mdi-pencil"></i>
                            </a>
                            <form id="delete-form-{{ $item->nik }}" action="{{ route('panel.karyawan.destroy', $item->nik) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete('{{ $item->nik }}')" class="btn btn-danger btn-sm" title="Hapus">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <i class="mdi mdi-information-outline" style="font-size: 48px; color: #ccc;"></i>
                            <p class="text-muted mt-2">Tidak ada data karyawan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                Menampilkan {{ $karyawan->firstItem() ?? 0 }} - {{ $karyawan->lastItem() ?? 0 }} dari {{ $karyawan->total() }} data
            </div>
            <div>
                {{ $karyawan->links() }}
            </div>
        </div>
    </div>
</div>
@endsection