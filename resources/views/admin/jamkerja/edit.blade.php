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

                <form action="{{ route('panel.jamkerja.update', $jamkerja->kode_jam_kerja) }}" method="POST" id="formJamKerja">
                    @csrf
                    @method('PUT')

                    <!-- Basic Info -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kode Jam Kerja</label>
                            <input type="text" class="form-control bg-light" value="{{ $jamkerja->kode_jam_kerja }}" disabled>
                            <small class="text-muted">Kode tidak dapat diubah</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Jam Kerja <span class="text-danger">*</span></label>
                            <input type="text" name="nama_jam_kerja" class="form-control @error('nama_jam_kerja') is-invalid @enderror"
                                value="{{ old('nama_jam_kerja', $jamkerja->nama_jam_kerja) }}" required>
                            @error('nama_jam_kerja')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Tipe Jam Kerja -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipe Jam Kerja <span class="text-danger">*</span></label>
                            <select name="tipe_jam_kerja" id="tipeJamKerja" class="form-select" required>
                                <option value="regular" {{ old('tipe_jam_kerja', $jamkerja->tipe_jam_kerja) == 'regular' ? 'selected' : '' }}>
                                    Regular (Jam Kerja Normal)
                                </option>
                                <option value="multi_shift" {{ old('tipe_jam_kerja', $jamkerja->tipe_jam_kerja) == 'multi_shift' ? 'selected' : '' }}>
                                    Multi Shift (Untuk Imam/Muazin dll)
                                </option>
                            </select>
                            <small class="text-muted">
                                Pilih "Multi Shift" untuk jam kerja dengan beberapa shift dalam 1 hari
                            </small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lintas Hari <span class="text-danger">*</span></label>
                            <select name="lintashari" class="form-select" required>
                                <option value="0" {{ old('lintashari', $jamkerja->lintashari) == '0' ? 'selected' : '' }}>Tidak</option>
                                <option value="1" {{ old('lintashari', $jamkerja->lintashari) == '1' ? 'selected' : '' }}>Ya (Shift Malam)</option>
                            </select>
                        </div>
                    </div>

                    <hr>

                    <!-- Regular Section -->
                    <div id="regularSection" style="{{ old('tipe_jam_kerja', $jamkerja->tipe_jam_kerja) == 'multi_shift' ? 'display:none' : '' }}">
                        <h6 class="mb-3">⏰ Konfigurasi Jam Kerja Regular</h6>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Awal Jam Masuk</label>
                                <input type="time" name="awal_jam_masuk" class="form-control"
                                    value="{{ old('awal_jam_masuk', date('H:i', strtotime($jamkerja->awal_jam_masuk))) }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Jam Masuk</label>
                                <input type="time" name="jam_masuk" class="form-control"
                                    value="{{ old('jam_masuk', date('H:i', strtotime($jamkerja->jam_masuk))) }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Akhir Jam Masuk</label>
                                <input type="time" name="akhir_jam_masuk" class="form-control"
                                    value="{{ old('akhir_jam_masuk', date('H:i', strtotime($jamkerja->akhir_jam_masuk))) }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Jam Pulang</label>
                                <input type="time" name="jam_pulang" class="form-control"
                                    value="{{ old('jam_pulang', date('H:i', strtotime($jamkerja->jam_pulang))) }}">
                            </div>
                        </div>
                    </div>

                    <!-- Multi Shift Section -->
                    <div id="multiShiftSection" style="{{ old('tipe_jam_kerja', $jamkerja->tipe_jam_kerja) == 'regular' ? 'display:none' : '' }}">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">🕌 Konfigurasi Multi Shift</h6>
                            <button type="button" class="btn btn-sm btn-primary" id="addShiftBtn">
                                <i class="mdi mdi-plus"></i> Tambah Shift
                            </button>
                        </div>

                        <input type="hidden" name="total_shift" id="totalShift" value="{{ $jamkerja->shifts->count() }}">

                        <div id="shiftsContainer">
                            @foreach($jamkerja->shifts as $shift)
                            <div class="card mb-3 shift-item">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Shift <span class="shift-number">{{ $shift->shift_ke }}</span></h6>
                                    <button type="button" class="btn btn-sm btn-danger remove-shift">
                                        <i class="mdi mdi-delete"></i> Hapus
                                    </button>
                                </div>
                                <div class="card-body">
                                    <input type="hidden" name="shifts[{{ $loop->index }}][shift_ke]" class="shift-ke" value="{{ $shift->shift_ke }}">

                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Nama Shift</label>
                                            <input type="text" name="shifts[{{ $loop->index }}][nama_shift]" class="form-control"
                                                value="{{ $shift->nama_shift }}" placeholder="Contoh: Subuh, Zuhur, dll" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Awal Jam Masuk</label>
                                            <input type="time" name="shifts[{{ $loop->index }}][awal_jam_masuk]" class="form-control"
                                                value="{{ date('H:i', strtotime($shift->awal_jam_masuk)) }}" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Jam Masuk</label>
                                            <input type="time" name="shifts[{{ $loop->index }}][jam_masuk]" class="form-control"
                                                value="{{ date('H:i', strtotime($shift->jam_masuk)) }}" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Akhir Jam Masuk</label>
                                            <input type="time" name="shifts[{{ $loop->index }}][akhir_jam_masuk]" class="form-control"
                                                value="{{ date('H:i', strtotime($shift->akhir_jam_masuk)) }}" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Jam Pulang</label>
                                            <input type="time" name="shifts[{{ $loop->index }}][jam_pulang]" class="form-control"
                                                value="{{ date('H:i', strtotime($shift->jam_pulang)) }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Template for new shift -->
                        <template id="shiftTemplate">
                            <div class="card mb-3 shift-item">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Shift <span class="shift-number">1</span></h6>
                                    <button type="button" class="btn btn-sm btn-danger remove-shift">
                                        <i class="mdi mdi-delete"></i> Hapus
                                    </button>
                                </div>
                                <div class="card-body">
                                    <input type="hidden" name="shifts[INDEX][shift_ke]" class="shift-ke" value="1">

                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Nama Shift</label>
                                            <input type="text" name="shifts[INDEX][nama_shift]" class="form-control"
                                                placeholder="Contoh: Subuh, Zuhur, dll" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Awal Jam Masuk</label>
                                            <input type="time" name="shifts[INDEX][awal_jam_masuk]" class="form-control" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Jam Masuk</label>
                                            <input type="time" name="shifts[INDEX][jam_masuk]" class="form-control" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Akhir Jam Masuk</label>
                                            <input type="time" name="shifts[INDEX][akhir_jam_masuk]" class="form-control" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Jam Pulang</label>
                                            <input type="time" name="shifts[INDEX][jam_pulang]" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
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

    <!-- Sidebar Info -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="mdi mdi-information-outline"></i> Informasi
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Kode:</strong>
                    <span class="badge bg-warning text-dark">{{ $jamkerja->kode_jam_kerja }}</span>
                </div>
                <div class="mb-3">
                    <strong>Nama:</strong> {{ $jamkerja->nama_jam_kerja }}
                </div>
                <div class="mb-3">
                    <strong>Tipe:</strong>
                    @if($jamkerja->tipe_jam_kerja == 'multi_shift')
                    <span class="badge bg-info">Multi Shift</span>
                    @else
                    <span class="badge bg-secondary">Regular</span>
                    @endif
                </div>

                @if($jamkerja->tipe_jam_kerja == 'multi_shift')
                <div class="mb-3">
                    <strong>Total Shift:</strong> {{ $jamkerja->shifts->count() }} shift/hari
                </div>
                @endif

                @if($total_penggunaan > 0)
                <hr>
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
                        Perubahan jam kerja akan mempengaruhi semua konfigurasi yang menggunakan jam kerja ini.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let shiftIndex = {{$jamkerja->shifts->count()}
    };

    $(document).ready(function() {
        // Toggle sections
        $('#tipeJamKerja').on('change', function() {
            toggleSections();
        });

        // Add shift
        $('#addShiftBtn').on('click', function() {
            addShift();
        });

        // Remove shift
        $(document).on('click', '.remove-shift', function() {
            $(this).closest('.shift-item').remove();
            updateShiftNumbers();
            updateTotalShift();
        });

        // Initialize
        toggleSections();
    });

    function toggleSections() {
        const tipe = $('#tipeJamKerja').val();

        if (tipe === 'multi_shift') {
            $('#regularSection').hide();
            $('#regularSection input').prop('required', false);
            $('#multiShiftSection').show();
            $('#multiShiftSection input[type="time"]').prop('required', true);
        } else {
            $('#regularSection').show();
            $('#regularSection input').prop('required', true);
            $('#multiShiftSection').hide();
            $('#multiShiftSection input[type="time"]').prop('required', false);
        }
    }

    function addShift() {
        shiftIndex++;

        const template = document.getElementById('shiftTemplate');
        const clone = template.content.cloneNode(true);
        const html = clone.querySelector('.shift-item').outerHTML.replace(/INDEX/g, shiftIndex);

        $('#shiftsContainer').append(html);

        updateShiftNumbers();
        updateTotalShift();
    }

    function updateShiftNumbers() {
        $('.shift-item').each(function(index) {
            $(this).find('.shift-number').text(index + 1);
            $(this).find('.shift-ke').val(index + 1);
        });
    }

    function updateTotalShift() {
        const total = $('.shift-item').length;
        $('#totalShift').val(total);
    }
</script>
@endpush

@endsection