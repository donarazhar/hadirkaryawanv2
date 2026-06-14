<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak QR Code - {{ $cabang->nama_cabang }}</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            text-align: center;
            margin: 0;
            padding: 50px;
            background: #f4f6fa;
        }
        .qr-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            max-width: 400px;
            margin: 0 auto;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .qr-code {
            margin: 20px 0;
            padding: 20px;
            border: 2px dashed #0053C5;
            border-radius: 10px;
        }
        .qr-code img {
            width: 100%;
            max-width: 250px;
            height: auto;
        }
        h1 {
            color: #333;
            margin: 0 0 10px 0;
        }
        p {
            color: #666;
            margin: 0 0 20px 0;
        }
        .btn-print {
            background: #0053C5;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
            font-weight: 600;
        }
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .qr-card {
                box-shadow: none;
            }
            .btn-print {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="qr-card">
        <h1>PresensiGPS</h1>
        <p>Cabang: <strong>{{ $cabang->nama_cabang }}</strong></p>
        
        <div class="qr-code">
            <!-- Using QRServer API for simple generation -->
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode($cabang->kode_cabang) }}" alt="QR Code Cabang">
        </div>

        <p><small>Gunakan menu "Scan QR" di aplikasi PresensiGPS Anda untuk melakukan absen di lokasi ini.</small></p>

        <button class="btn-print" onclick="window.print()">Cetak QR Code</button>
    </div>

</body>
</html>
