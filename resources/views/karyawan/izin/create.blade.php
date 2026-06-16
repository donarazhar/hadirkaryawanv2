@extends('karyawan.layouts.presensi')

@section('content')

<style>

    :root {
        --primary:       #2563EB;
        --primary-soft:  #EFF6FF;
        --primary-mid:   #BFDBFE;
        --success:       #10B981;
        --success-soft:  #ECFDF5;
        --danger:        #EF4444;
        --danger-soft:   #FEF2F2;
        --warning:       #F59E0B;
        --warning-soft:  #FFFBEB;
        --info:          #06B6D4;
        --info-soft:     #ECFEFF;
        --purple:        #8B5CF6;
        --purple-soft:   #F5F3FF;
        --text-900:      #111827;
        --text-600:      #4B5563;
        --text-400:      #9CA3AF;
        --border:        #F1F5F9;
        --border-med:    #E2E8F0;
        --surface:       #FFFFFF;
        --bg:            #F8FAFC;
        --radius-sm:     10px;
        --radius-md:     14px;
        --radius-lg:     18px;
        --shadow-sm:     0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: 'Inter', -apple-system, sans-serif;
        background: var(--bg);
        color: var(--text-900);
        -webkit-font-smoothing: antialiased;
    }

    /* ── PAGE HEADER ── */
    .pg-header {
        background: var(--surface);
        border-bottom: 1px solid var(--border);
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        position: sticky;
        top: 0;
        z-index: 50;
    }

    .btn-back {
        width: 36px; height: 36px;
        background: var(--bg);
        border: 1px solid var(--border-med);
        border-radius: var(--radius-sm);
        display: flex; align-items: center; justify-content: center;
        text-decoration: none;
        transition: background 0.2s;
        flex-shrink: 0;
        -webkit-tap-highlight-color: transparent;
    }

    .btn-back:active { background: var(--border-med); }
    .btn-back ion-icon { font-size: 20px; color: var(--text-600); }

    .pg-title {
        font-size: 17px;
        font-weight: 700;
        color: var(--text-900);
        line-height: 1.2;
    }

    .pg-sub {
        font-size: 11px;
        font-weight: 500;
        color: var(--primary);
        display: block;
        margin-top: 1px;
    }

    /* ── PAGE BODY ── */
    .pg-body {
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 14px;
        padding-bottom: 100px;
    }

    /* ── ALERT ── */
    .alert-box {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 12px 14px;
        border-radius: var(--radius-md);
        border: 1px solid #FECACA;
        background: var(--danger-soft);
        animation: fadeSlide 0.3s ease;
    }

    .alert-box ion-icon { font-size: 18px; color: var(--danger); flex-shrink: 0; margin-top: 1px; }

    .alert-box .alert-text {
        font-size: 13px;
        color: #DC2626;
        line-height: 1.5;
    }

    .alert-box ul { padding-left: 16px; margin-top: 6px; }
    .alert-box ul li { font-size: 12px; margin-bottom: 2px; }

    /* ── TYPE SELECTOR ── */
    .type-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
    }

    .type-opt { position: relative; }
    .type-opt input[type="radio"] { display: none; }

    .type-lbl {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 14px 10px;
        background: var(--bg);
        border: 2px solid var(--border-med);
        border-radius: var(--radius-md);
        cursor: pointer;
        transition: border-color 0.2s, background 0.2s;
        -webkit-tap-highlight-color: transparent;
    }

    .type-opt input:checked + .type-lbl {
        background: var(--type-soft);
        border-color: var(--type-color);
    }

    .type-icon-box {
        width: 40px; height: 40px;
        border-radius: 12px;
        background: var(--surface);
        border: 1.5px solid var(--border-med);
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 8px;
        transition: background 0.2s, border-color 0.2s;
    }

    .type-icon-box ion-icon { font-size: 20px; color: var(--type-color); }

    .type-opt input:checked + .type-lbl .type-icon-box {
        background: var(--type-color);
        border-color: var(--type-color);
    }

    .type-opt input:checked + .type-lbl .type-icon-box ion-icon { color: white; }

    .type-name {
        font-size: 12px;
        font-weight: 700;
        color: var(--text-600);
        transition: color 0.2s;
    }

    .type-opt input:checked + .type-lbl .type-name { color: var(--type-color); }

    /* Type color vars */
    .type-opt:nth-child(1) { --type-color: var(--warning); --type-soft: var(--warning-soft); }
    .type-opt:nth-child(2) { --type-color: var(--info);    --type-soft: var(--info-soft); }
    .type-opt:nth-child(3) { --type-color: var(--purple);  --type-soft: var(--purple-soft); }

    /* ── CARD ── */
    .form-card {
        background: var(--surface);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    .card-head {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 14px 16px 12px;
        border-bottom: 1px solid var(--border);
    }

    .card-head-icon {
        width: 30px; height: 30px;
        border-radius: 8px;
        background: var(--primary-soft);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }

    .card-head-icon ion-icon { font-size: 16px; color: var(--primary); }

    .card-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-900);
    }

    /* ── FORM FIELDS ── */
    .form-body { padding: 14px 16px; display: flex; flex-direction: column; gap: 14px; }

    .form-group { display: flex; flex-direction: column; gap: 6px; }

    .form-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--text-600);
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .form-label ion-icon { font-size: 14px; color: var(--primary); }
    .form-label .req { color: var(--danger); }

    .form-control {
        width: 100%;
        padding: 11px 14px;
        border: 1.5px solid var(--border-med);
        border-radius: var(--radius-sm);
        font-family: 'Inter', sans-serif;
        font-size: 13px;
        font-weight: 500;
        color: var(--text-900);
        background: var(--surface);
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        -webkit-appearance: none;
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37,99,235,0.10);
    }

    textarea.form-control {
        min-height: 100px;
        resize: vertical;
        line-height: 1.6;
    }

    select.form-control { cursor: pointer; }

    .form-hint {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        color: var(--text-400);
    }

    .form-hint ion-icon { font-size: 13px; color: var(--primary); }

    /* ── FILE UPLOAD ── */
    .file-upload-area {
        border: 2px dashed var(--border-med);
        border-radius: var(--radius-sm);
        padding: 16px;
        text-align: center;
        cursor: pointer;
        transition: border-color 0.2s, background 0.2s;
        background: var(--bg);
        -webkit-tap-highlight-color: transparent;
    }

    .file-upload-area:active { border-color: var(--primary); background: var(--primary-soft); }

    .file-upload-area ion-icon { font-size: 28px; color: var(--text-400); margin-bottom: 6px; display: block; }

    .file-upload-text {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-600);
        display: block;
    }

    .file-upload-sub {
        font-size: 11px;
        color: var(--text-400);
        margin-top: 2px;
        display: block;
    }

    .file-name-tag {
        display: none;
        align-items: center;
        gap: 6px;
        padding: 8px 12px;
        background: var(--success-soft);
        border: 1px solid #A7F3D0;
        border-radius: var(--radius-sm);
        margin-top: 8px;
        font-size: 12px;
        font-weight: 600;
        color: var(--success);
    }

    .file-name-tag.show { display: flex; }
    .file-name-tag ion-icon { font-size: 15px; }

    /* ── SUBMIT BUTTON ── */
    .btn-submit {
        width: 100%;
        padding: 14px;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: var(--radius-md);
        font-family: 'Inter', sans-serif;
        font-size: 15px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        box-shadow: 0 4px 14px rgba(37,99,235,0.28);
        transition: opacity 0.2s, transform 0.15s;
        -webkit-tap-highlight-color: transparent;
    }

    .btn-submit ion-icon { font-size: 18px; }
    .btn-submit:active { opacity: 0.88; transform: scale(0.98); }
    .btn-submit:disabled { opacity: 0.55; cursor: not-allowed; transform: none; }

    /* ── Animations ── */
    @keyframes fadeSlide {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .pg-body > * {
        animation: fadeSlide 0.3s ease both;
    }
    .pg-body > *:nth-child(1) { animation-delay: 0.04s; }
    .pg-body > *:nth-child(2) { animation-delay: 0.08s; }
    .pg-body > *:nth-child(3) { animation-delay: 0.12s; }
    .pg-body > *:nth-child(4) { animation-delay: 0.16s; }
    .pg-body > *:nth-child(5) { animation-delay: 0.20s; }

    @media (max-width: 360px) {
        .type-grid { grid-template-columns: 1fr; }
    }
</style>

{{-- ── PAGE HEADER ── --}}
<div class="pg-header">
    <a href="{{ route('izin.index') }}" class="btn-back">
        <ion-icon name="chevron-back-outline"></ion-icon>
    </a>
    <div>
        <div class="pg-title">Buat Pengajuan</div>
        <span class="pg-sub">Ajukan izin, sakit, atau cuti</span>
    </div>
</div>

{{-- ── PAGE BODY ── --}}
<div class="pg-body">

    {{-- Error alerts --}}
    @if(session('error'))
    <div class="alert-box">
        <ion-icon name="alert-circle-outline"></ion-icon>
        <div class="alert-text">{{ session('error') }}</div>
    </div>
    @endif

    @if($errors->any())
    <div class="alert-box">
        <ion-icon name="alert-circle-outline"></ion-icon>
        <div class="alert-text">
            <strong>Periksa kembali form Anda:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <form action="{{ route('izin.store') }}" method="POST" enctype="multipart/form-data" id="form-izin">
        @csrf

        {{-- Type selector --}}
        <div class="form-card">
            <div class="card-head">
                <div class="card-head-icon"><ion-icon name="clipboard-outline"></ion-icon></div>
                <div class="card-title">Tipe Pengajuan</div>
            </div>
            <div class="form-body">
                <div class="type-grid">
                    <div class="type-opt">
                        <input type="radio" name="status" id="type-izin" value="i" {{ old('status') == 'i' ? 'checked' : '' }} required>
                        <label for="type-izin" class="type-lbl">
                            <div class="type-icon-box"><ion-icon name="calendar-outline"></ion-icon></div>
                            <span class="type-name">Izin</span>
                        </label>
                    </div>
                    <div class="type-opt">
                        <input type="radio" name="status" id="type-sakit" value="s" {{ old('status') == 's' ? 'checked' : '' }}>
                        <label for="type-sakit" class="type-lbl">
                            <div class="type-icon-box"><ion-icon name="medkit-outline"></ion-icon></div>
                            <span class="type-name">Sakit</span>
                        </label>
                    </div>
                    <div class="type-opt">
                        <input type="radio" name="status" id="type-cuti" value="c" {{ old('status') == 'c' ? 'checked' : '' }}>
                        <label for="type-cuti" class="type-lbl">
                            <div class="type-icon-box"><ion-icon name="leaf-outline"></ion-icon></div>
                            <span class="type-name">Cuti</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        {{-- Jenis Cuti (conditional) --}}
        <div class="form-card" id="form-cuti" style="display:none;">
            <div class="card-head">
                <div class="card-head-icon"><ion-icon name="list-outline"></ion-icon></div>
                <div class="card-title">Jenis Cuti</div>
            </div>
            <div class="form-body">
                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="leaf-outline"></ion-icon>
                        Pilih Cuti <span class="req">*</span>
                    </label>
                    <select name="kode_cuti" id="kode_cuti" class="form-control">
                        <option value="">— Pilih Jenis Cuti —</option>
                        @foreach($cuti as $c)
                            <option value="{{ $c->kode_cuti }}" {{ old('kode_cuti') == $c->kode_cuti ? 'selected' : '' }}>
                                {{ $c->nama_cuti }} (Max: {{ $c->jml_hari }} Hari)
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Periode --}}
        <div class="form-card">
            <div class="card-head">
                <div class="card-head-icon"><ion-icon name="calendar-outline"></ion-icon></div>
                <div class="card-title">Periode Izin</div>
            </div>
            <div class="form-body">
                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="log-in-outline"></ion-icon>
                        Tanggal Mulai <span class="req">*</span>
                    </label>
                    <input type="date" name="tgl_izin_dari" class="form-control"
                           value="{{ old('tgl_izin_dari') }}"
                           required max="{{ date('Y-m-d', strtotime('+30 days')) }}">
                    <div class="form-hint">
                        <ion-icon name="information-circle-outline"></ion-icon>
                        Maksimal 30 hari ke depan
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="log-out-outline"></ion-icon>
                        Tanggal Selesai <span class="req">*</span>
                    </label>
                    <input type="date" name="tgl_izin_sampai" class="form-control"
                           value="{{ old('tgl_izin_sampai') }}"
                           required max="{{ date('Y-m-d', strtotime('+30 days')) }}">
                </div>
            </div>
        </div>

        {{-- Detail --}}
        <div class="form-card">
            <div class="card-head">
                <div class="card-head-icon"><ion-icon name="document-text-outline"></ion-icon></div>
                <div class="card-title">Detail Pengajuan</div>
            </div>
            <div class="form-body">
                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="chatbubble-outline"></ion-icon>
                        Keterangan <span class="req">*</span>
                    </label>
                    <textarea name="keterangan" class="form-control"
                              placeholder="Jelaskan alasan pengajuan Anda…" required>{{ old('keterangan') }}</textarea>
                    <div class="form-hint">
                        <ion-icon name="information-circle-outline"></ion-icon>
                        Minimal 10 karakter
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="document-attach-outline"></ion-icon>
                        Dokumen Pendukung <span style="color:var(--text-400); font-weight:500;">(Opsional)</span>
                    </label>
                    <input type="file" name="doc_sid" id="doc_sid" accept="image/*,.pdf"
                           style="display:none;" onchange="showFileName(event)">
                    <label for="doc_sid" class="file-upload-area">
                        <ion-icon name="cloud-upload-outline"></ion-icon>
                        <span class="file-upload-text">Ketuk untuk memilih file</span>
                        <span class="file-upload-sub">Foto atau PDF — maks. 2MB</span>
                    </label>
                    <div class="file-name-tag" id="file-name-tag">
                        <ion-icon name="document-outline"></ion-icon>
                        <span id="file-name-text"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn-submit" id="btn-submit">
            <ion-icon name="paper-plane-outline"></ion-icon>
            Kirim Pengajuan
        </button>

    </form>

