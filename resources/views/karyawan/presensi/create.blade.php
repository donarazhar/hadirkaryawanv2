@extends('karyawan.layouts.presensi')

@section('content')

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
        --text-900:      #0F172A;
        --text-600:      #475569;
        --text-400:      #94A3B8;
        --border:        #F1F5F9;
        --border-med:    #E2E8F0;
        --surface:       #FFFFFF;
        --bg:            #F8FAFC;
        --shadow-sm:     0 1px 3px rgba(0,0,0,0.07), 0 1px 2px rgba(0,0,0,0.05);
        --shadow-md:     0 4px 12px rgba(0,0,0,0.08);
        --shadow-blue:   0 4px 16px rgba(37,99,235,0.28);
        --shadow-green:  0 4px 16px rgba(16,185,129,0.28);
        --radius-md:     14px;
        --radius-lg:     18px;
        --radius-xl:     24px;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        background: var(--bg);
        color: var(--text-900);
        -webkit-font-smoothing: antialiased;
        min-height: 100vh;
    }

    /* ═══════════════════════════
       PAGE HEADER
    ═══════════════════════════ */
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
        box-shadow: var(--shadow-sm);
    }

    .btn-back {
        width: 36px; height: 36px;
        background: var(--bg);
        border: 1px solid var(--border-med);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        text-decoration: none;
        flex-shrink: 0;
        transition: background 0.2s, transform 0.15s;
        -webkit-tap-highlight-color: transparent;
    }
    .btn-back:active { background: var(--border-med); transform: scale(0.95); }
    .btn-back ion-icon { font-size: 20px; color: var(--text-600); }

    .pg-meta { flex: 1; min-width: 0; }
    .pg-title { font-size: 16px; font-weight: 700; color: var(--text-900); }
    .pg-sub   { font-size: 11px; font-weight: 500; color: var(--text-400); margin-top: 1px; }

    .pg-badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 5px 10px;
        border-radius: 50px;
        font-size: 11px; font-weight: 700;
        flex-shrink: 0;
    }
    .pg-badge.done    { background: var(--success-soft); color: var(--success); }
    .pg-badge.pending { background: #FFF7ED; color: #EA580C; }
    .pg-badge ion-icon { font-size: 12px; }

    /* ═══════════════════════════
       MAIN BODY
    ═══════════════════════════ */
    .page-body {
        padding: 14px 16px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        padding-bottom: 120px;
    }

    /* ═══════════════════════════
       INFO CARD (date + shift)
    ═══════════════════════════ */
    .info-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .info-icon-wrap {
        width: 46px; height: 46px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .info-icon-wrap.blue  { background: var(--primary-soft); }
    .info-icon-wrap.green { background: var(--success-soft); }
    .info-icon-wrap ion-icon { font-size: 22px; }
    .info-icon-wrap.blue  ion-icon { color: var(--primary); }
    .info-icon-wrap.green ion-icon { color: var(--success); }

    .info-card-label {
        font-size: 10px; font-weight: 600;
        color: var(--text-400);
        text-transform: uppercase; letter-spacing: 0.5px;
        margin-bottom: 3px;
    }
    .info-card-value {
        font-size: 14px; font-weight: 700;
        color: var(--text-900); line-height: 1.3;
    }
    .info-card-sub {
        font-size: 11.5px; color: var(--text-600);
        margin-top: 1px;
    }

    /* ═══════════════════════════
       CAMERA CARD
    ═══════════════════════════ */
    .cam-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        position: relative;
    }

    .cam-card-header {
        padding: 12px 16px 10px;
        border-bottom: 1px solid var(--border);
        display: flex; align-items: center; justify-content: space-between;
    }

    .cam-card-title {
        display: flex; align-items: center; gap: 8px;
        font-size: 13px; font-weight: 700; color: var(--text-900);
    }
    .cam-card-title ion-icon { font-size: 16px; color: var(--primary); }

    /* Auto-scan status pill */
    #auto-scan-pill {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 4px 10px;
        border-radius: 50px;
        font-size: 10.5px; font-weight: 700;
        background: var(--success-soft);
        color: var(--success);
        border: 1px solid var(--success-mid);
        transition: all 0.3s;
    }
    #auto-scan-pill .pill-dot {
        width: 7px; height: 7px; border-radius: 50%;
        background: var(--success);
        animation: pulseDot 1.5s ease-in-out infinite;
    }
    @keyframes pulseDot {
        0%,100% { opacity:1; transform:scale(1); }
        50%      { opacity:0.3; transform:scale(0.65); }
    }
    #auto-scan-pill.mismatch {
        background: var(--danger-soft); color: var(--danger);
        border-color: #FECACA;
    }
    #auto-scan-pill.mismatch .pill-dot { background: var(--danger); animation:none; }

    /* Webcam area */
    .webcam-wrap {
        position: relative;
        width: 100%;
        aspect-ratio: 3/4;
        background: #0F172A;
        overflow: hidden;
    }

    .webcam-capture,
    .webcam-capture video {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
        border-radius: 0 !important;
    }

    /* Dark vignette */
    .cam-vignette {
        position: absolute; inset: 0; z-index: 2; pointer-events: none;
        background:
            linear-gradient(to bottom,
                rgba(0,0,0,0.4) 0%, transparent 25%,
                transparent 65%, rgba(0,0,0,0.5) 100%);
    }

    /* Oval guide */
    .face-guide {
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -55%);
        width: 160px; height: 215px;
        border: 2px solid rgba(255,255,255,0.75);
        border-radius: 50%;
        z-index: 5; pointer-events: none;
        box-shadow: 0 0 0 9999px rgba(0,0,0,0.25);
    }
    .face-guide::before {
        content: '';
        position: absolute; top: -2px; left: -2px;
        width: 28px; height: 28px;
        border-top: 2.5px solid #60A5FA;
        border-left: 2.5px solid #60A5FA;
        border-radius: 50% 0 0 0;
    }
    .face-guide::after {
        content: '';
        position: absolute; bottom: -2px; right: -2px;
        width: 28px; height: 28px;
        border-bottom: 2.5px solid #60A5FA;
        border-right: 2.5px solid #60A5FA;
        border-radius: 0 0 50% 0;
    }

    /* Cam hint bar */
    .cam-hint-bar {
        position: absolute; bottom: 0; left: 0; right: 0;
        z-index: 6; padding: 10px 14px 12px;
        background: linear-gradient(to top, rgba(0,0,0,0.6) 0%, transparent 100%);
        text-align: center;
    }
    .cam-hint-text {
        font-size: 12px; font-weight: 600; color: rgba(255,255,255,0.9);
    }

    /* ═══════════════════════════
       MAP CARD
    ═══════════════════════════ */
    .map-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .map-card-header {
        padding: 11px 14px;
        border-bottom: 1px solid var(--border);
        display: flex; align-items: center; justify-content: space-between;
    }
    .map-card-title {
        display: flex; align-items: center; gap: 7px;
        font-size: 12.5px; font-weight: 700; color: var(--text-900);
    }
    .map-card-title ion-icon { font-size: 15px; color: var(--primary); }

    .radius-badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 9px;
        background: var(--primary-soft);
        color: var(--primary);
        border-radius: 50px;
        font-size: 10.5px; font-weight: 700;
    }

    #map { width: 100%; height: 130px; }

    /* ═══════════════════════════
       SHIFT SELECTOR
    ═══════════════════════════ */
    .shift-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .shift-card-header {
        padding: 11px 14px;
        border-bottom: 1px solid var(--border);
        display: flex; align-items: center; gap: 7px;
        font-size: 12.5px; font-weight: 700; color: var(--text-900);
    }
    .shift-card-header ion-icon { font-size: 15px; color: var(--primary); }

    .shift-select-wrap {
        position: relative; padding: 12px;
    }
    .shift-select-wrap select {
        width: 100%;
        appearance: none; -webkit-appearance: none;
        padding: 11px 38px 11px 14px;
        background: var(--bg);
        border: 1.5px solid var(--border-med);
        border-radius: 12px;
        color: var(--text-900);
        font-family: 'Inter', sans-serif;
        font-size: 13px; font-weight: 600;
        outline: none;
        -webkit-tap-highlight-color: transparent;
    }
    .shift-select-wrap ion-icon {
        position: absolute; right: 24px; top: 50%;
        transform: translateY(-50%);
        color: var(--text-400); font-size: 16px;
        pointer-events: none;
    }

    /* ═══════════════════════════
       ACTION BUTTONS
    ═══════════════════════════ */
    .action-section {
        display: flex; flex-direction: column; gap: 10px;
    }

    /* Primary — Face Absen (auto handled, shown as status) */
    .btn-absen-face {
        display: flex; align-items: center; justify-content: space-between;
        width: 100%;
        padding: 16px 18px;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: var(--radius-lg);
        font-family: 'Inter', sans-serif;
        font-size: 15px; font-weight: 700;
        cursor: pointer;
        box-shadow: var(--shadow-blue);
        transition: opacity 0.2s, transform 0.15s;
        -webkit-tap-highlight-color: transparent;
    }
    .btn-absen-face:active { opacity: 0.88; transform: scale(0.98); }
    .btn-absen-face:disabled { opacity: 0.5; cursor: not-allowed; }

    .btn-absen-face-left {
        display: flex; align-items: center; gap: 10px;
    }
    .btn-absen-face-left ion-icon { font-size: 22px; }
    .btn-absen-face-text { text-align: left; }
    .btn-absen-face-text .btn-label { font-size: 15px; font-weight: 700; display: block; }
    .btn-absen-face-text .btn-sublabel { font-size: 10.5px; font-weight: 500; opacity: 0.82; display: block; margin-top: 1px; }
    .btn-absen-face ion-icon.arrow-icon { font-size: 20px; opacity: 0.7; }

    /* Fingerprint button */
    .btn-fingerprint {
        display: flex; align-items: center; justify-content: center;
        gap: 10px;
        width: 100%;
        padding: 15px 18px;
        background: var(--surface);
        color: var(--text-900);
        border: 1.5px solid var(--border-med);
        border-radius: var(--radius-lg);
        font-family: 'Inter', sans-serif;
        font-size: 14px; font-weight: 700;
        cursor: pointer;
        box-shadow: var(--shadow-sm);
        transition: border-color 0.2s, background 0.2s, transform 0.15s;
        -webkit-tap-highlight-color: transparent;
    }
    .btn-fingerprint:active { background: var(--bg); transform: scale(0.98); }
    .btn-fingerprint ion-icon { font-size: 20px; color: var(--success); }
    .btn-fingerprint-text { text-align: left; flex: 1; }
    .btn-fingerprint-text .fp-label { font-size: 14px; font-weight: 700; display: block; color: var(--text-900); }
    .btn-fingerprint-text .fp-sub { font-size: 10.5px; font-weight: 500; color: var(--text-400); display: block; margin-top: 1px; }

    /* Divider with text */
    .or-divider {
        display: flex; align-items: center; gap: 10px;
    }
    .or-divider::before,
    .or-divider::after {
        content: ''; flex: 1;
        height: 1px; background: var(--border-med);
    }
    .or-divider span {
        font-size: 10.5px; font-weight: 600;
        color: var(--text-400);
        text-transform: uppercase; letter-spacing: 0.5px;
        flex-shrink: 0;
    }

    /* ═══════════════════════════
       LOADING OVERLAY
    ═══════════════════════════ */
    .loading-overlay {
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.55);
        display: none; align-items: center; justify-content: center;
        z-index: 9999;
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
    }
    .loading-overlay.show { display: flex; }

    .loading-card {
        background: white;
        border-radius: 20px;
        padding: 28px 36px;
        text-align: center;
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        width: 200px;
    }

    .loading-ring {
        width: 48px; height: 48px;
        border: 4px solid #EFF6FF;
        border-top: 4px solid var(--primary);
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
        margin: 0 auto 14px;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    .loading-text { font-size: 14px; font-weight: 700; color: var(--text-900); }
    .loading-sub  { font-size: 11.5px; color: var(--text-400); margin-top: 4px; }

    /* ═══════════════════════════
       ANIMATIONS
    ═══════════════════════════ */
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .page-body > * { animation: fadeUp 0.3s ease both; }
    .page-body > *:nth-child(1) { animation-delay: 0.04s; }
    .page-body > *:nth-child(2) { animation-delay: 0.08s; }
    .page-body > *:nth-child(3) { animation-delay: 0.12s; }
    .page-body > *:nth-child(4) { animation-delay: 0.16s; }
    .page-body > *:nth-child(5) { animation-delay: 0.20s; }
    .page-body > *:nth-child(6) { animation-delay: 0.24s; }

    @supports (padding: max(0px)) {
        .page-body { padding-bottom: max(120px, calc(env(safe-area-inset-bottom) + 120px)); }
    }
</style>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

{{-- ── PAGE HEADER ── --}}
<div class="pg-header">
    <a href="{{ route('dashboard') }}" class="btn-back">
        <ion-icon name="chevron-back-outline"></ion-icon>
    </a>
    <div class="pg-meta">
        <div class="pg-title">{{ $cek > 0 ? 'Absen Pulang' : 'Absen Masuk' }}</div>
        <div class="pg-sub">{{ $namahari }}, {{ \Carbon\Carbon::parse($hariini)->isoFormat('D MMM Y') }}</div>
    </div>
    @if($cek > 0)
        <span class="pg-badge done">
            <ion-icon name="checkmark-circle"></ion-icon> Sudah Absen
        </span>
    @else
        <span class="pg-badge pending">
            <ion-icon name="time"></ion-icon> Belum Absen
        </span>
    @endif
</div>

{{-- ── HIDDEN INPUTS ── --}}
<input type="hidden" id="lokasi">
@if(isset($is_multi_shift) && $is_multi_shift)
    <input type="hidden" id="shift_ke_val"         value="{{ $shift_ke }}">
    <input type="hidden" id="shift_nama_val"        value="{{ $current_shift->nama_shift }}">
    <input type="hidden" id="shift_jam_masuk_val"   value="{{ $current_shift->jam_masuk }}">
    <input type="hidden" id="shift_jam_pulang_val"  value="{{ $current_shift->jam_pulang }}">
@else
    <input type="hidden" id="shift_ke_val"         value="">
    <input type="hidden" id="shift_nama_val"        value="">
    <input type="hidden" id="shift_jam_masuk_val"   value="">
    <input type="hidden" id="shift_jam_pulang_val"  value="">
@endif

{{-- ── MAIN BODY ── --}}
<div class="page-body">

    {{-- ── INFO ROW: Tanggal + Shift ── --}}
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
        <div class="info-card">
            <div class="info-icon-wrap blue">
                <ion-icon name="calendar-outline"></ion-icon>
            </div>
            <div>
                <div class="info-card-label">Tanggal</div>
                <div class="info-card-value">{{ \Carbon\Carbon::parse($hariini)->isoFormat('D MMM') }}</div>
                <div class="info-card-sub">{{ $namahari }}</div>
            </div>
        </div>
        <div class="info-card">
            <div class="info-icon-wrap {{ $cek > 0 ? 'green' : 'blue' }}">
                <ion-icon name="{{ $cek > 0 ? 'log-out-outline' : 'log-in-outline' }}"></ion-icon>
            </div>
            <div>
                <div class="info-card-label">Jam Kerja</div>
                <div class="info-card-value">
                    @if(isset($is_multi_shift) && $is_multi_shift)
                        {{ date('H:i', strtotime($current_shift->jam_masuk)) }}
                    @else
                        {{ date('H:i', strtotime($jamkerja->jam_masuk)) }}
                    @endif
                </div>
                <div class="info-card-sub">
                    @if(isset($is_multi_shift) && $is_multi_shift)
                        s/d {{ date('H:i', strtotime($current_shift->jam_pulang)) }}
                    @else
                        s/d {{ date('H:i', strtotime($jamkerja->jam_pulang)) }}
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ── SHIFT SELECTOR (multi-shift only) ── --}}
    @if(isset($is_multi_shift) && $is_multi_shift)
    <div class="shift-card">
        <div class="shift-card-header">
            <ion-icon name="swap-horizontal-outline"></ion-icon>
            Pilih Shift
        </div>
        <form action="{{ route('presensi.create') }}" method="GET" id="shift-form">
            <div class="shift-select-wrap">
                <select name="shift_ke" id="shift_ke" onchange="document.getElementById('shift-form').submit()">
                    @foreach($shifts_available as $s)
                        <option value="{{ $s->shift_ke }}" {{ $shift_ke == $s->shift_ke ? 'selected' : '' }}>
                            Shift {{ $s->shift_ke }} – {{ $s->nama_shift }}
                            ({{ date('H:i', strtotime($s->jam_masuk)) }}–{{ date('H:i', strtotime($s->jam_pulang)) }})
                        </option>
                    @endforeach
                </select>
                <ion-icon name="chevron-down-outline"></ion-icon>
            </div>
        </form>
    </div>
    @endif

    {{-- ── CAMERA CARD ── --}}
    <div class="cam-card">
        <div class="cam-card-header">
            <div class="cam-card-title">
                <ion-icon name="scan-outline"></ion-icon>
                Verifikasi Wajah — Auto Scan
            </div>
            <div id="auto-scan-pill">
                <div class="pill-dot"></div>
                <span id="pill-label">Scanning…</span>
            </div>
        </div>

        <div class="webcam-wrap">
            <div class="webcam-capture"></div>
            <div class="cam-vignette"></div>
            <div class="face-guide"></div>
            <div class="cam-hint-bar">
                <div class="cam-hint-text" id="cam-hint-text">Arahkan wajah ke bingkai oval</div>
            </div>
        </div>
    </div>

    {{-- ── MAP CARD ── --}}
    <div class="map-card">
        <div class="map-card-header">
            <div class="map-card-title">
                <ion-icon name="location-outline"></ion-icon>
                Lokasi Saat Ini
            </div>
            <div class="radius-badge">
                <ion-icon name="radio-button-on"></ion-icon>
                Radius {{ $lok_kantor->radius_cabang }}m
            </div>
        </div>
        <div id="map"></div>
    </div>

    {{-- ── ACTION BUTTONS ── --}}
    <div class="action-section">

        {{-- Face auto-absen button (manual trigger) --}}
        <button class="btn-absen-face" id="takeabsen" {{ $cek > 0 ? 'disabled' : '' }}>
            <div class="btn-absen-face-left">
                <ion-icon name="{{ $cek > 0 ? 'checkmark-circle' : 'camera-outline' }}"></ion-icon>
                <div class="btn-absen-face-text">
                    <span class="btn-label">{{ $cek > 0 ? 'Absen Pulang (Face)' : 'Absen Masuk (Face)' }}</span>
                    <span class="btn-sublabel">{{ $cek > 0 ? 'Verifikasi wajah untuk pulang' : 'Otomatis saat wajah cocok' }}</span>
                </div>
            </div>
            <ion-icon name="chevron-forward-outline" class="arrow-icon"></ion-icon>
        </button>

        @if(Auth::guard('karyawan')->user()->webauthn_id)
        <div class="or-divider"><span>atau</span></div>

        {{-- Fingerprint button ── --}}
        <button class="btn-fingerprint" id="btnWebAuthn">
            <ion-icon name="finger-print-outline"></ion-icon>
            <div class="btn-fingerprint-text">
                <span class="fp-label">Absen via Fingerprint</span>
                <span class="fp-sub">Gunakan sensor sidik jari perangkat Anda</span>
            </div>
            <ion-icon name="chevron-forward-outline" style="font-size:16px; color:var(--text-400);"></ion-icon>
        </button>
        @endif

    </div>

