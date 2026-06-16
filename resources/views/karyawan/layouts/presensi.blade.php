<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#0053C5">
    <title>YPI Al Azhar - E-Presensi</title>
    <meta name="description" content="Sistem Presensi YPI Al Azhar">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="manifest" href="/manifest.json">
    <link rel="shortcut icon" href="/assets/img/logoypia.png" type="image/png" />
    <link rel="apple-touch-icon" href="/assets/img/pwa-icon.png">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #F8FAFC;
            padding-bottom: 80px;
        }

        @supports (padding: max(0px)) {
            body {
                padding-bottom: max(80px, calc(env(safe-area-inset-bottom) + 80px));
            }
        }

        /* ══════════════════════════════════════════
           BOTTOM NAVIGATION — MOBILE FIRST REDESIGN
           ══════════════════════════════════════════ */

        /* ── Container ── */
        .dock-container {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 999;
            animation: navRise 0.4s cubic-bezier(0.34, 1.2, 0.64, 1) both;
        }

        @supports (padding: max(0px)) {
            .dock-container {
                padding-bottom: env(safe-area-inset-bottom, 0px);
            }
        }

        @keyframes navRise {
            from { transform: translateY(100%); opacity: 0; }
            to   { transform: translateY(0);    opacity: 1; }
        }

        /* ── Bar ── */
        .glass-dock {
            display: flex;
            align-items: center;
            justify-content: space-around;
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(24px) saturate(180%);
            -webkit-backdrop-filter: blur(24px) saturate(180%);
            border-top: 1px solid rgba(0, 0, 0, 0.07);
            padding: 8px 12px 12px;
            gap: 4px;
            box-shadow: 0 -4px 24px rgba(0, 0, 0, 0.06);
        }

        /* ── Regular Nav Item ── */
        .dock-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            flex: 1;
            min-width: 0;
            padding: 4px 4px 2px;
            border-radius: 12px;
            text-decoration: none;
            cursor: pointer;
            position: relative;
            transition: transform 0.15s ease;
            -webkit-tap-highlight-color: transparent;
        }

        .dock-item:active {
            transform: scale(0.91);
        }

        /* Icon pill container */
        .dock-item .nav-icon-wrap {
            width: 40px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: background 0.22s ease;
        }

        .dock-item ion-icon {
            font-size: 22px;
            color: #94A3B8;
            transition: color 0.22s ease, transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            display: block;
        }

        .dock-item span {
            font-size: 10px;
            font-weight: 600;
            color: #94A3B8;
            letter-spacing: 0.1px;
            transition: color 0.22s ease;
            white-space: nowrap;
            display: block;
            line-height: 1;
        }

        /* ── ACTIVE STATE ── */
        .dock-item.active .nav-icon-wrap {
            background: #EFF6FF;
        }

        .dock-item.active ion-icon {
            color: #2563EB;
            transform: scale(1.1) translateY(-1px);
        }

        .dock-item.active span {
            color: #2563EB;
        }

        /* Active dot indicator */
        .dock-item.active::after {
            content: '';
            position: absolute;
            bottom: 0px;
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: #2563EB;
        }

        /* ── Badge ── */
        .dock-badge {
            position: absolute;
            top: 2px;
            right: calc(50% - 24px);
            min-width: 16px;
            height: 16px;
            background: #EF4444;
            color: #fff;
            border-radius: 999px;
            font-size: 9px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
            border: 1.5px solid #fff;
            box-shadow: 0 1px 4px rgba(239,68,68,0.4);
            z-index: 2;
            font-family: 'Inter', sans-serif;
        }

        /* ── FAB (Camera / Presensi) ── */
        .dock-fab {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            width: 56px;
            height: 56px;
            border-radius: 18px;
            background: linear-gradient(145deg, #2563EB 0%, #1D4ED8 100%);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.40),
                        0 2px 6px  rgba(37, 99, 235, 0.20),
                        inset 0 1px 1px rgba(255, 255, 255, 0.25);
            text-decoration: none;
            cursor: pointer;
            position: relative;
            overflow: visible;
            transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1),
                        box-shadow 0.2s ease;
            -webkit-tap-highlight-color: transparent;
            margin-top: -16px;
        }

        .dock-fab ion-icon {
            font-size: 26px;
            color: #fff;
            display: block;
            position: relative;
            z-index: 2;
            transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        /* Shine overlay */
        .dock-fab-inner {
            position: absolute;
            inset: 0;
            border-radius: 18px;
            background: linear-gradient(135deg,
                rgba(255,255,255,0.22) 0%,
                rgba(255,255,255,0)   55%);
            overflow: hidden;
        }

        /* Pulse ring */
        .dock-fab-ring {
            position: absolute;
            inset: -5px;
            border-radius: 23px;
            border: 2px solid rgba(37, 99, 235, 0.28);
            animation: fabPulse 2.6s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes fabPulse {
            0%, 100% { opacity: 0.7; transform: scale(1); }
            50%       { opacity: 0;   transform: scale(1.22); }
        }

        .dock-fab:active {
            transform: scale(0.89);
            box-shadow: 0 3px 10px rgba(37, 99, 235, 0.30);
        }

        .dock-fab:active ion-icon {
            transform: scale(0.9);
        }

        /* ── Small screens (≤360px) ── */
        @media (max-width: 360px) {
            .glass-dock { padding: 8px 8px 10px; gap: 2px; }
            .dock-item .nav-icon-wrap { width: 34px; height: 26px; }
            .dock-item ion-icon { font-size: 20px; }
            .dock-item span { font-size: 9px; }
            .dock-fab { width: 50px; height: 50px; border-radius: 16px; margin-top: -14px; }
            .dock-fab ion-icon { font-size: 23px; }
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


    <!-- Bottom Navigation -->
    <div class="dock-container">
        <nav class="glass-dock">

            {{-- Home --}}
            <a href="{{ route('dashboard') }}"
               class="dock-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <div class="nav-icon-wrap">
                    <ion-icon name="{{ request()->routeIs('dashboard') ? 'home' : 'home-outline' }}"></ion-icon>
                </div>
                <span>Home</span>
            </a>

            {{-- FaceID --}}
            <a href="{{ route('face.enrollment') }}"
               class="dock-item {{ request()->routeIs('face.enrollment') ? 'active' : '' }}">
                <div class="nav-icon-wrap">
                    <ion-icon name="{{ request()->routeIs('face.enrollment') ? 'scan' : 'scan-outline' }}"></ion-icon>
                </div>
                <span>FaceID</span>
            </a>

            {{-- FAB — Presensi --}}
            <a href="{{ route('presensi.create') }}" class="dock-fab" aria-label="Presensi">
                <div class="dock-fab-ring"></div>
                <ion-icon name="camera"></ion-icon>
            </a>

            {{-- Izin --}}
            <a href="{{ route('izin.index') }}"
               class="dock-item {{ request()->routeIs('izin.*') ? 'active' : '' }}">
                @if(isset($pendingIzin) && $pendingIzin > 0)
                    <span class="dock-badge">{{ $pendingIzin }}</span>
                @endif
                <div class="nav-icon-wrap">
                    <ion-icon name="{{ request()->routeIs('izin.*') ? 'calendar' : 'calendar-outline' }}"></ion-icon>
                </div>
                <span>Izin</span>
            </a>

            {{-- Profile --}}
            <a href="{{ route('profile.edit') }}"
               class="dock-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <div class="nav-icon-wrap">
                    <ion-icon name="{{ request()->routeIs('profile.*') ? 'person' : 'person-outline' }}"></ion-icon>
                </div>
                <span>Profile</span>
            </a>

        </nav>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="{{ asset('assets/js/plugins.js') }}"></script>
    <script src="{{ asset('assets/js/base.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.dock-item, .dock-fab').forEach(function (el) {
                el.addEventListener('touchstart', function () {
                    if ('vibrate' in navigator) navigator.vibrate(8);
                }, { passive: true });
            });
        });
    </script>
    @stack('myscript')
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/service-worker.js')
                    .then(reg => console.log('Service Worker registered'))
                    .catch(err => console.log('Service Worker registration failed: ', err));
            });
        }
    </script>
</body>

</html>