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
    <link rel="shortcut icon" href="https://siap.al-azhar.id/upload/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <style>
        /* ===== RESPONSIVE GLASSMORPHISM DOCK STYLE ===== */
        :root {
            --primary: #0053C5;
            --primary-light: #2E7CE6;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            padding-bottom: 90px;
            background: #f5f7fa;
        }

        /* Dock Container - Full Width Responsive */
        .dock-container {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 999;
            padding: 12px 16px 16px 16px;
            animation: dockFloat 0.5s ease;
        }

        /* Glass Dock - Full Width */
        .glass-dock {
            position: relative;
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(30px) saturate(180%);
            -webkit-backdrop-filter: blur(30px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 28px;
            padding: 12px 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12),
                0 16px 64px rgba(0, 0, 0, 0.05),
                inset 0 1px 1px rgba(255, 255, 255, 0.9);
            display: flex;
            justify-content: space-around;
            align-items: center;
            gap: 8px;
            width: 100%;
            max-width: 100%;
        }

        /* Glassmorphism Border Effect */
        .glass-dock::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 28px;
            padding: 1px;
            background: linear-gradient(135deg,
                    rgba(255, 255, 255, 0.9) 0%,
                    rgba(255, 255, 255, 0.3) 50%,
                    rgba(255, 255, 255, 0.9) 100%);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }

        /* Subtle gradient overlay */
        .glass-dock::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 100%;
            background: linear-gradient(to top, rgba(0, 83, 197, 0.03) 0%, transparent 100%);
            border-radius: 28px;
            pointer-events: none;
        }

        /* Dock Items */
        .dock-item {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-width: 56px;
            height: 56px;
            background: transparent;
            border-radius: 18px;
            text-decoration: none;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            flex: 1;
            max-width: 80px;
            z-index: 1;
        }

        .dock-item ion-icon {
            font-size: 26px;
            color: #64748b;
            transition: all 0.3s ease;
        }

        /* Label yang muncul saat hover/active */
        .dock-item span {
            position: absolute;
            bottom: -28px;
            font-size: 10px;
            font-weight: 600;
            color: #64748b;
            opacity: 0;
            transform: translateY(-8px);
            transition: all 0.3s ease;
            white-space: nowrap;
            background: rgba(255, 255, 255, 0.95);
            padding: 6px 12px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(10px);
            pointer-events: none;
        }

        /* Hover effect for desktop */
        @media (hover: hover) {
            .dock-item:hover {
                transform: translateY(-8px) scale(1.1);
            }

            .dock-item:hover span {
                opacity: 1;
                transform: translateY(0);
            }

            .dock-fab:hover {
                transform: translateY(-12px) scale(1.1);
                box-shadow: 0 12px 32px rgba(0, 83, 197, 0.5);
            }
        }

        /* Active state */
        .dock-item.active {
            background: linear-gradient(135deg, rgba(0, 83, 197, 0.15) 0%, rgba(46, 124, 230, 0.15) 100%);
        }

        .dock-item.active ion-icon {
            color: var(--primary);
            transform: scale(1.1);
        }

        /* Touch feedback for mobile */
        .dock-item:active:not(.dock-fab) {
            transform: scale(0.95);
        }

        /* Floating Action Button */
        .dock-fab {
            min-width: 64px;
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            border-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 24px rgba(0, 83, 197, 0.4),
                inset 0 1px 1px rgba(255, 255, 255, 0.3);
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            flex: 0 0 auto;
            position: relative;
            overflow: hidden;
        }

        .dock-fab ion-icon {
            font-size: 32px;
            color: white;
            position: relative;
            z-index: 2;
        }

        /* Ripple effect on FAB */
        .dock-fab::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 22px;
            opacity: 0;
            transform: scale(0);
            transition: all 0.5s ease;
        }

        .dock-fab:active {
            transform: scale(0.92);
            box-shadow: 0 4px 16px rgba(0, 83, 197, 0.4);
        }

        .dock-fab:active::before {
            opacity: 1;
            transform: scale(1);
        }

        /* Badge notification */
        .dock-badge {
            position: absolute;
            top: 6px;
            right: 6px;
            min-width: 18px;
            height: 18px;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            border-radius: 10px;
            font-size: 10px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 5px;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.5);
            border: 2px solid white;
            z-index: 3;
        }

        /* Animation */
        @keyframes dockFloat {
            0% {
                transform: translateY(100px);
                opacity: 0;
            }

            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* ===== RESPONSIVE BREAKPOINTS ===== */

        /* Small Mobile (320px - 374px) */
        @media (max-width: 374px) {
            .dock-container {
                padding: 10px 12px 12px 12px;
            }

            .glass-dock {
                padding: 10px 12px;
                border-radius: 24px;
                gap: 6px;
            }

            .dock-item {
                min-width: 48px;
                height: 48px;
            }

            .dock-item ion-icon {
                font-size: 22px;
            }

            .dock-fab {
                min-width: 56px;
                width: 56px;
                height: 56px;
            }

            .dock-fab ion-icon {
                font-size: 28px;
            }
        }

        /* Mobile (375px - 767px) */
        @media (min-width: 375px) and (max-width: 767px) {
            .dock-container {
                padding: 12px 16px 16px 16px;
            }

            .glass-dock {
                gap: 8px;
            }
        }

        /* Tablet (768px - 1023px) */
        @media (min-width: 768px) and (max-width: 1023px) {
            body {
                padding-bottom: 100px;
            }

            .dock-container {
                padding: 16px 32px 24px 32px;
            }

            .glass-dock {
                padding: 16px 24px;
                border-radius: 32px;
                max-width: 600px;
                margin: 0 auto;
            }

            .dock-item {
                min-width: 64px;
                height: 64px;
            }

            .dock-item ion-icon {
                font-size: 28px;
            }

            .dock-fab {
                width: 72px;
                height: 72px;
            }

            .dock-fab ion-icon {
                font-size: 36px;
            }
        }

        /* Desktop (1024px+) */
        @media (min-width: 1024px) {
            body {
                padding-bottom: 110px;
            }

            .dock-container {
                padding: 20px 48px 32px 48px;
            }

            .glass-dock {
                padding: 18px 32px;
                border-radius: 36px;
                max-width: 700px;
                margin: 0 auto;
                gap: 12px;
            }

            .dock-item {
                min-width: 72px;
                height: 72px;
            }

            .dock-item ion-icon {
                font-size: 30px;
            }

            .dock-fab {
                width: 80px;
                height: 80px;
                border-radius: 24px;
            }

            .dock-fab ion-icon {
                font-size: 40px;
            }

            /* Show labels on desktop by default */
            .dock-item span {
                position: relative;
                bottom: auto;
                opacity: 1;
                transform: translateY(0);
                margin-top: 6px;
                background: transparent;
                box-shadow: none;
                font-size: 11px;
                padding: 0;
            }
        }

        /* Large Desktop (1440px+) */
        @media (min-width: 1440px) {
            .glass-dock {
                max-width: 800px;
            }
        }

        /* Support for notched devices (iPhone X, etc) */
        @supports (padding: max(0px)) {
            body {
                padding-bottom: max(90px, env(safe-area-inset-bottom));
            }

            .dock-container {
                padding-bottom: max(16px, env(safe-area-inset-bottom));
            }
        }
    </style>

    @stack('styles')
</head>

@php
$nik = Auth::guard('karyawan')->user()->nik;
$hasFaceData = DB::table('face_data')
->where('nik', $nik)
->where('status', 'active')
->exists();
@endphp

@if(!$hasFaceData)
<!-- Face Enrollment Banner -->
<div class="alert-banner" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); padding: 16px 20px; margin: 0 0 16px 0; border-radius: 16px; box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);">
    <div style="display: flex; align-items: center; gap: 12px;">
        <div style="width: 40px; height: 40px; background: rgba(255, 255, 255, 0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <ion-icon name="scan-outline" style="font-size: 24px; color: white;"></ion-icon>
        </div>
        <div style="flex: 1;">
            <h6 style="margin: 0 0 4px 0; font-size: 14px; font-weight: 700; color: white;">
                Face Recognition Belum Terdaftar
            </h6>
            <p style="margin: 0; font-size: 12px; color: rgba(255, 255, 255, 0.9);">
                Daftarkan wajah Anda untuk keamanan presensi yang lebih baik
            </p>
        </div>
        <a href="{{ route('face.enrollment') }}" style="padding: 8px 16px; background: white; color: #8b5cf6; border-radius: 10px; font-size: 13px; font-weight: 700; text-decoration: none; white-space: nowrap;">
            Daftar Sekarang
        </a>
    </div>
