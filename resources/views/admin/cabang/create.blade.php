@extends('admin.layouts.admin')

@section('title', 'Tambah Cabang')
@section('page-title', 'Tambah Cabang')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Form Tambah Cabang</h5>
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

                <form action="{{ route('panel.cabang.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="kode_cabang" class="form-label">Kode Cabang <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('kode_cabang') is-invalid @enderror"
                            id="kode_cabang" name="kode_cabang"
                            placeholder="Contoh: CBG001"
                            value="{{ old('kode_cabang') }}"
                            maxlength="10" required>
                        @error('kode_cabang')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Maksimal 10 karakter</small>
                    </div>

                    <div class="mb-3">
                        <label for="nama_cabang" class="form-label">Nama Cabang <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_cabang') is-invalid @enderror"
                            id="nama_cabang" name="nama_cabang"
                            placeholder="Contoh: YPI Al Azhar Jakarta Pusat"
                            value="{{ old('nama_cabang') }}"
                            maxlength="50" required>
                        @error('nama_cabang')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="lokasi_cabang" class="form-label">Lokasi Cabang (Koordinat) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('lokasi_cabang') is-invalid @enderror"
                            id="lokasi_cabang" name="lokasi_cabang"
                            placeholder="Contoh: -6.2088,106.8456"
                            value="{{ old('lokasi_cabang') }}"
                            required>
                        @error('lokasi_cabang')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Format: latitude,longitude</small>
                    </div>

                    <div class="mb-3">
                        <label for="radius_cabang" class="form-label">Radius Cabang (meter) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('radius_cabang') is-invalid @enderror"
                            id="radius_cabang" name="radius_cabang"
                            placeholder="Contoh: 100"
                            value="{{ old('radius_cabang') }}"
                            min="1" required>
                        @error('radius_cabang')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Radius area presensi dalam meter</small>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('panel.cabang.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Informasi</h5>
            </div>
            <div class="card-body">
                <p><strong>Petunjuk Pengisian:</strong></p>
                <ul class="ps-3">
                    <li>Kode cabang harus unik dan maksimal 10 karakter</li>
                    <li>Nama cabang diisi dengan lengkap</li>
                    <li>Lokasi diisi dengan koordinat GPS (latitude,longitude)</li>
                    <li>Radius menentukan jarak maksimal presensi dari titik pusat</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection