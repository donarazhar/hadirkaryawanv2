@extends('karyawan.layouts.simple-face')

@section('content')

<style>
    body {
        background: #f0f4f8;
    }

    .page-header {
        background: linear-gradient(135deg, #0053C5 0%, #7c3aed 100%);
        padding: 24px 20px 60px 20px;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -40%;
        right: -15%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        filter: blur(60px);
    }

    .header-title {
        position: relative;
        z-index: 2;
    }

    .header-title h1 {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .header-title p {
        font-size: 14px;
        opacity: 0.9;
    }

    .content-section {
        padding: 0 20px 40px 20px;
        margin-top: -40px;
    }

    /* ===== QUICK ACTION BUTTONS ===== */
    .quick-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 20px;
    }

    .action-btn {
        background: white;
        border-radius: 20px;
        padding: 24px 16px;
        text-align: center;
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .action-btn:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }

    .action-btn.primary {
        background: linear-gradient(135deg, #0053C5 0%, #7c3aed 100%);
        border: none;
        grid-column: 1 / -1;
        /* Full width */
    }

    .action-icon {
        width: 60px;
        height: 60px;
        margin: 0 auto 12px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .action-btn.primary .action-icon {
        background: rgba(255, 255, 255, 0.2);
    }

    .action-btn.primary .action-icon ion-icon {
        font-size: 32px;
        color: white;
    }

    .action-btn.secondary .action-icon {
        background: linear-gradient(135deg, rgba(139, 92, 246, 0.1) 0%, rgba(124, 58, 237, 0.1) 100%);
    }

    .action-btn.secondary .action-icon ion-icon {
        font-size: 28px;
        color: #0053C5;
    }

    .action-title {
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .action-btn.primary .action-title {
        color: white;
    }

    .action-btn.secondary .action-title {
        color: #1e293b;
    }

    .action-subtitle {
        font-size: 12px;
        margin: 0;
    }

    .action-btn.primary .action-subtitle {
        color: rgba(255, 255, 255, 0.8);
    }

    .action-btn.secondary .action-subtitle {
        color: #64748b;
    }

    /* ===== CARDS ===== */
    .card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        margin-bottom: 16px;
    }

    .card-title {
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 16px;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .card-title ion-icon {
        font-size: 22px;
        color: #0053C5;
    }

    .stat-box {
        text-align: center;
        padding: 24px;
        background: linear-gradient(135deg, rgba(139, 92, 246, 0.1) 0%, rgba(124, 58, 237, 0.05) 100%);
        border-radius: 16px;
        border: 1px solid rgba(139, 92, 246, 0.2);
    }

    .stat-value {
        font-size: 48px;
        font-weight: 700;
        color: #0053C5;
        margin-bottom: 4px;
        line-height: 1;
    }

    .stat-label {
        font-size: 14px;
        color: #64748b;
        font-weight: 600;
    }

    .history-item {
        padding: 16px;
        background: #f8fafc;
        border-radius: 12px;
        margin-bottom: 10px;
        border-left: 4px solid #0053C5;
    }

    .history-item:last-child {
        margin-bottom: 0;
    }

    .history-time {
        font-size: 14px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 6px;
    }

    .history-date {
        font-size: 12px;
        color: #64748b;
        margin-bottom: 4px;
    }

    .history-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 600;
        margin-top: 6px;
    }

    .history-badge.complete {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .history-badge.pending {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    .warning-card {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(220, 38, 38, 0.05) 100%);
        border: 2px dashed #ef4444;
        text-align: center;
        padding: 30px 24px;
    }

    .warning-icon {
        width: 70px;
        height: 70px;
        margin: 0 auto 16px;
        background: rgba(239, 68, 68, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .warning-icon ion-icon {
        font-size: 36px;
        color: #ef4444;
    }

    .warning-title {
        font-size: 20px;
        font-weight: 700;
        color: #ef4444;
        margin-bottom: 8px;
    }

    .warning-text {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 20px;
    }

    .btn-daftar {
        display: inline-block;
        padding: 14px 28px;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 700;
        font-size: 15px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .btn-daftar:hover {
        background: #dc2626;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #64748b;
    }

    .empty-state ion-icon {
        font-size: 64px;
        opacity: 0.3;
        margin-bottom: 12px;
    }

    .empty-state p {
        margin: 0;
        font-size: 14px;
    }
</style>

<div class="page-header">
    <div class="header-title">
        <h1>Hi, {{ explode(' ', $nama_lengkap)[0] }}! 👋</h1>
        <p>{{ \Carbon\Carbon::now('Asia/Jakarta')->isoFormat('dddd, D MMMM Y') }}</p>
    </div>
</div>

<div class="content-section">
    @if(!$faceData)
    <!-- Warning: Belum Daftar Face -->
    <div class="card warning-card">
        <div class="warning-icon">
            <ion-icon name="warning"></ion-icon>
        </div>
        <h3 class="warning-title">Belum Terdaftar</h3>
        <p class="warning-text">
            Daftarkan wajah Anda terlebih dahulu untuk menggunakan sistem presensi face recognition
        </p>
        <a href="{{ route('face-presensi.enrollment') }}" class="btn-daftar">
            <ion-icon name="scan-outline" style="vertical-align: middle; margin-right: 6px;"></ion-icon>
            Daftar Wajah Sekarang
        </a>
    </div>
    @else
    <!-- Quick Actions -->
    <div class="quick-actions">
        <!-- BUTTON UTAMA: Mulai Presensi -->
        <a href="{{ route('face-presensi.create') }}" class="action-btn primary">
            <div class="action-icon">
                <ion-icon name="scan"></ion-icon>
            </div>
            <h3 class="action-title">Mulai Presensi</h3>
            <p class="action-subtitle">Face Recognition + GPS</p>
        </a>

        <!-- Button Kelola Wajah -->
        <a href="{{ route('face-presensi.enrollment') }}" class="action-btn secondary">
            <div class="action-icon">
                <ion-icon name="settings-outline"></ion-icon>
            </div>
            <h3 class="action-title">Kelola Wajah</h3>
            <p class="action-subtitle">Update/Hapus</p>
        </a>

        <!-- Button Riwayat -->
        <a href="#riwayat-section" class="action-btn secondary">
            <div class="action-icon">
                <ion-icon name="time-outline"></ion-icon>
            </div>
            <h3 class="action-title">Riwayat</h3>
            <p class="action-subtitle">Lihat Semua</p>
        </a>
    </div>

    <!-- Presensi Hari Ini -->
    <div class="card">
        <h3 class="card-title">
            <ion-icon name="today-outline"></ion-icon>
            Presensi Hari Ini
        </h3>

        @if($presensi_hari_ini->count() > 0)
        @foreach($presensi_hari_ini as $item)
        <div class="history-item">
            <div class="history-time">
                Masuk: {{ $item->jam_masuk ? date('H:i', strtotime($item->jam_masuk)) : '-' }} |
                Pulang: {{ $item->jam_pulang ? date('H:i', strtotime($item->jam_pulang)) : '-' }}
            </div>
            <div class="history-date">
                {{ \Carbon\Carbon::parse($item->tanggal)->isoFormat('dddd, D MMMM Y') }}
            </div>
            @if($item->lokasi)
            <div class="history-date">
                📍 <a href="{{ $item->google_maps_link }}" target="_blank" style="color: #0053C5; text-decoration: none; font-weight: 600;">
                    Lihat Lokasi
                </a>
            </div>
            @endif
            <div class="history-badge {{ $item->jam_pulang ? 'complete' : 'pending' }}">
                <ion-icon name="{{ $item->jam_pulang ? 'checkmark-done' : 'time' }}"></ion-icon>
                {{ $item->jam_pulang ? 'Lengkap' : 'Belum Pulang' }}
            </div>
        </div>
        @endforeach
        @else
        <div class="empty-state">
            <ion-icon name="calendar-outline"></ion-icon>
            <p>Belum ada presensi hari ini</p>
        </div>
        @endif
    </div>

    <!-- Statistik Bulan Ini -->
    <div class="card">
        <div class="stat-box">
            <div class="stat-value">{{ $statistik }}</div>
            <div class="stat-label">Total Presensi Bulan Ini</div>
        </div>
    </div>

    <!-- Riwayat Terakhir -->
    <div class="card" id="riwayat-section">
        <h3 class="card-title">
            <ion-icon name="time-outline"></ion-icon>
            Riwayat Terakhir
        </h3>

        @if($histori->count() > 0)
        @foreach($histori as $item)
        <div class="history-item">
            <div class="history-time">
                Masuk: {{ $item->jam_masuk ? date('H:i', strtotime($item->jam_masuk)) : '-' }} |
                Pulang: {{ $item->jam_pulang ? date('H:i', strtotime($item->jam_pulang)) : '-' }}
            </div>
            <div class="history-date">
                {{ \Carbon\Carbon::parse($item->tanggal)->isoFormat('dddd, D MMMM Y') }}
            </div>
            @if($item->lokasi)
            <div class="history-date">
                📍 <a href="{{ $item->google_maps_link }}" target="_blank" style="color: #0053C5; text-decoration: none; font-weight: 600;">
                    Lihat Lokasi
                </a>
            </div>
            @endif
            <div class="history-badge {{ $item->jam_pulang ? 'complete' : 'pending' }}">
                <ion-icon name="{{ $item->jam_pulang ? 'checkmark-done' : 'time' }}"></ion-icon>
                {{ $item->jam_pulang ? 'Lengkap' : 'Belum Pulang' }}
            </div>
        </div>
        @endforeach
        @else
        <div class="empty-state">
            <ion-icon name="time-outline"></ion-icon>
            <p>Belum ada riwayat presensi</p>
        </div>
        @endif
    </div>
    @endif
</div>

@endsection