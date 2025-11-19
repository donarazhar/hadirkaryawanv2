@extends('admin.layouts.admin')

@section('title', 'Edit Departemen')
@section('page-title', 'Edit Departemen')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Form Edit Departemen</h5>
            </div>
            <div class="card-body">
                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <strong>Error!</strong> Terdapat kesalahan pada form:
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <form action="{{ route('panel.departemen.update', $departemen->kode_dept) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="kode_dept" class="form-label">Kode Departemen</label>
                        <input type="text" class="form-control bg-light" id="kode_dept" value="{{ $departemen->kode_dept }}" disabled>
                        <small class="text-muted">Kode departemen tidak dapat diubah</small>
                    </div>

                    <div class="mb-3">
                        <label for="nama_dept" class="form-label">Nama Departemen <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_dept') is-invalid @enderror"
                            id="nama_dept" name="nama_dept"
                            value="{{ old('nama_dept', $departemen->nama_dept) }}"
                            maxlength="255" required>
                        @error('nama_dept')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('panel.departemen.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="mdi mdi-chart-bar"></i> Statistik Departemen
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted mb-1">Total Karyawan</label>
                    <h3 class="mb-0">{{ $departemen->karyawan->count() }}</h3>
                </div>

                @if($departemen->karyawan->count() > 0)
                <hr>
                <p class="mb-2"><strong>Karyawan di Departemen Ini:</strong></p>
                <div class="list-group" style="max-height: 300px; overflow-y: auto;">
                    @foreach($departemen->karyawan->take(10) as $karyawan)
                    <div class="list-group-item">
                        <div class="d-flex align-items-center">
                            @if($karyawan->foto)
                            <img src="{{ asset('storage/uploads/karyawan/' . $karyawan->foto) }}"
                                alt="{{ $karyawan->nama_lengkap }}"
                                class="rounded-circle me-2"
                                style="width: 30px; height: 30px; object-fit: cover;">
                            @else
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2"
                                style="width: 30px; height: 30px; font-size: 12px;">
                                {{ substr($karyawan->nama_lengkap, 0, 1) }}
                            </div>
                            @endif
                            <div>
                                <small class="fw-bold d-block">{{ $karyawan->nama_lengkap }}</small>
                                <small class="text-muted">{{ $karyawan->nik }} - {{ $karyawan->jabatan }}</small>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    @if($departemen->karyawan->count() > 10)
                    <div class="list-group-item text-center text-muted">
                        <small>Dan {{ $departemen->karyawan->count() - 10 }} karyawan lainnya...</small>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="mdi mdi-alert-circle-outline"></i> Peringatan
                </h5>
            </div>
            <div class="card-body">
                @if($departemen->karyawan->count() > 0)
                <div class="alert alert-warning mb-0">
                    <small>
                        <i class="mdi mdi-alert"></i>
                        Departemen ini memiliki <strong>{{ $departemen->karyawan->count() }} karyawan</strong>.
                        Pastikan untuk memindahkan atau menghapus karyawan terlebih dahulu sebelum menghapus departemen.
                    </small>
                </div>
                @else
                <div class="alert alert-info mb-0">
                    <small>
                        <i class="mdi mdi-information"></i>
                        Departemen ini belum memiliki karyawan dan dapat dihapus kapan saja.
                    </small>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection