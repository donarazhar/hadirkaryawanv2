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
    <link rel="apple-touch-icon" href="/assets/img/icon-192.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <style>

        * { margin: 0; padding: 0; box-sizing: border-box; }

        html {
            background-color: #E2E8F0; /* Darker background for desktop */
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #F8FAFC;
            padding-bottom: 80px;
            
            /* Center mobile view on desktop */
            margin: 0 auto;
            max-width: 480px;
            min-height: 100vh;
            box-shadow: 0 0 24px rgba(0,0,0,0.05);
            position: relative;
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
            margin: 0 auto;
            max-width: 480px;
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
        /* ── MENU BOTTOM SHEET ── */
        .menu-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(4px);
            z-index: 9999;
            align-items: flex-end;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .menu-overlay.show {
            display: flex;
            opacity: 1;
        }

        .menu-sheet {
            background: #ffffff;
            width: 100%;
            max-width: 480px;
            border-radius: 28px 28px 0 0;
            padding: 24px 24px 34px;
            transform: translateY(100%);
            transition: transform 0.35s cubic-bezier(0.34, 1.2, 0.64, 1);
            box-shadow: 0 -10px 40px rgba(0,0,0,0.1);
        }

        .menu-overlay.show .menu-sheet {
            transform: translateY(0);
        }

        .menu-drag-handle {
            width: 44px;
            height: 5px;
            background: #E2E8F0;
            border-radius: 3px;
            margin: 0 auto 24px;
        }

        .menu-header {
            font-size: 20px;
            font-weight: 800;
            color: #0F172A;
            margin-bottom: 20px;
            letter-spacing: -0.4px;
        }

        .menu-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px;
            background: #F8FAFC;
            border: 1px solid #F1F5F9;
            border-radius: 18px;
            text-decoration: none;
            color: #0F172A;
            transition: all 0.2s;
            -webkit-tap-highlight-color: transparent;
        }

        .menu-item:active {
            transform: scale(0.96);
            background: #F1F5F9;
        }

        .menu-icon-box {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .menu-icon-box ion-icon {
            font-size: 24px;
        }

        .menu-item-text {
            flex: 1;
        }

        .menu-item-title {
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 3px;
            color: #1E293B;
            letter-spacing: -0.2px;
        }

        .menu-item-desc {
            font-size: 12px;
            color: #64748B;
            line-height: 1.4;
        }
        
        .menu-section-title {
            font-size: 11px;
            font-weight: 700;
            color: #94A3B8;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin: 16px 0 4px 4px;
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

            {{-- Kalender --}}
            <a href="{{ route('karyawan.kalender') }}"
               class="dock-item {{ request()->routeIs('karyawan.kalender') ? 'active' : '' }}">
                <div class="nav-icon-wrap">
                    <ion-icon name="{{ request()->routeIs('karyawan.kalender') ? 'calendar-number' : 'calendar-number-outline' }}"></ion-icon>
                </div>
                <span>Kalender</span>
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

            {{-- Menu (sebelumnya Profile) --}}
            <a href="#" onclick="openMainMenu(event)"
               class="dock-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <div class="nav-icon-wrap">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>
                <span>Menu</span>
            </a>

        </nav>
    </div>

    <!-- ── MENU BOTTOM SHEET ── -->
    <div class="menu-overlay" id="mainMenuSheet">
        <div class="menu-sheet">
            <div class="menu-drag-handle"></div>
            <div class="menu-header">Menu Utama</div>
            
            <div class="menu-list">
                <!-- Menu Default Karyawan -->
                <a href="{{ route('profile.edit') }}" class="menu-item">
                    <div class="menu-icon-box" style="background: #F3F4F6; color: #4B5563;">
                        <ion-icon name="person"></ion-icon>
                    </div>
                    <div class="menu-item-text">
                        <div class="menu-item-title">Profile Saya</div>
                        <div class="menu-item-desc">Lihat dan ubah data profile Anda</div>
                    </div>
                    <ion-icon name="chevron-forward-outline" style="color: #9CA3AF;"></ion-icon>
                </a>
                
                @php
                    $userEmail = Auth::guard('karyawan')->user()->email ?? '';
                    
                    // Cek admin di PresensiGPS
                    $isAdminPresensi = \App\Models\User::where('email', $userEmail)->exists() || $userEmail === 'donarazhar@gmail.com';
                    
                    // Cek akses di Persuratan (Database terpisah tapi 1 server)
                    // Dibungkus try-catch agar tidak crash 500 di server shared hosting yang beda user DB
                    $isUserPersuratan = false;
                    try {
                        $isUserPersuratan = \Illuminate\Support\Facades\DB::table('persuratan.users')->where('email', $userEmail)->exists();
                    } catch (\Exception $e) {
                        // Abaikan jika tidak ada akses cross-database
                    }
                    if ($userEmail === 'donarazhar@gmail.com') {
                        $isUserPersuratan = true;
                    }
                @endphp
                
                <div class="menu-section-title">Aplikasi Terhubung</div>
                
                @if($isAdminPresensi)
                <a href="{{ route('karyawan.switch-to-admin') }}" class="menu-item">
                    <div class="menu-icon-box" style="background: #FEF2F2; color: #DC2626;">
                        <ion-icon name="settings"></ion-icon>
                    </div>
                    <div class="menu-item-text">
                        <div class="menu-item-title">Dashboard PresensiGPS</div>
                        <div class="menu-item-desc">Kelola master data & konfigurasi</div>
                    </div>
                    <ion-icon name="open-outline" style="color: #9CA3AF;"></ion-icon>
                </a>
                @endif
                
                @if($isUserPersuratan)
                @php
                    $persuratanUrl = rtrim(env('PERSURATAN_URL', 'http://localhost:8001'), '/');
                @endphp
                <a href="{{ $persuratanUrl }}/auth/presensi" target="_blank" class="menu-item">
                    <div class="menu-icon-box" style="background: #ECFEFF; color: #0891B2;">
                        <ion-icon name="mail"></ion-icon>
                    </div>
                    <div class="menu-item-text">
                        <div class="menu-item-title">Al Azhar Paperless System</div>
                        <div class="menu-item-desc">Manajemen surat menyurat & disposisi</div>
                    </div>
                    <ion-icon name="open-outline" style="color: #9CA3AF;"></ion-icon>
                </a>
                @endif

                @php
                    $todoUrl = rtrim(env('TODO_URL', 'http://localhost:8002'), '/');
                @endphp
                <a href="{{ $todoUrl }}/auth/presensi" target="_blank" class="menu-item">
                    <div class="menu-icon-box" style="background: #F0FDF4; color: #16A34A;">
                        <ion-icon name="checkmark-done-circle"></ion-icon>
                    </div>
                    <div class="menu-item-text">
                        <div class="menu-item-title">Al Azhar Task & Schedule System</div>
                        <div class="menu-item-desc">Manajemen tugas & jadwal kerja</div>
                    </div>
                    <ion-icon name="open-outline" style="color: #9CA3AF;"></ion-icon>
                </a>
            </div>
        </div>
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

        // --- BOTTOM MENU LOGIC ---
        function openMainMenu(e) {
            e.preventDefault();
            const menu = document.getElementById('mainMenuSheet');
            menu.style.display = 'flex';
            menu.offsetHeight; // force reflow
            menu.classList.add('show');
        }

        function closeMainMenu() {
            const menu = document.getElementById('mainMenuSheet');
            menu.classList.remove('show');
            setTimeout(() => {
                menu.style.display = 'none';
            }, 300);
        }

        document.addEventListener('click', function(e) {
            const menu = document.getElementById('mainMenuSheet');
            if (e.target === menu) {
                closeMainMenu();
            }
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

        // --- OFFLINE SYNC LOGIC (IndexedDB) ---
        const DB_NAME = 'presensigps_db';
        const DB_VERSION = 1;
        const STORE_NAME = 'offline_presensi';

        function openIndexedDB() {
            return new Promise((resolve, reject) => {
                let request = indexedDB.open(DB_NAME, DB_VERSION);
                request.onupgradeneeded = function(event) {
                    let db = event.target.result;
                    if (!db.objectStoreNames.contains(STORE_NAME)) {
                        db.createObjectStore(STORE_NAME, { keyPath: 'id', autoIncrement: true });
                    }
                };
                request.onsuccess = function(event) {
                    resolve(event.target.result);
                };
                request.onerror = function(event) {
                    reject('Error opening IndexedDB');
                };
            });
        }

        async function saveOfflinePresensi(data) {
            try {
                let db = await openIndexedDB();
                let transaction = db.transaction([STORE_NAME], 'readwrite');
                let store = transaction.objectStore(STORE_NAME);
                store.add(data);
                return new Promise((resolve) => {
                    transaction.oncomplete = () => resolve(true);
                });
            } catch (err) {
                console.error(err);
                return false;
            }
        }

        async function syncOfflinePresensi() {
            if (!navigator.onLine) return; // double check

            try {
                let db = await openIndexedDB();
                let transaction = db.transaction([STORE_NAME], 'readonly');
                let store = transaction.objectStore(STORE_NAME);
                let request = store.getAll();

                request.onsuccess = async function() {
                    let records = request.result;
                    if (records.length > 0) {
                        console.log(`Menemukan ${records.length} data presensi offline. Memulai sinkronisasi...`);
                        
                        // Show simple toast if needed, but background sync is better silent.
                        // We will sync one by one
                        for (let record of records) {
                            try {
                                await $.ajax({
                                    type: 'POST',
                                    url: '/presensi/store',
                                    data: record.payload,
                                    cache: false
                                });
                                // Jika sukses dikirim (bisa jadi respon error logika absen, tapi request sukses)
                                // Hapus dari IndexedDB
                                let deleteTx = db.transaction([STORE_NAME], 'readwrite');
                                let deleteStore = deleteTx.objectStore(STORE_NAME);
                                deleteStore.delete(record.id);
                            } catch (e) {
                                console.error('Gagal sinkronisasi data:', record, e);
                            }
                        }
                        
                        if (records.length > 0) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Sinkronisasi Selesai',
                                text: 'Data presensi offline berhasil diunggah.',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            });
                        }
                    }
                };
            } catch (err) {
                console.error('Error saat sinkronisasi:', err);
            }
        }

        // Listen for online event
        window.addEventListener('online', syncOfflinePresensi);
        // Also run on load if already online
        if (navigator.onLine) {
            window.addEventListener('load', syncOfflinePresensi);
        }
    </script>
</body>

</html>