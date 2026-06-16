<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="Login Presensi Karyawan — masuk menggunakan NIK atau akun Google Anda.">
    <title>Login Karyawan — PresensiGPS</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logoypia.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <style>
        :root {
            --primary:      #2563EB;
            --primary-dark: #1D4ED8;
            --primary-soft: #EFF6FF;
            --primary-mid:  #BFDBFE;
            --success:      #10B981;
            --danger:       #EF4444;
            --text-900:     #111827;
            --text-600:     #4B5563;
            --text-400:     #9CA3AF;
            --border:       #E5E7EB;
            --surface:      #FFFFFF;
            --bg:           #F9FAFB;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        html, body {
            height: 100%;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            -webkit-font-smoothing: antialiased;
            background: var(--bg);
            color: var(--text-900);
        }

        /* ── FULL PAGE LAYOUT ── */
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        /* ── CARD WRAPPER ── */
        .login-card {
            width: 100%;
            max-width: 420px;
            background: var(--surface);
            display: flex;
            flex-direction: column;
            /* Mobile: fullscreen */
            min-height: 100vh;
            padding: 0;
        }

        /* Desktop: floating card */
        @media (min-width: 540px) {
            body { padding: 24px; background: #F1F5F9; }

            .login-card {
                min-height: auto;
                border-radius: 24px;
                box-shadow:
                    0 0 0 1px rgba(0,0,0,0.04),
                    0 8px 24px rgba(0,0,0,0.07),
                    0 24px 48px rgba(0,0,0,0.04);
                overflow: hidden;
            }
        }

        /* ── TOP ACCENT BAND ── */
        .accent-band {
            height: 4px;
            background: linear-gradient(90deg, var(--primary) 0%, #60A5FA 50%, var(--primary) 100%);
            background-size: 200% 100%;
            animation: shimmer 3s ease-in-out infinite;
        }

        @keyframes shimmer {
            0%, 100% { background-position: 0% 0%; }
            50%       { background-position: 100% 0%; }
        }

        /* ── MAIN CONTENT ── */
        .login-body {
            flex: 1;
            padding: 36px 28px 28px;
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        @media (min-width: 540px) {
            .login-body { padding: 40px 36px 32px; }
        }

        /* ── LOGO SECTION ── */
        .logo-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-bottom: 28px;
        }

        .logo-wrap {
            width: 72px; height: 72px;
            border-radius: 18px;
            background: var(--primary-soft);
            border: 1.5px solid var(--primary-mid);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 16px;
            overflow: hidden;
        }

        .logo-wrap img {
            width: 52px; height: 52px;
            object-fit: contain;
        }

        .app-name {
            font-size: 22px;
            font-weight: 800;
            color: var(--text-900);
            letter-spacing: -0.4px;
            line-height: 1.1;
            margin-bottom: 6px;
        }

        .app-name span { color: var(--primary); }

        .app-sub {
            font-size: 13px;
            color: var(--text-400);
            font-weight: 500;
            line-height: 1.5;
            max-width: 280px;
        }

        /* ── ROLE BADGE ── */
        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            background: var(--primary-soft);
            border: 1px solid var(--primary-mid);
            border-radius: 50px;
            font-size: 11px;
            font-weight: 700;
            color: var(--primary);
            margin-top: 10px;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        .role-badge::before {
            content: '';
            width: 6px; height: 6px;
            background: var(--primary);
            border-radius: 50%;
        }

        /* ── ALERT ── */
        .alert {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 12px 14px;
            border-radius: 12px;
            font-size: 13px;
            margin-bottom: 20px;
            animation: fadeIn 0.25s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-4px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .alert-error {
            background: #FEF2F2;
            border: 1px solid #FECACA;
            color: #DC2626;
        }

        .alert-success {
            background: #ECFDF5;
            border: 1px solid #A7F3D0;
            color: #065F46;
        }

        .alert svg { flex-shrink: 0; margin-top: 1px; width: 18px; height: 18px; min-width: 18px; }

        /* ── GOOGLE BUTTON ── */
        .btn-google {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 13px 20px;
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-900);
            text-decoration: none;
            cursor: pointer;
            transition: background 0.18s, border-color 0.18s, box-shadow 0.18s;
            -webkit-tap-highlight-color: transparent;
        }

        .btn-google:hover   { background: var(--bg); border-color: #D1D5DB; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
        .btn-google:active  { background: #F3F4F6; transform: scale(0.99); }

        /* ── DIVIDER ── */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 22px 0;
            color: var(--text-400);
            font-size: 12px;
            font-weight: 500;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        /* ── FORM ── */
        .form-group {
            position: relative;
            margin-bottom: 14px;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-400);
            pointer-events: none;
            display: flex;
        }

        .input-icon svg { width: 18px; height: 18px; }

        .form-control {
            width: 100%;
            padding: 14px 44px 14px 44px;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-900);
            background: var(--surface);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            -webkit-appearance: none;
        }

        .form-control::placeholder { color: var(--text-400); }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37,99,235,0.12);
        }

        .eye-btn {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-400);
            display: flex;
            padding: 4px;
            -webkit-tap-highlight-color: transparent;
            transition: color 0.15s;
        }

        .eye-btn:hover { color: var(--text-600); }
        .eye-btn svg   { width: 18px; height: 18px; }

        /* ── SUBMIT BUTTON ── */
        .btn-submit {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 12px;
            font-family: 'Inter', sans-serif;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s, box-shadow 0.2s, transform 0.15s;
            margin-top: 4px;
            -webkit-tap-highlight-color: transparent;
        }

        /* Inactive state */
        .btn-submit {
            background: #E5E7EB;
            color: var(--text-400);
            box-shadow: none;
        }

        /* Active state via Alpine */
        .btn-submit.is-active {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 16px rgba(37,99,235,0.30);
        }

        .btn-submit.is-active:hover  { background: var(--primary-dark); }
        .btn-submit.is-active:active { transform: scale(0.98); box-shadow: 0 2px 8px rgba(37,99,235,0.25); }

        /* ── FOOTER ── */
        .login-footer {
            padding: 20px 28px 28px;
            text-align: center;
        }

        .footer-text {
            font-size: 12px;
            color: var(--text-400);
            line-height: 1.6;
        }

        .footer-text strong { color: var(--text-600); font-weight: 600; }

        /* ── ILLUSTRATION DOTS ── */
        .dots-pattern {
            position: fixed;
            top: 0; left: 0;
            width: 100vw; height: 100vh;
            pointer-events: none;
            z-index: -1;
            overflow: hidden;
            display: none;
        }

        @media (min-width: 540px) {
            .dots-pattern { display: block; }
        }

        .dots-pattern::before {
            content: '';
            position: absolute;
            width: 320px; height: 320px;
            top: -80px; left: -80px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(37,99,235,0.07) 0%, transparent 70%);
        }

        .dots-pattern::after {
            content: '';
            position: absolute;
            width: 280px; height: 280px;
            bottom: -60px; right: -60px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(96,165,250,0.07) 0%, transparent 70%);
        }

    </style>
