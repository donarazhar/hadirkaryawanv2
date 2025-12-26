@extends('admin.layouts.admin')

@section('title', 'Tambah Presensi Face')
@section('page-title', 'Tambah Presensi Face Recognition')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Form Tambah Presensi Face Recognition</h5>
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

        <form action="{{ route('panel.presensi-face.store') }}" method="POST" enctype="multipart/form-data" id="formPresensi">
            @csrf

            <!-- Karyawan Selection -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Pilih Karyawan <span class="text-danger">*</span></label>
                    <select name="nik" id="karyawanSelect" class="form-select @error('nik') is-invalid @enderror" required>
                        <option value="">-- Pilih Karyawan --</option>
                        @foreach($karyawan as $k)
                        <option value="{{ $k->nik }}"
                            data-nama="{{ $k->nama_lengkap }}"
                            data-jabatan="{{ $k->jabatan }}"
                            data-dept="{{ $k->departemen->nama_dept ?? '-' }}"
                            data-cabang="{{ $k->cabang->nama_cabang ?? '-' }}"
                            data-is-multi="{{ $k->jamKerja && $k->jamKerja->isMultiShift() ? '1' : '0' }}"
                            data-jam-kerja="{{ $k->jamKerja ? $k->jamKerja->kode_jam_kerja : '' }}"
                            data-shifts="{{ $k->jamKerja && $k->jamKerja->shifts ? json_encode($k->jamKerja->shifts) : '[]' }}"
                            {{ old('nik') == $k->nik ? 'selected' : '' }}>
                            {{ $k->nik }} - {{ $k->nama_lengkap }} ({{ $k->departemen->nama_dept ?? '-' }})
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

            <!-- Karyawan Info Display -->
            <div id="karyawanInfo" class="alert alert-light" style="display:none">
                <div class="row">
                    <div class="col-md-3">
                        <strong>Nama:</strong> <span id="infoNama">-</span>
                    </div>
                    <div class="col-md-3">
                        <strong>Jabatan:</strong> <span id="infoJabatan">-</span>
                    </div>
                    <div class="col-md-3">
                        <strong>Departemen:</strong> <span id="infoDept">-</span>
                    </div>
                    <div class="col-md-3">
                        <strong>Cabang:</strong> <span id="infoCabang">-</span>
                    </div>
                </div>
            </div>

            <hr>

            <!-- ✅ SHIFT SELECTION (Dynamic based on karyawan) -->
            <h6 class="mb-3">Informasi Shift</h6>

            <div id="shiftContainer" style="display:none">
                <!-- Multi Shift Selection -->
                <div class="alert alert-info">
                    <i class="mdi mdi-layers"></i>
                    <strong>Multi Shift Terdeteksi:</strong>
                    Karyawan ini menggunakan jam kerja multi-shift.
                    Pilih shift yang sesuai.
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
                        <input type="text" name="nama_shift" id="namaShift" class="form-control bg-light"
                            value="{{ old('nama_shift') }}" readonly>
                        <small class="text-muted">Otomatis terisi saat memilih shift</small>
                    </div>
                </div>

                <!-- Shift Info Display -->
                <div id="shiftInfo" class="alert alert-light" style="display:none">
                    <strong>Jadwal Shift:</strong>
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
            </div>

            <div id="regularInfo" class="alert alert-secondary" style="display:none">
                <i class="mdi mdi-clock-outline"></i>
                <strong>Jam Kerja Regular:</strong> Karyawan ini tidak menggunakan multi-shift.
            </div>

            <hr>

            <!-- Jam Presensi -->
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

            <hr>

            <!-- Status & Verification -->
            <h6 class="mb-3">Status Verifikasi</h6>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="verified" {{ old('status') == 'verified' ? 'selected' : '' }}>
                            Verified
                        </option>
                        <option value="failed" {{ old('status') == 'failed' ? 'selected' : '' }}>
                            Failed
                        </option>
                    </select>
                    @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Similarity Score</label>
                    <input type="number" name="similarity_score" step="0.01" min="0" max="1"
                        class="form-control @error('similarity_score') is-invalid @enderror"
                        value="{{ old('similarity_score') }}"
                        placeholder="0.00 - 1.00">
                    @error('similarity_score')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">0.00 - 1.00 (contoh: 0.95)</small>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Foto Presensi</label>
                    <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/*">
                    @error('foto')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Lokasi GPS</label>
                    <input type="text" name="lokasi" class="form-control @error('lokasi') is-invalid @enderror"
                        value="{{ old('lokasi') }}"
                        placeholder="-6.175392,106.827153">
                    @error('lokasi')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Format: latitude,longitude</small>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="2">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
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

@push('scripts')
<script>
    $(document).ready(function() {
        // Handle karyawan selection
        $('#karyawanSelect').on('change', function() {
            const selected = $(this).find('option:selected');
            const isMulti = selected.data('is-multi') == '1';
            const shifts = selected.data('shifts') || [];

            // Show karyawan info
            if (selected.val()) {
                $('#infoNama').text(selected.data('nama'));
                $('#infoJabatan').text(selected.data('jabatan'));
                $('#infoDept').text(selected.data('dept'));
                $('#infoCabang').text(selected.data('cabang'));
                $('#karyawanInfo').show();

                // Handle shift display
                if (isMulti && shifts.length > 0) {
                    // Multi-shift
                    $('#shiftContainer').show();
                    $('#regularInfo').hide();

                    // Populate shift options
                    $('#shiftSelect').html('<option value="">-- Pilih Shift --</option>');
                    shifts.forEach(function(shift) {
                        $('#shiftSelect').append(
                            `<option value="${shift.shift_ke}" 
                                data-nama="${shift.nama_shift}"
                                data-jam-masuk="${shift.jam_masuk}"
                                data-jam-pulang="${shift.jam_pulang}">
                                Shift ${shift.shift_ke} - ${shift.nama_shift} 
                                (${shift.jam_masuk.substring(0,5)} - ${shift.jam_pulang.substring(0,5)})
                            </option>`
                        );
                    });

                    // Make shift_ke required
                    $('#shiftSelect').attr('required', true);
                } else {
                    // Regular
                    $('#shiftContainer').hide();
                    $('#regularInfo').show();

                    // Clear shift fields
                    $('#shiftSelect').html('<option value="">-- Pilih Shift --</option>');
                    $('#shiftSelect').removeAttr('required');
                    $('#namaShift').val('');
                }
            } else {
                $('#karyawanInfo').hide();
                $('#shiftContainer').hide();
                $('#regularInfo').hide();
            }
        });

        // Handle shift selection
        $('#shiftSelect').on('change', function() {
            const selected = $(this).find('option:selected');
            const namaShift = selected.data('nama');
            const jamMasuk = selected.data('jam-masuk');
            const jamPulang = selected.data('jam-pulang');

            if (namaShift) {
                $('#namaShift').val(namaShift);

                // Format jam to HH:MM
                const jamMasukFormatted = jamMasuk ? jamMasuk.substring(0, 5) : '-';
                const jamPulangFormatted = jamPulang ? jamPulang.substring(0, 5) : '-';

                $('#infoJamMasuk').text(jamMasukFormatted);
                $('#infoJamPulang').text(jamPulangFormatted);
                $('#shiftInfo').show();
            } else {
                $('#namaShift').val('');
                $('#shiftInfo').hide();
            }
        });

        // Trigger change if karyawan already selected (from old input)
        if ($('#karyawanSelect').val()) {
            $('#karyawanSelect').trigger('change');
        }
    });
</script>
@endpush

@endsection