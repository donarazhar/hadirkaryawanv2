@extends('karyawan.layouts.presensi')
@section('content')

@php
    use Illuminate\Support\Facades\Auth;
    $karyawan = Auth::guard('karyawan')->user();
    $hasWebAuthn = !empty($karyawan->webauthn_id);
@endphp

<style>
    :root {
        --primary:       #2563EB;
        --primary-soft:  #EFF6FF;
        --primary-mid:   #BFDBFE;
        --primary-dark:  #1D4ED8;
        --success:       #10B981;
        --success-soft:  #ECFDF5;
        --success-mid:   #6EE7B7;
        --danger:        #EF4444;
        --danger-soft:   #FEF2F2;
        --warning:       #F59E0B;
        --warning-soft:  #FFFBEB;
        --text-900:      #0F172A;
        --text-600:      #475569;
        --text-400:      #94A3B8;
        --border:        #F1F5F9;
        --border-med:    #E2E8F0;
        --surface:       #FFFFFF;
        --bg:            #F8FAFC;
        --radius-sm:     10px;
        --radius-md:     14px;
        --radius-lg:     18px;
        --radius-xl:     24px;
        --shadow-xs: 0 1px 2px rgba(0,0,0,0.05);
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.07), 0 1px 2px rgba(0,0,0,0.05);
        --shadow-md: 0 4px 12px rgba(0,0,0,0.08), 0 2px 4px rgba(0,0,0,0.04);
        --shadow-blue: 0 4px 16px rgba(37,99,235,0.28);
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        background: var(--bg);
        color: var(--text-900);
        -webkit-font-smoothing: antialiased;
    }

    /* ═══════════════════════════════════
       PAGE HEADER
    ═══════════════════════════════════ */
    .pg-header {
        background: var(--surface);
        border-bottom: 1px solid var(--border);
        padding: 14px 18px;
        display: flex;
        align-items: center;
        gap: 12px;
        position: sticky;
        top: 0;
        z-index: 50;
        box-shadow: var(--shadow-xs);
    }

    .btn-back {
        width: 36px;
        height: 36px;
        background: var(--bg);
        border: 1px solid var(--border-med);
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        flex-shrink: 0;
        transition: background 0.2s, transform 0.15s;
        -webkit-tap-highlight-color: transparent;
    }

    .btn-back:active { background: var(--border-med); transform: scale(0.95); }
    .btn-back ion-icon { font-size: 20px; color: var(--text-600); }

    .pg-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-900);
        line-height: 1.2;
    }

    .pg-sub {
        font-size: 11px;
        font-weight: 500;
        color: var(--primary);
        display: block;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        margin-top: 1px;
    }

    /* ═══════════════════════════════════
       BODY CONTAINER
    ═══════════════════════════════════ */
    .enroll-body {
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 14px;
        padding-bottom: 110px;
    }

    /* ═══════════════════════════════════
       SEGMENT TABS
    ═══════════════════════════════════ */
    .segment-wrap {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 5px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4px;
        box-shadow: var(--shadow-sm);
    }

    .seg-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 11px 10px;
        border-radius: 13px;
        border: none;
        background: transparent;
        font-family: 'Inter', sans-serif;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-400);
        cursor: pointer;
        transition: all 0.22s cubic-bezier(0.34, 1.2, 0.64, 1);
        -webkit-tap-highlight-color: transparent;
        position: relative;
    }

    .seg-btn ion-icon { font-size: 17px; }

    .seg-btn.active {
        background: var(--primary);
        color: white;
        box-shadow: var(--shadow-blue);
    }

    .seg-status-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: var(--success);
        position: absolute;
        top: 8px;
        right: 10px;
        border: 1.5px solid white;
    }

    /* ═══════════════════════════════════
       TAB PANELS
    ═══════════════════════════════════ */
    .tab-panel { display: none; flex-direction: column; gap: 14px; }
    .tab-panel.active { display: flex; }

    /* ═══════════════════════════════════
       CARD
    ═══════════════════════════════════ */
    .card {
        background: var(--surface);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    /* ═══════════════════════════════════
       STATUS HERO
    ═══════════════════════════════════ */
    .status-hero {
        padding: 28px 20px 24px;
        text-align: center;
    }

    .status-ring {
        width: 84px;
        height: 84px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }

    .status-ring.success {
        background: var(--success-soft);
        box-shadow: 0 0 0 10px rgba(16,185,129,0.07);
    }

    .status-ring.pending {
        background: var(--primary-soft);
        box-shadow: 0 0 0 10px rgba(37,99,235,0.07);
    }

    .status-ring ion-icon { font-size: 38px; }
    .status-ring.success ion-icon { color: var(--success); }
    .status-ring.pending ion-icon { color: var(--primary); }

    .status-label {
        font-size: 17px;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .status-label.success { color: var(--success); }
    .status-label.pending { color: var(--text-900); }

    .status-desc {
        font-size: 13px;
        color: var(--text-600);
        line-height: 1.6;
        max-width: 280px;
        margin: 0 auto;
    }

    /* ═══════════════════════════════════
       FACE PHOTO
    ═══════════════════════════════════ */
    .face-photo-wrap {
        padding: 0 20px 20px;
        text-align: center;
    }

    .face-photo {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid var(--success-mid);
        box-shadow: 0 0 0 6px rgba(16,185,129,0.08);
    }

    .face-meta {
        font-size: 11px;
        color: var(--text-400);
        margin-top: 8px;
        font-weight: 500;
    }

    /* ═══════════════════════════════════
       INFO GRID (STATUS BADGES)
    ═══════════════════════════════════ */
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .info-tile {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 14px 12px;
        text-align: center;
        box-shadow: var(--shadow-xs);
    }

    .info-tile-label {
        font-size: 10px;
        font-weight: 600;
        color: var(--text-400);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .info-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        border-radius: 50px;
        padding: 4px 10px;
        font-size: 11px;
        font-weight: 700;
    }

    .info-badge ion-icon { font-size: 12px; }
    .info-badge.green { background: var(--success-soft); color: var(--success); }
    .info-badge.blue  { background: var(--primary-soft); color: var(--primary); }

    /* ═══════════════════════════════════
       NOTICE
    ═══════════════════════════════════ */
    .notice {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 13px 14px;
        border-radius: var(--radius-md);
    }

    .notice.warning { background: var(--warning-soft); border: 1px solid #FDE68A; }
    .notice.info    { background: var(--primary-soft); border: 1px solid var(--primary-mid); }
    .notice.success { background: var(--success-soft); border: 1px solid var(--success-mid); }

    .notice-icon { font-size: 18px; flex-shrink: 0; margin-top: 1px; }
    .notice.warning .notice-icon { color: var(--warning); }
    .notice.info    .notice-icon { color: var(--primary); }
    .notice.success .notice-icon { color: var(--success); }

    .notice-text {
        font-size: 12px;
        line-height: 1.6;
        color: var(--text-600);
    }

    .notice-text strong { color: var(--text-900); }

    /* ═══════════════════════════════════
       STEP GUIDE
    ═══════════════════════════════════ */
    .card-header {
        padding: 14px 16px 12px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .card-header-icon {
        width: 28px;
        height: 28px;
        background: var(--primary-soft);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .card-header-icon ion-icon { font-size: 14px; color: var(--primary); }

    .card-header-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--text-900);
    }

    .step-list {
        padding: 14px 16px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .step-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .step-num {
        width: 26px;
        height: 26px;
        border-radius: 8px;
        background: var(--primary-soft);
        border: 1.5px solid var(--primary-mid);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 700;
        color: var(--primary);
        flex-shrink: 0;
    }

    .step-text {
        font-size: 12.5px;
        color: var(--text-600);
        line-height: 1.55;
        padding-top: 4px;
    }

    .step-text strong { color: var(--text-900); font-weight: 600; }

    /* ═══════════════════════════════════
       ACTION BUTTONS
    ═══════════════════════════════════ */
    .btn-primary {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        width: 100%;
        padding: 15px 20px;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: var(--radius-lg);
        font-family: 'Inter', sans-serif;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
        box-shadow: var(--shadow-blue);
        -webkit-tap-highlight-color: transparent;
        text-decoration: none;
    }

    .btn-primary ion-icon { font-size: 19px; }
    .btn-primary:active { opacity: 0.88; transform: scale(0.98); }

    .btn-danger-soft {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        width: 100%;
        padding: 14px 20px;
        background: var(--danger-soft);
        color: var(--danger);
        border: 1.5px solid #FECACA;
        border-radius: var(--radius-lg);
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: opacity 0.2s, transform 0.15s;
        -webkit-tap-highlight-color: transparent;
    }

    .btn-danger-soft ion-icon { font-size: 17px; }
    .btn-danger-soft:active { opacity: 0.85; transform: scale(0.98); }

    /* ═══════════════════════════════════
       BIOMETRIC FEATURE ITEMS
    ═══════════════════════════════════ */
    .feature-list {
        padding: 4px 16px 16px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .feature-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        background: var(--bg);
        border-radius: var(--radius-md);
        border: 1px solid var(--border);
    }

    .feature-icon-wrap {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .feature-icon-wrap.blue  { background: var(--primary-soft); }
    .feature-icon-wrap.green { background: var(--success-soft); }
    .feature-icon-wrap.warn  { background: var(--warning-soft); }

    .feature-icon-wrap ion-icon { font-size: 18px; }
    .feature-icon-wrap.blue  ion-icon { color: var(--primary); }
    .feature-icon-wrap.green ion-icon { color: var(--success); }
    .feature-icon-wrap.warn  ion-icon { color: var(--warning); }

    .feature-text strong {
        display: block;
        font-size: 12.5px;
        font-weight: 700;
        color: var(--text-900);
        margin-bottom: 2px;
    }

    .feature-text span {
        font-size: 11.5px;
        color: var(--text-600);
        line-height: 1.4;
    }

    /* ═══════════════════════════════════
       DIVIDER
    ═══════════════════════════════════ */
    .divider-line {
        height: 1px;
        background: var(--border);
        margin: 0 16px;
    }

    /* ═══════════════════════════════════
       FULLSCREEN CAMERA UI
    ═══════════════════════════════════ */
    .camera-container {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: #000;
        flex-direction: column;
    }

    .camera-container.open { display: flex; }

    #video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        inset: 0;
    }

    .face-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -54%);
        width: 220px;
        height: 290px;
        border: 2.5px solid rgba(255,255,255,0.9);
        border-radius: 50%;
        pointer-events: none;
        z-index: 10;
        box-shadow: 0 0 0 9999px rgba(0,0,0,0.45);
    }

    .face-overlay::before,
    .face-overlay::after {
        content: '';
        position: absolute;
        width: 32px;
        height: 32px;
        border-color: #60A5FA;
        border-style: solid;
    }

    .face-overlay::before {
        top: -3px; left: -3px;
        border-width: 3px 0 0 3px;
        border-radius: 50% 0 0 0;
    }

    .face-overlay::after {
        bottom: -3px; right: -3px;
        border-width: 0 3px 3px 0;
        border-radius: 0 0 50% 0;
    }

    .cam-topbar {
        position: absolute;
        top: 0; left: 0; right: 0;
        padding: 50px 20px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        z-index: 20;
        background: linear-gradient(to bottom, rgba(0,0,0,0.55) 0%, transparent 100%);
    }

    .cam-close {
        width: 40px;
        height: 40px;
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
    }

    .cam-close ion-icon { font-size: 22px; color: white; }

    .cam-title {
        font-size: 14px;
        font-weight: 700;
        color: white;
        text-shadow: 0 1px 4px rgba(0,0,0,0.4);
    }

    .cam-bottombar {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        padding: 20px 20px 50px;
        background: linear-gradient(to top, rgba(0,0,0,0.65) 0%, transparent 100%);
        z-index: 20;
        text-align: center;
    }

    .cam-hint {
        font-size: 13px;
        color: rgba(255,255,255,0.9);
        font-weight: 500;
        margin-bottom: 4px;
    }

    #countdown-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: none;
        z-index: 30;
        text-align: center;
        pointer-events: none;
    }

    .countdown-label {
        font-size: 14px;
        font-weight: 600;
        color: rgba(255,255,255,0.9);
        margin-bottom: 4px;
        text-shadow: 0 2px 8px rgba(0,0,0,0.5);
    }

    #countdown-text {
        font-size: 96px;
        font-weight: 800;
        color: white;
        text-shadow: 0 0 30px rgba(96,165,250,0.8), 0 4px 12px rgba(0,0,0,0.5);
        line-height: 1;
        display: block;
    }

    #faceCanvas { display: none; }

    .countdown-progress {
        position: absolute;
        bottom: 0; left: 0;
        height: 3px;
        background: #60A5FA;
        border-radius: 0;
        transition: width 1s linear;
        z-index: 25;
    }

    /* ═══════════════════════════════════
       ANIMATIONS
    ═══════════════════════════════════ */
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .enroll-body > * {
        animation: fadeUp 0.35s ease both;
    }
    .enroll-body > *:nth-child(1) { animation-delay: 0.04s; }
    .enroll-body > *:nth-child(2) { animation-delay: 0.09s; }
    .enroll-body > *:nth-child(3) { animation-delay: 0.14s; }
    .enroll-body > *:nth-child(4) { animation-delay: 0.19s; }
    .enroll-body > *:nth-child(5) { animation-delay: 0.24s; }
</style>

{{-- ── PAGE HEADER ── --}}
<div class="pg-header">
    <a href="{{ route('profile.edit') }}" class="btn-back">
        <ion-icon name="chevron-back-outline"></ion-icon>
    </a>
    <div>
        <div class="pg-title">Keamanan Biometrik</div>
        <span class="pg-sub">Wajah & Fingerprint</span>
    </div>
</div>

{{-- ── BODY ── --}}
<div class="enroll-body">

    {{-- ── SEGMENT TABS ── --}}
    <div class="segment-wrap">
        <button class="seg-btn active" id="tab-face" onclick="switchTab('face')">
            <ion-icon name="scan-outline"></ion-icon>
            Wajah
            @if($faceData && $faceData->status == 'active')
                <span class="seg-status-dot"></span>
            @endif
        </button>
        <button class="seg-btn" id="tab-finger" onclick="switchTab('finger')">
            <ion-icon name="finger-print-outline"></ion-icon>
            Fingerprint
            @if($hasWebAuthn)
                <span class="seg-status-dot"></span>
            @endif
        </button>
    </div>

    {{-- ══════════════════════════════════
         TAB 1: FACE ENROLLMENT
    ══════════════════════════════════ --}}
    <div class="tab-panel active" id="panel-face">

        @if($faceData && $faceData->status == 'active')
        {{-- ── ENROLLED STATE ── --}}

        <div class="card">
            <div class="status-hero">
                <div class="status-ring success">
                    <ion-icon name="checkmark-circle"></ion-icon>
                </div>
                <div class="status-label success">Wajah Terdaftar</div>
                <p class="status-desc">Data wajah Anda sudah aktif dan siap digunakan untuk presensi.</p>
            </div>

            @if($faceData->face_image)
            <div class="face-photo-wrap">
                <img src="{{ Storage::url('uploads/faces/' . $faceData->face_image) }}"
                     alt="Foto Wajah" class="face-photo">
                <div class="face-meta">
                    Terdaftar: {{ $faceData->last_updated->format('d M Y, H:i') }}
                </div>
            </div>
            @endif

            <div style="padding: 0 16px 16px;">
                <div class="notice warning">
                    <ion-icon name="lock-closed" class="notice-icon"></ion-icon>
                    <div class="notice-text">
                        <strong>Data dikunci demi keamanan.</strong> Untuk memperbarui wajah, hubungi <strong>Admin</strong>.
                    </div>
                </div>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-tile">
                <div class="info-tile-label">Status</div>
                <div class="info-badge green">
                    <ion-icon name="checkmark-circle"></ion-icon> Aktif
                </div>
            </div>
            <div class="info-tile">
                <div class="info-tile-label">Keamanan</div>
                <div class="info-badge blue">
                    <ion-icon name="shield-checkmark"></ion-icon> Terlindungi
                </div>
            </div>
        </div>

        @else
        {{-- ── NOT ENROLLED STATE ── --}}

        <div class="card">
            <div class="status-hero">
                <div class="status-ring pending">
                    <ion-icon name="scan-outline"></ion-icon>
                </div>
                <div class="status-label pending">Belum Terdaftar</div>
                <p class="status-desc">Daftarkan wajah Anda untuk menggunakan fitur presensi verifikasi wajah.</p>
            </div>
        </div>

        {{-- Step Guide --}}
        <div class="card">
            <div class="card-header">
                <div class="card-header-icon">
                    <ion-icon name="list-outline"></ion-icon>
                </div>
                <div class="card-header-title">Panduan Pendaftaran</div>
            </div>
            <div class="step-list">
                <div class="step-item">
                    <div class="step-num">1</div>
                    <div class="step-text"><strong>Posisi wajah</strong> — Hadapkan wajah ke kamera dan masuk ke bingkai oval.</div>
                </div>
                <div class="step-item">
                    <div class="step-num">2</div>
                    <div class="step-text"><strong>Pencahayaan cukup</strong> — Pastikan ruangan terang agar wajah terlihat jelas.</div>
                </div>
                <div class="step-item">
                    <div class="step-num">3</div>
                    <div class="step-text"><strong>Lepas aksesori</strong> — Lepas kacamata hitam atau masker.</div>
                </div>
                <div class="step-item">
                    <div class="step-num">4</div>
                    <div class="step-text"><strong>Tetap diam</strong> — Tatap kamera selama hitungan mundur berlangsung.</div>
                </div>
            </div>
        </div>

        <div class="notice info">
            <ion-icon name="information-circle" class="notice-icon"></ion-icon>
            <div class="notice-text">Data wajah hanya digunakan untuk keperluan presensi dan <strong>tidak dibagikan</strong> ke pihak lain.</div>
        </div>

        <button class="btn-primary" id="startEnrollment">
            <ion-icon name="camera"></ion-icon>
            Mulai Pendaftaran Wajah
        </button>

        @endif

    </div>{{-- /panel-face --}}

    {{-- ══════════════════════════════════
         TAB 2: FINGERPRINT / WEBAUTHN
    ══════════════════════════════════ --}}
    <div class="tab-panel" id="panel-finger">

        @if($hasWebAuthn)
        {{-- ── FINGERPRINT REGISTERED ── --}}

        <div class="card">
            <div class="status-hero">
                <div class="status-ring success">
                    <ion-icon name="finger-print"></ion-icon>
                </div>
                <div class="status-label success">Fingerprint Terdaftar</div>
                <p class="status-desc">Perangkat ini sudah terdaftar. Gunakan sensor sidik jari / Face ID untuk absensi yang lebih cepat.</p>
            </div>
        </div>

        <div class="notice success">
            <ion-icon name="checkmark-circle" class="notice-icon"></ion-icon>
            <div class="notice-text">
                Autentikasi biometrik aktif. Saat absensi, Anda dapat <strong>langsung menggunakan fingerprint</strong> tanpa perlu foto wajah.
            </div>
        </div>

        <div class="info-grid">
            <div class="info-tile">
                <div class="info-tile-label">Status</div>
                <div class="info-badge green">
                    <ion-icon name="checkmark-circle"></ion-icon> Aktif
                </div>
            </div>
            <div class="info-tile">
                <div class="info-tile-label">Metode</div>
                <div class="info-badge blue">
                    <ion-icon name="phone-portrait"></ion-icon> Perangkat
                </div>
            </div>
        </div>

        <form action="{{ route('biometric.delete') }}" method="POST" id="deleteFingerForm">
            @csrf
            <button type="button" class="btn-danger-soft" onclick="confirmDeleteFinger()">
                <ion-icon name="trash-outline"></ion-icon>
                Hapus Pendaftaran Fingerprint
            </button>
        </form>

        @else
        {{-- ── FINGERPRINT NOT REGISTERED ── --}}

        <div class="card">
            <div class="status-hero">
                <div class="status-ring pending">
                    <ion-icon name="finger-print-outline"></ion-icon>
                </div>
                <div class="status-label pending">Belum Terdaftar</div>
                <p class="status-desc">Gunakan sidik jari atau Face ID bawaan HP untuk absensi lebih cepat dan mudah.</p>
            </div>
        </div>

        {{-- Feature highlights --}}
        <div class="card">
            <div class="card-header">
                <div class="card-header-icon">
                    <ion-icon name="star-outline"></ion-icon>
                </div>
                <div class="card-header-title">Keunggulan Biometrik HP</div>
            </div>
            <div class="feature-list">
                <div class="feature-item">
                    <div class="feature-icon-wrap blue">
                        <ion-icon name="flash"></ion-icon>
                    </div>
                    <div class="feature-text">
                        <strong>Absen Lebih Cepat</strong>
                        <span>Cukup sentuh sensor, tidak perlu foto wajah</span>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon-wrap green">
                        <ion-icon name="shield-checkmark"></ion-icon>
                    </div>
                    <div class="feature-text">
                        <strong>Lebih Aman</strong>
                        <span>Menggunakan keamanan biometrik bawaan perangkat</span>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon-wrap warn">
                        <ion-icon name="phone-portrait"></ion-icon>
                    </div>
                    <div class="feature-text">
                        <strong>Hanya di Perangkat Ini</strong>
                        <span>Pendaftaran berlaku untuk HP yang digunakan saat ini</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="notice info">
            <ion-icon name="information-circle" class="notice-icon"></ion-icon>
            <div class="notice-text">Pastikan HP Anda mendukung <strong>fingerprint</strong> atau <strong>Face ID</strong> dan sudah diaktifkan di pengaturan.</div>
        </div>

        <button class="btn-primary" id="btnEnrollFinger">
            <ion-icon name="finger-print"></ion-icon>
            Daftarkan Fingerprint Sekarang
        </button>

        @endif

    </div>{{-- /panel-finger --}}

</div>{{-- /enroll-body --}}

{{-- ══ FULLSCREEN CAMERA ══ --}}
<div class="camera-container" id="cameraContainer">
    <div class="cam-topbar">
        <button type="button" class="cam-close" onclick="cancelCamera()">
            <ion-icon name="close-outline"></ion-icon>
        </button>
        <div class="cam-title">Posisikan Wajah Anda</div>
        <div style="width:40px;"></div>
    </div>

    <video id="video" autoplay playsinline muted></video>
    <div class="face-overlay"></div>

    <div id="countdown-overlay">
        <div class="countdown-label">Bersiaplah...</div>
        <span id="countdown-text">10</span>
    </div>

    <div class="countdown-progress" id="countdownBar" style="width:100%;"></div>

    <div class="cam-bottombar">
        <div class="cam-hint" id="camHint">Posisikan wajah di dalam bingkai oval</div>
    </div>
</div>

<canvas id="faceCanvas"></canvas>

@endsection

@push('myscript')
<script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.js"></script>
<script>
// ══════════════════════════════════════════
// TAB SWITCHING
// ══════════════════════════════════════════
function switchTab(tab) {
    document.querySelectorAll('.seg-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.add('active');
    document.getElementById('panel-' + tab).classList.add('active');
}

// ══════════════════════════════════════════
// FACE ENROLLMENT
// ══════════════════════════════════════════
let video, canvas;
let modelsLoaded = false;
let countdownInterval;

async function loadModels() {
    try {
        const MODEL_URL = 'https://cdn.jsdelivr.net/npm/@vladmandic/face-api/model';
        await Promise.all([
            faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
            faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
            faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
        ]);
        modelsLoaded = true;
    } catch (err) {
        console.error('Model load error:', err);
        Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal memuat model face recognition', confirmButtonColor: '#2563EB' });
    }
}

document.getElementById('startEnrollment')?.addEventListener('click', async function () {
    if (!modelsLoaded) {
        Swal.fire({ title: 'Memuat Model...', text: 'Harap tunggu sebentar', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        await loadModels();
        Swal.close();
    }
    document.getElementById('cameraContainer').classList.add('open');
    startCamera();
});

async function startCamera() {
    try {
        video  = document.getElementById('video');
        canvas = document.getElementById('faceCanvas');
        const stream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: 'user', width: { ideal: 1280 }, height: { ideal: 720 } }
        });
        video.srcObject = stream;
        video.addEventListener('loadedmetadata', () => {
            canvas.width  = video.videoWidth;
            canvas.height = video.videoHeight;
            startCountdown();
        });
    } catch (err) {
        cancelCamera();
        Swal.fire({ icon: 'error', title: 'Kamera Error', text: 'Tidak dapat mengakses kamera. Pastikan izin kamera diaktifkan.', confirmButtonColor: '#2563EB' });
    }
}

