@extends('karyawan.layouts.presensi')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

    :root {
        --primary:      #2563EB;
        --primary-soft: #EFF6FF;
        --primary-mid:  #BFDBFE;
        --success:      #10B981;
        --success-soft: #ECFDF5;
        --danger:       #EF4444;
        --warning:      #F59E0B;
        --text-900:     #111827;
        --text-600:     #4B5563;
        --text-400:     #9CA3AF;
        --border:       #F1F5F9;
        --border-med:   #E2E8F0;
        --surface:      #FFFFFF;
        --bg:           #F8FAFC;
        --radius-sm:    10px;
        --radius-md:    14px;
        --radius-lg:    18px;
        --shadow-sm:    0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: 'Inter', -apple-system, sans-serif;
        background: var(--bg);
        color: var(--text-900);
        -webkit-font-smoothing: antialiased;
    }

    /* ── STICKY HEADER ── */
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
        flex-shrink: 0;
        transition: background 0.2s;
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

    /* ── INSTRUCTION CARD ── */
    .instruction-card {
        background: var(--primary-soft);
        border: 1px solid var(--primary-mid);
        border-radius: var(--radius-lg);
        padding: 13px 16px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }

    .instruction-card ion-icon {
        font-size: 20px;
        color: var(--primary);
        flex-shrink: 0;
        margin-top: 1px;
    }

    .instruction-card .instr-text {
        font-size: 13px;
        font-weight: 500;
        color: var(--primary);
        line-height: 1.55;
    }

    .instruction-card .instr-text strong {
        font-weight: 700;
        display: block;
        margin-bottom: 2px;
    }

    /* ── QR READER CARD ── */
    .qr-card {
        background: var(--surface);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .qr-card-head {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 13px 16px;
        border-bottom: 1px solid var(--border);
    }

    .qr-card-icon {
        width: 28px; height: 28px;
        border-radius: 8px;
        background: var(--primary-soft);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }

    .qr-card-icon ion-icon { font-size: 14px; color: var(--primary); }

    .qr-card-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--text-900);
        flex: 1;
    }

    /* Status pill */
    #status-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 50px;
        font-size: 10px;
        font-weight: 700;
        background: #FEF3C7;
        color: #D97706;
        border: 1px solid #FDE68A;
        transition: all 0.3s;
    }

    #status-pill.ready {
        background: var(--success-soft);
        color: var(--success);
        border-color: #A7F3D0;
    }

    #status-dot {
        width: 6px; height: 6px;
        border-radius: 50%;
        background: currentColor;
        animation: pulse-dot 1.4s ease-in-out infinite;
    }

    @keyframes pulse-dot {
        0%, 100% { opacity: 1; }
        50%       { opacity: 0.3; }
    }

    /* QR Reader container */
    .qr-reader-wrap {
        padding: 16px;
        position: relative;
    }

    /* html5-qrcode override */
    #reader {
        width: 100% !important;
        border: none !important;
        border-radius: var(--radius-md) !important;
        overflow: hidden !important;
    }

    #reader video {
        border-radius: var(--radius-md) !important;
        object-fit: cover !important;
    }

    /* Styled QR frame overlay */
    .qr-frame-overlay {
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        width: 180px; height: 180px;
        pointer-events: none;
        z-index: 10;
    }

    .qr-frame-overlay .corner {
        position: absolute;
        width: 24px; height: 24px;
        border-color: var(--primary);
        border-style: solid;
    }

    .qr-frame-overlay .corner.tl { top: 0; left: 0; border-width: 3px 0 0 3px; border-radius: 4px 0 0 0; }
    .qr-frame-overlay .corner.tr { top: 0; right: 0; border-width: 3px 3px 0 0; border-radius: 0 4px 0 0; }
    .qr-frame-overlay .corner.bl { bottom: 0; left: 0; border-width: 0 0 3px 3px; border-radius: 0 0 0 4px; }
    .qr-frame-overlay .corner.br { bottom: 0; right: 0; border-width: 0 3px 3px 0; border-radius: 0 0 4px 0; }

    /* Scan line animation */
    .scan-line {
        position: absolute;
        left: 0; right: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent, var(--primary), transparent);
        animation: scan-move 2s ease-in-out infinite;
        top: 0;
    }

    @keyframes scan-move {
        0%   { top: 0; }
        50%  { top: calc(100% - 2px); }
        100% { top: 0; }
    }

    /* ── MAP CARD ── */
    .map-card {
        background: var(--surface);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .map-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 13px 16px;
        border-bottom: 1px solid var(--border);
    }

    .map-head-left {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .map-head-icon {
        width: 28px; height: 28px;
        border-radius: 8px;
        background: var(--primary-soft);
        display: flex; align-items: center; justify-content: center;
    }

    .map-head-icon ion-icon { font-size: 14px; color: var(--primary); }

    .map-head-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--text-900);
    }

    .radius-tag {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        background: var(--primary-soft);
        border: 1px solid var(--primary-mid);
        border-radius: 50px;
        font-size: 11px;
        font-weight: 700;
        color: var(--primary);
    }

    .radius-tag ion-icon { font-size: 12px; }

    #map {
        width: 100%;
        height: 180px;
    }

    /* ── Animations ── */
    @keyframes fadeSlide {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .pg-body > * {
        animation: fadeSlide 0.28s ease both;
    }
    .pg-body > *:nth-child(1) { animation-delay: 0.04s; }
    .pg-body > *:nth-child(2) { animation-delay: 0.08s; }
    .pg-body > *:nth-child(3) { animation-delay: 0.12s; }

    @supports (padding: max(0px)) {
        .pg-body { padding-bottom: max(100px, calc(env(safe-area-inset-bottom) + 100px)); }
        .pg-header { padding-top: max(16px, env(safe-area-inset-top)); }
    }
</style>

{{-- Leaflet CSS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

{{-- ── STICKY HEADER ── --}}
<div class="pg-header">
    <a href="{{ route('dashboard') }}" class="btn-back">
        <ion-icon name="chevron-back-outline"></ion-icon>
    </a>
    <div>
        <div class="pg-title">Scan QR Code</div>
        <span class="pg-sub">Absen menggunakan QR Code cabang</span>
    </div>
</div>

{{-- ── PAGE BODY ── --}}
<div class="pg-body">

    {{-- Instruction Banner --}}
    <div class="instruction-card">
        <ion-icon name="qr-code-outline"></ion-icon>
        <div class="instr-text">
            <strong>Cara Penggunaan</strong>
            Arahkan kamera ke QR Code yang tersedia di kantor cabang Anda. Pastikan GPS sudah aktif sebelum melakukan scan.
        </div>
    </div>

    {{-- QR Scanner Card --}}
    <div class="qr-card">
        <div class="qr-card-head">
            <div class="qr-card-icon">
                <ion-icon name="scan-outline"></ion-icon>
            </div>
            <div class="qr-card-title">Kamera Scanner</div>
            <div id="status-pill">
                <div id="status-dot"></div>
                <span id="status-label">Menunggu GPS…</span>
            </div>
        </div>

        <div class="qr-reader-wrap">
            <div id="reader"></div>
            {{-- QR corner frame --}}
            <div class="qr-frame-overlay">
                <div class="scan-line"></div>
                <div class="corner tl"></div>
                <div class="corner tr"></div>
                <div class="corner bl"></div>
                <div class="corner br"></div>
            </div>
        </div>
    </div>

    {{-- Map Card --}}
    <div class="map-card">
        <div class="map-card-head">
            <div class="map-head-left">
                <div class="map-head-icon">
                    <ion-icon name="location-outline"></ion-icon>
                </div>
                <div class="map-head-title">Lokasi Anda</div>
            </div>
            <div class="radius-tag">
                <ion-icon name="business-outline"></ion-icon>
                Radius: {{ $lok_kantor->radius_cabang ?? '0' }}m
            </div>
        </div>
        <div id="map"></div>
    </div>

</div>

{{-- Hidden inputs & audio --}}
<input type="hidden" id="lokasi">
<audio id="notifikasi_in"  src="{{ asset('assets/sound/notifikasi_in.mp3') }}"  preload="auto"></audio>
<audio id="notifikasi_out" src="{{ asset('assets/sound/notifikasi_out.mp3') }}" preload="auto"></audio>

@endsection

@push('myscript')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    var notifikasi_in  = document.getElementById('notifikasi_in');
    var notifikasi_out = document.getElementById('notifikasi_out');
    var isScanning = false;

    /* ── Status pill helpers ── */
    function setStatus(text, ready) {
        document.getElementById('status-label').textContent = text;
        var pill = document.getElementById('status-pill');
        if (ready) {
            pill.classList.add('ready');
        } else {
            pill.classList.remove('ready');
        }
    }

    /* ── Geolocation ── */
    if (!navigator.geolocation) {
        Swal.fire({ icon:'error', title:'GPS Tidak Didukung', text:'Browser Anda tidak mendukung geolocation.', confirmButtonColor:'#2563EB' });
    } else {
        navigator.geolocation.getCurrentPosition(successCallback, errorCallback, {
            enableHighAccuracy: true, timeout: 10000, maximumAge: 0
        });
    }

    function successCallback(position) {
        var lat = position.coords.latitude;
        var lng = position.coords.longitude;

        document.getElementById('lokasi').value = lat + ',' + lng;
        setStatus('Siap Scan', true);

        /* ── Leaflet Map ── */
        try {
            if (!window.leafletMap) {
                window.leafletMap = L.map('map', {
                    zoomControl: false,
                    attributionControl: false
                }).setView([lat, lng], 17);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 })
                 .addTo(window.leafletMap);

                /* User marker */
                var userIcon = L.divIcon({
                    className: '',
                    html: '<div style="background:#10B981;width:34px;height:34px;border-radius:50%;border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.25);display:flex;align-items:center;justify-content:center;"><ion-icon name="person" style="color:white;font-size:17px;"></ion-icon></div>',
                    iconSize: [34, 34], iconAnchor: [17, 17]
                });

                window.leafletMarker = L.marker([lat, lng], { icon: userIcon })
                    .addTo(window.leafletMap)
                    .bindPopup('<strong style="color:#10B981;">Lokasi Anda</strong>');

                /* Office marker */
                var lok_kantor = "{{ $lok_kantor->lokasi_cabang ?? '' }}";
                if (lok_kantor) {
                    var lok    = lok_kantor.split(',');
                    var latK   = parseFloat(lok[0]);
                    var lngK   = parseFloat(lok[1]);
                    var radius = {{ $lok_kantor->radius_cabang ?? 0 }};

                    L.circle([latK, lngK], {
                        color: '#2563EB', fillColor: '#2563EB', fillOpacity: 0.10,
                        radius: radius, weight: 2, dashArray: '5,5'
                    }).addTo(window.leafletMap);

                    var offIcon = L.divIcon({
                        className: '',
                        html: '<div style="background:#2563EB;width:34px;height:34px;border-radius:50%;border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.25);display:flex;align-items:center;justify-content:center;"><ion-icon name="business" style="color:white;font-size:17px;"></ion-icon></div>',
                        iconSize: [34, 34], iconAnchor: [17, 17]
                    });

                    var offMarker = L.marker([latK, lngK], { icon: offIcon })
                        .addTo(window.leafletMap)
                        .bindPopup('<strong style="color:#2563EB;">Kantor</strong><br><small>Radius: ' + radius + 'm</small>');

                    window.leafletMap.fitBounds(
                        L.featureGroup([window.leafletMarker, offMarker])
                         .getBounds().pad(0.15)
                    );
                }

                startQRScanner();
            } else {
                if (window.leafletMarker) {
                    window.leafletMarker.setLatLng([lat, lng]);
                    window.leafletMap.setView([lat, lng]);
                }
            }
        } catch (e) {
            console.error('Map error:', e);
        }
    }

    function errorCallback() {
        setStatus('GPS Gagal', false);
        Swal.fire({
            icon: 'error',
            title: 'Lokasi Tidak Terdeteksi',
            text: 'Aktifkan GPS dan beri izin browser untuk mengakses lokasi.',
            confirmButtonColor: '#2563EB'
        });
    }

    /* ── QR Scanner ── */
    function startQRScanner() {
        const html5QrCode = new Html5Qrcode('reader');
        const config = { fps: 10, qrbox: { width: 220, height: 220 } };

        html5QrCode.start(
            { facingMode: 'environment' },
            config,
            function (decodedText) {
                if (isScanning) return;
                isScanning = true;

                html5QrCode.stop().then(function () {
                    prosesAbsen(decodedText);
                }).catch(console.error);
            },
            function () { /* scan error — ignore */ }
        ).catch(function (err) {
            console.error('Scanner start error:', err);
        });
    }

    /* ── Submit absen ── */
    function prosesAbsen(qrCodeData) {
        var lokasi = document.getElementById('lokasi').value;

        $.ajax({
            type: 'POST',
            url: '{{ route("presensi.storeQr") }}',
            data: { _token: '{{ csrf_token() }}', qr_code: qrCodeData, lokasi: lokasi },
            cache: false,
            success: function (res) {
                if (res.success) {
                    (res.message.includes('Masuk') ? notifikasi_in : notifikasi_out).play();
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: res.message,
                        confirmButtonColor: '#2563EB',
                        timer: 3000,
                        showConfirmButton: false
                    }).then(function () {
                        window.location.href = '{{ route("dashboard") }}';
                    });
                } else {
                    Swal.fire({ icon:'error', title:'Gagal!', text: res.message, confirmButtonColor:'#2563EB' })
                       .then(function () { isScanning = false; startQRScanner(); });
                }
            },
            error: function () {
                Swal.fire({ icon:'error', title:'Kesalahan', text:'Terjadi kesalahan sistem. Coba lagi.', confirmButtonColor:'#2563EB' })
                   .then(function () { isScanning = false; startQRScanner(); });
            }
        });
    }
</script>
@endpush
