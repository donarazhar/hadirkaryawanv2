<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Otorisasi Akses - PresensiGPS</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 40px;
            max-width: 420px;
            width: 90%;
            text-align: center;
            color: #fff;
        }
        .logo { font-size: 48px; margin-bottom: 16px; }
        h1 { font-size: 22px; margin-bottom: 8px; }
        p { color: rgba(255,255,255,0.7); font-size: 14px; margin-bottom: 24px; }
        .app-name {
            background: rgba(99,179,237,0.2);
            border: 1px solid rgba(99,179,237,0.4);
            border-radius: 8px;
            padding: 10px 16px;
            margin-bottom: 24px;
            font-weight: 600;
            color: #63b3ed;
        }
        .btn-group { display: flex; gap: 12px; }
        form { flex: 1; }
        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-approve {
            background: linear-gradient(135deg, #48bb78, #38a169);
            color: white;
        }
        .btn-approve:hover { opacity: 0.9; transform: translateY(-1px); }
        .btn-deny {
            background: rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.8);
        }
        .btn-deny:hover { background: rgba(255,0,0,0.2); }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">🔐</div>
        <h1>Otorisasi Akses</h1>
        <p>Aplikasi berikut meminta akses ke akun PresensiGPS Anda:</p>
        <div class="app-name">{{ $client->name }}</div>
        <p>Anda akan masuk sebagai <strong>{{ $user->name }}</strong></p>
        <br>
        <div class="btn-group">
            <form method="POST" action="{{ route('passport.authorizations.approve') }}">
                @csrf
                <input type="hidden" name="state" value="{{ $request->state }}">
                <input type="hidden" name="client_id" value="{{ $client->getKey() }}">
                <input type="hidden" name="auth_token" value="{{ $authToken }}">
                <button type="submit" class="btn-approve">✓ Izinkan Akses</button>
            </form>
            <form method="POST" action="{{ route('passport.authorizations.deny') }}">
                @csrf
                @method('DELETE')
                <input type="hidden" name="state" value="{{ $request->state }}">
                <input type="hidden" name="client_id" value="{{ $client->getKey() }}">
                <input type="hidden" name="auth_token" value="{{ $authToken }}">
                <button type="submit" class="btn-deny">✗ Tolak</button>
            </form>
        </div>
    </div>
</body>
</html>
