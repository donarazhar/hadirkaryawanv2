@extends('karyawan.layouts.presensi')
@section('content')

<style>
    /* ===== COMPACT & ELEGANT DASHBOARD ===== */
    :root {
        --primary: #0053C5;
        --primary-dark: #003d94;
        --primary-light: #2E7CE6;
        --primary-gradient: linear-gradient(135deg, #0053C5 0%, #2E7CE6 100%);
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --info: #06b6d4;
        --purple: #8b5cf6;
        --bg-main: #f0f4f8;
        --bg-card: #ffffff;
        --text-primary: #1e293b;
        --text-secondary: #64748b;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: var(--bg-main);
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }

    /* ===== PROPER SPACING ===== */
    .content-wrapper {
        padding: 0 20px;
        max-width: 100%;
    }

    /* ===== COMPACT HERO HEADER ===== */
    .hero-header {
        background: var(--primary-gradient);
        padding: 24px 20px 70px 20px;
        position: relative;
        overflow: hidden;
        margin: 0 0 0 0;
    }

    .hero-header::before {
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

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .hero-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .hero-greeting h4 {
        font-size: 12px;
        font-weight: 500;
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 4px;
    }

    .hero-greeting h2 {
        font-size: 20px;
        font-weight: 700;
        color: white;
        margin: 0;
    }

    .hero-avatar img {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        border: 2.5px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        object-fit: cover;
    }

    .hero-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    /* ===== LOGOUT BUTTON ===== */
    .hero-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-logout {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-logout ion-icon {
        font-size: 22px;
        color: white;
    }

    .btn-logout:active {
        transform: scale(0.95);
        background: rgba(255, 255, 255, 0.25);
    }

    /* ===== LOGOUT MODAL ===== */
    .logout-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(5px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 20px;
        animation: fadeIn 0.3s ease;
    }

    .logout-modal.active {
        display: flex;
    }

    .logout-modal-content {
        background: white;
        border-radius: 24px;
        padding: 32px 24px 24px 24px;
        max-width: 340px;
        width: 100%;
        text-align: center;
        animation: slideUp 0.3s ease;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }

    .logout-modal-icon {
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        box-shadow: 0 8px 24px rgba(239, 68, 68, 0.4);
    }

    .logout-modal-icon ion-icon {
        font-size: 32px;
        color: white;
    }

    .logout-modal-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 8px;
    }

    .logout-modal-text {
        font-size: 14px;
        color: var(--text-secondary);
        margin-bottom: 24px;
        line-height: 1.5;
    }

    .logout-modal-buttons {
        display: flex;
        gap: 10px;
    }

    .btn-modal {
        flex: 1;
        padding: 12px 20px;
        border: none;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-modal-cancel {
        background: #f1f5f9;
        color: var(--text-secondary);
    }

    .btn-modal-cancel:active {
        background: #e2e8f0;
        transform: scale(0.98);
    }

    .btn-modal-confirm {
        background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .btn-modal-confirm:active {
        opacity: 0.9;
        transform: scale(0.98);
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .stat-badge {
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        padding: 10px 12px;
        display: flex;
        align-items: center;
        gap: 10px;
        border: 1px solid rgba(255, 255, 255, 0.15);
    }

    .stat-badge-icon {
        width: 36px;
        height: 36px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-badge-icon ion-icon {
        font-size: 20px;
        color: white;
    }

    .stat-badge-info span {
        display: block;
        font-size: 10px;
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 2px;
    }

    .stat-badge-info strong {
        font-size: 13px;
        color: white;
        font-weight: 600;
    }

    /* ===== COMPACT PRESENCE CARD ===== */
    .floating-presence {
        position: relative;
        margin-top: -55px;
        padding: 0 20px;
        z-index: 10;
        margin-bottom: 20px;
    }

    .presence-card {
        background: var(--bg-card);
        border-radius: 20px;
        padding: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 83, 197, 0.08);
    }

    .presence-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .presence-item {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 14px;
        padding: 14px;
        position: relative;
        border-left: 3px solid var(--border-color);
    }

    .presence-item.check-in {
        --border-color: var(--success);
    }

    .presence-item.check-out {
        --border-color: var(--danger);
    }

    .presence-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 10px;
        background: var(--icon-bg);
    }

    .presence-item.check-in .presence-icon {
        --icon-bg: linear-gradient(135deg, var(--success) 0%, #34d399 100%);
    }

    .presence-item.check-out .presence-icon {
        --icon-bg: linear-gradient(135deg, var(--danger) 0%, #f87171 100%);
    }

    .presence-icon ion-icon {
        font-size: 20px;
        color: white;
    }

    .presence-icon img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 12px;
    }

    .presence-label {
        font-size: 10px;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 4px;
    }

    .presence-time {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-primary);
    }

    .presence-status {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        font-size: 9px;
        padding: 3px 8px;
        border-radius: 6px;
        font-weight: 600;
        margin-top: 6px;
        background: var(--status-bg);
        color: var(--status-color);
    }

    .presence-status.success {
        --status-bg: #dcfce7;
        --status-color: #16a34a;
    }

    .presence-status.pending {
        --status-bg: #fef3c7;
        --status-color: #d97706;
    }

    /* ===== SECTION WRAPPER ===== */
    .section-wrapper {
        padding: 0 20px 20px 20px;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 14px;
    }

    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .section-title ion-icon {
        font-size: 20px;
        color: var(--primary);
    }

    .view-all {
        font-size: 12px;
        color: var(--primary);
        font-weight: 600;
        text-decoration: none;
    }

    /* ===== STATS SECTION ===== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
    }

    .stat-card {
        background: var(--bg-card);
        border-radius: 14px;
        padding: 14px 10px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(0, 0, 0, 0.04);
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--color);
    }

    .stat-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 8px;
        background: var(--bg-color);
    }

    .stat-icon ion-icon {
        font-size: 18px;
        color: var(--color);
    }

    .stat-value {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 2px;
    }

    .stat-label {
        font-size: 10px;
        color: var(--text-secondary);
        font-weight: 600;
    }

    .stat-card.present {
        --color: var(--primary);
        --bg-color: #dbeafe;
    }

    .stat-card.late {
        --color: var(--danger);
        --bg-color: #fee2e2;
    }

    .stat-card.permit {
        --color: var(--info);
        --bg-color: #cffafe;
    }

    .stat-card.sick {
        --color: var(--warning);
        --bg-color: #fef3c7;
    }

    /* ===== LEADERBOARD ===== */
    .leaderboard-card {
        background: var(--bg-card);
        border-radius: 16px;
        padding: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(0, 83, 197, 0.08);
    }

    .leaderboard-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 12px;
        margin-bottom: 8px;
        border-left: 3px solid var(--rank-color);
    }

    .leaderboard-item:last-child {
        margin-bottom: 0;
    }

    .leaderboard-rank {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        flex-shrink: 0;
        background: var(--rank-bg);
        color: var(--rank-text);
    }

    .leaderboard-item:nth-child(1) {
        --rank-color: #fbbf24;
        --rank-bg: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        --rank-text: white;
    }

    .leaderboard-item:nth-child(2) {
        --rank-color: #94a3b8;
        --rank-bg: linear-gradient(135deg, #94a3b8 0%, #64748b 100%);
        --rank-text: white;
    }

    .leaderboard-item:nth-child(3) {
        --rank-color: #fb923c;
        --rank-bg: linear-gradient(135deg, #fb923c 0%, #f97316 100%);
        --rank-text: white;
    }

    .leaderboard-item:nth-child(n+4) {
        --rank-color: #e2e8f0;
        --rank-bg: white;
        --rank-text: #64748b;
    }

    .leaderboard-avatar {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        border: 2px solid white;
        object-fit: cover;
    }

    .leaderboard-info {
        flex: 1;
    }

    .leaderboard-name {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 2px;
    }

    .leaderboard-role {
        font-size: 10px;
        color: var(--text-secondary);
    }

    .leaderboard-time {
        font-size: 11px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 8px;
        background: linear-gradient(135deg, var(--success) 0%, #34d399 100%);
        color: white;
    }

    /* ===== TAB NAVIGATION (BARU) ===== */
    .tab-navigation {
        display: flex;
        background: white;
        border-radius: 12px;
        padding: 4px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(0, 83, 197, 0.08);
        margin-bottom: 14px;
    }

    .tab-button {
        flex: 1;
        padding: 10px 12px;
        border: none;
        background: transparent;
        color: var(--text-secondary);
        font-size: 13px;
        font-weight: 600;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .tab-button ion-icon {
        font-size: 18px;
    }

    .tab-button.active {
        background: var(--primary-gradient);
        color: white;
        box-shadow: 0 4px 12px rgba(0, 83, 197, 0.3);
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    /* ===== RIWAYAT CARDS ===== */
    .riwayat-card {
        background: var(--bg-card);
        border-radius: 16px;
        padding: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(0, 83, 197, 0.08);
    }

    .riwayat-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 12px;
        margin-bottom: 8px;
        border-left: 3px solid var(--status-border);
    }

    .riwayat-item:last-child {
        margin-bottom: 0;
    }

    .riwayat-item.late {
        --status-border: var(--danger);
    }

    .riwayat-item.ontime {
        --status-border: var(--success);
    }

    .riwayat-photo {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        object-fit: cover;
        border: 2px solid white;
    }

    .riwayat-info {
        flex: 1;
    }

    .riwayat-name {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 3px;
    }

    .riwayat-meta {
        font-size: 10px;
        color: var(--text-secondary);
    }

    .riwayat-badge {
        font-size: 10px;
        font-weight: 600;
        padding: 4px 8px;
        border-radius: 6px;
        background: var(--badge-bg);
        color: var(--badge-color);
    }

    .riwayat-item.late .riwayat-badge {
        --badge-bg: #fee2e2;
        --badge-color: #dc2626;
    }

    .riwayat-item.ontime .riwayat-badge {
        --badge-bg: #dcfce7;
        --badge-color: #16a34a;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 30px 20px;
        color: var(--text-secondary);
    }

    .empty-state ion-icon {
        font-size: 48px;
        color: #cbd5e1;
        margin-bottom: 12px;
    }

    .empty-state p {
        font-size: 13px;
        margin: 0;
    }

    /* ===== BOTTOM SPACING ===== */
    .bottom-spacer {
        padding-bottom: 100px;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 375px) {

        .content-wrapper,
        .section-wrapper,
        .floating-presence {
            padding-left: 16px;
            padding-right: 16px;
        }

        .stats-grid {
            gap: 8px;
        }

        .stat-card {
            padding: 12px 8px;
        }
    }
</style>

<!-- Hero Header -->
<div class="hero-header">
    <div class="hero-content content-wrapper">
        <div class="hero-top">
            <div class="hero-greeting">
                <h4>{{ date('l, d M Y') }}</h4>
                <h2>Hi, {{ explode(' ', Auth::guard('karyawan')->user()->nama_lengkap)[0] }}! 👋</h2>
            </div>
            <div class="hero-actions">
                <!-- Tombol Logout -->
                <a href="#" class="btn-logout" onclick="openLogoutModal(event)">
                    <ion-icon name="log-out-outline"></ion-icon>
                </a>
                <!-- Avatar -->
                <div class="hero-avatar">
                    @if (!empty(Auth::guard('karyawan')->user()->foto))
                    @php
                    $path = Storage::url('uploads/karyawan/' . Auth::guard('karyawan')->user()->foto);
                    @endphp
                    <img src="{{ url($path) }}" alt="avatar">
                    @else
                    <img src="{{ asset('assets/img/sample/avatar/avatar1.jpg') }}" alt="avatar">
                    @endif
                </div>
            </div>
        </div>

        <div class="hero-stats">
            <div class="stat-badge">
                <div class="stat-badge-icon">
                    <ion-icon name="briefcase"></ion-icon>
                </div>
                <div class="stat-badge-info">
                    <span>Jabatan</span>
                    <strong>{{ Auth::guard('karyawan')->user()->jabatan }}</strong>
                </div>
            </div>
            <div class="stat-badge">
                <div class="stat-badge-icon">
                    <ion-icon name="location"></ion-icon>
                </div>
                <div class="stat-badge-info">
                    <span>Cabang</span>
                    <strong>{{ Auth::guard('karyawan')->user()->cabang->nama_cabang ?? 'N/A' }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Logout Modal -->
<div class="logout-modal" id="logoutModal">
    <div class="logout-modal-content">
        <div class="logout-modal-icon">
            <ion-icon name="log-out-outline"></ion-icon>
        </div>
        <h3 class="logout-modal-title">Konfirmasi Logout</h3>
        <p class="logout-modal-text">Apakah Anda yakin ingin keluar dari aplikasi? Anda perlu login kembali untuk mengakses dashboard.</p>
        <div class="logout-modal-buttons">
            <button type="button" class="btn-modal btn-modal-cancel" onclick="closeLogoutModal()">
                Batal
            </button>
            <form method="POST" action="{{ route('proseslogout') }}" style="flex: 1; margin: 0;">
                @csrf
                <button type="submit" class="btn-modal btn-modal-confirm" style="width: 100%;">
                    Ya, Keluar
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Floating Presence Card -->
<div class="floating-presence">
    <div class="presence-card">
        <div class="presence-grid">
            <div class="presence-item check-in">
                <div class="presence-icon">
                    @if ($presensihariini != null)
                    @php
                    $path = Storage::url('uploads/absensi/' . $presensihariini->foto_in);
                    @endphp
                    <img src="{{ url($path) }}" alt="Check In">
                    @else
                    <ion-icon name="log-in"></ion-icon>
                    @endif
                </div>
                <div class="presence-label">Check In</div>
                <div class="presence-time">
                    {{ $presensihariini != null ? $presensihariini->jam_in : '--:--' }}
                </div>
                @if ($presensihariini != null)
                <span class="presence-status success">
                    <ion-icon name="checkmark-circle"></ion-icon>
                    Tercatat
                </span>
                @else
                <span class="presence-status pending">
                    <ion-icon name="time"></ion-icon>
                    Belum Absen
                </span>
                @endif
            </div>

            <div class="presence-item check-out">
                <div class="presence-icon">
                    @if ($presensihariini != null && $presensihariini->jam_out != null)
                    @php
                    $path = Storage::url('uploads/absensi/' . $presensihariini->foto_out);
                    @endphp
                    <img src="{{ url($path) }}" alt="Check Out">
                    @else
                    <ion-icon name="log-out"></ion-icon>
                    @endif
                </div>
                <div class="presence-label">Check Out</div>
                <div class="presence-time">
                    {{ $presensihariini != null && $presensihariini->jam_out != null ? $presensihariini->jam_out : '--:--' }}
                </div>
                @if ($presensihariini != null && $presensihariini->jam_out != null)
                <span class="presence-status success">
                    <ion-icon name="checkmark-circle"></ion-icon>
                    Tercatat
                </span>
                @else
                <span class="presence-status pending">
                    <ion-icon name="time"></ion-icon>
                    Belum Absen
                </span>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Stats Section -->
<div class="section-wrapper">
    <div class="section-header">
        <h3 class="section-title">
            <ion-icon name="bar-chart"></ion-icon>
            Rekap {{ $namabulan[$bulanini] }}
        </h3>
        <a href="/presensi/histori" class="view-all">Lihat Semua →</a>
    </div>

    <div class="stats-grid">
        <div class="stat-card present">
            <div class="stat-icon">
                <ion-icon name="checkmark-circle"></ion-icon>
            </div>
            <div class="stat-value">{{ $rekappresensi->jmlhadir }}</div>
            <div class="stat-label">Hadir</div>
        </div>

        <div class="stat-card late">
            <div class="stat-icon">
                <ion-icon name="alarm"></ion-icon>
            </div>
            <div class="stat-value">{{ $rekappresensi->jmlterlambat }}</div>
            <div class="stat-label">Telat</div>
        </div>

        <div class="stat-card permit">
            <div class="stat-icon">
                <ion-icon name="document-text"></ion-icon>
            </div>
            <div class="stat-value">{{ $rekapizin != null ? $rekapizin->jmlizin : 0 }}</div>
            <div class="stat-label">Izin</div>
        </div>

        <div class="stat-card sick">
            <div class="stat-icon">
                <ion-icon name="medkit"></ion-icon>
            </div>
            <div class="stat-value">{{ $rekapizin != null ? $rekapizin->jmlsakit : 0 }}</div>
            <div class="stat-label">Sakit</div>
        </div>
    </div>
</div>

<!-- Leaderboard -->
<div class="section-wrapper">
    <div class="section-header">
        <h3 class="section-title">
            <ion-icon name="trophy"></ion-icon>
            Top 5 Hari Ini
        </h3>
    </div>

    <div class="leaderboard-card">
        @if($leaderboard->count() > 0)
        @foreach ($leaderboard->take(5) as $index => $d)
        <div class="leaderboard-item">
            <div class="leaderboard-rank">{{ $index + 1 }}</div>
            <img src="{{ asset('assets/img/sample/avatar/avatar1.jpg') }}" alt="avatar" class="leaderboard-avatar">
            <div class="leaderboard-info">
                <div class="leaderboard-name">{{ $d->nama_lengkap }}</div>
                <div class="leaderboard-role">{{ $d->jabatan }}</div>
            </div>
            <div class="leaderboard-time">{{ $d->jam_in }}</div>
        </div>
        @endforeach
        @else
        <div class="empty-state">
            <ion-icon name="people-outline"></ion-icon>
            <p>Belum ada yang presensi hari ini</p>
        </div>
        @endif
    </div>
</div>

<!-- TAB: Riwayat Tim & Riwayat Saya (BARU) -->
<div class="section-wrapper bottom-spacer">
    <div class="section-header">
        <h3 class="section-title">
            <ion-icon name="time"></ion-icon>
            Riwayat Presensi
        </h3>
    </div>

    <!-- Tab Navigation -->
    <div class="tab-navigation">
        <button class="tab-button active" onclick="switchTab('tim')">
            <ion-icon name="people"></ion-icon>
            <span>Tim Hari Ini</span>
        </button>
        <button class="tab-button" onclick="switchTab('saya')">
            <ion-icon name="person"></ion-icon>
            <span>Riwayat Saya</span>
        </button>
    </div>

    <!-- Tab Content: Riwayat Tim (Hari Ini) -->
    <div class="tab-content active" id="tab-tim">
        <div class="riwayat-card">
            @if($riwayattim->count() > 0)
            @foreach ($riwayattim as $d)
            @php
            $path = Storage::url('uploads/absensi/' . $d->foto_in);
            $isLate = $d->jam_in > $d->jam_masuk;
            @endphp
            <div class="riwayat-item {{ $isLate ? 'late' : 'ontime' }}">
                <img src="{{ url($path) }}" alt="foto" class="riwayat-photo">
                <div class="riwayat-info">
                    <div class="riwayat-name">{{ $d->nama_lengkap }}</div>
                    <div class="riwayat-meta">
                        {{ $d->jabatan }} • {{ $d->jam_in }}
                    </div>
                </div>
                <div class="riwayat-badge">
                    {{ $isLate ? 'Terlambat' : 'Tepat Waktu' }}
                </div>
            </div>
            @endforeach
            @else
            <div class="empty-state">
                <ion-icon name="people-outline"></ion-icon>
                <p>Belum ada tim yang presensi hari ini</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Tab Content: Riwayat Saya (Bulan Ini) -->
    <div class="tab-content" id="tab-saya">
        <div class="riwayat-card">
            @if($historibulanini->count() > 0)
            @foreach ($historibulanini->take(10) as $d)
            @php
            $path = Storage::url('uploads/absensi/' . $d->foto_in);
            $isLate = $d->jam_in > $d->jam_masuk;
            @endphp
            <div class="riwayat-item {{ $isLate ? 'late' : 'ontime' }}">
                <img src="{{ url($path) }}" alt="foto" class="riwayat-photo">
                <div class="riwayat-info">
                    <div class="riwayat-name">{{ date('d M Y', strtotime($d->tgl_presensi)) }}</div>
                    <div class="riwayat-meta">
                        Masuk: {{ $d->jam_in }}
                        @if ($d->jam_out)
                        • Pulang: {{ $d->jam_out }}
                        @endif
                    </div>
                </div>
                <div class="riwayat-badge">
                    {{ $isLate ? 'Terlambat' : 'Tepat Waktu' }}
                </div>
            </div>
            @endforeach
            @else
            <div class="empty-state">
                <ion-icon name="calendar-outline"></ion-icon>
                <p>Belum ada riwayat presensi bulan ini</p>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('myscript')
<script>
    // Tab Switching Function
    function switchTab(tabName) {
        // Remove active class from all buttons
        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('active');
        });

        // Remove active class from all content
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.remove('active');
        });

        // Add active class to clicked button
        event.currentTarget.classList.add('active');

        // Show selected tab content
        document.getElementById('tab-' + tabName).classList.add('active');
    }

    // Logout Modal Functions
    function openLogoutModal(event) {
        event.preventDefault();
        document.getElementById('logoutModal').classList.add('active');

        // Haptic feedback
        if ('vibrate' in navigator) {
            navigator.vibrate(10);
        }
    }

    function closeLogoutModal() {
        document.getElementById('logoutModal').classList.remove('active');
    }

    // Close modal when clicking outside
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('logoutModal');
        if (event.target === modal) {
            closeLogoutModal();
        }
    });

    // Close modal with ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeLogoutModal();
        }
    });
</script>
@endpush