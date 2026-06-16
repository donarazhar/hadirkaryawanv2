@extends('karyawan.layouts.presensi')
@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

    :root {
        --primary:       #2563EB;
        --primary-soft:  #EFF6FF;
        --primary-mid:   #BFDBFE;
        --success:       #10B981;
        --success-soft:  #ECFDF5;
        --success-mid:   #6EE7B7;
        --danger:        #EF4444;
        --danger-soft:   #FEF2F2;
        --warning:       #F59E0B;
        --warning-soft:  #FFFBEB;
        --text-900:      #111827;
        --text-600:      #4B5563;
        --text-400:      #9CA3AF;
        --border:        #F1F5F9;
        --border-med:    #E5E7EB;
        --surface:       #FFFFFF;
        --bg:            #F8FAFC;
        --radius-sm:     10px;
        --radius-md:     14px;
        --radius-lg:     18px;
        --radius-xl:     22px;
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
        --shadow-md: 0 4px 6px rgba(0,0,0,0.05), 0 2px 4px rgba(0,0,0,0.04);
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: 'Inter', -apple-system, sans-serif;
        background: var(--bg);
        color: var(--text-900);
        -webkit-font-smoothing: antialiased;
    }

    /* ── PAGE HEADER ── */
    .pg-header {
        background: var(--surface);
        border-bottom: 1px solid var(--border);
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 14px;
        position: sticky;
        top: 0;
        z-index: 50;
    }

    .btn-back {
        width: 36px;
        height: 36px;
        background: var(--bg);
        border: 1px solid var(--border-med);
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        flex-shrink: 0;
        transition: background 0.2s;
    }

    .btn-back:active { background: var(--border-med); }

    .btn-back ion-icon {
        font-size: 20px;
        color: var(--text-600);
    }

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
        margin-top: 1px;
        display: block;
        text-transform: uppercase;
        letter-spacing: 0.6px;
    }

    /* ── BODY ── */
    .enroll-body {
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 14px;
        padding-bottom: 100px;
    }

    /* ── CARD ── */
    .card {
        background: var(--surface);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    /* ── STATUS HERO — ENROLLED ── */
    .status-hero {
        padding: 28px 20px 24px;
        text-align: center;
    }

    .status-ring {
        width: 88px;
        height: 88px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        position: relative;
    }

    .status-ring.success {
        background: var(--success-soft);
        box-shadow: 0 0 0 8px rgba(16,185,129,0.08);
    }

    .status-ring.pending {
        background: var(--danger-soft);
        box-shadow: 0 0 0 8px rgba(239,68,68,0.08);
    }

    .status-ring ion-icon {
        font-size: 40px;
    }

    .status-ring.success ion-icon { color: var(--success); }
    .status-ring.pending ion-icon { color: var(--danger); }

    .status-label {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .status-label.success { color: var(--success); }
    .status-label.pending { color: var(--danger); }

    .status-desc {
        font-size: 13px;
        color: var(--text-600);
        line-height: 1.55;
    }

    /* ── FACE PHOTO ── */
    .face-photo-wrap {
        padding: 0 20px 20px;
        text-align: center;
    }

    .face-photo {
        width: 160px;
        height: 160px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid var(--success-mid);
        box-shadow: 0 0 0 6px rgba(16,185,129,0.10);
    }

    .face-meta {
        font-size: 11px;
        color: var(--text-400);
        margin-top: 8px;
        font-weight: 500;
    }

    /* ── INFO NOTICE ── */
    .notice {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 14px 16px;
        margin: 0 16px 16px;
        border-radius: var(--radius-md);
    }

    .notice.warning {
        background: var(--warning-soft);
        border: 1px solid #FDE68A;
    }

    .notice.info {
        background: var(--primary-soft);
        border: 1px solid var(--primary-mid);
    }

    .notice-icon {
        font-size: 18px;
        flex-shrink: 0;
        margin-top: 1px;
    }

    .notice.warning .notice-icon { color: var(--warning); }
    .notice.info    .notice-icon { color: var(--primary); }

    .notice-text {
        font-size: 12px;
        line-height: 1.6;
        color: var(--text-600);
    }

    .notice-text strong { color: var(--text-900); }

    /* ── STEP GUIDE ── */
    .step-list {
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .step-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .step-num {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: var(--primary-soft);
        border: 1px solid var(--primary-mid);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
        color: var(--primary);
        flex-shrink: 0;
    }

    .step-text {
        font-size: 12px;
        color: var(--text-600);
        line-height: 1.55;
        padding-top: 5px;
    }

    .step-text strong {
        color: var(--text-900);
        font-weight: 600;
    }

    /* ── START BUTTON ── */
    .btn-start {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        padding: 15px 20px;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: var(--radius-lg);
        font-family: 'Inter', sans-serif;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        transition: opacity 0.2s, transform 0.15s;
        box-shadow: 0 4px 14px rgba(37,99,235,0.30);
        -webkit-tap-highlight-color: transparent;
    }

    .btn-start ion-icon { font-size: 20px; }
    .btn-start:active { opacity: 0.88; transform: scale(0.98); }

    /* ── SECTION LABEL ── */
    .sec-label {
        font-size: 11px;
        font-weight: 700;
        color: var(--text-400);
        text-transform: uppercase;
        letter-spacing: 0.7px;
        padding: 0 4px;
        margin-bottom: 2px;
    }

    /* ══════════════════════════════
       FULLSCREEN CAMERA UI
       ══════════════════════════════ */
    .camera-container {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: #000;
        flex-direction: column;
    }

    .camera-container.open { display: flex; }

    #video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        inset: 0;
    }

    /* Oval face guide */
    .face-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -52%);
        width: 220px;
        height: 290px;
        border: 2.5px solid rgba(255,255,255,0.85);
        border-radius: 50%;
        pointer-events: none;
        z-index: 10;
        box-shadow: 0 0 0 9999px rgba(0,0,0,0.42);
    }

    /* Corner accents on oval */
    .face-overlay::before,
    .face-overlay::after {
        content: '';
        position: absolute;
        width: 32px;
        height: 32px;
        border-color: #2563EB;
        border-style: solid;
    }

    .face-overlay::before {
        top: -3px; left: -3px;
        border-width: 3px 0 0 3px;
        border-radius: 50% 0 0 0;
    }

    .face-overlay::after {
        bottom: -3px; right: -3px;
        border-width: 0 3px 3px 0;
        border-radius: 0 0 50% 0;
    }

    /* Camera top bar */
    .cam-topbar {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        padding: 20px 20px 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        z-index: 20;
        background: linear-gradient(to bottom, rgba(0,0,0,0.5) 0%, transparent 100%);
    }

    .cam-close {
        width: 40px;
        height: 40px;
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.25);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
    }

    .cam-close ion-icon { font-size: 22px; color: white; }

    .cam-title {
        font-size: 14px;
        font-weight: 700;
        color: white;
        text-shadow: 0 1px 4px rgba(0,0,0,0.4);
    }

    /* Camera bottom bar */
    .cam-bottombar {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 20px 20px 40px;
        background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, transparent 100%);
        z-index: 20;
        text-align: center;
    }

    .cam-hint {
        font-size: 13px;
        color: rgba(255,255,255,0.85);
        font-weight: 500;
        margin-bottom: 4px;
    }

    /* Countdown display */
    #countdown-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: none;
        z-index: 30;
        text-align: center;
        pointer-events: none;
    }

    .countdown-label {
        font-size: 14px;
        font-weight: 600;
        color: rgba(255,255,255,0.9);
        margin-bottom: 4px;
        text-shadow: 0 2px 8px rgba(0,0,0,0.5);
    }

    #countdown-text {
        font-size: 96px;
        font-weight: 800;
        color: white;
        text-shadow: 0 0 30px rgba(37,99,235,0.8), 0 4px 12px rgba(0,0,0,0.5);
        line-height: 1;
        display: block;
    }

    #faceCanvas { display: none; }

    /* Progress bar for countdown */
    .countdown-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        background: #2563EB;
        border-radius: 0;
        transition: width 1s linear;
        z-index: 25;
    }

    /* Animations */
    @keyframes fadeSlide {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .enroll-body > * {
        animation: fadeSlide 0.35s ease both;
    }
    .enroll-body > *:nth-child(1) { animation-delay: 0.05s; }
    .enroll-body > *:nth-child(2) { animation-delay: 0.10s; }
    .enroll-body > *:nth-child(3) { animation-delay: 0.15s; }
    .enroll-body > *:nth-child(4) { animation-delay: 0.20s; }
</style>

{{-- ── PAGE HEADER ── --}}
<div class="pg-header">
    <a href="{{ route('dashboard') }}" class="btn-back">
        <ion-icon name="chevron-back-outline"></ion-icon>
    </a>
    <div>
        <div class="pg-title">Pendaftaran Wajah</div>
        <span class="pg-sub">Face Recognition Setup</span>
    </div>
</div>

{{-- ── BODY ── --}}
<div class="enroll-body">

    @if($faceData && $faceData->status == 'active')
    {{-- ══ ALREADY ENROLLED STATE ══ --}}

    {{-- Status Card --}}
    <div class="card">
        <div class="status-hero">
            <div class="status-ring success">
                <ion-icon name="checkmark-circle"></ion-icon>
            </div>
            <div class="status-label success">Wajah Terdaftar</div>
            <p class="status-desc">Data wajah Anda sudah tersimpan dan aktif dalam sistem presensi.</p>
        </div>

        @if($faceData->face_image)
        <div class="face-photo-wrap">
            <img src="{{ Storage::url('uploads/faces/' . $faceData->face_image) }}"
                 alt="Foto Wajah" class="face-photo">
            <div class="face-meta">
                Terakhir diperbarui: {{ $faceData->last_updated->format('d M Y, H:i') }}
            </div>
        </div>
        @endif

        <div class="notice warning">
            <ion-icon name="lock-closed" class="notice-icon"></ion-icon>
            <div class="notice-text">
                <strong>Data dikunci demi keamanan.</strong> Untuk memperbarui atau mendaftarkan ulang wajah, silakan hubungi <strong>Admin Pusat</strong>.
            </div>
        </div>
    </div>

    {{-- Status badge row --}}
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
        <div class="card" style="padding:14px; text-align:center;">
            <div style="font-size:10px; font-weight:600; color:var(--text-400); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:6px;">Status</div>
            <div style="display:inline-flex; align-items:center; gap:5px; background:var(--success-soft); color:var(--success); border-radius:50px; padding:4px 12px; font-size:12px; font-weight:700;">
                <ion-icon name="checkmark-circle" style="font-size:14px;"></ion-icon> Aktif
            </div>
        </div>
        <div class="card" style="padding:14px; text-align:center;">
            <div style="font-size:10px; font-weight:600; color:var(--text-400); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:6px;">Keamanan</div>
            <div style="display:inline-flex; align-items:center; gap:5px; background:var(--primary-soft); color:var(--primary); border-radius:50px; padding:4px 12px; font-size:12px; font-weight:700;">
                <ion-icon name="shield-checkmark" style="font-size:14px;"></ion-icon> Terlindungi
            </div>
        </div>
    </div>

    @else
    {{-- ══ NOT ENROLLED STATE ══ --}}

    {{-- Status Card --}}
    <div class="card">
        <div class="status-hero">
            <div class="status-ring pending">
                <ion-icon name="person-add"></ion-icon>
            </div>
            <div class="status-label pending">Belum Terdaftar</div>
            <p class="status-desc">Daftarkan wajah Anda agar dapat melakukan presensi menggunakan fitur verifikasi wajah.</p>
        </div>
    </div>

    {{-- Step Guide --}}
    <div>
        <div class="sec-label">Petunjuk Pendaftaran</div>
        <div class="card" style="margin-top:8px;">
            <div class="step-list">
                <div class="step-item">
                    <div class="step-num">1</div>
                    <div class="step-text"><strong>Posisi wajah</strong> — Pastikan wajah Anda berada di tengah bingkai oval pada kamera.</div>
                </div>
                <div class="step-item">
                    <div class="step-num">2</div>
                    <div class="step-text"><strong>Pencahayaan</strong> — Pastikan ruangan cukup terang agar wajah terlihat jelas.</div>
                </div>
                <div class="step-item">
                    <div class="step-num">3</div>
                    <div class="step-text"><strong>Lepas aksesori</strong> — Lepas kacamata hitam atau masker sebelum pendaftaran.</div>
                </div>
                <div class="step-item">
                    <div class="step-num">4</div>
                    <div class="step-text"><strong>Tetap diam</strong> — Tatap langsung ke kamera dan diam selama hitungan mundur berlangsung.</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Notice --}}
    <div class="notice info" style="margin:0; border-radius:var(--radius-lg);">
        <ion-icon name="information-circle" class="notice-icon"></ion-icon>
        <div class="notice-text">Data wajah hanya digunakan untuk keperluan presensi dan tidak dibagikan kepada pihak lain.</div>
    </div>

    {{-- Start Button --}}
    <button class="btn-start" id="startEnrollment">
        <ion-icon name="camera"></ion-icon>
        Mulai Pendaftaran
    </button>

    @endif

