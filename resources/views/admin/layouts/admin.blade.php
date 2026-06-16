<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>@yield('title') - YPI Al Azhar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/img/logoypia.png') }}" type="image/png" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <style>

        :root {
            --primary:      #2563EB;
            --primary-dark: #1D4ED8;
            --primary-soft: #EFF6FF;
            --sidebar-w:    268px;
            --sidebar-col:  68px;
            --sl-900: #0F172A;
            --sl-800: #1E293B;
            --sl-700: #334155;
            --sl-500: #64748B;
            --sl-400: #94A3B8;
            --sl-300: #CBD5E1;
            --sl-200: #E2E8F0;
            --sl-100: #F1F5F9;
            --sl-50:  #F8FAFC;
            --white:  #FFFFFF;
        }

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--sl-50);
            -webkit-font-smoothing: antialiased;
        }

        /* ── TOGGLE BUTTON ── */
        .sidebar-toggle-btn {
            position: fixed;
            top: 16px; left: 16px;
            z-index: 1100;
            width: 40px; height: 40px;
            background: var(--sl-900);
            border: none;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(15,23,42,0.30);
            transition: background 0.2s, transform 0.15s;
        }
        .sidebar-toggle-btn:hover { background: var(--sl-700); transform: scale(1.05); }
        .sidebar-toggle-btn i { color: var(--white); font-size: 22px; transition: transform 0.3s; }

        /* ── OVERLAY ── */
        .sidebar-overlay {
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.45);
            z-index: 998;
            display: none; opacity: 0;
            transition: opacity 0.3s;
            backdrop-filter: blur(2px);
        }
        .sidebar-overlay.show { display: block; opacity: 1; }

        /* ── SIDEBAR ── */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            width: var(--sidebar-w);
            background: var(--sl-900);
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 999;
            transition: width 0.3s cubic-bezier(0.4,0,0.2,1);
            display: flex;
            flex-direction: column;
        }

        .sidebar.collapsed { width: var(--sidebar-col); }

        .sb-nav::-webkit-scrollbar { width: 4px; }
        .sb-nav::-webkit-scrollbar-track { background: transparent; }
        .sb-nav::-webkit-scrollbar-thumb { background: var(--sl-700); border-radius: 4px; }

        /* ── SIDEBAR BRAND ── */
        .sb-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px 18px 18px;
            border-bottom: 1px solid var(--sl-800);
            text-decoration: none;
            flex-shrink: 0;
            overflow: hidden;
        }

        .sb-logo-wrap {
            width: 36px; height: 36px;
            border-radius: 9px;
            background: rgba(37,99,235,0.20);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .sb-logo-wrap img { width: 24px; height: 24px; object-fit: contain; }

        .sb-brand-text { overflow: hidden; white-space: nowrap; opacity: 1; transition: opacity 0.2s; }
        .sidebar.collapsed .sb-brand-text { opacity: 0; width: 0; }

        .sb-app-name {
            font-size: 15px; font-weight: 800;
            color: var(--white); line-height: 1.1; letter-spacing: -0.3px;
        }
        .sb-app-name span { color: #60A5FA; }
        .sb-app-sub { font-size: 10px; color: var(--sl-400); font-weight: 500; margin-top: 1px; }

        /* ── NAV BODY ── */
        .sb-nav { flex: 1; padding: 12px 0 20px; overflow-y: auto; overflow-x: hidden; }

        /* ── SECTION LABEL ── */
        .sb-section-label {
            font-size: 9.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--sl-500);
            padding: 14px 20px 6px;
            white-space: nowrap;
            overflow: hidden;
            transition: opacity 0.2s;
        }
        .sidebar.collapsed .sb-section-label { opacity: 0; height: 0; padding: 0; }

        /* ── SINGLE MENU ITEM ── */
        .sb-item {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 10px 16px;
            margin: 1px 8px;
            border-radius: 9px;
            text-decoration: none;
            color: var(--sl-400);
            font-size: 13.5px;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            transition: background 0.15s, color 0.15s;
            position: relative;
        }

        .sb-item:hover {
            background: var(--sl-800);
            color: var(--white);
        }

        .sb-item.active {
            background: rgba(37,99,235,0.18);
            color: #60A5FA;
            font-weight: 700;
        }

        .sb-item.active::before {
            content: '';
            position: absolute;
            left: 0; top: 50%;
            transform: translateY(-50%);
            width: 3px; height: 60%;
            background: #60A5FA;
            border-radius: 0 3px 3px 0;
        }

        .sb-item i {
            font-size: 19px;
            flex-shrink: 0;
            width: 22px;
            text-align: center;
            transition: color 0.15s;
        }

        .sb-item-text { transition: opacity 0.2s; }
        .sidebar.collapsed .sb-item-text { opacity: 0; width: 0; overflow: hidden; }
        .sidebar.collapsed .sb-item { justify-content: center; padding: 10px; margin: 1px 6px; }

        /* ── COLLAPSIBLE GROUP ── */
        .sb-group { overflow: hidden; }

        .sb-group-trigger {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 10px 16px;
            margin: 1px 8px;
            border-radius: 9px;
            cursor: pointer;
            color: var(--sl-400);
            font-size: 13.5px;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            transition: background 0.15s, color 0.15s;
            user-select: none;
        }

        .sb-group-trigger:hover { background: var(--sl-800); color: var(--white); }

        .sb-group-trigger.open { color: var(--white); }

        .sb-group-trigger i.trigger-icon { font-size: 19px; flex-shrink: 0; width: 22px; text-align: center; }

        .sb-group-trigger .trigger-label { flex: 1; }

        .sb-chevron {
            font-size: 16px;
            transition: transform 0.25s;
            margin-left: auto;
            flex-shrink: 0;
        }
        .sb-group-trigger.open .sb-chevron { transform: rotate(180deg); }

        .sidebar.collapsed .sb-group-trigger { justify-content: center; padding: 10px; margin: 1px 6px; }
        .sidebar.collapsed .trigger-label,
        .sidebar.collapsed .sb-chevron { opacity: 0; width: 0; overflow: hidden; }

        /* Group children */
        .sb-children {
            overflow: hidden;
            transition: max-height 0.3s ease;
            max-height: 0;
        }
        .sb-children.open { max-height: 400px; }
        .sidebar.collapsed .sb-children { display: none; }

        .sb-sub-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 16px 8px 46px;
            margin: 1px 8px;
            border-radius: 8px;
            text-decoration: none;
            color: var(--sl-400);
            font-size: 12.5px;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            transition: background 0.15s, color 0.15s;
            position: relative;
        }

        .sb-sub-item:hover { background: var(--sl-800); color: var(--white); }

        .sb-sub-item.active {
            color: #60A5FA;
            font-weight: 700;
            background: rgba(37,99,235,0.12);
        }

        .sb-sub-item i { font-size: 16px; flex-shrink: 0; }

        /* ── SIDEBAR FOOTER ── */
        .sb-footer {
            padding: 12px 10px;
            border-top: 1px solid var(--sl-800);
            flex-shrink: 0;
        }

        .sb-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            border-radius: 9px;
            overflow: hidden;
            transition: background 0.15s;
        }
        .sb-user:hover { background: var(--sl-800); }

        .sb-user-avatar {
            width: 32px; height: 32px;
            border-radius: 8px;
            background: rgba(37,99,235,0.25);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            color: #60A5FA;
            font-size: 16px;
        }

        .sb-user-info { overflow: hidden; white-space: nowrap; transition: opacity 0.2s; }
        .sidebar.collapsed .sb-user-info { opacity: 0; width: 0; }

        .sb-user-name { font-size: 12px; font-weight: 700; color: var(--white); }
        .sb-user-role { font-size: 10px; color: var(--sl-400); margin-top: 1px; }

        /* ── TOOLTIP (collapsed) ── */
        .sidebar.collapsed .sb-item,
        .sidebar.collapsed .sb-group-trigger {
            position: relative;
        }

        .sidebar.collapsed .sb-item[data-tip]::after,
        .sidebar.collapsed .sb-group-trigger[data-tip]::after {
            content: attr(data-tip);
            position: absolute;
            left: calc(100% + 10px);
            top: 50%; transform: translateY(-50%);
            background: var(--sl-700);
            color: var(--white);
            padding: 6px 10px;
            border-radius: 7px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.2s;
            z-index: 9999;
            box-shadow: 0 4px 12px rgba(0,0,0,0.25);
        }

        .sidebar.collapsed .sb-item[data-tip]:hover::after,
        .sidebar.collapsed .sb-group-trigger[data-tip]:hover::after { opacity: 1; }

        /* ── MAIN CONTENT ── */
        .main-content {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            transition: margin-left 0.3s cubic-bezier(0.4,0,0.2,1);
        }
        .main-content.expanded { margin-left: var(--sidebar-col); }

        /* ── TOPBAR ── */
        .topbar {
            background: var(--white);
            padding: 0 28px 0 72px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--sl-200);
            position: sticky;
            top: 0;
            z-index: 40;
        }

        .topbar-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--sl-900);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .topbar-right { display: flex; align-items: center; gap: 12px; }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .topbar-avatar {
            width: 34px; height: 34px;
            border-radius: 9px;
            background: var(--primary-soft);
            display: flex; align-items: center; justify-content: center;
            color: var(--primary);
            font-size: 17px;
            flex-shrink: 0;
        }

        .topbar-name {
            font-size: 13px;
            font-weight: 700;
            color: var(--sl-900);
        }
        .topbar-role {
            font-size: 11px;
            color: var(--sl-400);
        }

        .btn-logout {
            display: flex;
            align-items: center;
            gap: 6px;
            background: #FEF2F2;
            color: #DC2626;
            border: 1.5px solid #FECACA;
            padding: 7px 14px;
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.15s;
        }
        .btn-logout:hover { background: #FEE2E2; }
        .btn-logout i { font-size: 15px; }

        /* ── CONTENT AREA ── */
        .content { padding: 28px 28px 40px; background: var(--sl-50); min-height: calc(100vh - 60px); }

        /* ── CARDS (used by sub-pages) ── */
        .card { border: 1px solid var(--sl-200); border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); background: var(--white); margin-bottom: 24px; }
        .card-header { background: var(--white); border-bottom: 1px solid var(--sl-200); padding: 16px 20px; border-radius: 12px 12px 0 0 !important; }
        .card-title { font-size: 15px; font-weight: 700; color: var(--sl-900); margin: 0; }
        .card-body { padding: 20px; }

        /* Buttons */
        .btn-primary { background: var(--primary); border: none; border-radius: 8px; padding: 9px 18px; font-weight: 600; font-family: 'Inter', sans-serif; transition: background 0.15s; }
        .btn-primary:hover { background: var(--primary-dark); }
        .btn-success  { background: #10B981; border: none; border-radius: 8px; padding: 8px 16px; font-family: 'Inter', sans-serif; }
        .btn-warning  { background: #F59E0B; border: none; border-radius: 8px; padding: 8px 16px; font-family: 'Inter', sans-serif; color: #fff; }
        .btn-danger   { background: #EF4444; border: none; border-radius: 8px; padding: 8px 16px; font-family: 'Inter', sans-serif; }
        .btn-sm { padding: 6px 12px; font-size: 12px; }

        /* Table */
        .table { margin-bottom: 0; }
        .table thead th { background: var(--sl-50); border-bottom: 1px solid var(--sl-200); font-weight: 700; color: var(--sl-500); font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 14px; }
        .table tbody td { padding: 12px 14px; vertical-align: middle; font-size: 13px; border-bottom: 1px solid var(--sl-100); color: var(--sl-700); }
        .table tbody tr:last-child td { border-bottom: none; }
        .table tbody tr:hover td { background: var(--sl-50); }

        /* Form */
        .form-label { font-weight: 600; color: var(--sl-700); font-size: 12px; margin-bottom: 6px; }
        .form-control, .form-select { border: 1.5px solid var(--sl-200); border-radius: 8px; padding: 9px 13px; font-family: 'Inter', sans-serif; font-size: 13px; transition: border-color 0.15s, box-shadow 0.15s; }
        .form-control:focus, .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37,99,235,0.10); }

        /* Badges */
        .badge { padding: 5px 10px; border-radius: 50px; font-weight: 700; font-size: 11px; }
        .bg-primary { background: var(--primary) !important; }
        .bg-success { background: #10B981 !important; }

        /* Alert */
        .alert { border-radius: 10px; border: none; padding: 12px 16px; }

        /* Pagination */
        .page-link { color: var(--primary); border: 1px solid var(--sl-200); padding: 7px 11px; border-radius: 7px; margin: 0 2px; font-size: 13px; }
        .page-link:hover { background: var(--primary); color: var(--white); border-color: var(--primary); }
        .page-item.active .page-link { background: var(--primary); border-color: var(--primary); }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s cubic-bezier(0.4,0,0.2,1), width 0.3s; }
            .sidebar.show { transform: translateX(0); }
            .sidebar.collapsed { width: var(--sidebar-w); }
            .main-content, .main-content.expanded { margin-left: 0; }
            .topbar { padding: 0 16px 0 64px; }
            .topbar-name, .topbar-role { display: none; }
            .content { padding: 20px 14px 40px; }
        }

        @media (max-width: 576px) {
            .btn-logout span { display: none; }
        }

        /* Scrollbar global */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: var(--sl-100); }
        ::-webkit-scrollbar-thumb { background: var(--sl-300); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--sl-400); }
    </style>


    @stack('styles')
