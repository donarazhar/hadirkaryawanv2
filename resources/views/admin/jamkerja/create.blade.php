@extends('admin.layouts.admin')

@section('title', 'Tambah Jam Kerja')
@section('page-title', 'Tambah Jam Kerja')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Form Tambah Jam Kerja</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('panel.jamkerja.store') }}" method="POST" id="formJamKerja">
            @csrf

            <!-- Basic Info -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kode Jam Kerja <span class="text-danger">*</span></label>
                    <input type="text" name="kode_jam_kerja" class="form-control @error('kode_jam_kerja') is-invalid @enderror"
                        value="{{ old('kode_jam_kerja') }}" required>
                    @error('kode_jam_kerja')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Jam Kerja <span class="text-danger">*</span></label>
                    <input type="text" name="nama_jam_kerja" class="form-control @error('nama_jam_kerja') is-invalid @enderror"
                        value="{{ old('nama_jam_kerja') }}" required>
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
                        <option value="regular" {{ old('tipe_jam_kerja') == 'regular' ? 'selected' : '' }}>
                            Regular (Jam Kerja Normal)
                        </option>
                        <option value="multi_shift" {{ old('tipe_jam_kerja') == 'multi_shift' ? 'selected' : '' }}>
                            Multi Shift (Untuk Imam/Muazin dll)
                        </option>
                    </select>
                    <small class="text-muted">
                        Pilih "Multi Shift" untuk jam kerja yang memiliki beberapa shift dalam 1 hari (contoh: Imam & Muazin dengan 5 waktu sholat)
                    </small>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Lintas Hari <span class="text-danger">*</span></label>
                    <select name="lintashari" class="form-select" required>
                        <option value="0" {{ old('lintashari') == '0' ? 'selected' : '' }}>Tidak</option>
                        <option value="1" {{ old('lintashari') == '1' ? 'selected' : '' }}>Ya</option>
                    </select>
                </div>
            </div>

            <!-- Regular Jam Kerja (Hidden when multi_shift) -->
            <div id="regularSection">
                <h6 class="mb-3">⏰ Jam Kerja Regular</h6>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Awal Jam Masuk</label>
                        <input type="time" name="awal_jam_masuk" class="form-control"
                            value="{{ old('awal_jam_masuk') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Jam Masuk</label>
                        <input type="time" name="jam_masuk" class="form-control"
                            value="{{ old('jam_masuk') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Akhir Jam Masuk</label>
                        <input type="time" name="akhir_jam_masuk" class="form-control"
                            value="{{ old('akhir_jam_masuk') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Jam Pulang</label>
                        <input type="time" name="jam_pulang" class="form-control"
                            value="{{ old('jam_pulang') }}">
                    </div>
                </div>
            </div>

            <!-- Multi Shift Section (Shown when multi_shift selected) -->
            <div id="multiShiftSection" style="display: none;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">🕌 Konfigurasi Multi Shift</h6>
                    <button type="button" class="btn btn-sm btn-primary" id="addShiftBtn">
                        <i class="mdi mdi-plus"></i> Tambah Shift
                    </button>
                </div>

                <input type="hidden" name="total_shift" id="totalShift" value="1">

                <div id="shiftsContainer">
                    <!-- Shift items will be added here -->
                </div>

                <!-- Template for Shift (Hidden) -->
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

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="mdi mdi-content-save"></i> Simpan
                </button>
                <a href="{{ route('panel.jamkerja.index') }}" class="btn btn-secondary">
                    <i class="mdi mdi-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let shiftIndex = 0;

    $(document).ready(function() {
        // Toggle sections based on tipe jam kerja
        $('#tipeJamKerja').on('change', function() {
            toggleSections();
        });

        // Add shift button
        $('#addShiftBtn').on('click', function() {
            addShift();
        });

        // Remove shift button (delegated)
        $(document).on('click', '.remove-shift', function() {
            $(this).closest('.shift-item').remove();
            updateShiftNumbers();
            updateTotalShift();
        });

        // Initialize
        toggleSections();

        // Add default shift if multi_shift is selected
        if ($('#tipeJamKerja').val() === 'multi_shift') {
            addShift();
        }
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

        // Clone template
        const template = document.getElementById('shiftTemplate');
        const clone = template.content.cloneNode(true);

        // Replace INDEX placeholder
        const html = clone.querySelector('.shift-item').outerHTML.replace(/INDEX/g, shiftIndex);

        // Append to container
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