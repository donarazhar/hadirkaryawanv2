<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Laporan Presensi - {{ $karyawan->nama_lengkap }}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            font-size: 22px;
            color: #0053C5;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 14px;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 5px;
            font-size: 14px;
        }
        .info-table .label {
            width: 120px;
            font-weight: bold;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-bottom: 30px;
        }
        .data-table th, .data-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
            vertical-align: middle;
        }
        .data-table th {
            background-color: #f5f5f5;
            font-weight: bold;
            color: #333;
        }
        .foto {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 4px;
        }
        .footer {
            width: 100%;
            margin-top: 30px;
        }
        .footer-table {
            width: 100%;
            text-align: center;
        }
        .footer-table td {
            width: 50%;
            padding: 10px;
        }
        .signature-space {
            height: 80px;
        }
        
        /* Print settings */
        @media print {
            body {
                padding: 0;
            }
            @page {
                size: A4;
                margin: 1cm;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>YPI AL-AZHAR</h2>
        <p>LAPORAN PRESENSI KARYAWAN BULAN {{ strtoupper($namabulan[$bulan]) }} {{ $tahun }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">NIK</td>
            <td>: {{ $karyawan->nik }}</td>
            <td class="label">Departemen</td>
            <td>: {{ $karyawan->nama_dept }}</td>
        </tr>
        <tr>
            <td class="label">Nama Karyawan</td>
            <td>: <strong>{{ $karyawan->nama_lengkap }}</strong></td>
            <td class="label">Cabang</td>
            <td>: {{ $karyawan->nama_cabang }}</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th>No.</th>
                <th>Tanggal</th>
                <th>Shift / Jam Kerja</th>
                <th>Jam Masuk</th>
                <th>Foto In</th>
                <th>Jam Pulang</th>
                <th>Foto Out</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @if (empty($rekap))
                <tr>
                    <td colspan="8" style="padding: 20px;">Tidak ada data presensi pada bulan ini.</td>
                </tr>
            @else
                @php $no = 1; @endphp
                @foreach ($rekap as $tgl => $presensiList)
                    @foreach($presensiList as $index => $d)
                        @php
                            $foto_in = $d->foto_in;
                            $foto_out = $d->foto_out;
                            
                            $jam_masuk_asli = $d->jam_masuk;
                            $jam_in_absen = $d->jam_in;
                            $telat = false;
                            if ($jam_in_absen > $jam_masuk_asli && !empty($jam_in_absen)) {
                                $telat = true;
                            }
                        @endphp
                        <tr>
                            <!-- Hanya tampilkan No dan Tanggal di row pertama untuk hari tersebut -->
                            @if($index == 0)
                                <td rowspan="{{ count($presensiList) }}">{{ $no++ }}</td>
                                <td rowspan="{{ count($presensiList) }}">{{ date('d-m-Y', strtotime($tgl)) }}</td>
                            @endif
                            
                            <td>
                                {{ $d->nama_jam_kerja }}
                                @if($d->shift_ke)
                                    <br><small>(Shift {{ $d->shift_ke }})</small>
                                @endif
                            </td>
                            
                            <td style="{{ $telat ? 'color: red; font-weight: bold;' : '' }}">
                                {{ $d->jam_in ? date('H:i', strtotime($d->jam_in)) : 'Belum Absen' }}
                            </td>
                            <td>
                                @if($foto_in === 'face_api')
                                    <span style="color: green; font-weight: bold;">Verified ✓</span>
                                @elseif($foto_in)
                                    <img src="{{ url(Storage::url('uploads/absensi/' . $foto_in)) }}" class="foto" alt="IN">
                                @else
                                    -
                                @endif
                            </td>
                            
                            <td>
                                {{ $d->jam_out ? date('H:i', strtotime($d->jam_out)) : 'Belum Absen' }}
                            </td>
                            <td>
                                @if($foto_out === 'face_api')
                                    <span style="color: green; font-weight: bold;">Verified ✓</span>
                                @elseif($foto_out)
                                    <img src="{{ url(Storage::url('uploads/absensi/' . $foto_out)) }}" class="foto" alt="OUT">
                                @else
                                    -
                                @endif
                            </td>
                            
                            <td>
                                @if ($d->status == 'i')
                                    Izin
                                @elseif($d->status == 's')
                                    Sakit
                                @elseif($telat)
                                    Terlambat
                                @else
                                    Hadir
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            @endif
        </tbody>
    </table>

    <div class="footer">
        <table class="footer-table">
            <tr>
                <td>
                    <p>Mengetahui,<br><strong>Pimpinan Cabang / Manajer</strong></p>
                    <div class="signature-space"></div>
                    <p>_______________________</p>
                </td>
                <td>
                    <p>Dicetak pada: {{ date('d-m-Y H:i') }}<br><strong>HRD / Admin Presensi</strong></p>
                    <div class="signature-space"></div>
                    <p>_______________________</p>
                </td>
            </tr>
        </table>
    </div>

    <!-- Auto Print Script -->
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