</div>

{{-- ══ FULLSCREEN CAMERA ══ --}}
<div class="camera-container" id="cameraContainer">
    {{-- Top bar --}}
    <div class="cam-topbar">
        <button type="button" class="cam-close" onclick="cancelCamera()">
            <ion-icon name="close-outline"></ion-icon>
        </button>
        <div class="cam-title">Posisikan Wajah</div>
        <div style="width:40px;"></div>{{-- spacer --}}
    </div>

    {{-- Video --}}
    <video id="video" autoplay playsinline muted></video>

    {{-- Oval guide --}}
    <div class="face-overlay"></div>

    {{-- Countdown --}}
    <div id="countdown-overlay">
        <div class="countdown-label">Bersiaplah...</div>
        <span id="countdown-text">10</span>
    </div>

    {{-- Progress bar --}}
    <div class="countdown-progress" id="countdownBar" style="width:100%;"></div>

    {{-- Bottom bar --}}
    <div class="cam-bottombar">
        <div class="cam-hint" id="camHint">Posisikan wajah di dalam bingkai oval</div>
    </div>
</div>

<canvas id="faceCanvas"></canvas>

@endsection

@push('myscript')
<script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.js"></script>
<script>
let video, canvas;
let modelsLoaded = false;
let countdownInterval;

