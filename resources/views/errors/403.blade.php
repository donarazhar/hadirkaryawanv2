<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — Akses Ditolak | Hadir Karyawan</title>
    <link rel="shortcut icon" href="/assets/img/logoypia.png" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #FFFBEB 0%, #F8FAFC 60%, #FEF2F2 100%);
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            padding: 24px;
        }
        .card {
            background: #fff; border-radius: 24px;
            box-shadow: 0 8px 40px rgba(245,158,11,0.10), 0 1px 4px rgba(0,0,0,0.06);
            padding: 48px 40px; max-width: 440px; width: 100%; text-align: center;
        }
        .icon-wrap {
            width: 88px; height: 88px; border-radius: 22px;
            background: linear-gradient(135deg, #FEF3C7, #FFFBEB);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 24px;
        }
        .icon-wrap svg { width: 46px; height: 46px; }
        .code {
            font-size: 72px; font-weight: 900; letter-spacing: -4px;
            background: linear-gradient(135deg, #F59E0B, #D97706);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            line-height: 1; margin-bottom: 12px;
        }
        h1 { font-size: 20px; font-weight: 800; color: #111827; margin-bottom: 10px; }
        p { font-size: 14px; color: #6B7280; line-height: 1.6; margin-bottom: 32px; }
        .btn {
            display: inline-flex; align-items: center; gap: 8px;
            background: linear-gradient(135deg, #F59E0B, #D97706);
            color: #fff; text-decoration: none;
            padding: 12px 28px; border-radius: 12px;
            font-size: 14px; font-weight: 700;
            box-shadow: 0 4px 14px rgba(245,158,11,0.30);
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
                <rect x="10" y="20" width="26" height="20" rx="4" stroke="#F59E0B" stroke-width="2.5"/>
                <path d="M16 20v-5a7 7 0 0114 0v5" stroke="#F59E0B" stroke-width="2.5" stroke-linecap="round"/>
                <circle cx="23" cy="30" r="2" fill="#F59E0B"/>
            </svg>
        </div>
        <div class="code">403</div>
        <h1>Akses Ditolak</h1>
        <p>Anda tidak memiliki izin untuk mengakses halaman ini.<br>Silakan login dengan akun yang sesuai atau kembali ke dashboard.</p>
        <div class="btn-group">
            <a href="/dashboard" class="btn">🏠 Kembali ke Dashboard</a>
            <a href="javascript:history.back()" class="btn-sec">← Halaman Sebelumnya</a>
        </div>
    </div>
</body>
</html>