function cancelCamera() {
    document.getElementById('cameraContainer').classList.remove('open');
    if (video?.srcObject) video.srcObject.getTracks().forEach(t => t.stop());
    if (countdownInterval) clearInterval(countdownInterval);
    document.getElementById('countdown-overlay').style.display = 'none';
    document.getElementById('countdownBar').style.width = '100%';
    document.getElementById('camHint').textContent = 'Posisikan wajah di dalam bingkai oval';
}

function startCountdown() {
    let total = 10, count = total;
    const overlay = document.getElementById('countdown-overlay');
    const text    = document.getElementById('countdown-text');
    const bar     = document.getElementById('countdownBar');
    const hint    = document.getElementById('camHint');

    overlay.style.display = 'block';
    text.textContent = count;
    hint.textContent = 'Tetap diam dan tatap kamera...';

    countdownInterval = setInterval(() => {
        count--;
        bar.style.width = ((count / total) * 100) + '%';
        if (count > 0) {
            text.textContent = count;
        } else {
            clearInterval(countdownInterval);
            overlay.style.display = 'none';
            hint.textContent = 'Memproses wajah...';
            detectAndCapture();
        }
    }, 1000);
}

async function detectAndCapture() {
    if (!modelsLoaded) return;
    try {
        const detections = await faceapi
            .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
            .withFaceLandmarks()
            .withFaceDescriptor();

        if (detections && detections.detection.score > 0.5) {
            await captureFaceData(detections.descriptor);
        } else if (detections) {
            Swal.fire({ icon: 'warning', title: 'Wajah Tidak Jelas', text: 'Coba posisikan wajah lebih dekat ke kamera.', confirmButtonColor: '#2563EB' })
                .then(() => detectAndCapture());
        } else {
            setTimeout(() => detectAndCapture(), 1000);
        }
    } catch (err) {
        cancelCamera();
        Swal.fire({ icon: 'error', title: 'Gagal Deteksi', text: 'Tidak dapat mendeteksi wajah. Silakan coba lagi.', confirmButtonColor: '#2563EB' });
    }
}

