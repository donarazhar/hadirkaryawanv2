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
        background: linear-gradient(to bottom, rgba(0, 0, 0, 0.7) 0%, transparent 100%);
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
        box-shadow: linear-gradient(135deg, #0053C5 0%, #2E7CE6 100%);
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

    /* ===== MAP SECTION ===== */
    .map-section {
        flex: 1;
        background: #f0f4f8;
        padding: 20px;
        padding-bottom: 20px;
    }

    .map-card {
        background: white;
        border-radius: 20px;
        padding: 16px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .map-title {
        font-size: 14px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .map-title ion-icon {
        font-size: 20px;
        color: #0053C5;
    }

    #map {
        height: 200px;
        border-radius: 12px;
        overflow: hidden;
    }

    .location-info {
        margin-top: 12px;
        padding: 12px;
        background: linear-gradient(135deg, rgba(139, 92, 246, 0.05) 0%, rgba(124, 58, 237, 0.05) 100%);
        border-radius: 10px;
        border: 1px solid rgba(139, 92, 246, 0.2);
    }

    .location-info p {
        margin: 0;
        font-size: 12px;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .location-info ion-icon {
        font-size: 16px;
        color: #0053C5;
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
        background: linear-gradient(135deg, #0053C5 0%, #7c3aed 100%) !important;
        border: 5px solid white !important;
        border-radius: 50% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        cursor: pointer !important;
        transition: all 0.3s ease !important;
        box-shadow: 0 8px 32px rgba(139, 92, 246, 0.8) !important;
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
        box-shadow: 0 12px 40px rgba(139, 92, 246, 0.9) !important;
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
        background: rgba(0, 0, 0, 0.9);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 100000;
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

        <!-- Camera -->
        <div class="camera-container">
            <div class="webcam-capture"></div>
            <div class="face-overlay"></div>
        </div>

        <!-- Instruction -->
        <div class="instruction-overlay">
            <div class="instruction-box">
                <p>Posisikan wajah di dalam bingkai</p>
            </div>
        </div>
    </div>

    <!-- MAP SECTION -->
    <div class="map-section">
        <div class="map-card">
            <div class="map-title">
                <ion-icon name="location-outline"></ion-icon>
                Lokasi Presensi
            </div>
            <div id="map"></div>
            <div class="location-info">
                <p>
                    <ion-icon name="navigate-circle-outline"></ion-icon>
                    Lokasi Anda: <strong id="coords-display">Mendeteksi...</strong>
                </p>
            </div>
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
    </div>
</div>

<!-- Hidden Input -->
<input type="hidden" id="lokasi">

<!-- Audio -->
<audio id="success_sound" src="{{ asset('assets/sound/notifikasi_in.mp3') }}"></audio>

<!-- Face Recognition Status Card -->
<div class="card" style="margin-bottom: 16px; overflow: hidden; border: none; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);">
    <div style="background: linear-gradient(135deg, #0053C5 0%, #7c3aed 100%); padding: 16px 20px;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 50px; height: 50px; background: rgba(255, 255, 255, 0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <ion-icon name="scan-outline" style="font-size: 28px; color: white;"></ion-icon>
            </div>
            <div style="flex: 1;">
                <h6 style="margin: 0 0 4px 0; font-size: 16px; font-weight: 700; color: white;">
                    Face Recognition
                </h6>
                <p style="margin: 0; font-size: 13px; color: rgba(255, 255, 255, 0.9);">
                    Verifikasi Biometrik Presensi
                </p>
            </div>
        </div>
    </div>

    <div class="card-body" style="padding: 16px 20px;">
        @if($faceData)
        <!-- Already Enrolled -->
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
            <div style="width: 40px; height: 40px; background: rgba(16, 185, 129, 0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                <ion-icon name="checkmark-circle" style="font-size: 24px; color: #10b981;"></ion-icon>
            </div>
            <div>
                <p style="margin: 0; font-size: 14px; font-weight: 700; color: #10b981;">
                    Wajah Terdaftar
                </p>
                <p style="margin: 0; font-size: 12px; color: #64748b;">
                    Terakhir diperbarui: {{ \Carbon\Carbon::parse($faceData->last_updated)->diffForHumans() }}
                </p>
            </div>
        </div>

        <a href="{{ route('face-presensi.enrollment') }}" style="display: block; width: 100%; padding: 12px; background: linear-gradient(135deg, rgba(139, 92, 246, 0.1) 0%, rgba(124, 58, 237, 0.1) 100%); border: 1px solid #0053C5; border-radius: 12px; text-align: center; color: #0053C5; font-weight: 700; font-size: 14px; text-decoration: none;">
            <ion-icon name="settings-outline" style="vertical-align: middle; margin-right: 6px;"></ion-icon>
            Kelola Data Wajah
        </a>
        @else
        <!-- Not Enrolled Yet -->
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
            <div style="width: 40px; height: 40px; background: rgba(239, 68, 68, 0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                <ion-icon name="alert-circle" style="font-size: 24px; color: #ef4444;"></ion-icon>
            </div>
            <div>
                <p style="margin: 0; font-size: 14px; font-weight: 700; color: #ef4444;">
                    Belum Terdaftar
                </p>
                <p style="margin: 0; font-size: 12px; color: #64748b;">
                    Daftarkan wajah untuk keamanan lebih
                </p>
            </div>
        </div>

        <a href="{{ route('face-presensi.enrollment') }}" style="display: block; width: 100%; padding: 12px; background: linear-gradient(135deg, #0053C5 0%, #7c3aed 100%); border-radius: 12px; text-align: center; color: white; font-weight: 700; font-size: 14px; text-decoration: none; box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);">
            <ion-icon name="scan-outline" style="vertical-align: middle; margin-right: 6px;"></ion-icon>
            Daftar Wajah Sekarang
        </a>
        @endif
    </div>
</div>

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
window.OFFICE_LAT = {{ trim($lokasi_parts[0]) }};
window.OFFICE_LNG = {{ trim($lokasi_parts[1]) }};
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

        // Update display
        document.getElementById('coords-display').textContent = latitude.toFixed(6) + ', ' + longitude.toFixed(6);

        try {
            // Initialize map
            map = L.map('map').setView([latitude, longitude], 17);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            // User location marker
            var userIcon = L.divIcon({
                className: 'custom-div-icon',
                html: '<div style="background: linear-gradient(135deg, #0053C5 0%, #7c3aed 100%); width: 40px; height: 40px; border-radius: 50%; border: 4px solid white; box-shadow: 0 3px 10px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center;"><ion-icon name="person" style="color: white; font-size: 22px;"></ion-icon></div>',
                iconSize: [40, 40],
                iconAnchor: [20, 20]
            });

            marker = L.marker([latitude, longitude], {
                icon: userIcon
            }).addTo(map);
            marker.bindPopup('<strong style="color: #0053C5;">Lokasi Anda Saat Ini</strong>').openPopup();

            // Office location marker (optional - for reference)
            if (typeof window.OFFICE_LAT !== 'undefined' && typeof window.OFFICE_LNG !== 'undefined') {
                var officeIcon = L.divIcon({
                    className: 'custom-div-icon',
                    html: '<div style="background: linear-gradient(135deg, #0053C5 0%, #003d94 100%); width: 40px; height: 40px; border-radius: 50%; border: 4px solid white; box-shadow: 0 3px 10px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center;"><ion-icon name="business" style="color: white; font-size: 22px;"></ion-icon></div>',
                    iconSize: [40, 40],
                    iconAnchor: [20, 20]
                });

                var officeMarker = L.marker([window.OFFICE_LAT, window.OFFICE_LNG], {
                    icon: officeIcon
                }).addTo(map);
                officeMarker.bindPopup('<strong style="color: #0053C5;">' + window.OFFICE_NAME + '</strong>');

                // Fit bounds to show both markers
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
            console.error('Error loading models:', error);
            return false;
        }
    }

    // Get reference face descriptor
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

   // Verify face dengan info lebih detail
async function verifyFace() {
    try {
        const video = document.querySelector('.webcam-capture video');

        if (!video) {
            throw new Error('Video element not found');
        }

        // ✅ Deteksi wajah
        const detection = await faceapi
            .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
            .withFaceLandmarks()
            .withFaceDescriptor();

        if (!detection) {
            throw new Error('Wajah tidak terdeteksi. Pastikan wajah terlihat jelas di dalam bingkai.');
        }

        // ✅ Cek confidence score
        const confidence = (detection.detection.score * 100).toFixed(1);
        console.log('Face detection confidence:', confidence + '%');

        if (detection.detection.score < 0.5) {
            throw new Error('Deteksi wajah kurang jelas (' + confidence + '%). Coba posisikan lebih baik.');
        }

        // ✅ Ambil descriptor wajah yang terdaftar
        const referenceDescriptor = await getReferenceFaceDescriptor();
        
        // ✅ Hitung jarak (distance) antara wajah sekarang dengan yang terdaftar
        const distance = faceapi.euclideanDistance(detection.descriptor, referenceDescriptor);
        const similarity = ((1 - distance) * 100).toFixed(1); // Convert ke persentase similarity

        console.log('=== FACE VERIFICATION RESULT ===');
        console.log('Detection Confidence:', confidence + '%');
        console.log('Euclidean Distance:', distance.toFixed(4));
        console.log('Face Similarity:', similarity + '%');
        console.log('Threshold:', '0.6 (max)');
        console.log('Status:', distance <= 0.6 ? '✅ MATCH' : '❌ NOT MATCH');
        console.log('================================');

        // ✅ Tampilkan info di loading overlay
        const loadingText = document.querySelector('.loading-text');
        loadingText.innerHTML = `
            Memverifikasi wajah...<br>
            <small style="font-size: 13px; color: rgba(255,255,255,0.8); margin-top: 8px; display: block;">
                Tingkat Kecocokan: <strong>${similarity}%</strong>
            </small>
        `;

        // ✅ Cek apakah wajah cocok (threshold 0.6)
        if (distance > 0.6) {
            throw new Error(`
                <div style="text-align: center;">
                    <p style="margin: 0 0 12px 0; font-size: 15px;">Verifikasi wajah gagal!</p>
                    <div style="background: rgba(239, 68, 68, 0.1); padding: 12px; border-radius: 8px; margin-bottom: 12px;">
                        <p style="margin: 0; font-size: 13px; color: #ef4444;">
                            <strong>Tingkat Kecocokan: ${similarity}%</strong><br>
                            <span style="font-size: 12px; color: #64748b;">Minimal yang dibutuhkan: 40%</span>
                        </p>
                    </div>
                    <p style="margin: 0; font-size: 13px; color: #64748b;">
                        Wajah tidak cocok dengan data terdaftar.<br>
                        Silakan ulangi atau hubungi admin.
                    </p>
                </div>
            `);
        }

        // ✅ Jika cocok, return true
        return {
            success: true,
            confidence: confidence,
            similarity: similarity,
            distance: distance
        };

    } catch (error) {
        console.error('Verification error:', error);
        throw error;
    }
}

    // Capture Button Event
    document.addEventListener('DOMContentLoaded', function() {
        const btnCapture = document.getElementById('btn-capture');

        if (!btnCapture) {
            console.error('Button capture tidak ditemukan!');
            return;
        }

        console.log('Button capture ready');

        btnCapture.addEventListener('click', async function(e) {
            e.preventDefault();
            console.log('Button capture diklik');

            // Validasi kamera
            if (!webcamReady) {
                Swal.fire({
                    icon: 'error',
                    title: 'Kamera Belum Siap',
                    text: 'Mohon tunggu kamera aktif',
                    confirmButtonColor: '#0053C5'
                });
                return;
            }

            // ❌ HAPUS validasi lokasi GPS - tidak diperlukan lagi
            // var lokasi_val = lokasi.value;
            // if (!lokasi_val) { ... }

            try {
                // Haptic feedback
                if ('vibrate' in navigator) {
                    navigator.vibrate(20);
                }

                // Show loading
                document.getElementById('loading-overlay').classList.add('show');

                // Load models if not loaded
                if (!modelsLoaded) {
                    const loaded = await loadFaceModels();
                    if (!loaded) {
                        throw new Error('Gagal memuat model face recognition');
                    }
                }

                // Verify face
                await verifyFace();

                // Send to server
                $.ajax({
                    type: 'POST',
                    url: '/face-presensi/store',
                    data: {
                        _token: "{{ csrf_token() }}",
                        // ❌ lokasi tidak perlu dikirim - diambil dari cabang
                        verified: 'true'
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
                        console.error('AJAX Error:', error);

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
                console.error('Capture error:', error);

                Swal.fire({
                    icon: 'error',
                    title: 'Verifikasi Gagal',
                    html: error.message || 'Verifikasi wajah gagal. Silakan coba lagi.',
                    confirmButtonColor: '#0053C5'
                });
            }
        });
    });

    // Preload face-api models
    $(document).ready(function() {
        console.log('Preloading face-api models...');
        loadFaceModels();
    });
</script>
@endpush