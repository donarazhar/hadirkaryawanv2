<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Administrator Panel — Al Azhar Presensi System. Kelola data presensi dan konfigurasi sistem.">
    <title>Admin Panel — Al Azhar Presensi System</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logoypia.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <style>
        :root {
            --slate-900: #0F172A;
            --slate-800: #1E293B;
            --slate-700: #334155;
            --slate-600: #475569;
            --slate-400: #94A3B8;
            --slate-200: #E2E8F0;
            --slate-100: #F1F5F9;
            --slate-50:  #F8FAFC;
            --white:     #FFFFFF;
            --blue:      #2563EB;
            --blue-dark: #1D4ED8;
            --blue-soft: #EFF6FF;
            --blue-mid:  #BFDBFE;
            --danger:    #EF4444;
        }

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        html { height: 100%; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            -webkit-font-smoothing: antialiased;
            height: 100%;
            min-height: 100vh;
            background: var(--slate-50);
            color: var(--slate-900);
        }

        /* ════════════════════════════════════════
           SPLIT LAYOUT — Left panel | Right form
           ════════════════════════════════════════ */
        .layout {
            display: flex;
            min-height: 100vh;
        }

        /* ── LEFT BRAND PANEL ── */
        .brand-panel {
            display: none; /* hidden on mobile */
            flex: 0 0 420px;
            background: var(--slate-900);
            padding: 48px 44px;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        /* Decorative blobs */
        .brand-panel::before,
        .brand-panel::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
        }

        .brand-panel::before {
            width: 380px; height: 380px;
            top: -120px; left: -120px;
            background: radial-gradient(circle, rgba(37,99,235,0.20) 0%, transparent 70%);
        }

        .brand-panel::after {
            width: 300px; height: 300px;
            bottom: -80px; right: -80px;
            background: radial-gradient(circle, rgba(96,165,250,0.15) 0%, transparent 70%);
        }

        /* Show brand panel on large screens */
        @media (min-width: 960px) {
            .brand-panel { display: flex; }
        }

        @media (min-width: 960px) and (max-width: 1199px) {
            .brand-panel { flex: 0 0 360px; }
        }

        /* Brand top section */
        .brand-top { position: relative; z-index: 2; }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 48px;
        }

        .brand-logo-img {
            width: 42px; height: 42px;
            border-radius: 10px;
            object-fit: contain;
            background: rgba(255,255,255,0.1);
            padding: 4px;
        }

        .brand-logo-name {
            font-size: 18px;
            font-weight: 800;
            color: var(--white);
            letter-spacing: -0.3px;
        }

        .brand-logo-name span {
            color: #60A5FA;
        }

        .brand-headline {
            font-size: 30px;
            font-weight: 900;
            color: var(--white);
            line-height: 1.2;
            letter-spacing: -0.6px;
            margin-bottom: 16px;
        }

        .brand-headline em {
            font-style: normal;
            color: #60A5FA;
        }

        .brand-desc {
            font-size: 14px;
            color: var(--slate-400);
            line-height: 1.7;
        }

        /* Feature list */
        .brand-features {
            margin-top: 40px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .feat-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .feat-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: #60A5FA;
            flex-shrink: 0;
        }

        .feat-text {
            font-size: 13px;
            color: var(--slate-400);
            font-weight: 500;
        }

        /* Bottom section */
        .brand-bottom {
            position: relative;
            z-index: 2;
        }

        .brand-version {
            font-size: 12px;
            color: var(--slate-600);
        }

        /* ── RIGHT FORM PANEL ── */
        .form-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 32px 24px;
            background: var(--white);
            min-height: 100vh;
        }

        .form-inner {
            width: 100%;
            max-width: 400px;
        }

        /* Mobile-only logo (shown when brand panel is hidden) */
        .mobile-logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-bottom: 32px;
        }

        @media (min-width: 960px) {
            .mobile-logo { display: none; }
        }

        .mobile-logo-wrap {
            width: 64px; height: 64px;
            border-radius: 16px;
            background: var(--slate-900);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 14px;
            overflow: hidden;
        }

        .mobile-logo-wrap img {
            width: 44px; height: 44px;
            object-fit: contain;
        }

        .mobile-logo-title {
            font-size: 22px;
            font-weight: 800;
            color: var(--slate-900);
            letter-spacing: -0.4px;
            margin-bottom: 4px;
        }

        .mobile-logo-title span { color: var(--blue); }

        .mobile-logo-sub {
            font-size: 13px;
            color: var(--slate-400);
        }

        /* Role badge */
        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            background: var(--slate-900);
            border-radius: 50px;
            font-size: 11px;
            font-weight: 700;
            color: #94A3B8;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-top: 10px;
        }

        .role-badge::before {
            content: '';
            width: 6px; height: 6px;
            background: #60A5FA;
            border-radius: 50%;
        }

        /* Form header */
        .form-header {
            margin-bottom: 28px;
        }

        .form-eyebrow {
            font-size: 12px;
            font-weight: 700;
            color: var(--blue);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 6px;
        }

        .form-headline {
            font-size: 26px;
            font-weight: 800;
            color: var(--slate-900);
            letter-spacing: -0.5px;
            line-height: 1.15;
        }

        .form-sub {
            font-size: 13px;
            color: var(--slate-400);
            margin-top: 6px;
            line-height: 1.6;
        }

        /* ── ALERTS ── */
        .alert {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
            animation: alertIn 0.2s ease;
        }

        @keyframes alertIn {
            from { opacity: 0; transform: translateY(-4px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .alert-error   { background: #FEF2F2; border: 1px solid #FECACA; color: #DC2626; }
        .alert-success { background: #ECFDF5; border: 1px solid #A7F3D0; color: #065F46; }

        .alert svg { flex-shrink: 0; margin-top: 1px; width: 16px; height: 16px; min-width: 16px; }

        /* ── GOOGLE BTN ── */
        .btn-google {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 13px 20px;
            background: var(--white);
            border: 1.5px solid var(--slate-200);
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            color: var(--slate-900);
            text-decoration: none;
            transition: background 0.18s, border-color 0.18s, box-shadow 0.18s;
            -webkit-tap-highlight-color: transparent;
        }

        .btn-google:hover  { background: var(--slate-50); border-color: var(--slate-400); box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
        .btn-google:active { background: var(--slate-100); }

        /* ── DIVIDER ── */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 20px 0;
            font-size: 12px;
            font-weight: 500;
            color: var(--slate-400);
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--slate-200);
        }

        /* ── INPUT FIELDS ── */
        .form-group {
            position: relative;
            margin-bottom: 14px;
        }

        .input-icon {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--slate-400);
            pointer-events: none;
            display: flex;
        }

        .input-icon svg { width: 16px; height: 16px; }

        .form-control {
            width: 100%;
            padding: 13px 42px 13px 40px;
            border: 1.5px solid var(--slate-200);
            border-radius: 10px;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 500;
            color: var(--slate-900);
            background: var(--white);
            outline: none;
            transition: border-color 0.18s, box-shadow 0.18s;
            -webkit-appearance: none;
        }

        .form-control::placeholder { color: var(--slate-400); }

        .form-control:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(37,99,235,0.12);
            background: var(--white);
        }

        .eye-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--slate-400);
            display: flex;
            padding: 4px;
            transition: color 0.15s;
            -webkit-tap-highlight-color: transparent;
        }

        .eye-btn:hover { color: var(--slate-700); }
        .eye-btn svg   { width: 17px; height: 17px; }

        /* ── SUBMIT BUTTON ── */
        .btn-submit {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-family: 'Inter', sans-serif;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.18s, box-shadow 0.18s, transform 0.12s;
            margin-top: 6px;
            -webkit-tap-highlight-color: transparent;
            background: var(--slate-200);
            color: var(--slate-400);
        }

        .btn-submit.is-active {
            background: var(--slate-900);
            color: var(--white);
            box-shadow: 0 4px 16px rgba(15,23,42,0.20);
        }

        .btn-submit.is-active:hover  { background: var(--slate-800); }
        .btn-submit.is-active:active { transform: scale(0.98); box-shadow: 0 2px 8px rgba(15,23,42,0.16); }

        /* ── FOOTER ── */
        .form-footer {
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid var(--slate-100);
            text-align: center;
            font-size: 12px;
            color: var(--slate-400);
            line-height: 1.6;
        }

        /* Tablet centered card */
        @media (min-width: 540px) and (max-width: 959px) {
            body { background: var(--slate-100); }

            .form-panel {
                padding: 40px 24px;
                background: transparent;
            }

            .form-inner {
                background: var(--white);
                border-radius: 20px;
                box-shadow:
                    0 0 0 1px rgba(0,0,0,0.05),
                    0 8px 24px rgba(0,0,0,0.07);
                padding: 40px 36px;
            }
        }
    </style>