</div>

@endsection

@push('myscript')
<script>
    function showFileName(event) {
        var file = event.target.files[0];
        if (!file) { document.getElementById('file-name-tag').classList.remove('show'); return; }

        if (file.size > 2048000) {
            Swal.fire({ icon:'error', title:'File Terlalu Besar', text:'Ukuran file maksimal 2MB', confirmButtonColor:'#2563EB' });
            event.target.value = '';
            return;
        }

        document.getElementById('file-name-text').textContent = file.name;
        document.getElementById('file-name-tag').classList.add('show');
    }

    $(function () {
        /* ── Form validation ── */
        $('#form-izin').on('submit', function (e) {
            var dari    = new Date($('[name="tgl_izin_dari"]').val());
            var sampai  = new Date($('[name="tgl_izin_sampai"]').val());
            var ket     = $('[name="keterangan"]').val().trim();

            if (sampai < dari) {
                e.preventDefault();
                return Swal.fire({ icon:'error', title:'Tanggal Tidak Valid', text:'Tanggal selesai tidak boleh sebelum tanggal mulai', confirmButtonColor:'#2563EB' });
            }

            if (ket.length < 10) {
                e.preventDefault();
                return Swal.fire({ icon:'error', title:'Keterangan Terlalu Singkat', text:'Keterangan minimal 10 karakter', confirmButtonColor:'#2563EB' });
            }

            var btn = $('#btn-submit');
            btn.prop('disabled', true).html('<ion-icon name="hourglass-outline"></ion-icon> Mengirim…');
        });

        /* ── Toggle cuti section ── */
        $('input[name="status"]').on('change', function () {
            if ($(this).val() === 'c') {
                $('#form-cuti').slideDown(200);
                $('#kode_cuti').prop('required', true);
            } else {
                $('#form-cuti').slideUp(200);
                $('#kode_cuti').prop('required', false).val('');
            }
        });

        /* ── Restore state for old() ── */
        if ($('input[name="status"]:checked').val() === 'c') {
            $('#form-cuti').show();
            $('#kode_cuti').prop('required', true);
        }

        /* ── Auto-fill tanggal sampai ── */
        $('[name="tgl_izin_dari"]').on('change', function () {
            if ($(this).val() && !$('[name="tgl_izin_sampai"]').val()) {
                $('[name="tgl_izin_sampai"]').val($(this).val());
            }
        });
    });
</script>
@endpush