@extends('karyawan.layouts.simple-face')

@section('content')

<style>
    * {
        box-sizing: border-box;
        -webkit-tap-highlight-color: transparent;
    }

    html,
    body {
        margin: 0;
        padding: 0 !important;
        background: #000;
        min-height: 100vh;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
        overflow-x: hidden;
    }

    .page-wrapper {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    /* ===== CAMERA SECTION ===== */
    .camera-section {
        position: relative;
        width: 100%;
        height: 50vh;
        min-height: 300px;
        max-height: 400px;
        background: #111;
        overflow: hidden;
    }

    .camera-header {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        z-index: 100;
        padding: 12px 16px;
        padding-top: calc(12px + env(safe-area-inset-top, 0px));
        background: linear-gradient(to bottom, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0.4) 70%, transparent 100%);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .btn-close {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        border: 1px solid rgba(255, 255, 255, 0.3);
        flex-shrink: 0;
        cursor: pointer;
    }

    .btn-close:active {
        transform: scale(0.95);
        background: rgba(255, 255, 255, 0.3);
    }

    .btn-close ion-icon {
        font-size: 22px;
        color: white;
    }

    .header-center {
        flex: 1;
        text-align: center;
    }

    .header-title {
        font-size: 15px;
        font-weight: 600;
        color: white;
        margin: 0 0 2px 0;
    }

    .header-time {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.75);
        margin: 0;
    }

    .header-spacer {
        width: 40px;
        flex-shrink: 0;
    }

    /* Shift Badge */
    .shift-badge-float {
        position: absolute;
        top: calc(60px + env(safe-area-inset-top, 0px));
        left: 12px;
        right: 12px;
        z-index: 100;
    }

    .shift-badge-content {
        background: linear-gradient(135deg, #0053C5 0%, #003A8C 100%);
        padding: 10px 14px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 4px 16px rgba(0, 83, 197, 0.5);
    }

    .shift-number-badge {
        width: 32px;
        height: 32px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: 700;
        color: white;
    }

    .shift-info {
        flex: 1;
    }

    .shift-name-text {
        font-size: 13px;
        font-weight: 600;
        color: white;
        margin: 0 0 1px 0;
    }

    .shift-time-text {
        font-size: 11px;
        color: rgba(255, 255, 255, 0.75);
        margin: 0;
    }

    /* ===== WEBCAM STYLES - CRITICAL ===== */
    .camera-view {
        width: 100%;
        height: 100%;
        position: relative;
        background: #000;
    }

    /* Webcam container - pastikan visible */
    .webcam-capture {
        width: 100% !important;
        height: 100% !important;
        position: absolute !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        overflow: hidden !important;
        background: #000 !important;
    }

    .webcam-capture video {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
        display: block !important;
    }

    .face-guide {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 180px;
        height: 230px;
        border: 3px solid rgba(0, 83, 197, 0.9);
        border-radius: 50%;
        pointer-events: none;
        z-index: 50;
        animation: guide-pulse 2.5s ease-in-out infinite;
        box-shadow:
            0 0 0 4px rgba(0, 83, 197, 0.15),
            0 0 30px rgba(0, 83, 197, 0.3),
            inset 0 0 30px rgba(0, 83, 197, 0.1);
    }

    @keyframes guide-pulse {

        0%,
        100% {
            opacity: 0.7;
            transform: translate(-50%, -50%) scale(1);
        }

        50% {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1.02);
        }
    }

    .camera-hint {
        position: absolute;
        bottom: 12px;
        left: 12px;
        right: 12px;
        text-align: center;
        z-index: 60;
    }

    .hint-box {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(0, 0, 0, 0.75);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        padding: 10px 16px;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .hint-box ion-icon {
        font-size: 16px;
        color: #0053C5;
    }

    .hint-box span {
        font-size: 12px;
        color: white;
        font-weight: 500;
    }

    /* Camera Loading State */
    .camera-loading {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        z-index: 40;
    }

    .camera-loading-spinner {
        width: 40px;
        height: 40px;
        border: 3px solid rgba(255, 255, 255, 0.2);
        border-top-color: #0053C5;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
        margin: 0 auto 12px;
    }

    .camera-loading-text {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.8);
    }

    @keyframes spin {
        100% {
            transform: rotate(360deg);
        }
    }

    /* ===== INFO SECTION ===== */
    .info-section {
        flex: 1;
        background: linear-gradient(180deg, #0053C5 0%, #003A8C 100%);
        padding: 16px;
        padding-bottom: calc(100px + env(safe-area-inset-bottom, 20px));
        min-height: 50vh;
    }

    /* Info Cards Grid */
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-bottom: 14px;
    }

    .info-card-small {
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 14px;
        padding: 12px;
        text-align: center;
    }

    .info-icon {
        width: 32px;
        height: 32px;
        margin: 0 auto 6px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .info-icon ion-icon {
        font-size: 16px;
        color: white;
    }

    .info-label {
        font-size: 10px;
        color: rgba(255, 255, 255, 0.7);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 2px;
    }

    .info-value {
        font-size: 13px;
        font-weight: 600;
        color: white;
    }

    /* Map Card */
    .map-card {
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 18px;
        padding: 14px;
        margin-bottom: 14px;
    }

    .map-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px;
    }

    .map-header ion-icon {
        font-size: 18px;
        color: white;
    }

    .map-header span {
        font-size: 14px;
        font-weight: 600;
        color: white;
    }

    /* Map Container - CRITICAL untuk Leaflet */
    .map-container {
        height: 150px;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 10px;
        background: #1a1a2e;
        position: relative;
    }

    #map {
        height: 100% !important;
        width: 100% !important;
        z-index: 1;
    }

    /* Fix Leaflet z-index issues */
    .leaflet-pane {
        z-index: 1 !important;
    }

    .leaflet-control {
        z-index: 2 !important;
    }

    .map-loading {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        z-index: 0;
    }

    .map-loading ion-icon {
        font-size: 32px;
        color: rgba(255, 255, 255, 0.3);
        animation: pulse-icon 1.5s ease-in-out infinite;
    }

    @keyframes pulse-icon {

        0%,
        100% {
            opacity: 0.3;
        }

        50% {
            opacity: 0.6;
        }
    }

    .map-loading-text {
        font-size: 11px;
        color: rgba(255, 255, 255, 0.5);
        margin-top: 6px;
    }

    .location-text {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.8);
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 8px 10px;
        background: rgba(0, 0, 0, 0.2);
        border-radius: 8px;
    }

    .location-text ion-icon {
        font-size: 16px;
        color: white;
        flex-shrink: 0;
    }

    .location-text span {
        flex: 1;
        word-break: break-all;
    }

    /* Distance Status */
    .distance-badge {
        margin-top: 10px;
        padding: 12px;
        border-radius: 12px;
        display: none;
        align-items: center;
        gap: 10px;
    }

    .distance-badge.show {
        display: flex;
    }

    .distance-badge.valid {
        background: rgba(16, 185, 129, 0.2);
        border: 1px solid rgba(16, 185, 129, 0.4);
    }

    .distance-badge.invalid {
        background: rgba(239, 68, 68, 0.2);
        border: 1px solid rgba(239, 68, 68, 0.4);
    }

    .distance-badge ion-icon {
        font-size: 24px;
        flex-shrink: 0;
    }

    .distance-badge.valid ion-icon {
        color: #10B981;
    }

    .distance-badge.invalid ion-icon {
        color: #EF4444;
    }

    .distance-info {
        flex: 1;
    }

    .distance-value {
        font-size: 14px;
        font-weight: 600;
        color: white;
        margin-bottom: 2px;
    }

    .distance-desc {
        font-size: 11px;
        color: rgba(255, 255, 255, 0.7);
    }

    /* Tips */
    .tips-card {
        background: rgba(16, 185, 129, 0.15);
        border: 1px solid rgba(16, 185, 129, 0.3);
        border-radius: 14px;
        padding: 12px 14px;
    }

    .tips-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px;
    }

    .tips-icon {
        width: 26px;
        height: 26px;
        background: rgba(16, 185, 129, 0.25);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .tips-icon ion-icon {
        font-size: 14px;
        color: #10B981;
    }

    .tips-title {
        font-size: 13px;
        font-weight: 600;
        color: white;
    }

    .tips-list {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .tip-item {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        color: rgba(255, 255, 255, 0.9);
        background: rgba(255, 255, 255, 0.1);
        padding: 5px 10px;
        border-radius: 6px;
    }

    .tip-item ion-icon {
        font-size: 12px;
        color: #10B981;
    }

    /* ===== CAPTURE BUTTON ===== */
    .capture-container {
        position: fixed;
        bottom: 24px;
        bottom: calc(24px + env(safe-area-inset-bottom, 0px));
        left: 50%;
        transform: translateX(-50%);
        z-index: 9999;
    }

    .btn-capture {
        width: 72px;
        height: 72px;
        background: linear-gradient(135deg, #0053C5 0%, #003A8C 100%);
        border: 4px solid white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 6px 24px rgba(0, 83, 197, 0.6);
        transition: all 0.2s ease;
        outline: none;
        -webkit-appearance: none;
        appearance: none;
    }

    .btn-capture:active:not(:disabled) {
        transform: scale(0.92);
    }

    .btn-capture:disabled {
        background: linear-gradient(135deg, #6B7280 0%, #4B5563 100%);
        opacity: 0.5;
        cursor: not-allowed;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
    }

    .btn-capture ion-icon {
        font-size: 32px;
        color: white;
        pointer-events: none;
    }

    /* ===== LOADING OVERLAY ===== */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.92);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 100000;
        flex-direction: column;
        padding: 20px;
    }

    .loading-overlay.show {
        display: flex;
    }

    .loading-content {
        text-align: center;
        max-width: 280px;
    }

    .loading-spinner {
        width: 50px;
        height: 50px;
        border: 3px solid rgba(255, 255, 255, 0.15);
        border-top-color: #0053C5;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
        margin: 0 auto 20px;
    }

    .loading-text {
        font-size: 16px;
        font-weight: 600;
        color: white;
        margin-bottom: 6px;
    }

    .loading-detail {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.6);
    }

    /* ===== ERROR STATE ===== */
    .error-state {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        padding: 20px;
        z-index: 45;
        display: none;
    }

    .error-state.show {
        display: block;
    }

    .error-state ion-icon {
        font-size: 48px;
        color: #EF4444;
        margin-bottom: 12px;
    }

    .error-state p {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.8);
        margin: 0 0 16px 0;
    }

    .error-state button {
        padding: 10px 20px;
        background: #0053C5;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }
</style>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />

<div class="page-wrapper">
    <!-- Camera Section -->
    <div class="camera-section">
        <!-- Header -->
        <div class="camera-header">
            <a href="{{ route('face-presensi.dashboard') }}" class="btn-close">
                <ion-icon name="close"></ion-icon>
            </a>
            <div class="header-center">
                <p class="header-title">Face Recognition</p>
                <p class="header-time" id="current-time">{{ \Carbon\Carbon::now('Asia/Jakarta')->format('H:i:s') }}</p>
            </div>
            <div class="header-spacer"></div>
        </div>

        <!-- Shift Badge (if multi-shift) -->
        @if(isset($shift_ke) && $shift_ke && isset($current_shift))
        <div class="shift-badge-float">
            <div class="shift-badge-content">
                <div class="shift-number-badge">{{ $shift_ke }}</div>
                <div class="shift-info">
                    <p class="shift-name-text">{{ $current_shift->nama_shift }}</p>
                    <p class="shift-time-text">{{ date('H:i', strtotime($current_shift->jam_masuk)) }} - {{ date('H:i', strtotime($current_shift->jam_pulang)) }}</p>
                </div>
            </div>
        </div>
        <input type="hidden" id="shift_ke_input" value="{{ $shift_ke }}">
        <input type="hidden" id="shift_jam_masuk" value="{{ $current_shift->jam_masuk }}">
        <input type="hidden" id="shift_jam_pulang" value="{{ $current_shift->jam_pulang }}">
        <input type="hidden" id="shift_nama" value="{{ $current_shift->nama_shift }}">
        @else
        <input type="hidden" id="shift_ke_input" value="">
        @endif

        <!-- Camera View -->
        <div class="camera-view">
            <!-- Camera Loading -->
            <div class="camera-loading" id="camera-loading">
                <div class="camera-loading-spinner"></div>
                <p class="camera-loading-text">Memuat kamera...</p>
            </div>

            <!-- Error State -->
            <div class="error-state" id="camera-error">
                <ion-icon name="camera-outline"></ion-icon>
                <p>Tidak dapat mengakses kamera.<br>Pastikan izin kamera diaktifkan.</p>
                <button onclick="location.reload()">Coba Lagi</button>
            </div>

            <!-- Webcam Container -->
            <div class="webcam-capture" id="webcam-container"></div>

            <!-- Face Guide -->
            <div class="face-guide" id="face-guide" style="display: none;"></div>

            <!-- Camera Hint -->
            <div class="camera-hint" id="camera-hint" style="display: none;">
                <div class="hint-box">
                    <ion-icon name="scan-outline"></ion-icon>
                    <span>Posisikan wajah di dalam bingkai</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Section -->
    <div class="info-section">
        <!-- Info Cards -->
        <div class="info-grid">
            <div class="info-card-small">
                <div class="info-icon">
                    <ion-icon name="person-outline"></ion-icon>
                </div>
                <div class="info-label">NIK</div>
                <div class="info-value">{{ Auth::guard('karyawan')->user()->nik }}</div>
            </div>
            <div class="info-card-small">
                <div class="info-icon">
                    <ion-icon name="shield-checkmark-outline"></ion-icon>
                </div>
                <div class="info-label">Face ID</div>
                <div class="info-value">{{ $faceData ? 'Aktif' : 'Belum Daftar' }}</div>
            </div>
        </div>

        <!-- Map Card -->
        <div class="map-card">
            <div class="map-header">
                <ion-icon name="location-outline"></ion-icon>
                <span>Lokasi Anda</span>
            </div>
            <div class="map-container">
                <!-- Map Loading -->
                <div class="map-loading" id="map-loading">
                    <ion-icon name="location-outline"></ion-icon>
                    <p class="map-loading-text">Memuat peta...</p>
                </div>
                <div id="map"></div>
            </div>
            <div class="location-text">
                <ion-icon name="navigate-outline"></ion-icon>
                <span id="coords-display">Mendeteksi lokasi...</span>
            </div>

            <!-- Distance Badge -->
            <div class="distance-badge" id="distance-status">
                <ion-icon name="alert-circle"></ion-icon>
                <div class="distance-info">
                    <div class="distance-value" id="distance-value">-</div>
                    <div class="distance-desc" id="distance-desc">Menghitung jarak...</div>
                </div>
            </div>
        </div>

        <!-- Tips -->
        <div class="tips-card">
            <div class="tips-header">
                <div class="tips-icon">
                    <ion-icon name="bulb-outline"></ion-icon>
                </div>
                <span class="tips-title">Tips Verifikasi</span>
            </div>
            <div class="tips-list">
                <div class="tip-item"><ion-icon name="checkmark"></ion-icon> Wajah jelas</div>
                <div class="tip-item"><ion-icon name="checkmark"></ion-icon> Cahaya cukup</div>
                <div class="tip-item"><ion-icon name="checkmark"></ion-icon> Tatap kamera</div>
                <div class="tip-item"><ion-icon name="checkmark"></ion-icon> Tanpa masker</div>
            </div>
        </div>
    </div>
</div>

<!-- Capture Button -->
<div class="capture-container">
    <button class="btn-capture" id="btn-capture" type="button" disabled>
        <ion-icon name="scan"></ion-icon>
    </button>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loading-overlay">
    <div class="loading-content">
        <div class="loading-spinner"></div>
        <p class="loading-text" id="loading-text">Memverifikasi...</p>
        <p class="loading-detail" id="loading-detail">Mohon tunggu</p>
    </div>
</div>

<!-- Hidden Inputs -->
<input type="hidden" id="lokasi" value="">

<!-- Audio -->
<audio id="success_sound" src="{{ asset('assets/sound/notifikasi_in.mp3') }}" preload="auto"></audio>

@endsection

@push('myscript')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

<!-- Webcam JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>

<!-- Face-API.js -->
<script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.js"></script>

<!-- Office Location Config -->
@if($lok_kantor)
@php
    $lokasi_parts = explode(',', $lok_kantor->lokasi_cabang);
    $office_lat = trim($lokasi_parts[0]);
    $office_lng = trim($lokasi_parts[1]);
@endphp
<script>
    window.OFFICE_CONFIG = {
        lat: {{ $office_lat }},
        lng: {{ $office_lng }},
        name: "{{ $lok_kantor->nama_cabang }}",
        radius: {{ $lok_kantor->radius_cabang ?? 1000 }}
    };
</script>
@else
<script>
    window.OFFICE_CONFIG = null;
</script>
@endif

<script>
    (function() {
        'use strict';

        // ===== STATE VARIABLES =====
        let webcamReady = false;
        let modelsLoaded = false;
        let map = null;
        let userMarker = null;
        let userLocation = null;
        let withinRadius = false;

        // ===== DOM ELEMENTS =====
        const elements = {
            btnCapture: document.getElementById('btn-capture'),
            lokasiInput: document.getElementById('lokasi'),
            coordsDisplay: document.getElementById('coords-display'),
            distanceStatus: document.getElementById('distance-status'),
            distanceValue: document.getElementById('distance-value'),
            distanceDesc: document.getElementById('distance-desc'),
            cameraLoading: document.getElementById('camera-loading'),
            cameraError: document.getElementById('camera-error'),
            faceGuide: document.getElementById('face-guide'),
            cameraHint: document.getElementById('camera-hint'),
            mapLoading: document.getElementById('map-loading'),
            loadingOverlay: document.getElementById('loading-overlay'),
            loadingText: document.getElementById('loading-text'),
            loadingDetail: document.getElementById('loading-detail'),
            currentTime: document.getElementById('current-time'),
            successSound: document.getElementById('success_sound')
        };

        // ===== UTILITY FUNCTIONS =====
        function calculateDistance(lat1, lon1, lat2, lon2) {
            const R = 6371e3; // Earth radius in meters
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

        function validateTime() {
            const now = new Date();
            const currentMinutes = now.getHours() * 60 + now.getMinutes();

            const shiftJamMasuk = document.getElementById('shift_jam_masuk');
            const shiftNama = document.getElementById('shift_nama');

            if (shiftJamMasuk && shiftJamMasuk.value) {
                const [hours, minutes] = shiftJamMasuk.value.split(':').map(Number);
                const shiftStartMinutes = hours * 60 + minutes;
                const toleranceMinutes = shiftStartMinutes - 60; // 60 minutes before
                if (currentMinutes < toleranceMinutes) {
                    const toleranceTime = `${String(Math.floor(toleranceMinutes / 60)).padStart(2, '0')}:${String(toleranceMinutes % 60).padStart(2, '0')}`;
                    return {
                        valid: false,
                        message: `Belum waktunya absen untuk ${shiftNama?.value || 'shift ini'}. Bisa mulai jam ${toleranceTime}`
                    };
                }
            }

            return {
                valid: true
            };
        }

        function updateButtonState() {
            if (!elements.btnCapture) return;

            const timeCheck = validateTime();
            const canCapture = webcamReady && withinRadius && timeCheck.valid;

            elements.btnCapture.disabled = !canCapture;

            console.log('[Button State]', {
                webcamReady,
                withinRadius,
                timeValid: timeCheck.valid,
                canCapture
            });
        }

        function showLoading(text, detail) {
            if (elements.loadingText) elements.loadingText.textContent = text || 'Memproses...';
            if (elements.loadingDetail) elements.loadingDetail.textContent = detail || 'Mohon tunggu';
            if (elements.loadingOverlay) elements.loadingOverlay.classList.add('show');
        }

        function hideLoading() {
            if (elements.loadingOverlay) elements.loadingOverlay.classList.remove('show');
        }

        // ===== TIME UPDATE =====
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            });
            if (elements.currentTime) {
                elements.currentTime.textContent = timeString;
            }
        }

        setInterval(updateTime, 1000);

        // ===== WEBCAM INITIALIZATION =====
        function initWebcam() {
            console.log('[Webcam] Initializing...');

            const container = document.getElementById('webcam-container');
            if (!container) {
                console.error('[Webcam] Container not found!');
                return;
            }

            // Get dimensions
            const section = document.querySelector('.camera-section');
            const width = section ? section.offsetWidth : window.innerWidth;
            const height = section ? section.offsetHeight : 300;

            console.log('[Webcam] Dimensions:', width, 'x', height);

            Webcam.set({
                width: width,
                height: height,
                image_format: 'jpeg',
                jpeg_quality: 90,
                flip_horiz: true,
                constraints: {
                    video: {
                        facingMode: 'user',
                        width: {
                            ideal: 640
                        },
                        height: {
                            ideal: 480
                        }
                    }
                }
            });

            Webcam.on('live', function() {
                console.log('[Webcam] Camera is LIVE!');
                webcamReady = true;

                // Hide loading, show guides
                if (elements.cameraLoading) elements.cameraLoading.style.display = 'none';
                if (elements.faceGuide) elements.faceGuide.style.display = 'block';
                if (elements.cameraHint) elements.cameraHint.style.display = 'block';

                updateButtonState();
            });

            Webcam.on('error', function(err) {
                console.error('[Webcam] Error:', err);

                if (elements.cameraLoading) elements.cameraLoading.style.display = 'none';
                if (elements.cameraError) elements.cameraError.classList.add('show');

                Swal.fire({
                    icon: 'error',
                    title: 'Kamera Error',
                    text: 'Tidak dapat mengakses kamera. Pastikan izin kamera sudah diaktifkan.',
                    confirmButtonColor: '#0053C5'
                });
            });

            try {
                Webcam.attach('#webcam-container');
                console.log('[Webcam] Attached to container');
            } catch (e) {
                console.error('[Webcam] Attach error:', e);
            }
        }

        // ===== GEOLOCATION & MAP =====
        function initGeolocation() {
            console.log('[GPS] Initializing...');

            if (!navigator.geolocation) {
                console.error('[GPS] Not supported');
                if (elements.coordsDisplay) {
                    elements.coordsDisplay.textContent = 'GPS tidak didukung';
                }
                Swal.fire({
                    icon: 'error',
                    title: 'GPS Tidak Didukung',
                    text: 'Browser Anda tidak mendukung GPS.',
                    confirmButtonColor: '#0053C5'
                });
                return;
            }

            navigator.geolocation.getCurrentPosition(
                onGeolocationSuccess,
                onGeolocationError, {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 0
                }
            );
        }

        function onGeolocationSuccess(position) {
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;

            console.log('[GPS] Success:', latitude, longitude);

            userLocation = {
                latitude,
                longitude
            };

            // Update hidden input
            if (elements.lokasiInput) {
                elements.lokasiInput.value = `${latitude},${longitude}`;
            }

            // Update display
            if (elements.coordsDisplay) {
                elements.coordsDisplay.textContent = `${latitude.toFixed(6)}, ${longitude.toFixed(6)}`;
            }

            // Calculate distance if office config exists
            if (window.OFFICE_CONFIG) {
                const distance = calculateDistance(
                    window.OFFICE_CONFIG.lat,
                    window.OFFICE_CONFIG.lng,
                    latitude,
                    longitude
                );

                withinRadius = distance <= window.OFFICE_CONFIG.radius;

                // Update distance badge
                if (elements.distanceStatus) {
                    elements.distanceStatus.classList.add('show');
                    elements.distanceStatus.classList.remove('valid', 'invalid');
                    elements.distanceStatus.classList.add(withinRadius ? 'valid' : 'invalid');

                    const icon = elements.distanceStatus.querySelector('ion-icon');
                    if (icon) {
                        icon.setAttribute('name', withinRadius ? 'checkmark-circle' : 'close-circle');
                    }
                }

                if (elements.distanceValue) {
                    elements.distanceValue.textContent = `${distance} meter dari kantor`;
                }

                if (elements.distanceDesc) {
                    elements.distanceDesc.textContent = withinRadius ?
                        'Dalam radius ✓' :
                        `Terlalu jauh! Max: ${window.OFFICE_CONFIG.radius}m`;
                }
            } else {
                // No office config, assume within radius
                withinRadius = true;
            }

            updateButtonState();
            initMap(latitude, longitude);
        }

        function onGeolocationError(error) {
            console.error('[GPS] Error:', error);

            let message = 'Tidak dapat mendapatkan lokasi.';
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    message = 'Akses lokasi ditolak. Aktifkan GPS dan izinkan akses lokasi.';
                    break;
                case error.POSITION_UNAVAILABLE:
                    message = 'Informasi lokasi tidak tersedia.';
                    break;
                case error.TIMEOUT:
                    message = 'Waktu mendapatkan lokasi habis. Coba lagi.';
                    break;
            }

            if (elements.coordsDisplay) {
                elements.coordsDisplay.textContent = 'Lokasi gagal dideteksi';
            }

            Swal.fire({
                icon: 'error',
                title: 'Lokasi Error',
                text: message,
                confirmButtonColor: '#0053C5',
                confirmButtonText: 'Coba Lagi'
            }).then((result) => {
                if (result.isConfirmed) {
                    initGeolocation();
                }
            });
        }

        function initMap(lat, lng) {
            console.log('[Map] Initializing at:', lat, lng);

            // Hide loading
            if (elements.mapLoading) {
                elements.mapLoading.style.display = 'none';
            }

            try {
                // Initialize map
                map = L.map('map', {
                    zoomControl: false,
                    attributionControl: false
                }).setView([lat, lng], 17);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19
                }).addTo(map);

                // User marker
                const userIcon = L.divIcon({
                    className: 'custom-marker',
                    html: `<div style="
                background: linear-gradient(135deg, #0053C5, #003A8C);
                width: 32px;
                height: 32px;
                border-radius: 50%;
                border: 3px solid white;
                box-shadow: 0 2px 8px rgba(0,0,0,0.3);
                display: flex;
                align-items: center;
                justify-content: center;
            "><ion-icon name="person" style="color: white; font-size: 16px;"></ion-icon></div>`,
                    iconSize: [32, 32],
                    iconAnchor: [16, 16]
                });

                userMarker = L.marker([lat, lng], {
                    icon: userIcon
                }).addTo(map);
                userMarker.bindPopup('<b>Lokasi Anda</b>').openPopup();

                // Office marker and radius circle
                if (window.OFFICE_CONFIG) {
                    const officeIcon = L.divIcon({
                        className: 'custom-marker',
                        html: `<div style="
                    background: linear-gradient(135deg, #10B981, #059669);
                    width: 32px;
                    height: 32px;
                    border-radius: 50%;
                    border: 3px solid white;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                "><ion-icon name="business" style="color: white; font-size: 16px;"></ion-icon></div>`,
                        iconSize: [32, 32],
                        iconAnchor: [16, 16]
                    });

                    const officeMarker = L.marker(
                        [window.OFFICE_CONFIG.lat, window.OFFICE_CONFIG.lng], {
                            icon: officeIcon
                        }
                    ).addTo(map);
                    officeMarker.bindPopup(`<b>${window.OFFICE_CONFIG.name}</b>`);

                    // Radius circle
                    L.circle([window.OFFICE_CONFIG.lat, window.OFFICE_CONFIG.lng], {
                        color: withinRadius ? '#10B981' : '#EF4444',
                        fillColor: withinRadius ? '#10B981' : '#EF4444',
                        fillOpacity: 0.1,
                        weight: 2,
                        radius: window.OFFICE_CONFIG.radius
                    }).addTo(map);

                    // Fit bounds to show both markers
                    const group = L.featureGroup([userMarker, officeMarker]);
                    map.fitBounds(group.getBounds().pad(0.3));
                }

                console.log('[Map] Initialized successfully');

                // Force map resize after a short delay
                setTimeout(() => {
                    map.invalidateSize();
                }, 300);

            } catch (e) {
                console.error('[Map] Error:', e);
            }
        }

        // ===== FACE API =====
        async function loadFaceModels() {
            if (modelsLoaded) return true;

            try {
                console.log('[FaceAPI] Loading models...');
                const MODEL_URL = 'https://cdn.jsdelivr.net/npm/@vladmandic/face-api/model';

                await Promise.all([
                    faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
                    faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
                    faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
                ]);

                modelsLoaded = true;
                console.log('[FaceAPI] Models loaded successfully');
                return true;
            } catch (e) {
                console.error('[FaceAPI] Error loading models:', e);
                return false;
            }
        }

        async function getReferenceFaceDescriptor() {
            const response = await fetch('/face-presensi/descriptor', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                }
            });

            const result = await response.json();

            if (result.success) {
                return new Float32Array(result.descriptor);
            } else {
                throw new Error(result.message || 'Gagal mengambil data wajah');
            }
        }

        async function verifyFace() {
            const video = document.querySelector('.webcam-capture video');

            if (!video) {
                throw new Error('Video element tidak ditemukan');
            }

            console.log('[FaceAPI] Detecting face...');

            const detection = await faceapi
                .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
                .withFaceLandmarks()
                .withFaceDescriptor();

            if (!detection) {
                throw new Error('Wajah tidak terdeteksi. Pastikan wajah terlihat jelas di dalam bingkai.');
            }

            const confidence = (detection.detection.score * 100).toFixed(1);
            console.log('[FaceAPI] Detection confidence:', confidence + '%');

            if (detection.detection.score < 0.5) {
                throw new Error(`Deteksi wajah kurang jelas (${confidence}%). Coba posisikan wajah lebih baik.`);
            }

            // Get reference descriptor
            const referenceDescriptor = await getReferenceFaceDescriptor();

            // Calculate distance/similarity
            const distance = faceapi.euclideanDistance(detection.descriptor, referenceDescriptor);
            const similarity = ((1 - distance) * 100).toFixed(1);

            console.log('[FaceAPI] Similarity:', similarity + '%', 'Distance:', distance.toFixed(4));

            if (elements.loadingDetail) {
                elements.loadingDetail.innerHTML = `Kecocokan: <strong>${similarity}%</strong>`;
            }

            if (distance > 0.6) {
                throw new Error(`Verifikasi gagal! Kecocokan: ${similarity}%. Wajah tidak cocok dengan data terdaftar.`);
            }

            return {
                success: true,
                confidence: confidence,
                similarity: similarity
            };
        }

        // ===== CAPTURE HANDLER =====
        async function handleCapture() {
            console.log('[Capture] Starting...');

            // Validate location
            if (!withinRadius) {
                const distance = window.OFFICE_CONFIG && userLocation ?
                    calculateDistance(window.OFFICE_CONFIG.lat, window.OFFICE_CONFIG.lng, userLocation.latitude, userLocation.longitude) :
                    0;

                Swal.fire({
                    icon: 'error',
                    title: 'Diluar Radius',
                    html: `Anda berada <strong>${distance}m</strong> dari kantor.<br>Maksimal radius: <strong>${window.OFFICE_CONFIG?.radius || 1000}m</strong>`,
                    confirmButtonColor: '#0053C5'
                });
                return;
            }

            // Validate time
            const timeCheck = validateTime();
            if (!timeCheck.valid) {
                Swal.fire({
                    icon: 'error',
                    title: 'Belum Waktunya',
                    text: timeCheck.message,
                    confirmButtonColor: '#0053C5'
                });
                return;
            }

            // Validate webcam
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
                // Vibrate feedback
                if ('vibrate' in navigator) {
                    navigator.vibrate(20);
                }

                showLoading('Memverifikasi wajah...', 'Mohon tunggu');

                // Load models if needed
                if (!modelsLoaded) {
                    showLoading('Memuat model AI...', 'Proses pertama kali mungkin lebih lama');
                    const loaded = await loadFaceModels();
                    if (!loaded) {
                        throw new Error('Gagal memuat model face recognition');
                    }
                }

                // Verify face
                showLoading('Mendeteksi wajah...', 'Pastikan wajah terlihat jelas');
                await verifyFace();

                // Send to server
                showLoading('Menyimpan presensi...', 'Hampir selesai');

                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('verified', 'true');
                formData.append('lokasi', elements.lokasiInput.value);

                const shiftKe = document.getElementById('shift_ke_input')?.value;
                if (shiftKe) {
                    formData.append('shift_ke', shiftKe);
                }

                const response = await fetch('/face-presensi/store', {
                    method: 'POST',
                    body: formData
                });

                const responseText = await response.text();
                hideLoading();

                const [status, message] = responseText.split('|');

                if (status === 'success') {
                    if (elements.successSound) {
                        elements.successSound.play().catch(() => {});
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        html: `<strong>${message}</strong>`,
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
                        text: message || 'Terjadi kesalahan',
                        confirmButtonColor: '#0053C5'
                    });
                }

            } catch (error) {
                hideLoading();
                console.error('[Capture] Error:', error);

                Swal.fire({
                    icon: 'error',
                    title: 'Verifikasi Gagal',
                    html: error.message || 'Verifikasi wajah gagal. Silakan coba lagi.',
                    confirmButtonColor: '#0053C5'
                });
            }
        }

        // ===== EVENT LISTENERS =====
        if (elements.btnCapture) {
            elements.btnCapture.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                handleCapture();
            });
        }

        // ===== INITIALIZATION =====
        function init() {
            console.log('[Init] Starting app initialization...');

            // Initialize webcam
            initWebcam();

            // Initialize geolocation
            initGeolocation();

            // Preload face models in background
            loadFaceModels();

            console.log('[Init] Initialization complete');
        }

        // Start when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }
    })();
</script>
@endpush