@extends('admin.layouts.admin')

@section('title', 'Edit Presensi Face')
@section('page-title', 'Edit Presensi Face Recognition')

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
                    <i class="mdi mdi-pencil"></i> Form Edit Presensi Face Recognition
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

                <form action="{{ route('panel.presensi-face.update', $presensi->id) }}" method="POST" id="formPresensi">
                    @csrf
                    @method('PUT')

                    <!-- Karyawan Info (Read Only) -->
                    <div class="info-card">
                        <div class="row">
                            <div class="col-md-3">
                                <small class="text-muted">NIK</small>
                                <div class="fw-bold">{{ $presensi->nik }}</div>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted">Nama</small>
                                <div>{{ $presensi->karyawan->nama_lengkap ?? '-' }}</div>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted">Departemen</small>
                                <div>
                                    @if($presensi->karyawan && $presensi->karyawan->departemen)
                                    <span class="badge bg-purple">{{ $presensi->karyawan->departemen->nama_dept }}</span>
                                    @else - @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted">Cabang</small>
                                <div>
                                    @if($presensi->karyawan && $presensi->karyawan->cabang)
                                    <span class="badge bg-blue">{{ $presensi->karyawan->cabang->nama_cabang }}</span>
                                    @else - @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                                value="{{ old('tanggal', $presensi->tanggal ? $presensi->tanggal->format('Y-m-d') : '') }}" required>
                            @error('tanggal')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Shift Section -->
                    @if($presensi->karyawan && $presensi->karyawan->jamKerja && $presensi->karyawan->jamKerja->tipe_jam_kerja === 'multi_shift')
                    <!-- Multi-Shift -->
                    <div class="alert alert-warning shift-alert">
                        <i class="mdi mdi-layers"></i>
                        <strong>Multi-Shift</strong> - Karyawan menggunakan {{ $presensi->karyawan->jamKerja->total_shift }} shift per hari
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pilih Shift <span class="text-danger">*</span></label>
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
                            <label class="form-label">Nama Shift</label>
                            <input type="text" name="nama_shift" id="namaShift" class="form-control"
                                value="{{ old('nama_shift', $presensi->nama_shift) }}" readonly>
                        </div>
                    </div>

                    <div id="shiftInfo" class="shift-info-display" style="{{ old('shift_ke', $presensi->shift_ke) ? '' : 'display:none' }}">
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
                    @else
                    <!-- Regular -->
                    <div class="alert alert-secondary regular-alert">
                        <i class="mdi mdi-clock-outline"></i>
                        <strong>Jam Kerja Regular</strong> - Tidak menggunakan multi-shift
                    </div>
                    <input type="hidden" name="shift_ke" value="">
                    <input type="hidden" name="nama_shift" value="">
                    @endif

                    <hr>

                    <!-- Waktu Presensi -->
                    <h6 class="mb-3">Waktu Presensi</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jam Masuk</label>
                            <input type="time" name="jam_masuk" class="form-control @error('jam_masuk') is-invalid @enderror"
                                value="{{ old('jam_masuk', $presensi->jam_masuk ? \Carbon\Carbon::parse($presensi->jam_masuk)->format('H:i') : '') }}">
                            @error('jam_masuk')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jam Pulang</label>
                            <input type="time" name="jam_pulang" class="form-control @error('jam_pulang') is-invalid @enderror"
                                value="{{ old('jam_pulang', $presensi->jam_pulang ? \Carbon\Carbon::parse($presensi->jam_pulang)->format('H:i') : '') }}">
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
                                <option value="verified" {{ old('status', $presensi->status) == 'verified' ? 'selected' : '' }}>Verified</option>
                                <option value="failed" {{ old('status', $presensi->status) == 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                            @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lokasi GPS</label>
                            <input type="text" name="lokasi" class="form-control @error('lokasi') is-invalid @enderror"
                                value="{{ old('lokasi', $presensi->lokasi) }}" placeholder="-6.175392,106.827153">
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
                        <div>
                            <a href="{{ route('panel.presensi-face.show', $presensi->id) }}" class="btn btn-info me-2">
                                <i class="mdi mdi-eye"></i> Lihat Detail
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-content-save"></i> Update
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
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

        // Trigger change on load if shift already selected
        if ($('#shiftSelect').val()) {
            $('#shiftSelect').trigger('change');
        }

        // Confirmation
        $('#formPresensi').on('submit', function() {
            return confirm('Yakin ingin mengupdate data presensi ini?');
        });
    });
</script>
@endpush

@endsection