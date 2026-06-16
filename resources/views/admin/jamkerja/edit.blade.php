@extends('admin.layouts.admin')

@section('title', 'Edit Data Jam Kerja')
@section('page-title', 'Edit Data Jam Kerja')

@push('styles')
<style>
    :root {
        --blue:       #2563EB;
        --blue-dark:  #1D4ED8;
        --blue-soft:  #EFF6FF;
        --blue-mid:   #BFDBFE;
        --green:      #10B981;
        --green-soft: #ECFDF5;
        --red:        #EF4444;
        --red-soft:   #FEF2F2;
        --amber:      #F59E0B;
        --amber-soft: #FFFBEB;
        --purple:     #8B5CF6;
        --purple-soft:#F5F3FF;
        --slate-900:  #111827;
        --slate-700:  #374151;
        --slate-600:  #4B5563;
        --slate-400:  #9CA3AF;
        --slate-300:  #D1D5DB;
        --slate-200:  #E5E7EB;
        --slate-100:  #F3F4F6;
        --slate-50:   #F9FAFB;
        --white:      #FFFFFF;
        --shadow:     0 1px 3px rgba(0,0,0,0.06),0 1px 2px rgba(0,0,0,0.04);
        --radius:     14px;
        --radius-sm:  10px;
    }

    .form-wrap { display: flex; flex-direction: column; gap: 20px; }

    /* ── PAGE HEADER ── */
    .form-header {
        background: var(--white);
        border: 1px solid var(--slate-200);
        border-radius: var(--radius);
        padding: 20px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        box-shadow: var(--shadow);
    }
    .form-header-left { display: flex; align-items: center; gap: 14px; }
    .form-header-icon {
        width: 46px; height: 46px; border-radius: 12px; background: var(--amber-soft);
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .form-header-icon i { font-size: 22px; color: #D97706; }
    .form-header-title { font-size: 17px; font-weight: 800; color: var(--slate-900); letter-spacing: -0.2px; }
    .form-header-sub   { font-size: 12px; color: var(--slate-400); margin-top: 2px; }
    
    .btn-back {
        display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px;
        border: 1.5px solid var(--slate-200); border-radius: 9px; font-family: 'Inter', sans-serif;
        font-size: 13px; font-weight: 700; cursor: pointer; transition: all 0.15s;
        text-decoration: none; background: var(--white); color: var(--slate-700);
    }
    .btn-back i { font-size: 16px; }
    .btn-back:hover { background: var(--slate-50); color: var(--slate-900); border-color: var(--slate-300); }

    /* ── LAYOUT ── */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 20px;
        align-items: start;
    }
    @media (max-width: 992px) { .form-grid { grid-template-columns: 1fr; } }
    .side-col { display: flex; flex-direction: column; gap: 20px; position: sticky; top: 20px; }

    /* ── CARDS ── */
    .card { background: var(--white); border: 1px solid var(--slate-200); border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; margin-bottom: 20px; }
    .card-head { padding: 18px 24px; border-bottom: 1px solid var(--slate-100); display: flex; align-items: center; gap: 8px; justify-content: space-between; }
    .card-head-left { display: flex; align-items: center; gap: 8px; }
    .card-head i { font-size: 18px; color: var(--purple); }
    .card-title { font-size: 14px; font-weight: 800; color: var(--slate-900); m-0; }
    .card-body { padding: 24px; }

    /* ── FORM ELEMENTS ── */
    .fg-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
    @media (max-width: 640px) { .fg-row { grid-template-columns: 1fr; } }
    
    .fg { margin-bottom: 20px; }
    .fg:last-child { margin-bottom: 0; }
    .fg label {
        display: block; font-size: 11.5px; font-weight: 700; color: var(--slate-700);
        margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;
    }
    .req { color: var(--red); }
    
    .form-control, .form-select {
        width: 100%; height: 42px; padding: 0 14px; border: 1.5px solid var(--slate-200);
        border-radius: 9px; font-family: 'Inter', sans-serif; font-size: 13.5px;
        color: var(--slate-900); background: var(--white); transition: all 0.15s; outline: none;
    }
    .form-control:focus, .form-select:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(37,99,235,0.10); }
    .form-control:disabled { background-color: var(--slate-50); color: var(--slate-500); cursor: not-allowed; }
    
    .is-invalid { border-color: var(--red) !important; background: var(--red-soft); }
    .invalid-feedback { display: block; font-size: 11.5px; color: var(--red); font-weight: 600; margin-top: 6px; }
    .form-hint { display: block; font-size: 11.5px; color: var(--slate-500); margin-top: 6px; font-weight: 500; line-height: 1.4; }

    /* Time Row Grid */
    .time-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; }
    @media (max-width: 768px) { .time-grid { grid-template-columns: repeat(2, 1fr); } }
    
    /* Action Footer */
    .form-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--slate-100); }
    .btn-save {
        height: 42px; padding: 0 24px; border: none; border-radius: 9px; font-family: 'Inter', sans-serif;
        font-size: 13.5px; font-weight: 700; color: var(--white); background: var(--amber);
        cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: background 0.15s;
    }
    .btn-save:hover { background: #D97706; }
    .btn-save i { font-size: 18px; }

    /* MULTI SHIFT ADD BUTTON */
    .btn-add-shift {
        height: 36px; padding: 0 16px; border: none; border-radius: 8px; font-family: 'Inter', sans-serif;
        font-size: 12.5px; font-weight: 700; color: var(--white); background: var(--purple);
        cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: background 0.15s;
    }
    .btn-add-shift:hover { background: #7C3AED; }

    /* SHIFT ITEM STYLES */
    .shift-item {
        background: var(--slate-50); border: 1px solid var(--slate-200);
        border-radius: 12px; overflow: hidden; margin-bottom: 20px;
    }
    .shift-header {
        padding: 12px 20px; background: var(--slate-100); border-bottom: 1px solid var(--slate-200);
        display: flex; justify-content: space-between; align-items: center;
    }
    .shift-title { font-size: 13px; font-weight: 800; color: var(--slate-900); display: flex; align-items: center; gap: 8px; }
    .shift-badge { background: var(--purple); color: var(--white); padding: 2px 8px; border-radius: 6px; font-size: 11px; }
    
    .btn-del-shift {
        background: var(--white); border: 1px solid var(--red-soft); color: var(--red);
        height: 30px; padding: 0 12px; border-radius: 6px; font-size: 12px; font-weight: 700;
        cursor: pointer; display: inline-flex; align-items: center; gap: 4px; transition: all 0.15s;
    }
    .btn-del-shift:hover { background: var(--red-soft); border-color: #FECACA; }
    .shift-body { padding: 20px; }

    /* ── ALERT BOXES ── */
    .alert-box {
        display: flex; align-items: flex-start; gap: 12px; padding: 16px; border-radius: 10px; margin-bottom: 16px;
    }
    .alert-box:last-child { margin-bottom: 0; }
    .alert-box i { font-size: 20px; line-height: 1; }
    .alert-box-warning { background: var(--amber-soft); border: 1px solid #FDE68A; color: #B45309; }
    .alert-box-info { background: var(--blue-soft); border: 1px solid var(--blue-mid); color: var(--blue-dark); }
    .ab-text { font-size: 12.5px; font-weight: 500; line-height: 1.5; padding-top: 2px; }
    .ab-text strong { font-weight: 800; }

    .meta-box {
        background: var(--slate-50); border: 1px solid var(--slate-200); border-radius: 10px; padding: 16px;
    }
    .meta-item { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; font-size: 12.5px; }
    .meta-item:last-child { margin-bottom: 0; }
    .meta-lbl { color: var(--slate-500); font-weight: 700; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; }
    .meta-val { color: var(--slate-900); font-weight: 700; }
    .meta-badge { display: inline-block; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; }
</style>
@endpush

@section('content')
<div class="form-wrap">

    {{-- HEADER --}}
    <div class="form-header">
        <div class="form-header-left">
            <div class="form-header-icon">
                <i class="mdi mdi-clock-edit-outline"></i>
            </div>
            <div>
                <div class="form-header-title">Edit Data Jam Kerja</div>
                <div class="form-header-sub">Perbarui konfigurasi jadwal masuk dan pulang</div>
            </div>
        </div>
        <div>
            <a href="{{ route('panel.jamkerja.index') }}" class="btn-back">
                <i class="mdi mdi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    {{-- ALERTS (Error Summary) --}}
    @if($errors->any())
    <div class="card" style="border-color:var(--red); background:var(--red-soft); box-shadow:none;">
        <div class="card-body" style="padding:16px;">
            <div style="font-size:13px; font-weight:800; color:var(--red); margin-bottom:8px; display:flex; align-items:center; gap:6px;">
                <i class="mdi mdi-alert-circle" style="font-size:18px;"></i> Terdapat kesalahan pada input:
            </div>
            <ul style="margin:0; padding-left:24px; color:#991B1B; font-size:12.5px; font-weight:500;">
                @foreach($errors->all() as $error)
                    <li style="margin-bottom:2px;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <div class="form-grid">
        
        {{-- LEFT COLUMN: FORM --}}
        <div>
            <form action="{{ route('panel.jamkerja.update', $jamkerja->kode_jam_kerja) }}" method="POST" id="formJamKerja">
                @csrf
                @method('PUT')
                
                {{-- CARD 1: Basic Info --}}
                <div class="card">
                    <div class="card-head">
                        <div class="card-head-left">
                            <i class="mdi mdi-information-outline"></i>
                            <h3 class="card-title">Informasi Dasar</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="fg-row">
                            <div class="fg">
                                <label>Kode Jam Kerja</label>
                                <input type="text" class="form-control" value="{{ $jamkerja->kode_jam_kerja }}" disabled>
                                <div class="form-hint"><i class="mdi mdi-lock-outline"></i> Kode tidak dapat diubah setelah dibuat.</div>
                            </div>
                            <div class="fg">
                                <label>Nama Jam Kerja <span class="req">*</span></label>
                                <input type="text" name="nama_jam_kerja" class="form-control @error('nama_jam_kerja') is-invalid @enderror" 
                                    value="{{ old('nama_jam_kerja', $jamkerja->nama_jam_kerja) }}" required>
                                @error('nama_jam_kerja')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="fg-row" style="margin-bottom: 0;">
                            <div class="fg">
                                <label>Tipe Jam Kerja <span class="req">*</span></label>
                                <select name="tipe_jam_kerja" id="tipeJamKerja" class="form-select" required>
                                    <option value="regular" {{ old('tipe_jam_kerja', $jamkerja->tipe_jam_kerja) == 'regular' ? 'selected' : '' }}>Regular (Satu Shift/Hari)</option>
                                    <option value="multi_shift" {{ old('tipe_jam_kerja', $jamkerja->tipe_jam_kerja) == 'multi_shift' ? 'selected' : '' }}>Multi Shift (Banyak Shift/Hari)</option>
                                </select>
                            </div>
                            <div class="fg">
                                <label>Lintas Hari <span class="req">*</span></label>
                                <select name="lintashari" class="form-select" required>
                                    <option value="0" {{ old('lintashari', $jamkerja->lintashari) == '0' ? 'selected' : '' }}>Tidak</option>
                                    <option value="1" {{ old('lintashari', $jamkerja->lintashari) == '1' ? 'selected' : '' }}>Ya (Shift Malam)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CARD 2: Regular Section --}}
                <div class="card" id="regularSection" style="{{ old('tipe_jam_kerja', $jamkerja->tipe_jam_kerja) == 'multi_shift' ? 'display:none;' : '' }}">
                    <div class="card-head">
                        <div class="card-head-left">
                            <i class="mdi mdi-clock-time-four-outline"></i>
                            <h3 class="card-title">Konfigurasi Jadwal (Regular)</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="time-grid">
                            <div class="fg">
                                <label>Awal Jam Masuk</label>
                                <input type="time" name="awal_jam_masuk" class="form-control" value="{{ old('awal_jam_masuk', date('H:i', strtotime($jamkerja->awal_jam_masuk))) }}">
                            </div>
                            <div class="fg">
                                <label>Jam Masuk</label>
                                <input type="time" name="jam_masuk" class="form-control" value="{{ old('jam_masuk', date('H:i', strtotime($jamkerja->jam_masuk))) }}">
                            </div>
                            <div class="fg">
                                <label>Akhir Jam Masuk</label>
                                <input type="time" name="akhir_jam_masuk" class="form-control" value="{{ old('akhir_jam_masuk', date('H:i', strtotime($jamkerja->akhir_jam_masuk))) }}">
                            </div>
                            <div class="fg">
                                <label>Jam Pulang</label>
                                <input type="time" name="jam_pulang" class="form-control" value="{{ old('jam_pulang', date('H:i', strtotime($jamkerja->jam_pulang))) }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CARD 3: Multi Shift Section --}}
                <div class="card" id="multiShiftSection" style="background: transparent; box-shadow: none; border: none; {{ old('tipe_jam_kerja', $jamkerja->tipe_jam_kerja) == 'regular' ? 'display:none;' : '' }}">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 style="font-size: 15px; font-weight: 800; color: var(--slate-900); margin:0;">
                            <i class="mdi mdi-layers" style="color:var(--purple); margin-right:6px;"></i> Konfigurasi Multi Shift
                        </h3>
                        <button type="button" class="btn-add-shift" id="addShiftBtn">
                            <i class="mdi mdi-plus"></i> Tambah Shift
                        </button>
                    </div>

                    <input type="hidden" name="total_shift" id="totalShift" value="{{ $jamkerja->shifts->count() }}">

                    <div id="shiftsContainer">
                        @foreach($jamkerja->shifts as $shift)
                        <div class="shift-item">
                            <div class="shift-header">
                                <div class="shift-title">
                                    <span class="shift-badge shift-number">{{ $shift->shift_ke }}</span> Shift {{ $shift->shift_ke }}
                                </div>
                                <button type="button" class="btn-del-shift remove-shift">
                                    <i class="mdi mdi-delete"></i> Hapus
                                </button>
                            </div>
                            <div class="shift-body">
                                <input type="hidden" name="shifts[{{ $loop->index }}][shift_ke]" class="shift-ke" value="{{ $shift->shift_ke }}">
                                
                                <div class="fg">
                                    <label>Nama Shift</label>
                                    <input type="text" name="shifts[{{ $loop->index }}][nama_shift]" class="form-control" value="{{ $shift->nama_shift }}" placeholder="Contoh: Subuh, Zuhur, Pagi..." required>
                                </div>
                                
                                <div class="time-grid">
                                    <div class="fg">
                                        <label>Awal Masuk</label>
                                        <input type="time" name="shifts[{{ $loop->index }}][awal_jam_masuk]" class="form-control" value="{{ date('H:i', strtotime($shift->awal_jam_masuk)) }}" required>
                                    </div>
                                    <div class="fg">
                                        <label>Jam Masuk</label>
                                        <input type="time" name="shifts[{{ $loop->index }}][jam_masuk]" class="form-control" value="{{ date('H:i', strtotime($shift->jam_masuk)) }}" required>
                                    </div>
                                    <div class="fg">
                                        <label>Akhir Masuk</label>
                                        <input type="time" name="shifts[{{ $loop->index }}][akhir_jam_masuk]" class="form-control" value="{{ date('H:i', strtotime($shift->akhir_jam_masuk)) }}" required>
                                    </div>
                                    <div class="fg">
                                        <label>Jam Pulang</label>
                                        <input type="time" name="shifts[{{ $loop->index }}][jam_pulang]" class="form-control" value="{{ date('H:i', strtotime($shift->jam_pulang)) }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- TEMPLATE -->
                    <template id="shiftTemplate">
                        <div class="shift-item">
                            <div class="shift-header">
                                <div class="shift-title">
                                    <span class="shift-badge shift-number">1</span> Shift Baru
                                </div>
                                <button type="button" class="btn-del-shift remove-shift">
                                    <i class="mdi mdi-delete"></i> Hapus
                                </button>
                            </div>
                            <div class="shift-body">
                                <input type="hidden" name="shifts[INDEX][shift_ke]" class="shift-ke" value="1">
                                
                                <div class="fg">
                                    <label>Nama Shift</label>
                                    <input type="text" name="shifts[INDEX][nama_shift]" class="form-control" placeholder="Contoh: Subuh, Zuhur, Pagi..." required>
                                </div>
                                
                                <div class="time-grid">
                                    <div class="fg">
                                        <label>Awal Masuk</label>
                                        <input type="time" name="shifts[INDEX][awal_jam_masuk]" class="form-control" required>
                                    </div>
                                    <div class="fg">
                                        <label>Jam Masuk</label>
                                        <input type="time" name="shifts[INDEX][jam_masuk]" class="form-control" required>
                                    </div>
                                    <div class="fg">
                                        <label>Akhir Masuk</label>
                                        <input type="time" name="shifts[INDEX][akhir_jam_masuk]" class="form-control" required>
                                    </div>
                                    <div class="fg">
                                        <label>Jam Pulang</label>
                                        <input type="time" name="shifts[INDEX][jam_pulang]" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- FOOTER ACTIONS --}}
                <div class="card" style="background:transparent; border:none; box-shadow:none; margin-bottom:0;">
                    <div class="form-actions" style="margin-top:0; border-top:none; padding-top:0;">
                        <button type="submit" class="btn-save">
                            <i class="mdi mdi-content-save-edit"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>

            </form>
        </div>

        {{-- RIGHT COLUMN: SIDEBAR --}}
        <div class="side-col">
            {{-- SUMMARY CARD --}}
            <div class="card">
                <div class="card-head">
                    <i class="mdi mdi-text-box-search-outline"></i>
                    <h3 class="card-title">Ringkasan Jam Kerja</h3>
                </div>
                <div class="card-body">
                    <div class="meta-box">
                        <div class="meta-item">
                            <span class="meta-lbl">Kode Jam Kerja</span>
                            <span class="meta-val" style="font-family:monospace; background:var(--amber-soft); color:#D97706; padding:2px 8px; border-radius:4px; border:1px solid #FDE68A;">{{ $jamkerja->kode_jam_kerja }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-lbl">Tipe Jam Kerja</span>
                            @if($jamkerja->tipe_jam_kerja == 'multi_shift')
                                <span class="meta-badge" style="background:var(--blue-soft); color:var(--blue-dark); border:1px solid var(--blue-mid);">Multi Shift</span>
                            @else
                                <span class="meta-badge" style="background:var(--slate-200); color:var(--slate-700);">Regular</span>
                            @endif
                        </div>
                        @if($jamkerja->tipe_jam_kerja == 'multi_shift')
                        <div class="meta-item">
                            <span class="meta-lbl">Total Shift</span>
                            <span class="meta-val">{{ $jamkerja->shifts->count() }} Shift / Hari</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- WARNING & USAGE CARD --}}
            <div class="card">
                <div class="card-head">
                    <i class="mdi mdi-shield-alert-outline" style="color:var(--amber);"></i>
                    <h3 class="card-title">Informasi Penggunaan</h3>
                </div>
                <div class="card-body">
                    @if($total_penggunaan > 0)
                    <div class="alert-box alert-box-info">
                        <i class="mdi mdi-information"></i>
                        <div class="ab-text">
                            Jam kerja ini sedang digunakan oleh <strong>{{ $total_penggunaan }}</strong> konfigurasi departemen/karyawan.
                        </div>
                    </div>
                    @endif

                    <div class="alert-box alert-box-warning">
                        <i class="mdi mdi-alert"></i>
                        <div class="ab-text">
                            <strong>Perhatian:</strong> Perubahan jadwal pada jam kerja ini akan otomatis mengubah jadwal bagi seluruh karyawan yang menggunakannya. Pastikan jadwal yang dimasukkan sudah benar.
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    let shiftIndex = {{ $jamkerja->shifts->count() }};

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
            $(this).closest('.shift-item').fadeOut(300, function() {
                $(this).remove();
                updateShiftNumbers();
                updateTotalShift();
            });
        });

        // Initialize state based on value
        const initialType = $('#tipeJamKerja').val();
        if (initialType === 'multi_shift') {
            $('#regularSection').hide();
            $('#multiShiftSection').show();
        } else {
            $('#regularSection').show();
            $('#multiShiftSection').hide();
        }
    });

    function toggleSections() {
        const tipe = $('#tipeJamKerja').val();

        if (tipe === 'multi_shift') {
            $('#regularSection').fadeOut(200, function() {
                $('#multiShiftSection').fadeIn(300);
            });
            $('#regularSection input').prop('required', false);
            $('#multiShiftSection input[type="time"]').prop('required', true);
        } else {
            $('#multiShiftSection').fadeOut(200, function() {
                $('#regularSection').fadeIn(300);
            });
            $('#regularSection input').prop('required', true);
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

        // Append to container with fade in
        const $el = $(html).hide().appendTo('#shiftsContainer').fadeIn(300);

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