@extends('admin.layouts.admin')

@section('title', 'Tambah Karyawan')
@section('page-title', 'Tambah Karyawan')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Form Tambah Karyawan</h5>
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

                <form action="{{ route('panel.karyawan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nik" class="form-label">NIK <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nik') is-invalid @enderror"
                                    id="nik" name="nik"
                                    placeholder="Contoh: 2024001"
                                    value="{{ old('nik') }}"
                                    maxlength="10" required>
                                @error('nik')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Maksimal 10 karakter, unik</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror"
                                    id="nama_lengkap" name="nama_lengkap"
                                    placeholder="Contoh: Ahmad Rizki"
                                    value="{{ old('nama_lengkap') }}"
                                    maxlength="100" required>
                                @error('nama_lengkap')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jabatan" class="form-label">Jabatan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('jabatan') is-invalid @enderror"
                                    id="jabatan" name="jabatan"
                                    placeholder="Contoh: IT Manager"
                                    value="{{ old('jabatan') }}"
                                    maxlength="20" required>
                                @error('jabatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="no_hp" class="form-label">No HP <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('no_hp') is-invalid @enderror"
                                    id="no_hp" name="no_hp"
                                    placeholder="Contoh: 081234567890"
                                    value="{{ old('no_hp') }}"
                                    maxlength="15" required>
                                @error('no_hp')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kode_dept" class="form-label">Departemen <span class="text-danger">*</span></label>
                                <select class="form-select @error('kode_dept') is-invalid @enderror"
                                    id="kode_dept" name="kode_dept" required>
                                    <option value="">-- Pilih Departemen --</option>
                                    @foreach($departemen as $dept)
                                    <option value="{{ $dept->kode_dept }}" {{ old('kode_dept') == $dept->kode_dept ? 'selected' : '' }}>
                                        {{ $dept->nama_dept }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('kode_dept')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kode_cabang" class="form-label">Cabang <span class="text-danger">*</span></label>
                                <select class="form-select @error('kode_cabang') is-invalid @enderror"
                                    id="kode_cabang" name="kode_cabang" required>
                                    <option value="">-- Pilih Cabang --</option>
                                    @foreach($cabang as $cbg)
                                    <option value="{{ $cbg->kode_cabang }}" {{ old('kode_cabang') == $cbg->kode_cabang ? 'selected' : '' }}>
                                        {{ $cbg->nama_cabang }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('kode_cabang')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            id="password" name="password"
                            placeholder="Minimal 4 karakter"
                            required>
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Password untuk login karyawan, minimal 4 karakter</small>
                    </div>

                    <div class="mb-3">
                        <label for="foto" class="form-label">Foto Profil</label>
                        <input type="file" class="form-control @error('foto') is-invalid @enderror"
                            id="foto" name="foto"
                            accept="image/jpeg,image/png,image/jpg"
                            onchange="previewImage(event)">
                        @error('foto')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB</small>

                        <!-- Preview Image -->
                        <div id="imagePreview" class="mt-3" style="display: none;">
                            <img id="preview" src="" alt="Preview" class="rounded" style="max-width: 200px; max-height: 200px;">
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('panel.karyawan.index') }}" class="btn btn-secondary">
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
                    <li class="mb-2">NIK harus unik dan maksimal 10 karakter</li>
                    <li class="mb-2">Nama lengkap tanpa singkatan</li>
                    <li class="mb-2">Jabatan sesuai posisi di perusahaan</li>
                    <li class="mb-2">No HP aktif dan dapat dihubungi</li>
                    <li class="mb-2">Departemen dan Cabang harus dipilih</li>
                    <li class="mb-2">Password akan digunakan untuk login</li>
                    <li class="mb-2">Foto profil opsional, format JPG/PNG</li>
                </ul>

                <hr>

                <div class="alert alert-info mb-0">
                    <small>
                        <i class="mdi mdi-lightbulb-outline"></i>
                        <strong>Tips:</strong> Gunakan password yang mudah diingat karyawan atau berikan informasi password kepada karyawan setelah data tersimpan.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview').src = e.target.result;
                document.getElementById('imagePreview').style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    }
</script>
@endpush
@endsection