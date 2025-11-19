@extends('admin.layouts.admin')

@section('title', 'Edit Jam Kerja')
@section('page-title', 'Edit Jam Kerja')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Form Edit Jam Kerja</h5>
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

                <form action="{{ route('panel.jamkerja.update', $jamkerja->kode_jam_kerja) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kode_jam_kerja" class="form-label">Kode Jam Kerja</label>
                                <input type="text" class="form-control bg-light" id="kode_jam_kerja" value="{{ $jamkerja->kode_jam_kerja }}" disabled>
                                <small class="text-muted">Kode jam kerja tidak dapat diubah</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama_jam_kerja" class="form-label">Nama Jam Kerja <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_jam_kerja') is-invalid @enderror"
                                    id="nama_jam_kerja" name="nama_jam_kerja"
                                    value="{{ old('nama_jam_kerja', $jamkerja->nama_jam_kerja) }}"
                                    maxlength="15" required>
                                @error('nama_jam_kerja')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h6 class="mb-3">Konfigurasi Waktu Masuk</h6>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="awal_jam_masuk" class="form-label">
                                    Awal Jam Masuk <span class="text-danger">*</span>
                                    <i class="mdi mdi-help-circle text-info" data-bs-toggle="tooltip" title="Waktu paling awal karyawan bisa mulai presensi masuk"></i>
                                </label>
                                <input type="time" class="form-control @error('awal_jam_masuk') is-invalid @enderror"
                                    id="awal_jam_masuk" name="awal_jam_masuk"
                                    value="{{ old('awal_jam_masuk', date('H:i', strtotime($jamkerja->awal_jam_masuk))) }}" required>
                                @error('awal_jam_masuk')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="jam_masuk" class="form-label">
                                    Jam Masuk <span class="text-danger">*</span>
                                    <i class="mdi mdi-help-circle text-info" data-bs-toggle="tooltip" title="Jam kerja resmi dimulai"></i>
                                </label>
                                <input type="time" class="form-control @error('jam_masuk') is-invalid @enderror"
                                    id="jam_masuk" name="jam_masuk"
                                    value="{{ old('jam_masuk', date('H:i', strtotime($jamkerja->jam_masuk))) }}" required>
                                @error('jam_masuk')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="akhir_jam_masuk" class="form-label">
                                    Akhir Jam Masuk <span class="text-danger">*</span>
                                    <i class="mdi mdi-help-circle text-info" data-bs-toggle="tooltip" title="Batas waktu toleransi keterlambatan"></i>
                                </label>
                                <input type="time" class="form-control @error('akhir_jam_masuk') is-invalid @enderror"
                                    id="akhir_jam_masuk" name="akhir_jam_masuk"
                                    value="{{ old('akhir_jam_masuk', date('H:i', strtotime($jamkerja->akhir_jam_masuk))) }}" required>
                                @error('akhir_jam_masuk')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h6 class="mb-3">Waktu Pulang</h6>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jam_pulang" class="form-label">
                                    Jam Pulang <span class="text-danger">*</span>
                                    <i class="mdi mdi-help-circle text-info" data-bs-toggle="tooltip" title="Waktu kerja selesai"></i>
                                </label>
                                <input type="time" class="form-control @error('jam_pulang') is-invalid @enderror"
                                    id="jam_pulang" name="jam_pulang"
                                    value="{{ old('jam_pulang', date('H:i', strtotime($jamkerja->jam_pulang))) }}" required>
                                @error('jam_pulang')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Lintas Hari <span class="text-danger">*</span></label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="lintashari" id="lintashari_tidak" value="0"
                                        {{ old('lintashari', $jamkerja->lintashari) == '0' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="lintashari_tidak">
                                        <strong>Tidak</strong> - Jam pulang di hari yang sama
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="lintashari" id="lintashari_ya" value="1"
                                        {{ old('lintashari', $jamkerja->lintashari) == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="lintashari_ya">
                                        <strong>Ya</strong> - Jam pulang melewati tengah malam (shift malam)
                                    </label>
                                </div>
                                @error('lintashari')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('panel.jamkerja.index') }}" class="btn btn-secondary">
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
                    <i class="mdi mdi-clock-outline"></i> Detail Jam Kerja
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <h4 class="text-primary mb-1">{{ $jamkerja->nama_jam_kerja }}</h4>
                    <span class="badge bg-warning text-dark">{{ $jamkerja->kode_jam_kerja }}</span>
                </div>

                <hr>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">
                            <i class="mdi mdi-clock-outline text-primary"></i> Awal Masuk
                        </span>
                        <strong>{{ date('H:i', strtotime($jamkerja->awal_jam_masuk)) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">
                            <i class="mdi mdi-clock text-success"></i> Jam Masuk
                        </span>
                        <strong>{{ date('H:i', strtotime($jamkerja->jam_masuk)) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">
                            <i class="mdi mdi-clock-alert text-warning"></i> Akhir Masuk
                        </span>
                        <strong>{{ date('H:i', strtotime($jamkerja->akhir_jam_masuk)) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">
                            <i class="mdi mdi-clock-end text-danger"></i> Jam Pulang
                        </span>
                        <strong>{{ date('H:i', strtotime($jamkerja->jam_pulang)) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">
                            <i class="mdi mdi-weather-night text-info"></i> Lintas Hari
                        </span>
                        @if($jamkerja->lintashari == '1')
                        <span class="badge bg-info">Ya</span>
                        @else
                        <span class="badge bg-secondary">Tidak</span>
                        @endif
                    </div>
                </div>

                <hr>

                @if($total_penggunaan > 0)
                <div class="alert alert-info mb-0">
                    <small>
                        <i class="mdi mdi-information-outline"></i>
                        Jam kerja ini sedang digunakan oleh <strong>{{ $total_penggunaan }}</strong> konfigurasi departemen
                    </small>
                </div>
                @endif
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
                        Perubahan jam kerja akan mempengaruhi semua konfigurasi yang menggunakan jam kerja ini. Pastikan perubahan sudah sesuai.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
@endpush
@endsection