@extends('karyawan.layouts.presensi')

@section('content')

<style>

    :root {
        --primary:      #2563EB;
        --primary-soft: #EFF6FF;
        --success:      #10B981;
        --danger:       #EF4444;
        --warning:      #F59E0B;
        --text-900:     #111827;
        --text-600:     #4B5563;
        --border:       rgba(255,255,255,0.18);
        --glass-bg:     rgba(0,0,0,0.45);
        --glass-blur:   blur(14px) saturate(160%);
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: 'Inter', -apple-system, sans-serif;
        background: #000;
        overflow: hidden;
        height: 100vh;
    }

    /* ══════════════════════════════════════
       FULLSCREEN WEBCAM BACKGROUND
       ══════════════════════════════════════ */
    .webcam-capture,
    .webcam-capture video {
        position: fixed !important;
        top: 0; left: 0;
        width: 100vw !important;
        height: 100vh !important;
        object-fit: cover !important;
        border-radius: 0;
        z-index: 0;
    }

    /* Dark vignette overlay for readability */
    .cam-vignette {
        position: fixed;
        inset: 0;
        z-index: 1;
        background:
            linear-gradient(to bottom,
                rgba(0,0,0,0.55) 0%,
                transparent 30%,
                transparent 55%,
                rgba(0,0,0,0.70) 100%);
        pointer-events: none;
    }

    /* ══════════════════════════════════════
       TOP BAR
       ══════════════════════════════════════ */
    .top-bar {
        position: fixed;
        top: 0; left: 0; right: 0;
        z-index: 20;
        padding: 16px 16px 12px;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
    }

    /* Back button */
    .btn-back {
        width: 40px;
        height: 40px;
        background: var(--glass-bg);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 1px solid var(--border);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        flex-shrink: 0;
        transition: background 0.2s;
        -webkit-tap-highlight-color: transparent;
    }

    .btn-back:active { background: rgba(0,0,0,0.65); }
    .btn-back ion-icon { font-size: 22px; color: white; }

    /* Title card (top right) */
    .title-card {
        background: var(--glass-bg);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 10px 14px;
        text-align: right;
        flex: 1;
        min-width: 0;
    }

    .title-type {
        font-size: 14px;
        font-weight: 700;
        color: white;
        line-height: 1.2;
        margin-bottom: 2px;
    }

    .title-date {
        font-size: 10px;
        font-weight: 500;
        color: rgba(255,255,255,0.75);
        margin-bottom: 5px;
    }

    .shift-info {
        font-size: 10px;
        line-height: 1.5;
        color: rgba(255,255,255,0.8);
        font-weight: 500;
    }

    .absen-badge {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 50px;
        font-size: 10px;
        font-weight: 700;
        margin-top: 3px;
    }

    .absen-badge.done { background: rgba(16,185,129,0.25); color: #6EE7B7; }
    .absen-badge.pending { background: rgba(239,68,68,0.25); color: #FCA5A5; }

    /* ══════════════════════════════════════
       SHIFT SELECTOR
       ══════════════════════════════════════ */
    .shift-selector {
        position: fixed;
        z-index: 20;
        left: 16px; right: 16px;
    }

    /* Positioned below top-bar — dynamic top via JS or fixed estimate */
    .shift-selector { top: 128px; }

    .shift-select-wrap {
        position: relative;
    }

    .shift-select-wrap select {
        width: 100%;
        appearance: none;
        -webkit-appearance: none;
        padding: 11px 40px 11px 14px;
        background: var(--glass-bg);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 1px solid var(--border);
        border-radius: 12px;
        color: white;
        font-family: 'Inter', sans-serif;
        font-size: 13px;
        font-weight: 600;
        outline: none;
    }

    .shift-select-wrap select option {
        background: #1E293B;
        color: white;
    }

    .shift-select-wrap ion-icon {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: rgba(255,255,255,0.7);
        font-size: 16px;
        pointer-events: none;
    }

    /* ══════════════════════════════════════
       AUTO-SCAN STATUS PILL
       ══════════════════════════════════════ */
    #auto-scan-status {
        position: fixed;
        top: 76px;
        left: 16px;
        z-index: 20;
        background: var(--glass-bg);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 1px solid var(--border);
        border-radius: 50px;
        padding: 7px 12px 7px 10px;
        display: flex;
        align-items: center;
        gap: 7px;
        transition: background 0.3s, border-color 0.3s;
    }

    #auto-scan-status .scan-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--success);
        flex-shrink: 0;
        animation: pulse-dot 1.5s ease-in-out infinite;
    }

    @keyframes pulse-dot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50%       { opacity: 0.4; transform: scale(0.7); }
    }

    #auto-scan-status .scan-label {
        font-size: 11px;
        font-weight: 600;
        color: var(--success);
        line-height: 1;
    }

    #auto-scan-status .scan-sub {
        font-size: 9px;
        color: rgba(255,255,255,0.6);
        line-height: 1;
        margin-top: 2px;
    }

    /* ══════════════════════════════════════
       FACE GUIDE OVAL
       ══════════════════════════════════════ */
    .face-guide {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -54%);
        width: 200px;
        height: 265px;
        border: 2px solid rgba(255,255,255,0.6);
        border-radius: 50%;
        z-index: 5;
        pointer-events: none;
        box-shadow: 0 0 0 9999px rgba(0,0,0,0.22);
    }

    /* Corner accent */
    .face-guide::before {
        content: '';
        position: absolute;
        top: -2px; left: -2px;
        width: 28px; height: 28px;
        border-top: 3px solid var(--primary);
        border-left: 3px solid var(--primary);
        border-radius: 50% 0 0 0;
    }

    .face-guide::after {
        content: '';
        position: absolute;
        bottom: -2px; right: -2px;
        width: 28px; height: 28px;
        border-bottom: 3px solid var(--primary);
        border-right: 3px solid var(--primary);
        border-radius: 0 0 50% 0;
    }

    /* ══════════════════════════════════════
       BOTTOM PANEL
       ══════════════════════════════════════ */
    .bottom-panel {
        position: fixed;
        bottom: 80px; /* above dock nav */
        left: 0; right: 0;
        z-index: 20;
        padding: 0 16px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    /* Map strip */
    .map-strip {
        background: var(--glass-bg);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
        height: 110px;
        position: relative;
    }

    #map {
        width: 100%;
        height: 100%;
    }

    .map-radius-tag {
        position: absolute;
        bottom: 8px;
        right: 8px;
        z-index: 500;
        background: rgba(0,0,0,0.60);
        backdrop-filter: blur(4px);
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 8px;
        padding: 3px 8px;
        font-size: 10px;
        font-weight: 600;
        color: white;
    }

    /* Manual button row (fallback) */
    .manual-btn-row {
        display: none; /* hidden by default, shown via JS if needed */
        gap: 10px;
    }

    .btn-manual {
        flex: 1;
        padding: 14px;
        border-radius: 14px;
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        border: none;
        transition: opacity 0.2s, transform 0.15s;
        -webkit-tap-highlight-color: transparent;
    }

    .btn-manual:active { opacity: 0.85; transform: scale(0.97); }
    .btn-manual:disabled { opacity: 0.5; cursor: not-allowed; }

    .btn-masuk {
        background: white;
        color: var(--success);
        border: 1.5px solid var(--success);
    }

    .btn-masuk:active { background: var(--success); color: white; }

    .btn-pulang {
        background: white;
        color: var(--danger);
        border: 1.5px solid var(--danger);
    }

    .btn-pulang:active { background: var(--danger); color: white; }

    .btn-manual ion-icon { font-size: 18px; }

    /* ══════════════════════════════════════
       LOADING OVERLAY
       ══════════════════════════════════════ */
    .loading-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.60);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        backdrop-filter: blur(4px);
    }

    .loading-overlay.show { display: flex; }

    .loading-card {
        background: white;
        border-radius: 20px;
        padding: 28px 32px;
        text-align: center;
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }

    .loading-ring {
        width: 48px;
        height: 48px;
        border: 4px solid #EFF6FF;
        border-top: 4px solid var(--primary);
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
        margin: 0 auto 14px;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .loading-text {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-900);
    }

    .loading-sub {
        font-size: 12px;
        color: #6B7280;
        margin-top: 4px;
    }

    /* ══════════════════════════════════════
       SAFE AREA
       ══════════════════════════════════════ */
    @supports (padding: max(0px)) {
        .bottom-panel {
            bottom: max(80px, calc(env(safe-area-inset-bottom) + 80px));
        }
        .top-bar {
            padding-top: max(16px, env(safe-area-inset-top));
        }
    }

    /* ══════════════════════════════════════
       SMALL SCREEN TWEAKS
       ══════════════════════════════════════ */
    @media (max-width: 360px) {
        .title-card { padding: 8px 10px; }
        .title-type { font-size: 13px; }
        .map-strip { height: 90px; }
    }
