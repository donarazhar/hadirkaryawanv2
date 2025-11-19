@extends('admin.layouts.admin')

@section('title', 'Tambah Departemen')
@section('page-title', 'Tambah Departemen')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Form Tambah Departemen</h5>
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

                <form action="{{ route('panel.departemen.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="kode_dept" class="form-label">Kode Departemen <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('kode_dept') is-invalid @enderror"
                            id="kode_dept" name="kode_dept"
                            placeholder="Contoh: IT, HRD, FIN"
                            value="{{ old('kode_dept') }}"
                            maxlength="10" required>
                        @error('kode_dept')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Maksimal 10 karakter (gunakan kode singkat)</small>
                    </div>

                    <div class="mb-3">
                        <label for="nama_dept" class="form-label">Nama Departemen <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_dept') is-invalid @enderror"
                            id="nama_dept" name="nama_dept"
                            placeholder="Contoh: Information Technology"
                            value="{{ old('nama_dept') }}"
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
                <h5 class="card-title mb-0">
                    <i class="mdi mdi-information-outline"></i> Informasi
                </h5>
            </div>
            <div class="card-body">
                <p><strong>Petunjuk Pengisian:</strong></p>
                <ul class="ps-3">
                    <li class="mb-2">Kode departemen harus unik dan maksimal 10 karakter</li>
                    <li class="mb-2">Gunakan kode singkat yang mudah diingat (IT, HRD, FIN, dll)</li>
                    <li class="mb-2">Nama departemen diisi dengan lengkap dan jelas</li>
                    <li class="mb-2">Pastikan tidak ada duplikasi kode departemen</li>
                </ul>

                <hr>

                <p><strong>Contoh Departemen:</strong></p>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="badge bg-success">IT</span></td>
                                <td>Information Technology</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-success">HRD</span></td>
                                <td>Human Resources</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-success">FIN</span></td>
                                <td>Finance</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-success">MKT</span></td>
                                <td>Marketing</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-success">OPS</span></td>
                                <td>Operations</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection