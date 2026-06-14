<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="Login Presensi Karyawan YPI Al Azhar.">
    <title>Login Karyawan — PresensiGPS</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logoypia.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <style>
        :root {
            --primary-color: #0053C5; /* Corporate blue */
            --primary-hover: #003d94;
            --text-dark: #1f2937;
            --text-muted: #6b7280;
            --border-color: #d1d5db;
            --bg-body: #f9fafb;
            --bg-surface: #ffffff;
            --transition: all 0.2s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            -webkit-font-smoothing: antialiased;
        }

        .login-wrapper {
            width: 100%;
            max-width: 440px;
            background: var(--bg-surface);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 32px 24px;
        }

        /* Desktop enhancements */
        @media (min-width: 640px) {
            .login-wrapper {
                min-height: auto;
                border-radius: 24px;
                box-shadow: 0 10px 40px -10px rgba(0,0,0,0.08);
                margin: 40px 20px;
                padding: 48px 40px;
            }
        }

        /* Header / Logo */
        .header {
            margin-bottom: 32px;
            text-align: center;
        }

        .logo-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
        }

        .logo-text {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: var(--text-dark);
        }

        .welcome-subtitle {
            font-size: 14px;
            color: var(--text-muted);
            line-height: 1.5;
        }

        /* Google Login Button */
        .btn-google {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            padding: 14px;
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            color: var(--text-dark);
            text-decoration: none;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-google:hover {
            background: #f3f4f6;
            border-color: #9ca3af;
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 28px 0;
            color: var(--text-muted);
            font-size: 14px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid var(--border-color);
        }

        .divider:not(:empty)::before {
            margin-right: .5em;
        }

        .divider:not(:empty)::after {
            margin-left: .5em;
        }

        /* Form Inputs */
        .form-group {
            margin-bottom: 16px;
            position: relative;
        }

        .form-control {
            width: 100%;
            padding: 16px 16px;
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            font-size: 15px;
            color: var(--text-dark);
            transition: var(--transition);
            outline: none;
        }

        .form-control::placeholder {
            color: #9ca3af;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 83, 197, 0.1);
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            font-size: 18px;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .password-toggle:hover {
            color: var(--text-dark);
        }

        /* Alert Error / Success */
        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 14px;
            margin-bottom: 24px;
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }
        
        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
        }

        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
        }

        /* Main Submit Button */
        .btn-submit {
            width: 100%;
            padding: 16px;
            background: #9ca3af;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 8px;
        }

        .btn-submit.active {
            background: var(--primary-color);
            box-shadow: 0 4px 12px rgba(0, 83, 197, 0.2);
        }

        .btn-submit.active:hover {
            background: var(--primary-hover);
        }

        .footer {
            margin-top: auto;
            padding-top: 32px;
            text-align: center;
            font-size: 13px;
            color: var(--text-muted);
        }

    </style>
</head>
<body>

    <div class="login-wrapper" x-data="{ 
            nik: '{{ old('nik') }}', 
            password: '', 
            showPassword: false 
        }">
        
        <div class="header">
            <div class="logo-container">
                <img src="{{ asset('assets/img/logoypia.png') }}" alt="YPI Al Azhar Logo" style="width: 64px; height: 64px; border-radius: 8px; object-fit: contain;">
                <div class="logo-text">Presensi <span style="color: var(--primary-color);">Karyawan</span></div>
            </div>
            <p class="welcome-subtitle">Masuk untuk melakukan presensi harian atau mengajukan perizinan.</p>
        </div>

        @if(session('error'))
            <div class="alert alert-error">
                <i class="bi bi-exclamation-circle-fill" style="margin-top: 2px;"></i>
                <div>
                    <p>{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill" style="margin-top: 2px;"></i>
                <div>
                    <p>{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <i class="bi bi-exclamation-circle-fill" style="margin-top: 2px;"></i>
                <div>
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        <a href="{{ route('auth.google', ['type' => 'karyawan']) }}" class="btn-google">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
            </svg>
            Login dengan Google
        </a>

        <div class="divider">Atau gunakan NIK / Email</div>

        <form action="{{ route('proseslogin') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <input 
                    type="text" 
                    name="nik" 
                    class="form-control" 
                    placeholder="Masukkan NIK atau Email" 
                    x-model="nik"
                    required 
                    autocomplete="username"
                >
            </div>

            <div class="form-group">
                <input 
                    :type="showPassword ? 'text' : 'password'" 
                    name="password" 
                    class="form-control" 
                    placeholder="Masukkan Password" 
                    x-model="password"
                    required
                >
                <button type="button" class="password-toggle" @click="showPassword = !showPassword" aria-label="Toggle password visibility">
                    <i :class="showPassword ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                </button>
            </div>

            <button 
                type="submit" 
                class="btn-submit"
                :class="{'active': nik.length > 0 && password.length > 0}"
            >
                Login
            </button>
        </form>

        <div class="footer">
            &copy; {{ date('Y') }} YPI Al Azhar. All rights reserved.
        </div>

    </div>

</body>
</html>