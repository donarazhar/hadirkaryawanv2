@extends('admin.layouts.admin')

@section('title', 'Tambah Presensi Face')
@section('page-title', 'Tambah Presensi Face Recognition')

@push('styles')
<style>
    .badge.bg-blue {
        background-color: #0054a6 !important;
        color: #ffffff !important;
    }

    .badge.bg-purple {
        background-color: #7c3aed !important;
        color: #ffffff !important;
    }

    .badge.bg-teal {
        background-color: #0d9488 !important;
        color: #ffffff !important;
    }

    .info-card {
        background: linear-gradient(135deg, rgba(0, 84, 166, 0.05) 0%, rgba(0, 61, 148, 0.02) 100%);
        border: 1px solid rgba(0, 84, 166, 0.15);
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .shift-alert {
        border-left: 4px solid #f59e0b;
        background: #fffbeb;
    }

    .regular-alert {
        border-left: 4px solid #6c757d;
        background: #f8f9fa;
    }

    .shift-info-display {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 0.75rem;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="mdi mdi-plus-circle"></i> Form Tambah Presensi Face Recognition
                </h5>
            </div>
            <div class="card-body">
                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <strong><i class="mdi mdi-alert-circle"></i> Error!</strong> Terdapat kesalahan:
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <form action="{{ route('panel.presensi-face.store') }}" method="POST" id="formPresensi">
                    @csrf

                    <!-- Pilih Karyawan -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pilih Karyawan <span class="text-danger">*</span></label>
                            <select name="nik" id="karyawanSelect" class="form-select @error('nik') is-invalid @enderror" required>
                                <option value="">-- Pilih Karyawan --</option>
                                @foreach($karyawan as $k)
                                <option value="{{ $k->nik }}"
                                    data-nama="{{ $k->nama_lengkap }}"
                                    data-jabatan="{{ $k->jabatan ?? '-' }}"
                                    data-dept="{{ $k->departemen->nama_dept ?? '-' }}"
                                    data-cabang="{{ $k->cabang->nama_cabang ?? '-' }}"
                                    data-is-multi="{{ $k->jamKerja && $k->jamKerja->is_multi_shift ? '1' : '0' }}"
                                    data-shifts='{{ $k->jamKerja && $k->jamKerja->shifts ? json_encode($k->jamKerja->shifts) : "[]" }}'
                                    {{ old('nik') == $k->nik ? 'selected' : '' }}>
                                    {{ $k->nik }} - {{ $k->nama_lengkap }}
                                    @if($k->departemen) ({{ $k->departemen->nama_dept }}) @endif
                                </option>
                                @endforeach
                            </select>
                            @error('nik')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                                value="{{ old('tanggal', date('Y-m-d')) }}" required>
                            @error('tanggal')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Karyawan Info -->
                    <div id="karyawanInfo" class="info-card" style="display:none">
                        <div class="row">
                            <div class="col-md-3">
                                <small class="text-muted">Nama</small>
                                <div class="fw-bold" id="infoNama">-</div>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted">Jabatan</small>
                                <div id="infoJabatan">-</div>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted">Departemen</small>
                                <div id="infoDept">-</div>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted">Cabang</small>
                                <div id="infoCabang">-</div>
                            </div>
                        </div>
                    </div>

                    <!-- Multi-Shift Section -->
                    <div id="multiShiftSection" style="display:none">
                        <div class="alert alert-warning shift-alert">
                            <i class="mdi mdi-layers"></i>
                            <strong>Multi-Shift Terdeteksi</strong> - Karyawan ini menggunakan jam kerja multi-shift. Pilih shift yang sesuai.
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pilih Shift <span class="text-danger">*</span></label>
                                <select name="shift_ke" id="shiftSelect" class="form-select @error('shift_ke') is-invalid @enderror">
                                    <option value="">-- Pilih Shift --</option>
                                </select>
                                @error('shift_ke')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Shift</label>
                                <input type="text" name="nama_shift" id="namaShift" class="form-control" readonly>
                            </div>
                        </div>

                        <div id="shiftInfo" class="shift-info-display" style="display:none">
                            <strong>Jadwal Shift:</strong>
                            <div class="d-flex gap-3 mt-2">
                                <div>
                                    <i class="mdi mdi-clock-in text-success"></i>
                                    Masuk: <strong id="infoJamMasuk">-</strong>
                                </div>
                                <div>
                                    <i class="mdi mdi-clock-out text-danger"></i>
                                    Pulang: <strong id="infoJamPulang">-</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Regular Section -->
                    <div id="regularSection" class="alert alert-secondary regular-alert" style="display:none">
                        <i class="mdi mdi-clock-outline"></i>
                        <strong>Jam Kerja Regular</strong> - Karyawan ini tidak menggunakan multi-shift.
                    </div>

                    <hr>

                    <!-- Waktu Presensi -->
                    <h6 class="mb-3">Waktu Presensi</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jam Masuk</label>
                            <input type="time" name="jam_masuk" class="form-control @error('jam_masuk') is-invalid @enderror"
                                value="{{ old('jam_masuk') }}">
                            @error('jam_masuk')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jam Pulang</label>
                            <input type="time" name="jam_pulang" class="form-control @error('jam_pulang') is-invalid @enderror"
                                value="{{ old('jam_pulang') }}">
                            @error('jam_pulang')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Status & Lokasi -->
                    <h6 class="mb-3">Status & Lokasi</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="verified" {{ old('status', 'verified') == 'verified' ? 'selected' : '' }}>Verified</option>
                                <option value="failed" {{ old('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                            @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lokasi GPS</label>
                            <input type="text" name="lokasi" class="form-control @error('lokasi') is-invalid @enderror"
                                value="{{ old('lokasi') }}" placeholder="-6.175392,106.827153">
                            @error('lokasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Format: latitude,longitude</small>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('panel.presensi-face.index') }}" class="btn btn-secondary">
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
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#karyawanSelect').on('change', function() {
            const selected = $(this).find('option:selected');
            const isMulti = selected.data('is-multi') == '1';
            let shifts = [];

            try {
                const shiftsData = selected.data('shifts');
                if (typeof shiftsData === 'string') {
                    shifts = JSON.parse(shiftsData);
                } else if (Array.isArray(shiftsData)) {
                    shifts = shiftsData;
                }
            } catch (e) {
                shifts = [];
            }

            if (selected.val()) {
                $('#infoNama').text(selected.data('nama'));
                $('#infoJabatan').text(selected.data('jabatan'));
                $('#infoDept').text(selected.data('dept'));
                $('#infoCabang').text(selected.data('cabang'));
                $('#karyawanInfo').fadeIn();

                if (isMulti && shifts.length > 0) {
                    $('#multiShiftSection').fadeIn();
                    $('#regularSection').hide();

                    $('#shiftSelect').html('<option value="">-- Pilih Shift --</option>');
                    shifts.forEach(function(shift) {
                        const jamMasuk = shift.jam_masuk ? shift.jam_masuk.substring(0, 5) : '-';
                        const jamPulang = shift.jam_pulang ? shift.jam_pulang.substring(0, 5) : '-';

                        $('#shiftSelect').append(
                            `<option value="${shift.shift_ke}" 
                                data-nama="${shift.nama_shift}"
                                data-jam-masuk="${jamMasuk}"
                                data-jam-pulang="${jamPulang}">
                                Shift ${shift.shift_ke} - ${shift.nama_shift} (${jamMasuk} - ${jamPulang})
                            </option>`
                        );
                    });

                    $('#shiftSelect').attr('required', true);
                } else {
                    $('#multiShiftSection').hide();
                    $('#regularSection').fadeIn();
                    $('#shiftSelect').removeAttr('required');
                    $('#namaShift').val('');
                }
            } else {
                $('#karyawanInfo').hide();
                $('#multiShiftSection').hide();
                $('#regularSection').hide();
            }
        });

        $('#shiftSelect').on('change', function() {
            const selected = $(this).find('option:selected');
            const namaShift = selected.data('nama');
            const jamMasuk = selected.data('jam-masuk');
            const jamPulang = selected.data('jam-pulang');

            if (namaShift) {
                $('#namaShift').val(namaShift);
                $('#infoJamMasuk').text(jamMasuk);
                $('#infoJamPulang').text(jamPulang);
                $('#shiftInfo').fadeIn();
            } else {
                $('#namaShift').val('');
                $('#shiftInfo').hide();
            }
        });

        @if(old('nik'))
        $('#karyawanSelect').trigger('change');
        @if(old('shift_ke'))
        setTimeout(function() {
            $('#shiftSelect').val('{{ old("shift_ke") }}').trigger('change');
        }, 100);
        @endif
        @endif
    });
</script>
@endpush

@endsection