</div>{{-- /page-body --}}

{{-- ── LOADING OVERLAY ── --}}
<div class="loading-overlay" id="loading-overlay">
    <div class="loading-card">
        <div class="loading-ring"></div>
        <div class="loading-text">Memproses…</div>
        <div class="loading-sub">Harap tunggu</div>
    </div>
</div>

@endsection

@push('myscript')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.js"></script>

<script>
    var map, marker, circle;
    var webcamReady = false;
    var modelsLoaded = false;
    var cachedReferenceDescriptor = null;
    var autoScanInterval = null;
    var isProcessing = false;

    /* ══════════════════════════════════
       WEBCAM INIT
    ══════════════════════════════════ */
    if (typeof Webcam !== 'undefined') {
        Webcam.set({
            width: 640, height: 480,
            image_format: 'jpeg', jpeg_quality: 90,
            flip_horiz: true,
            constraints: { video: true, facingMode: 'user' }
        });
        Webcam.attach('.webcam-capture');
        Webcam.on('live', function() { webcamReady = true; });
        Webcam.on('error', function() {
            Swal.fire({ icon:'error', title:'Kamera Error', text:'Tidak dapat mengakses kamera. Pastikan izin kamera diaktifkan.', confirmButtonColor:'#2563EB' });
        });
    }

    /* ══════════════════════════════════
       GEOLOCATION + MAP
    ══════════════════════════════════ */
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(successCallback, errorCallback, {
            enableHighAccuracy: true, timeout: 10000, maximumAge: 0
        });
    } else {
        Swal.fire({ icon:'error', title:'GPS Tidak Didukung', text:'Browser Anda tidak mendukung GPS.', confirmButtonColor:'#2563EB' });
    }

    function successCallback(position) {
        var lat = position.coords.latitude;
        var lng = position.coords.longitude;
        document.getElementById('lokasi').value = lat + ',' + lng;

        try {
            if (!map) {
                map = L.map('map', { zoomControl: false, attributionControl: false }).setView([lat, lng], 17);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);

                var lok    = "{{ $lok_kantor->lokasi_cabang }}".split(',');
                var latOff = parseFloat(lok[0]);
                var lngOff = parseFloat(lok[1]);
                var radius = {{ $lok_kantor->radius_cabang }};

                circle = L.circle([latOff, lngOff], {
                    color: '#2563EB', fillColor: '#2563EB', fillOpacity: 0.10,
                    radius: radius, weight: 2, dashArray: '5,5'
                }).addTo(map);

                var offIcon = L.divIcon({
                    className: '',
                    html: '<div style="background:#2563EB;width:32px;height:32px;border-radius:50%;border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.3);display:flex;align-items:center;justify-content:center;"><ion-icon name="business" style="color:white;font-size:15px;"></ion-icon></div>',
                    iconSize: [32,32], iconAnchor: [16,16]
                });
                L.marker([latOff, lngOff], { icon: offIcon }).addTo(map)
                 .bindPopup('<strong style="color:#2563EB;">Kantor</strong><br><small>Radius: ' + radius + 'm</small>');
            }

            var userIcon = L.divIcon({
                className: '',
                html: '<div style="background:#10B981;width:32px;height:32px;border-radius:50%;border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.3);display:flex;align-items:center;justify-content:center;"><ion-icon name="person" style="color:white;font-size:15px;"></ion-icon></div>',
                iconSize: [32,32], iconAnchor: [16,16]
            });

            if (marker) {
                marker.setLatLng([lat, lng]);
                map.setView([lat, lng]);
            } else {
                marker = L.marker([lat, lng], { icon: userIcon }).addTo(map)
                          .bindPopup('<strong style="color:#10B981;">Lokasi Anda</strong>').openPopup();
            }
        } catch(e) { console.error('Map error:', e); }
    }

    function errorCallback(error) {
        var msg, hint;
        switch(error.code) {
            case error.PERMISSION_DENIED:
                msg  = 'Izin lokasi ditolak.';
                hint = 'Buka pengaturan browser dan aktifkan izin lokasi.'; break;
            case error.POSITION_UNAVAILABLE:
                msg  = 'Informasi lokasi tidak tersedia.';
                hint = 'Pastikan GPS perangkat Anda aktif.'; break;
            case error.TIMEOUT:
                msg  = 'Request lokasi timeout.';
                hint = 'Sinyal GPS lemah. Pindah ke tempat terbuka.'; break;
            default:
                msg  = 'Gagal mendapatkan lokasi.';
                hint = 'Periksa koneksi dan izin GPS Anda.';
        }
        Swal.fire({ icon:'error', title:'📍 Lokasi Tidak Terdeteksi',
            html: '<b>' + msg + '</b><br><small style="color:#6B7280;">' + hint + '</small>',
            confirmButtonColor:'#2563EB', confirmButtonText:'Mengerti' });
    }

    /* ══════════════════════════════════
       FACE MODELS & VERIFICATION
    ══════════════════════════════════ */
    async function loadFaceModels() {
        if (modelsLoaded) return true;
        try {
            const MODEL_URL = 'https://cdn.jsdelivr.net/npm/@vladmandic/face-api/model';
            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
                faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
                faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
            ]);
            modelsLoaded = true;
            return true;
        } catch(e) { console.error('Model load error:', e); return false; }
    }

    async function getReferenceFaceDescriptor() {
        if (cachedReferenceDescriptor) return cachedReferenceDescriptor;
        const res  = await fetch('/face/descriptor', { headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
        const data = await res.json();
        if (data.success) {
            cachedReferenceDescriptor = new Float32Array(data.descriptor);
            return cachedReferenceDescriptor;
        }
        throw new Error(data.message);
    }

    async function verifyFaceSilent() {
        try {
            const video = document.querySelector('.webcam-capture video');
            if (!video) return { matched: false };
            const detection = await faceapi
                .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
                .withFaceLandmarks().withFaceDescriptor();
            if (detection && detection.detection.score >= 0.5) {
                const ref  = await getReferenceFaceDescriptor();
                const dist = faceapi.euclideanDistance(detection.descriptor, ref);
                if (dist <= 0.6) return { matched: true, descriptor: Array.from(detection.descriptor) };
                setPillState('mismatch');
            }
            return { matched: false };
        } catch(e) { return { matched: false }; }
    }

    /* ── Auto-scan pill UI ── */
    function setPillState(state) {
        var pill  = document.getElementById('auto-scan-pill');
        var label = document.getElementById('pill-label');
        var hint  = document.getElementById('cam-hint-text');
        pill.className = '';

        if (state === 'scanning') {
            pill.classList.add('');
            label.textContent = 'Scanning…';
            hint.textContent  = 'Arahkan wajah ke bingkai oval';
        } else if (state === 'matched') {
            label.textContent = '✓ Wajah Cocok!';
            hint.textContent  = 'Memproses presensi…';
            pill.style.background = 'rgba(16,185,129,0.15)';
            pill.style.color      = '#10B981';
            pill.style.borderColor= '#6EE7B7';
        } else if (state === 'mismatch') {
            pill.classList.add('mismatch');
            label.textContent = 'Tidak Cocok';
            hint.textContent  = 'Coba lagi…';
        }
    }

    /* ══════════════════════════════════
       TEXT-TO-SPEECH
    ══════════════════════════════════ */
    function speakText(text) {
        if (!window.speechSynthesis) return;
        var clean = text.replace(/[|]/g,' ')
            .replace(/[\u{1F000}-\u{1FFFF}\u{2600}-\u{27FF}\u{2300}-\u{23FF}\u{2B00}-\u{2BFF}✅❌⚠⌛⏳📍📷🔒🆔📅🤖]/gu,'')
            .replace(/\s+/g,' ').trim();
        window.speechSynthesis.cancel();
        var utter = new SpeechSynthesisUtterance(clean);
        utter.lang = 'id-ID'; utter.rate = 1.0;
        var voices = window.speechSynthesis.getVoices();
        var idVoice = voices.find(v => v.lang === 'id-ID');
        if (idVoice) utter.voice = idVoice;
        window.speechSynthesis.speak(utter);
    }
    if (window.speechSynthesis) {
        window.speechSynthesis.onvoiceschanged = () => window.speechSynthesis.getVoices();
    }

    /* ══════════════════════════════════
       NOTIFICATION HANDLER
    ══════════════════════════════════ */
    function showNotification(respond, onErrorRetry) {
        var parts   = respond.split('|');
        var status  = parts[0];
        var message = parts[1];
        var type    = parts[2];

        if (status === 'success') {
            var isIn = (type === 'in');
            speakText((isIn ? 'Absen Masuk Berhasil. ' : 'Absen Pulang Berhasil. ') + message);
            Swal.fire({
                icon: 'success', iconColor: isIn ? '#10B981' : '#2563EB',
                title: isIn ? '✅ Absen Masuk Berhasil!' : '🌆 Absen Pulang Berhasil!',
                html: '<b style="font-size:15px;">' + message + '</b>' +
                      '<br><small style="color:#6B7280;">Terverifikasi dengan ' +
                      (isIn ? 'Face ID' : 'Biometrik') + '</small>',
                confirmButtonColor: isIn ? '#10B981' : '#2563EB',
                timer: 4000, timerProgressBar: true
            }).then(() => { window.location.href = '/dashboard'; });
        } else {
            var icon = 'error', title = '', hint = '', color = '#EF4444', btnText = 'Coba Lagi';
            if (type === 'radius') {
                icon='warning'; color='#F59E0B';
                title='📍 Di Luar Radius Kantor';
                hint='Pastikan Anda berada di area kantor yang diizinkan.';
            } else if (type === 'in') {
                icon='warning'; color='#F59E0B';
                if (message.indexOf('Belum waktunya') !== -1) { title='⏳ Terlalu Awal!'; hint='Presensi masuk belum dibuka.'; }
                else if (message.indexOf('sudah habis') !== -1) { title='⌛ Waktu Habis'; hint='Batas absen masuk sudah terlewati.'; color='#EF4444'; icon='error'; }
                else { title='❌ Gagal Absen Masuk'; hint='Terjadi kesalahan.'; }
            } else if (type === 'out') {
                icon='warning'; color='#F59E0B';
                if (message.indexOf('Belum waktunya absen pulang') !== -1) { title='⏳ Belum Waktunya Pulang'; hint='Jam pulang belum tiba.'; }
                else if (message.indexOf('sudah melakukan absen pulang') !== -1) { title='✅ Sudah Absen Pulang'; icon='info'; color='#06B6D4'; btnText='OK'; hint='Anda sudah absen pulang.'; }
                else { title='❌ Gagal Absen Pulang'; hint='Terjadi kesalahan.'; }
            } else {
                if (message.indexOf('Lokasi tidak terdeteksi') !== -1) { icon='warning'; color='#F59E0B'; title='📍 GPS Belum Aktif'; hint='Aktifkan GPS di perangkat Anda.'; }
                else if (message.indexOf('referensi tidak ditemukan') !== -1) { title='🆔 Data Wajah Tidak Ditemukan'; hint='Daftarkan wajah terlebih dahulu.'; btnText='Ke Face ID'; }
                else if (message.indexOf('Verifikasi wajah gagal') !== -1 || message.indexOf('Wajah Tidak Cocok') !== -1) { title='😶 Wajah Tidak Cocok'; hint='Pastikan pencahayaan cukup dan wajah terlihat jelas.'; }
                else { title='⚠️ Terjadi Kesalahan'; hint='Silakan coba lagi.'; }
            }
            speakText(title.replace(/[\u{1F000}-\u{1FFFF}\u{2600}-\u{27FF}\u{2B00}-\u{2BFF}✅❌⚠⌛⏳📍📷🔒🆔📅🤖]/gu,'').trim() + '. ' + message);
            Swal.fire({
                icon, iconColor: color, title,
                html: '<b style="font-size:14px;color:#111827;">' + message + '</b>' +
                      '<br><small style="color:#6B7280;margin-top:4px;display:block;">' + hint + '</small>',
                confirmButtonColor: color, confirmButtonText: btnText
            }).then(function() {
                if (btnText === 'Ke Face ID') window.location.href = '/face/enrollment';
                else if (typeof onErrorRetry === 'function') onErrorRetry();
            });
        }
    }

    /* ══════════════════════════════════
       AUTO VERIFICATION LOOP
    ══════════════════════════════════ */
    function stopAutoVerification() {
        if (autoScanInterval) { clearInterval(autoScanInterval); autoScanInterval = null; }
        isProcessing = true;
    }

    function startAutoVerification() {
        stopAutoVerification();
        isProcessing = false;
        setPillState('scanning');

        autoScanInterval = setInterval(async function() {
            if (isProcessing || !webcamReady || !modelsLoaded) return;
            var lokasi_val = document.getElementById('lokasi').value;
            if (!lokasi_val) return;

            isProcessing = true;
            var result = await verifyFaceSilent();
            if (!result.matched) { isProcessing = false; return; }

            stopAutoVerification();
            setPillState('matched');
            document.getElementById('loading-overlay').classList.add('show');

            Webcam.snap(async function(uri) {
                const payload = {
                    _token: '{{ csrf_token() }}',
                    image: uri, lokasi: lokasi_val,
                    verified: true,
                    face_descriptor: JSON.stringify(result.descriptor),
                    shift_ke: $('#shift_ke_val').val(),
                    shift_nama: $('#shift_nama_val').val(),
                    shift_jam_masuk: $('#shift_jam_masuk_val').val(),
                    shift_jam_pulang: $('#shift_jam_pulang_val').val()
                };

                if (!navigator.onLine) {
                    const now = new Date();
                    const pad = n => String(n).padStart(2,'0');
                    payload.offline_time = `${now.getFullYear()}-${pad(now.getMonth()+1)}-${pad(now.getDate())} ${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
                    await saveOfflinePresensi({ payload, timestamp: payload.offline_time });
                    document.getElementById('loading-overlay').classList.remove('show');
                    Swal.fire({ icon:'success', title:'Disimpan Offline',
                        html:'Data presensi disimpan di perangkat.<br><small style="color:#6B7280;">Sinkronisasi otomatis saat online.</small>',
                        confirmButtonColor:'#10B981' }).then(() => { window.location.href = '/dashboard'; });
                    return;
                }

                $.ajax({ type:'POST', url:'/presensi/store', data:payload, cache:false,
                    success: function(respond) {
                        document.getElementById('loading-overlay').classList.remove('show');
                        if (respond.split('|')[0] === 'success') { showNotification(respond, null); }
                        else { showNotification(respond, function() { isProcessing=false; startAutoVerification(); }); }
                    },
                    error: function() {
                        document.getElementById('loading-overlay').classList.remove('show');
                        Swal.fire({ icon:'error', title:'Koneksi Bermasalah', text:'Gagal mengirim data ke server.', confirmButtonColor:'#EF4444' })
                            .then(function() { isProcessing=false; startAutoVerification(); });
                    }
                });
            });
        }, 1500);
    }

    /* ══════════════════════════════════
       MANUAL FACE BUTTON
    ══════════════════════════════════ */
    $('#takeabsen').click(function(e) {
        e.preventDefault();
        var lokasi_val = $('#lokasi').val();
        if (!lokasi_val) return Swal.fire({ icon:'error', title:'Lokasi Belum Terdeteksi', text:'Mohon tunggu GPS aktif', confirmButtonColor:'#2563EB' });
        if (!webcamReady) return Swal.fire({ icon:'error', title:'Kamera Belum Siap', text:'Mohon tunggu kamera aktif', confirmButtonColor:'#2563EB' });

        document.getElementById('loading-overlay').classList.add('show');
        Webcam.snap(async function(uri) {
            const payload = { _token:'{{ csrf_token() }}', image:uri, lokasi:lokasi_val };
            if (!navigator.onLine) {
                const now=new Date(); const pad=n=>String(n).padStart(2,'0');
                payload.offline_time=`${now.getFullYear()}-${pad(now.getMonth()+1)}-${pad(now.getDate())} ${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
                await saveOfflinePresensi({ payload, timestamp:payload.offline_time });
                document.getElementById('loading-overlay').classList.remove('show');
                Swal.fire({ icon:'success', title:'Disimpan Offline', confirmButtonColor:'#10B981' })
                    .then(()=>{ window.location.href='/dashboard'; });
                return;
            }
            $.ajax({ type:'POST', url:'/presensi/store', data:payload, cache:false,
                success:function(respond){ document.getElementById('loading-overlay').classList.remove('show'); showNotification(respond,null); },
                error:function(){ document.getElementById('loading-overlay').classList.remove('show');
                    Swal.fire({ icon:'error', title:'Koneksi Bermasalah', confirmButtonColor:'#EF4444' }); }
            });
        });
    });

    /* ══════════════════════════════════
       FINGERPRINT / WEBAUTHN
    ══════════════════════════════════ */
    $('#btnWebAuthn').click(async function(e) {
        e.preventDefault();
        var lokasi_val = $('#lokasi').val();
        if (!lokasi_val) return Swal.fire({ icon:'error', title:'Lokasi Belum Terdeteksi', text:'Mohon tunggu GPS aktif', confirmButtonColor:'#2563EB' });

        try {
            const rawIdBase64url = "{{ Auth::guard('karyawan')->user()->webauthn_id ?? '' }}";
            if (!rawIdBase64url) return;

            const rawIdBase64 = rawIdBase64url.replace(/-/g,'+').replace(/_/g,'/');
            const rawIdStr    = atob(rawIdBase64);
            const rawIdBuffer = new Uint8Array(rawIdStr.length);
            for (let i = 0; i < rawIdStr.length; i++) rawIdBuffer[i] = rawIdStr.charCodeAt(i);

            const challenge = new Uint8Array(32);
            window.crypto.getRandomValues(challenge);

            Swal.fire({ title:'Menunggu Fingerprint…', text:'Sentuh sensor sidik jari Anda', allowOutsideClick:false, didOpen:()=>Swal.showLoading() });

            const assertion = await navigator.credentials.get({
                publicKey: {
                    challenge, timeout: 60000,
                    userVerification: 'required',
                    allowCredentials: [{ id: rawIdBuffer, type: 'public-key' }]
                }
            });

            if (assertion) {
                Swal.close();
                stopAutoVerification();
                document.getElementById('loading-overlay').classList.add('show');

                Webcam.snap(async function(uri) {
                    const payload = {
                        _token: '{{ csrf_token() }}',
                        image: uri, lokasi: lokasi_val,
                        verified: true, verified_by: 'fingerprint',
                        shift_ke: $('#shift_ke_val').val(),
                        shift_nama: $('#shift_nama_val').val(),
                        shift_jam_masuk: $('#shift_jam_masuk_val').val(),
                        shift_jam_pulang: $('#shift_jam_pulang_val').val()
                    };

                    if (!navigator.onLine) {
                        const now=new Date(); const pad=n=>String(n).padStart(2,'0');
                        payload.offline_time=`${now.getFullYear()}-${pad(now.getMonth()+1)}-${pad(now.getDate())} ${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
                        await saveOfflinePresensi({ payload, timestamp:payload.offline_time });
                        document.getElementById('loading-overlay').classList.remove('show');
                        Swal.fire({ icon:'success', title:'Disimpan Offline', confirmButtonColor:'#10B981' })
                            .then(()=>{ window.location.href='/dashboard'; });
                        return;
                    }

                    $.ajax({ type:'POST', url:'/presensi/store', data:payload, cache:false,
                        success:function(respond){ document.getElementById('loading-overlay').classList.remove('show'); showNotification(respond,null); },
                        error:function(){ document.getElementById('loading-overlay').classList.remove('show');
                            Swal.fire({ icon:'error', title:'Koneksi Bermasalah', confirmButtonColor:'#EF4444' }); }
                    });
                });
            }
        } catch(err) {
            Swal.close();
            console.error('WebAuthn Error:', err);
            if (err.name === 'NotAllowedError') {
                Swal.fire({ icon:'warning', title:'Dibatalkan', text:'Pemindaian sidik jari dibatalkan.', confirmButtonColor:'#F59E0B' });
            } else {
                Swal.fire({ icon:'error', title:'Gagal', text:'Sensor biometrik tidak dapat diakses.', confirmButtonColor:'#EF4444' });
            }
        }
    });

    /* ══════════════════════════════════
       INIT
    ══════════════════════════════════ */
    $(document).ready(async function() {
        await loadFaceModels();
        startAutoVerification();
    });
</script>
@endpush