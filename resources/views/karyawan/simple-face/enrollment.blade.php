@extends('karyawan.layouts.simple-face')

@section('content')

<style>
    body {
        background: #f0f4f8;
        padding: 0 !important;
    }

    /* ===== PAGE HEADER ===== */
    .page-header {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
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

    /* ===== ENROLLMENT SECTION ===== */
    .enrollment-section {
        padding: 0 20px;
        margin-top: -65px;
        margin-bottom: 120px;
    }

    .enrollment-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(139, 92, 246, 0.08);
        margin-bottom: 16px;
    }

    .status-info {
        text-align: center;
        padding: 20px;
        margin-bottom: 20px;
    }

    .status-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 16px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .status-icon.not-enrolled {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(220, 38, 38, 0.1) 100%);
    }

    .status-icon.enrolled {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.1) 100%);
    }

    .status-icon ion-icon {
        font-size: 40px;
    }

    .status-icon.not-enrolled ion-icon {
        color: #ef4444;
    }

    .status-icon.enrolled ion-icon {
        color: #10b981;
    }

    .status-title {
        font-size: 18px;
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
        margin: 0;
    }

    #faceCanvas {
        display: none;
    }

    .camera-container {
        position: relative;
        width: 100%;
        border-radius: 16px;
        overflow: hidden;
        display: none;
    }

    .camera-container.active {
        display: block;
    }

    #video {
        width: 100%;
        height: auto;
        border-radius: 16px;
    }

    .face-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 220px;
        height: 280px;
        border: 3px solid #8b5cf6;
        border-radius: 50%;
        pointer-events: none;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 0.6;
        }

        50% {
            opacity: 1;
        }
    }

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

    .btn-primary {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
        box-shadow: 0 6px 20px rgba(139, 92, 246, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(139, 92, 246, 0.4);
    }

    .btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }

    .btn-secondary {
        background: #f1f5f9;
        color: #64748b;
    }

    .info-box {
        background: linear-gradient(135deg, rgba(139, 92, 246, 0.1) 0%, rgba(124, 58, 237, 0.05) 100%);
        border: 1px solid rgba(139, 92, 246, 0.2);
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 20px;
    }

    .info-title {
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
        color: #8b5cf6;
    }

    .info-title ion-icon {
        font-size: 20px;
    }

    .info-list {
        font-size: 13px;
        color: #64748b;
        line-height: 1.8;
        padding-left: 20px;
        margin: 0;
    }

    .enrolled-image {
        text-align: center;
        margin-bottom: 20px;
    }

    .enrolled-image img {
        width: 200px;
        height: 200px;
        object-fit: cover;
        border-radius: 16px;
        border: 3px solid #10b981;
    }

    .enrolled-info {
        text-align: center;
        font-size: 12px;
        color: #64748b;
        margin-top: 12px;
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
                Data wajah Anda sudah terdaftar dalam sistem
            </p>
            <p class="enrolled-info">
                Terakhir diperbarui: {{ \Carbon\Carbon::parse($faceData->last_updated)->diffForHumans() }}
            </p>
        </div>

        @if($faceData->face_image)
        <div class="enrolled-image">
            <img src="{{ Storage::url('uploads/faces/' . $faceData->face_image) }}" alt="Face Reference">
        </div>
        @endif

        <button class="btn-action btn-primary" onclick="reEnroll()">
            <ion-icon name="refresh"></ion-icon>
            <span>Perbarui Data Wajah</span>
        </button>

        <button class="btn-action btn-danger" onclick="deleteFaceData()">
            <ion-icon name="trash"></ion-icon>
            <span>Hapus Data Wajah</span>
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
                Daftarkan wajah Anda untuk menggunakan fitur verifikasi wajah saat presensi
            </p>
        </div>

        <div class="enrollment-card info-box">
            <h4 class="info-title">
                <ion-icon name="information-circle"></ion-icon>
                Petunjuk
            </h4>
            <ul class="info-list">
                <li>Pastikan wajah Anda terlihat jelas</li>
                <li>Posisikan wajah di dalam bingkai oval</li>
                <li>Pastikan pencahayaan cukup</li>
                <li>Lepas kacamata hitam/masker</li>
                <li>Tatap langsung ke kamera</li>
            </ul>
        </div>

        <div class="enrollment-card">
            <div class="camera-container" id="cameraContainer">
                <video id="video" autoplay></video>
                <div class="face-overlay"></div>
            </div>
            <canvas id="faceCanvas"></canvas>
        </div>

        <button class="btn-action btn-primary" id="startEnrollment">
            <ion-icon name="camera"></ion-icon>
            <span>Mulai Pendaftaran</span>
        </button>
    </div>
    @endif
</div>

@endsection

@push('myscript')
<!-- Face-API.js -->
<script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.js"></script>

<script>
    let video, canvas, faceDetectionStarted = false;
    let modelsLoaded = false;

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
            console.log('Models loaded successfully');
        } catch (error) {
            console.error('Error loading models:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Gagal memuat model face recognition',
                confirmButtonColor: '#8b5cf6'
            });
        }
    }

    // Start enrollment
    document.getElementById('startEnrollment')?.addEventListener('click', async function() {
        if (!modelsLoaded) {
            Swal.fire({
                title: 'Loading...',
                text: 'Memuat model face recognition',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            await loadModels();
            Swal.close();
        }

        document.getElementById('cameraContainer').classList.add('active');
        this.style.display = 'none';

        startCamera();
    });

    async function startCamera() {
        try {
            video = document.getElementById('video');
            canvas = document.getElementById('faceCanvas');

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

                // Start face detection after 2 seconds
                setTimeout(() => {
                    detectAndCapture();
                }, 2000);
            });

        } catch (error) {
            console.error('Camera error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Kamera Error',
                text: 'Tidak dapat mengakses kamera. Pastikan izin kamera diaktifkan.',
                confirmButtonColor: '#8b5cf6'
            });
        }
    }

    async function detectAndCapture() {
        if (!modelsLoaded) return;

        try {
            console.log('Detecting face...');

            const detections = await faceapi
                .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
                .withFaceLandmarks()
                .withFaceDescriptor();

            if (detections) {
                console.log('Face detected! Confidence:', detections.detection.score);

                if (detections.detection.score > 0.5) {
                    // Good detection, capture it
                    await captureFaceData(detections.descriptor);
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Wajah Tidak Jelas',
                        text: 'Deteksi wajah kurang jelas. Coba lagi.',
                        confirmButtonColor: '#8b5cf6'
                    }).then(() => {
                        detectAndCapture();
                    });
                }
            } else {
                console.log('No face detected, retrying...');
                setTimeout(() => detectAndCapture(), 1000);
            }

        } catch (error) {
            console.error('Detection error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Gagal mendeteksi wajah. Silakan coba lagi.',
                confirmButtonColor: '#8b5cf6'
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
            const tracks = stream.getTracks();
            tracks.forEach(track => track.stop());

            // Show loading
            Swal.fire({
                title: 'Menyimpan...',
                text: 'Sedang menyimpan data wajah Anda',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

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

            if (result.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: result.message,
                    confirmButtonColor: '#8b5cf6'
                }).then(() => {
                    window.location.href = '/face-presensi/create';
                });
            } else {
                throw new Error(result.message);
            }

        } catch (error) {
            console.error('Capture error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: error.message || 'Gagal menyimpan data wajah',
                confirmButtonColor: '#8b5cf6'
            });
        }
    }

    function reEnroll() {
        Swal.fire({
            title: 'Perbarui Data Wajah?',
            text: 'Data wajah lama akan diganti dengan yang baru',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#8b5cf6',
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
            text: 'Data wajah Anda akan dihapus dari sistem',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const response = await fetch('/face-presensi/enrollment/delete', {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: data.message,
                            confirmButtonColor: '#8b5cf6'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        throw new Error(data.message);
                    }
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: error.message,
                        confirmButtonColor: '#8b5cf6'
                    });
                }
            }
        });
    }

    // Load models on page load
    loadModels();
</script>
@endpush