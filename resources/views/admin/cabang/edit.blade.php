@extends('admin.layouts.admin')

@section('title', 'Edit Cabang')
@section('page-title', 'Edit Cabang')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Form Edit Cabang</h5>
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

                <form action="{{ route('panel.cabang.update', $cabang->kode_cabang) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="kode_cabang" class="form-label">Kode Cabang</label>
                        <input type="text" class="form-control" id="kode_cabang" value="{{ $cabang->kode_cabang }}" disabled>
                        <small class="text-muted">Kode cabang tidak dapat diubah</small>
                    </div>

                    <div class="mb-3">
                        <label for="nama_cabang" class="form-label">Nama Cabang <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_cabang') is-invalid @enderror"
                            id="nama_cabang" name="nama_cabang"
                            value="{{ old('nama_cabang', $cabang->nama_cabang) }}"
                            maxlength="50" required>
                        @error('nama_cabang')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="lokasi_cabang" class="form-label">Lokasi Cabang (Koordinat) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('lokasi_cabang') is-invalid @enderror"
                            id="lokasi_cabang" name="lokasi_cabang"
                            value="{{ old('lokasi_cabang', $cabang->lokasi_cabang) }}"
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
                            value="{{ old('radius_cabang', $cabang->radius_cabang) }}"
                            min="1" required>
                        @error('radius_cabang')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('panel.cabang.index') }}" class="btn btn-secondary">
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
</div>
@endsection