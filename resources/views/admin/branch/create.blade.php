@extends('admin.layouts.admin')

@section('title', 'Tambah Cabang')
@section('page-title', 'Tambah Cabang')

@section('content')
<div class="row">
    <div class="col-md-7 col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="mdi mdi-office-building-plus" style="font-size:20px; color:var(--primary);"></i>
                <h5 class="card-title mb-0">Form Tambah Cabang</h5>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('panel.branch.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nama Cabang <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" 
                               placeholder="Contoh: YPI Al Azhar Jakarta Selatan" 
                               value="{{ old('name') }}" required>
                        <div class="form-text">Nama cabang harus unik dan belum terdaftar sebelumnya.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Koordinat Lokasi GPS <span class="text-danger">*</span></label>
                        <input type="text" name="lokasi_cabang" class="form-control"
                               placeholder="Contoh: -6.234352, 106.800191"
                               value="{{ old('lokasi_cabang') }}" required>
                        <div class="form-text">
                            Format: <strong>latitude, longitude</strong>. 
                            Dapatkan koordinat dari 
                            <a href="https://maps.google.com" target="_blank">Google Maps</a>.
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Radius Absen (meter) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="radius_cabang" class="form-control"
                                   placeholder="Contoh: 100"
                                   value="{{ old('radius_cabang', 100) }}"
                                   min="1" max="10000" required>
                            <span class="input-group-text">meter</span>
                        </div>
                        <div class="form-text">Jarak maksimal karyawan dari titik lokasi agar presensi valid (1 – 10.000 m).</div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('panel.branch.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save me-1"></i> Simpan Cabang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
