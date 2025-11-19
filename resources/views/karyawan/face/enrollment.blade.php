@extends('karyawan.layouts.presensi')

@section('content')

<style>
    /* Style sama dengan presensi create, disesuaikan */
    :root {
        --primary: #0053C5;
        --primary-gradient: linear-gradient(135deg, #0053C5 0%, #2E7CE6 100%);
        --success: #10b981;
        --danger: #ef4444;
        --bg-card: #ffffff;
        --text-primary: #1e293b;
        --text-secondary: #64748b;
    }

    .page-header {
        background: var(--primary-gradient);
        padding: 24px 20px 80px 20px;
        position: relative;
        overflow: hidden;
    }

    .enrollment-section {
        padding: 0 20px;
        margin-top: -65px;
        margin-bottom: 120px;
    }

    .enrollment-card {
        background: var(--bg-card);
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 83, 197, 0.08);
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
        color: var(--danger);
    }

    .status-icon.enrolled ion-icon {
        color: var(--success);
    }

    #faceCanvas {
        display: none;
    }

    .camera-container {
        position: relative;
        width: 100%;
        border-radius: 16px;
        overflow: hidden;
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
        width: 250px;
        height: 300px;
        border: 3px solid var(--primary);
        border-radius: 50%;
        pointer-events: none;
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
        background: var(--primary-gradient);
        color: white;
        box-shadow: 0 6px 20px rgba(0, 83, 197, 0.3);
    }

    .btn-danger {
        background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);
        color: white;
    }

    .btn-secondary {
        background: #f1f5f9;
        color: var(--text-secondary);
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <div class="header-content" style="display: flex; align-items: center; gap: 16px;">
        <a href="{{ route('dashboard') }}" style="width: 40px; height: 40px; background: rgba(255,255,255,0.15); border-radius: 12px; display: flex; align-items: center; justify-content: center; text-decoration: none;">
            <ion-icon name="chevron-back-outline" style="font-size: 24px; color: white;"></ion-icon>
        </a>
        <div>
            <h1 style="font-size: 22px; font-weight: 700; color: white; margin: 0 0 4px 0;">
                Pendaftaran Wajah
            </h1>
            <p style="font-size: 13px; color: rgba(255,255,255,0.8); margin: 0;">
                Face Recognition Setup
            </p>
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
            <h3 style="color: var(--success); margin-bottom: 8px;">Wajah Terdaftar</h3>
            <p style="color: var(--text-secondary); font-size: 14px; margin: 0;">
                Data wajah Anda sudah terdaftar dalam sistem
            </p>
            <p style="color: var(--text-secondary); font-size: 12px; margin-top: 12px;">
                Terakhir diperbarui: {{ $faceData->last_updated->format('d M Y H:i') }}
            </p>
        </div>

        @if($faceData->face_image)
        <div style="text-align: center; margin-bottom: 20px;">
            <img src="{{ Storage::url('uploads/faces/' . $faceData->face_image) }}" 
                 alt="Face Reference" 
                 style="width: 200px; height: 200px; object-fit: cover; border-radius: 16px; border: 3px solid var(--success);">
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
            <h3 style="color: var(--danger); margin-bottom: 8px;">Belum Terdaftar</h3>
            <p style="color: var(--text-secondary); font-size: 14px; margin: 0;">
                Daftarkan wajah Anda untuk menggunakan fitur verifikasi wajah saat presensi
            </p>
        </div>

        <div class="enrollment-card">
            <h4 style="font-size: 14px; font-weight: 700; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                <ion-icon name="information-circle" style="color: var(--primary); font-size: 20px;"></ion-icon>
                Petunjuk
            </h4>
            <ul style="font-size: 13px; color: var(--text-secondary); line-height: 1.8; padding-left: 20px;">
                <li>Pastikan wajah Anda terlihat jelas</li>
                <li>Posisikan wajah di dalam bingkai oval</li>
                <li>Pastikan pencahayaan cukup</li>
                <li>Lepas kacamata hitam/masker</li>
                <li>Tatap langsung ke kamera</li>
            </ul>
        </div>

        <div class="enrollment-card">
            <div class="camera-container" style="display: none;" id="cameraContainer">
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
            confirmButtonColor: '#0053C5'
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

    document.getElementById('cameraContainer').style.display = 'block';
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
                width: { ideal: 640 },
                height: { ideal: 480 }
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
            confirmButtonColor: '#0053C5'
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
                    confirmButtonColor: '#0053C5'
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
            confirmButtonColor: '#0053C5'
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
        const response = await fetch('/face/enrollment/store', {
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
                confirmButtonColor: '#0053C5'
            }).then(() => {
                window.location.reload();
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
            confirmButtonColor: '#0053C5'
        });
    }
}

function reEnroll() {
    Swal.fire({
        title: 'Perbarui Data Wajah?',
        text: 'Data wajah lama akan diganti dengan yang baru',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#0053C5',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Perbarui',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '/face/enrollment';
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
                const response = await fetch('/face/delete', {
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
                        confirmButtonColor: '#0053C5'
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
                    confirmButtonColor: '#0053C5'
                });
            }
        }
    });
}

// Load models on page load
loadModels();
</script>
@endpush