</head>
<body x-data="{
    nik: '{{ old('nik') }}',
    password: '',
    showPassword: false,
    get isReady() { return this.nik.length > 0 && this.password.length > 0; }
}">

<div class="dots-pattern"></div>

<div class="login-card">

    {{-- Top shimmer band --}}
    <div class="accent-band"></div>

    <div class="login-body">

        {{-- Logo + Title --}}
        <div class="logo-section">
            <div class="logo-wrap">
                <img src="{{ asset('assets/img/logoypia.png') }}" alt="Logo">
            </div>
            <div class="app-name">Presensi <span>Karyawan</span></div>
            <p class="app-sub">Masuk untuk mencatat kehadiran harian dan mengajukan perizinan.</p>
            <span class="role-badge">Portal Karyawan</span>
        </div>

        {{-- Alerts --}}
        @if(session('error'))
        <div class="alert alert-error">
            <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor" style="flex-shrink:0;"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        @if(session('success'))
        <div class="alert alert-success">
            <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor" style="flex-shrink:0;"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-error">
            <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor" style="flex-shrink:0;margin-top:1px;"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
            <div>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Google Login --}}
        <a href="{{ route('auth.google', ['type' => 'karyawan']) }}" class="btn-google">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
            </svg>
            Masuk dengan Google
        </a>

        {{-- Divider --}}
        <div class="divider">atau masuk dengan NIK / Email</div>

        {{-- Login Form --}}
        <form action="{{ route('proseslogin') }}" method="POST">
            @csrf

            {{-- NIK / Email --}}
            <div class="form-group">
                <span class="input-icon">
                    <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M10 10a4 4 0 100-8 4 4 0 000 8zm-7 8a7 7 0 0114 0H3z"/></svg>
                </span>
                <input
                    type="text"
                    name="nik"
                    class="form-control"
                    placeholder="NIK atau Email"
                    x-model="nik"
                    autocomplete="username"
                    required>
            </div>

            {{-- Password --}}
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

            {{-- Submit --}}
            <button
                type="submit"
                class="btn-submit"
                :class="{ 'is-active': isReady }">
                Masuk
            </button>
        </form>

    </div>

    {{-- Footer --}}
    <div class="login-footer">
        <p class="footer-text">
            &copy; {{ date('Y') }} <strong>YPI Al Azhar</strong> — PresensiGPS.<br>
            Seluruh data dilindungi dan terenkripsi.
        </p>
    </div>

</div>

</body>
</html>