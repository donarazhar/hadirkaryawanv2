@extends('karyawan.layouts.presensi')

@section('content')

<style>
    /* ===== MODERN CREATE IZIN PAGE ===== */
    :root {
        --primary: #0053C5;
        --primary-dark: #003d94;
        --primary-light: #2E7CE6;
        --primary-gradient: linear-gradient(135deg, #0053C5 0%, #2E7CE6 100%);
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --info: #06b6d4;
        --bg-main: #f0f4f8;
        --bg-card: #ffffff;
        --text-primary: #1e293b;
        --text-secondary: #64748b;
    }

    body {
        background: var(--bg-main);
    }

    /* ===== PAGE HEADER ===== */
    .page-header {
        background: var(--primary-gradient);
        padding: 24px 20px 70px 20px;
        position: relative;
        overflow: hidden;
        margin: 0;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -40%;
        right: -15%;
        width: 250px;
        height: 250px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
        filter: blur(50px);
    }

    .header-content {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .btn-back {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-back ion-icon {
        font-size: 24px;
        color: white;
    }

    .btn-back:active {
        transform: scale(0.95);
        background: rgba(255, 255, 255, 0.25);
    }

    .header-title {
        flex: 1;
    }

    .header-title h1 {
        font-size: 22px;
        font-weight: 700;
        color: white;
        margin: 0 0 4px 0;
    }

    .header-title p {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.8);
        margin: 0;
    }

    /* ===== FORM SECTION ===== */
    .form-section {
        padding: 0 20px;
        margin-top: -55px;
        margin-bottom: 100px;
        position: relative;
        z-index: 10;
    }

    .form-card {
        background: var(--bg-card);
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 83, 197, 0.08);
        margin-bottom: 16px;
    }

    .form-card-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-card-title ion-icon {
        font-size: 20px;
        color: var(--primary);
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-group:last-child {
        margin-bottom: 0;
    }

    .form-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-secondary);
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .form-label ion-icon {
        font-size: 16px;
        color: var(--primary);
    }

    .form-label .required {
        color: var(--danger);
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        font-size: 14px;
        color: var(--text-primary);
        background: #f8fafc;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        background: white;
        box-shadow: 0 0 0 3px rgba(0, 83, 197, 0.1);
    }

    textarea.form-control {
        min-height: 100px;
        resize: vertical;
    }

    .form-hint {
        font-size: 11px;
        color: var(--text-secondary);
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .form-hint ion-icon {
        font-size: 14px;
    }

    /* Type Selection Radio */
    .type-selection {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
    }

    .type-option {
        position: relative;
    }

    .type-option input[type="radio"] {
        display: none;
    }

    .type-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 16px 12px;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .type-option input[type="radio"]:checked+.type-label {
        background: var(--type-bg);
        border-color: var(--type-color);
        box-shadow: 0 4px 12px var(--type-shadow);
    }

    .type-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        margin-bottom: 8px;
        transition: all 0.3s ease;
    }

    .type-option input[type="radio"]:checked+.type-label .type-icon {
        background: var(--type-color);
    }

    .type-icon ion-icon {
        font-size: 24px;
        color: var(--type-color);
        transition: all 0.3s ease;
    }

    .type-option input[type="radio"]:checked+.type-label .type-icon ion-icon {
        color: white;
    }

    .type-text {
        font-size: 12px;
        font-weight: 600;
        color: var(--text-secondary);
        transition: all 0.3s ease;
    }

    .type-option input[type="radio"]:checked+.type-label .type-text {
        color: var(--type-color);
    }

    .type-option:nth-child(1) {
        --type-color: var(--warning);
        --type-bg: rgba(245, 158, 11, 0.1);
        --type-shadow: rgba(245, 158, 11, 0.2);
    }

    .type-option:nth-child(2) {
        --type-color: var(--info);
        --type-bg: rgba(6, 182, 212, 0.1);
        --type-shadow: rgba(6, 182, 212, 0.2);
    }

    .type-option:nth-child(3) {
        --type-color: var(--success);
        --type-bg: rgba(16, 185, 129, 0.1);
        --type-shadow: rgba(16, 185, 129, 0.2);
    }

    /* File Upload */
    .file-upload {
        position: relative;
    }

    .file-upload-label {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 14px 20px;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border: 2px dashed #e2e8f0;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .file-upload-label:hover {
        border-color: var(--primary);
        background: rgba(0, 83, 197, 0.05);
    }

    .file-upload-label ion-icon {
        font-size: 24px;
        color: var(--primary);
    }

    .file-upload-text {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-secondary);
    }

    .file-name {
        margin-top: 8px;
        padding: 8px 12px;
        background: rgba(16, 185, 129, 0.1);
        border-radius: 8px;
        font-size: 12px;
        color: var(--success);
        display: none;
    }

    .file-name.show {
        display: block;
    }

    /* Buttons */
    .btn-primary {
        width: 100%;
        padding: 14px 20px;
        background: var(--primary-gradient);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 83, 197, 0.3);
    }

    .btn-primary ion-icon {
        font-size: 20px;
    }

    .btn-primary:active {
        transform: scale(0.98);
        box-shadow: 0 2px 8px rgba(0, 83, 197, 0.3);
    }

    .btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* Alerts */
    .alert {
        padding: 14px 16px;
        border-radius: 12px;
        margin-bottom: 16px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }

    .alert ion-icon {
        font-size: 20px;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .alert-error {
        background: #fee2e2;
        color: #dc2626;
        border: 1px solid #fca5a5;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 375px) {
        .form-section {
            padding-left: 16px;
            padding-right: 16px;
        }

        .type-selection {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <div class="header-content">
        <a href="{{ route('izin.index') }}" class="btn-back">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
        <div class="header-title">
            <h1>Buat Pengajuan</h1>
            <p>Ajukan izin, sakit, atau cuti</p>
        </div>
    </div>
</div>

<!-- Form Section -->
<div class="form-section">
    <!-- Alerts -->
    @if(session('error'))
    <div class="alert alert-error">
        <ion-icon name="alert-circle"></ion-icon>
        <div>{{ session('error') }}</div>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-error">
        <ion-icon name="alert-circle"></ion-icon>
        <div>
            <strong>Periksa kembali form Anda:</strong>
            <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                <li style="font-size: 13px;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <form action="{{ route('izin.store') }}" method="POST" enctype="multipart/form-data" id="form-izin">
        @csrf

        <!-- Tipe Izin -->
        <div class="form-card">
            <div class="form-card-title">
                <ion-icon name="clipboard"></ion-icon>
                Tipe Pengajuan
            </div>

            <div class="form-group">
                <label class="form-label">
                    <ion-icon name="list"></ion-icon>
                    Pilih Tipe <span class="required">*</span>
                </label>
                <div class="type-selection">
                    <div class="type-option">
                        <input type="radio" name="status" id="type-izin" value="i" {{ old('status') == 'i' ? 'checked' : '' }} required>
                        <label for="type-izin" class="type-label">
                            <div class="type-icon">
                                <ion-icon name="calendar"></ion-icon>
                            </div>
                            <span class="type-text">Izin</span>
                        </label>
                    </div>
                    <div class="type-option">
                        <input type="radio" name="status" id="type-sakit" value="s" {{ old('status') == 's' ? 'checked' : '' }}>
                        <label for="type-sakit" class="type-label">
                            <div class="type-icon">
                                <ion-icon name="medkit"></ion-icon>
                            </div>
                            <span class="type-text">Sakit</span>
                        </label>
                    </div>
                    <div class="type-option">
                        <input type="radio" name="status" id="type-cuti" value="c" {{ old('status') == 'c' ? 'checked' : '' }}>
                        <label for="type-cuti" class="type-label">
                            <div class="type-icon">
                                <ion-icon name="time"></ion-icon>
                            </div>
                            <span class="type-text">Cuti</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tanggal -->
        <div class="form-card">
            <div class="form-card-title">
                <ion-icon name="calendar-outline"></ion-icon>
                Periode Izin
            </div>

            <div class="form-group">
                <label class="form-label">
                    <ion-icon name="calendar"></ion-icon>
                    Tanggal Mulai <span class="required">*</span>
                </label>
                <input type="date" name="tgl_izin_dari" class="form-control"
                    value="{{ old('tgl_izin_dari') }}" required max="{{ date('Y-m-d', strtotime('+30 days')) }}">
                <div class="form-hint">
                    <ion-icon name="information-circle"></ion-icon>
                    Maksimal 30 hari ke depan
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">
                    <ion-icon name="calendar"></ion-icon>
                    Tanggal Selesai <span class="required">*</span>
                </label>
                <input type="date" name="tgl_izin_sampai" class="form-control"
                    value="{{ old('tgl_izin_sampai') }}" required max="{{ date('Y-m-d', strtotime('+30 days')) }}">
            </div>
        </div>

        <!-- Keterangan -->
        <div class="form-card">
            <div class="form-card-title">
                <ion-icon name="document-text-outline"></ion-icon>
                Detail Pengajuan
            </div>

            <div class="form-group">
                <label class="form-label">
                    <ion-icon name="text"></ion-icon>
                    Keterangan <span class="required">*</span>
                </label>
                <textarea name="keterangan" class="form-control"
                    placeholder="Jelaskan alasan pengajuan Anda..." required>{{ old('keterangan') }}</textarea>
                <div class="form-hint">
                    <ion-icon name="information-circle"></ion-icon>
                    Minimal 10 karakter
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">
                    <ion-icon name="document-attach"></ion-icon>
                    Dokumen Pendukung (Opsional)
                </label>
                <div class="file-upload">
                    <input type="file" name="doc_sid" id="doc_sid" accept="image/*,.pdf" style="display: none;" onchange="showFileName(event)">
                    <label for="doc_sid" class="file-upload-label">
                        <ion-icon name="cloud-upload"></ion-icon>
                        <span class="file-upload-text">Pilih File (Foto/PDF)</span>
                    </label>
                    <div class="file-name" id="file-name"></div>
                </div>
                <div class="form-hint">
                    <ion-icon name="information-circle"></ion-icon>
                    Format: JPG, PNG, PDF | Maksimal 2MB
                </div>
            </div>
        </div>

        <button type="submit" class="btn-primary" id="btn-submit">
            <ion-icon name="paper-plane"></ion-icon>
            <span>Kirim Pengajuan</span>
        </button>
    </form>
</div>

@endsection

@push('myscript')
<script>
    // Show file name
    function showFileName(event) {
        const file = event.target.files[0];
        const fileNameDiv = document.getElementById('file-name');

        if (file) {
            // Validasi ukuran (max 2MB)
            if (file.size > 2048000) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Terlalu Besar',
                    text: 'Ukuran file maksimal 2MB',
                    confirmButtonColor: '#0053C5'
                });
                event.target.value = '';
                return;
            }

            fileNameDiv.textContent = '📎 ' + file.name;
            fileNameDiv.classList.add('show');
        } else {
            fileNameDiv.classList.remove('show');
        }
    }

    // Form validation
    $(document).ready(function() {
        $('#form-izin').on('submit', function(e) {
            const tglDari = new Date($('[name="tgl_izin_dari"]').val());
            const tglSampai = new Date($('[name="tgl_izin_sampai"]').val());
            const keterangan = $('[name="keterangan"]').val();

            // Validasi tanggal
            if (tglSampai < tglDari) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Tanggal Tidak Valid',
                    text: 'Tanggal selesai harus lebih besar atau sama dengan tanggal mulai',
                    confirmButtonColor: '#0053C5'
                });
                return false;
            }

            // Validasi keterangan
            if (keterangan.length < 10) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Keterangan Terlalu Singkat',
                    text: 'Keterangan minimal 10 karakter',
                    confirmButtonColor: '#0053C5'
                });
                return false;
            }

            // Show loading
            const btnSubmit = $('#btn-submit');
            btnSubmit.prop('disabled', true);
            btnSubmit.html('<ion-icon name="hourglass-outline"></ion-icon> <span>Mengirim...</span>');
        });

        // Auto set tanggal sampai = tanggal dari
        $('[name="tgl_izin_dari"]').on('change', function() {
            const tglDari = $(this).val();
            if (tglDari && !$('[name="tgl_izin_sampai"]').val()) {
                $('[name="tgl_izin_sampai"]').val(tglDari);
            }
        });
    });
</script>
@endpush