/* ── Load models ── */
async function loadModels() {
    try {
        const MODEL_URL = 'https://cdn.jsdelivr.net/npm/@vladmandic/face-api/model';
        await Promise.all([
            faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
            faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
            faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
        ]);
        modelsLoaded = true;
    } catch (err) {
        console.error('Model load error:', err);
        Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal memuat model face recognition', confirmButtonColor: '#2563EB' });
    }
}

/* ── Start enrollment ── */
document.getElementById('startEnrollment')?.addEventListener('click', async function () {
    if (!modelsLoaded) {
        Swal.fire({ title: 'Memuat Model...', text: 'Harap tunggu sebentar', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        await loadModels();
        Swal.close();
    }

    document.getElementById('cameraContainer').classList.add('open');
    this.style.display = 'none';
    startCamera();
});

/* ── Camera ── */
async function startCamera() {
    try {
        video  = document.getElementById('video');
        canvas = document.getElementById('faceCanvas');

        const stream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: 'user', width: { ideal: 1280 }, height: { ideal: 720 } }
        });

        video.srcObject = stream;
        video.addEventListener('loadedmetadata', () => {
            canvas.width  = video.videoWidth;
            canvas.height = video.videoHeight;
            startCountdown();
        });

    } catch (err) {
        console.error('Camera error:', err);
        cancelCamera();
        Swal.fire({ icon: 'error', title: 'Kamera Error', text: 'Tidak dapat mengakses kamera. Pastikan izin kamera diaktifkan.', confirmButtonColor: '#2563EB' });
    }
}