</head>
<body x-data="{
    email: '{{ old('email') }}',
    password: '',
    showPassword: false,
    get isReady() { return this.email.length > 0 && this.password.length > 0; }
}">

<div class="layout">

    {{-- ── LEFT BRAND PANEL (Desktop only) ── --}}
    <div class="brand-panel">
        <div class="brand-top">
            <div class="brand-logo">
                <img src="{{ asset('assets/img/logoypia.png') }}" class="brand-logo-img" alt="Logo">
                <div class="brand-logo-name">Al Azhar Presensi <span>System</span></div>
            </div>

            <div class="brand-headline">
                Sistem Manajemen<br>
                <em>Presensi Digital</em>
            </div>

            <p class="brand-desc">
                Platform terpusat untuk mengelola presensi karyawan berbasis GPS dan Face Recognition secara real-time.
            </p>

            <div class="brand-features">
                <div class="feat-item"><div class="feat-dot"></div><span class="feat-text">Monitoring presensi real-time</span></div>
                <div class="feat-item"><div class="feat-dot"></div><span class="feat-text">Laporan & rekap otomatis</span></div>
                <div class="feat-item"><div class="feat-dot"></div><span class="feat-text">Manajemen izin & cuti digital</span></div>
                <div class="feat-item"><div class="feat-dot"></div><span class="feat-text">Verifikasi wajah & GPS multi-cabang</span></div>
            </div>
        </div>

        <div class="brand-bottom">
            <div class="brand-version">&copy; {{ date('Y') }} YPI Al Azhar — Al Azhar Presensi System</div>
        </div>
    </div>

    {{-- ── RIGHT FORM PANEL ── --}}
    <div class="form-panel">
        <div class="form-inner">

            {{-- Mobile-only logo --}}
            <div class="mobile-logo">
                <div class="mobile-logo-wrap">
                    <img src="{{ asset('assets/img/logoypia.png') }}" alt="Logo">
                </div>
                <div class="mobile-logo-title">Admin <span>Panel</span></div>
                <div class="mobile-logo-sub">Al Azhar Presensi System</div>
                <span class="role-badge">Administrator</span>
            </div>

            {{-- Form heading (desktop) --}}
            <div class="form-header" style="display:none;" id="desktop-header">
                <div class="form-eyebrow">Administrator</div>
                <div class="form-headline">Selamat Datang<br>Kembali 👋</div>
                <div class="form-sub">Masuk ke sistem untuk mengelola data presensi dan konfigurasi.</div>
            </div>

            {{-- Alerts --}}
            @if(session('error'))
            <div class="alert alert-error">
                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                <span>{{ session('error') }}</span>
            </div>
            @endif

            @if(session('success'))
            <div class="alert alert-success">
                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-error">
                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor" style="flex-shrink:0;margin-top:2px;"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                <div>
                    @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
                </div>
            </div>
            @endif

            {{-- Google SSO --}}
            <a href="{{ route('auth.google', ['type' => 'admin']) }}" class="btn-google">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Masuk dengan Google
            </a>

            <div class="divider">atau masuk dengan email</div>

            {{-- Login Form --}}
            <form action="{{ route('panel.login.process') }}" method="POST">
                @csrf

                <div class="form-group">
                    <span class="input-icon">
                        <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/><path stroke-linecap="round" stroke-linejoin="round" d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/></svg>
                    </span>
                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        placeholder="Alamat Email"
                        x-model="email"
                        autocomplete="email"
                        required>
                </div>

                <div class="form-group">
                    <span class="input-icon">
                        <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M5 9V7a5 5 0 0110 0v2M4 9h12a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2v-6a2 2 0 012-2z"/></svg>
                    </span>
                    <input
                        :type="showPassword ? 'text' : 'password'"
                        name="password"
                        class="form-control"
                        placeholder="Password"
                        x-model="password"
                        autocomplete="current-password"
                        required>
                    <button type="button" class="eye-btn" @click="showPassword = !showPassword" aria-label="Tampilkan password">
                        <svg x-show="!showPassword" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M1 10S4 4 10 4s9 6 9 6-3 6-9 6-9-6-9-6z"/><circle cx="10" cy="10" r="2.5" stroke="currentColor"/></svg>
                        <svg x-show="showPassword" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3l14 14M10 4c-1.3 0-2.5.3-3.6.8M1 10s.5-.9 1.5-2M19 10s-3 6-9 6c-1.3 0-2.5-.3-3.6-.8"/></svg>
                    </button>
                </div>

                <button
                    type="submit"
                    class="btn-submit"
                    :class="{ 'is-active': isReady }">
                    Masuk ke Panel Admin
                </button>
            </form>

            <div class="form-footer">
                &copy; {{ date('Y') }} <strong style="color:var(--slate-600);">YPI Al Azhar</strong> — Al Azhar Presensi System.<br>
                Akses khusus administrator yang terotorisasi.
            </div>

        </div>
    </div>

</div>

<script>
    /* Show desktop header when brand panel is visible */
    (function () {
        var mq = window.matchMedia('(min-width: 960px)');
        var header = document.getElementById('desktop-header');

        function toggle(e) {
            header.style.display = e.matches ? 'block' : 'none';
        }

        toggle(mq);
        mq.addEventListener('change', toggle);
    })();
</script>

</body>
</html>