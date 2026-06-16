<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Halaman Tidak Ditemukan | Hadir Karyawan</title>
    <link rel="shortcut icon" href="/assets/img/logoypia.png" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #EFF6FF 0%, #F8FAFC 60%, #EDE9FE 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .card {
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 8px 40px rgba(37,99,235,0.10), 0 1px 4px rgba(0,0,0,0.06);
            padding: 48px 40px;
            max-width: 440px;
            width: 100%;
            text-align: center;
        }
        .icon-wrap {
            width: 88px; height: 88px;
            border-radius: 22px;
            background: linear-gradient(135deg, #DBEAFE, #EFF6FF);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 24px;
        }
        .icon-wrap svg { width: 46px; height: 46px; }
        .code {
            font-size: 72px;
            font-weight: 900;
            letter-spacing: -4px;
            background: linear-gradient(135deg, #2563EB, #7C3AED);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1;
            margin-bottom: 12px;
        }
        h1 { font-size: 20px; font-weight: 800; color: #111827; margin-bottom: 10px; }
        p { font-size: 14px; color: #6B7280; line-height: 1.6; margin-bottom: 32px; }
        .btn {
            display: inline-flex; align-items: center; gap: 8px;
            background: linear-gradient(135deg, #2563EB, #1D4ED8);
            color: #fff; text-decoration: none;
            padding: 12px 28px; border-radius: 12px;
            font-size: 14px; font-weight: 700;
            box-shadow: 0 4px 14px rgba(37,99,235,0.30);
            transition: transform 0.15s, box-shadow 0.15s;
        }
        .btn:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(37,99,235,0.35); color: #fff; }
        .btn-sec {
            display: inline-flex; align-items: center; gap: 8px;
            background: #F3F4F6; color: #374151;
            text-decoration: none;
            padding: 12px 24px; border-radius: 12px;
            font-size: 14px; font-weight: 700;
            margin-top: 12px;
            transition: background 0.15s;
        }
        .btn-sec:hover { background: #E5E7EB; color: #111827; }
        .btn-group { display: flex; flex-direction: column; align-items: center; gap: 0; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon-wrap">
            <svg viewBox="0 0 46 46" fill="none">
                <path d="M23 4L4 39h38L23 4z" stroke="#2563EB" stroke-width="2.5" stroke-linejoin="round"/>
                <path d="M23 18v9" stroke="#2563EB" stroke-width="2.5" stroke-linecap="round"/>
                <circle cx="23" cy="32" r="1.5" fill="#2563EB"/>
            </svg>
        </div>
        <div class="code">404</div>
        <h1>Halaman Tidak Ditemukan</h1>
        <p>Maaf, halaman yang Anda cari tidak ada atau sudah dipindahkan.<br>Silakan kembali ke halaman utama.</p>
        <div class="btn-group">
            <a href="/dashboard" class="btn">🏠 Kembali ke Dashboard</a>
            <a href="javascript:history.back()" class="btn-sec">← Halaman Sebelumnya</a>
        </div>
    </div>
</body>
</html>
