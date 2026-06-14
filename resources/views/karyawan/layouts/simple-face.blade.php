<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#0053C5">
    <title>YPI Al Azhar - E-Presensi</title>
    <meta name="description" content="Sistem Presensi YPI Al Azhar">
    <link rel="shortcut icon" href="{{ asset('assets/img/logoypia.png') }}" type="image/png" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f5f7fa;
            padding-bottom: 70px; /* Space for bottom menu */
        }

        /* ===== BOTTOM NAVIGATION ===== */
        .appBottomMenu {
            min-height: 65px;
            position: fixed;
            z-index: 9999;
            bottom: 0;
            left: 0;
            right: 0;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-around;
            border-top: 1px solid #f3f4f6;
            padding-bottom: env(safe-area-inset-bottom);
            box-shadow: 0 -4px 10px rgba(0,0,0,0.03);
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
        }
        
        /* Hide menu if needed using .d-none */
        .appBottomMenu.d-none {
            display: none !important;
        }

        .appBottomMenu .item {
            width: 20%;
            text-align: center;
            text-decoration: none;
            color: #6b7280;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 8px 0;
            transition: all 0.2s;
        }

        .appBottomMenu .item ion-icon {
            font-size: 24px;
            margin-bottom: 2px;
            transition: all 0.2s;
        }

        .appBottomMenu .item strong {
            font-size: 10px;
            font-weight: 600;
            display: block;
        }

        .appBottomMenu .item:hover,
        .appBottomMenu .item.active {
            color: #0053C5;
        }

        .appBottomMenu .item.active ion-icon {
            transform: scale(1.1);
        }

        /* Center Action Button */
        .appBottomMenu .item.action-btn {
            position: relative;
            justify-content: flex-end; /* Align text to bottom */
        }

        .action-button-center {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #0053C5 0%, #003d94 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(0, 83, 197, 0.4);
            position: absolute;
            top: -26px;
            left: 50%;
            transform: translateX(-50%);
            border: 4px solid #ffffff;
            color: white;
            transition: all 0.2s;
            z-index: 10;
        }

        .action-button-center ion-icon {
            font-size: 26px !important;
            color: white !important;
            margin: 0 !important;
            display: block;
        }

        .appBottomMenu .item.action-btn strong {
            margin-top: 32px;
        }

        .appBottomMenu .item.action-btn:hover .action-button-center {
            transform: translateX(-50%) translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 83, 197, 0.5);
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- App Capsule -->
    <div id="appCapsule">
        @yield('content')
    </div>
    <!-- * App Capsule -->

    <!-- App Bottom Menu -->
    @if(!isset($hideBottomMenu))
    <div class="appBottomMenu">
        <a href="{{ route('face-presensi.dashboard') }}" class="item {{ Request::is('face-presensi/dashboard') ? 'active' : '' }}">
            <div class="col">
                <ion-icon name="home-outline"></ion-icon>
                <strong>Home</strong>
            </div>
        </a>
        <a href="{{ route('face-presensi.dashboard') }}#riwayat-section" class="item">
            <div class="col">
                <ion-icon name="time-outline"></ion-icon>
                <strong>Riwayat</strong>
            </div>
        </a>
        <a href="{{ route('face-presensi.create') }}" class="item action-btn {{ Request::is('face-presensi/create') ? 'active' : '' }}" id="bottom-nav-capture-btn">
            <div class="col">
                <div class="action-button-center">
                    <ion-icon name="scan"></ion-icon>
                </div>
                <strong>Presensi</strong>
            </div>
        </a>
        <a href="{{ route('face-presensi.enrollment') }}" class="item {{ Request::is('face-presensi/enrollment') ? 'active' : '' }}">
            <div class="col">
                <ion-icon name="person-circle-outline"></ion-icon>
                <strong>Face ID</strong>
            </div>
        </a>
        <a href="#" class="item" onclick="event.preventDefault(); document.getElementById('logout-form-bottom').submit();">
            <div class="col">
                <ion-icon name="log-out-outline"></ion-icon>
                <strong>Logout</strong>
            </div>
        </a>
    </div>
    <form id="logout-form-bottom" action="{{ route('proseslogout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    @endif
    <!-- * App Bottom Menu -->

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('myscript')
</body>

</html>