</style>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<!-- ── WEBCAM FULLSCREEN BACKGROUND ── -->
<div class="webcam-capture"></div>

<!-- Vignette -->
<div class="cam-vignette"></div>

<!-- Face guide oval -->
<div class="face-guide"></div>

<!-- ── TOP BAR ── -->
<div class="top-bar">
    <a href="{{ route('dashboard') }}" class="btn-back">
        <ion-icon name="chevron-back-outline"></ion-icon>
    </a>

    <div class="title-card">
        <div class="title-type">{{ $cek > 0 ? '🌆 Absen Pulang' : '🌅 Absen Masuk' }}</div>
        <div class="title-date">{{ $namahari }}, {{ \Carbon\Carbon::parse($hariini)->isoFormat('D MMMM Y') }}</div>
        <div class="shift-info">
            @if(isset($is_multi_shift) && $is_multi_shift)
                Shift {{ $current_shift->nama_shift }}
                ({{ date('H:i', strtotime($current_shift->jam_masuk)) }}–{{ date('H:i', strtotime($current_shift->jam_pulang)) }})
            @else
                {{ $jamkerja->nama_jam_kerja }}
                ({{ date('H:i', strtotime($jamkerja->jam_masuk)) }}–{{ date('H:i', strtotime($jamkerja->jam_pulang)) }})
            @endif
        </div>
        @if($cek > 0)
            <span class="absen-badge done">✓ Sudah Absen</span>
        @else
            <span class="absen-badge pending">● Belum Absen</span>
        @endif
    </div>
