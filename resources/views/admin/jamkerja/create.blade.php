@extends('admin.layouts.admin')

@section('title', 'Tambah Jam Kerja')
@section('page-title', 'Tambah Jam Kerja')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Form Tambah Jam Kerja</h5>
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

                <form action="{{ route('panel.jamkerja.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kode_jam_kerja" class="form-label">Kode Jam Kerja <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('kode_jam_kerja') is-invalid @enderror"
                                    id="kode_jam_kerja" name="kode_jam_kerja"
                                    placeholder="Contoh: JK01"
                                    value="{{ old('kode_jam_kerja') }}"
                                    maxlength="10" required>
                                @error('kode_jam_kerja')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Maksimal 10 karakter</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama_jam_kerja" class="form-label">Nama Jam Kerja <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_jam_kerja') is-invalid @enderror"
                                    id="nama_jam_kerja" name="nama_jam_kerja"
                                    placeholder="Contoh: Shift Pagi"
                                    value="{{ old('nama_jam_kerja') }}"
                                    maxlength="15" required>
                                @error('nama_jam_kerja')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Maksimal 15 karakter</small>
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
                                    value="{{ old('awal_jam_masuk') }}" required>
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
                                    value="{{ old('jam_masuk') }}" required>
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
                                    value="{{ old('akhir_jam_masuk') }}" required>
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
                                    value="{{ old('jam_pulang') }}" required>
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
                                        {{ old('lintashari', '0') == '0' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="lintashari_tidak">
                                        <strong>Tidak</strong> - Jam pulang di hari yang sama
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="lintashari" id="lintashari_ya" value="1"
                                        {{ old('lintashari') == '1' ? 'checked' : '' }}>
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
                    <i class="mdi mdi-information-outline"></i> Penjelasan
                </h5>
            </div>
            <div class="card-body">
                <h6>Arti Setiap Waktu:</h6>

                <div class="mb-3">
                    <strong class="text-primary">
                        <i class="mdi mdi-clock-outline"></i> Awal Jam Masuk
                    </strong>
                    <p class="mb-0 small">Waktu paling awal karyawan dapat melakukan presensi masuk.</p>
                </div>

                <div class="mb-3">
                    <strong class="text-success">
                        <i class="mdi mdi-clock"></i> Jam Masuk
                    </strong>
                    <p class="mb-0 small">Jam kerja resmi dimulai. Presensi setelah jam ini dianggap terlambat.</p>
                </div>

                <div class="mb-3">
                    <strong class="text-warning">
                        <i class="mdi mdi-clock-alert"></i> Akhir Jam Masuk
                    </strong>
                    <p class="mb-0 small">Batas akhir waktu toleransi untuk presensi masuk. Setelah ini dianggap tidak hadir.</p>
                </div>

                <div class="mb-3">
                    <strong class="text-danger">
                        <i class="mdi mdi-clock-end"></i> Jam Pulang
                    </strong>
                    <p class="mb-0 small">Waktu kerja selesai dan karyawan dapat melakukan presensi pulang.</p>
                </div>

                <hr>

                <h6>Contoh Konfigurasi:</h6>

                <div class="card mb-2">
                    <div class="card-body p-2">
                        <strong>Shift Pagi</strong>
                        <ul class="small mb-0 ps-3">
                            <li>Awal: 07:00</li>
                            <li>Masuk: 08:00</li>
                            <li>Akhir: 08:30</li>
                            <li>Pulang: 16:00</li>
                            <li>Lintas Hari: Tidak</li>
                        </ul>
                    </div>
                </div>

                <div class="card mb-2">
                    <div class="card-body p-2">
                        <strong>Shift Malam</strong>
                        <ul class="small mb-0 ps-3">
                            <li>Awal: 19:00</li>
                            <li>Masuk: 20:00</li>
                            <li>Akhir: 20:30</li>
                            <li>Pulang: 04:00</li>
                            <li>Lintas Hari: Ya</li>
                        </ul>
                    </div>
                </div>

                <div class="alert alert-info mt-3 mb-0">
                    <small>
                        <i class="mdi mdi-lightbulb-outline"></i>
                        <strong>Tips:</strong> Untuk shift malam yang jam pulangnya melewati tengah malam (00:00), pilih "Lintas Hari = Ya".
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