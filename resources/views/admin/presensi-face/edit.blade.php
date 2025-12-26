@extends('admin.layouts.admin')

@section('title', 'Edit Presensi Face')
@section('page-title', 'Edit Presensi Face')

@push('styles')
<style>
    /* Custom badge colors */
    .badge.bg-blue {
        background-color: #0054a6 !important;
        color: #ffffff !important;
    }
    
    .badge.bg-purple {
        background-color: #7c3aed !important;
        color: #ffffff !important;
    }
    
    .badge.bg-info {
        background-color: #3b82f6 !important;
        color: #ffffff !important;
    }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header bg-light">
        <h5 class="card-title mb-0">
            <i class="mdi mdi-pencil"></i> Form Edit Presensi Face Recognition
        </h5>
    </div>
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <strong><i class="mdi mdi-alert-circle"></i> Error!</strong> Terdapat kesalahan pada form:
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <form action="{{ route('panel.presensi-face.update', $presensi->id) }}" method="POST" enctype="multipart/form-data" id="formPresensi">
            @csrf
            @method('PUT')

            <!-- Karyawan Info -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="mdi mdi-card-account-details"></i> NIK <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="nik" class="form-control bg-light @error('nik') is-invalid @enderror"
                        value="{{ old('nik', $presensi->nik) }}" required readonly>
                    @error('nik')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if($presensi->karyawan)
                    <small class="text-muted">
                        <i class="mdi mdi-account"></i> {{ $presensi->karyawan->nama_lengkap }}
                        @if($presensi->karyawan->cabang)
                        <span class="badge badge-sm bg-blue ms-2">
                            <i class="mdi mdi-office-building"></i> {{ $presensi->karyawan->cabang->nama_cabang }}
                        </span>
                        @endif
                        @if($presensi->karyawan->departemen)
                        <span class="badge badge-sm bg-purple ms-1">
                            <i class="mdi mdi-account-group"></i> {{ $presensi->karyawan->departemen->nama_dept }}
                        </span>
                        @endif
                    </small>
                    @endif
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="mdi mdi-calendar"></i> Tanggal <span class="text-danger">*</span>
                    </label>
                    <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                        value="{{ old('tanggal', $presensi->tanggal ? $presensi->tanggal->format('Y-m-d') : '') }}" required>
                    @error('tanggal')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">
                        <i class="mdi mdi-information"></i> Format: YYYY-MM-DD
                    </small>
                </div>
            </div>

            <hr>

            <!-- Shift Selection -->
            <h6 class="mb-3">
                <i class="mdi mdi-clock-outline"></i> Informasi Shift
            </h6>

            @if($presensi->karyawan && $presensi->karyawan->jamKerja && $presensi->karyawan->jamKerja->isMultiShift())
            <!-- Multi Shift Selection -->
            <div class="alert alert-info">
                <div class="d-flex align-items-center">
                    <i class="mdi mdi-layers me-2" style="font-size: 24px;"></i>
                    <div>
                        <strong>Multi Shift Terdeteksi:</strong>
                        <div class="small">
                            Karyawan ini menggunakan jam kerja multi-shift dengan
                            <strong>{{ $presensi->karyawan->jamKerja->total_shift }} shift</strong> per hari.
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="mdi mdi-numeric"></i> Pilih Shift <span class="text-danger">*</span>
                    </label>
                    <select name="shift_ke" id="shiftSelect" class="form-select @error('shift_ke') is-invalid @enderror" required>
                        <option value="">-- Pilih Shift --</option>
                        @foreach($presensi->karyawan->jamKerja->shifts as $shift)
                        <option value="{{ $shift->shift_ke }}"
                            data-nama="{{ $shift->nama_shift }}"
                            data-jam-masuk="{{ date('H:i', strtotime($shift->jam_masuk)) }}"
                            data-jam-pulang="{{ date('H:i', strtotime($shift->jam_pulang)) }}"
                            {{ old('shift_ke', $presensi->shift_ke) == $shift->shift_ke ? 'selected' : '' }}>
                            Shift {{ $shift->shift_ke }} - {{ $shift->nama_shift }}
                            ({{ date('H:i', strtotime($shift->jam_masuk)) }} - {{ date('H:i', strtotime($shift->jam_pulang)) }})
                        </option>
                        @endforeach
                    </select>
                    @error('shift_ke')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="mdi mdi-tag"></i> Nama Shift
                    </label>
                    <input type="text" name="nama_shift" id="namaShift" class="form-control bg-light"
                        value="{{ old('nama_shift', $presensi->nama_shift) }}" readonly>
                    <small class="text-muted">
                        <i class="mdi mdi-information"></i> Otomatis terisi saat memilih shift
                    </small>
                </div>
            </div>

            <!-- Shift Info Display -->
            <div id="shiftInfo" class="alert alert-light" style="{{ old('shift_ke', $presensi->shift_ke) ? '' : 'display:none' }}">
                <strong><i class="mdi mdi-clock-time-four"></i> Jadwal Shift:</strong>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <i class="mdi mdi-clock-in text-success"></i>
                        <strong>Jam Masuk:</strong> <span id="infoJamMasuk">-</span>
                    </div>
                    <div class="col-md-6">
                        <i class="mdi mdi-clock-out text-danger"></i>
                        <strong>Jam Pulang:</strong> <span id="infoJamPulang">-</span>
                    </div>
                </div>
            </div>

            @else
            <!-- Regular - No Shift Selection -->
            <div class="alert alert-secondary">
                <div class="d-flex align-items-center">
                    <i class="mdi mdi-clock-outline me-2" style="font-size: 24px;"></i>
                    <div>
                        <strong>Jam Kerja Regular:</strong>
                        <div class="small">Karyawan ini tidak menggunakan multi-shift.</div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="shift_ke" value="">
            <input type="hidden" name="nama_shift" value="">
            @endif

            <hr>

            <!-- Jam Presensi -->
            <h6 class="mb-3">
                <i class="mdi mdi-clock-check"></i> Waktu Presensi
            </h6>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="mdi mdi-login"></i> Jam Masuk
                    </label>
                    <input type="time" name="jam_masuk" class="form-control @error('jam_masuk') is-invalid @enderror"
                        value="{{ old('jam_masuk', $presensi->jam_masuk ? \Carbon\Carbon::parse($presensi->jam_masuk)->format('H:i') : '') }}">
                    @error('jam_masuk')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">
                        <i class="mdi mdi-information"></i> Format: HH:MM (24 jam)
                    </small>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="mdi mdi-logout"></i> Jam Pulang
                    </label>
                    <input type="time" name="jam_pulang" class="form-control @error('jam_pulang') is-invalid @enderror"
                        value="{{ old('jam_pulang', $presensi->jam_pulang ? \Carbon\Carbon::parse($presensi->jam_pulang)->format('H:i') : '') }}">
                    @error('jam_pulang')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">
                        <i class="mdi mdi-information"></i> Format: HH:MM (24 jam)
                    </small>
                </div>
            </div>

            <hr>

            <!-- Status & GPS -->
            <h6 class="mb-3">
                <i class="mdi mdi-check-decagram"></i> Status & Lokasi
            </h6>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="mdi mdi-checkbox-marked-circle"></i> Status <span class="text-danger">*</span>
                    </label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="verified" {{ old('status', $presensi->status) == 'verified' ? 'selected' : '' }}>
                            ✓ Verified
                        </option>
                        <option value="failed" {{ old('status', $presensi->status) == 'failed' ? 'selected' : '' }}>
                            ✗ Failed
                        </option>
                    </select>
                    @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="mdi mdi-map-marker"></i> Lokasi GPS
                    </label>
                    <input type="text" name="lokasi" class="form-control @error('lokasi') is-invalid @enderror"
                        value="{{ old('lokasi', $presensi->lokasi) }}"
                        placeholder="-6.175392,106.827153">
                    @error('lokasi')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">
                        <i class="mdi mdi-information"></i> Format: latitude,longitude
                    </small>
                </div>
            </div>

            <hr>

            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('panel.presensi-face.index') }}" class="btn btn-secondary">
                    <i class="mdi mdi-arrow-left"></i> Kembali
                </a>
                <div>
                    <button type="reset" class="btn btn-warning me-2">
                        <i class="mdi mdi-refresh"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-content-save"></i> Update Data
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Handle shift selection
        $('#shiftSelect').on('change', function() {
            const selected = $(this).find('option:selected');
            const namaShift = selected.data('nama');
            const jamMasuk = selected.data('jam-masuk');
            const jamPulang = selected.data('jam-pulang');

            if (namaShift) {
                $('#namaShift').val(namaShift);
                $('#infoJamMasuk').text(jamMasuk);
                $('#infoJamPulang').text(jamPulang);
                $('#shiftInfo').show();
            } else {
                $('#namaShift').val('');
                $('#shiftInfo').hide();
            }
        });

        // Trigger change on load if shift already selected
        if ($('#shiftSelect').val()) {
            $('#shiftSelect').trigger('change');
        }

        // Form validation before submit
        $('#formPresensi').on('submit', function(e) {
            let isValid = true;
            let errorMessage = '';

            // Check if multi-shift and shift not selected
            if ($('#shiftSelect').length && $('#shiftSelect').attr('required') && !$('#shiftSelect').val()) {
                isValid = false;
                errorMessage = 'Silakan pilih shift terlebih dahulu.';
            }

            if (!isValid) {
                e.preventDefault();
                alert(errorMessage);
                return false;
            }

            // Confirm before submit
            return confirm('Apakah Anda yakin ingin mengupdate data presensi ini?');
        });
    });
</script>
@endpush

@endsection