</div>

<!-- ── AUTO-SCAN STATUS ── -->
<div id="auto-scan-status">
    <div class="scan-dot"></div>
    <div>
        <div class="scan-label">Auto-Scan Aktif</div>
        <div class="scan-sub">Arahkan & tahan posisi wajah…</div>
    </div>
</div>

<!-- ── SHIFT SELECTOR (multi-shift only) ── -->
@if(isset($is_multi_shift) && $is_multi_shift)
<div class="shift-selector">
    <form action="{{ route('presensi.create') }}" method="GET" id="shift-form">
        <div class="shift-select-wrap">
            <select name="shift_ke" id="shift_ke" onchange="document.getElementById('shift-form').submit()">
                @foreach($shifts_available as $s)
                    <option value="{{ $s->shift_ke }}" {{ $shift_ke == $s->shift_ke ? 'selected' : '' }}>
                        Shift {{ $s->shift_ke }} – {{ $s->nama_shift }} ({{ date('H:i', strtotime($s->jam_masuk)) }}–{{ date('H:i', strtotime($s->jam_pulang)) }})
                    </option>
                @endforeach
            </select>
            <ion-icon name="chevron-down-outline"></ion-icon>
        </div>
    </form>
</div>
@endif

<!-- ── HIDDEN INPUTS ── -->
<input type="hidden" id="lokasi">
@if(isset($is_multi_shift) && $is_multi_shift)
    <input type="hidden" id="shift_ke_val"        value="{{ $shift_ke }}">
    <input type="hidden" id="shift_nama_val"      value="{{ $current_shift->nama_shift }}">
    <input type="hidden" id="shift_jam_masuk_val" value="{{ $current_shift->jam_masuk }}">
    <input type="hidden" id="shift_jam_pulang_val" value="{{ $current_shift->jam_pulang }}">
@else
    <input type="hidden" id="shift_ke_val"        value="">
    <input type="hidden" id="shift_nama_val"      value="">
    <input type="hidden" id="shift_jam_masuk_val" value="">
    <input type="hidden" id="shift_jam_pulang_val" value="">
@endif

