@extends('karyawan.layouts.presensi')

@section('content')

<style>
    /* ===== MODERN PRESENSI PAGE ===== */
    :root {
        --primary: #0053C5;
        --primary-dark: #003d94;
        --primary-light: #2E7CE6;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --bg-main: #f8fafc;
        --bg-card: #ffffff;
        --text-primary: #0f172a;
        --text-secondary: #64748b;
    }

    body {
        background: var(--bg-main);
    }

    /* ===== PAGE HEADER ===== */
    .page-header {
        background: white;
        padding: 24px 20px 80px 20px;
        position: relative;
        overflow: hidden;
        margin: 0;
        border-bottom: 1px solid rgba(0, 83, 197, 0.08);
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
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-back ion-icon {
        font-size: 24px;
        color: var(--text-secondary);
    }

    .btn-back:active, .btn-back:hover {
        background: #e2e8f0;
        color: var(--primary);
    }

    .btn-back:hover ion-icon {
        color: var(--primary);
    }

    .header-title h1 {
        font-size: 22px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 4px 0;
    }

    .header-title p {
        font-size: 12px;
        font-weight: 600;
        color: var(--primary);
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* ===== INFO CARD ===== */
    .presensi-section {
        padding: 0 20px;
        margin-top: -65px;
        margin-bottom: 20px;
        position: relative;
        z-index: 10;
    }

    .info-card {
        background: var(--bg-card);
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0, 83, 197, 0.05);
        margin-bottom: 16px;
    }

    .info-card-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-card-title ion-icon {
        font-size: 20px;
        color: var(--primary);
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .info-item {
        padding: 12px;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 12px;
    }

    .info-label {
        font-size: 11px;
        color: var(--text-secondary);
        font-weight: 600;
        margin-bottom: 4px;
    }

    .info-value {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-primary);
    }

    /* ===== WEBCAM SECTION ===== */
    .webcam-card {
        background: var(--bg-card);
        border-radius: 20px;
        padding: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0, 83, 197, 0.05);
        margin-bottom: 16px;
    }

    .webcam-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .webcam-title ion-icon {
        font-size: 20px;
        color: var(--primary);
    }

    .webcam-capture,
    .webcam-capture video {
        position: fixed !important;
        top: 0;
        left: 0;
        width: 100vw !important;
        height: 100vh !important;
        object-fit: cover !important;
        border-radius: 0;
        overflow: hidden;
        box-shadow: none;
        z-index: 0;
    }

    /* ===== MAP SECTION ===== */
    .map-card {
        background: var(--bg-card);
        border-radius: 20px;
        padding: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0, 83, 197, 0.05);
        margin-bottom: 16px;
    }

    .map-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .map-title ion-icon {
        font-size: 20px;
        color: var(--primary);
    }

    #map {
        height: 250px;
        border-radius: 12px;
        overflow: hidden;
    }

    .location-info {
        margin-top: 12px;
        padding: 12px;
        background: linear-gradient(135deg, rgba(0, 83, 197, 0.05) 0%, rgba(46, 124, 230, 0.05) 100%);
        border-radius: 10px;
        border: 1px solid rgba(0, 83, 197, 0.2);
    }

    .location-info p {
        margin: 0;
        font-size: 12px;
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .location-info ion-icon {
        font-size: 16px;
        color: var(--primary);
    }

    .location-info strong {
        color: var(--primary);
        font-weight: 600;
    }

    /* ===== BUTTON ABSEN ===== */
    .button-section {
        padding: 0 20px 20px;
    }

    .btn-presensi {
        width: 100%;
        padding: 16px 20px;
        border-radius: 16px;
        font-size: 16px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 12px;
    }

    .btn-presensi ion-icon {
        font-size: 24px;
    }

    .btn-presensi:active {
        transform: scale(0.98);
    }

    .btn-presensi:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .btn-masuk {
        background: white;
        color: var(--success);
        border: 1px solid var(--success);
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.05);
    }

    .btn-masuk:active {
        background: var(--success);
        color: white;
    }

    .btn-pulang {
        background: white;
        color: var(--danger);
        border: 1px solid var(--danger);
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.05);
    }

    .btn-pulang:active {
        background: var(--danger);
        color: white;
    }

    /* ===== BUTTON FACE VERIFICATION (BARU) ===== */
    .btn-masuk-face {
        background: white;
        color: #0053C5;
        border: 1px solid #0053C5;
        box-shadow: 0 2px 8px rgba(0, 83, 197, 0.05);
    }

    .btn-masuk-face:active {
        background: #0053C5;
        color: white;
    }

    .btn-pulang-face {
        background: white;
        color: #8b5cf6;
        border: 1px solid #8b5cf6;
        box-shadow: 0 2px 8px rgba(139, 92, 246, 0.05);
    }

    .btn-pulang-face:active {
        background: #8b5cf6;
        color: white;
    }

    /* ===== STATUS BADGE ===== */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-badge.success {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }

    .status-badge.danger {
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger);
    }

    /* ===== LOADING STATE ===== */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }

    .loading-overlay.show {
        display: flex;
    }

    .loading-content {
        background: white;
        padding: 30px;
        border-radius: 20px;
        text-align: center;
    }

    .loading-spinner {
        width: 50px;
        height: 50px;
        border: 4px solid #f3f3f3;
        border-top: 4px solid var(--primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 15px;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 375px) {

        .presensi-section,
        .button-section {
            padding-left: 16px;
            padding-right: 16px;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<!-- Full-screen Background Camera -->
<div class="webcam-capture"></div>

<!-- Floating Back Button -->
<a href="{{ route('dashboard') }}" class="btn-back-floating" style="position: fixed; top: 20px; left: 20px; width: 40px; height: 40px; background: rgba(0,0,0,0.5); backdrop-filter: blur(5px); border-radius: 12px; display: flex; align-items: center; justify-content: center; z-index: 20; border: 1px solid rgba(255,255,255,0.3); color: white; text-decoration: none;">
    <ion-icon name="chevron-back-outline" style="font-size: 24px;"></ion-icon>
</a>

<!-- Floating Schedule Text -->
<div class="schedule-floating-text" style="position: fixed; top: 20px; right: 20px; z-index: 20; color: white; text-shadow: 0px 1px 3px rgba(0,0,0,0.8); background: rgba(0,0,0,0.5); backdrop-filter: blur(5px); padding: 10px; border-radius: 12px; text-align: right; border: 1px solid rgba(255,255,255,0.2);">
    <h1 style="font-size: 14px; font-weight: 700; margin: 0 0 2px 0; color: white;">{{ $cek > 0 ? 'Absen Pulang' : 'Absen Masuk' }}</h1>
    <p style="font-size: 10px; font-weight: 500; margin: 0 0 4px 0; opacity: 0.9;">{{ $namahari }}, {{ \Carbon\Carbon::parse($hariini)->isoFormat('D MMMM Y') }}</p>
    
    <div style="font-size: 10px; line-height: 1.4; font-weight: 500;">
        @if(isset($is_multi_shift) && $is_multi_shift)
            Shift: {{ $current_shift->nama_shift }}<br>
            ({{ date('H:i', strtotime($current_shift->jam_masuk)) }} - {{ date('H:i', strtotime($current_shift->jam_pulang)) }})<br>
        @else
            Shift: {{ $jamkerja->nama_jam_kerja }}<br>
            ({{ date('H:i', strtotime($jamkerja->jam_masuk)) }} - {{ date('H:i', strtotime($jamkerja->jam_pulang)) }})<br>
        @endif
        @if($cek > 0)
            <span style="color: #4ade80; font-weight: bold;">Sudah Absen</span>
        @else
            <span style="color: #f87171; font-weight: bold;">Belum Absen</span>
        @endif
    </div>
</div>

<!-- Shift Selection Form (if multi-shift) -->
@if(isset($is_multi_shift) && $is_multi_shift)
<div style="position: fixed; top: 130px; left: 20px; right: 20px; z-index: 20;">
    <form action="{{ route('presensi.create') }}" method="GET" id="shift-form" style="position: relative;">
        <select name="shift_ke" id="shift_ke" onchange="document.getElementById('shift-form').submit()" style="width: 100%; appearance: none; -webkit-appearance: none; height: auto; min-height: 48px; padding: 12px 40px 12px 16px; background: rgba(0,0,0,0.6); backdrop-filter: blur(10px); color: white; border: 1px solid rgba(255,255,255,0.4); border-radius: 12px; font-size: 13px; font-weight: 600; outline: none; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
            @foreach($shifts_available as $s)
                <option style="color: #0f172a; font-weight: 500;" value="{{ $s->shift_ke }}" {{ $shift_ke == $s->shift_ke ? 'selected' : '' }}>
                    Shift {{ $s->shift_ke }} - {{ $s->nama_shift }} ({{ date('H:i', strtotime($s->jam_masuk)) }} s/d {{ date('H:i', strtotime($s->jam_pulang)) }})
                </option>
            @endforeach
        </select>
        <ion-icon name="chevron-down-outline" style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); color: white; font-size: 18px; pointer-events: none;"></ion-icon>
    </form>
</div>
@endif

<!-- Floating Map -->
<div class="map-floating" style="position: fixed; bottom: 90px; left: 0; right: 0; width: 100vw; height: 120px; border-radius: 0; overflow: hidden; border-top: 2px solid rgba(255,255,255,0.5); border-bottom: 2px solid rgba(255,255,255,0.5); box-shadow: 0 4px 15px rgba(0,0,0,0.3); z-index: 20;">
    <div id="map" style="width: 100%; height: 100%;"></div>
    <!-- Radius Kantor Info inside Map -->
    <div style="position: absolute; bottom: 8px; right: 8px; z-index: 1000; background: rgba(0,0,0,0.6); backdrop-filter: blur(5px); padding: 4px 8px; border-radius: 8px; font-size: 10px; color: white; border: 1px solid rgba(255,255,255,0.2);">
        Radius: {{ $lok_kantor->radius_cabang }}m
    </div>
</div>

<!-- Button Section (Hidden inputs & Auto-scan indicator) -->
<div class="button-section">
    <input type="hidden" id="lokasi">

    @if(isset($is_multi_shift) && $is_multi_shift)
    <input type="hidden" id="shift_ke_val" value="{{ $shift_ke }}">
    <input type="hidden" id="shift_nama_val" value="{{ $current_shift->nama_shift }}">
    <input type="hidden" id="shift_jam_masuk_val" value="{{ $current_shift->jam_masuk }}">
    <input type="hidden" id="shift_jam_pulang_val" value="{{ $current_shift->jam_pulang }}">
    @else
    <input type="hidden" id="shift_ke_val" value="">
    <input type="hidden" id="shift_nama_val" value="">
    <input type="hidden" id="shift_jam_masuk_val" value="">
    <input type="hidden" id="shift_jam_pulang_val" value="">
    @endif

    <!-- Auto-Scan Status Indicator -->
    <div id="auto-scan-status" style="position: fixed; top: 70px; left: 20px; background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(5px); border: 1px solid rgba(255,255,255,0.2); border-radius: 12px; padding: 6px 10px; color: white; display: flex; align-items: center; gap: 8px; z-index: 20;">
        <div class="spinner-border spinner-border-sm" role="status" style="width: 1rem; height: 1rem; border-width: 0.15em; color: #10b981;"></div>
        <div style="display: flex; flex-direction: column; text-align: left;">
            <span style="color: #10b981; font-size: 11px; font-weight: 600; line-height: 1;">Auto-Scan Aktif</span>
            <small style="color: #cbd5e1; font-size: 9px; line-height: 1; margin-top: 2px;">Arahkan & Tahan Posisi...</small>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loading-overlay">
    <div class="loading-content">
        <div class="loading-spinner"></div>
        <p style="margin: 0; color: var(--text-primary); font-weight: 600;">Memproses presensi...</p>
    </div>
</div>

<!-- Audio Notifications -->
<audio id="notifikasi_in" src="{{ asset('assets/sound/notifikasi_in.mp3') }}"></audio>
<audio id="notifikasi_out" src="{{ asset('assets/sound/notifikasi_out.mp3') }}"></audio>
<audio id="radius_sound" src="{{ asset('assets/sound/radius_sound.mp3') }}"></audio>

@endsection

@push('myscript')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<!-- Webcam JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
<!-- Face-API.js -->
<script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.js"></script>

<script>
    console.log('Script started');

    var notifikasi_in = document.getElementById('notifikasi_in');
    var notifikasi_out = document.getElementById('notifikasi_out');
    var radius_sound = document.getElementById('radius_sound');
    var map;
    var marker;
    var circle;
    var webcamReady = false;

    // Face Recognition Variables
    var modelsLoaded = false;
    var faceDescriptor = null;

    // Check if Webcam library loaded
    if (typeof Webcam === 'undefined') {
        console.error('Webcam.js not loaded!');
        Swal.fire({
            icon: 'error',
            title: 'Library Error',
            text: 'Webcam library tidak ter-load. Refresh halaman.',
            confirmButtonColor: '#0053C5'
        });
    } else {
        console.log('Webcam.js loaded successfully');

        // Initialize Webcam
        try {
            Webcam.set({
                width: 640,
                height: 480,
                image_format: 'jpeg',
                jpeg_quality: 90,
                flip_horiz: true,
                constraints: {
                    video: true,
                    facingMode: "user"
                }
            });

            Webcam.attach('.webcam-capture');

            // Wait for camera ready
            Webcam.on('live', function() {
                console.log('Camera is live');
                webcamReady = true;
            });

            Webcam.on('error', function(err) {
                console.error('Webcam error:', err);
                Swal.fire({
                    icon: 'error',
                    title: 'Kamera Error',
                    html: 'Tidak dapat mengakses kamera.<br>Pastikan:<br>- Izinkan akses kamera<br>- Gunakan HTTPS<br>- Browser mendukung camera API',
                    confirmButtonColor: '#0053C5'
                });
            });
        } catch (e) {
            console.error('Webcam initialization error:', e);
        }
    }

    // Check if Leaflet loaded
    if (typeof L === 'undefined') {
        console.error('Leaflet.js not loaded!');
        Swal.fire({
            icon: 'error',
            title: 'Maps Error',
            text: 'Maps library tidak ter-load. Refresh halaman.',
            confirmButtonColor: '#0053C5'
        });
    } else {
        console.log('Leaflet.js loaded successfully');
    }

    // Get User Location
    var lokasi = document.getElementById('lokasi');

    if (!navigator.geolocation) {
        console.error('Geolocation not supported');
        Swal.fire({
            icon: 'error',
            title: 'GPS Tidak Didukung',
            text: 'Browser Anda tidak mendukung GPS. Gunakan browser yang lebih modern.',
            confirmButtonColor: '#0053C5'
        });
    } else {
        console.log('Requesting geolocation...');

        navigator.geolocation.watchPosition(
            successCallback,
            errorCallback, {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    }

    function successCallback(position) {
        console.log('Geolocation success:', position);

        var latitude = position.coords.latitude;
        var longitude = position.coords.longitude;

        lokasi.value = latitude + "," + longitude;
        console.log('Location set:', lokasi.value);

        // Initialize Map
        try {
            if (!map) {
                map = L.map('map').setView([latitude, longitude], 17);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap'
                }).addTo(map);

                // Office Location
                var lok_kantor = "{{ $lok_kantor->lokasi_cabang }}";
                var lok = lok_kantor.split(",");
                var lat_kantor = parseFloat(lok[0]);
                var long_kantor = parseFloat(lok[1]);
                var radius = {{ $lok_kantor->radius_cabang }};

                console.log('Office location:', lat_kantor, long_kantor, 'Radius:', radius);

                // Office Circle
                circle = L.circle([lat_kantor, long_kantor], {
                    color: '#0053C5',
                    fillColor: '#0053C5',
                    fillOpacity: 0.15,
                    radius: radius,
                    weight: 2,
                    dashArray: '5, 5'
                }).addTo(map);

                // Office Marker
                var officeIcon = L.divIcon({
                    className: 'custom-div-icon',
                    html: '<div style="background: linear-gradient(135deg, #0053C5 0%, #003d94 100%); width: 40px; height: 40px; border-radius: 50%; border: 4px solid white; box-shadow: 0 3px 10px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center;"><ion-icon name="business" style="color: white; font-size: 22px;"></ion-icon></div>',
                    iconSize: [40, 40],
                    iconAnchor: [20, 20]
                });

                var officeMarker = L.marker([lat_kantor, long_kantor], {
                    icon: officeIcon
                }).addTo(map);
                officeMarker.bindPopup('<strong style="color: #0053C5;">Kantor</strong><br><small>Radius: ' + radius + 'm</small>');
            }

            if (marker) {
                marker.setLatLng([latitude, longitude]);
                map.setView([latitude, longitude]);
            } else {
                // User Marker
                var userIcon = L.divIcon({
                    className: 'custom-div-icon',
                    html: '<div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); width: 40px; height: 40px; border-radius: 50%; border: 4px solid white; box-shadow: 0 3px 10px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center;"><ion-icon name="person" style="color: white; font-size: 22px;"></ion-icon></div>',
                    iconSize: [40, 40],
                    iconAnchor: [20, 20]
                });

                marker = L.marker([latitude, longitude], {
                    icon: userIcon
                }).addTo(map);
                marker.bindPopup('<strong style="color: #10b981;">Lokasi Anda</strong>').openPopup();
            }

            // Calculate distance
            var lok_kantor_str = "{{ $lok_kantor->lokasi_cabang }}";
            var lok_arr = lok_kantor_str.split(",");
            var lat_kntr = parseFloat(lok_arr[0]);
            var long_kntr = parseFloat(lok_arr[1]);
            var rad = {{ $lok_kantor->radius_cabang }};
            
            var distance = calculateDistance(latitude, longitude, lat_kntr, long_kntr);
            console.log('Jarak dari kantor:', distance, 'meter');

            if (distance > rad) {
                console.warn('Diluar radius!', distance, '>', rad);
            }

            console.log('Map updated successfully');
        } catch (e) {
            console.error('Map initialization error:', e);
            Swal.fire({
                icon: 'error',
                title: 'Maps Error',
                text: 'Gagal menginisialisasi peta: ' + e.message,
                confirmButtonColor: '#0053C5'
            });
        }
    }

    function errorCallback(error) {
        console.error('Geolocation error:', error);

        var errorMsg = '';
        switch (error.code) {
            case error.PERMISSION_DENIED:
                errorMsg = 'Izin lokasi ditolak. Aktifkan GPS di pengaturan browser.';
                break;
            case error.POSITION_UNAVAILABLE:
                errorMsg = 'Informasi lokasi tidak tersedia.';
                break;
            case error.TIMEOUT:
                errorMsg = 'Request lokasi timeout. Coba lagi.';
                break;
            default:
                errorMsg = 'Error mendapatkan lokasi: ' + error.message;
        }

        Swal.fire({
            icon: 'error',
            title: 'Lokasi Tidak Terdeteksi',
            html: errorMsg + '<br><br><small>Pastikan GPS aktif dan browser memiliki izin lokasi.</small>',
            confirmButtonColor: '#0053C5'
        });
    }

    // Calculate distance
    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371e3;
        const φ1 = lat1 * Math.PI / 180;
        const φ2 = lat2 * Math.PI / 180;
        const Δφ = (lat2 - lat1) * Math.PI / 180;
        const Δλ = (lon2 - lon1) * Math.PI / 180;

        const a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
            Math.cos(φ1) * Math.cos(φ2) *
            Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

        return Math.round(R * c);
    }

    // ===== METODE LAMA: Take Attendance (Tanpa Face Verification) =====
    $("#takeabsen").click(function(e) {
        e.preventDefault();

        console.log('Take attendance clicked (No Face Verification)');

        // Validate location
        var lokasi_val = $("#lokasi").val();
        if (!lokasi_val) {
            Swal.fire({
                icon: 'error',
                title: 'Lokasi Belum Terdeteksi',
                text: 'Mohon tunggu hingga lokasi Anda terdeteksi',
                confirmButtonColor: '#0053C5'
            });
            return;
        }

        // Validate webcam
        if (!webcamReady) {
            Swal.fire({
                icon: 'error',
                title: 'Kamera Belum Siap',
                text: 'Mohon tunggu hingga kamera aktif',
                confirmButtonColor: '#0053C5'
            });
            return;
        }

        // Show loading
        $("#loading-overlay").addClass('show');

        // Capture photo
        Webcam.snap(function(uri) {
            console.log('Photo captured');
            var image = uri;

            $.ajax({
                type: 'POST',
                url: '/presensi/store',
                data: {
                    _token: "{{ csrf_token() }}",
                    image: image,
                    lokasi: lokasi_val
                },
                cache: false,
                success: function(respond) {
                    $("#loading-overlay").removeClass('show');
                    console.log('Response:', respond);

                    var status = respond.split("|");

                    if (status[0] == "success") {
                        if (status[2] == "in") {
                            notifikasi_in.play();
                        } else {
                            notifikasi_out.play();
                        }

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: status[1],
                            confirmButtonColor: '#0053C5',
                            timer: 3000
                        }).then(() => {
                            window.location.href = '/dashboard';
                        });
                    } else {
                        if (status[2] == "radius") {
                            radius_sound.play();
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: status[1],
                            confirmButtonColor: '#0053C5'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    $("#loading-overlay").removeClass('show');
                    console.error('AJAX Error:', error);

                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Gagal mengirim data presensi. Silakan coba lagi.',
                        confirmButtonColor: '#0053C5'
                    });
                }
            });
        });
    });

    // ===== FACE-API.js Functions =====

    // Load Face-API models
    async function loadFaceModels() {
        if (modelsLoaded) return true;

        try {
            console.log('Loading face-api models...');

            const MODEL_URL = 'https://cdn.jsdelivr.net/npm/@vladmandic/face-api/model';

            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
                faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
                faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
            ]);

            modelsLoaded = true;
            console.log('Face-API models loaded successfully');
            return true;
        } catch (error) {
            console.error('Error loading face models:', error);
            return false;
        }
    }

    let cachedReferenceDescriptor = null;
    // Get reference face descriptor from server
    async function getReferenceFaceDescriptor() {
        if (cachedReferenceDescriptor) return cachedReferenceDescriptor;

        try {
            const response = await fetch('/face/descriptor', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const result = await response.json();

            if (result.success) {
                cachedReferenceDescriptor = new Float32Array(result.descriptor);
                return cachedReferenceDescriptor;
            } else {
                throw new Error(result.message);
            }
        } catch (error) {
            console.error('Error getting reference descriptor:', error);
            throw error;
        }
    }

    // Verify face from webcam SILENTLY for auto-scan
    async function verifyFaceSilent() {
        try {
            const video = document.querySelector('.webcam-capture video');
            if (!video) return { matched: false };

            // Ekstrak descriptor langsung
            const descriptorDetection = await faceapi
                .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
                .withFaceLandmarks()
                .withFaceDescriptor();

            if (descriptorDetection && descriptorDetection.detection.score >= 0.5) {
                const referenceDescriptor = await getReferenceFaceDescriptor();
                const distance = faceapi.euclideanDistance(descriptorDetection.descriptor, referenceDescriptor);
                
                const threshold = 0.6;
                if (distance <= threshold) {
                    console.log('Face matched automatically! Distance:', distance);
                    return { matched: true, descriptor: Array.from(descriptorDetection.descriptor) };
                } else {
                    $("#auto-scan-status").html(`
                        <ion-icon name="close-circle-outline" style="font-size: 18px; color: #ef4444;"></ion-icon>
                        <div style="display: flex; flex-direction: column; text-align: left;">
                            <span style="color: #ef4444; font-size: 11px; font-weight: 600; line-height: 1;">Wajah Tidak Cocok</span>
                            <small style="color: #cbd5e1; font-size: 9px; line-height: 1; margin-top: 2px;">Silakan coba lagi</small>
                        </div>
                    `);
                }
            }

            return { matched: false };
        } catch (error) {
            console.error('Silent face verification error:', error);
            return { matched: false };
        }
    }

    // Auto Verification Loop
    var autoScanInterval = null;
    var isProcessing = false;

    function startAutoVerification() {
        if (autoScanInterval) clearInterval(autoScanInterval);
        
        console.log('Starting Auto-Scan Loop...');
        
        autoScanInterval = setInterval(async function() {
            if (isProcessing) return; // Jangan scan jika sedang proses AJAX
            if (!webcamReady) return; // Jangan scan jika kamera belum live
            if (!modelsLoaded) return; // Jangan scan jika AI belum siap
            
            var lokasi_val = $("#lokasi").val();
            if (!lokasi_val) return; // Jangan scan jika lokasi belum terdeteksi

            // Mulai scan silent
            var result = await verifyFaceSilent();
            
            if (result.matched) {
                // Wajah cocok! Hentikan loop sementara.
                isProcessing = true;
                clearInterval(autoScanInterval);
                
                // Ubah status UI
                $("#auto-scan-status").html(`
                    <ion-icon name="checkmark-circle" style="font-size: 18px; color: #10b981;"></ion-icon>
                    <div style="display: flex; flex-direction: column; text-align: left;">
                        <span style="color: #10b981; font-size: 11px; font-weight: 600; line-height: 1;">Wajah Cocok!</span>
                        <small style="color: #cbd5e1; font-size: 9px; line-height: 1; margin-top: 2px;">Menyimpan...</small>
                    </div>
                `);
                
                $("#auto-scan-status").css({
                    'background': 'rgba(16, 185, 129, 0.2)',
                    'border-color': '#10b981'
                });

                $("#loading-overlay").addClass('show');

                // Lakukan presensi AJAX
                Webcam.snap(function(uri) {
                    var image = uri;
                    var shift_ke_val = $("#shift_ke_val").val();
                    var shift_nama_val = $("#shift_nama_val").val();
                    var shift_jam_masuk_val = $("#shift_jam_masuk_val").val();
                    var shift_jam_pulang_val = $("#shift_jam_pulang_val").val();

                    $.ajax({
                        type: 'POST',
                        url: '/presensi/store',
                        data: {
                            _token: "{{ csrf_token() }}",
                            image: image,
                            lokasi: lokasi_val,
                            verified: true,
                            face_descriptor: JSON.stringify(result.descriptor),
                            shift_ke: shift_ke_val,
                            shift_nama: shift_nama_val,
                            shift_jam_masuk: shift_jam_masuk_val,
                            shift_jam_pulang: shift_jam_pulang_val
                        },
                        cache: false,
                        success: function(respond) {
                            $("#loading-overlay").removeClass('show');
                            var status = respond.split("|");

                            if (status[0] == "success") {
                                if (status[2] == "in") {
                                    notifikasi_in.play();
                                } else {
                                    notifikasi_out.play();
                                }

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    html: '<strong>' + status[1] + '</strong><br><small>✅ Terverifikasi Otomatis</small>',
                                    confirmButtonColor: '#0053C5',
                                    timer: 3000,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.href = '/dashboard';
                                });
                            } else {
                                if (status[2] == "radius") {
                                    radius_sound.play();
                                }

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: status[1],
                                    confirmButtonColor: '#0053C5'
                                }).then(() => {
                                    // Reset UI dan jalankan loop auto-scan lagi
                                    resetAutoScanUI();
                                    isProcessing = false;
                                    startAutoVerification();
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            $("#loading-overlay").removeClass('show');
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: 'Gagal mengirim data presensi. Silakan coba lagi.',
                                confirmButtonColor: '#0053C5'
                            }).then(() => {
                                resetAutoScanUI();
                                isProcessing = false;
                                startAutoVerification();
                            });
                        }
                    });
                });
            }
        }, 1500); // Scan tiap 1.5 detik
    }

    function resetAutoScanUI() {
        $("#auto-scan-status").html(`
            <div class="spinner-border spinner-border-sm" role="status" style="width: 1rem; height: 1rem; border-width: 0.15em; color: #10b981;"></div>
            <div style="display: flex; flex-direction: column; text-align: left;">
                <span style="color: #10b981; font-size: 11px; font-weight: 600; line-height: 1;">Auto-Scan Aktif</span>
                <small style="color: #cbd5e1; font-size: 9px; line-height: 1; margin-top: 2px;">Arahkan wajah & Tahan</small>
            </div>
        `);
        $("#auto-scan-status").css({
            'background': 'rgba(0, 0, 0, 0.6)',
            'border-color': 'rgba(255,255,255,0.2)',
            'color': 'white'
        });
    }

    // Preload face-api models saat halaman dimuat dan jalankan auto-scan
    $(document).ready(async function() {
        console.log('Page ready - Preloading face-api models...');
        await loadFaceModels();
        // Mulai auto verification setelah model di-load
        startAutoVerification();
    });
</script>
@endpush