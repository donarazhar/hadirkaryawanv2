@extends('karyawan.layouts.presensi')
@section('content')

<style>
    /* ===== GOOGLE FONTS ===== */

    /* ===== DESIGN TOKENS ===== */
    :root {
        --primary: #2563EB;
        --primary-soft: #EFF6FF;
        --primary-border: #BFDBFE;
        --success: #10B981;
        --success-soft: #ECFDF5;
        --danger: #EF4444;
        --danger-soft: #FEF2F2;
        --warning: #F59E0B;
        --warning-soft: #FFFBEB;
        --info: #06B6D4;
        --info-soft: #ECFEFF;
        --purple: #8B5CF6;
        --purple-soft: #F5F3FF;
        --text-900: #111827;
        --text-600: #4B5563;
        --text-400: #9CA3AF;
        --border: #F1F5F9;
        --border-medium: #E5E7EB;
        --surface: #FFFFFF;
        --bg: #F8FAFC;
        --shadow-xs: 0 1px 2px rgba(0,0,0,0.04);
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
        --shadow-md: 0 4px 6px rgba(0,0,0,0.05), 0 2px 4px rgba(0,0,0,0.04);
        --shadow-lg: 0 10px 15px rgba(0,0,0,0.05), 0 4px 6px rgba(0,0,0,0.04);
        --radius-sm: 10px;
        --radius-md: 14px;
        --radius-lg: 18px;
        --radius-xl: 22px;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        background: var(--bg);
        color: var(--text-900);
        -webkit-font-smoothing: antialiased;
    }

    /* ===== HEADER ===== */
    .db-header {
        background: var(--surface);
        padding: 20px 20px 16px;
        border-bottom: 1px solid var(--border);
        position: sticky;
        top: 0;
        z-index: 100;
    }

    .db-header-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .db-date {
        font-size: 11px;
        font-weight: 600;
        color: var(--primary);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 3px;
    }

    .db-greeting {
        font-size: 20px;
        font-weight: 700;
        color: var(--text-900);
        line-height: 1.2;
    }

    .db-header-right {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-icon {
        width: 38px;
        height: 38px;
        background: var(--bg);
        border: 1px solid var(--border-medium);
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: background 0.2s, border-color 0.2s;
        cursor: pointer;
    }

    .btn-icon:hover {
        background: var(--danger-soft);
        border-color: #FCA5A5;
    }

    .btn-icon:hover ion-icon {
        color: var(--danger);
    }

    .btn-icon ion-icon {
        font-size: 19px;
        color: var(--text-600);
        transition: color 0.2s;
    }

    .db-avatar {
        width: 38px;
        height: 38px;
        border-radius: var(--radius-sm);
        object-fit: cover;
        border: 2px solid var(--border-medium);
    }

    /* ===== INFO CHIPS ===== */
    .info-chips {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin-top: 14px;
    }

    .info-chip {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        background: var(--primary-soft);
        border: 1px solid var(--primary-border);
        border-radius: var(--radius-md);
        padding: 10px 12px;
        min-width: 0;
    }

    .info-chip-icon {
        width: 28px;
        height: 28px;
        background: var(--primary);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-top: 1px;
    }

    .info-chip-icon ion-icon {
        font-size: 14px;
        color: white;
    }

    .info-chip-body {
        flex: 1;
        min-width: 0;
    }

    .info-chip-label {
        font-size: 10px;
        color: var(--primary);
        font-weight: 500;
        display: block;
        line-height: 1;
        margin-bottom: 3px;
        opacity: 0.75;
    }

    .info-chip-value {
        font-size: 12px;
        color: var(--primary);
        font-weight: 700;
        display: block;
        line-height: 1.35;
        word-break: break-word;
    }

    /* ===== MAIN CONTENT ===== */
    .db-body {
        padding: 16px 16px 0;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    /* ===== SECTION HEADING ===== */
    .sec-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .sec-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--text-900);
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .sec-title ion-icon {
        font-size: 16px;
        color: var(--primary);
    }

    .sec-link {
        font-size: 11px;
        font-weight: 600;
        color: var(--primary);
        text-decoration: none;
    }

    /* ===== CARD BASE ===== */
    .card {
        background: var(--surface);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    /* ===== ATTENDANCE CARD ===== */
    .attend-card {
        background: var(--surface);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .shift-tag {
        text-align: center;
        padding: 8px 16px;
        font-size: 11px;
        font-weight: 700;
        color: var(--primary);
        background: var(--primary-soft);
        border-bottom: 1px solid var(--primary-border);
        letter-spacing: 0.3px;
    }

    .attend-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0;
    }

    .attend-item {
        padding: 16px;
        position: relative;
    }

    .attend-item:first-child {
        border-right: 1px solid var(--border);
    }

    .attend-item-head {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px;
    }

    .attend-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .attend-dot.in { background: var(--success); }
    .attend-dot.out { background: var(--danger); }

    .attend-type {
        font-size: 10px;
        font-weight: 700;
        color: var(--text-400);
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    .attend-photo {
        width: 44px;
        height: 44px;
        border-radius: var(--radius-sm);
        object-fit: cover;
        border: 1.5px solid var(--border-medium);
        margin-bottom: 10px;
    }

    .attend-photo-placeholder {
        width: 44px;
        height: 44px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 10px;
        border: 1.5px dashed var(--border-medium);
    }

    .attend-photo-placeholder.in { background: var(--success-soft); }
    .attend-photo-placeholder.out { background: var(--danger-soft); }

    .attend-photo-placeholder ion-icon {
        font-size: 20px;
    }

    .attend-photo-placeholder.in ion-icon { color: var(--success); }
    .attend-photo-placeholder.out ion-icon { color: var(--danger); }

    .attend-time {
        font-size: 20px;
        font-weight: 800;
        color: var(--text-900);
        letter-spacing: -0.5px;
        margin-bottom: 6px;
    }

    .attend-time.empty {
        color: var(--text-400);
        font-size: 18px;
    }

    .attend-badge {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        font-size: 10px;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 50px;
    }

    .attend-badge.success {
        background: var(--success-soft);
        color: var(--success);
    }

    .attend-badge.pending {
        background: var(--warning-soft);
        color: #B45309;
    }

    .attend-badge ion-icon { font-size: 10px; }

    /* ===== QR BUTTON ===== */
    .qr-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin: 0;
        padding: 13px 16px;
        background: var(--surface);
        border: 1.5px dashed var(--primary-border);
        border-radius: var(--radius-lg);
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        color: var(--primary);
        transition: background 0.2s, border-color 0.2s;
    }

    .qr-btn:hover, .qr-btn:active {
        background: var(--primary-soft);
        border-color: var(--primary);
    }

    .qr-btn ion-icon {
        font-size: 18px;
    }

    /* ===== STATS GRID ===== */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 8px;
    }

    .stat-item {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 12px 8px;
        text-align: center;
        box-shadow: var(--shadow-xs);
        position: relative;
        overflow: hidden;
    }

    .stat-item::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 2.5px;
        background: var(--stat-color);
        border-radius: 0 0 2px 2px;
    }

    .stat-icon-wrap {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 7px;
        background: var(--stat-bg);
    }

    .stat-icon-wrap ion-icon {
        font-size: 16px;
        color: var(--stat-color);
    }

    .stat-num {
        font-size: 18px;
        font-weight: 800;
        color: var(--text-900);
        line-height: 1;
        margin-bottom: 3px;
    }

    .stat-lbl {
        font-size: 9px;
        font-weight: 600;
        color: var(--text-400);
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .stat-item.hadir   { --stat-color: var(--primary);  --stat-bg: var(--primary-soft); }
    .stat-item.telat   { --stat-color: var(--danger);   --stat-bg: var(--danger-soft); }
    .stat-item.izin    { --stat-color: var(--info);     --stat-bg: var(--info-soft); }
    .stat-item.sakit   { --stat-color: var(--warning);  --stat-bg: var(--warning-soft); }
    .stat-item.cuti    { --stat-color: var(--purple);   --stat-bg: var(--purple-soft); }

    /* ===== LEADERBOARD ===== */
    .lb-list { display: flex; flex-direction: column; gap: 0; }

    .lb-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 11px 14px;
        border-bottom: 1px solid var(--border);
        transition: background 0.15s;
    }

    .lb-item:last-child { border-bottom: none; }
    .lb-item:hover { background: var(--bg); }

    .lb-rank {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 12px;
        flex-shrink: 0;
        background: var(--rank-bg);
        color: var(--rank-color);
    }

    .lb-item:nth-child(1) { --rank-bg: #FEF3C7; --rank-color: #D97706; }
    .lb-item:nth-child(2) { --rank-bg: #F1F5F9; --rank-color: #475569; }
    .lb-item:nth-child(3) { --rank-bg: #FEF2F2; --rank-color: #DC2626; }
    .lb-item:nth-child(n+4) { --rank-bg: var(--bg); --rank-color: var(--text-400); }

    .lb-avatar {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        object-fit: cover;
        border: 1.5px solid var(--border-medium);
        flex-shrink: 0;
    }

    .lb-info { flex: 1; min-width: 0; }

    .lb-name {
        font-size: 12px;
        font-weight: 600;
        color: var(--text-900);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 1px;
    }

    .lb-role {
        font-size: 10px;
        color: var(--text-400);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .lb-time {
        font-size: 11px;
        font-weight: 700;
        padding: 4px 9px;
        border-radius: 50px;
        background: var(--success-soft);
        color: var(--success);
        white-space: nowrap;
        flex-shrink: 0;
    }

    /* ===== TAB NAV ===== */
    .tab-nav {
        display: flex;
        background: var(--bg);
        border-radius: var(--radius-sm);
        padding: 3px;
        border: 1px solid var(--border);
        margin-bottom: 10px;
    }

    .tab-btn {
        flex: 1;
        padding: 9px 10px;
        border: none;
        background: transparent;
        color: var(--text-400);
        font-family: 'Inter', sans-serif;
        font-size: 12px;
        font-weight: 600;
        border-radius: 7px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        transition: all 0.2s;
    }

    .tab-btn ion-icon { font-size: 15px; }

    .tab-btn.active {
        background: var(--surface);
        color: var(--primary);
        box-shadow: var(--shadow-sm);
    }

    .tab-pane { display: none; }
    .tab-pane.active { display: block; }

    /* ===== HISTORY LIST ===== */
    .hist-list { display: flex; flex-direction: column; gap: 0; }

    .hist-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 11px 14px;
        border-bottom: 1px solid var(--border);
        transition: background 0.15s;
    }

    .hist-item:last-child { border-bottom: none; }
    .hist-item:hover { background: var(--bg); }

    .hist-photo {
        width: 36px;
        height: 36px;
        border-radius: 9px;
        object-fit: cover;
        border: 1.5px solid var(--border-medium);
        flex-shrink: 0;
    }

    .hist-info { flex: 1; min-width: 0; }

    .hist-name {
        font-size: 12px;
        font-weight: 600;
        color: var(--text-900);
        margin-bottom: 1px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .hist-meta {
        font-size: 10px;
        color: var(--text-400);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .hist-badge {
        font-size: 10px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 50px;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .hist-badge.ontime {
        background: var(--success-soft);
        color: var(--success);
    }

    .hist-badge.late {
        background: var(--danger-soft);
        color: var(--danger);
    }

    /* ===== EMPTY STATE ===== */
    .empty-box {
        text-align: center;
        padding: 28px 20px;
    }

    .empty-box ion-icon {
        font-size: 40px;
        color: var(--border-medium);
        margin-bottom: 8px;
    }

    .empty-box p {
        font-size: 12px;
        color: var(--text-400);
        font-weight: 500;
    }

    /* ===== LOGOUT MODAL ===== */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(17, 24, 39, 0.35);
        backdrop-filter: blur(6px);
        z-index: 9999;
        align-items: flex-end;
        padding: 16px;
        animation: fadeIn 0.25s ease;
    }

    .modal-overlay.active { display: flex; }

    .modal-sheet {
        background: var(--surface);
        border-radius: var(--radius-xl) var(--radius-xl) var(--radius-lg) var(--radius-lg);
        padding: 24px;
        width: 100%;
        max-width: 420px;
        margin: 0 auto;
        animation: slideUp 0.3s cubic-bezier(0.34, 1.2, 0.64, 1);
        box-shadow: 0 -4px 32px rgba(0,0,0,0.08);
    }

    .modal-handle {
        width: 36px;
        height: 4px;
        background: var(--border-medium);
        border-radius: 2px;
        margin: 0 auto 20px;
    }

    .modal-icon-ring {
        width: 56px;
        height: 56px;
        background: var(--danger-soft);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 14px;
    }

    .modal-icon-ring ion-icon {
        font-size: 28px;
        color: var(--danger);
    }

    .modal-title {
        font-size: 17px;
        font-weight: 700;
        color: var(--text-900);
        text-align: center;
        margin-bottom: 6px;
    }

    .modal-desc {
        font-size: 13px;
        color: var(--text-600);
        text-align: center;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .modal-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }

    .btn-cancel {
        padding: 12px;
        border: 1.5px solid var(--border-medium);
        background: var(--surface);
        border-radius: var(--radius-md);
        font-size: 13px;
        font-weight: 600;
        color: var(--text-600);
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        transition: background 0.2s;
    }

    .btn-cancel:hover { background: var(--bg); }

    .btn-confirm {
        padding: 12px;
        border: none;
        background: var(--danger);
        border-radius: var(--radius-md);
        font-size: 13px;
        font-weight: 600;
        color: white;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        width: 100%;
        transition: opacity 0.2s;
    }

    .btn-confirm:hover { opacity: 0.9; }

    /* ===== DIVIDER ===== */
    .divider {
        height: 1px;
        background: var(--border);
        margin: 0 -16px;
    }

    /* ===== BOTTOM SPACER ===== */
    .bottom-spacer { height: 100px; }

    /* ===== ANIMATIONS ===== */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(40px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeSlideIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .db-body > * {
        animation: fadeSlideIn 0.3s ease both;
    }

    .db-body > *:nth-child(1) { animation-delay: 0.05s; }
    .db-body > *:nth-child(2) { animation-delay: 0.1s; }
    .db-body > *:nth-child(3) { animation-delay: 0.15s; }
    .db-body > *:nth-child(4) { animation-delay: 0.2s; }
    .db-body > *:nth-child(5) { animation-delay: 0.25s; }
</style>

{{-- ===== HEADER ===== --}}
<div class="db-header">
    <div class="db-header-top">
        <div>
            <div class="db-date">{{ date('l, d M Y') }}</div>
            <div class="db-greeting">Hi, {{ explode(' ', Auth::guard('karyawan')->user()->nama_lengkap)[0] }}! 👋</div>
        </div>
        <div class="db-header-right">
            <a href="#" class="btn-icon" onclick="openLogoutModal(event)" title="Logout">
                <ion-icon name="log-out-outline"></ion-icon>
            </a>
            @php
                $avatarPath = !empty(Auth::guard('karyawan')->user()->foto)
                    ? Storage::url('uploads/karyawan/' . Auth::guard('karyawan')->user()->foto)
                    : asset('assets/img/sample/avatar/avatar1.jpg');
            @endphp
            <img src="{{ url($avatarPath) }}" alt="avatar" class="db-avatar">
        </div>
    </div>

    <div class="info-chips">
        <div class="info-chip">
            <div class="info-chip-icon">
                <ion-icon name="briefcase"></ion-icon>
            </div>
            <div class="info-chip-body">
                <span class="info-chip-label">Jabatan</span>
                <span class="info-chip-value">{{ Auth::guard('karyawan')->user()->jabatan }}</span>
            </div>
        </div>
        <div class="info-chip">
            <div class="info-chip-icon">
                <ion-icon name="location"></ion-icon>
            </div>
            <div class="info-chip-body">
                <span class="info-chip-label">Cabang</span>
                <span class="info-chip-value">{{ Auth::guard('karyawan')->user()->cabang->nama_cabang ?? 'N/A' }}</span>
            </div>
        </div>
    </div>
</div>

{{-- ===== LOGOUT MODAL ===== --}}
<div class="modal-overlay" id="logoutModal">
    <div class="modal-sheet">
        <div class="modal-handle"></div>
        <div class="modal-icon-ring">
            <ion-icon name="log-out-outline"></ion-icon>
        </div>
        <h3 class="modal-title">Keluar Aplikasi?</h3>
        <p class="modal-desc">Anda akan keluar dari sesi ini dan perlu login kembali untuk mengakses dashboard.</p>
        <div class="modal-actions">
            <button type="button" class="btn-cancel" onclick="closeLogoutModal()">Batal</button>
            <form method="POST" action="{{ route('proseslogout') }}" style="margin:0;">
                @csrf
                <button type="submit" class="btn-confirm">Ya, Keluar</button>
            </form>
        </div>
    </div>
</div>

{{-- ===== MAIN BODY ===== --}}
<div class="db-body">

    {{-- ATTENDANCE SECTION --}}
    <div>
        @if($presensihariini->isEmpty())
        <div class="attend-card">
            <div class="attend-grid">
                <div class="attend-item">
                    <div class="attend-item-head">
                        <div class="attend-dot in"></div>
                        <span class="attend-type">Check In</span>
                    </div>
                    <div class="attend-photo-placeholder in">
                        <ion-icon name="log-in-outline"></ion-icon>
                    </div>
                    <div class="attend-time empty">--:--</div>
                    <span class="attend-badge pending">
                        <ion-icon name="time-outline"></ion-icon> Belum Absen
                    </span>
                </div>
                <div class="attend-item">
                    <div class="attend-item-head">
                        <div class="attend-dot out"></div>
                        <span class="attend-type">Check Out</span>
                    </div>
                    <div class="attend-photo-placeholder out">
                        <ion-icon name="log-out-outline"></ion-icon>
                    </div>
                    <div class="attend-time empty">--:--</div>
                    <span class="attend-badge pending">
                        <ion-icon name="time-outline"></ion-icon> Belum Absen
                    </span>
                </div>
            </div>
        </div>
        @else
        <div style="display:flex; flex-direction:column; gap:10px;">
            @foreach($presensihariini as $presensi)
            <div class="attend-card">
                @if($presensi->shift_ke != null)
                <div class="shift-tag">
                    Shift {{ $presensi->shift_ke }} · {{ $presensi->nama_shift }}
                </div>
                @endif
                <div class="attend-grid">
                    <div class="attend-item">
                        <div class="attend-item-head">
                            <div class="attend-dot in"></div>
                            <span class="attend-type">Check In</span>
                        </div>
                        @if ($presensi->foto_in)
                            @php
                                $fp = 'uploads/absensi/' . $presensi->foto_in;
                                $fe = Storage::disk('public')->exists($fp);
                            @endphp
                            @if($fe)
                                <img src="{{ Storage::url($fp) }}" alt="Check In" class="attend-photo">
                            @else
                                <img src="{{ asset('assets/img/sample/avatar/noprofile.svg') }}" alt="No Photo" class="attend-photo">
                            @endif
                        @else
                            <img src="{{ asset('assets/img/sample/avatar/noprofile.svg') }}" alt="No Photo" class="attend-photo">
                        @endif
                        <div class="attend-time">{{ $presensi->jam_in }}</div>
                        <span class="attend-badge success">
                            <ion-icon name="checkmark-circle-outline"></ion-icon> Tercatat
                        </span>
                    </div>
                    <div class="attend-item">
                        <div class="attend-item-head">
                            <div class="attend-dot out"></div>
                            <span class="attend-type">Check Out</span>
                        </div>
                        @if ($presensi->jam_out != null)
                            @if ($presensi->foto_out)
                                @php
                                    $fp2 = 'uploads/absensi/' . $presensi->foto_out;
                                    $fe2 = Storage::disk('public')->exists($fp2);
                                @endphp
                                @if($fe2)
                                    <img src="{{ Storage::url($fp2) }}" alt="Check Out" class="attend-photo">
                                @else
                                    <img src="{{ asset('assets/img/sample/avatar/noprofile.png') }}" alt="No Photo" class="attend-photo">
                                @endif
                            @else
                                <img src="{{ asset('assets/img/sample/avatar/noprofile.png') }}" alt="No Photo" class="attend-photo">
                            @endif
                            <div class="attend-time">{{ $presensi->jam_out }}</div>
                            <span class="attend-badge success">
                                <ion-icon name="checkmark-circle-outline"></ion-icon> Tercatat
                            </span>
                        @else
                            <div class="attend-photo-placeholder out">
                                <ion-icon name="log-out-outline"></ion-icon>
                            </div>
                            <div class="attend-time empty">--:--</div>
                            <span class="attend-badge pending">
                                <ion-icon name="time-outline"></ion-icon> Belum Absen
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- QR Code Button --}}
        <a href="{{ route('presensi.qrScan') }}" class="qr-btn" style="margin-top:10px; display:flex;">
            <ion-icon name="qr-code-outline"></ion-icon>
            Absen via QR Code
        </a>
    </div>

    {{-- STATS SECTION --}}
    <div>
        <div class="sec-head">
            <span class="sec-title">
                <ion-icon name="bar-chart-outline"></ion-icon>
                Rekap {{ $namabulan[$bulanini] }}
            </span>
            <a href="/presensi/histori" class="sec-link">Lihat Semua →</a>
        </div>
        <div class="stats-row">
            <div class="stat-item hadir">
                <div class="stat-icon-wrap">
                    <ion-icon name="checkmark-circle-outline"></ion-icon>
                </div>
                <div class="stat-num">{{ $rekappresensi->jmlhadir }}</div>
                <div class="stat-lbl">Hadir</div>
            </div>
            <div class="stat-item telat">
                <div class="stat-icon-wrap">
                    <ion-icon name="alarm-outline"></ion-icon>
                </div>
                <div class="stat-num">{{ $rekappresensi->jmlterlambat }}</div>
                <div class="stat-lbl">Telat</div>
            </div>
            <div class="stat-item izin">
                <div class="stat-icon-wrap">
                    <ion-icon name="document-text-outline"></ion-icon>
                </div>
                <div class="stat-num">{{ $rekapizin != null ? $rekapizin->jmlizin : 0 }}</div>
                <div class="stat-lbl">Izin</div>
            </div>
            <div class="stat-item sakit">
                <div class="stat-icon-wrap">
                    <ion-icon name="medkit-outline"></ion-icon>
                </div>
                <div class="stat-num">{{ $rekapizin != null ? $rekapizin->jmlsakit : 0 }}</div>
                <div class="stat-lbl">Sakit</div>
            </div>
            <div class="stat-item cuti">
                <div class="stat-icon-wrap">
                    <ion-icon name="calendar-number-outline"></ion-icon>
                </div>
                <div class="stat-num">{{ $rekapizin != null ? $rekapizin->jmlcuti : 0 }}</div>
                <div class="stat-lbl">Cuti</div>
            </div>
        </div>
    </div>

    {{-- LEADERBOARD --}}
    <div>
        <div class="sec-head">
            <span class="sec-title">
                <ion-icon name="trophy-outline"></ion-icon>
                Top 5 Hari Ini
            </span>
        </div>
        <div class="card">
            @if($leaderboard->count() > 0)
            <div class="lb-list">
                @foreach($leaderboard->take(5) as $index => $d)
                @php
                    $av = !empty($d->foto) ? 'uploads/karyawan/' . $d->foto : null;
                    $avExists = $av && Storage::disk('public')->exists($av);
                @endphp
                <div class="lb-item">
                    <div class="lb-rank">{{ $index + 1 }}</div>
                    <img src="{{ $avExists ? Storage::url($av) : asset('assets/img/sample/avatar/noprofile.png') }}"
                         alt="avatar" class="lb-avatar">
                    <div class="lb-info">
                        <div class="lb-name">{{ $d->nama_lengkap }}</div>
                        <div class="lb-role">{{ $d->jabatan }}</div>
                    </div>
                    <div class="lb-time">{{ $d->jam_in }}</div>
                </div>
                @endforeach
            </div>
            @else
            <div class="empty-box">
                <ion-icon name="people-outline"></ion-icon>
                <p>Belum ada yang presensi hari ini</p>
            </div>
            @endif
        </div>
    </div>

    {{-- HISTORY TAB --}}
    <div>
        <div class="sec-head">
            <span class="sec-title">
                <ion-icon name="time-outline"></ion-icon>
                Riwayat Presensi
            </span>
        </div>

        <div class="tab-nav">
            <button class="tab-btn active" id="btn-tim" onclick="switchTab('tim')">
                <ion-icon name="people-outline"></ion-icon>
                Tim Hari Ini
            </button>
            <button class="tab-btn" id="btn-saya" onclick="switchTab('saya')">
                <ion-icon name="person-outline"></ion-icon>
                Riwayat Saya
            </button>
        </div>

        {{-- Tab: Tim --}}
        <div class="card tab-pane active" id="tab-tim">
            @if($riwayattim->count() > 0)
            <div class="hist-list">
                @foreach($riwayattim as $d)
                @php
                    $fp = 'uploads/absensi/' . $d->foto_in;
                    $fe = Storage::disk('public')->exists($fp);
                    $isLate = $d->jam_in > $d->jam_masuk;
                @endphp
                <div class="hist-item">
                    <img src="{{ $fe ? Storage::url($fp) : asset('assets/img/sample/avatar/noprofile.png') }}"
                         alt="foto" class="hist-photo">
                    <div class="hist-info">
                        <div class="hist-name">{{ $d->nama_lengkap }}</div>
                        <div class="hist-meta">{{ $d->jabatan }} · {{ $d->jam_in }}</div>
                    </div>
                    <span class="hist-badge {{ $isLate ? 'late' : 'ontime' }}">
                        {{ $isLate ? 'Telat' : 'Tepat' }}
                    </span>
                </div>
                @endforeach
            </div>
            @else
            <div class="empty-box">
                <ion-icon name="people-outline"></ion-icon>
                <p>Belum ada tim yang presensi hari ini</p>
            </div>
            @endif
        </div>

        {{-- Tab: Saya --}}
        <div class="card tab-pane" id="tab-saya">
            @if($historibulanini->count() > 0)
            <div class="hist-list">
                @foreach($historibulanini->take(10) as $d)
                @php
                    $fp = 'uploads/absensi/' . $d->foto_in;
                    $fe = Storage::disk('public')->exists($fp);
                    $isLate = $d->jam_in > $d->jam_masuk;
                @endphp
                <div class="hist-item">
                    <img src="{{ $fe ? Storage::url($fp) : asset('assets/img/sample/avatar/noprofile.png') }}"
                         alt="foto" class="hist-photo">
                    <div class="hist-info">
                        <div class="hist-name">{{ date('d M Y', strtotime($d->tgl_presensi)) }}</div>
                        <div class="hist-meta">
                            Masuk: {{ $d->jam_in }}{{ $d->jam_out ? ' · Pulang: ' . $d->jam_out : '' }}
                        </div>
                    </div>
                    <span class="hist-badge {{ $isLate ? 'late' : 'ontime' }}">
                        {{ $isLate ? 'Telat' : 'Tepat' }}
                    </span>
                </div>
                @endforeach
            </div>
            @else
            <div class="empty-box">
                <ion-icon name="calendar-outline"></ion-icon>
                <p>Belum ada riwayat bulan ini</p>
            </div>
            @endif
        </div>
    </div>

    <div class="bottom-spacer"></div>
</div>

@endsection

@push('myscript')
<script>
    function switchTab(tab) {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
        document.getElementById('btn-' + tab).classList.add('active');
        document.getElementById('tab-' + tab).classList.add('active');
    }

    function openLogoutModal(e) {
        e.preventDefault();
        document.getElementById('logoutModal').classList.add('active');
        if ('vibrate' in navigator) navigator.vibrate(10);
    }

    function closeLogoutModal() {
        document.getElementById('logoutModal').classList.remove('active');
    }

    document.addEventListener('click', function(e) {
        const modal = document.getElementById('logoutModal');
        if (e.target === modal) closeLogoutModal();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeLogoutModal();
    });

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('img').forEach(img => {
            img.addEventListener('error', function() {
                this.src = '{{ asset("assets/img/sample/avatar/noprofile.png") }}';
            });
        });
    });
</script>
@endpush