<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password — PresensiGPS</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logoypia.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
            background: var(--bg);
            color: var(--text-900);
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: var(--surface);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            padding: 0;
        }

        @media (min-width: 540px) {
            body { padding: 24px; background: #F1F5F9; }
            .login-card {
                min-height: auto;
                border-radius: 24px;
                box-shadow: 0 8px 24px rgba(0,0,0,0.07);
                overflow: hidden;
            }
        }

        .accent-band {
            height: 4px;
            background: linear-gradient(90deg, var(--primary) 0%, #60A5FA 50%, var(--primary) 100%);
        }

        .login-body {
            flex: 1;
            padding: 36px 28px 28px;
            display: flex;
            flex-direction: column;
        }

        .logo-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-bottom: 28px;
        }

        .app-name {
            font-size: 22px;
            font-weight: 800;
            color: var(--text-900);
            margin-bottom: 6px;
        }

        .app-sub {
            font-size: 13px;
            color: var(--text-400);
            font-weight: 500;
            line-height: 1.5;
            max-width: 280px;
        }

        .alert {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 12px 14px;
            border-radius: 12px;
            font-size: 13px;
            margin-bottom: 20px;
        }
        .alert-error { background: #FEF2F2; border: 1px solid #FECACA; color: #DC2626; }
        .alert-success { background: #ECFDF5; border: 1px solid #A7F3D0; color: #065F46; }

        .form-group { position: relative; margin-bottom: 14px; }
        .form-control {
            width: 100%;
            padding: 14px;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 500;
        }
        .form-control:focus { border-color: var(--primary); outline: none; }

        .btn-submit {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 12px;
            font-family: 'Inter', sans-serif;
            font-size: 15px;
            font-weight: 700;
            background: var(--primary);
            color: white;
            cursor: pointer;
            margin-top: 10px;
        }
        .btn-submit:hover { background: var(--primary-dark); }
        .btn-back {
            display: block;
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
            color: var(--text-600);
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="accent-band"></div>
    <div class="login-body">
        <div class="logo-section">
            <div class="app-name">Reset Password</div>
            <p class="app-sub">Masukkan password baru Anda untuk mereset akun.</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <input id="email" type="email" class="form-control" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" readonly placeholder="Alamat Email">
            </div>

            <div class="form-group">
                <input id="password" type="password" class="form-control" name="password" required autocomplete="new-password" placeholder="Password Baru">
            </div>

            <div class="form-group">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Konfirmasi Password Baru">
            </div>

            <button type="submit" class="btn-submit">
                Simpan Password Baru
            </button>

            <a href="{{ route('login') }}" class="btn-back">Batal & Kembali ke Login</a>
        </form>
    </div>
</div>

</body>
</html>
