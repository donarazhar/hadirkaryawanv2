<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Login - Presensi YPI Al Azhar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta content="Sistem Presensi YPI Al Azhar" name="description" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/img/ypia.png') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css" rel="stylesheet">

    <!-- App CSS -->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
        :root {
            --primary-color: #0053C5;
            --primary-dark: #003d94;
            --primary-light: #3379d9;
        }

        body {
            background: linear-gradient(135deg, #0053C5 0%, #003d94 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-container {
            max-width: 420px;
            margin: 0 auto;
            padding: 20px;
        }

        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .login-header {
            background: linear-gradient(135deg, #0053C5 0%, #003d94 100%);
            padding: 30px 20px;
            text-align: center;
            color: white;
        }

        .login-header h4 {
            margin: 10px 0 5px 0;
            font-weight: 600;
            font-size: 24px;
        }

        .login-header p {
            margin: 0;
            opacity: 0.9;
            font-size: 14px;
        }

        .login-body {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }

        .form-control {
            height: 45px;
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 10px 15px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #0053C5;
            box-shadow: 0 0 0 0.2rem rgba(0, 83, 197, 0.25);
        }

        .input-group-text {
            background: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 8px 0 0 8px;
        }

        .btn-login {
            width: 100%;
            height: 45px;
            background: linear-gradient(135deg, #0053C5 0%, #003d94 100%);
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            font-size: 16px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 83, 197, 0.4);
            background: linear-gradient(135deg, #003d94 0%, #0053C5 100%);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .alert {
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .form-check {
            margin: 15px 0;
        }

        .form-check-input:checked {
            background-color: #0053C5;
            border-color: #0053C5;
        }

        .admin-link {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .admin-link a {
            color: #0053C5;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .admin-link a:hover {
            color: #003d94;
            text-decoration: underline;
        }

        .logo-icon {
            font-size: 48px;
            margin-bottom: 10px;
        }

        .input-icon {
            position: relative;
        }

        .input-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .input-icon .form-control {
            padding-left: 45px;
        }

        .ypi-logo {
            width: 60px;
            height: 60px;
            margin-bottom: 10px;
        }

        .text-white {
            color: white !important;
        }

        .btn-close {
            filter: brightness(0) invert(1);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="login-container">
            <div class="login-card">
                <!-- Header -->
                <div class="login-header">
                    <div class="logo-icon">
                        <i class="mdi mdi-account-check"></i>
                    </div>
                    <h4>Presensi YPI Al Azhar</h4>
                    <p>Silakan login untuk melanjutkan</p>
                </div>

                <!-- Body -->
                <div class="login-body">
                    <!-- Alert Messages -->
                    @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-alert-outline me-2"></i>
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-check-circle-outline me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-alert-circle-outline me-2"></i>
                        @foreach($errors->all() as $error)
                        {{ $error }}<br>
                        @endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <!-- Login Form -->
                    <form action="/proseslogin" method="POST" id="formLogin">
                        @csrf

                        <div class="form-group">
                            <label for="nik">NIK</label>
                            <div class="input-icon">
                                <i class="mdi mdi-account"></i>
                                <input type="text"
                                    class="form-control @error('nik') is-invalid @enderror"
                                    id="nik"
                                    name="nik"
                                    placeholder="Masukkan NIK Anda"
                                    value="{{ old('nik') }}"
                                    required
                                    autofocus>
                            </div>
                            @error('nik')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="input-icon">
                                <i class="mdi mdi-lock"></i>
                                <input type="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    id="password"
                                    name="password"
                                    placeholder="Masukkan Password Anda"
                                    required>
                            </div>
                            @error('password')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Ingat Saya
                            </label>
                        </div>

                        <button type="submit" class="btn btn-login">
                            <i class="mdi mdi-login me-1"></i> Login
                        </button>
                    </form>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-4">
                <p class="text-white">
                    <small>&copy; {{ date('Y') }} YPI Al Azhar. All rights reserved.</small>
                </p>
            </div>
        </div>
    </div>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Auto hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    </script>
</body>

</html>