<!-- ── BOTTOM PANEL ── -->
<div class="bottom-panel">

    <!-- Map Strip -->
    <div class="map-strip">
        <div id="map"></div>
        <div class="map-radius-tag">Radius: {{ $lok_kantor->radius_cabang }}m</div>
    </div>

    <!-- Manual button row (fallback — shown via JS when face not enrolled) -->
    <div class="manual-btn-row" id="manual-btn-row">
        <button class="btn-manual btn-masuk" id="takeabsen" {{ $cek > 0 ? 'disabled' : '' }}>
            <ion-icon name="log-in-outline"></ion-icon>
            Absen Masuk
        </button>
        <button class="btn-manual btn-pulang" id="takeabsen-out" {{ $cek == 0 ? 'disabled' : '' }}>
            <ion-icon name="log-out-outline"></ion-icon>
            Absen Pulang
        </button>
    </div>
</div>

<!-- ── LOADING OVERLAY ── -->
<div class="loading-overlay" id="loading-overlay">
    <div class="loading-card">
        <div class="loading-ring"></div>
        <div class="loading-text">Memproses Presensi…</div>
        <div class="loading-sub">Harap tunggu sebentar</div>
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

    /* ── Webcam init ── */
    if (typeof Webcam === 'undefined') {
        Swal.fire({ icon:'error', title:'Library Error', text:'Webcam library tidak ter-load. Refresh halaman.', confirmButtonColor:'#2563EB' });
    } else {
        Webcam.set({
            width: 640, height: 480,
            image_format: 'jpeg', jpeg_quality: 90,
            flip_horiz: true,
            constraints: { video: true, facingMode: 'user' }
        });
        Webcam.attach('.webcam-capture');
        Webcam.on('live', function() { webcamReady = true; });
        Webcam.on('error', function(err) {
            Swal.fire({ icon:'error', title:'Kamera Error', html:'Tidak dapat mengakses kamera.<br>Pastikan izin kamera diaktifkan.', confirmButtonColor:'#2563EB' });
        });
    }

    /* ── Geolocation ── */
    if (!navigator.geolocation) {
        Swal.fire({ icon:'error', title:'GPS Tidak Didukung', text:'Browser Anda tidak mendukung GPS.', confirmButtonColor:'#2563EB' });
    } else {
        navigator.geolocation.getCurrentPosition(successCallback, errorCallback, {
            enableHighAccuracy: true, timeout: 10000, maximumAge: 0
        });
    }

    function successCallback(position) {
        var lat = position.coords.latitude;
        var lng = position.coords.longitude;
        document.getElementById('lokasi').value = lat + ',' + lng;

        try {
            if (!map) {
                map = L.map('map', { zoomControl: false, attributionControl: false })
                       .setView([lat, lng], 17);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);

                var lok     = "{{ $lok_kantor->lokasi_cabang }}".split(',');
                var lat_off = parseFloat(lok[0]);
                var lng_off = parseFloat(lok[1]);
                var radius  = {{ $lok_kantor->radius_cabang }};

                circle = L.circle([lat_off, lng_off], {
                    color: '#2563EB', fillColor: '#2563EB', fillOpacity: 0.12,
                    radius: radius, weight: 2, dashArray: '5,5'
                }).addTo(map);

                var offIcon = L.divIcon({
                    className: '',
                    html: '<div style="background:#2563EB;width:34px;height:34px;border-radius:50%;border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.3);display:flex;align-items:center;justify-content:center;"><ion-icon name="business" style="color:white;font-size:17px;"></ion-icon></div>',
                    iconSize: [34,34], iconAnchor: [17,17]
                });
                L.marker([lat_off, lng_off], { icon: offIcon }).addTo(map)
                 .bindPopup('<strong style="color:#2563EB;">Kantor</strong><br><small>Radius: ' + radius + 'm</small>');
            }

            var userIcon = L.divIcon({
                className: '',
                html: '<div style="background:#10B981;width:34px;height:34px;border-radius:50%;border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.3);display:flex;align-items:center;justify-content:center;"><ion-icon name="person" style="color:white;font-size:17px;"></ion-icon></div>',
                iconSize: [34,34], iconAnchor: [17,17]
            });

            if (marker) {
                marker.setLatLng([lat, lng]);
                map.setView([lat, lng]);
            } else {
                marker = L.marker([lat, lng], { icon: userIcon }).addTo(map)
                          .bindPopup('<strong style="color:#10B981;">Lokasi Anda</strong>').openPopup();
            }
        } catch(e) {
            console.error('Map error:', e);
        }
    }

    function errorCallback(error) {
        var msg = '', hint = '';
        switch(error.code) {
            case error.PERMISSION_DENIED:
                msg  = 'Izin lokasi ditolak.';
                hint = 'Buka pengaturan browser dan aktifkan izin lokasi untuk halaman ini.';
                break;
            case error.POSITION_UNAVAILABLE:
                msg  = 'Informasi lokasi tidak tersedia.';
                hint = 'Pastikan GPS perangkat Anda aktif.';
                break;
            case error.TIMEOUT:
                msg  = 'Request lokasi timeout.';
                hint = 'Sinyal GPS lemah. Pindah ke tempat terbuka dan coba lagi.';
                break;
            default:
                msg  = 'Gagal mendapatkan lokasi.';
                hint = 'Periksa koneksi dan izin GPS Anda.';
        }
        Swal.fire({
            icon: 'error',
            title: '📍 Lokasi Tidak Terdeteksi',
            html: '<b>' + msg + '</b><br><small style="color:#6B7280;">' + hint + '</small>',
            confirmButtonColor: '#2563EB',
            confirmButtonText: 'Mengerti'
        });
    }

    /* ── Face models ── */
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
        } catch(e) {
            console.error('Model load error:', e);
            return false;
        }
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

    /* ── Silent face verify ── */
    async function verifyFaceSilent() {
        try {
            const video = document.querySelector('.webcam-capture video');
            if (!video) return { matched: false };

            const detection = await faceapi
                .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
                .withFaceLandmarks()
                .withFaceDescriptor();

            if (detection && detection.detection.score >= 0.5) {
                const ref  = await getReferenceFaceDescriptor();
                const dist = faceapi.euclideanDistance(detection.descriptor, ref);
                if (dist <= 0.6) return { matched: true, descriptor: Array.from(detection.descriptor) };

                setAutoScanUI('mismatch');
            }
            return { matched: false };
        } catch(e) {
            return { matched: false };
        }
    }

    /* ── Auto-scan UI helpers ── */
    function setAutoScanUI(state) {
        var el = document.getElementById('auto-scan-status');
        if (state === 'scanning') {
            el.innerHTML = `
                <div class="scan-dot" style="background:#10B981;"></div>
                <div>
                    <div class="scan-label" style="color:#10B981;">Auto-Scan Aktif</div>
                    <div class="scan-sub">Arahkan & tahan posisi wajah…</div>
                </div>`;
            el.style.borderColor = 'rgba(255,255,255,0.18)';
        } else if (state === 'matched') {
            el.innerHTML = `
                <div class="scan-dot" style="background:#10B981; animation:none;"></div>
                <div>
                    <div class="scan-label" style="color:#6EE7B7;">✓ Wajah Cocok!</div>
                    <div class="scan-sub">Menyimpan presensi…</div>
                </div>`;
            el.style.borderColor = '#10B981';
            el.style.background  = 'rgba(16,185,129,0.25)';
        } else if (state === 'mismatch') {
            el.innerHTML = `
                <div class="scan-dot" style="background:#EF4444; animation:none;"></div>
                <div>
                    <div class="scan-label" style="color:#FCA5A5;">Wajah Tidak Cocok</div>
                    <div class="scan-sub">Silakan coba lagi…</div>
                </div>`;
            el.style.borderColor = '#EF4444';
        }
    }

    /* ══════════════════════════════════════
       TEXT-TO-SPEECH HELPER (Web Speech API)
       ══════════════════════════════════════ */
    function speakText(text) {
        if (!window.speechSynthesis) return;
        // Strip emoji & pipes before speaking
        var clean = text
            .replace(/[|]/g, ' ')
            .replace(/[\u{1F000}-\u{1FFFF}\u{2600}-\u{27FF}\u{2300}-\u{23FF}\u{2B00}-\u{2BFF}\u{FE00}-\u{FEFF}✅❌⚠⌛⏳📍📷🔒🆔📅📷🤖]/gu, '')
            .replace(/\s+/g, ' ')
            .trim();
        window.speechSynthesis.cancel();
        var utter = new SpeechSynthesisUtterance(clean);
        utter.lang  = 'id-ID';
        utter.rate  = 1.0;
        utter.pitch = 1.0;
        utter.volume = 1.0;
        // Prefer Indonesian voice if available
        var voices = window.speechSynthesis.getVoices();
        var idVoice = voices.find(function(v) { return v.lang === 'id-ID'; });
        if (idVoice) utter.voice = idVoice;
        window.speechSynthesis.speak(utter);
    }

    // Pre-load voices (some browsers load them async)
    if (window.speechSynthesis) {
        window.speechSynthesis.onvoiceschanged = function() {
            window.speechSynthesis.getVoices();
        };
    }

    /* ══════════════════════════════════════
       NOTIFICATION HELPER
       Parses controller response: "status|message|type"
       ══════════════════════════════════════ */
    function showNotification(respond, onErrorRetry) {
        var parts = respond.split('|');
        var status  = parts[0];   // success / error
        var message = parts[1];   // human message
        var type    = parts[2];   // in / out / radius / system

        if (status === 'success') {
            var isIn = (type === 'in');
            var successTitle = isIn ? 'Absen Masuk Berhasil' : 'Absen Pulang Berhasil';
            speakText(successTitle + '. ' + message);
            Swal.fire({
                icon: 'success',
                iconColor: isIn ? '#10B981' : '#2563EB',
                title: isIn ? '✅ Absen Masuk Berhasil!' : '🌆 Absen Pulang Berhasil!',
                html: '<b style="font-size:15px;">' + message + '</b>' +
                      '<br><small style="color:#6B7280;">🤖 Terverifikasi dengan Face ID</small>',
                confirmButtonColor: isIn ? '#10B981' : '#2563EB',
                confirmButtonText: 'OK',
                timer: 4000,
                timerProgressBar: true
            }).then(function() { window.location.href = '/dashboard'; });

        } else {
            // ── ERROR: determine icon, title, hint by type & message content ──
            var icon = 'error', title = '', hint = '', color = '#EF4444', btnText = 'Coba Lagi';

            if (type === 'radius') {
                icon  = 'warning';
                color = '#F59E0B';
                title = '📍 Di Luar Radius Kantor';
                hint  = 'Pastikan Anda berada di dalam area kantor atau cabang yang diizinkan. Periksa lokasi Anda di peta.';

            } else if (type === 'in') {
                icon = 'warning';
                color = '#F59E0B';
                if (message.indexOf('Belum waktunya') !== -1) {
                    title = '⏳ Terlalu Awal!';
                    hint  = 'Presensi masuk belum dibuka. Silakan tunggu hingga waktu yang ditentukan.';
                } else if (message.indexOf('sudah habis') !== -1) {
                    title = '⌛ Waktu Presensi Habis';
                    hint  = 'Batas waktu absen masuk sudah terlewati. Hubungi atasan atau HR Anda.';
                    color = '#EF4444';
                    icon  = 'error';
                } else {
                    title = '❌ Gagal Absen Masuk';
                    hint  = 'Terjadi kesalahan saat menyimpan presensi masuk.';
                }

            } else if (type === 'out') {
                icon = 'warning';
                color = '#F59E0B';
                if (message.indexOf('Belum waktunya absen pulang') !== -1) {
                    title = '⏳ Belum Waktunya Pulang';
                    hint  = 'Jam pulang belum tiba. Harap tetap berada di tempat kerja hingga jam yang ditentukan.';
                } else if (message.indexOf('sudah melakukan absen pulang') !== -1) {
                    title = '✅ Sudah Absen Pulang';
                    hint  = 'Anda sudah melakukan absen pulang sebelumnya hari ini.';
                    icon  = 'info';
                    color = '#06B6D4';
                    btnText = 'OK';
                } else {
                    title = '❌ Gagal Absen Pulang';
                    hint  = 'Terjadi kesalahan saat menyimpan presensi pulang.';
                }

            } else {
                // system errors
                if (message.indexOf('Lokasi tidak terdeteksi') !== -1) {
                    icon  = 'warning';
                    color = '#F59E0B';
                    title = '📍 GPS Belum Aktif';
                    hint  = 'Aktifkan GPS / lokasi di perangkat dan browser Anda, lalu coba lagi.';
                } else if (message.indexOf('Foto tidak terdeteksi') !== -1) {
                    title = '📷 Kamera Tidak Aktif';
                    hint  = 'Izinkan akses kamera di pengaturan browser, lalu muat ulang halaman.';
                } else if (message.indexOf('wajah tidak lengkap') !== -1 || message.indexOf('Wajah Tidak Cocok') !== -1 || message.indexOf('Verifikasi wajah gagal') !== -1) {
                    title = '😶 Verifikasi Wajah Gagal';
                    hint  = 'Pastikan pencahayaan cukup dan wajah Anda terlihat jelas di dalam bingkai oval.';
                } else if (message.indexOf('Face Verification diwajibkan') !== -1) {
                    title = '🔒 Verifikasi Wajah Diperlukan';
                    hint  = 'Sistem akan memverifikasi wajah Anda secara otomatis. Arahkan wajah ke kamera.';
                    icon  = 'info';
                    color = '#2563EB';
                } else if (message.indexOf('referensi tidak ditemukan') !== -1) {
                    title = '🆔 Data Wajah Tidak Ditemukan';
                    hint  = 'Silakan daftarkan wajah Anda terlebih dahulu melalui menu Face ID.';
                    btnText = 'Ke Face ID';
                } else if (message.indexOf('Jam kerja tidak ditemukan') !== -1) {
                    title = '📅 Jadwal Tidak Ditemukan';
                    hint  = 'Jadwal kerja Anda untuk hari ini belum dikonfigurasi. Hubungi admin.';
                } else {
                    title = '⚠️ Terjadi Kesalahan';
                    hint  = 'Silakan coba lagi atau hubungi admin jika masalah berlanjut.';
                }
            }

            // Speak clean title + message
            var speakTitle = title.replace(/[\u{1F000}-\u{1FFFF}\u{2600}-\u{27FF}\u{2B00}-\u{2BFF}✅❌⚠⌛⏳📍📷🔒🆔📅📷🤖]/gu, '').trim();
            speakText(speakTitle + '. ' + message);

            Swal.fire({
                icon: icon,
                iconColor: color,
                title: title,
                html: '<b style="font-size:14px;color:#111827;">' + message + '</b>' +
                      '<br><small style="color:#6B7280;margin-top:4px;display:block;">' + hint + '</small>',
                confirmButtonColor: color,
                confirmButtonText: btnText
            }).then(function() {
                if (btnText === 'Ke Face ID') {
                    window.location.href = '/face/enrollment';
                } else if (typeof onErrorRetry === 'function') {
                    onErrorRetry();
                }
            });
        }
    }

    /* ── Auto verification loop ── */
    function stopAutoVerification() {
        if (autoScanInterval) {
            clearInterval(autoScanInterval);
            autoScanInterval = null;
        }
        isProcessing = true; // kunci agar tidak ada tick baru
    }

    function startAutoVerification() {
        stopAutoVerification();
        isProcessing = false; // buka kunci setelah reset bersih
        setAutoScanUI('scanning');

        autoScanInterval = setInterval(async function() {
            // Guard: langsung tolak jika sedang diproses
            if (isProcessing || !webcamReady || !modelsLoaded) return;
            var lokasi_val = document.getElementById('lokasi').value;
            if (!lokasi_val) return;

            // ── KUNCI SEGERA sebelum async ── cegah race condition
            isProcessing = true;

            var result = await verifyFaceSilent();

            if (!result.matched) {
                // Wajah tidak cocok, buka kunci agar tick berikutnya bisa coba
                isProcessing = false;
                return;
            }

            // Wajah cocok: hentikan loop sepenuhnya
            stopAutoVerification();
            setAutoScanUI('matched');
            document.getElementById('loading-overlay').classList.add('show');

            Webcam.snap(async function(uri) {
                const payload = {
                    _token:           '{{ csrf_token() }}',
                    image:            uri,
                    lokasi:           lokasi_val,
                    verified:         true,
                    face_descriptor:  JSON.stringify(result.descriptor),
                    shift_ke:         $('#shift_ke_val').val(),
                    shift_nama:       $('#shift_nama_val').val(),
                    shift_jam_masuk:  $('#shift_jam_masuk_val').val(),
                    shift_jam_pulang: $('#shift_jam_pulang_val').val()
                };

                if (!navigator.onLine) {
                    const now = new Date();
                    const pad = (n) => String(n).padStart(2, '0');
                    payload.offline_time = `${now.getFullYear()}-${pad(now.getMonth() + 1)}-${pad(now.getDate())} ${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
                    
                    await saveOfflinePresensi({ payload: payload, timestamp: payload.offline_time });
                    
                    document.getElementById('loading-overlay').classList.remove('show');
                    Swal.fire({
                        icon: 'success',
                        title: 'Disimpan Offline',
                        html: 'Data presensi berhasil disimpan di perangkat.<br><small style="color:#6B7280;">Sistem akan melakukan sinkronisasi otomatis saat internet tersedia.</small>',
                        confirmButtonColor: '#10B981'
                    }).then(() => {
                        window.location.href = '/dashboard';
                    });
                    return;
                }

                $.ajax({
                    type: 'POST', url: '/presensi/store',
                    data: payload,
                    cache: false,
                    success: function(respond) {
                        document.getElementById('loading-overlay').classList.remove('show');
                        var parts  = respond.split('|');
                        var status = parts[0];

                        if (status === 'success') {
                            showNotification(respond, null);
                        } else {
                            showNotification(respond, function() {
                                isProcessing = false;
                                startAutoVerification();
                            });
                        }
                    },
                    error: function() {
                        document.getElementById('loading-overlay').classList.remove('show');
                        Swal.fire({
                            icon: 'error',
                            title: '⚡ Koneksi Bermasalah',
                            html: '<b>Gagal mengirim data ke server.</b><br><small style="color:#6B7280;">Periksa koneksi internet Anda dan coba lagi.</small>',
                            confirmButtonColor: '#EF4444'
                        }).then(function() {
                            isProcessing = false;
                            startAutoVerification();
                        });
                    }
                });
            });
        }, 1500);
    }

    /* ── Manual fallback button ── */
    $('#takeabsen').click(function(e) {
        e.preventDefault();
        var lokasi_val = $('#lokasi').val();
        if (!lokasi_val) return Swal.fire({ icon:'error', title:'Lokasi Belum Terdeteksi', text:'Mohon tunggu hingga lokasi terdeteksi', confirmButtonColor:'#2563EB' });
        if (!webcamReady) return Swal.fire({ icon:'error', title:'Kamera Belum Siap', text:'Mohon tunggu hingga kamera aktif', confirmButtonColor:'#2563EB' });

        document.getElementById('loading-overlay').classList.add('show');
        Webcam.snap(async function(uri) {
            const payload = { _token: '{{ csrf_token() }}', image: uri, lokasi: lokasi_val };

            if (!navigator.onLine) {
                const now = new Date();
                const pad = (n) => String(n).padStart(2, '0');
                payload.offline_time = `${now.getFullYear()}-${pad(now.getMonth() + 1)}-${pad(now.getDate())} ${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
                
                await saveOfflinePresensi({ payload: payload, timestamp: payload.offline_time });
                
                document.getElementById('loading-overlay').classList.remove('show');
                Swal.fire({
                    icon: 'success',
                    title: 'Disimpan Offline',
                    html: 'Data presensi berhasil disimpan di perangkat.<br><small style="color:#6B7280;">Sistem akan melakukan sinkronisasi otomatis saat internet tersedia.</small>',
                    confirmButtonColor: '#10B981'
                }).then(() => {
                    window.location.href = '/dashboard';
                });
                return;
            }

            $.ajax({
                type: 'POST', url: '/presensi/store',
                data: payload,
                cache: false,
                success: function(respond) {
                    document.getElementById('loading-overlay').classList.remove('show');
                    showNotification(respond, null);
                },
                error: function() {
                    document.getElementById('loading-overlay').classList.remove('show');
                    Swal.fire({ icon:'error', title:'⚡ Koneksi Bermasalah', html:'<b>Gagal mengirim data ke server.</b><br><small style="color:#6B7280;">Periksa koneksi internet Anda.</small>', confirmButtonColor:'#EF4444' });
                }
            });
        });
    });

    /* ── Init on ready ── */
    $(document).ready(async function() {
        await loadFaceModels();
        startAutoVerification();
    });
</script>
@endpush