async function captureFaceData(descriptor) {
    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    const imageData = canvas.toDataURL('image/png');
    if (video?.srcObject) video.srcObject.getTracks().forEach(t => t.stop());
    cancelCamera();

    Swal.fire({ title: 'Menyimpan...', text: 'Sedang menyimpan data wajah Anda', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

    try {
        const response = await fetch('/face/enrollment/store', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({
                face_descriptor: JSON.stringify(Array.from(descriptor)),
                face_image: imageData
            })
        });
        const result = await response.json();
        if (result.success) {
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: result.message, confirmButtonColor: '#2563EB' })
                .then(() => window.location.reload());
        } else {
            throw new Error(result.message);
        }
    } catch (err) {
        Swal.fire({ icon: 'error', title: 'Gagal Menyimpan', text: err.message || 'Terjadi kesalahan. Silakan coba lagi.', confirmButtonColor: '#2563EB' });
    }
}

// Load face models
loadModels();

// ══════════════════════════════════════════
// FINGERPRINT / WEBAUTHN ENROLLMENT
// ══════════════════════════════════════════
function bufferToBase64url(buffer) {
    const bytes = new Uint8Array(buffer);
    let str = '';
    for (let charCode of bytes) { str += String.fromCharCode(charCode); }
    return btoa(str).replace(/\+/g, '-').replace(/\//g, '_').replace(/=/g, '');
}

document.getElementById('btnEnrollFinger')?.addEventListener('click', async function () {
    if (!window.PublicKeyCredential) {
        Swal.fire({ icon: 'error', title: 'Tidak Didukung', text: 'Perangkat atau browser Anda tidak mendukung fitur biometrik/WebAuthn.', confirmButtonColor: '#2563EB' });
        return;
    }

    try {
        Swal.fire({ title: 'Menunggu Biometrik...', text: 'Ikuti instruksi sensor sidik jari di HP Anda', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        const challenge = new Uint8Array(32);
        window.crypto.getRandomValues(challenge);
        const userId = new Uint8Array(16);
        window.crypto.getRandomValues(userId);

        const publicKey = {
            challenge,
            rp: { name: "Hadir Karyawan", id: window.location.hostname },
            user: {
                id: userId,
                name: "{{ $karyawan->nik }}",
                displayName: "{{ $karyawan->nama_lengkap }}"
            },
            pubKeyCredParams: [
                { type: "public-key", alg: -7 },
                { type: "public-key", alg: -257 }
            ],
            authenticatorSelection: {
                authenticatorAttachment: "platform",
                userVerification: "required"
            },
            timeout: 60000,
            attestation: "none"
        };

        const credential = await navigator.credentials.create({ publicKey });
        const rawId = bufferToBase64url(credential.rawId);

        $.ajax({
            type: 'POST',
            url: '{{ route('biometric.store') }}',
            data: { _token: '{{ csrf_token() }}', rawId },
            success: function(res) {
                if (res.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: res.message, confirmButtonColor: '#10B981' })
                        .then(() => location.reload());
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: res.message, confirmButtonColor: '#EF4444' });
                }
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'Kesalahan Server', text: 'Gagal menyimpan data fingerprint.' });
            }
        });

    } catch (err) {
        console.error('WebAuthn Error:', err);
        if (err.name === 'NotAllowedError') {
            Swal.fire({ icon: 'warning', title: 'Dibatalkan', text: 'Pendaftaran dibatalkan atau ditolak oleh pengguna.', confirmButtonColor: '#2563EB' });
        } else {
            Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat menginisialisasi sensor biometrik perangkat Anda.', confirmButtonColor: '#EF4444' });
        }
    }
});

function confirmDeleteFinger() {
    Swal.fire({
        icon: 'warning',
        title: 'Hapus Fingerprint?',
        text: 'Anda harus mendaftar ulang untuk menggunakan fitur ini.',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#64748B'
    }).then(result => {
        if (result.isConfirmed) {
            document.getElementById('deleteFingerForm').submit();
        }
    });
}
</script>
@endpush