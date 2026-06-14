@extends('karyawan.layouts.simple-face')

@section('content')

<style>
    :root {
        --bg-app: #f5f7fa;
        --primary-50: #eff6ff;
        --primary-100: #dbeafe;
        --primary-500: #0053C5;
        --primary-600: #0045A5;
        --primary-700: #003A8C;
        --text-400: #9ca3af;
        --text-500: #6b7280;
        --text-800: #1f2937;
        --text-900: #111827;
        --border-100: #f3f4f6;
    }

    * {
        box-sizing: border-box;
        -webkit-tap-highlight-color: transparent;
    }

    body {
        background: var(--bg-app);
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

    /* ===== MAIN CONTENT ===== */
    .dashboard-container {
        padding-bottom: 120px;
        max-width: 800px;
        margin: 0 auto;
    }

    /* ===== HERO BANNER ===== */
    .hero-banner {
        background: #ffffff;
        padding: 32px 24px 64px 24px;
        position: relative;
        overflow: hidden;
        border-bottom-left-radius: 32px;
        border-bottom-right-radius: 32px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        border-bottom: 1px solid #f3f4f6;
    }

    .hero-banner::before {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 150px; height: 150px;
        background: radial-gradient(circle, rgba(0,83,197,0.03) 0%, transparent 70%);
        border-radius: 50%;
    }

    .hero-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        position: relative;
        z-index: 2;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-avatar {
        width: 48px;
        height: 48px;
        background: #eff6ff;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .user-avatar ion-icon {
        font-size: 24px;
        color: var(--primary-500);
    }

    .user-details h2 {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-900);
        margin: 0 0 4px 0;
    }

    .user-details p {
        font-size: 13px;
        color: var(--text-500);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .btn-logout {
        width: 40px;
        height: 40px;
        background: #fee2e2;
        border: 1px solid #fecaca;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #ef4444;
    }

    /* Date Time Info */
    .datetime-bar {
        display: flex;
        justify-content: space-between;
        margin-top: 24px;
        position: relative;
        z-index: 2;
    }

    .dt-item {
        color: var(--text-900);
    }

    .dt-label {
        font-size: 11px;
        color: var(--text-500);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .dt-value {
        font-size: 15px;
        font-weight: 700;
    }

    /* ===== ICON GRID (Menu) ===== */
    .icon-grid-wrapper {
        margin: -32px 24px 24px 24px;
        position: relative;
        z-index: 10;
        background: white;
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.04);
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }

    .icon-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
        gap: 8px;
    }

    .icon-circle {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }
    
    .bg-blue-soft { background: #eff6ff; color: #0053C5; }
    .bg-green-soft { background: #dcfce7; color: #16a34a; }
    .bg-amber-soft { background: #fef3c7; color: #d97706; }

    .icon-item span {
        font-size: 11px;
        font-weight: 600;
        color: var(--text-800);
        text-align: center;
    }

    /* ===== WARNING CARD ===== */
    .content-section {
        padding: 0 24px;
    }

    .warning-card {
        background: #fffbeb;
        border: 1px dashed #f59e0b;
        border-radius: 16px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
    }

    .warning-icon {
        width: 44px; height: 44px;
        background: #fef3c7;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        color: #d97706; font-size: 24px; flex-shrink: 0;
    }

    .warning-text h4 {
        margin: 0 0 4px 0; font-size: 14px; color: #92400e; font-weight: 700;
    }
    .warning-text p {
        margin: 0; font-size: 12px; color: #b45309;
    }
    .warning-btn {
        background: #f59e0b; color: white; border: none; padding: 6px 12px;
        border-radius: 8px; font-size: 12px; font-weight: 600; text-decoration: none;
        display: inline-block; margin-top: 8px;
    }

    /* ===== SECTION TITLE ===== */
    .section-title {
        font-size: 16px;
        font-weight: 800;
        color: var(--text-900);
        margin: 0 0 16px 0;
        display: flex; justify-content: space-between; align-items: center;
    }
    
    .section-badge {
        font-size: 11px;
        color: var(--primary-500);
        background: var(--primary-50);
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 600;
    }

    /* ===== STATS GRID ===== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: white;
        border: 1px solid var(--border-100);
        border-radius: 16px;
        padding: 16px 12px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }

    .stat-val {
        font-size: 22px; font-weight: 800; color: var(--primary-600); margin-bottom: 2px;
    }

    .stat-label {
        font-size: 10px; color: var(--text-500); font-weight: 600; text-transform: uppercase;
    }

    /* ===== MAIN ACTION (Mulai Presensi) ===== */
    .action-card {
        background: white;
        border-radius: 20px;
        border: 1px solid var(--border-100);
        padding: 24px;
        display: flex; align-items: center; gap: 16px;
        text-decoration: none; box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        margin-bottom: 24px;
    }
    .action-icon {
        width: 56px; height: 56px; border-radius: 16px;
        background: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-500) 100%);
        color: white; font-size: 28px; display: flex; align-items: center; justify-content: center;
        box-shadow: 0 4px 12px rgba(0, 83, 197, 0.2);
    }
    .action-text h3 {
        margin: 0 0 4px 0; font-size: 16px; font-weight: 800; color: var(--text-900);
    }
    .action-text p {
        margin: 0; font-size: 13px; color: var(--text-500);
    }
    
    /* SHIFTS LIST */
    .shift-card {
        background: white;
        border-radius: 16px; border: 1px solid var(--border-100);
        padding: 16px; display: flex; align-items: center; gap: 16px;
        text-decoration: none; margin-bottom: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }
    .shift-num {
        width: 48px; height: 48px; background: var(--primary-50); color: var(--primary-500);
        border-radius: 14px; display: flex; align-items: center; justify-content: center;
        font-size: 18px; font-weight: 800;
    }
    .shift-info { flex: 1; }
    .shift-title { font-size: 14px; font-weight: 700; color: var(--text-900); margin-bottom: 2px; }
    .shift-time { font-size: 12px; color: var(--text-500); display: flex; align-items: center; gap: 4px; }
    .shift-badge-status {
        padding: 6px 12px; border-radius: 12px; font-size: 11px; font-weight: 700;
    }
    .status-pending { background: var(--primary-500); color: white; }
    .status-progress { background: #f59e0b; color: white; }
    .status-complete { background: #dcfce7; color: #166534; }

    /* ===== VERTICAL LIST (Hari Ini / Riwayat) ===== */
    .v-list-container {
        display: flex; flex-direction: column; gap: 12px; margin-bottom: 24px;
    }
    .v-card {
        background: white; border: 1px solid var(--border-100); border-radius: 16px;
        padding: 16px; display: flex; flex-direction: column; gap: 12px;
    }
    .v-header {
        display: flex; justify-content: space-between; align-items: center;
        border-bottom: 1px dashed var(--border-100); padding-bottom: 12px;
    }
    .v-title { font-size: 14px; font-weight: 700; color: var(--text-900); }
    .v-times { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .v-time-box { text-align: center; }
    .v-time-label { font-size: 11px; color: var(--text-400); margin-bottom: 4px; }
    .v-time-val { font-size: 16px; font-weight: 700; color: var(--text-800); }
    .v-time-val.empty { color: #cbd5e1; }

</style>

<div class="safe-area-top"></div>
<div class="dashboard-container">
    
    <!-- Hero Banner -->
    <div class="hero-banner">
        <div class="hero-top">
            <div class="user-info">
                <div class="user-avatar">
                    <ion-icon name="person"></ion-icon>
                </div>
                <div class="user-details">
                    <h2>{{ $nama_lengkap }}</h2>
                    <p>
                        <ion-icon name="card-outline"></ion-icon>
                        {{ Auth::guard('karyawan')->user()->nik }}
                    </p>
                </div>
            </div>
            <form action="{{ route('proseslogout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn-logout" onclick="return confirm('Yakin ingin logout?')">
                    <ion-icon name="log-out-outline"></ion-icon>
                </button>
            </form>
        </div>
        <div class="datetime-bar">
            <div class="dt-item">
                <div class="dt-label">Tanggal</div>
                <div class="dt-value">{{ \Carbon\Carbon::now('Asia/Jakarta')->isoFormat('DD MMM YYYY') }}</div>
            </div>
            <div class="dt-item" style="text-align: right;">
                <div class="dt-label">Waktu</div>
                <div class="dt-value" id="current-time">{{ \Carbon\Carbon::now('Asia/Jakarta')->format('H:i') }}</div>
            </div>
        </div>
    </div>

    <!-- Icon Grid (Menu) -->
    <div class="icon-grid-wrapper">
        <a href="{{ route('face-presensi.dashboard') }}" class="icon-item">
            <div class="icon-circle bg-blue-soft"><ion-icon name="home"></ion-icon></div>
            <span>Home</span>
        </a>
        <a href="{{ route('face-presensi.enrollment') }}" class="icon-item">
            <div class="icon-circle bg-green-soft"><ion-icon name="scan"></ion-icon></div>
            <span>Kelola Wajah</span>
        </a>
        <a href="#riwayat-section" class="icon-item">
            <div class="icon-circle bg-amber-soft"><ion-icon name="time"></ion-icon></div>
            <span>Riwayat</span>
        </a>
    </div>

    <div class="content-section">
        
        @if(!$faceData)
        <div class="warning-card">
            <div class="warning-icon"><ion-icon name="alert-circle"></ion-icon></div>
            <div class="warning-text">
                <h4>Wajah Belum Terdaftar</h4>
                <p>Daftarkan wajah Anda untuk presensi.</p>
                <a href="{{ route('face-presensi.enrollment') }}" class="warning-btn">Daftar Sekarang</a>
            </div>
        </div>
        @else
        
        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-val">{{ $presensi_hari_ini->count() }}</div>
                <div class="stat-label">Hari Ini</div>
            </div>
            <div class="stat-card">
                <div class="stat-val">{{ $statistik }}</div>
                <div class="stat-label">Bulan Ini</div>
            </div>
            <div class="stat-card">
                <div class="stat-val">
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
        <div class="section-title">
            <span>Waktu Shalat</span>
            <span class="section-badge">{{ $total_shifts }} shift tersedia</span>
        </div>
        <div class="v-list-container">
            @foreach($shifts_available as $shift)
            @php
            $today_shift = $presensi_hari_ini->where('shift_ke', $shift->shift_ke)->first();
            $is_completed = $today_shift && !empty($today_shift->jam_pulang);
            $is_in_progress = $today_shift && empty($today_shift->jam_pulang);
            @endphp
            <a href="{{ route('face-presensi.create', ['shift_ke' => $shift->shift_ke]) }}" class="shift-card">
                <div class="shift-num">{{ $shift->shift_ke }}</div>
                <div class="shift-info">
                    <div class="shift-title">{{ $shift->nama_shift }}</div>
                    <div class="shift-time"><ion-icon name="time-outline"></ion-icon> {{ date('H:i', strtotime($shift->jam_masuk)) }} - {{ date('H:i', strtotime($shift->jam_pulang)) }}</div>
                </div>
                <div>
                    @if($is_completed)
                    <div class="shift-badge-status status-complete"><ion-icon name="checkmark"></ion-icon> Selesai</div>
                    @elseif($is_in_progress)
                    <div class="shift-badge-status status-progress">Pulang</div>
                    @else
                    <div class="shift-badge-status status-pending">Mulai</div>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="section-title"><span>Presensi Harian</span></div>
        <a href="{{ route('face-presensi.create') }}" class="action-card">
            <div class="action-icon"><ion-icon name="scan"></ion-icon></div>
            <div class="action-text">
                <h3>Mulai Presensi</h3>
                <p>Scan wajah & lokasi GPS</p>
            </div>
            <div style="margin-left:auto; color:var(--text-400)"><ion-icon name="chevron-forward" style="font-size:24px"></ion-icon></div>
        </a>
        @endif

        <!-- Hari Ini -->
        <div class="section-title">
            <span>Presensi Hari Ini</span>
            <span class="section-badge">{{ \Carbon\Carbon::now('Asia/Jakarta')->isoFormat('dddd') }}</span>
        </div>
        <div class="v-list-container">
            @forelse($presensi_hari_ini as $item)
            <div class="v-card">
                <div class="v-header">
                    <div class="v-title"><i class="mdi mdi-layers-outline text-primary me-1"></i> {{ $item->nama_shift ? $item->nama_shift : 'Reguler' }}</div>
                    <div class="shift-badge-status {{ $item->jam_pulang ? 'status-complete' : 'status-progress' }}">
                        {{ $item->jam_pulang ? 'Lengkap' : 'Belum Pulang' }}
                    </div>
                </div>
                <div class="v-times">
                    <div class="v-time-box">
                        <div class="v-time-label">Jam Masuk</div>
                        <div class="v-time-val {{ $item->jam_masuk ? '' : 'empty' }}">{{ $item->jam_masuk ? date('H:i', strtotime($item->jam_masuk)) : '--:--' }}</div>
                    </div>
                    <div class="v-time-box">
                        <div class="v-time-label">Jam Pulang</div>
                        <div class="v-time-val {{ $item->jam_pulang ? '' : 'empty' }}">{{ $item->jam_pulang ? date('H:i', strtotime($item->jam_pulang)) : '--:--' }}</div>
                    </div>
                </div>
            </div>
            @empty
            <div class="v-card align-items-center py-4">
                <ion-icon name="calendar-outline" style="font-size:40px; color:var(--text-400); margin-bottom:8px"></ion-icon>
                <div style="color:var(--text-500); font-size:13px">Belum ada presensi hari ini</div>
            </div>
            @endforelse
        </div>

        <!-- Riwayat -->
        <div class="section-title" id="riwayat-section">
            <span>Riwayat 7 Hari</span>
            <span class="section-badge">{{ $histori->count() }} data</span>
        </div>
        <div class="v-list-container">
            @if($histori->count() > 0)
                @php $grouped = $histori->groupBy('tanggal'); @endphp
                @foreach($grouped->take(7) as $tanggal => $items)
                <div class="v-card">
                    <div class="v-header" style="border-bottom:none; padding-bottom:0;">
                        <div>
                            <div class="v-title">{{ \Carbon\Carbon::parse($tanggal)->isoFormat('dddd') }}</div>
                            <div style="font-size:12px; color:var(--text-500)">{{ \Carbon\Carbon::parse($tanggal)->isoFormat('D MMMM Y') }}</div>
                        </div>
                        <div class="d-flex gap-2">
                            <span class="badge bg-light text-dark border">{{ $items->count() }} Shift</span>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
            <div class="v-card align-items-center py-4">
                <ion-icon name="time-outline" style="font-size:40px; color:var(--text-400); margin-bottom:8px"></ion-icon>
                <div style="color:var(--text-500); font-size:13px">Belum ada riwayat</div>
            </div>
            @endif
        </div>
        @endif
        
    </div>
</div>

<script>
    setInterval(function() {
        const now = new Date();
        document.getElementById('current-time').textContent =
            String(now.getHours()).padStart(2, '0') + ':' + String(now.getMinutes()).padStart(2, '0');
    }, 60000);
</script>
@endsection