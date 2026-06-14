<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>@yield('title') - YPI Al Azhar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/img/logoypia.png') }}" type="image/png" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #0053C5;
            --primary-dark: #003d94;
            --primary-light: #3379d9;
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 70px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
        }

        /* ===== TOGGLE BUTTON (Desktop & Mobile) ===== */
        .sidebar-toggle-btn {
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1100;
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #0053C5 0%, #003d94 100%);
            border: none;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 83, 197, 0.3);
            transition: all 0.3s;
        }

        .sidebar-toggle-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 16px rgba(0, 83, 197, 0.4);
        }

        .sidebar-toggle-btn i {
            color: white;
            font-size: 24px;
            transition: transform 0.3s;
        }

        /* ===== SIDEBAR OVERLAY (Mobile) ===== */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 998;
            display: none;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .sidebar-overlay.show {
            display: block;
            opacity: 1;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: #ffffff;
            color: #333;
            border-right: 1px solid #e5e7eb;
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 999;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Sidebar Collapsed State (Desktop) */
        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
        }

        /* ===== SIDEBAR HEADER ===== */
        .sidebar-header {
            padding: 25px 20px;
            text-align: center;
            border-bottom: 1px solid #e5e7eb;
            transition: all 0.3s;
        }

        .sidebar-header img.sidebar-logo {
            width: 48px;
            height: auto;
            margin-bottom: 10px;
            transition: all 0.3s;
        }

        .sidebar-header h4 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
            white-space: nowrap;
            opacity: 1;
            transition: all 0.3s;
        }

        .sidebar-header p {
            margin: 5px 0 0 0;
            font-size: 12px;
            color: #6b7280;
            transition: all 0.3s;
        }

        /* Collapsed Header */
        .sidebar.collapsed .sidebar-header {
            padding: 20px 10px;
        }

        .sidebar.collapsed .sidebar-header img.sidebar-logo {
            width: 32px;
            margin-bottom: 0;
        }

        .sidebar.collapsed .sidebar-header h4,
        .sidebar.collapsed .sidebar-header p {
            opacity: 0;
            height: 0;
            overflow: hidden;
        }

        /* ===== SIDEBAR MENU ===== */
        .sidebar-menu {
            padding: 20px 0;
        }

        /* ===== MENU GROUP (Collapsible) ===== */
        .menu-group {
            margin-bottom: 10px;
        }

        .menu-group-header {
            padding: 12px 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .menu-group-header:hover {
            background: #f8f9fa;
            color: var(--primary-color);
        }

        .menu-group-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            font-weight: 600;
            color: #4b5563;
            flex: 1;
            white-space: nowrap;
        }

        .menu-group-title i {
            font-size: 20px;
            min-width: 24px;
            text-align: center;
        }

        .menu-group-title span {
            opacity: 1;
            transition: opacity 0.3s;
        }

        .menu-group-icon {
            transition: transform 0.3s;
            opacity: 1;
        }

        .menu-group-header[aria-expanded="true"] .menu-group-icon {
            transform: rotate(180deg);
        }

        /* Collapsed Menu Group */
        .sidebar.collapsed .menu-group-header {
            padding: 12px 10px;
            justify-content: center;
        }

        .sidebar.collapsed .menu-group-title span,
        .sidebar.collapsed .menu-group-icon {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        /* Menu Items Inside Group */
        .menu-group-content {
            overflow: hidden;
        }

        .menu-item {
            padding: 12px 20px 12px 50px;
            color: #6b7280;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: all 0.3s;
            border-left: 3px solid transparent;
            font-size: 14px;
            white-space: nowrap;
        }

        .menu-item:hover {
            background: #f8f9fa;
            color: var(--primary-color);
        }

        .menu-item.active {
            background: #eff6ff;
            color: var(--primary-color);
            border-left-color: var(--primary-color);
            font-weight: 600;
        }

        .menu-item i {
            margin-right: 10px;
            font-size: 18px;
            min-width: 20px;
            text-align: center;
        }

        .menu-item span {
            opacity: 1;
            transition: opacity 0.3s;
        }

        /* Collapsed Menu Items */
        .sidebar.collapsed .menu-item {
            padding: 12px 10px;
            justify-content: center;
        }

        .sidebar.collapsed .menu-item span {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        /* Hide submenu when collapsed */
        .sidebar.collapsed .menu-group-content {
            display: none;
        }

        /* Single Menu Item (No Group) */
        .menu-single {
            padding: 12px 20px;
            color: #4b5563;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: all 0.3s;
            border-left: 3px solid transparent;
            margin-bottom: 5px;
            white-space: nowrap;
        }

        .menu-single:hover {
            background: #f8f9fa;
            color: var(--primary-color);
        }

        .menu-single.active {
            background: #eff6ff;
            color: var(--primary-color);
            border-left-color: var(--primary-color);
            font-weight: 600;
        }

        .menu-single i {
            margin-right: 10px;
            font-size: 20px;
            min-width: 24px;
            text-align: center;
        }

        .menu-single span {
            opacity: 1;
            transition: opacity 0.3s;
        }

        /* Collapsed Single Menu */
        .sidebar.collapsed .menu-single {
            padding: 12px 10px;
            justify-content: center;
        }

        .sidebar.collapsed .menu-single span {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        /* ===== TOOLTIP FOR COLLAPSED STATE ===== */
        .sidebar.collapsed .menu-single,
        .sidebar.collapsed .menu-group-header {
            position: relative;
        }

        .sidebar.collapsed .menu-single::after,
        .sidebar.collapsed .menu-group-header::after {
            content: attr(data-tooltip);
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            background: #333;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 13px;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s;
            margin-left: 10px;
            z-index: 1000;
        }

        .sidebar.collapsed .menu-single:hover::after,
        .sidebar.collapsed .menu-group-header:hover::after {
            opacity: 1;
            margin-left: 15px;
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .main-content.expanded {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* ===== TOPBAR ===== */
        .topbar {
            background: #ffffff;
            padding: 15px 30px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e5e7eb;
        }

        .topbar h5 {
            margin: 0;
            color: #111827;
            font-weight: 600;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-info {
            text-align: right;
        }

        .user-info .name {
            font-weight: 600;
            color: #111827;
            font-size: 14px;
        }

        .user-info .role {
            font-size: 12px;
            color: #6b7280;
        }

        .btn-logout {
            background: #dc3545;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-logout:hover {
            background: #c82333;
            transform: translateY(-2px);
        }

        /* ===== CONTENT AREA ===== */
        .content {
            padding: 30px;
        }

        /* ===== CARD ===== */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #f0f0f0;
            padding: 20px;
            border-radius: 10px 10px 0 0 !important;
        }

        .card-title {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        .card-body {
            padding: 20px;
        }

        /* ===== BUTTONS ===== */
        .btn-primary {
            background: linear-gradient(135deg, #0053C5 0%, #003d94 100%);
            border: none;
            padding: 10px 24px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 83, 197, 0.3);
        }

        .btn-success {
            background: #28a745;
            border: none;
            padding: 8px 20px;
            border-radius: 6px;
        }

        .btn-warning {
            background: #ffc107;
            border: none;
            padding: 8px 20px;
            border-radius: 6px;
            color: #333;
        }

        .btn-danger {
            background: #dc3545;
            border: none;
            padding: 8px 20px;
            border-radius: 6px;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 13px;
        }

        /* ===== TABLE ===== */
        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            color: #333;
            padding: 12px;
        }

        .table tbody td {
            padding: 12px;
            vertical-align: middle;
        }

        /* ===== FORM ===== */
        .form-label {
            font-weight: 500;
            color: #333;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 10px 15px;
            transition: all 0.3s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #0053C5;
            box-shadow: 0 0 0 0.2rem rgba(0, 83, 197, 0.25);
        }

        /* ===== BADGE ===== */
        .badge {
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 500;
        }

        /* ===== ALERT ===== */
        .alert {
            border-radius: 8px;
            border: none;
            padding: 15px 20px;
        }

        /* ===== PAGINATION ===== */
        .pagination {
            margin: 0;
        }

        .page-link {
            color: #0053C5;
            border: 1px solid #ddd;
            padding: 8px 12px;
            margin: 0 2px;
            border-radius: 6px;
        }

        .page-link:hover {
            background: #0053C5;
            color: white;
            border-color: #0053C5;
        }

        .page-item.active .page-link {
            background: #0053C5;
            border-color: #0053C5;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 992px) {

            /* Mobile: Sidebar slides from left */
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            /* Mobile: No collapsed state, always full width when shown */
            .sidebar.collapsed {
                width: var(--sidebar-width);
            }

            .main-content,
            .main-content.expanded {
                margin-left: 0;
            }

            .topbar {
                padding: 15px 15px 15px 70px;
            }

            .topbar h5 {
                font-size: 16px;
            }

            .user-info {
                display: none;
            }

            .content {
                padding: 20px 15px;
            }

            /* Hide tooltips on mobile */
            .sidebar.collapsed .menu-single::after,
            .sidebar.collapsed .menu-group-header::after {
                display: none;
            }
        }

        @media (max-width: 576px) {
            .topbar h5 {
                font-size: 14px;
            }

            .btn-logout {
                padding: 6px 12px;
                font-size: 13px;
            }
        }

        /* Desktop: Show collapse functionality */
        @media (min-width: 993px) {
            .sidebar.collapsed .sidebar-menu {
                padding: 10px 0;
            }
        }

        /* ===== CUSTOM SCROLLBAR ===== */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Toggle Button (Desktop & Mobile) -->
    <button class="sidebar-toggle-btn" id="sidebarToggleBtn">
        <i class="mdi mdi-menu" id="toggleIcon"></i>
    </button>

    <!-- Sidebar Overlay (Mobile Only) -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('assets/img/logoypia.png') }}" alt="Logo YPI" class="sidebar-logo">
            <h4>YPI Al Azhar</h4>
            <p>Sistem Presensi</p>
        </div>

        <div class="sidebar-menu">
            <!-- Dashboard (Single Menu) -->
            <a href="{{ route('panel.dashboard') }}"
                class="menu-single {{ Request::is('panel/dashboard') ? 'active' : '' }}"
                data-tooltip="Dashboard">
                <i class="mdi mdi-view-dashboard"></i>
                <span>Dashboard</span>
            </a>

            <!-- Master Data Group (Collapsible) -->
            <div class="menu-group">
                <div class="menu-group-header"
                    data-bs-toggle="collapse"
                    data-bs-target="#masterDataMenu"
                    data-tooltip="Master Data"
                    aria-expanded="{{ Request::is('panel/cabang*') || Request::is('panel/departemen*') || Request::is('panel/karyawan*') ? 'true' : 'false' }}">
                    <div class="menu-group-title">
                        <i class="mdi mdi-database"></i>
                        <span>Master Data</span>
                    </div>
                    <i class="mdi mdi-chevron-down menu-group-icon"></i>
                </div>
                <div class="collapse {{ Request::is('panel/cabang*') || Request::is('panel/departemen*') || Request::is('panel/karyawan*') ? 'show' : '' }}"
                    id="masterDataMenu">
                    <div class="menu-group-content">
                        <a href="{{ route('panel.cabang.index') }}"
                            class="menu-item {{ Request::is('panel/cabang*') ? 'active' : '' }}">
                            <i class="mdi mdi-office-building"></i>
                            <span>Data Cabang</span>
                        </a>
                        <a href="{{ route('panel.departemen.index') }}"
                            class="menu-item {{ Request::is('panel/departemen*') ? 'active' : '' }}">
                            <i class="mdi mdi-file-tree"></i>
                            <span>Data Departemen</span>
                        </a>
                        <a href="{{ route('panel.karyawan.index') }}"
                            class="menu-item {{ Request::is('panel/karyawan*') ? 'active' : '' }}">
                            <i class="mdi mdi-account-group"></i>
                            <span>Data Karyawan</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Konfigurasi Group (Collapsible) -->
            <div class="menu-group">
                <div class="menu-group-header"
                    data-bs-toggle="collapse"
                    data-bs-target="#konfigurasiMenu"
                    data-tooltip="Konfigurasi"
                    aria-expanded="{{ Request::is('panel/jamkerja*') || Request::is('panel/konfigurasi-jk-dept*') || Request::is('panel/user*') ? 'true' : 'false' }}">
                    <div class="menu-group-title">
                        <i class="mdi mdi-cog"></i>
                        <span>Konfigurasi</span>
                    </div>
                    <i class="mdi mdi-chevron-down menu-group-icon"></i>
                </div>
                <div class="collapse {{ Request::is('panel/jamkerja*') || Request::is('panel/konfigurasi-jk-dept*') || Request::is('panel/user*') ? 'show' : '' }}"
                    id="konfigurasiMenu">
                    <div class="menu-group-content">
                        <a href="{{ route('panel.user.index') }}"
                            class="menu-item {{ Request::is('panel/user*') ? 'active' : '' }}">
                            <i class="mdi mdi-account-cog"></i>
                            <span>Data User</span>
                        </a>
                        <a href="{{ route('panel.jamkerja.index') }}"
                            class="menu-item {{ Request::is('panel/jamkerja*') ? 'active' : '' }}">
                            <i class="mdi mdi-clock-outline"></i>
                            <span>Jam Kerja</span>
                        </a>
                        <a href="{{ route('panel.konfigurasi-jk-dept.index') }}"
                            class="menu-item {{ Request::is('panel/konfigurasi-jk-dept*') ? 'active' : '' }}">
                            <i class="mdi mdi-cog-outline"></i>
                            <span>Jam Kerja Departemen</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Presensi & Laporan Group (Collapsible) -->
            <div class="menu-group">
                <div class="menu-group-header"
                    data-bs-toggle="collapse"
                    data-bs-target="#presensiMenu"
                    data-tooltip="Presensi & Laporan"
                    aria-expanded="{{ Request::is('panel/monitoring*') || Request::is('panel/laporan*') || Request::is('panel/rekap*') || Request::is('panel/face-verification*') || Request::is('panel/izinsakit*') ? 'true' : 'false' }}">
                    <div class="menu-group-title">
                        <i class="mdi mdi-map-marker-check"></i>
                        <span>Presensi & Laporan</span>
                    </div>
                    <i class="mdi mdi-chevron-down menu-group-icon"></i>
                </div>
                <div class="collapse {{ Request::is('panel/monitoring*') || Request::is('panel/laporan*') || Request::is('panel/rekap*') || Request::is('panel/face-verification*') || Request::is('panel/izinsakit*') ? 'show' : '' }}"
                    id="presensiMenu">
                    <div class="menu-group-content">
                        <a href="{{ route('panel.monitoring.index') }}"
                            class="menu-item {{ Request::is('panel/monitoring*') ? 'active' : '' }}">
                            <i class="mdi mdi-map"></i>
                            <span>Monitoring Presensi</span>
                        </a>
                        <a href="{{ route('panel.laporan.index') }}"
                            class="menu-item {{ Request::is('panel/laporan*') ? 'active' : '' }}">
                            <i class="mdi mdi-file-document"></i>
                            <span>Laporan Presensi</span>
                        </a>
                        <a href="{{ route('panel.rekap.index') }}"
                            class="menu-item {{ Request::is('panel/rekap*') ? 'active' : '' }}">
                            <i class="mdi mdi-calendar-month"></i>
                            <span>Rekap Kehadiran</span>
                        </a>
                        <a href="{{ route('panel.izinsakit.index') }}"
                            class="menu-item {{ Request::is('panel/izinsakit*') ? 'active' : '' }}">
                            <i class="mdi mdi-hospital-box-outline"></i>
                            <span>Data Izin / Sakit</span>
                        </a>
                        <a href="{{ route('panel.face-verification.index') }}"
                            class="menu-item {{ Request::is('panel/face-verification*') ? 'active' : '' }}">
                            <i class="mdi mdi-face-recognition"></i>
                            <span>Verifikasi Wajah</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Topbar -->
        <div class="topbar">
            <h5>@yield('page-title')</h5>
            <div class="user-menu">
                <div class="user-info">
                    <div class="name">{{ Auth::guard('user')->user()->name }}</div>
                    <div class="role">Administrator</div>
                </div>
                <form action="{{ route('panel.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="mdi mdi-logout"></i> Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Content -->
        <div class="content">
            @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // ===== SIDEBAR TOGGLE FUNCTIONALITY =====
        const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const mainContent = document.getElementById('mainContent');
        const toggleIcon = document.getElementById('toggleIcon');

        // Check if we're on mobile or desktop
        function isMobile() {
            return window.innerWidth <= 992;
        }

        // Load saved state from localStorage
        function loadSidebarState() {
            const savedState = localStorage.getItem('sidebarCollapsed');
            if (savedState === 'true' && !isMobile()) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
                updateToggleIcon(true);
            }
        }

        // Save state to localStorage
        function saveSidebarState(isCollapsed) {
            localStorage.setItem('sidebarCollapsed', isCollapsed);
        }

        // Update toggle button icon
        function updateToggleIcon(isCollapsed) {
            if (isCollapsed && !isMobile()) {
                toggleIcon.classList.remove('mdi-menu');
                toggleIcon.classList.add('mdi-menu-open');
            } else {
                toggleIcon.classList.remove('mdi-menu-open');
                toggleIcon.classList.add('mdi-menu');
            }
        }

        // Toggle sidebar
        function toggleSidebar() {
            if (isMobile()) {
                // Mobile: slide in/out
                sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');

                // Prevent body scroll when sidebar is open
                if (sidebar.classList.contains('show')) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            } else {
                // Desktop: collapse/expand
                const isCollapsed = sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
                updateToggleIcon(isCollapsed);
                saveSidebarState(isCollapsed);

                // Close all open menus when collapsing
                if (isCollapsed) {
                    const openMenus = document.querySelectorAll('.collapse.show');
                    openMenus.forEach(menu => {
                        const bsCollapse = new bootstrap.Collapse(menu, {
                            toggle: false
                        });
                        bsCollapse.hide();
                    });
                }
            }
        }

        // Event listeners
        sidebarToggleBtn.addEventListener('click', toggleSidebar);
        sidebarOverlay.addEventListener('click', toggleSidebar);

        // Close sidebar when clicking menu item on mobile
        const menuItems = document.querySelectorAll('.menu-item, .menu-single');
        menuItems.forEach(item => {
            item.addEventListener('click', function() {
                if (isMobile() && sidebar.classList.contains('show')) {
                    toggleSidebar();
                }
            });
        });

        // Handle window resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                if (isMobile()) {
                    // Mobile: remove collapsed state
                    sidebar.classList.remove('collapsed');
                    mainContent.classList.remove('expanded');
                    if (!sidebar.classList.contains('show')) {
                        document.body.style.overflow = '';
                    }
                } else {
                    // Desktop: restore saved state
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                    document.body.style.overflow = '';
                    loadSidebarState();
                }
                updateToggleIcon(sidebar.classList.contains('collapsed'));
            }, 250);
        });

        // Prevent menu group toggle when sidebar is collapsed on desktop
        const menuGroupHeaders = document.querySelectorAll('.menu-group-header');
        menuGroupHeaders.forEach(header => {
            header.addEventListener('click', function(e) {
                if (!isMobile() && sidebar.classList.contains('collapsed')) {
                    e.preventDefault();
                    e.stopPropagation();
                }
            });
        });

        // Initialize sidebar state
        loadSidebarState();

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