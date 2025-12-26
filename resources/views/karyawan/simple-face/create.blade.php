@extends('karyawan.layouts.simple-face')

@section('content')

<style>
    body {
        background: #000;
        padding: 0 !important;
        margin: 0;
        overflow-y: auto;
        padding-bottom: 120px !important;
    }

    .page-container {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    /* ===== CAMERA SECTION ===== */
    .camera-section {
        position: relative;
        width: 100%;
        height: 60vh;
        background: #000;
    }

    .camera-header {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        z-index: 100;
        padding: 16px 20px;
        background: linear-gradient(to bottom, rgba(0, 0, 0, 0.8) 0%, transparent 100%);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .btn-back {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s ease;
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.05);
    }

    .btn-back ion-icon {
        font-size: 24px;
        color: white;
    }

    .header-info {
        text-align: center;
        flex: 1;
    }

    .header-info h3 {
        font-size: 16px;
        font-weight: 700;
        color: white;
        margin: 0 0 4px 0;
    }

    .header-info p {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.8);
        margin: 0;
    }

    /* Shift Info Badge */
    .shift-info-badge {
        position: absolute;
        top: 70px;
        left: 20px;
        right: 20px;
        z-index: 100;
    }

    .shift-badge-content {
        background: linear-gradient(135deg, #0053C5 0%, #003d94 100%);
        backdrop-filter: blur(10px);
        padding: 12px 16px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 4px 16px rgba(0, 83, 197, 0.5);
    }

    .shift-icon-badge {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .shift-icon-badge span {
        font-size: 20px;
        font-weight: 700;
        color: white;
    }

    .shift-details {
        flex: 1;
    }

    .shift-title {
        font-size: 14px;
        font-weight: 700;
        color: white;
        margin-bottom: 2px;
    }

    .shift-time-range {
        font-size: 11px;
        color: rgba(255, 255, 255, 0.8);
    }

    .camera-container {
        width: 100%;
        height: 100%;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .webcam-capture,
    .webcam-capture video {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover;
    }

    .face-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 200px;
        height: 240px;
        border: 4px solid #0053C5;
        border-radius: 50%;
        pointer-events: none;
        animation: pulse 2s infinite;
        box-shadow: 0 0 30px rgba(0, 83, 197, 0.6);
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 0.6;
            transform: translate(-50%, -50%) scale(1);
        }

        50% {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1.03);
        }
    }

    .instruction-overlay {
        position: absolute;
        bottom: 20px;
        left: 0;
        right: 0;
        text-align: center;
        padding: 0 20px;
        z-index: 50;
    }

    .instruction-box {
        display: inline-block;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(10px);
        padding: 10px 20px;
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .instruction-box p {
        font-size: 12px;
        color: white;
        margin: 0;
        font-weight: 600;
    }

    /* ===== INFO SECTION (Blue Theme) ===== */
    .info-section {
        flex: 1;
        background: linear-gradient(135deg, #0053C5 0%, #003d94 100%);
        padding: 20px;
        padding-bottom: 20px;
    }

    .info-card {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        padding: 16px;
        margin-bottom: 16px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    }

    .info-title {
        font-size: 14px;
        font-weight: 700;
        color: white;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-title ion-icon {
        font-size: 20px;
    }

    .map-container {
        height: 180px;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 12px;
    }

    #map {
        height: 100%;
        width: 100%;
    }

    .location-info {
        padding: 10px 12px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .location-info p {
        margin: 0;
        font-size: 12px;
        color: rgba(255, 255, 255, 0.9);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .location-info ion-icon {
        font-size: 16px;
        color: white;
    }

    /* Status Info Cards */
    .status-info-cards {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 16px;
    }

    .status-info-item {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        padding: 14px;
        text-align: center;
    }

    .status-icon-small {
        width: 36px;
        height: 36px;
        margin: 0 auto 8px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .status-icon-small ion-icon {
        font-size: 20px;
        color: white;
    }

    .status-label-small {
        font-size: 11px;
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 4px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-value-small {
        font-size: 16px;
        font-weight: 700;
        color: white;
    }

    /* Tips Card */
    .tips-card {
        background: rgba(16, 185, 129, 0.2);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(16, 185, 129, 0.3);
        border-radius: 16px;
        padding: 14px;
    }

    .tips-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px;
    }

    .tips-icon {
        width: 28px;
        height: 28px;
        background: rgba(16, 185, 129, 0.3);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .tips-icon ion-icon {
        font-size: 16px;
        color: #10b981;
    }

    .tips-title {
        font-size: 13px;
        font-weight: 700;
        color: white;
    }

    .tips-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .tips-list li {
        font-size: 11px;
        color: rgba(255, 255, 255, 0.9);
        padding-left: 16px;
        position: relative;
        margin-bottom: 6px;
    }

    .tips-list li:before {
        content: "✓";
        position: absolute;
        left: 0;
        color: #10b981;
        font-weight: 700;
    }

    .tips-list li:last-child {
        margin-bottom: 0;
    }

    /* ===== CAPTURE BUTTON ===== */
    .capture-button-container {
        position: fixed !important;
        bottom: 30px !important;
        left: 50% !important;
        transform: translateX(-50%) !important;
        z-index: 99999 !important;
        pointer-events: auto !important;
    }

    .btn-capture {
        width: 80px !important;
        height: 80px !important;
        background: linear-gradient(135deg, #0053C5 0%, #003d94 100%) !important;
        border: 5px solid white !important;
        border-radius: 50% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        cursor: pointer !important;
        transition: all 0.3s ease !important;
        box-shadow: 0 8px 32px rgba(0, 83, 197, 0.8) !important;
        position: relative !important;
        pointer-events: auto !important;
    }

    .btn-capture ion-icon {
        font-size: 40px !important;
        color: white !important;
        pointer-events: none !important;
    }

    .btn-capture:hover {
        transform: scale(1.1) !important;
        box-shadow: 0 12px 40px rgba(0, 83, 197, 0.9) !important;
    }

    .btn-capture:active {
        transform: scale(0.9) !important;
    }

    /* ===== LOADING OVERLAY ===== */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.95);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 100000;
        flex-direction: column;
    }

    .loading-overlay.show {
        display: flex;
    }

    .loading-content {
        text-align: center;
    }

    .loading-spinner {
        width: 60px;
        height: 60px;
        border: 4px solid rgba(255, 255, 255, 0.2);
        border-top: 4px solid #0053C5;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 20px;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .loading-text {
        color: white;
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .loading-detail {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.7);
    }

    /* Support for notched devices */
    @supports (padding: max(0px)) {
        .camera-header {
            padding-top: max(16px, env(safe-area-inset-top));
        }

        .capture-button-container {
            bottom: max(30px, env(safe-area-inset-bottom)) !important;
        }
    }
</style>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<div class="page-container">
    <!-- CAMERA SECTION -->
    <div class="camera-section">
        <!-- Header -->
        <div class="camera-header">
            <a href="{{ route('face-presensi.dashboard') }}" class="btn-back">
                <ion-icon name="close"></ion-icon>
            </a>
            <div class="header-info">
                <h3>Face Recognition</h3>
                <p id="current-time">{{ \Carbon\Carbon::now('Asia/Jakarta')->format('H:i:s') }}</p>
            </div>
            <div style="width: 40px;"></div>
        </div>

        <!-- Shift Info (if multi-shift) -->
        @if(isset($shift_ke) && $shift_ke)
        @php
        $current_shift = $shifts_available->where('shift_ke', $shift_ke)->first();
        @endphp

        <div class="shift-info-badge">
            <div class="shift-badge-content">
                <div class="shift-icon-badge">
                    <span>{{ $shift_ke }}</span>
                </div>
                <div class="shift-details">
                    <div class="shift-title">{{ $current_shift->nama_shift }}</div>
                    <div class="shift-time-range">
                        {{ date('H:i', strtotime($current_shift->jam_masuk)) }} -
                        {{ date('H:i', strtotime($current_shift->jam_pulang)) }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Hidden input for shift_ke -->
        <input type="hidden" id="shift_ke_input" value="{{ $shift_ke }}">
        @endif

        <!-- Camera -->
        <div class="camera-container">
            <div class="webcam-capture"></div>
            <div class="face-overlay"></div>
        </div>

        <!-- Instruction -->
        <div class="instruction-overlay">
            <div class="instruction-box">
                <p>Posisikan wajah di dalam bingkai biru</p>
            </div>
        </div>
    </div>

    <!-- INFO SECTION (Blue Theme) -->
    <div class="info-section">
        <!-- Status Info -->
        <div class="status-info-cards">
            <div class="status-info-item">
                <div class="status-icon-small">
                    <ion-icon name="person-outline"></ion-icon>
                </div>
                <div class="status-label-small">Karyawan</div>
                <div class="status-value-small">{{ Auth::guard('karyawan')->user()->nik }}</div>
            </div>
            <div class="status-info-item">
                <div class="status-icon-small">
                    <ion-icon name="shield-checkmark-outline"></ion-icon>
                </div>
                <div class="status-label-small">Status Face</div>
                <div class="status-value-small">{{ $faceData ? 'Aktif' : 'Belum' }}</div>
            </div>
        </div>

        <!-- Map Card -->
        <div class="info-card">
            <div class="info-title">
                <ion-icon name="location-outline"></ion-icon>
                Lokasi Presensi
            </div>
            <div class="map-container">
                <div id="map"></div>
            </div>
            <div class="location-info">
                <p>
                    <ion-icon name="navigate-circle-outline"></ion-icon>
                    <strong id="coords-display">Mendeteksi lokasi...</strong>
                </p>
            </div>
        </div>

        <!-- Tips Card -->
        <div class="tips-card">
            <div class="tips-header">
                <div class="tips-icon">
                    <ion-icon name="bulb"></ion-icon>
                </div>
                <div class="tips-title">Tips Verifikasi Wajah</div>
            </div>
            <ul class="tips-list">
                <li>Pastikan wajah terlihat jelas</li>
                <li>Cahaya cukup terang</li>
                <li>Tatap langsung ke kamera</li>
                <li>Lepas masker & kacamata hitam</li>
            </ul>
        </div>
    </div>
</div>

<!-- CAPTURE BUTTON -->
<div class="capture-button-container">
    <button class="btn-capture" id="btn-capture" type="button">
        <ion-icon name="scan"></ion-icon>
    </button>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loading-overlay">
    <div class="loading-content">
        <div class="loading-spinner"></div>
        <p class="loading-text">Memverifikasi wajah...</p>
        <p class="loading-detail" id="loading-detail">Mohon tunggu sebentar</p>
    </div>
</div>

<!-- Hidden Input -->
<input type="hidden" id="lokasi">

<!-- Audio -->
<audio id="success_sound" src="{{ asset('assets/sound/notifikasi_in.mp3') }}"></audio>

@endsection

@push('myscript')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<!-- Webcam JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
<!-- Face-API.js -->
<script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.js"></script>

@if($lok_kantor)
@php
$lokasi_parts = explode(',', $lok_kantor->lokasi_cabang);
@endphp
<script>
    window.OFFICE_LAT = {
        {
            trim($lokasi_parts[0])
        }
    };
    window.OFFICE_LNG = {
        {
            trim($lokasi_parts[1])
        }
    };
    window.OFFICE_NAME = "{{ $lok_kantor->nama_cabang }}";
</script>
@endif

<script>
    var webcamReady = false;
    var modelsLoaded = false;
    var map, marker;
    var success_sound = document.getElementById('success_sound');
    var lokasi = document.getElementById('lokasi');

    // Update time
    setInterval(function() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        });
        document.getElementById('current-time').textContent = timeString;
    }, 1000);

    // Initialize Webcam
    console.log('Initializing webcam...');

    Webcam.set({
        width: window.innerWidth,
        height: Math.floor(window.innerHeight * 0.6),
        image_format: 'jpeg',
        jpeg_quality: 90,
        flip_horiz: true,
        constraints: {
            video: true,
            facingMode: "user"
        }
    });

    Webcam.attach('.webcam-capture');

    Webcam.on('live', function() {
        console.log('Camera is live');
        webcamReady = true;
    });

    Webcam.on('error', function(err) {
        console.error('Webcam error:', err);
        Swal.fire({
            icon: 'error',
            title: 'Kamera Error',
            text: 'Tidak dapat mengakses kamera. Pastikan izin kamera diaktifkan.',
            confirmButtonColor: '#0053C5'
        }).then(() => {
            window.location.href = '/face-presensi/dashboard';
        });
    });

    // Initialize Geolocation & Map
    if (!navigator.geolocation) {
        Swal.fire({
            icon: 'error',
            title: 'GPS Tidak Didukung',
            text: 'Browser Anda tidak mendukung GPS.',
            confirmButtonColor: '#0053C5'
        });
    } else {
        navigator.geolocation.getCurrentPosition(successCallback, errorCallback, {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
        });
    }

    function successCallback(position) {
        var latitude = position.coords.latitude;
        var longitude = position.coords.longitude;

        lokasi.value = latitude + "," + longitude;
        console.log('Location set:', lokasi.value);

        document.getElementById('coords-display').textContent = latitude.toFixed(6) + ', ' + longitude.toFixed(6);

        try {
            map = L.map('map').setView([latitude, longitude], 17);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            var userIcon = L.divIcon({
                className: 'custom-div-icon',
                html: '<div style="background: linear-gradient(135deg, #0053C5 0%, #003d94 100%); width: 40px; height: 40px; border-radius: 50%; border: 4px solid white; box-shadow: 0 3px 10px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center;"><ion-icon name="person" style="color: white; font-size: 22px;"></ion-icon></div>',
                iconSize: [40, 40],
                iconAnchor: [20, 20]
            });

            marker = L.marker([latitude, longitude], {
                icon: userIcon
            }).addTo(map);
            marker.bindPopup('<strong style="color: #0053C5;">Lokasi Anda</strong>').openPopup();

            if (typeof window.OFFICE_LAT !== 'undefined' && typeof window.OFFICE_LNG !== 'undefined') {
                var officeIcon = L.divIcon({
                    className: 'custom-div-icon',
                    html: '<div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); width: 40px; height: 40px; border-radius: 50%; border: 4px solid white; box-shadow: 0 3px 10px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center;"><ion-icon name="business" style="color: white; font-size: 22px;"></ion-icon></div>',
                    iconSize: [40, 40],
                    iconAnchor: [20, 20]
                });

                var officeMarker = L.marker([window.OFFICE_LAT, window.OFFICE_LNG], {
                    icon: officeIcon
                }).addTo(map);
                officeMarker.bindPopup('<strong style="color: #10b981;">' + window.OFFICE_NAME + '</strong>');

                var group = L.featureGroup([marker, officeMarker]);
                map.fitBounds(group.getBounds().pad(0.2));
            }

            console.log('Map initialized');
        } catch (e) {
            console.error('Map error:', e);
        }
    }

    function errorCallback(error) {
        console.error('Geolocation error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Lokasi Tidak Terdeteksi',
            text: 'Aktifkan GPS untuk melanjutkan presensi.',
            confirmButtonColor: '#0053C5'
        });
    }

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
            console.error('Error loading models:', error);
            return false;
        }
    }

    async function getReferenceFaceDescriptor() {
        try {
            const response = await fetch('/face-presensi/descriptor', {
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
            console.error('Error getting descriptor:', error);
            throw error;
        }
    }

    async function verifyFace() {
        try {
            const video = document.querySelector('.webcam-capture video');

            if (!video) {
                throw new Error('Video element not found');
            }

            const detection = await faceapi
                .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
                .withFaceLandmarks()
                .withFaceDescriptor();

            if (!detection) {
                throw new Error('Wajah tidak terdeteksi. Pastikan wajah terlihat jelas di dalam bingkai.');
            }

            const confidence = (detection.detection.score * 100).toFixed(1);
            console.log('Face detection confidence:', confidence + '%');

            if (detection.detection.score < 0.5) {
                throw new Error('Deteksi wajah kurang jelas (' + confidence + '%). Coba posisikan lebih baik.');
            }

            const referenceDescriptor = await getReferenceFaceDescriptor();
            const distance = faceapi.euclideanDistance(detection.descriptor, referenceDescriptor);
            const similarity = ((1 - distance) * 100).toFixed(1);

            console.log('=== FACE VERIFICATION ===');
            console.log('Confidence:', confidence + '%');
            console.log('Similarity:', similarity + '%');
            console.log('Distance:', distance.toFixed(4));

            const loadingDetail = document.getElementById('loading-detail');
            loadingDetail.innerHTML = 'Kecocokan: <strong>' + similarity + '%</strong>';

            if (distance > 0.6) {
                throw new Error('Verifikasi gagal!<br>Kecocokan: <strong>' + similarity + '%</strong><br>Wajah tidak cocok dengan data terdaftar.');
            }

            return {
                success: true,
                confidence: confidence,
                similarity: similarity
            };

        } catch (error) {
            throw error;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const btnCapture = document.getElementById('btn-capture');

        if (!btnCapture) {
            console.error('Button capture tidak ditemukan!');
            return;
        }

        console.log('Button capture ready');

        btnCapture.addEventListener('click', async function(e) {
            e.preventDefault();

            if (!webcamReady) {
                Swal.fire({
                    icon: 'error',
                    title: 'Kamera Belum Siap',
                    text: 'Mohon tunggu kamera aktif',
                    confirmButtonColor: '#0053C5'
                });
                return;
            }

            try {
                if ('vibrate' in navigator) {
                    navigator.vibrate(20);
                }

                document.getElementById('loading-overlay').classList.add('show');

                if (!modelsLoaded) {
                    const loaded = await loadFaceModels();
                    if (!loaded) {
                        throw new Error('Gagal memuat model face recognition');
                    }
                }

                await verifyFace();

                $.ajax({
                    type: 'POST',
                    url: '/face-presensi/store',
                    data: {
                        _token: "{{ csrf_token() }}",
                        verified: 'true',
                        shift_ke: $('#shift_ke_input').val() || null
                    },
                    cache: false,
                    success: function(respond) {
                        document.getElementById('loading-overlay').classList.remove('show');

                        var status = respond.split("|");

                        if (status[0] == "success") {
                            success_sound.play();

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                html: '<strong>' + status[1] + '</strong>',
                                confirmButtonColor: '#0053C5',
                                timer: 3000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = '/face-presensi/dashboard';
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: status[1],
                                confirmButtonColor: '#0053C5'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        document.getElementById('loading-overlay').classList.remove('show');

                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'Gagal mengirim data. Silakan coba lagi.',
                            confirmButtonColor: '#0053C5'
                        });
                    }
                });

            } catch (error) {
                document.getElementById('loading-overlay').classList.remove('show');

                Swal.fire({
                    icon: 'error',
                    title: 'Verifikasi Gagal',
                    html: error.message || 'Verifikasi wajah gagal. Silakan coba lagi.',
                    confirmButtonColor: '#0053C5'
                });
            }
        });
    });

    $(document).ready(function() {
        loadFaceModels();
    });
</script>
@endpush