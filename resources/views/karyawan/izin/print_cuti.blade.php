<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Cuti – {{ $izin->kode_izin }}</title>
    <style>
        /* ── RESET ── */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        @import url('https://fonts.googleapis.com/css2?family=Times+New+Roman&display=swap');

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            color: #000;
            background: #f0f0f0;
            display: flex;
            justify-content: center;
            padding: 30px 16px 60px;
        }

        /* ── PAPER ── */
        .paper {
            width: 210mm;
            min-height: 297mm;
            background: #fff;
            padding: 20mm 25mm 20mm 30mm;
            box-shadow: 0 4px 24px rgba(0,0,0,0.12);
            position: relative;
        }

        /* ── KOP SURAT ── */
        .kop {
            display: flex;
            align-items: center;
            gap: 16px;
            padding-bottom: 10px;
            border-bottom: 3px solid #000;
            margin-bottom: 6px;
        }

        .kop-logo {
            width: 72px;
            height: 72px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .kop-logo-placeholder {
            width: 72px;
            height: 72px;
            border: 2px solid #999;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 9pt;
            color: #888;
            flex-shrink: 0;
            text-align: center;
        }

        .kop-text { flex: 1; text-align: center; }
        .kop-instansi {
            font-size: 15pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            line-height: 1.3;
        }
        .kop-sub {
            font-size: 10pt;
            margin-top: 2px;
            color: #333;
        }
        .kop-alamat { font-size: 9pt; color: #555; margin-top: 2px; }

        .garis-bawah-kop {
            border-bottom: 1px solid #000;
            margin-bottom: 18px;
        }

        /* ── JUDUL ── */
        .surat-judul {
            text-align: center;
            margin: 18px 0 4px;
        }
        .surat-judul h2 {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            text-decoration: underline;
        }
        .surat-nomor {
            text-align: center;
            font-size: 11pt;
            margin-bottom: 18px;
            color: #444;
        }

        /* ── DASAR ── */
        .dasar { font-size: 11pt; margin-bottom: 14px; line-height: 1.8; }
        .dasar p { margin-bottom: 4px; }

        /* ── INFO TABLE ── */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 14px 0 18px;
            font-size: 11.5pt;
        }
        .info-table td {
            padding: 5px 8px;
            vertical-align: top;
        }
        .info-table td:first-child { width: 38%; font-weight: 600; }
        .info-table td:nth-child(2) { width: 4%; text-align: center; }
        .info-table td:last-child { width: 58%; }

        /* ── ISI SURAT ── */
        .isi { font-size: 11.5pt; line-height: 1.9; margin-bottom: 16px; text-align: justify; }

        /* ── TANDA TANGAN ── */
        .ttd-wrap {
            display: flex;
            justify-content: space-between;
            margin-top: 28px;
        }
        .ttd-box { text-align: center; }
        .ttd-box .ttd-label { font-size: 11pt; margin-bottom: 4px; }
        .ttd-box .ttd-name {
            margin-top: 64px;
            font-size: 11.5pt;
            font-weight: bold;
            border-top: 1px solid #000;
            padding-top: 4px;
            min-width: 160px;
        }
        .ttd-box .ttd-jabatan { font-size: 10pt; color: #444; }

        /* ── CATATAN ── */
        .catatan-box {
            margin-top: 28px;
            border: 1px solid #bbb;
            border-radius: 4px;
            padding: 10px 14px;
            font-size: 10pt;
            color: #555;
            background: #fafafa;
        }
        .catatan-box .catatan-label { font-weight: bold; color: #333; margin-bottom: 4px; }

        /* ── WATERMARK ── */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 80pt;
            font-weight: bold;
            color: rgba(16,185,129,0.06);
            pointer-events: none;
            user-select: none;
            white-space: nowrap;
        }

        /* ── TOOLBAR (screen only) ── */
        .toolbar {
            position: fixed;
            bottom: 0; left: 0; right: 0;
            background: #1e293b;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            z-index: 100;
        }
        .btn-print {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 28px;
            background: linear-gradient(135deg, #059669, #10B981);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: 'Inter', -apple-system, sans-serif;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(16,185,129,0.4);
            transition: opacity 0.2s;
        }
        .btn-print:hover { opacity: 0.9; }
        .btn-close {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: #334155;
            color: #cbd5e1;
            border: 1px solid #475569;
            border-radius: 10px;
            font-family: 'Inter', -apple-system, sans-serif;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s;
        }
        .btn-close:hover { background: #475569; }
        .toolbar-info {
            color: #94a3b8;
            font-family: 'Inter', sans-serif;
            font-size: 12px;
        }

        /* ── PRINT MEDIA ── */
        @media print {
            body { background: #fff; padding: 0; }
            .paper {
                width: 100%;
                min-height: auto;
                padding: 15mm 20mm 15mm 25mm;
                box-shadow: none;
            }
            .toolbar { display: none !important; }
        }

        @page {
            size: A4;
            margin: 0;
        }
    </style>
</head>
<body>

@php
    use Carbon\Carbon;

    $tglDari   = Carbon::parse($izin->tgl_izin_dari);
    $tglSampai = Carbon::parse($izin->tgl_izin_sampai);
    $durasi    = $tglDari->diffInDays($tglSampai) + 1;

    $tglDariStr   = $tglDari->isoFormat('D MMMM Y');
    $tglSampaiStr = $tglSampai->isoFormat('D MMMM Y');
    $tglHariIni   = Carbon::now()->isoFormat('D MMMM Y');

    $nomorSurat   = $izin->kode_izin . '/CUTI/' . Carbon::now()->format('Y');
    $namaCuti     = $izin->nama_cuti ?? 'Cuti';
@endphp

<div class="paper">

    {{-- Watermark --}}
    <div class="watermark">DISETUJUI</div>

    {{-- KOP SURAT --}}
    <div class="kop">
        <div class="kop-logo-placeholder">LOGO</div>
        <div class="kop-text">
            <div class="kop-instansi">{{ $karyawan->nama_cabang ?? config('app.name', 'Perusahaan') }}</div>
            <div class="kop-sub">Sistem Presensi Karyawan</div>
            <div class="kop-alamat">Alamat Perusahaan, Kota, Provinsi &bull; Telp. (0xx) xxxxxxx</div>
        </div>
    </div>
    <div class="garis-bawah-kop"></div>

    {{-- JUDUL --}}
    <div class="surat-judul">
        <h2>Surat Izin Cuti</h2>
    </div>
    <div class="surat-nomor">Nomor: {{ $nomorSurat }}</div>

    {{-- DASAR --}}
    <div class="dasar">
        <p>Yang bertanda tangan di bawah ini, Pimpinan {{ $karyawan->nama_dept ?? 'Departemen' }}
        pada {{ $karyawan->nama_cabang ?? config('app.name') }}, dengan ini menerangkan bahwa:</p>
    </div>

    {{-- INFO KARYAWAN --}}
    <table class="info-table">
        <tr>
            <td>Nama Lengkap</td>
            <td>:</td>
            <td><strong>{{ $karyawan->nama_lengkap }}</strong></td>
        </tr>
        <tr>
            <td>NIK / No. Karyawan</td>
            <td>:</td>
            <td>{{ $karyawan->nik }}</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>:</td>
            <td>{{ $karyawan->jabatan ?? '-' }}</td>
        </tr>
        <tr>
            <td>Departemen</td>
            <td>:</td>
            <td>{{ $karyawan->nama_dept ?? '-' }}</td>
        </tr>
        <tr>
            <td>Jenis Cuti</td>
            <td>:</td>
            <td><strong>{{ $namaCuti }}</strong></td>
        </tr>
        <tr>
            <td>Tanggal Mulai Cuti</td>
            <td>:</td>
            <td>{{ $tglDariStr }}</td>
        </tr>
        <tr>
            <td>Tanggal Selesai Cuti</td>
            <td>:</td>
            <td>{{ $tglSampaiStr }}</td>
        </tr>
        <tr>
            <td>Lama Cuti</td>
            <td>:</td>
            <td><strong>{{ $durasi }} hari kerja</strong></td>
        </tr>
        <tr>
            <td>Alasan / Keterangan</td>
            <td>:</td>
            <td>{{ $izin->keterangan }}</td>
        </tr>
    </table>

    {{-- ISI --}}
    <div class="isi">
        <p>
            Diberikan izin cuti kepada karyawan tersebut di atas terhitung mulai tanggal
            <strong>{{ $tglDariStr }}</strong> sampai dengan <strong>{{ $tglSampaiStr }}</strong>
            ({{ $durasi }} hari).
        </p>
        <br>
        <p>
            Surat izin cuti ini dibuat untuk disampaikan kepada Bagian HRD sebagai
            bukti persetujuan resmi dari Pimpinan Departemen dan digunakan sebagai
            dasar pencatatan kehadiran karyawan yang bersangkutan.
        </p>
        <br>
        <p>
            Demikian surat izin cuti ini dibuat dengan sebenar-benarnya untuk dapat
            dipergunakan sebagaimana mestinya.
        </p>
    </div>

    {{-- CATATAN PIMPINAN --}}
    @if(!empty($izin->catatan_atasan))
    <div class="catatan-box">
        <div class="catatan-label">Catatan Pimpinan:</div>
        <div>{{ $izin->catatan_atasan }}</div>
    </div>
    @endif

    {{-- TANDA TANGAN --}}
    <div class="ttd-wrap">
        <div class="ttd-box">
            <div class="ttd-label">Pemohon Cuti,</div>
            <div class="ttd-name">{{ $karyawan->nama_lengkap }}</div>
            <div class="ttd-jabatan">{{ $karyawan->jabatan ?? 'Karyawan' }}</div>
        </div>

        <div class="ttd-box">
            <div class="ttd-label">{{ $tglHariIni }}</div>
            <div class="ttd-label" style="margin-top:4px;">Pimpinan {{ $karyawan->nama_dept ?? 'Departemen' }},</div>
            <div class="ttd-name">( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )</div>
            <div class="ttd-jabatan">Pimpinan Departemen</div>
        </div>
    </div>

    {{-- FOOTER ── kode & tanggal cetak --}}
    <div style="margin-top: 32px; padding-top: 8px; border-top: 1px dashed #ccc;
                font-size: 9pt; color: #888; display: flex; justify-content: space-between;">
        <span>Kode: {{ $izin->kode_izin }}</span>
        <span>Dicetak: {{ Carbon::now()->isoFormat('D MMM Y, HH:mm') }} WIB</span>
    </div>

</div>

{{-- TOOLBAR (screen only) --}}
<div class="toolbar">
    <a href="{{ route('izin.show', $izin->kode_izin) }}" class="btn-close">
        ← Kembali
    </a>
    <span class="toolbar-info">Surat Cuti &bull; {{ $izin->kode_izin }}</span>
    <button class="btn-print" onclick="window.print()">
        🖨️ &nbsp;Cetak / Simpan PDF
    </button>
</div>

</body>
</html>
