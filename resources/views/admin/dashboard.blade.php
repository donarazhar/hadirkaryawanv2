@extends('admin.layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');

    :root {
        --blue:       #2563EB;
        --blue-dark:  #1D4ED8;
        --blue-soft:  #EFF6FF;
        --blue-mid:   #BFDBFE;
        --green:      #10B981;
        --green-soft: #ECFDF5;
        --green-mid:  #A7F3D0;
        --amber:      #F59E0B;
        --amber-soft: #FFFBEB;
        --red:        #EF4444;
        --red-soft:   #FEF2F2;
        --purple:     #8B5CF6;
        --purple-soft:#F5F3FF;
        --slate-900:  #111827;
        --slate-700:  #374151;
        --slate-600:  #4B5563;
        --slate-400:  #9CA3AF;
        --slate-200:  #E5E7EB;
        --slate-100:  #F3F4F6;
        --slate-50:   #F9FAFB;
        --white:      #FFFFFF;
        --shadow-sm:  0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
        --shadow-md:  0 4px 12px rgba(0,0,0,0.07);
        --radius:     14px;
        --radius-sm:  10px;
    }

    .dash {
        display: flex;
        flex-direction: column;
        gap: 28px;
    }

    /* ── HERO BANNER ── */
    .hero-card {
        background: var(--white);
        border: 1px solid var(--slate-200);
        border-radius: var(--radius);
        padding: 28px 32px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        box-shadow: var(--shadow-sm);
        position: relative;
        overflow: hidden;
    }

    .hero-card::before {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 220px; height: 220px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(37,99,235,0.06) 0%, transparent 70%);
        pointer-events: none;
    }

    .hero-left { display: flex; align-items: center; gap: 18px; z-index: 1; }

    .hero-icon {
        width: 56px; height: 56px;
        border-radius: 14px;
        background: var(--blue-soft);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }

    .hero-icon i { font-size: 28px; color: var(--blue); }

    .hero-name {
        font-size: 20px;
        font-weight: 800;
        color: var(--slate-900);
        line-height: 1.2;
        letter-spacing: -0.3px;
    }

    .hero-date {
        font-size: 13px;
        color: var(--slate-400);
        font-weight: 500;
        margin-top: 3px;
    }

    .hero-right { display: flex; gap: 10px; z-index: 1; flex-shrink: 0; }

    .btn-hero {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 9px 18px;
        border-radius: var(--radius-sm);
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        border: none;
        transition: opacity 0.2s, transform 0.15s;
        font-family: 'Inter', sans-serif;
    }

    .btn-hero:active { opacity: 0.85; transform: scale(0.98); }
    .btn-hero i { font-size: 16px; }

    .btn-hero-primary { background: var(--blue); color: var(--white); box-shadow: 0 3px 10px rgba(37,99,235,0.25); }
    .btn-hero-outline { background: var(--white); color: var(--slate-700); border: 1.5px solid var(--slate-200); }

    /* ── SECTION LABEL ── */
    .sec-label {
        font-size: 11px;
        font-weight: 700;
        color: var(--slate-400);
        text-transform: uppercase;
        letter-spacing: 0.7px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .sec-label i { font-size: 14px; color: var(--blue); }

    /* ── STATS GRID (Master Data) ── */
    .stats-4 {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
    }

    @media (max-width: 1100px) { .stats-4 { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 520px)  { .stats-4 { grid-template-columns: repeat(2, 1fr); } }

    .stat-card {
        background: var(--white);
        border: 1px solid var(--slate-200);
        border-radius: var(--radius);
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: var(--shadow-sm);
        transition: box-shadow 0.2s, transform 0.2s;
    }

    .stat-card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }

    .stat-icon {
        width: 46px; height: 46px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }

    .stat-icon i { font-size: 22px; }

    .stat-val {
        font-size: 22px;
        font-weight: 900;
        color: var(--slate-900);
        line-height: 1;
        margin-bottom: 3px;
        letter-spacing: -0.5px;
    }

    .stat-lbl {
        font-size: 12px;
        color: var(--slate-400);
        font-weight: 600;
    }

    /* ── 2-COL PRESENSI GRID ── */
    .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    @media (max-width: 768px) { .grid-2 { grid-template-columns: 1fr; } }

    /* Presensi panel card */
    .panel-card {
        background: var(--white);
        border: 1px solid var(--slate-200);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .panel-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
        border-bottom: 1px solid var(--slate-100);
    }

    .panel-card-head-left {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 700;
        color: var(--slate-900);
    }

    .panel-card-head-left i { font-size: 18px; color: var(--blue); }

    .panel-total-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 700;
    }

    .badge-blue  { background: var(--blue-soft);  color: var(--blue); }
    .badge-green { background: var(--green-soft);  color: var(--green); }

    /* Mini stat inside panel */
    .mini-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0;
        padding: 16px;
        gap: 10px;
    }

    @media (max-width: 480px) { .mini-grid { grid-template-columns: repeat(2, 1fr); } }

    .mini-stat {
        text-align: center;
        padding: 14px 8px;
        background: var(--slate-50);
        border-radius: var(--radius-sm);
        transition: background 0.15s;
    }

    .mini-stat:hover { background: var(--slate-100); }

    .mini-stat i { font-size: 22px; display: block; margin-bottom: 6px; }
    .mini-num { font-size: 20px; font-weight: 800; line-height: 1; margin-bottom: 4px; letter-spacing: -0.5px; }
    .mini-lbl { font-size: 10px; font-weight: 700; color: var(--slate-400); text-transform: uppercase; letter-spacing: 0.4px; }

    /* ── CHART CARD ── */
    .chart-card {
        background: var(--white);
        border: 1px solid var(--slate-200);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .chart-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
        border-bottom: 1px solid var(--slate-100);
    }

    .chart-head-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--slate-900);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .chart-head-title i { font-size: 18px; color: var(--blue); }

    .chart-range {
        font-size: 12px;
        color: var(--slate-400);
        font-weight: 500;
    }

    .chart-body {
        padding: 20px;
        position: relative;
        height: 280px;
    }

    /* ── LEADERBOARD 3-COL ── */
    .grid-3 {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
    }

    @media (max-width: 992px) { .grid-3 { grid-template-columns: 1fr; } }
    @media (min-width: 600px) and (max-width: 992px) { .grid-3 { grid-template-columns: repeat(2, 1fr); } }

    .lb-card {
        background: var(--white);
        border: 1px solid var(--slate-200);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .lb-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 16px;
        border-bottom: 1px solid var(--slate-100);
    }

    .lb-head-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--slate-900);
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .lb-period {
        font-size: 10px;
        color: var(--slate-400);
        font-weight: 600;
        background: var(--slate-100);
        padding: 3px 8px;
        border-radius: 50px;
    }

    .lb-list { padding: 8px; display: flex; flex-direction: column; gap: 6px; }

    .lb-row {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 10px;
        border-radius: var(--radius-sm);
        border: 1px solid transparent;
        transition: background 0.15s;
    }

    .lb-row:hover { background: var(--slate-50); border-color: var(--slate-200); }

    .lb-rank {
        width: 28px; height: 28px;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 12px;
        font-weight: 800;
        flex-shrink: 0;
        color: var(--white);
    }

    .rank-1 { background: linear-gradient(135deg, #F59E0B, #D97706); }
    .rank-2 { background: linear-gradient(135deg, #9CA3AF, #6B7280); }
    .rank-3 { background: linear-gradient(135deg, #D97706, #92400E); }
    .rank-n { background: var(--slate-200); color: var(--slate-600); }

    .lb-name {
        flex: 1;
        font-size: 13px;
        font-weight: 600;
        color: var(--slate-900);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .lb-sub {
        font-size: 11px;
        color: var(--slate-400);
        margin-top: 1px;
    }

    .lb-count {
        font-size: 12px;
        font-weight: 800;
        padding: 3px 9px;
        border-radius: 50px;
        flex-shrink: 0;
    }

    .count-blue   { background: var(--blue-soft); color: var(--blue); }
    .count-green  { background: var(--green-soft); color: var(--green); }
    .count-red    { background: var(--red-soft); color: var(--red); }

    .lb-empty { padding: 24px; text-align: center; color: var(--slate-400); font-size: 13px; }
    .lb-empty i { font-size: 36px; display: block; margin-bottom: 8px; color: var(--slate-200); }

    /* ── TABLE CARD ── */
    .tbl-card {
        background: var(--white);
        border: 1px solid var(--slate-200);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .tbl-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 20px;
        border-bottom: 1px solid var(--slate-100);
    }

    .tbl-head-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--slate-900);
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .tbl-head-title i { font-size: 17px; color: var(--blue); }

    .tbl-wrap { overflow-x: auto; }

    .tbl-wrap table { width: 100%; border-collapse: collapse; }

    .tbl-wrap thead th {
        padding: 10px 16px;
        background: var(--slate-50);
        font-size: 11px;
        font-weight: 700;
        color: var(--slate-400);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid var(--slate-200);
        white-space: nowrap;
    }

    .tbl-wrap tbody td {
        padding: 12px 16px;
        font-size: 13px;
        color: var(--slate-700);
        border-bottom: 1px solid var(--slate-100);
        vertical-align: middle;
    }

    .tbl-wrap tbody tr:last-child td { border-bottom: none; }
    .tbl-wrap tbody tr:hover td { background: var(--slate-50); }

    /* User cell */
    .user-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .user-avatar {
        width: 34px; height: 34px;
        border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        font-size: 16px;
    }

    .avatar-blue  { background: var(--blue-soft); color: var(--blue); }
    .avatar-green { background: var(--green-soft); color: var(--green); }

    .user-name { font-size: 13px; font-weight: 700; color: var(--slate-900); }
    .user-sub  { font-size: 11px; color: var(--slate-400); margin-top: 1px; }

    /* Status badge */
    .sb {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .sb i { font-size: 13px; }
    .sb-success { background: var(--green-soft); color: var(--green); }
    .sb-danger  { background: var(--red-soft);   color: var(--red); }
    .sb-warning { background: var(--amber-soft);  color: #D97706; }
    .sb-info    { background: var(--blue-soft);   color: var(--blue); }

    /* Time cell */
    .time-in  { font-size: 12px; font-weight: 700; color: var(--green); }
    .time-out { font-size: 12px; font-weight: 700; color: var(--red); }

    /* Empty row */
    .empty-row { padding: 32px; text-align: center; color: var(--slate-400); font-size: 13px; }
    .empty-row i { font-size: 32px; display: block; margin-bottom: 8px; color: var(--slate-200); }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .hero-card { flex-direction: column; align-items: flex-start; padding: 20px; }
        .hero-right { display: none; }
        .hero-name { font-size: 17px; }
    }
</style>
@endpush

@section('content')
<div class="dash">

    {{-- ── HERO BANNER ── --}}
    <div class="hero-card">
        <div class="hero-left">
            <div class="hero-icon">
                <i class="mdi mdi-hand-wave"></i>
            </div>
            <div>
                <div class="hero-name">Selamat Datang, {{ Auth::guard('user')->user()->name }}!</div>
                <div class="hero-date">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }} — PresensiGPS YPI Al Azhar</div>
            </div>
        </div>
        <div class="hero-right">
            <a href="{{ route('panel.laporan.index') }}" class="btn-hero btn-hero-outline">
                <i class="mdi mdi-file-chart"></i> Laporan
            </a>
            <a href="{{ route('panel.monitoring.index') }}" class="btn-hero btn-hero-primary">
                <i class="mdi mdi-map"></i> Monitoring
            </a>
        </div>
    </div>

    {{-- ── MASTER DATA ── --}}
    <div>
        <div class="sec-label"><i class="mdi mdi-database"></i> Master Data</div>
        <div class="stats-4">
            <div class="stat-card">
                <div class="stat-icon" style="background:var(--blue-soft);">
                    <i class="mdi mdi-office-building" style="color:var(--blue);"></i>
                </div>
                <div>
                    <div class="stat-val">{{ $totalCabang }}</div>
                    <div class="stat-lbl">Total Cabang</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background:var(--green-soft);">
                    <i class="mdi mdi-file-tree" style="color:var(--green);"></i>
                </div>
                <div>
                    <div class="stat-val">{{ $totalDepartemen }}</div>
                    <div class="stat-lbl">Total Departemen</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background:var(--purple-soft);">
                    <i class="mdi mdi-account-group" style="color:var(--purple);"></i>
                </div>
                <div>
                    <div class="stat-val">{{ $totalKaryawan }}</div>
                    <div class="stat-lbl">Total Karyawan</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background:var(--amber-soft);">
                    <i class="mdi mdi-clock-outline" style="color:var(--amber);"></i>
                </div>
                <div>
                    <div class="stat-val">{{ $totalJamKerja }}</div>
                    <div class="stat-lbl">Total Jam Kerja</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── PRESENSI HARI INI ── --}}
    <div>
        <div class="sec-label"><i class="mdi mdi-calendar-today"></i> Presensi Hari Ini</div>
        <div class="grid-2">
            {{-- GPS --}}
            <div class="panel-card">
                <div class="panel-card-head">
                    <div class="panel-card-head-left">
                        <i class="mdi mdi-map-marker-check"></i> Presensi GPS
                    </div>
                    <span class="panel-total-badge badge-blue">{{ $presensiGPSHariIni }} total</span>
                </div>
                <div class="mini-grid">
                    <div class="mini-stat">
                        <i class="mdi mdi-account-check" style="color:var(--green);"></i>
                        <div class="mini-num" style="color:var(--green);">{{ $hadirGPS }}</div>
                        <div class="mini-lbl">Hadir</div>
                    </div>
                    <div class="mini-stat">
                        <i class="mdi mdi-account-clock" style="color:var(--amber);"></i>
                        <div class="mini-num" style="color:var(--amber);">{{ $terlambatGPS }}</div>
                        <div class="mini-lbl">Terlambat</div>
                    </div>
                    <div class="mini-stat">
                        <i class="mdi mdi-file-document" style="color:var(--blue);"></i>
                        <div class="mini-num" style="color:var(--blue);">{{ $izinGPS }}</div>
                        <div class="mini-lbl">Izin/Sakit</div>
                    </div>
                    <div class="mini-stat">
                        <i class="mdi mdi-close-octagon" style="color:var(--red);"></i>
                        <div class="mini-num" style="color:var(--red);">{{ $totalKaryawan - $presensiGPSHariIni }}</div>
                        <div class="mini-lbl">Absen</div>
                    </div>
                </div>
            </div>

            {{-- Face --}}
            <div class="panel-card">
                <div class="panel-card-head">
                    <div class="panel-card-head-left">
                        <i class="mdi mdi-face-recognition"></i> Face Recognition
                    </div>
                    <span class="panel-total-badge badge-green">{{ $presensiFaceHariIni }} total</span>
                </div>
                <div class="mini-grid">
                    <div class="mini-stat">
                        <i class="mdi mdi-login" style="color:var(--green);"></i>
                        <div class="mini-num" style="color:var(--green);">{{ $checkInFace }}</div>
                        <div class="mini-lbl">Check In</div>
                    </div>
                    <div class="mini-stat">
                        <i class="mdi mdi-logout" style="color:var(--red);"></i>
                        <div class="mini-num" style="color:var(--red);">{{ $checkOutFace }}</div>
                        <div class="mini-lbl">Check Out</div>
                    </div>
                    <div class="mini-stat">
                        <i class="mdi mdi-check-circle" style="color:var(--blue);"></i>
                        <div class="mini-num" style="color:var(--blue);">{{ $verifiedFace }}</div>
                        <div class="mini-lbl">Verified</div>
                    </div>
                    <div class="mini-stat">
                        <i class="mdi mdi-close-circle" style="color:var(--amber);"></i>
                        <div class="mini-num" style="color:var(--amber);">{{ $failedFace }}</div>
                        <div class="mini-lbl">Gagal</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── FACE ENROLLMENT ── --}}
    <div>
        <div class="sec-label"><i class="mdi mdi-shield-account"></i> Status Face Enrollment</div>
        <div class="stats-4">
            <div class="stat-card">
                <div class="stat-icon" style="background:var(--blue-soft);">
                    <i class="mdi mdi-account-multiple-check" style="color:var(--blue);"></i>
                </div>
                <div>
                    <div class="stat-val">{{ $totalEnrolled }}</div>
                    <div class="stat-lbl">Total Enrolled ({{ $totalKaryawan > 0 ? number_format(($totalEnrolled / $totalKaryawan * 100), 1) : 0 }}%)</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background:var(--green-soft);">
                    <i class="mdi mdi-check-all" style="color:var(--green);"></i>
                </div>
                <div>
                    <div class="stat-val">{{ $enrolledActive }}</div>
                    <div class="stat-lbl">Enrolled Aktif</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background:var(--slate-100);">
                    <i class="mdi mdi-cancel" style="color:var(--slate-400);"></i>
                </div>
                <div>
                    <div class="stat-val">{{ $enrolledInactive }}</div>
                    <div class="stat-lbl">Enrolled Nonaktif</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background:var(--red-soft);">
                    <i class="mdi mdi-alert-circle" style="color:var(--red);"></i>
                </div>
                <div>
                    <div class="stat-val">{{ $belumEnroll }}</div>
                    <div class="stat-lbl">Belum Enroll</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── CHART ── --}}
    <div class="chart-card">
        <div class="chart-head">
            <div class="chart-head-title">
                <i class="mdi mdi-chart-line"></i>
                Grafik Presensi 7 Hari Terakhir
            </div>
            <div class="chart-range">
                {{ \Carbon\Carbon::now()->subDays(6)->format('d M') }} — {{ \Carbon\Carbon::now()->format('d M Y') }}
            </div>
        </div>
        <div class="chart-body">
            <canvas id="presensiChart"></canvas>
        </div>
    </div>

    {{-- ── LEADERBOARDS ── --}}
    <div>
        <div class="sec-label"><i class="mdi mdi-trophy"></i> Ranking & Keterlambatan</div>
        <div class="grid-3">

            {{-- Top Cabang --}}
            <div class="lb-card">
                <div class="lb-head">
                    <div class="lb-head-title">
                        <i class="mdi mdi-trophy" style="color:var(--amber);"></i>
                        Top 5 Cabang
                    </div>
                    <span class="lb-period">Bulan ini</span>
                </div>
                <div class="lb-list">
                    @forelse($rankingCabang as $i => $item)
                    <div class="lb-row">
                        <div class="lb-rank {{ $i==0 ? 'rank-1' : ($i==1 ? 'rank-2' : ($i==2 ? 'rank-3' : 'rank-n')) }}">{{ $i+1 }}</div>
                        <div class="lb-name">{{ $item->nama_cabang }}</div>
                        <span class="lb-count count-blue">{{ $item->total_presensi }}</span>
                    </div>
                    @empty
                    <div class="lb-empty"><i class="mdi mdi-database-off"></i>Tidak ada data</div>
                    @endforelse
                </div>
            </div>

            {{-- Top Departemen --}}
            <div class="lb-card">
                <div class="lb-head">
                    <div class="lb-head-title">
                        <i class="mdi mdi-star" style="color:var(--green);"></i>
                        Top 5 Departemen
                    </div>
                    <span class="lb-period">Bulan ini</span>
                </div>
                <div class="lb-list">
                    @forelse($rankingDepartemen as $i => $item)
                    <div class="lb-row">
                        <div class="lb-rank {{ $i==0 ? 'rank-1' : ($i==1 ? 'rank-2' : ($i==2 ? 'rank-3' : 'rank-n')) }}">{{ $i+1 }}</div>
                        <div class="lb-name">{{ $item->nama_dept }}</div>
                        <span class="lb-count count-green">{{ $item->total_presensi }}</span>
                    </div>
                    @empty
                    <div class="lb-empty"><i class="mdi mdi-database-off"></i>Tidak ada data</div>
                    @endforelse
                </div>
            </div>

            {{-- Terlambat hari ini --}}
            <div class="lb-card">
                <div class="lb-head">
                    <div class="lb-head-title">
                        <i class="mdi mdi-clock-alert" style="color:var(--red);"></i>
                        Terlambat
                    </div>
                    <span class="lb-period">Hari ini</span>
                </div>
                <div class="lb-list">
                    @forelse($karyawanTerlambat as $item)
                    <div class="lb-row">
                        <div class="lb-rank rank-n"><i class="mdi mdi-account" style="font-size:14px;"></i></div>
                        <div>
                            <div class="lb-name">{{ $item->nama_lengkap }}</div>
                            <div class="lb-sub">{{ $item->nama_cabang }}</div>
                        </div>
                        <span class="lb-count count-red">{{ $item->jam_in }}</span>
                    </div>
                    @empty
                    <div class="lb-empty">
                        <i class="mdi mdi-emoticon-happy-outline" style="color:var(--green);"></i>
                        Tidak ada keterlambatan
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    {{-- ── RECENT TABLES ── --}}
    <div class="grid-2">

        {{-- GPS Terbaru --}}
        <div class="tbl-card">
            <div class="tbl-head">
                <div class="tbl-head-title">
                    <i class="mdi mdi-map-marker-check"></i> GPS Terbaru
                </div>
            </div>
            <div class="tbl-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Karyawan</th>
                            <th>Jam Masuk</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($presensiGPSTerbaru as $item)
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <div class="user-avatar avatar-blue"><i class="mdi mdi-account"></i></div>
                                    <div>
                                        <div class="user-name">{{ $item->nama_lengkap }}</div>
                                        <div class="user-sub">{{ $item->nama_cabang }}</div>
                                    </div>
                                </div>
                            </td>
                            <td><strong>{{ $item->jam_in }}</strong></td>
                            <td>
                                @if($item->status=='h')
                                    <span class="sb sb-success"><i class="mdi mdi-check-circle"></i> Hadir</span>
                                @elseif($item->status=='i')
                                    <span class="sb sb-info"><i class="mdi mdi-information"></i> Izin</span>
                                @elseif($item->status=='s')
                                    <span class="sb sb-warning"><i class="mdi mdi-hospital-box"></i> Sakit</span>
                                @else
                                    <span class="sb sb-danger">{{ $item->status }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3"><div class="empty-row"><i class="mdi mdi-database-off"></i>Tidak ada data presensi</div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Face Terbaru --}}
        <div class="tbl-card">
            <div class="tbl-head">
                <div class="tbl-head-title">
                    <i class="mdi mdi-face-recognition"></i> Face Recognition Terbaru
                </div>
            </div>
            <div class="tbl-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Karyawan</th>
                            <th>Waktu</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($presensiFaceTerbaru as $item)
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <div class="user-avatar avatar-green"><i class="mdi mdi-face-recognition"></i></div>
                                    <div>
                                        <div class="user-name">{{ $item->nama_lengkap }}</div>
                                        <div class="user-sub">{{ $item->nama_cabang }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($item->jam_masuk) <div class="time-in">In: {{ $item->jam_masuk }}</div> @endif
                                @if($item->jam_pulang) <div class="time-out">Out: {{ $item->jam_pulang }}</div> @endif
                            </td>
                            <td>
                                @if($item->status=='verified')
                                    <span class="sb sb-success"><i class="mdi mdi-check-circle"></i> Verified</span>
                                @else
                                    <span class="sb sb-danger"><i class="mdi mdi-close-circle"></i> Failed</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3"><div class="empty-row"><i class="mdi mdi-database-off"></i>Tidak ada data presensi</div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var ctx = document.getElementById('presensiChart');
        if (!ctx) return;

        var labels   = @json($last7Days ?? []);
        var gpsData  = @json($presensiGPSChart ?? []);
        var faceData = @json($presensiFaceChart ?? []);

        if (!labels.length) return;

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Presensi GPS',
                        data: gpsData,
                        borderColor: '#2563EB',
                        backgroundColor: 'rgba(37,99,235,0.08)',
                        borderWidth: 2.5,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#2563EB',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                    },
                    {
                        label: 'Presensi Face',
                        data: faceData,
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16,185,129,0.07)',
                        borderWidth: 2.5,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#10B981',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 8,
                            padding: 20,
                            font: { family: 'Inter', size: 12, weight: '600' }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#F3F4F6' },
                        border: { display: false },
                        ticks: { font: { family: 'Inter', size: 11 }, color: '#9CA3AF' }
                    },
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: { font: { family: 'Inter', size: 11 }, color: '#9CA3AF' }
                    }
                }
            }
        });
    });
</script>
@endpush