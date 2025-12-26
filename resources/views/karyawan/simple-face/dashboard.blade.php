@extends('karyawan.layouts.simple-face')

@section('content')

<style>
    body {
        background: linear-gradient(135deg, #0053C5 0%, #003d94 100%);
        min-height: 100vh;
    }

    /* ===== HEADER WITH USER INFO ===== */
    .page-header {
        background: transparent;
        padding: 24px 20px 40px 20px;
        position: relative;
    }

    .user-info-card {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 24px;
        padding: 20px;
        color: white;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .user-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .user-profile {
        display: flex;
        align-items: center;
        gap: 16px;
        flex: 1;
    }

    .user-avatar {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.3) 0%, rgba(255, 255, 255, 0.1) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .user-avatar ion-icon {
        font-size: 32px;
        color: white;
    }

    .user-details h2 {
        font-size: 20px;
        font-weight: 700;
        margin: 0 0 4px 0;
        color: white;
    }

    .user-meta {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 13px;
        color: rgba(255, 255, 255, 0.9);
        flex-wrap: wrap;
    }

    .user-meta-item {
        display: flex;
        align-items: center;
        gap: 4px;
        background: rgba(255, 255, 255, 0.15);
        padding: 4px 10px;
        border-radius: 8px;
    }

    .user-meta-item ion-icon {
        font-size: 16px;
    }

    /* Shift Type Badge */
    .shift-type-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(16, 185, 129, 0.3);
        border: 1px solid rgba(16, 185, 129, 0.5);
        padding: 6px 12px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 700;
        color: white;
        margin-top: 8px;
    }

    .shift-type-badge.multi-shift {
        background: rgba(245, 158, 11, 0.3);
        border-color: rgba(245, 158, 11, 0.5);
    }

    .shift-type-badge ion-icon {
        font-size: 16px;
    }

    .btn-logout {
        width: 44px;
        height: 44px;
        background: rgba(239, 68, 68, 0.2);
        backdrop-filter: blur(10px);
        border: 1.5px solid rgba(239, 68, 68, 0.4);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-logout:hover {
        background: rgba(239, 68, 68, 0.3);
        transform: scale(1.05);
    }

    .btn-logout ion-icon {
        font-size: 24px;
        color: white;
    }

    .date-time-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-top: 16px;
    }

    .info-box {
        background: rgba(255, 255, 255, 0.1);
        padding: 12px;
        border-radius: 12px;
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.15);
    }

    .info-label {
        font-size: 11px;
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 4px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-value {
        font-size: 16px;
        font-weight: 700;
        color: white;
    }

    /* ===== CONTENT SECTION ===== */
    .content-section {
        padding: 0 20px 100px 20px;
        margin-top: -20px;
    }

    /* ===== STATUS CARDS ===== */
    .status-cards {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 20px;
    }

    .status-card {
        background: white;
        border-radius: 20px;
        padding: 16px 12px;
        text-align: center;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }

    .status-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }

    .status-card.highlight {
        border-color: #0053C5;
        background: linear-gradient(135deg, rgba(0, 83, 197, 0.1) 0%, rgba(0, 61, 148, 0.05) 100%);
    }

    .status-icon {
        width: 48px;
        height: 48px;
        margin: 0 auto 10px;
        background: linear-gradient(135deg, #0053C5 0%, #003d94 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .status-icon ion-icon {
        font-size: 24px;
        color: white;
    }

    .status-value {
        font-size: 28px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 4px;
        line-height: 1;
    }

    .status-label {
        font-size: 11px;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* ===== SECTION TITLE ===== */
    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: white;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-title ion-icon {
        font-size: 24px;
    }

    .section-subtitle {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.8);
        margin-top: 4px;
        font-weight: 400;
    }

    /* ===== SHIFT CARDS ===== */
    .shifts-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        margin-bottom: 24px;
    }

    .shift-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        text-decoration: none;
        display: block;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        border: 2px solid transparent;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .shift-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, rgba(0, 83, 197, 0.1) 0%, transparent 100%);
        border-radius: 0 0 0 100%;
    }

    .shift-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        border-color: #0053C5;
    }

    .shift-card.completed {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-color: #10b981;
    }

    .shift-card.completed::before {
        background: rgba(255, 255, 255, 0.1);
    }

    .shift-card.in-progress {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        border-color: #f59e0b;
    }

    .shift-number {
        font-size: 32px;
        font-weight: 700;
        color: #0053C5;
        margin-bottom: 4px;
        line-height: 1;
        position: relative;
        z-index: 2;
    }

    .shift-card.completed .shift-number,
    .shift-card.in-progress .shift-number {
        color: white;
    }

    .shift-name {
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 8px;
        position: relative;
        z-index: 2;
    }

    .shift-card.completed .shift-name,
    .shift-card.in-progress .shift-name {
        color: rgba(255, 255, 255, 0.95);
    }

    .shift-time {
        font-size: 12px;
        color: #64748b;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 4px;
        position: relative;
        z-index: 2;
    }

    .shift-card.completed .shift-time,
    .shift-card.in-progress .shift-time {
        color: rgba(255, 255, 255, 0.85);
    }

    .shift-time ion-icon {
        font-size: 14px;
    }

    .shift-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 700;
        position: relative;
        z-index: 2;
    }

    .shift-badge.pending {
        background: rgba(0, 83, 197, 0.15);
        color: #0053C5;
    }

    .shift-badge.in-progress {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }

    .shift-badge.complete {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }

    .shift-badge ion-icon {
        font-size: 16px;
    }

    /* ===== REGULAR ACTION BUTTON ===== */
    .action-btn-large {
        background: white;
        border-radius: 24px;
        padding: 28px 24px;
        text-align: center;
        text-decoration: none;
        display: block;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        margin-bottom: 24px;
        transition: all 0.3s ease;
        border: 3px solid #0053C5;
    }

    .action-btn-large:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(0, 83, 197, 0.3);
    }

    .action-icon-large {
        width: 80px;
        height: 80px;
        margin: 0 auto 16px;
        background: linear-gradient(135deg, #0053C5 0%, #003d94 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 24px rgba(0, 83, 197, 0.3);
    }

    .action-icon-large ion-icon {
        font-size: 48px;
        color: white;
    }

    .action-title-large {
        font-size: 22px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 8px;
    }

    .action-subtitle-large {
        font-size: 14px;
        color: #64748b;
        margin: 0;
    }

    /* ===== QUICK MENU ===== */
    .quick-menu {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        margin-bottom: 24px;
    }

    .menu-item {
        background: white;
        border-radius: 16px;
        padding: 16px;
        text-align: center;
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .menu-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
    }

    .menu-icon {
        width: 44px;
        height: 44px;
        margin: 0 auto 10px;
        background: linear-gradient(135deg, rgba(0, 83, 197, 0.1) 0%, rgba(0, 61, 148, 0.1) 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .menu-icon ion-icon {
        font-size: 24px;
        color: #0053C5;
    }

    .menu-label {
        font-size: 13px;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }

    /* ===== SECTION CARD ===== */
    .section-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        margin-bottom: 16px;
    }

    .section-card-title {
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-card-title ion-icon {
        font-size: 22px;
        color: #0053C5;
    }

    /* ===== GROUPED PRESENSI ITEM ===== */
    .presensi-item-grouped {
        background: linear-gradient(135deg, rgba(0, 83, 197, 0.05) 0%, rgba(0, 61, 148, 0.02) 100%);
        border: 1px solid rgba(0, 83, 197, 0.15);
        border-radius: 16px;
        padding: 16px;
        margin-bottom: 12px;
        border-left: 4px solid #0053C5;
    }

    .presensi-item-grouped:last-child {
        margin-bottom: 0;
    }

    .presensi-date-header {
        font-size: 13px;
        font-weight: 700;
        color: #0053C5;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .presensi-date-header ion-icon {
        font-size: 18px;
    }

    .shift-row {
        background: white;
        border-radius: 12px;
        padding: 12px;
        margin-bottom: 10px;
        border: 1px solid #e2e8f0;
    }

    .shift-row:last-child {
        margin-bottom: 0;
    }

    .shift-row-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .presensi-shift-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        background: linear-gradient(135deg, #0053C5 0%, #003d94 100%);
        color: white;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
    }

    .presensi-shift-badge.regular {
        background: linear-gradient(135deg, #64748b 0%, #475569 100%);
    }

    .presensi-times {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .time-box {
        background: #f8fafc;
        padding: 10px;
        border-radius: 10px;
        text-align: center;
    }

    .time-label {
        font-size: 10px;
        color: #64748b;
        margin-bottom: 4px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .time-value {
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
    }

    .time-value.empty {
        color: #94a3b8;
    }

    .presensi-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 700;
    }

    .presensi-status-badge.complete {
        background: rgba(16, 185, 129, 0.15);
        color: #10b981;
    }

    .presensi-status-badge.pending {
        background: rgba(245, 158, 11, 0.15);
        color: #f59e0b;
    }

    /* ===== EMPTY STATE ===== */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
    }

    .empty-state ion-icon {
        font-size: 64px;
        color: #cbd5e1;
        margin-bottom: 12px;
    }

    .empty-state p {
        color: #94a3b8;
        font-size: 14px;
        margin: 0;
    }

    /* ===== WARNING CARD ===== */
    .warning-card {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(220, 38, 38, 0.05) 100%);
        border: 2px dashed #ef4444;
        border-radius: 20px;
        padding: 28px 20px;
        text-align: center;
        margin-bottom: 20px;
    }

    .warning-icon {
        width: 70px;
        height: 70px;
        margin: 0 auto 16px;
        background: rgba(239, 68, 68, 0.15);
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
        border-radius: 16px;
        text-decoration: none;
        font-weight: 700;
        font-size: 15px;
        box-shadow: 0 4px 16px rgba(239, 68, 68, 0.3);
        transition: all 0.3s ease;
    }

    .btn-daftar:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
    }

    /* ===== COMPACT HISTORY STYLES ===== */
    .history-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .history-item-compact {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .history-item-compact:hover {
        border-color: #0053C5;
        box-shadow: 0 2px 8px rgba(0, 83, 197, 0.1);
    }

    .history-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 16px;
        cursor: pointer;
        user-select: none;
    }

    .history-date {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
    }

    .date-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, rgba(0, 83, 197, 0.1) 0%, rgba(0, 61, 148, 0.1) 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .date-icon ion-icon {
        font-size: 22px;
        color: #0053C5;
    }

    .date-info {
        flex: 1;
    }

    .date-day {
        font-size: 14px;
        font-weight: 700;
        color: #1e293b;
    }

    .date-full {
        font-size: 12px;
        color: #64748b;
    }

    .history-summary {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .shift-count {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 12px;
        color: #64748b;
        background: #f1f5f9;
        padding: 4px 8px;
        border-radius: 6px;
    }

    .shift-count ion-icon {
        font-size: 16px;
    }

    .completion-badge {
        display: flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 700;
    }

    .completion-badge.complete {
        background: rgba(16, 185, 129, 0.15);
        color: #10b981;
    }

    .completion-badge.incomplete {
        background: rgba(245, 158, 11, 0.15);
        color: #f59e0b;
    }

    .completion-badge ion-icon {
        font-size: 14px;
    }

    .chevron-icon {
        font-size: 20px;
        color: #94a3b8;
        transition: transform 0.3s ease;
    }

    .history-item-compact.expanded .chevron-icon {
        transform: rotate(180deg);
    }

    .history-detail {
        border-top: 1px solid #e2e8f0;
        background: #f8fafc;
        padding: 12px 16px;
    }

    .shift-detail-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px dashed #e2e8f0;
    }

    .shift-detail-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .shift-info-compact {
        flex: 1;
    }

    .shift-label {
        display: inline-block;
        padding: 3px 8px;
        background: linear-gradient(135deg, #0053C5 0%, #003d94 100%);
        color: white;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .shift-label.regular {
        background: linear-gradient(135deg, #64748b 0%, #475569 100%);
    }

    .shift-name-small {
        font-size: 11px;
        color: #64748b;
    }

    .time-compact {
        display: flex;
        align-items: center;
        gap: 8px;
        flex: 1;
        justify-content: center;
    }

    .time-item {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .time-icon {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .time-icon.in {
        background: rgba(16, 185, 129, 0.15);
        color: #10b981;
    }

    .time-icon.out {
        background: rgba(239, 68, 68, 0.15);
        color: #ef4444;
    }

    .time-icon ion-icon {
        font-size: 12px;
    }

    .time-text {
        font-size: 13px;
        font-weight: 700;
        color: #1e293b;
    }

    .time-separator {
        font-size: 14px;
        color: #94a3b8;
    }

    .status-icon-compact {
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .status-icon-compact ion-icon {
        font-size: 20px;
    }

    .status-icon-compact.complete {
        color: #10b981;
    }

    .status-icon-compact.incomplete {
        color: #f59e0b;
    }

    .btn-load-more {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 20px;
        background: linear-gradient(135deg, rgba(0, 83, 197, 0.1) 0%, rgba(0, 61, 148, 0.1) 100%);
        border: 1px solid #0053C5;
        color: #0053C5;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-load-more:hover {
        background: linear-gradient(135deg, #0053C5 0%, #003d94 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 83, 197, 0.3);
    }

    .btn-load-more ion-icon {
        font-size: 18px;
    }
</style>

<!-- Header with User Info -->
<div class="page-header">
    <div class="user-info-card">
        <div class="user-header">
            <div class="user-profile">
                <div class="user-avatar">
                    <ion-icon name="person"></ion-icon>
                </div>
                <div class="user-details">
                    <h2>{{ $nama_lengkap }}</h2>
                    <div class="user-meta">
                        <div class="user-meta-item">
                            <ion-icon name="card-outline"></ion-icon>
                            <span>{{ Auth::guard('karyawan')->user()->nik }}</span>
                        </div>
                        @if(Auth::guard('karyawan')->user()->jabatan)
                        <div class="user-meta-item">
                            <ion-icon name="briefcase-outline"></ion-icon>
                            <span>{{ Auth::guard('karyawan')->user()->jabatan }}</span>
                        </div>
                        @endif
                    </div>

                    <!-- ✅ SHIFT TYPE BADGE -->
                    @if($is_multi_shift)
                    <div class="shift-type-badge multi-shift">
                        <ion-icon name="layers"></ion-icon>
                        <span>Multi-Shift ({{ $total_shifts }} Shift)</span>
                    </div>
                    @else
                    <div class="shift-type-badge">
                        <ion-icon name="time"></ion-icon>
                        <span>Shift Reguler</span>
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

        <div class="date-time-info">
            <div class="info-box">
                <div class="info-label">Hari & Tanggal</div>
                <div class="info-value">{{ \Carbon\Carbon::now('Asia/Jakarta')->isoFormat('DD MMM') }}</div>
            </div>
            <div class="info-box">
                <div class="info-label">Waktu</div>
                <div class="info-value" id="current-time">
                    {{ \Carbon\Carbon::now('Asia/Jakarta')->format('H:i') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Section -->
<div class="content-section">
    @if(!$faceData)
    <!-- Warning: Belum Daftar Face -->
    <div class="warning-card">
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
    <!-- Status Cards -->
    <div class="status-cards">
        <div class="status-card">
            <div class="status-icon">
                <ion-icon name="calendar"></ion-icon>
            </div>
            <div class="status-value">{{ $presensi_hari_ini->count() }}</div>
            <div class="status-label">Hari Ini</div>
        </div>

        <div class="status-card">
            <div class="status-icon">
                <ion-icon name="stats-chart"></ion-icon>
            </div>
            <div class="status-value">{{ $statistik }}</div>
            <div class="status-label">Bulan Ini</div>
        </div>

        <div class="status-card">
            <div class="status-icon">
                <ion-icon name="checkmark-done"></ion-icon>
            </div>
            <div class="status-value">
                @if($is_multi_shift)
                {{ count($completed_shifts) }}/{{ $total_shifts }}
                @else
                {{ $regular_done ? '1/1' : '0/1' }}
                @endif
            </div>
            <div class="status-label">Lengkap</div>
        </div>
    </div>

    @if($is_multi_shift)
    <!-- MULTI-SHIFT MODE -->
    <h3 class="section-title">
        <ion-icon name="calendar-outline"></ion-icon>
        <div>
            Pilih Shift Presensi
            <div class="section-subtitle">Anda memiliki {{ $total_shifts }} shift yang tersedia hari ini</div>
        </div>
    </h3>

    <div class="shifts-grid">
        @foreach($shifts_available as $shift)
        @php
        $today_shift = $presensi_hari_ini->where('shift_ke', $shift->shift_ke)->first();
        $is_completed = $today_shift && !empty($today_shift->jam_pulang);
        $is_in_progress = $today_shift && empty($today_shift->jam_pulang);
        @endphp

        <a href="{{ route('face-presensi.create', ['shift_ke' => $shift->shift_ke]) }}"
            class="shift-card {{ $is_completed ? 'completed' : ($is_in_progress ? 'in-progress' : '') }}">

            <div class="shift-number">{{ $shift->shift_ke }}</div>
            <div class="shift-name">{{ $shift->nama_shift }}</div>
            <div class="shift-time">
                <ion-icon name="time-outline"></ion-icon>
                {{ date('H:i', strtotime($shift->jam_masuk)) }} -
                {{ date('H:i', strtotime($shift->jam_pulang)) }}
            </div>

            @if($is_completed)
            <div class="shift-badge complete">
                <ion-icon name="checkmark-done"></ion-icon>
                Selesai
            </div>
            @elseif($is_in_progress)
            <div class="shift-badge in-progress">
                <ion-icon name="time"></ion-icon>
                Belum Pulang
            </div>
            @else
            <div class="shift-badge pending">
                <ion-icon name="scan"></ion-icon>
                Mulai Shift
            </div>
            @endif
        </a>
        @endforeach
    </div>
    @else
    <!-- REGULAR MODE -->
    <h3 class="section-title">
        <ion-icon name="scan-outline"></ion-icon>
        <div>
            Presensi Harian
            <div class="section-subtitle">Satu kali masuk dan pulang per hari</div>
        </div>
    </h3>

    <a href="{{ route('face-presensi.create') }}" class="action-btn-large">
        <div class="action-icon-large">
            <ion-icon name="scan"></ion-icon>
        </div>
        <h3 class="action-title-large">Mulai Presensi</h3>
        <p class="action-subtitle-large">Face Recognition & GPS Verification</p>
    </a>
    @endif

    <!-- Quick Menu -->
    <div class="quick-menu">
        <a href="{{ route('face-presensi.enrollment') }}" class="menu-item">
            <div class="menu-icon">
                <ion-icon name="settings-outline"></ion-icon>
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
        <h3 class="section-card-title">
            <ion-icon name="today-outline"></ion-icon>
            Presensi Hari Ini
        </h3>

        @if($presensi_hari_ini->count() > 0)
        <div class="presensi-item-grouped">
            <div class="presensi-date-header">
                <ion-icon name="calendar"></ion-icon>
                {{ \Carbon\Carbon::now('Asia/Jakarta')->isoFormat('dddd, D MMMM Y') }}
            </div>

            @foreach($presensi_hari_ini as $item)
            <div class="shift-row">
                <div class="shift-row-header">
                    @if($item->shift_ke)
                    <div class="presensi-shift-badge">
                        <ion-icon name="calendar"></ion-icon>
                        Shift {{ $item->shift_ke }} - {{ $item->nama_shift }}
                    </div>
                    @else
                    <div class="presensi-shift-badge regular">
                        <ion-icon name="calendar"></ion-icon>
                        Shift Regular
                    </div>
                    @endif

                    <div class="presensi-status-badge {{ $item->jam_pulang ? 'complete' : 'pending' }}">
                        <ion-icon name="{{ $item->jam_pulang ? 'checkmark-done' : 'time' }}"></ion-icon>
                        {{ $item->jam_pulang ? 'Lengkap' : 'Belum Pulang' }}
                    </div>
                </div>

                <div class="presensi-times">
                    <div class="time-box">
                        <div class="time-label">Masuk</div>
                        <div class="time-value {{ $item->jam_masuk ? '' : 'empty' }}">
                            {{ $item->jam_masuk ? date('H:i', strtotime($item->jam_masuk)) : '-' }}
                        </div>
                    </div>
                    <div class="time-box">
                        <div class="time-label">Pulang</div>
                        <div class="time-value {{ $item->jam_pulang ? '' : 'empty' }}">
                            {{ $item->jam_pulang ? date('H:i', strtotime($item->jam_pulang)) : '-' }}
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <ion-icon name="calendar-outline"></ion-icon>
            <p>Belum ada presensi hari ini</p>
        </div>
        @endif
    </div>

    <!-- Riwayat Terakhir (COMPACT ACCORDION STYLE) -->
    <div class="section-card" id="riwayat-section">
        <h3 class="section-card-title">
            <ion-icon name="time-outline"></ion-icon>
            Riwayat Bulan Ini
            <span style="margin-left: auto; font-size: 14px; color: #0053C5; font-weight: 600;">
                {{ $histori->count() }} presensi
            </span>
        </h3>

        @if($histori->count() > 0)
        @php
        $grouped_history = $histori->groupBy('tanggal');
        @endphp

        <div class="history-list">
            @foreach($grouped_history->take(10) as $tanggal => $items)
            <div class="history-item-compact">
                <div class="history-header" onclick="toggleHistory('{{ $tanggal }}')">
                    <div class="history-date">
                        <div class="date-icon">
                            <ion-icon name="calendar-outline"></ion-icon>
                        </div>
                        <div class="date-info">
                            <div class="date-day">{{ \Carbon\Carbon::parse($tanggal)->isoFormat('dddd') }}</div>
                            <div class="date-full">{{ \Carbon\Carbon::parse($tanggal)->isoFormat('D MMMM Y') }}</div>
                        </div>
                    </div>

                    <div class="history-summary">
                        <div class="shift-count">
                            <ion-icon name="layers-outline"></ion-icon>
                            <span>{{ $items->count() }} shift</span>
                        </div>
                        <div class="completion-badge {{ $items->where('jam_pulang', '!=', null)->count() == $items->count() ? 'complete' : 'incomplete' }}">
                            @if($items->where('jam_pulang', '!=', null)->count() == $items->count())
                            <ion-icon name="checkmark-circle"></ion-icon>
                            Lengkap
                            @else
                            <ion-icon name="alert-circle"></ion-icon>
                            {{ $items->where('jam_pulang', '!=', null)->count() }}/{{ $items->count() }}
                            @endif
                        </div>
                        <ion-icon name="chevron-down-outline" class="chevron-icon"></ion-icon>
                    </div>
                </div>

                <div class="history-detail" id="history-{{ $tanggal }}" style="display: none;">
                    @foreach($items as $item)
                    <div class="shift-detail-row">
                        <div class="shift-info-compact">
                            @if($item->shift_ke)
                            <div class="shift-label">Shift {{ $item->shift_ke }}</div>
                            @else
                            <div class="shift-label regular">Regular</div>
                            @endif

                            @if($item->nama_shift)
                            <div class="shift-name-small">{{ $item->nama_shift }}</div>
                            @endif
                        </div>

                        <div class="time-compact">
                            <div class="time-item">
                                <span class="time-icon in"><ion-icon name="log-in-outline"></ion-icon></span>
                                <span class="time-text">{{ $item->jam_masuk ? date('H:i', strtotime($item->jam_masuk)) : '-' }}</span>
                            </div>
                            <div class="time-separator">→</div>
                            <div class="time-item">
                                <span class="time-icon out"><ion-icon name="log-out-outline"></ion-icon></span>
                                <span class="time-text">{{ $item->jam_pulang ? date('H:i', strtotime($item->jam_pulang)) : '-' }}</span>
                            </div>
                        </div>

                        <div class="status-icon-compact {{ $item->jam_pulang ? 'complete' : 'incomplete' }}">
                            <ion-icon name="{{ $item->jam_pulang ? 'checkmark-circle' : 'time-outline' }}"></ion-icon>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        @if($grouped_history->count() > 10)
        <div style="text-align: center; margin-top: 16px;">
            <button class="btn-load-more" onclick="loadMoreHistory()">
                <ion-icon name="add-circle-outline"></ion-icon>
                Lihat Lebih Banyak
            </button>
        </div>
        @endif
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
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        document.getElementById('current-time').textContent = hours + ':' + minutes;
    }, 60000);

    function toggleHistory(tanggal) {
        const detail = document.getElementById('history-' + tanggal);
        const item = detail.closest('.history-item-compact');

        if (detail.style.display === 'none') {
            detail.style.display = 'block';
            item.classList.add('expanded');
        } else {
            detail.style.display = 'none';
            item.classList.remove('expanded');
        }
    }

    function loadMoreHistory() {
        Swal.fire({
            icon: 'info',
            title: 'Fitur Load More',
            text: 'Scroll untuk melihat lebih banyak riwayat',
            confirmButtonColor: '#0053C5'
        });
    }
</script>

@endsection