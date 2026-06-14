@extends('karyawan.layouts.simple-face')

@section('content')

<style>
    body {
        background: linear-gradient(135deg, #0053C5 0%, #003d94 100%);
        padding: 0 !important;
        min-height: 100vh;
    }

    /* ===== PAGE HEADER ===== */
    .page-header {
        background: transparent;
        padding: 24px 20px 40px 20px;
        position: relative;
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
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
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

    .header-title h1 {
        font-size: 22px;
        font-weight: 700;
        color: white;
        margin: 0 0 4px 0;
    }

    .header-title p {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.9);
        margin: 0;
    }

    /* ===== ENROLLMENT SECTION ===== */
    .enrollment-section {
        padding: 0 20px 120px 20px;
    }

    .enrollment-card {
        background: white;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        margin-bottom: 16px;
    }

    /* ===== STATUS INFO ===== */
    .status-info {
        text-align: center;
        padding: 20px 0;
        margin-bottom: 24px;
    }

    .status-icon {
        width: 100px;
        height: 100px;
        margin: 0 auto 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    }

    .status-icon.not-enrolled {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(220, 38, 38, 0.1) 100%);
        border: 3px solid rgba(239, 68, 68, 0.3);
    }

    .status-icon.enrolled {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(5, 150, 105, 0.1) 100%);
        border: 3px solid rgba(16, 185, 129, 0.3);
    }

    .status-icon ion-icon {
        font-size: 48px;
    }

    .status-icon.not-enrolled ion-icon {
        color: #ef4444;
    }

    .status-icon.enrolled ion-icon {
        color: #10b981;
    }

    .status-title {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .status-title.enrolled {
        color: #10b981;
    }

    .status-title.not-enrolled {
        color: #ef4444;
    }

    .status-text {
        color: #64748b;
        font-size: 14px;
        margin: 0 0 8px 0;
        line-height: 1.6;
    }

    .enrolled-info {
        font-size: 12px;
        color: #94a3b8;
        margin-top: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .enrolled-info ion-icon {
        font-size: 14px;
    }

    /* ===== ENROLLED IMAGE ===== */
    .enrolled-image {
        text-align: center;
        margin-bottom: 24px;
    }

    .enrolled-image-wrapper {
        position: relative;
        display: inline-block;
    }

    .enrolled-image img {
        width: 200px;
        height: 200px;
        object-fit: cover;
        border-radius: 20px;
        border: 4px solid #10b981;
        box-shadow: 0 8px 24px rgba(16, 185, 129, 0.3);
    }

    .enrolled-badge {
        position: absolute;
        top: -10px;
        right: -10px;
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    }

    .enrolled-badge ion-icon {
        font-size: 20px;
        color: white;
    }

    /* ===== CAMERA CONTAINER ===== */
    #faceCanvas {
        display: none;
    }

    .camera-container {
        position: relative;
        width: 100%;
        border-radius: 16px;
        overflow: hidden;
        display: none;
        margin-bottom: 20px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    }

    .camera-container.active {
        display: block;
    }

    #video {
        width: 100%;
        height: auto;
        border-radius: 16px;
        display: block;
    }

    .face-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 220px;
        height: 280px;
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

    .camera-instruction {
        position: absolute;
        bottom: 20px;
        left: 0;
        right: 0;
        text-align: center;
        padding: 0 20px;
    }

    .camera-instruction-box {
        display: inline-block;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(10px);
        padding: 10px 20px;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .camera-instruction-box p {
        font-size: 12px;
        color: white;
        margin: 0;
        font-weight: 600;
    }

    /* ===== BUTTONS ===== */
    .btn-action {
        width: 100%;
        padding: 16px;
        border: none;
        border-radius: 16px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-bottom: 12px;
    }

    .btn-action ion-icon {
        font-size: 22px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #0053C5 0%, #003d94 100%);
        color: white;
        box-shadow: 0 6px 20px rgba(0, 83, 197, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 83, 197, 0.4);
    }

    .btn-primary:active {
        transform: translateY(0);
    }

    .btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        box-shadow: 0 6px 20px rgba(239, 68, 68, 0.3);
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(239, 68, 68, 0.4);
    }

    .btn-secondary {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #e2e8f0;
    }

    .btn-secondary:hover {
        background: #e2e8f0;
    }

    /* ===== INFO BOX ===== */
    .info-box {
        background: linear-gradient(135deg, rgba(0, 83, 197, 0.08) 0%, rgba(0, 61, 148, 0.05) 100%);
        border: 1px solid rgba(0, 83, 197, 0.2);
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 24px;
    }

    .info-title {
        font-size: 15px;
        font-weight: 700;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
        color: #0053C5;
    }

    .info-title ion-icon {
        font-size: 22px;
    }

    .info-list {
        font-size: 13px;
        color: #475569;
        line-height: 2;
        padding-left: 20px;
        margin: 0;
    }

    .info-list li {
        margin-bottom: 6px;
    }

    .info-list li::marker {
        color: #0053C5;
    }

    /* ===== FEATURES GRID ===== */
    .features-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        margin-bottom: 24px;
    }

    .feature-item {
        background: linear-gradient(135deg, rgba(0, 83, 197, 0.05) 0%, rgba(0, 61, 148, 0.02) 100%);
        border: 1px solid rgba(0, 83, 197, 0.1);
        border-radius: 12px;
        padding: 16px;
        text-align: center;
    }

    .feature-icon {
        width: 48px;
        height: 48px;
        margin: 0 auto 10px;
        background: linear-gradient(135deg, #0053C5 0%, #003d94 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .feature-icon ion-icon {
        font-size: 24px;
        color: white;
    }

    .feature-label {
        font-size: 12px;
        font-weight: 600;
        color: #475569;
        margin: 0;
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
</style>

<!-- Page Header -->
<div class="page-header">
    <div class="header-content">
        <a href="{{ route('face-presensi.dashboard') }}" class="btn-back">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
        <div class="header-title">
            <h1>Pendaftaran Wajah</h1>
            <p>Face Recognition Setup</p>
        </div>
    </div>
</div>

<!-- Enrollment Section -->
<div class="enrollment-section">
    @if($faceData && $faceData->status == 'active')
    <!-- Already Enrolled -->
    <div class="enrollment-card">
        <div class="status-info">
            <div class="status-icon enrolled">
                <ion-icon name="checkmark-circle"></ion-icon>
            </div>
            <h3 class="status-title enrolled">Wajah Terdaftar</h3>
            <p class="status-text">
                Data wajah Anda sudah terdaftar dan aktif dalam sistem presensi
            </p>
            <div class="enrolled-info">
                <ion-icon name="time-outline"></ion-icon>
                <span>Terakhir diperbarui: {{ \Carbon\Carbon::parse($faceData->last_updated)->diffForHumans() }}</span>
            </div>
        </div>

        @if($faceData->face_image)
        <div class="enrolled-image">
            <div class="enrolled-image-wrapper">
                <img src="{{ route('face-presensi.image') }}" alt="Face Reference">
                <div class="enrolled-badge">
                    <ion-icon name="checkmark"></ion-icon>
                </div>
            </div>
        </div>
        @endif

        <!-- Features Grid -->
        <div class="features-grid">
            <div class="feature-item">
                <div class="feature-icon">
                    <ion-icon name="shield-checkmark"></ion-icon>
                </div>
                <p class="feature-label">Terverifikasi</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <ion-icon name="flash"></ion-icon>
                </div>
                <p class="feature-label">Presensi Cepat</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <ion-icon name="lock-closed"></ion-icon>
                </div>
                <p class="feature-label">Aman</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <ion-icon name="checkbox"></ion-icon>
                </div>
                <p class="feature-label">Akurat</p>
            </div>
        </div>

        <button class="btn-action btn-primary" onclick="reEnroll()">
            <ion-icon name="refresh"></ion-icon>
            <span>Perbarui Data Wajah</span>
        </button>

        <button class="btn-action btn-danger" onclick="deleteFaceData()">
            <ion-icon name="trash"></ion-icon>
            <span>Hapus Data Wajah</span>
        </button>

        <button class="btn-action btn-secondary" onclick="window.location.href='{{ route('face-presensi.dashboard') }}'">
            <ion-icon name="arrow-back"></ion-icon>
            <span>Kembali ke Dashboard</span>
        </button>
    </div>
    @else
    <!-- Not Enrolled -->
    <div class="enrollment-card">
        <div class="status-info">
            <div class="status-icon not-enrolled">
                <ion-icon name="person-add"></ion-icon>
            </div>
            <h3 class="status-title not-enrolled">Belum Terdaftar</h3>
            <p class="status-text">
                Daftarkan wajah Anda untuk menggunakan fitur verifikasi wajah saat presensi. Proses pendaftaran hanya membutuhkan beberapa detik.
            </p>
        </div>

        <!-- Info Box -->
        <div class="info-box">
            <h4 class="info-title">
                <ion-icon name="information-circle"></ion-icon>
                Petunjuk Pendaftaran
            </h4>
            <ul class="info-list">
                <li>Pastikan wajah Anda terlihat jelas dan tidak tertutup</li>
                <li>Posisikan wajah di dalam bingkai oval biru</li>
                <li>Pastikan pencahayaan cukup terang</li>
                <li>Lepas kacamata hitam, masker, atau topi</li>
                <li>Tatap langsung ke kamera tanpa menoleh</li>
                <li>Jaga posisi stabil saat proses deteksi</li>
            </ul>
        </div>

        <!-- Camera Container -->
        <div class="camera-container" id="cameraContainer">
            <video id="video" autoplay playsinline></video>
            <div class="face-overlay"></div>
            <div class="camera-instruction">
                <div class="camera-instruction-box">
                    <p>Posisikan wajah di dalam bingkai</p>
                </div>
            </div>
        </div>
        <canvas id="faceCanvas"></canvas>

        <!-- Action Button -->
        <button class="btn-action btn-primary" id="startEnrollment">
            <ion-icon name="camera"></ion-icon>
            <span>Mulai Pendaftaran Wajah</span>
        </button>

        <button class="btn-action btn-secondary" onclick="window.location.href='{{ route('face-presensi.dashboard') }}'">
            <ion-icon name="arrow-back"></ion-icon>
            <span>Kembali ke Dashboard</span>
        </button>
    </div>
    @endif
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loading-overlay">
    <div class="loading-content">
        <div class="loading-spinner"></div>
        <p class="loading-text" id="loading-text">Memproses...</p>
        <p class="loading-detail" id="loading-detail">Mohon tunggu sebentar</p>
    </div>
</div>

@endsection

@push('myscript')
<!-- Face-API.js -->
<script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.js"></script>

<script>
    let video, canvas, faceDetectionStarted = false;
    let modelsLoaded = false;

    // Show/Hide Loading
    function showLoading(text = 'Memproses...', detail = 'Mohon tunggu sebentar') {
        document.getElementById('loading-text').textContent = text;
        document.getElementById('loading-detail').textContent = detail;
        document.getElementById('loading-overlay').classList.add('show');
    }

    function hideLoading() {
        document.getElementById('loading-overlay').classList.remove('show');
    }

    // Load Face-API models
    async function loadModels() {
        try {
            console.log('Loading face-api models...');

            const MODEL_URL = 'https://cdn.jsdelivr.net/npm/@vladmandic/face-api/model';

            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
                faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
                faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
            ]);

            modelsLoaded = true;
            console.log('✅ Models loaded successfully');
        } catch (error) {
            console.error('❌ Error loading models:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Gagal memuat model face recognition. Silakan refresh halaman.',
                confirmButtonColor: '#0053C5'
            });
        }
    }

    // Start enrollment
    document.getElementById('startEnrollment')?.addEventListener('click', async function() {
        if (!modelsLoaded) {
            showLoading('Memuat Model...', 'Mempersiapkan face recognition');
            await loadModels();
            hideLoading();

            if (!modelsLoaded) {
                return;
            }
        }

        document.getElementById('cameraContainer').classList.add('active');
        this.style.display = 'none';

        startCamera();
    });

    async function startCamera() {
        try {
            video = document.getElementById('video');
            canvas = document.getElementById('faceCanvas');

            console.log('Starting camera...');

            const stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: 'user',
                    width: {
                        ideal: 640
                    },
                    height: {
                        ideal: 480
                    }
                }
            });

            video.srcObject = stream;

            video.addEventListener('loadedmetadata', () => {
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;

                console.log('✅ Camera ready, starting detection in 2s...');

                // Start face detection after 2 seconds
                setTimeout(() => {
                    detectAndCapture();
                }, 2000);
            });

        } catch (error) {
            console.error('❌ Camera error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Kamera Error',
                text: 'Tidak dapat mengakses kamera. Pastikan izin kamera diaktifkan.',
                confirmButtonColor: '#0053C5'
            }).then(() => {
                location.reload();
            });
        }
    }

    async function detectAndCapture() {
        if (!modelsLoaded) return;

        try {
            console.log('Detecting face...');

            const detection = await faceapi
                .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
                .withFaceLandmarks()
                .withFaceDescriptor();

            if (detection) {
                const confidence = (detection.detection.score * 100).toFixed(1);
                console.log('Face detected! Confidence:', confidence + '%');

                if (detection.detection.score > 0.5) {
                    // Good detection, capture it
                    console.log('✅ Good detection, capturing...');
                    await captureFaceData(detection.descriptor);
                } else {
                    console.log('⚠️ Low confidence, retrying...');
                    Swal.fire({
                        icon: 'warning',
                        title: 'Wajah Tidak Jelas',
                        text: 'Deteksi wajah kurang jelas (' + confidence + '%). Silakan coba lagi dengan pencahayaan lebih baik.',
                        confirmButtonColor: '#0053C5'
                    }).then(() => {
                        detectAndCapture();
                    });
                }
            } else {
                console.log('No face detected, retrying in 1s...');
                setTimeout(() => detectAndCapture(), 1000);
            }

        } catch (error) {
            console.error('❌ Detection error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Gagal mendeteksi wajah. Silakan coba lagi.',
                confirmButtonColor: '#0053C5'
            }).then(() => {
                detectAndCapture();
            });
        }
    }

    async function captureFaceData(descriptor) {
        try {
            // Capture image from video
            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            const imageData = canvas.toDataURL('image/png');

            // Stop camera
            const stream = video.srcObject;
            if (stream) {
                const tracks = stream.getTracks();
                tracks.forEach(track => track.stop());
            }

            // Show loading
            showLoading('Menyimpan Data...', 'Sedang menyimpan data wajah Anda');

            // Send to server
            const response = await fetch('/face-presensi/enrollment/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    face_descriptor: JSON.stringify(Array.from(descriptor)),
                    face_image: imageData
                })
            });

            const result = await response.json();

            hideLoading();

            if (result.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    html: '<strong>' + result.message + '</strong><br><small style="color: #64748b;">Data wajah Anda telah tersimpan dengan aman</small>',
                    confirmButtonColor: '#0053C5',
                    confirmButtonText: 'Lanjut Presensi'
                }).then(() => {
                    window.location.href = '/face-presensi/create';
                });
            } else {
                throw new Error(result.message);
            }

        } catch (error) {
            hideLoading();
            console.error('❌ Capture error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: error.message || 'Gagal menyimpan data wajah',
                confirmButtonColor: '#0053C5'
            });
        }
    }

    function reEnroll() {
        Swal.fire({
            title: 'Perbarui Data Wajah?',
            html: 'Data wajah lama akan diganti dengan yang baru.<br><small style="color: #64748b;">Pastikan kondisi pencahayaan baik</small>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0053C5',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Perbarui',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.reload();
            }
        });
    }

    function deleteFaceData() {
        Swal.fire({
            title: 'Hapus Data Wajah?',
            html: 'Data wajah Anda akan dihapus dari sistem.<br><small style="color: #64748b;">Anda perlu mendaftar ulang untuk menggunakan face recognition</small>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    showLoading('Menghapus...', 'Sedang menghapus data wajah');

                    const response = await fetch('/face-presensi/enrollment/delete', {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    const data = await response.json();

                    hideLoading();

                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: data.message,
                            confirmButtonColor: '#0053C5'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        throw new Error(data.message);
                    }
                } catch (error) {
                    hideLoading();
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: error.message,
                        confirmButtonColor: '#0053C5'
                    });
                }
            }
        });
    }

    // Load models on page load
    console.log('Page loaded, preloading models...');
    loadModels();
</script>
@endpush