function cancelCamera() {
    document.getElementById('cameraContainer').classList.remove('open');
    const startBtn = document.getElementById('startEnrollment');
    if (startBtn) startBtn.style.display = 'flex';
    if (video?.srcObject) video.srcObject.getTracks().forEach(t => t.stop());
    if (countdownInterval) clearInterval(countdownInterval);
    document.getElementById('countdown-overlay').style.display = 'none';
    document.getElementById('countdownBar').style.width = '100%';
}

/* ── Countdown ── */
function startCountdown() {
    let total = 10, count = total;
    const overlay = document.getElementById('countdown-overlay');
    const text    = document.getElementById('countdown-text');
    const bar     = document.getElementById('countdownBar');
    const hint    = document.getElementById('camHint');

    overlay.style.display = 'block';
    text.textContent = count;
    hint.textContent = 'Tetap diam dan tatap kamera...';

    countdownInterval = setInterval(() => {
        count--;
        bar.style.width = ((count / total) * 100) + '%';

        if (count > 0) {
            text.textContent = count;
        } else {
            clearInterval(countdownInterval);
            overlay.style.display = 'none';
            hint.textContent = 'Memproses wajah...';
            detectAndCapture();
        }
    }, 1000);
}

/* ── Detect ── */
async function detectAndCapture() {
    if (!modelsLoaded) return;
    try {
        const detections = await faceapi
            .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
            .withFaceLandmarks()
            .withFaceDescriptor();

        if (detections && detections.detection.score > 0.5) {
            await captureFaceData(detections.descriptor);
        } else if (detections) {
            Swal.fire({ icon: 'warning', title: 'Wajah Tidak Jelas', text: 'Coba posisikan wajah lebih dekat ke kamera.', confirmButtonColor: '#2563EB' })
                .then(() => detectAndCapture());
        } else {
            setTimeout(() => detectAndCapture(), 1000);
        }
    } catch (err) {
        console.error('Detection error:', err);
        cancelCamera();
        Swal.fire({ icon: 'error', title: 'Gagal Deteksi', text: 'Tidak dapat mendeteksi wajah. Silakan coba lagi.', confirmButtonColor: '#2563EB' });
    }
}

/* ── Capture & save ── */
async function captureFaceData(descriptor) {
    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    const imageData = canvas.toDataURL('image/png');

    if (video?.srcObject) video.srcObject.getTracks().forEach(t => t.stop());
    cancelCamera();

    Swal.fire({ title: 'Menyimpan...', text: 'Sedang menyimpan data wajah Anda', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

    try {
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
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: result.message, confirmButtonColor: '#2563EB' })
                .then(() => window.location.reload());
        } else {
            throw new Error(result.message);
        }
    } catch (err) {
        console.error('Save error:', err);
        Swal.fire({ icon: 'error', title: 'Gagal Menyimpan', text: err.message || 'Terjadi kesalahan. Silakan coba lagi.', confirmButtonColor: '#2563EB' });
        const startBtn = document.getElementById('startEnrollment');
        if (startBtn) startBtn.style.display = 'flex';
    }
}

// Load models on page load
loadModels();
</script>
@endpush