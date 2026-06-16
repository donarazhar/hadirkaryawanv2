<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 — Kesalahan Server | Hadir Karyawan</title>
    <link rel="shortcut icon" href="/assets/img/logoypia.png" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #FEF2F2 0%, #F8FAFC 60%, #FFF7ED 100%);
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            padding: 24px;
        }
        .card {
            background: #fff; border-radius: 24px;
            box-shadow: 0 8px 40px rgba(239,68,68,0.10), 0 1px 4px rgba(0,0,0,0.06);
            padding: 48px 40px; max-width: 440px; width: 100%; text-align: center;
        }
        .icon-wrap {
            width: 88px; height: 88px; border-radius: 22px;
            background: linear-gradient(135deg, #FEE2E2, #FEF2F2);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 24px;
        }
        .icon-wrap svg { width: 46px; height: 46px; }
        .code {
            font-size: 72px; font-weight: 900; letter-spacing: -4px;
            background: linear-gradient(135deg, #EF4444, #DC2626);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            line-height: 1; margin-bottom: 12px;
        }
        h1 { font-size: 20px; font-weight: 800; color: #111827; margin-bottom: 10px; }
        p { font-size: 14px; color: #6B7280; line-height: 1.6; margin-bottom: 32px; }
        .btn {
            display: inline-flex; align-items: center; gap: 8px;
            background: linear-gradient(135deg, #EF4444, #DC2626);
            color: #fff; text-decoration: none;
            padding: 12px 28px; border-radius: 12px;
            font-size: 14px; font-weight: 700;
            box-shadow: 0 4px 14px rgba(239,68,68,0.30);
            transition: transform 0.15s;
        }
        .btn:hover { transform: translateY(-1px); color: #fff; }
        .btn-sec {
            display: inline-flex; align-items: center; gap: 8px;
            background: #F3F4F6; color: #374151; text-decoration: none;
            padding: 12px 24px; border-radius: 12px;
            font-size: 14px; font-weight: 700; margin-top: 12px;
            transition: background 0.15s;
        }
        .btn-sec:hover { background: #E5E7EB; color: #111827; }
        .btn-group { display: flex; flex-direction: column; align-items: center; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon-wrap">
            <svg viewBox="0 0 46 46" fill="none">
                <circle cx="23" cy="23" r="18" stroke="#EF4444" stroke-width="2.5"/>
                <path d="M23 14v10" stroke="#EF4444" stroke-width="2.5" stroke-linecap="round"/>
                <circle cx="23" cy="30" r="1.5" fill="#EF4444"/>
            </svg>
        </div>
        <div class="code">500</div>
        <h1>Terjadi Kesalahan Server</h1>
        <p>Maaf, terjadi kesalahan pada sistem kami. Tim teknis kami sudah diberitahu.<br>Silakan coba lagi beberapa saat.</p>
        <div class="btn-group">
            <a href="/dashboard" class="btn">🏠 Kembali ke Dashboard</a>
            <a href="javascript:location.reload()" class="btn-sec">↺ Muat Ulang Halaman</a>
        </div>
    </div>
</body>
</html>
