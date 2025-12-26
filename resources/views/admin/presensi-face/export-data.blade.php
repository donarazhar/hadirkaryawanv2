<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export Data Presensi Face Recognition</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            margin: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        
        .header h2 {
            margin: 5px 0;
            font-size: 16pt;
        }
        
        .header p {
            margin: 3px 0;
            font-size: 10pt;
            color: #666;
        }
        
        .info-section {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f5f5f5;
            border-radius: 5px;
        }
        
        .info-section table {
            width: 100%;
        }
        
        .info-section td {
            padding: 3px 5px;
            font-size: 9pt;
        }
        
        .info-section .label {
            font-weight: bold;
            width: 150px;
        }
        
        .stats {
            margin-bottom: 15px;
            text-align: center;
        }
        
        .stats table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .stats td {
            padding: 8px;
            background-color: #e9ecef;
            border: 1px solid #dee2e6;
            font-weight: bold;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        .data-table thead {
            background-color: #343a40;
            color: white;
        }
        
        .data-table th {
            padding: 8px 4px;
            font-size: 8pt;
            text-align: left;
            border: 1px solid #dee2e6;
        }
        
        .data-table td {
            padding: 6px 4px;
            font-size: 8pt;
            border: 1px solid #dee2e6;
        }
        
        .data-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .data-table tbody tr:hover {
            background-color: #e9ecef;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7pt;
            font-weight: bold;
        }
        
        .badge-success {
            background-color: #28a745;
            color: white;
        }
        
        .badge-danger {
            background-color: #dc3545;
            color: white;
        }
        
        .badge-info {
            background-color: #17a2b8;
            color: white;
        }
        
        .badge-secondary {
            background-color: #6c757d;
            color: white;
        }
        
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #dee2e6;
            font-size: 8pt;
            text-align: right;
            color: #666;
        }
        
        @page {
            margin: 15mm;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h2>LAPORAN DATA PRESENSI FACE RECOGNITION</h2>
        <p>Sistem Presensi Face Recognition - PT Your Company</p>
        <p>Tanggal Export: {{ \Carbon\Carbon::now()->format('d F Y H:i:s') }}</p>
    </div>
    
    <!-- Filter Info -->
    <div class="info-section">
        <table>
            <tr>
                <td class="label">Periode:</td>
                <td>
                    @if($request->filled('tanggal_awal') && $request->filled('tanggal_akhir'))
                        {{ \Carbon\Carbon::parse($request->tanggal_awal)->format('d/m/Y') }} - 
                        {{ \Carbon\Carbon::parse($request->tanggal_akhir)->format('d/m/Y') }}
                    @else
                        Semua Periode
                    @endif
                </td>
                <td class="label">Status:</td>
                <td>
                    @if($request->filled('status'))
                        {{ ucfirst($request->status) }}
                    @else
                        Semua Status
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">Cabang:</td>
                <td>
                    @if($request->filled('kode_cabang'))
                        {{ $request->kode_cabang }}
                    @else
                        Semua Cabang
                    @endif
                </td>
                <td class="label">Tipe Shift:</td>
                <td>
                    @if($request->filled('shift_type'))
                        {{ ucfirst($request->shift_type) }}
                    @else
                        Semua Tipe
                    @endif
                </td>
            </tr>
        </table>
    </div>
    
    <!-- Statistics -->
    <div class="stats">
        <table>
            <tr>
                <td>Total Data<br><strong>{{ $stats['total_data'] }}</strong></td>
                <td>Total Hadir<br><strong>{{ $stats['total_hadir'] }}</strong></td>
                <td>Total Pulang<br><strong>{{ $stats['total_pulang'] }}</strong></td>
                <td>Verified<br><strong>{{ $stats['total_verified'] }}</strong></td>
                <td>Failed<br><strong>{{ $stats['total_failed'] }}</strong></td>
                <td>Multi-Shift<br><strong>{{ $stats['total_multi_shift'] }}</strong></td>
                <td>Regular<br><strong>{{ $stats['total_regular'] }}</strong></td>
            </tr>
        </table>
    </div>
    
    <!-- Data Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th width="3%" class="text-center">No</th>
                <th width="8%">Tanggal</th>
                <th width="8%">NIK</th>
                <th width="15%">Nama</th>
                <th width="10%">Jabatan</th>
                <th width="10%">Cabang</th>
                <th width="8%">Shift</th>
                <th width="7%">Masuk</th>
                <th width="7%">Pulang</th>
                <th width="7%" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($presensi as $item)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                <td><strong>{{ $item->nik }}</strong></td>
                <td>{{ $item->karyawan->nama_lengkap ?? 'N/A' }}</td>
                <td>{{ $item->karyawan->jabatan ?? '-' }}</td>
                <td>{{ $item->karyawan->cabang->nama_cabang ?? '-' }}</td>
                <td>
                    @if($item->shift_ke)
                        <span class="badge badge-info">Shift {{ $item->shift_ke }}</span>
                    @else
                        <span class="badge badge-secondary">Regular</span>
                    @endif
                </td>
                <td>{{ $item->jam_masuk ? \Carbon\Carbon::parse($item->jam_masuk)->format('H:i') : '-' }}</td>
                <td>{{ $item->jam_pulang ? \Carbon\Carbon::parse($item->jam_pulang)->format('H:i') : '-' }}</td>
                <td class="text-center">
                    @if($item->status == 'verified')
                        <span class="badge badge-success">✓</span>
                    @else
                        <span class="badge badge-danger">✗</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <!-- Footer -->
    <div class="footer">
        <p><em>Dokumen ini dibuat secara otomatis oleh sistem</em></p>
        <p>Total Data: {{ $presensi->count() }} | Dicetak: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>