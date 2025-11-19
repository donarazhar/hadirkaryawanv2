@extends('karyawan.layouts.presensi')

@section('content')

<style>
    /* ===== MODERN PRESENSI PAGE ===== */
    :root {
        --primary: #0053C5;
        --primary-dark: #003d94;
        --primary-light: #2E7CE6;
        --primary-gradient: linear-gradient(135deg, #0053C5 0%, #2E7CE6 100%);
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
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
        padding: 24px 20px 80px 20px;
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
    }

    .btn-back ion-icon {
        font-size: 24px;
        color: white;
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
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 83, 197, 0.08);
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
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(0, 83, 197, 0.08);
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
        width: 100% !important;
        height: auto !important;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* ===== MAP SECTION ===== */
    .map-card {
        background: var(--bg-card);
        border-radius: 20px;
        padding: 16px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(0, 83, 197, 0.08);
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
        border: none;
        border-radius: 16px;
        font-size: 16px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
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
        background: linear-gradient(135deg, var(--success) 0%, #059669 100%);
        color: white;
    }

    .btn-pulang {
        background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);
        color: white;
    }

    /* ===== BUTTON FACE VERIFICATION (BARU) ===== */
    .btn-masuk-face {
        background: linear-gradient(135deg, #0053C5 0%, #003d94 100%);
        color: white;
        border: 2px solid rgba(16, 185, 129, 0.3);
    }

    .btn-pulang-face {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
        border: 2px solid rgba(239, 68, 68, 0.3);
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

<!-- Page Header -->
<div class="page-header">
    <div class="header-content">
        <a href="{{ route('dashboard') }}" class="btn-back">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
        <div class="header-title">
            <h1>{{ $cek > 0 ? 'Absen Pulang' : 'Absen Masuk' }}</h1>
            <p>{{ $namahari }}, {{ \Carbon\Carbon::parse($hariini)->isoFormat('D MMMM Y') }}</p>
        </div>
    </div>
</div>

<!-- Presensi Section -->
<div class="presensi-section">
    <!-- Info Card -->
    <div class="info-card">
        <div class="info-card-title">
            <ion-icon name="time-outline"></ion-icon>
            Jadwal Kerja Hari Ini
        </div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Shift</div>
                <div class="info-value">{{ $jamkerja->nama_jam_kerja }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Jam Kerja</div>
                <div class="info-value">{{ date('H:i', strtotime($jamkerja->jam_masuk)) }} - {{ date('H:i', strtotime($jamkerja->jam_pulang)) }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Waktu Absen</div>
                <div class="info-value">{{ date('H:i', strtotime($jamkerja->awal_jam_masuk)) }} - {{ date('H:i', strtotime($jamkerja->akhir_jam_masuk)) }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Status</div>
                <div class="info-value">
                    @if($cek > 0)
                    <span class="status-badge success">
                        <ion-icon name="checkmark-circle"></ion-icon>
                        Sudah Absen
                    </span>
                    @else
                    <span class="status-badge danger">
                        <ion-icon name="time"></ion-icon>
                        Belum Absen
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Webcam Card -->
    <div class="webcam-card">
        <div class="webcam-title">
            <ion-icon name="camera-outline"></ion-icon>
            Ambil Foto Selfie
        </div>
        <div class="webcam-capture"></div>
    </div>
</div>

<!-- Button Section -->
<div class="button-section">
    <input type="hidden" id="lokasi">

    @if($cek > 0)
    <!-- METODE LAMA: Absen Pulang Biasa -->
    <button id="takeabsen" class="btn-presensi btn-pulang">
        <ion-icon name="log-out-outline"></ion-icon>
        <span>Absen Pulang</span>
    </button>

    <!-- METODE BARU: Absen Pulang dengan Face Verification -->
    <button id="takeabsen-face" class="btn-presensi btn-pulang-face">
        <ion-icon name="scan-outline"></ion-icon>
        <span>Absen Pulang + Verifikasi Wajah</span>
    </button>
    @else
    <!-- METODE LAMA: Absen Masuk Biasa -->
    <button id="takeabsen" class="btn-presensi btn-masuk">
        <ion-icon name="log-in-outline"></ion-icon>
        <span>Absen Masuk</span>
    </button>

    <!-- METODE BARU: Absen Masuk dengan Face Verification -->
    <button id="takeabsen-face" class="btn-presensi btn-masuk-face">
        <ion-icon name="scan-outline"></ion-icon>
        <span>Absen Masuk + Verifikasi Wajah</span>
    </button>
    @endif
</div>

<!-- Map Section -->
<div class="map-section">
    <div class="map-card">
        <div class="map-title">
            <ion-icon name="location-outline"></ion-icon>
            Lokasi Anda
        </div>
        <div id="map"></div>
        <div class="location-info">
            <p>
                <ion-icon name="business-outline"></ion-icon>
                <strong>Radius Kantor:</strong> {{ $lok_kantor->radius_cabang }} meter
            </p>
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

        navigator.geolocation.getCurrentPosition(
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
            map = L.map('map').setView([latitude, longitude], 17);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

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

            // Fit bounds
            var group = L.featureGroup([marker, officeMarker, circle]);
            map.fitBounds(group.getBounds().pad(0.1));

            // Calculate distance
            var distance = calculateDistance(latitude, longitude, lat_kantor, long_kantor);
            console.log('Jarak dari kantor:', distance, 'meter');

            if (distance > radius) {
                console.warn('Diluar radius!', distance, '>', radius);
            }

            console.log('Map initialized successfully');
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

    // Get reference face descriptor from server
    async function getReferenceFaceDescriptor() {
        try {
            const response = await fetch('/face/descriptor', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const result = await response.json();

            if (result.success) {
                return new Float32Array(result.descriptor);
            } else {
                throw new Error(result.message);
            }
        } catch (error) {
            console.error('Error getting reference descriptor:', error);
            throw error;
        }
    }

    // Verify face from webcam
    async function verifyFace() {
        try {
            console.log('Starting face verification...');

            // Get current frame from webcam
            const video = document.querySelector('.webcam-capture video');

            if (!video) {
                throw new Error('Video element not found');
            }

            // Detect face in current frame
            const detection = await faceapi
                .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
                .withFaceLandmarks()
                .withFaceDescriptor();

            if (!detection) {
                throw new Error('Wajah tidak terdeteksi. Pastikan wajah Anda terlihat jelas di kamera.');
            }

            console.log('Face detected with confidence:', detection.detection.score);

            if (detection.detection.score < 0.5) {
                throw new Error('Deteksi wajah kurang jelas. Coba posisikan wajah lebih baik.');
            }

            // Get reference descriptor
            const referenceDescriptor = await getReferenceFaceDescriptor();

            // Calculate distance (similarity)
            const distance = faceapi.euclideanDistance(detection.descriptor, referenceDescriptor);
            console.log('Face distance:', distance);

            // Threshold: 0.6 (makin kecil makin mirip)
            const threshold = 0.6;

            if (distance > threshold) {
                throw new Error('Verifikasi wajah gagal. Wajah tidak cocok dengan data yang terdaftar.');
            }

            console.log('Face verification SUCCESS! Distance:', distance);
            return true;

        } catch (error) {
            console.error('Face verification error:', error);
            throw error;
        }
    }

    // ===== METODE BARU: Take Attendance WITH Face Verification =====
    $("#takeabsen-face").click(async function(e) {
        e.preventDefault();

        console.log('Take attendance with face verification clicked');

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

        try {
            // Show loading for face verification
            Swal.fire({
                title: 'Memverifikasi Wajah...',
                html: 'Mohon tunggu, sedang memverifikasi identitas Anda',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Load models if not loaded yet
            if (!modelsLoaded) {
                const loaded = await loadFaceModels();
                if (!loaded) {
                    throw new Error('Gagal memuat model face recognition');
                }
            }

            // Verify face
            await verifyFace();

            // Close loading
            Swal.close();

            // Show success message
            await Swal.fire({
                icon: 'success',
                title: 'Verifikasi Berhasil!',
                text: 'Wajah Anda terverifikasi. Melanjutkan presensi...',
                timer: 2000,
                showConfirmButton: false,
                confirmButtonColor: '#0053C5'
            });

            // Continue with normal attendance process
            $("#loading-overlay").addClass('show');

            // Capture photo
            Webcam.snap(function(uri) {
                console.log('Photo captured after face verification');
                var image = uri;

                $.ajax({
                    type: 'POST',
                    url: '/presensi/store',
                    data: {
                        _token: "{{ csrf_token() }}",
                        image: image,
                        lokasi: lokasi_val,
                        verified: true // Flag bahwa ini menggunakan verifikasi wajah
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
                                html: '<strong>' + status[1] + '</strong><br><small>✅ Terverifikasi dengan Face Recognition</small>',
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

        } catch (error) {
            Swal.close();
            $("#loading-overlay").removeClass('show');

            console.error('Face verification failed:', error);

            Swal.fire({
                icon: 'error',
                title: 'Verifikasi Gagal',
                html: error.message || 'Verifikasi wajah gagal. Pastikan:<br>- Wajah Anda sudah terdaftar di sistem<br>- Wajah terlihat jelas di kamera<br>- Pencahayaan cukup',
                confirmButtonColor: '#0053C5'
            });
        }
    });

    // Preload face-api models saat halaman dimuat (optional)
    $(document).ready(function() {
        console.log('Page ready - Preloading face-api models...');
        loadFaceModels();
    });
</script>
@endpush