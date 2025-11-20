<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>@yield('title') - YPI Al Azhar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="shortcut icon" href="https://siap.al-azhar.id/upload/favicon.ico" type="image/x-icon" />

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

        /* ===== HAMBURGER BUTTON ===== */
        .hamburger-btn {
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1100;
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #0053C5 0%, #003d94 100%);
            border: none;
            border-radius: 10px;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 83, 197, 0.3);
            transition: all 0.3s;
        }

        .hamburger-btn:hover {
            transform: scale(1.05);
        }

        .hamburger-btn i {
            color: white;
            font-size: 24px;
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
            background: linear-gradient(180deg, #0053C5 0%, #003d94 100%);
            color: white;
            overflow-y: auto;
            z-index: 999;
            transition: all 0.3s;
        }

        .sidebar::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
        }

        .sidebar-header {
            padding: 25px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header i {
            font-size: 48px;
            margin-bottom: 10px;
        }

        .sidebar-header h4 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
        }

        .sidebar-header p {
            margin: 5px 0 0 0;
            font-size: 12px;
            opacity: 0.8;
        }

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
            background: rgba(255, 255, 255, 0.05);
        }

        .menu-group-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9);
        }

        .menu-group-title i {
            font-size: 20px;
        }

        .menu-group-icon {
            transition: transform 0.3s;
        }

        .menu-group-header[aria-expanded="true"] .menu-group-icon {
            transform: rotate(180deg);
        }

        /* Menu Items Inside Group */
        .menu-group-content {
            overflow: hidden;
        }

        .menu-item {
            padding: 12px 20px 12px 50px;
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: all 0.3s;
            border-left: 3px solid transparent;
            font-size: 14px;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-left-color: white;
        }

        .menu-item.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border-left-color: white;
            font-weight: 600;
        }

        .menu-item i {
            margin-right: 10px;
            font-size: 18px;
            width: 20px;
        }

        /* Single Menu Item (No Group) */
        .menu-single {
            padding: 12px 20px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: all 0.3s;
            border-left: 3px solid transparent;
            margin-bottom: 5px;
        }

        .menu-single:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-left-color: white;
        }

        .menu-single.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border-left-color: white;
            font-weight: 600;
        }

        .menu-single i {
            margin-right: 10px;
            font-size: 20px;
            width: 24px;
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s;
        }

        /* ===== TOPBAR ===== */
        .topbar {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .topbar h5 {
            margin: 0;
            color: #333;
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
            color: #333;
            font-size: 14px;
        }

        .user-info .role {
            font-size: 12px;
            color: #999;
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
            .hamburger-btn {
                display: flex;
            }

            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
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
    <!-- Hamburger Button (Mobile Only) -->
    <button class="hamburger-btn" id="hamburgerBtn">
        <i class="mdi mdi-menu"></i>
    </button>

    <!-- Sidebar Overlay (Mobile Only) -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <i class="mdi mdi-shield-star"></i>
            <h4>YPI Al Azhar</h4>
            <p>Sistem Presensi</p>
        </div>

        <div class="sidebar-menu">
            <!-- Dashboard (Single Menu) -->
            <a href="{{ route('panel.dashboard') }}" class="menu-single {{ Request::is('panel/dashboard') ? 'active' : '' }}">
                <i class="mdi mdi-view-dashboard"></i>
                <span>Dashboard</span>
            </a>

            <!-- Master Data Group (Collapsible) -->
            <div class="menu-group">
                <div class="menu-group-header" data-bs-toggle="collapse" data-bs-target="#masterDataMenu" aria-expanded="{{ Request::is('panel/cabang*') || Request::is('panel/departemen*') || Request::is('panel/karyawan*') ? 'true' : 'false' }}">
                    <div class="menu-group-title">
                        <i class="mdi mdi-database"></i>
                        <span>Master Data</span>
                    </div>
                    <i class="mdi mdi-chevron-down menu-group-icon"></i>
                </div>
                <div class="collapse {{ Request::is('panel/cabang*') || Request::is('panel/departemen*') || Request::is('panel/karyawan*') ? 'show' : '' }}" id="masterDataMenu">
                    <div class="menu-group-content">
                        <a href="{{ route('panel.cabang.index') }}" class="menu-item {{ Request::is('panel/cabang*') ? 'active' : '' }}">
                            <i class="mdi mdi-office-building"></i>
                            <span>Data Cabang</span>
                        </a>
                        <a href="{{ route('panel.departemen.index') }}" class="menu-item {{ Request::is('panel/departemen*') ? 'active' : '' }}">
                            <i class="mdi mdi-file-tree"></i>
                            <span>Data Departemen</span>
                        </a>
                        <a href="{{ route('panel.karyawan.index') }}" class="menu-item {{ Request::is('panel/karyawan*') ? 'active' : '' }}">
                            <i class="mdi mdi-account-group"></i>
                            <span>Data Karyawan</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Konfigurasi Group (Collapsible) -->
            <div class="menu-group">
                <div class="menu-group-header" data-bs-toggle="collapse" data-bs-target="#konfigurasiMenu" aria-expanded="{{ Request::is('panel/jamkerja*') || Request::is('panel/konfigurasi-jk-dept*') ? 'true' : 'false' }}">
                    <div class="menu-group-title">
                        <i class="mdi mdi-cog"></i>
                        <span>Konfigurasi</span>
                    </div>
                    <i class="mdi mdi-chevron-down menu-group-icon"></i>
                </div>
                <div class="collapse {{ Request::is('panel/jamkerja*') || Request::is('panel/konfigurasi-jk-dept*') ? 'show' : '' }}" id="konfigurasiMenu">
                    <div class="menu-group-content">
                        <a href="{{ route('panel.jamkerja.index') }}" class="menu-item {{ Request::is('panel/jamkerja*') ? 'active' : '' }}">
                            <i class="mdi mdi-clock-outline"></i>
                            <span>Jam Kerja</span>
                        </a>
                        <a href="{{ route('panel.konfigurasi-jk-dept.index') }}" class="menu-item {{ Request::is('panel/konfigurasi-jk-dept*') ? 'active' : '' }}">
                            <i class="mdi mdi-cog-outline"></i>
                            <span>Jam Kerja Departemen</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Presensi Group (Collapsible) - Contoh tambahan -->
            <div class="menu-group">
                <div class="menu-group-header" data-bs-toggle="collapse" data-bs-target="#presensiMenu" aria-expanded="false">
                    <div class="menu-group-title">
                        <i class="mdi mdi-calendar-check"></i>
                        <span>Presensi</span>
                    </div>
                    <i class="mdi mdi-chevron-down menu-group-icon"></i>
                </div>
                <div class="collapse" id="presensiMenu">
                    <div class="menu-group-content">
                        <a href="#" class="menu-item">
                            <i class="mdi mdi-calendar-today"></i>
                            <span>Rekap Harian</span>
                        </a>
                        <a href="#" class="menu-item">
                            <i class="mdi mdi-calendar-month"></i>
                            <span>Rekap Bulanan</span>
                        </a>
                        <a href="#" class="menu-item">
                            <i class="mdi mdi-file-export"></i>
                            <span>Export Data</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Laporan Group (Collapsible) - Contoh tambahan -->
            <div class="menu-group">
                <div class="menu-group-header" data-bs-toggle="collapse" data-bs-target="#laporanMenu" aria-expanded="false">
                    <div class="menu-group-title">
                        <i class="mdi mdi-file-chart"></i>
                        <span>Laporan</span>
                    </div>
                    <i class="mdi mdi-chevron-down menu-group-icon"></i>
                </div>
                <div class="collapse" id="laporanMenu">
                    <div class="menu-group-content">
                        <a href="#" class="menu-item">
                            <i class="mdi mdi-chart-line"></i>
                            <span>Grafik Kehadiran</span>
                        </a>
                        <a href="#" class="menu-item">
                            <i class="mdi mdi-account-clock"></i>
                            <span>Keterlambatan</span>
                        </a>
                        <a href="#" class="menu-item">
                            <i class="mdi mdi-account-off"></i>
                            <span>Ketidakhadiran</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
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
        // ===== SIDEBAR TOGGLE (Mobile) =====
        const hamburgerBtn = document.getElementById('hamburgerBtn');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            sidebar.classList.toggle('show');
            sidebarOverlay.classList.toggle('show');
        }

        hamburgerBtn.addEventListener('click', toggleSidebar);
        sidebarOverlay.addEventListener('click', toggleSidebar);

        // Close sidebar when clicking menu item on mobile
        const menuItems = document.querySelectorAll('.menu-item, .menu-single');
        menuItems.forEach(item => {
            item.addEventListener('click', function() {
                if (window.innerWidth <= 992) {
                    toggleSidebar();
                }
            });
        });

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

        // ===== PREVENT BODY SCROLL WHEN SIDEBAR OPEN (Mobile) =====
        const mutationObserver = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (sidebar.classList.contains('show')) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            });
        });

        mutationObserver.observe(sidebar, {
            attributes: true,
            attributeFilter: ['class']
        });
    </script>

    @stack('scripts')
</body>

</html>