</div>
@endif

<body>
    <!-- App Capsule -->
    <div id="appCapsule">
        @yield('content')
    </div>
    <!-- * App Capsule -->
    @php
    $nik = Auth::guard('karyawan')->user()->nik;
    $faceData = DB::table('face_data')
    ->where('nik', $nik)
    ->where('status', 'active')
    ->first();
    @endphp

    <!-- Face Recognition Status Card -->
    <div class="card" style="margin-bottom: 16px; overflow: hidden; border: none; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);">
        <div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); padding: 16px 20px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 50px; height: 50px; background: rgba(255, 255, 255, 0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <ion-icon name="scan-outline" style="font-size: 28px; color: white;"></ion-icon>
                </div>
                <div style="flex: 1;">
                    <h6 style="margin: 0 0 4px 0; font-size: 16px; font-weight: 700; color: white;">
                        Face Recognition
                    </h6>
                    <p style="margin: 0; font-size: 13px; color: rgba(255, 255, 255, 0.9);">
                        Verifikasi Biometrik Presensi
                    </p>
                </div>
            </div>
        </div>

        <div class="card-body" style="padding: 16px 20px;">
            @if($faceData)
            <!-- Already Enrolled -->
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                <div style="width: 40px; height: 40px; background: rgba(16, 185, 129, 0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <ion-icon name="checkmark-circle" style="font-size: 24px; color: #10b981;"></ion-icon>
                </div>
                <div>
                    <p style="margin: 0; font-size: 14px; font-weight: 700; color: #10b981;">
                        Wajah Terdaftar
                    </p>
                    <p style="margin: 0; font-size: 12px; color: #64748b;">
                        Terakhir diperbarui: {{ \Carbon\Carbon::parse($faceData->last_updated)->diffForHumans() }}
                    </p>
                </div>
            </div>

            <a href="{{ route('face.enrollment') }}" style="display: block; width: 100%; padding: 12px; background: linear-gradient(135deg, rgba(139, 92, 246, 0.1) 0%, rgba(124, 58, 237, 0.1) 100%); border: 1px solid #8b5cf6; border-radius: 12px; text-align: center; color: #8b5cf6; font-weight: 700; font-size: 14px; text-decoration: none;">
                <ion-icon name="settings-outline" style="vertical-align: middle; margin-right: 6px;"></ion-icon>
                Kelola Data Wajah
            </a>
            @else
            <!-- Not Enrolled Yet -->
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                <div style="width: 40px; height: 40px; background: rgba(239, 68, 68, 0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <ion-icon name="alert-circle" style="font-size: 24px; color: #ef4444;"></ion-icon>
                </div>
                <div>
                    <p style="margin: 0; font-size: 14px; font-weight: 700; color: #ef4444;">
                        Belum Terdaftar
                    </p>
                    <p style="margin: 0; font-size: 12px; color: #64748b;">
                        Daftarkan wajah untuk keamanan lebih
                    </p>
                </div>
            </div>

            <a href="{{ route('face.enrollment') }}" style="display: block; width: 100%; padding: 12px; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-radius: 12px; text-align: center; color: white; font-weight: 700; font-size: 14px; text-decoration: none; box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);">
                <ion-icon name="scan-outline" style="vertical-align: middle; margin-right: 6px;"></ion-icon>
                Daftar Wajah Sekarang
            </a>
            @endif
        </div>
    </div>

    <!-- Responsive Glassmorphism Dock Navigation -->
    <div class="dock-container">
        <nav class="glass-dock">
            <a href="{{ route('dashboard') }}" class="dock-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <ion-icon name="{{ request()->routeIs('dashboard') ? 'home' : 'home-outline' }}"></ion-icon>
                <span>Home</span>
            </a>

            <a href="{{ route('histori.index') }}" class="dock-item {{ request()->routeIs('histori.*') ? 'active' : '' }}">
                <ion-icon name="{{ request()->routeIs('histori.*') ? 'time' : 'time-outline' }}"></ion-icon>
                <span>Histori</span>
            </a>

            <a href="{{ route('presensi.create') }}" class="dock-fab">
                <ion-icon name="camera"></ion-icon>
            </a>

            <a href="{{ route('izin.index') }}" class="dock-item {{ request()->routeIs('izin.*') ? 'active' : '' }}">
                @if(isset($pendingIzin) && $pendingIzin > 0)
                <span class="dock-badge">{{ $pendingIzin }}</span>
                @endif
                <ion-icon name="{{ request()->routeIs('izin.*') ? 'calendar' : 'calendar-outline' }}"></ion-icon>
                <span>Izin</span>
            </a>

            <a href="{{ route('profile.edit') }}" class="dock-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <ion-icon name="{{ request()->routeIs('profile.*') ? 'person' : 'person-outline' }}"></ion-icon>
                <span>Profile</span>
            </a>
        </nav>
    </div>
    <!-- jQuery (harus sebelum plugins lain) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="{{ asset('assets/js/lib/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/bootstrap.min.js') }}"></script>
    <!-- App JS -->
    <script src="{{ asset('assets/js/plugins.js') }}"></script>
    <script src="{{ asset('assets/js/base.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Scrip bottom Nav -->
    <script>
        // Haptic feedback untuk mobile
        document.addEventListener('DOMContentLoaded', function() {
            const navItems = document.querySelectorAll('.dock-item, .dock-fab');

            navItems.forEach(item => {
                item.addEventListener('touchstart', function() {
                    // Haptic feedback jika browser support
                    if ('vibrate' in navigator) {
                        navigator.vibrate(10);
                    }
                });
            });

            // Smooth active indicator
            const activeItem = document.querySelector('.dock-item.active');
            if (activeItem) {
                activeItem.style.transition = 'all 0.3s ease';
            }
        });

        // Prevent default touch behavior untuk FAB
        document.querySelector('.dock-fab')?.addEventListener('touchstart', function(e) {
            e.currentTarget.style.transform = 'scale(0.92)';
        });

        document.querySelector('.dock-fab')?.addEventListener('touchend', function(e) {
            e.currentTarget.style.transform = '';
        });
    </script>
    @stack('myscript')
    <!-- REMOVE atau COMMENT service worker registration -->
    {{-- <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/service-worker.js')
                .then(reg => console.log('Service Worker registered'))
                .catch(err => console.log('Service Worker registration failed'));
        }
    </script> --}}
</body>

</html>