@extends('admin.layouts.admin')

@section('title', 'Edit Karyawan')
@section('page-title', 'Edit Karyawan')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Form Edit Karyawan</h5>
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

                <form action="{{ route('panel.karyawan.update', $karyawan->nik) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nik" class="form-label">NIK</label>
                                <input type="text" class="form-control bg-light" id="nik" value="{{ $karyawan->nik }}" disabled>
                                <small class="text-muted">NIK tidak dapat diubah</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror"
                                    id="nama_lengkap" name="nama_lengkap"
                                    value="{{ old('nama_lengkap', $karyawan->nama_lengkap) }}"
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
                                    value="{{ old('jabatan', $karyawan->jabatan) }}"
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
                                    value="{{ old('no_hp', $karyawan->no_hp) }}"
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
                                    <option value="{{ $dept->kode_dept }}"
                                        {{ old('kode_dept', $karyawan->kode_dept) == $dept->kode_dept ? 'selected' : '' }}>
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
                                    <option value="{{ $cbg->kode_cabang }}"
                                        {{ old('kode_cabang', $karyawan->kode_cabang) == $cbg->kode_cabang ? 'selected' : '' }}>
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
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            id="password" name="password"
                            placeholder="Kosongkan jika tidak ingin mengubah password">
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                    </div>

                    <div class="mb-3">
                        <label for="foto" class="form-label">Foto Profil</label>

                        @if($karyawan->foto)
                        <div class="mb-2">
                            <img src="{{ asset('storage/uploads/karyawan/' . $karyawan->foto) }}"
                                alt="{{ $karyawan->nama_lengkap }}"
                                class="rounded"
                                style="max-width: 200px; max-height: 200px;">
                            <p class="text-muted mt-1 mb-0"><small>Foto saat ini</small></p>
                        </div>
                        @endif

                        <input type="file" class="form-control @error('foto') is-invalid @enderror"
                            id="foto" name="foto"
                            accept="image/jpeg,image/png,image/jpg"
                            onchange="previewImage(event)">
                        @error('foto')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB. Kosongkan jika tidak ingin mengubah foto.</small>

                        <!-- Preview Image -->
                        <div id="imagePreview" class="mt-3" style="display: none;">
                            <p class="mb-1"><strong>Preview Foto Baru:</strong></p>
                            <img id="preview" src="" alt="Preview" class="rounded" style="max-width: 200px; max-height: 200px;">
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('panel.karyawan.index') }}" class="btn btn-secondary">
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
                    <i class="mdi mdi-account-circle"></i> Info Karyawan
                </h5>
            </div>
            <div class="card-body text-center">
                @if($karyawan->foto)
                <img src="{{ asset('storage/uploads/karyawan/' . $karyawan->foto) }}"
                    alt="{{ $karyawan->nama_lengkap }}"
                    class="rounded-circle mb-3"
                    style="width: 120px; height: 120px; object-fit: cover;">
                @else
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3"
                    style="width: 120px; height: 120px; font-size: 48px; font-weight: bold;">
                    {{ substr($karyawan->nama_lengkap, 0, 1) }}
                </div>
                @endif

                <h5>{{ $karyawan->nama_lengkap }}</h5>
                <p class="text-muted mb-1">{{ $karyawan->jabatan }}</p>
                <p class="mb-3">
                    <span class="badge bg-info">{{ $karyawan->nik }}</span>
                </p>

                <hr>

                <div class="text-start">
                    <p class="mb-2">
                        <i class="mdi mdi-phone text-primary"></i>
                        <strong>No HP:</strong> {{ $karyawan->no_hp }}
                    </p>
                    <p class="mb-2">
                        <i class="mdi mdi-file-tree text-success"></i>
                        <strong>Departemen:</strong> {{ $karyawan->departemen->nama_dept ?? 'N/A' }}
                    </p>
                    <p class="mb-0">
                        <i class="mdi mdi-office-building text-info"></i>
                        <strong>Cabang:</strong> {{ $karyawan->cabang->nama_cabang ?? 'N/A' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="mdi mdi-alert-circle-outline"></i> Peringatan
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning mb-0">
                    <small>
                        <i class="mdi mdi-alert"></i>
                        Perubahan data karyawan akan mempengaruhi sistem presensi. Pastikan data yang diinput sudah benar.
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