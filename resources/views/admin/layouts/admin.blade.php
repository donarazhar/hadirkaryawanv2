<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>@yield('title') - YPI Al Azhar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/img/ypia.png') }}">

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
            --sidebar-width: 260px;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #0053C5 0%, #003d94 100%);
            color: white;
            overflow-y: auto;
            z-index: 1000;
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
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h4 {
            margin: 10px 0 5px 0;
            font-size: 20px;
            font-weight: 600;
        }

        .sidebar-header p {
            margin: 0;
            font-size: 12px;
            opacity: 0.8;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-title {
            padding: 10px 20px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.6;
            font-weight: 600;
        }

        .menu-item {
            padding: 12px 20px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: all 0.3s;
            border-left: 3px solid transparent;
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
            font-size: 20px;
            width: 24px;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        /* Topbar */
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

        /* Content Area */
        .content {
            padding: 30px;
        }

        /* Card */
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

        /* Buttons */
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

        /* Table */
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

        /* Form */
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

        /* Badge */
        .badge {
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 500;
        }

        /* Alert */
        .alert {
            border-radius: 8px;
            border: none;
            padding: 15px 20px;
        }

        /* Pagination */
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

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                margin-left: calc(var(--sidebar-width) * -1);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar.show {
                margin-left: 0;
            }
        }

        /* Custom Scrollbar */
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
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <i class="mdi mdi-shield-star" style="font-size: 48px;"></i>
            <h4>YPI Al Azhar</h4>
            <p>Sistem Presensi</p>
        </div>

        <div class="sidebar-menu">
            <div class="menu-title">Main Menu</div>
            <a href="{{ route('panel.dashboard') }}" class="menu-item {{ Request::is('panel/dashboard') ? 'active' : '' }}">
                <i class="mdi mdi-view-dashboard"></i>
                <span>Dashboard</span>
            </a>

            <div class="menu-title">Master Data</div>
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

            <div class="menu-title">Konfigurasi</div>
            <a href="{{ route('panel.jamkerja.index') }}" class="menu-item {{ Request::is('panel/jamkerja*') ? 'active' : '' }}">
                <i class="mdi mdi-clock-outline"></i>
                <span>Jam Kerja</span>
            </a>
            <a href="{{ route('panel.konfigurasi-jk-dept.index') }}" class="menu-item {{ Request::is('panel/konfigurasi-jk-dept*') ? 'active' : '' }}">
                <i class="mdi mdi-cog"></i>
                <span>Jam Kerja Departemen</span>
            </a>
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
        // Auto hide alerts
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);

        // Confirm delete
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