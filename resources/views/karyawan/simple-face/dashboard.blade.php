@extends('karyawan.layouts.simple-face')

@section('content')

<style>
    * {
        box-sizing: border-box;
        -webkit-tap-highlight-color: transparent;
    }

    body {
        background: linear-gradient(180deg, #0053C5 0%, #003A8C 100%);
        min-height: 100vh;
        margin: 0;
        padding: 0;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
    }

    /* ===== SAFE AREA ===== */
    .safe-area-top {
        height: env(safe-area-inset-top, 0px);
        background: transparent;
    }

    /* ===== HEADER ===== */
    .page-header {
        padding: 20px 16px 32px;
    }

    .user-card {
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 24px;
        padding: 20px;
    }

    .user-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .user-info {
        display: flex;
        gap: 14px;
        flex: 1;
    }

    .user-avatar {
        width: 52px;
        height: 52px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .user-avatar ion-icon {
        font-size: 26px;
        color: white;
    }

    .user-details {
        flex: 1;
        min-width: 0;
    }

    .user-name {
        font-size: 18px;
        font-weight: 700;
        color: white;
        margin: 0 0 4px 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-nik {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.75);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .shift-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(16, 185, 129, 0.25);
        border: 1px solid rgba(16, 185, 129, 0.4);
        padding: 6px 12px;
        border-radius: 10px;
        font-size: 11px;
        font-weight: 600;
        color: white;
        margin-top: 10px;
    }

    .shift-badge.multi {
        background: rgba(251, 191, 36, 0.25);
        border-color: rgba(251, 191, 36, 0.4);
    }

    .shift-badge ion-icon {
        font-size: 14px;
    }

    .btn-logout {
        width: 42px;
        height: 42px;
        background: rgba(239, 68, 68, 0.2);
        border: 1px solid rgba(239, 68, 68, 0.3);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        flex-shrink: 0;
    }

    .btn-logout:active {
        transform: scale(0.95);
        background: rgba(239, 68, 68, 0.3);
    }

    .btn-logout ion-icon {
        font-size: 22px;
        color: white;
    }

    .datetime-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .datetime-box {
        background: rgba(255, 255, 255, 0.1);
        padding: 12px;
        border-radius: 14px;
        text-align: center;
    }

    .datetime-label {
        font-size: 10px;
        color: rgba(255, 255, 255, 0.7);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .datetime-value {
        font-size: 16px;
        font-weight: 700;
        color: white;
    }

    /* ===== MAIN CONTENT ===== */
    .main-content {
        padding: 0 16px 100px;
    }

    /* ===== STAT CARDS ===== */
    .stat-cards {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: white;
        border-radius: 20px;
        padding: 16px 12px;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .stat-icon {
        width: 44px;
        height: 44px;
        margin: 0 auto 10px;
        background: linear-gradient(135deg, #0053C5 0%, #003A8C 100%);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-icon ion-icon {
        font-size: 22px;
        color: white;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 700;
        color: #1E293B;
        margin-bottom: 2px;
    }

    .stat-label {
        font-size: 10px;
        color: #64748B;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    /* ===== WARNING CARD ===== */
    .warning-card {
        background: white;
        border: 2px dashed #F59E0B;
        border-radius: 24px;
        padding: 28px 20px;
        text-align: center;
        margin-bottom: 24px;
    }

    .warning-icon {
        width: 64px;
        height: 64px;
        margin: 0 auto 16px;
        background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .warning-icon ion-icon {
        font-size: 32px;
        color: #F59E0B;
    }

    .warning-title {
        font-size: 18px;
        font-weight: 700;
        color: #92400E;
        margin-bottom: 8px;
    }

    .warning-text {
        font-size: 13px;
        color: #64748B;
        margin-bottom: 20px;
        line-height: 1.5;
    }

    .btn-warning {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 14px 24px;
        background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
        color: white;
        border-radius: 14px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        box-shadow: 0 4px 16px rgba(245, 158, 11, 0.35);
    }

    .btn-warning:active {
        transform: scale(0.98);
    }

    /* ===== SECTION TITLE ===== */
    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: white;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-title ion-icon {
        font-size: 20px;
    }

    .section-subtitle {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.7);
        font-weight: 400;
        margin-left: 4px;
    }

    /* ===== SHIFTS LIST - 1 ROW PER SHIFT ===== */
    .shifts-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 24px;
    }

    .shift-item {
        background: white;
        border-radius: 16px;
        padding: 14px 16px;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        transition: all 0.2s ease;
        border: 2px solid transparent;
    }

    .shift-item:active {
        transform: scale(0.98);
        border-color: #0053C5;
    }

    /* Shift Number Badge */
    .shift-num {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .shift-num span {
        font-size: 20px;
        font-weight: 700;
        color: #0053C5;
    }

    /* Shift Info */
    .shift-info {
        flex: 1;
        min-width: 0;
    }

    .shift-name {
        font-size: 15px;
        font-weight: 600;
        color: #1E293B;
        margin-bottom: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .shift-time {
        font-size: 12px;
        color: #64748B;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .shift-time ion-icon {
        font-size: 14px;
        color: #94A3B8;
    }

    /* Shift Status Badge */
    .shift-action {
        flex-shrink: 0;
    }

    .shift-status-badge {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 600;
    }

    .shift-status-badge ion-icon {
        font-size: 18px;
    }

    .shift-status-badge.pending {
        background: linear-gradient(135deg, #0053C5 0%, #003A8C 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(0, 83, 197, 0.3);
    }

    .shift-status-badge.in-progress {
        background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
    }

    .shift-status-badge.completed {
        background: rgba(16, 185, 129, 0.15);
        color: #059669;
    }

    /* ===== COMPLETED STATE ===== */
    .shift-item.completed {
        background: linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%);
        border-color: #A7F3D0;
    }

    .shift-item.completed .shift-num {
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
    }

    .shift-item.completed .shift-num span {
        color: white;
    }

    .shift-item.completed .shift-name {
        color: #065F46;
    }

    .shift-item.completed .shift-time {
        color: #047857;
    }

    .shift-item.completed .shift-time ion-icon {
        color: #10B981;
    }

    /* ===== IN PROGRESS STATE ===== */
    .shift-item.in-progress {
        background: linear-gradient(135deg, #FFFBEB 0%, #FEF3C7 100%);
        border-color: #FDE68A;
    }

    .shift-item.in-progress .shift-num {
        background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
    }

    .shift-item.in-progress .shift-num span {
        color: white;
    }

    .shift-item.in-progress .shift-name {
        color: #92400E;
    }

    .shift-item.in-progress .shift-time {
        color: #B45309;
    }

    .shift-item.in-progress .shift-time ion-icon {
        color: #F59E0B;
    }

    /* ===== ACTION BUTTON LARGE ===== */
    .action-btn-large {
        background: white;
        border-radius: 24px;
        padding: 28px 24px;
        text-align: center;
        text-decoration: none;
        display: block;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        margin-bottom: 24px;
        border: 2px solid transparent;
    }

    .action-btn-large:active {
        transform: scale(0.98);
        border-color: #0053C5;
    }

    .action-icon-large {
        width: 72px;
        height: 72px;
        margin: 0 auto 14px;
        background: linear-gradient(135deg, #0053C5 0%, #003A8C 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 24px rgba(0, 83, 197, 0.35);
    }

    .action-icon-large ion-icon {
        font-size: 36px;
        color: white;
    }

    .action-title-large {
        font-size: 20px;
        font-weight: 700;
        color: #1E293B;
        margin-bottom: 6px;
    }

    .action-subtitle-large {
        font-size: 13px;
        color: #64748B;
        margin: 0;
    }

    /* ===== QUICK MENU ===== */
    .quick-menu {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-bottom: 24px;
    }

    .menu-item {
        background: white;
        border-radius: 18px;
        padding: 18px 14px;
        text-align: center;
        text-decoration: none;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
    }

    .menu-item:active {
        transform: scale(0.98);
    }

    .menu-icon {
        width: 44px;
        height: 44px;
        margin: 0 auto 10px;
        background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .menu-icon ion-icon {
        font-size: 22px;
        color: #0053C5;
    }

    .menu-label {
        font-size: 13px;
        font-weight: 600;
        color: #1E293B;
        margin: 0;
    }

    /* ===== SECTION CARD ===== */
    .section-card {
        background: white;
        border-radius: 24px;
        padding: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        margin-bottom: 16px;
    }

    .section-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .section-card-title {
        font-size: 15px;
        font-weight: 700;
        color: #1E293B;
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0;
    }

    .section-card-title ion-icon {
        font-size: 20px;
        color: #0053C5;
    }

    .section-badge {
        font-size: 12px;
        font-weight: 600;
        color: #0053C5;
        background: #EFF6FF;
        padding: 4px 10px;
        border-radius: 8px;
    }

    /* ===== TODAY PRESENSI ===== */
    .today-item {
        background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);
        border-radius: 16px;
        padding: 16px;
        margin-bottom: 10px;
        border-left: 4px solid #0053C5;
    }

    .today-item:last-child {
        margin-bottom: 0;
    }

    .today-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }

    .today-shift-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 10px;
        background: linear-gradient(135deg, #0053C5 0%, #003A8C 100%);
        color: white;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 600;
    }

    .today-shift-badge ion-icon {
        font-size: 14px;
    }

    .today-status {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 600;
    }

    .today-status.complete {
        background: rgba(16, 185, 129, 0.15);
        color: #059669;
    }

    .today-status.pending {
        background: rgba(245, 158, 11, 0.15);
        color: #D97706;
    }

    .today-status ion-icon {
        font-size: 14px;
    }

    .today-times {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .time-box {
        background: white;
        padding: 12px;
        border-radius: 12px;
        text-align: center;
    }

    .time-label {
        font-size: 10px;
        color: #64748B;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .time-value {
        font-size: 18px;
        font-weight: 700;
        color: #1E293B;
    }

    .time-value.empty {
        color: #CBD5E1;
    }

    /* ===== HISTORY COMPACT ===== */
    .history-item {
        background: #F8FAFC;
        border-radius: 14px;
        margin-bottom: 8px;
        overflow: hidden;
        border: 1px solid #E2E8F0;
    }

    .history-item:last-child {
        margin-bottom: 0;
    }

    .history-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 16px;
        cursor: pointer;
    }

    .history-date {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .date-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .date-icon ion-icon {
        font-size: 20px;
        color: #0053C5;
    }

    .date-info {
        line-height: 1.3;
    }

    .date-day {
        font-size: 14px;
        font-weight: 600;
        color: #1E293B;
    }

    .date-full {
        font-size: 12px;
        color: #64748B;
    }

    .history-meta {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .meta-badge {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        padding: 4px 8px;
        border-radius: 6px;
    }

    .meta-badge.shifts {
        background: #F1F5F9;
        color: #64748B;
    }

    .meta-badge.complete {
        background: rgba(16, 185, 129, 0.12);
        color: #059669;
    }

    .meta-badge.incomplete {
        background: rgba(245, 158, 11, 0.12);
        color: #D97706;
    }

    .meta-badge ion-icon {
        font-size: 14px;
    }

    .chevron-icon {
        font-size: 20px;
        colorTo run code,
        enable code execution and file creation in Settings>Capabilities.Continue10.49: #94A3B8;
        transition: transform 0.2s ease;
    }

    .history-item.expanded .chevron-icon {
        transform: rotate(180deg);
    }

    .history-detail {
        display: none;
        padding: 0 16px 14px;
    }

    .history-item.expanded .history-detail {
        display: block;
    }

    .detail-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 0;
        border-top: 1px dashed #E2E8F0;
    }

    .detail-shift {
        font-size: 12px;
        font-weight: 600;
        color: #0053C5;
        background: #EFF6FF;
        padding: 4px 8px;
        border-radius: 6px;
    }

    .detail-times {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #1E293B;
    }

    .detail-times span {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .detail-times ion-icon {
        font-size: 14px;
    }

    .detail-times .in {
        color: #10B981;
    }

    .detail-times .out {
        color: #EF4444;
    }

    .detail-status ion-icon {
        font-size: 18px;
    }

    .detail-status.complete {
        color: #10B981;
    }

    .detail-status.incomplete {
        color: #F59E0B;
    }

    /* ===== EMPTY STATE ===== */
    .empty-state {
        text-align: center;
        padding: 32px 16px;
    }

    .empty-state ion-icon {
        font-size: 48px;
        color: #CBD5E1;
        margin-bottom: 12px;
    }

    .empty-state p {
        color: #94A3B8;
        font-size: 14px;
        margin: 0;
    }

    /* ===== RESPONSIVE ===== */
    @supports (padding: max(0px)) {
        .page-header {
            padding-top: max(20px, env(safe-area-inset-top));
        }

        .main-content {
            padding-bottom: max(100px, calc(env(safe-area-inset-bottom) + 80px));
        }
    }
</style>
<div class="safe-area-top"></div>
<!-- Header -->
<div class="page-header">
    <div class="user-card">
        <div class="user-top">
            <div class="user-info">
                <div class="user-avatar">
                    <ion-icon name="person"></ion-icon>
                </div>
                <div class="user-details">
                    <h2 class="user-name">{{ $nama_lengkap }}</h2>
                    <div class="user-nik">
                        <ion-icon name="card-outline"></ion-icon>
                        {{ Auth::guard('karyawan')->user()->nik }}
                    </div>
                    @if($is_multi_shift)
                    <div class="shift-badge multi">
                        <ion-icon name="layers-outline"></ion-icon>
                        Multi-Shift ({{ $total_shifts }} Shift)
                    </div>
                    @else
                    <div class="shift-badge">
                        <ion-icon name="time-outline"></ion-icon>
                        Shift Reguler
                    </div>
                    @endif
                </div>
            </div>
            <form action="{{ route('proseslogout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn-logout" onclick="return confirm('Yakin ingin logout?')">
                    <ion-icon name="log-out-outline"></ion-icon>
                </button>
            </form>
        </div>
        <div class="datetime-row">
            <div class="datetime-box">
                <div class="datetime-label">Tanggal</div>
                <div class="datetime-value">{{ \Carbon\Carbon::now('Asia/Jakarta')->isoFormat('DD MMM YYYY') }}</div>
            </div>
            <div class="datetime-box">
                <div class="datetime-label">Waktu</div>
                <div class="datetime-value" id="current-time">{{ \Carbon\Carbon::now('Asia/Jakarta')->format('H:i') }}</div>
            </div>
        </div>
    </div>
</div>
<!-- Main Content -->
<div class="main-content">
    @if(!$faceData)
    <!-- Warning: Belum Daftar Face -->
    <div class="warning-card">
        <div class="warning-icon">
            <ion-icon name="alert-circle"></ion-icon>
        </div>
        <h3 class="warning-title">Wajah Belum Terdaftar</h3>
        <p class="warning-text">
            Daftarkan wajah Anda terlebih dahulu untuk menggunakan sistem presensi
        </p>
        <a href="{{ route('face-presensi.enrollment') }}" class="btn-warning">
            <ion-icon name="scan-outline"></ion-icon>
            Daftar Sekarang
        </a>
    </div>
    @else
    <!-- Stats -->
    <div class="stat-cards">
        <div class="stat-card">
            <div class="stat-icon">
                <ion-icon name="today-outline"></ion-icon>
            </div>
            <div class="stat-value">{{ $presensi_hari_ini->count() }}</div>
            <div class="stat-label">Hari Ini</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <ion-icon name="calendar-outline"></ion-icon>
            </div>
            <div class="stat-value">{{ $statistik }}</div>
            <div class="stat-label">Bulan Ini</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <ion-icon name="checkmark-done-outline"></ion-icon>
            </div>
            <div class="stat-value">
                @if($is_multi_shift)
                {{ count($completed_shifts ?? []) }}/{{ $total_shifts ?? 0 }}
                @else
                {{ $regular_done ? '1/1' : '0/1' }}
                @endif
            </div>
            <div class="stat-label">Lengkap</div>
        </div>
    </div>
    @if($is_multi_shift)
    <!-- Multi-Shift -->
    <div class="section-title">
        <ion-icon name="layers-outline"></ion-icon>
        Waktu Shalat
        <span class="section-subtitle">{{ $total_shifts }} shift tersedia</span>
    </div>

    <div class="shifts-list">
        @foreach($shifts_available as $shift)
        @php
        $today_shift = $presensi_hari_ini->where('shift_ke', $shift->shift_ke)->first();
        $is_completed = $today_shift && !empty($today_shift->jam_pulang);
        $is_in_progress = $today_shift && empty($today_shift->jam_pulang);
        $cardClass = $is_completed ? 'completed' : ($is_in_progress ? 'in-progress' : '');
        @endphp

        <a href="{{ route('face-presensi.create', ['shift_ke' => $shift->shift_ke]) }}"
            class="shift-item {{ $cardClass }}">

            <!-- Shift Number -->
            <div class="shift-num">
                <span>{{ $shift->shift_ke }}</span>
            </div>

            <!-- Shift Info -->
            <div class="shift-info">
                <div class="shift-name">{{ $shift->nama_shift }}</div>
                <div class="shift-time">
                    <ion-icon name="time-outline"></ion-icon>
                    <span>{{ date('H:i', strtotime($shift->jam_masuk)) }} - {{ date('H:i', strtotime($shift->jam_pulang)) }}</span>
                </div>
            </div>

            <!-- Shift Status -->
            <div class="shift-action">
                @if($is_completed)
                <div class="shift-status-badge completed">
                    <ion-icon name="checkmark-circle"></ion-icon>
                    <span>Selesai</span>
                </div>
                @elseif($is_in_progress)
                <div class="shift-status-badge in-progress">
                    <ion-icon name="time"></ion-icon>
                    <span>Pulang</span>
                </div>
                @else
                <div class="shift-status-badge pending">
                    <ion-icon name="arrow-forward-circle"></ion-icon>
                    <span>Mulai</span>
                </div>
                @endif
            </div>
        </a>
        @endforeach
    </div>

    @else
    <!-- Regular -->
    <div class="section-title">
        <ion-icon name="scan-outline"></ion-icon>
        Presensi Harian
    </div>

    <a href="{{ route('face-presensi.create') }}" class="action-btn-large">
        <div class="action-icon-large">
            <ion-icon name="scan"></ion-icon>
        </div>
        <h3 class="action-title-large">Mulai Presensi</h3>
        <p class="action-subtitle-large">Face Recognition & GPS</p>
    </a>
    @endif

    <!-- Quick Menu -->
    <div class="quick-menu">
        <a href="{{ route('face-presensi.enrollment') }}" class="menu-item">
            <div class="menu-icon">
                <ion-icon name="person-circle-outline"></ion-icon>
            </div>
            <p class="menu-label">Kelola Wajah</p>
        </a>
        <a href="#riwayat-section" class="menu-item">
            <div class="menu-icon">
                <ion-icon name="time-outline"></ion-icon>
            </div>
            <p class="menu-label">Riwayat</p>
        </a>
    </div>

    <!-- Presensi Hari Ini -->
    <div class="section-card">
        <div class="section-card-header">
            <h3 class="section-card-title">
                <ion-icon name="today-outline"></ion-icon>
                Hari Ini
            </h3>
            <span class="section-badge">{{ \Carbon\Carbon::now('Asia/Jakarta')->isoFormat('dddd') }}</span>
        </div>

        @if($presensi_hari_ini->count() > 0)
        @foreach($presensi_hari_ini as $item)
        <div class="today-item">
            <div class="today-header">
                <div class="today-shift-badge">
                    <ion-icon name="layers-outline"></ion-icon>
                    {{ $item->nama_shift ? $item->nama_shift : 'Reguler' }}
                </div>
                <div class="today-status {{ $item->jam_pulang ? 'complete' : 'pending' }}">
                    <ion-icon name="{{ $item->jam_pulang ? 'checkmark-circle' : 'time' }}"></ion-icon>
                    {{ $item->jam_pulang ? 'Lengkap' : 'Belum Pulang' }}
                </div>
            </div>
            <div class="today-times">
                <div class="time-box">
                    <div class="time-label">Masuk</div>
                    <div class="time-value {{ $item->jam_masuk ? '' : 'empty' }}">
                        {{ $item->jam_masuk ? date('H:i', strtotime($item->jam_masuk)) : '--:--' }}
                    </div>
                </div>
                <div class="time-box">
                    <div class="time-label">Pulang</div>
                    <div class="time-value {{ $item->jam_pulang ? '' : 'empty' }}">
                        {{ $item->jam_pulang ? date('H:i', strtotime($item->jam_pulang)) : '--:--' }}
                    </div>
                </div>
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

    <!-- Riwayat -->
    <div class="section-card" id="riwayat-section">
        <div class="section-card-header">
            <h3 class="section-card-title">
                <ion-icon name="time-outline"></ion-icon>
                Riwayat
            </h3>
            <span class="section-badge">{{ $histori->count() }} data</span>
        </div>

        @if($histori->count() > 0)
        @php $grouped = $histori->groupBy('tanggal'); @endphp

        @foreach($grouped->take(7) as $tanggal => $items)
        <div class="history-item" onclick="toggleHistory(this)">
            <div class="history-header">
                <div class="history-date">
                    <div class="date-icon">
                        <ion-icon name="calendar-outline"></ion-icon>
                    </div>
                    <div class="date-info">
                        <div class="date-day">{{ \Carbon\Carbon::parse($tanggal)->isoFormat('dddd') }}</div>
                        <div class="date-full">{{ \Carbon\Carbon::parse($tanggal)->isoFormat('D MMMM Y') }}</div>
                    </div>
                </div>
                <div class="history-meta">
                    <div class="meta-badge shifts">
                        <ion-icon name="layers-outline"></ion-icon>
                        {{ $items->count() }}
                    </div>
                    <div class="meta-badge {{ $items->where('jam_pulang', '!=', null)->count() == $items->count() ? 'complete' : 'incomplete' }}">
                        <ion-icon name="{{ $items->where('jam_pulang', '!=', null)->count() == $items->count() ? 'checkmark-circle' : 'alert-circle' }}"></ion-icon>
                    </div>
                    <ion-icon name="chevron-down-outline" class="chevron-icon"></ion-icon>
                </div>
            </div>
            <div class="history-detail">
                @foreach($items as $item)
                <div class="detail-row">
                    <div class="detail-shift">{{ $item->nama_shift ?? 'Reguler' }}</div>
                    <div class="detail-times">
                        <span class="in">
                            <ion-icon name="log-in-outline"></ion-icon>
                            {{ $item->jam_masuk ? date('H:i', strtotime($item->jam_masuk)) : '--:--' }}
                        </span>
                        <span>→</span>
                        <span class="out">
                            <ion-icon name="log-out-outline"></ion-icon>
                            {{ $item->jam_pulang ? date('H:i', strtotime($item->jam_pulang)) : '--:--' }}
                        </span>
                    </div>
                    <div class="detail-status {{ $item->jam_pulang ? 'complete' : 'incomplete' }}">
                        <ion-icon name="{{ $item->jam_pulang ? 'checkmark-circle' : 'time-outline' }}"></ion-icon>
                    </div>
                </div>
                @endforeach
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
<script>
    setInterval(function() {
        const now = new Date();
        document.getElementById('current-time').textContent =
            String(now.getHours()).padStart(2, '0') + ':' + String(now.getMinutes()).padStart(2, '0');
    }, 60000);

    function toggleHistory(el) {
        el.classList.toggle('expanded');
    }
</script>
@endsection