</head>

<body>

    <!-- Toggle Button -->
    <button class="sidebar-toggle-btn" id="sidebarToggleBtn">
        <i class="mdi mdi-menu" id="toggleIcon"></i>
    </button>

    <!-- Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- ═══ SIDEBAR ═══ -->
    <div class="sidebar" id="sidebar">

        {{-- Brand --}}
        <div class="sb-brand">
            <div class="sb-logo-wrap">
                <img src="{{ asset('assets/img/logoypia.png') }}" alt="Logo">
            </div>
            <div class="sb-brand-text">
                <div class="sb-app-name">Presensi<span>GPS</span></div>
                <div class="sb-app-sub">YPI Al Azhar — Panel Admin</div>
            </div>
        </div>

        {{-- Navigation --}}
        <div class="sb-nav">

            {{-- ① OVERVIEW --}}
            <div class="sb-section-label">Overview</div>

            <a href="{{ route('panel.dashboard') }}"
               class="sb-item {{ Request::is('panel/dashboard') ? 'active' : '' }}"
               data-tip="Dashboard">
                <i class="mdi mdi-view-dashboard-outline"></i>
                <span class="sb-item-text">Dashboard</span>
            </a>

            {{-- ② MONITORING & PRESENSI --}}
            <div class="sb-section-label">Monitoring</div>

            {{-- Monitoring single --}}
            <a href="{{ route('panel.monitoring.index') }}"
               class="sb-item {{ Request::is('panel/monitoring*') ? 'active' : '' }}"
               data-tip="Monitoring">
                <i class="mdi mdi-map-marker-radius-outline"></i>
                <span class="sb-item-text">Monitoring Live</span>
            </a>

            {{-- Presensi & Laporan group --}}
            <div class="sb-group">
                @php
                    $presensiOpen = Request::is('panel/laporan*') || Request::is('panel/rekap*');
                @endphp
                <div class="sb-group-trigger {{ $presensiOpen ? 'open' : '' }}"
                     onclick="toggleGroup('grpPresensi', this)"
                     data-tip="Presensi">
                    <i class="trigger-icon mdi mdi-chart-bar"></i>
                    <span class="trigger-label">Laporan & Rekap</span>
                    <i class="mdi mdi-chevron-down sb-chevron"></i>
                </div>
                <div class="sb-children {{ $presensiOpen ? 'open' : '' }}" id="grpPresensi">
                    <a href="{{ route('panel.laporan.index') }}"
                       class="sb-sub-item {{ Request::is('panel/laporan*') ? 'active' : '' }}">
                        <i class="mdi mdi-file-chart-outline"></i> Laporan Presensi
                    </a>
                    <a href="{{ route('panel.rekap.index') }}"
                       class="sb-sub-item {{ Request::is('panel/rekap*') ? 'active' : '' }}">
                        <i class="mdi mdi-calendar-month-outline"></i> Rekap Kehadiran
                    </a>
                </div>
            </div>

            {{-- ③ KARYAWAN & IZIN --}}
            <div class="sb-section-label">Karyawan</div>

            @if(in_array(Auth::guard('user')->user()->role, ['admin', 'superadmin']))
            <div class="sb-group">
                @php
                    $karyawanOpen = Request::is('panel/karyawan*') || Request::is('panel/izinsakit*') || Request::is('panel/cuti*') || Request::is('panel/face-verification*');
                @endphp
                <div class="sb-group-trigger {{ $karyawanOpen ? 'open' : '' }}"
                     onclick="toggleGroup('grpKaryawan', this)"
                     data-tip="Karyawan">
                    <i class="trigger-icon mdi mdi-account-group-outline"></i>
                    <span class="trigger-label">Data Karyawan</span>
                    <i class="mdi mdi-chevron-down sb-chevron"></i>
                </div>
                <div class="sb-children {{ $karyawanOpen ? 'open' : '' }}" id="grpKaryawan">
                    <a href="{{ route('panel.karyawan.index') }}"
                       class="sb-sub-item {{ Request::is('panel/karyawan*') ? 'active' : '' }}">
                        <i class="mdi mdi-account-outline"></i> Data Karyawan
                    </a>
                    @if(in_array(Auth::guard('user')->user()->role, ['admin','superadmin','pimpinan']))
                    <a href="{{ route('panel.izinsakit.index') }}"
                       class="sb-sub-item {{ Request::is('panel/izinsakit*') ? 'active' : '' }}">
                        <i class="mdi mdi-hospital-box-outline"></i> Izin & Sakit
                    </a>
                    @endif
                    <a href="{{ route('panel.cuti.index') }}"
                       class="sb-sub-item {{ Request::is('panel/cuti*') ? 'active' : '' }}">
                        <i class="mdi mdi-calendar-text-outline"></i> Data Cuti
                    </a>
                    @if(in_array(Auth::guard('user')->user()->role, ['admin','superadmin']))
                    <a href="{{ route('panel.face-verification.index') }}"
                       class="sb-sub-item {{ Request::is('panel/face-verification*') ? 'active' : '' }}">
                        <i class="mdi mdi-face-recognition"></i> Verifikasi Wajah
                    </a>
                    @endif
                </div>
            </div>
            @endif

            {{-- ④ MASTER DATA --}}
            @if(in_array(Auth::guard('user')->user()->role, ['admin', 'superadmin']))
            <div class="sb-section-label">Master Data</div>
            <div class="sb-group">
                @php
                    $masterOpen = Request::is('panel/cabang*') || Request::is('panel/departemen*');
                @endphp
                <div class="sb-group-trigger {{ $masterOpen ? 'open' : '' }}"
                     onclick="toggleGroup('grpMaster', this)"
                     data-tip="Master Data">
                    <i class="trigger-icon mdi mdi-database-outline"></i>
                    <span class="trigger-label">Master Data</span>
                    <i class="mdi mdi-chevron-down sb-chevron"></i>
                </div>
                <div class="sb-children {{ $masterOpen ? 'open' : '' }}" id="grpMaster">
                    <a href="{{ route('panel.cabang.index') }}"
                       class="sb-sub-item {{ Request::is('panel/cabang*') ? 'active' : '' }}">
                        <i class="mdi mdi-office-building-outline"></i> Data Cabang
                    </a>
                    <a href="{{ route('panel.departemen.index') }}"
                       class="sb-sub-item {{ Request::is('panel/departemen*') ? 'active' : '' }}">
                        <i class="mdi mdi-file-tree"></i> Data Departemen
                    </a>
                </div>
            </div>
            @endif

            {{-- ⑤ KONFIGURASI --}}
            @if(in_array(Auth::guard('user')->user()->role, ['admin', 'superadmin']))
            <div class="sb-section-label">Konfigurasi</div>
            <div class="sb-group">
                @php
                    $konfOpen = Request::is('panel/jamkerja*') || Request::is('panel/konfigurasi-jk-dept*') || Request::is('panel/user*') || Request::is('panel/activity-logs*');
                @endphp
                <div class="sb-group-trigger {{ $konfOpen ? 'open' : '' }}"
                     onclick="toggleGroup('grpKonf', this)"
                     data-tip="Konfigurasi">
                    <i class="trigger-icon mdi mdi-cog-outline"></i>
                    <span class="trigger-label">Konfigurasi</span>
                    <i class="mdi mdi-chevron-down sb-chevron"></i>
                </div>
                <div class="sb-children {{ $konfOpen ? 'open' : '' }}" id="grpKonf">
                    <a href="{{ route('panel.jamkerja.index') }}"
                       class="sb-sub-item {{ Request::is('panel/jamkerja*') ? 'active' : '' }}">
                        <i class="mdi mdi-clock-outline"></i> Jam Kerja
                    </a>
                    <a href="{{ route('panel.konfigurasi-jk-dept.index') }}"
                       class="sb-sub-item {{ Request::is('panel/konfigurasi-jk-dept*') ? 'active' : '' }}">
                        <i class="mdi mdi-cog-sync-outline"></i> Jam Kerja Dept.
                    </a>
                    @if(Auth::guard('user')->user()->role == 'superadmin')
                    <a href="{{ route('panel.user.index') }}"
                       class="sb-sub-item {{ Request::is('panel/user*') ? 'active' : '' }}">
                        <i class="mdi mdi-account-cog-outline"></i> Data User
                    </a>
                    <a href="{{ route('panel.activity-logs.index') }}"
                       class="sb-sub-item {{ Request::is('panel/activity-logs*') ? 'active' : '' }}">
                        <i class="mdi mdi-history"></i> Audit Trail
                    </a>
                    @endif
                </div>
            </div>
            @endif

        </div>{{-- end sb-nav --}}

        {{-- Sidebar Footer: user + logout --}}
        <div class="sb-footer">
            <div class="sb-user">
                <div class="sb-user-avatar"><i class="mdi mdi-account"></i></div>
                <div class="sb-user-info">
                    <div class="sb-user-name">{{ Auth::guard('user')->user()->name }}</div>
                    <div class="sb-user-role">{{ ucfirst(Auth::guard('user')->user()->role) }}</div>
                </div>
            </div>
        </div>

    </div>{{-- end sidebar --}}

    <!-- ═══ MAIN CONTENT ═══ -->
    <div class="main-content" id="mainContent">

        {{-- Topbar --}}
        <div class="topbar">
            <div class="topbar-title">
                <i class="mdi mdi-slash-forward" style="color:var(--sl-300);font-size:18px;"></i>
                @yield('page-title')
            </div>
            <div class="topbar-right">
                <div class="topbar-user">
                    <div class="topbar-avatar"><i class="mdi mdi-account"></i></div>
                    <div>
                        <div class="topbar-name">{{ Auth::guard('user')->user()->name }}</div>
                        <div class="topbar-role">{{ ucfirst(Auth::guard('user')->user()->role) }}</div>
                    </div>
                </div>
                <form action="{{ route('panel.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="mdi mdi-logout"></i>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </div>

        {{-- Content --}}
        <div class="content">
            @yield('content')
        </div>

    </div>{{-- end main-content --}}

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // ═══ SIDEBAR LOGIC ═══
        const sidebar     = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const overlay     = document.getElementById('sidebarOverlay');
        const toggleBtn   = document.getElementById('sidebarToggleBtn');
        const toggleIcon  = document.getElementById('toggleIcon');

        function isMobile() { return window.innerWidth <= 992; }

        function loadState() {
            if (localStorage.getItem('sb_collapsed') === 'true' && !isMobile()) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
                setIcon(true);
            }
        }

        function setIcon(collapsed) {
            if (collapsed && !isMobile()) {
                toggleIcon.classList.replace('mdi-menu', 'mdi-menu-open');
            } else {
                toggleIcon.classList.replace('mdi-menu-open', 'mdi-menu');
            }
        }

        function toggleSidebar() {
            if (isMobile()) {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
                document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
            } else {
                const c = sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
                setIcon(c);
                localStorage.setItem('sb_collapsed', c);
            }
        }

        toggleBtn.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);

        // Close sidebar on mobile when clicking nav links
        document.querySelectorAll('.sb-item, .sb-sub-item').forEach(el => {
            el.addEventListener('click', () => {
                if (isMobile() && sidebar.classList.contains('show')) toggleSidebar();
            });
        });

        // Resize handler
        let resizeT;
        window.addEventListener('resize', () => {
            clearTimeout(resizeT);
            resizeT = setTimeout(() => {
                if (isMobile()) {
                    sidebar.classList.remove('collapsed');
                    mainContent.classList.remove('expanded');
                } else {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                    document.body.style.overflow = '';
                    loadState();
                }
                setIcon(sidebar.classList.contains('collapsed'));
            }, 200);
        });

        loadState();

        // ── CUSTOM GROUP TOGGLE (pure JS, no Bootstrap collapse) ──
        function toggleGroup(groupId, trigger) {
            const el = document.getElementById(groupId);
            if (!el) return;
            const isOpen = el.classList.toggle('open');
            trigger.classList.toggle('open', isOpen);
        }

        // ===== AUTO HIDE ALERTS =====
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);

        // ===== CONFIRM DELETE =====
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>

    @stack('scripts')
</body>

</html>
