@extends('admin.layouts.admin')

@section('title', 'Tambah Jam Kerja')
@section('page-title', 'Tambah Jam Kerja')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
        width: 46px; height: 46px; border-radius: 12px; background: var(--purple-soft);
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .form-header-icon i { font-size: 22px; color: var(--purple); }
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
        font-size: 13.5px; font-weight: 700; color: var(--white); background: var(--blue);
        cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: background 0.15s;
    }
    .btn-save:hover { background: var(--blue-dark); }
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

    /* ── INFO CARD ── */
    .info-card { position: sticky; top: 20px; }
    .info-list { margin: 0; padding: 0; list-style: none; display: flex; flex-direction: column; gap: 14px; }
    .info-item { display: flex; gap: 12px; }
    .ii-icon {
        width: 32px; height: 32px; border-radius: 8px; background: var(--slate-50);
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        border: 1px solid var(--slate-200); color: var(--slate-600); font-size: 16px;
    }
    .ii-text { font-size: 12.5px; color: var(--slate-600); line-height: 1.5; padding-top: 4px; }
    .ii-text strong { color: var(--slate-900); font-weight: 700; }
</style>
@endpush

@section('content')
<div class="form-wrap">

    {{-- HEADER --}}
    <div class="form-header">
        <div class="form-header-left">
            <div class="form-header-icon">
                <i class="mdi mdi-clock-plus-outline"></i>
            </div>
            <div>
                <div class="form-header-title">Tambah Jam Kerja</div>
                <div class="form-header-sub">Buat konfigurasi jam masuk dan pulang karyawan</div>
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
            <form action="{{ route('panel.jamkerja.store') }}" method="POST" id="formJamKerja">
                @csrf
                
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
                                <label>Kode Jam Kerja <span class="req">*</span></label>
                                <input type="text" name="kode_jam_kerja" class="form-control @error('kode_jam_kerja') is-invalid @enderror" 
                                    value="{{ $autoCode ?? old('kode_jam_kerja') }}" required readonly>
                                @error('kode_jam_kerja')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="fg">
                                <label>Nama Jam Kerja <span class="req">*</span></label>
                                <input type="text" name="nama_jam_kerja" class="form-control @error('nama_jam_kerja') is-invalid @enderror" 
                                    value="{{ old('nama_jam_kerja') }}" required>
                                @error('nama_jam_kerja')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="fg-row" style="margin-bottom: 0;">
                            <div class="fg">
                                <label>Tipe Jam Kerja <span class="req">*</span></label>
                                <select name="tipe_jam_kerja" id="tipeJamKerja" class="form-select" required>
                                    <option value="regular" {{ old('tipe_jam_kerja') == 'regular' ? 'selected' : '' }}>Regular (Satu Shift/Hari)</option>
                                    <option value="multi_shift" {{ old('tipe_jam_kerja') == 'multi_shift' ? 'selected' : '' }}>Multi Shift (Banyak Shift/Hari)</option>
                                </select>
                                <div class="form-hint">Pilih "Multi Shift" untuk jam kerja seperti Imam/Muazin.</div>
                            </div>
                            <div class="fg">
                                <label>Lintas Hari <span class="req">*</span></label>
                                <select name="lintashari" class="form-select" required>
                                    <option value="0" {{ old('lintashari') == '0' ? 'selected' : '' }}>Tidak</option>
                                    <option value="1" {{ old('lintashari') == '1' ? 'selected' : '' }}>Ya</option>
                                </select>
                                <div class="form-hint">Pilih "Ya" jika jam pulang melewati tengah malam (hari berikutnya).</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CARD 2: Regular Section --}}
                <div class="card" id="regularSection">
                    <div class="card-head">
                        <div class="card-head-left">
                            <i class="mdi mdi-clock-time-four-outline"></i>
                            <h3 class="card-title">Konfigurasi Jadwal (Regular)</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="time-grid">
                            <div class="fg">
                                <label>Awal Jam Masuk (WIB)</label>
                                <input type="time" name="awal_jam_masuk" class="form-control time-picker-wib" value="{{ old('awal_jam_masuk') }}">
                            </div>
                            <div class="fg">
                                <label>Jam Masuk (WIB)</label>
                                <input type="time" name="jam_masuk" class="form-control time-picker-wib" value="{{ old('jam_masuk') }}">
                            </div>
                            <div class="fg">
                                <label>Akhir Jam Masuk (WIB)</label>
                                <input type="time" name="akhir_jam_masuk" class="form-control time-picker-wib" value="{{ old('akhir_jam_masuk') }}">
                            </div>
                            <div class="fg">
                                <label>Jam Pulang (WIB)</label>
                                <input type="time" name="jam_pulang" class="form-control time-picker-wib" value="{{ old('jam_pulang') }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CARD 3: Multi Shift Section --}}
                <div class="card" id="multiShiftSection" style="display: none; background: transparent; box-shadow: none; border: none;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 style="font-size: 15px; font-weight: 800; color: var(--slate-900); margin:0;">
                            <i class="mdi mdi-layers" style="color:var(--purple); margin-right:6px;"></i> Konfigurasi Multi Shift
                        </h3>
                        <button type="button" class="btn-add-shift" id="addShiftBtn">
                            <i class="mdi mdi-plus"></i> Tambah Shift
                        </button>
                    </div>

                    <input type="hidden" name="total_shift" id="totalShift" value="1">

                    <div id="shiftsContainer">
                        <!-- Shift items inserted here -->
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
                <div class="card" style="background:transparent; border:none; box-shadow:none;">
                    <div class="form-actions" style="margin-top:0; border-top:none;">
                        <button type="submit" class="btn-save">
                            <i class="mdi mdi-content-save-check"></i> Simpan Jam Kerja
                        </button>
                    </div>
                </div>

            </form>
        </div>

        {{-- RIGHT COLUMN: INFO --}}
        <div>
            <div class="card info-card">
                <div class="card-head">
                    <i class="mdi mdi-lightbulb-on-outline" style="color:var(--amber);"></i>
                    <h3 class="card-title">Panduan Waktu</h3>
                </div>
                <div class="card-body">
                    <ul class="info-list">
                        <li class="info-item">
                            <div class="ii-icon"><i class="mdi mdi-ray-start-arrow text-primary"></i></div>
                            <div class="ii-text"><strong>Awal Masuk:</strong> Batas paling awal karyawan diizinkan melakukan scan absen masuk.</div>
                        </li>
                        <li class="info-item">
                            <div class="ii-icon"><i class="mdi mdi-login text-success"></i></div>
                            <div class="ii-text"><strong>Jam Masuk:</strong> Waktu wajib kehadiran (Tenggat/Jadwal Resmi).</div>
                        </li>
                        <li class="info-item">
                            <div class="ii-icon"><i class="mdi mdi-ray-end-arrow text-warning"></i></div>
                            <div class="ii-text"><strong>Akhir Masuk:</strong> Batas akhir karyawan bisa absen. Lebih dari ini dianggap tidak hadir (kecuali disetel lain).</div>
                        </li>
                        <li class="info-item">
                            <div class="ii-icon"><i class="mdi mdi-logout text-danger"></i></div>
                            <div class="ii-text"><strong>Jam Pulang:</strong> Waktu jadwal kepulangan.</div>
                        </li>
                        <li class="info-item mt-3">
                            <div class="ii-icon" style="background:var(--purple-soft); border-color:#DDD6FE;"><i class="mdi mdi-layers text-purple"></i></div>
                            <div class="ii-text"><strong>Tipe Multi Shift</strong> cocok untuk pegawai dengan jadwal terpecah dalam 1 hari (misal 5 waktu sholat).</div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
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

        // Init Flatpickr for 24h format (WIB)
        flatpickr(".time-picker-wib", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true
        });
    });

    function toggleSections() {
        const tipe = $('#tipeJamKerja').val();

        if (tipe === 'multi_shift') {
            $('#regularSection').hide();
            $('#regularSection input').prop('required', false);
            $('#multiShiftSection').fadeIn(300);
            $('#multiShiftSection input[type="time"]').prop('required', true);
        } else {
            $('#regularSection').fadeIn(300);
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