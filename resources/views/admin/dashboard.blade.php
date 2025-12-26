@extends('admin.layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    .stat-card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s;
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
    }

    .stat-icon {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
    }

    .stat-icon i {
        font-size: 36px;
    }

    .stat-number {
        font-size: 32px;
        font-weight: 700;
        margin: 10px 0;
    }

    .stat-label {
        font-size: 14px;
        color: #6c757d;
        margin: 0;
    }

    .stat-change {
        font-size: 12px;
        margin-top: 8px;
    }

    .chart-card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .chart-container {
        position: relative;
        height: 350px;
        width: 100%;
        padding: 10px;
    }

    @media (max-width: 768px) {
        .chart-container {
            height: 280px;
        }
    }

    .table-card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .ranking-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 10px;
        background: #f8f9fa;
        transition: all 0.3s;
    }

    .ranking-item:hover {
        background: #e9ecef;
        transform: translateX(5px);
    }

    .ranking-number {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 16px;
    }

    .ranking-number.gold {
        background: linear-gradient(135deg, #FFD700, #FFA500);
        color: white;
    }

    .ranking-number.silver {
        background: linear-gradient(135deg, #C0C0C0, #808080);
        color: white;
    }

    .ranking-number.bronze {
        background: linear-gradient(135deg, #CD7F32, #8B4513);
        color: white;
    }

    .ranking-number.other {
        background: #dee2e6;
        color: #495057;
    }

    .progress-thin {
        height: 8px;
        border-radius: 10px;
    }

    .welcome-card {
        background: linear-gradient(135deg, #0053C5 0%, #003d94 100%);
        color: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 4px 16px rgba(0, 83, 197, 0.3);
    }

    .welcome-card h3 {
        font-weight: 700;
        margin-bottom: 10px;
    }

    .welcome-card p {
        opacity: 0.9;
        margin-bottom: 0;
    }

    .quick-action-btn {
        border-radius: 8px;
        padding: 12px 24px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .quick-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .mini-stat {
        text-align: center;
        padding: 15px;
        border-radius: 8px;
        background: #f8f9fa;
    }

    .mini-stat-number {
        font-size: 24px;
        font-weight: 700;
        margin: 5px 0;
    }

    .mini-stat-label {
        font-size: 12px;
        color: #6c757d;
    }
</style>
@endpush

@section('content')
<!-- Welcome Card -->
<div class="row mb-4">
    <div class="col-12">
        <div class="welcome-card">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3>Selamat Datang, {{ Auth::guard('user')->user()->name }}! 👋</h3>
                    <p>Sistem Presensi YPI Al Azhar - Monitoring Real-time Presensi Karyawan</p>
                    <p class="mb-0"><small>{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</small></p>
                </div>
                <div class="col-md-4 text-end">
                    <button class="btn btn-light quick-action-btn me-2">
                        <i class="mdi mdi-file-chart"></i> Laporan
                    </button>
                    <button class="btn btn-warning quick-action-btn">
                        <i class="mdi mdi-cog"></i> Pengaturan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Master Data Statistics -->
<div class="row mb-4">
    <div class="col-12">
        <h5 class="mb-3"><i class="mdi mdi-database me-2"></i>Master Data</h5>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card">
            <div class="card-body text-center">
                <div class="stat-icon" style="background: rgba(0, 83, 197, 0.1);">
                    <i class="mdi mdi-office-building" style="color: #0053C5;"></i>
                </div>
                <div class="stat-number">{{ $totalCabang }}</div>
                <p class="stat-label">Total Cabang</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card">
            <div class="card-body text-center">
                <div class="stat-icon" style="background: rgba(40, 167, 69, 0.1);">
                    <i class="mdi mdi-file-tree" style="color: #28a745;"></i>
                </div>
                <div class="stat-number">{{ $totalDepartemen }}</div>
                <p class="stat-label">Total Departemen</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card">
            <div class="card-body text-center">
                <div class="stat-icon" style="background: rgba(23, 162, 184, 0.1);">
                    <i class="mdi mdi-account-group" style="color: #17a2b8;"></i>
                </div>
                <div class="stat-number">{{ $totalKaryawan }}</div>
                <p class="stat-label">Total Karyawan</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card">
            <div class="card-body text-center">
                <div class="stat-icon" style="background: rgba(255, 193, 7, 0.1);">
                    <i class="mdi mdi-clock-outline" style="color: #ffc107;"></i>
                </div>
                <div class="stat-number">{{ $totalJamKerja }}</div>
                <p class="stat-label">Total Jam Kerja</p>
            </div>
        </div>
    </div>
</div>

<!-- Presensi Hari Ini -->
<div class="row mb-4">
    <div class="col-12">
        <h5 class="mb-3"><i class="mdi mdi-calendar-today me-2"></i>Presensi Hari Ini</h5>
    </div>

    <!-- Presensi GPS -->
    <div class="col-md-6 mb-3">
        <div class="card table-card">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="mdi mdi-map-marker-check text-primary me-2"></i>Presensi GPS</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 mb-3">
                        <div class="mini-stat">
                            <i class="mdi mdi-account-check text-success" style="font-size: 24px;"></i>
                            <div class="mini-stat-number text-success">{{ $hadirGPS }}</div>
                            <div class="mini-stat-label">Hadir</div>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="mini-stat">
                            <i class="mdi mdi-account-clock text-warning" style="font-size: 24px;"></i>
                            <div class="mini-stat-number text-warning">{{ $terlambatGPS }}</div>
                            <div class="mini-stat-label">Terlambat</div>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="mini-stat">
                            <i class="mdi mdi-file-document text-info" style="font-size: 24px;"></i>
                            <div class="mini-stat-number text-info">{{ $izinGPS }}</div>
                            <div class="mini-stat-label">Izin/Sakit</div>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="mini-stat">
                            <i class="mdi mdi-format-list-checks text-primary" style="font-size: 24px;"></i>
                            <div class="mini-stat-number text-primary">{{ $presensiGPSHariIni }}</div>
                            <div class="mini-stat-label">Total Presensi</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Presensi Face -->
    <div class="col-md-6 mb-3">
        <div class="card table-card">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="mdi mdi-face-recognition text-success me-2"></i>Presensi Face Recognition</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 mb-3">
                        <div class="mini-stat">
                            <i class="mdi mdi-login text-success" style="font-size: 24px;"></i>
                            <div class="mini-stat-number text-success">{{ $checkInFace }}</div>
                            <div class="mini-stat-label">Check In</div>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="mini-stat">
                            <i class="mdi mdi-logout text-danger" style="font-size: 24px;"></i>
                            <div class="mini-stat-number text-danger">{{ $checkOutFace }}</div>
                            <div class="mini-stat-label">Check Out</div>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="mini-stat">
                            <i class="mdi mdi-check-circle text-success" style="font-size: 24px;"></i>
                            <div class="mini-stat-number text-success">{{ $verifiedFace }}</div>
                            <div class="mini-stat-label">Terverifikasi</div>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="mini-stat">
                            <i class="mdi mdi-close-circle text-danger" style="font-size: 24px;"></i>
                            <div class="mini-stat-number text-danger">{{ $failedFace }}</div>
                            <div class="mini-stat-label">Gagal</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Verifikasi Wajah -->
<div class="row mb-4">
    <div class="col-12">
        <h5 class="mb-3"><i class="mdi mdi-account-check me-2"></i>Status Verifikasi Wajah</h5>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card">
            <div class="card-body text-center">
                <div class="stat-icon" style="background: rgba(40, 167, 69, 0.1);">
                    <i class="mdi mdi-check-all" style="color: #28a745;"></i>
                </div>
                <div class="stat-number text-success">{{ $enrolledActive }}</div>
                <p class="stat-label">Enrolled Aktif</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card">
            <div class="card-body text-center">
                <div class="stat-icon" style="background: rgba(108, 117, 125, 0.1);">
                    <i class="mdi mdi-cancel" style="color: #6c757d;"></i>
                </div>
                <div class="stat-number text-secondary">{{ $enrolledInactive }}</div>
                <p class="stat-label">Enrolled Nonaktif</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card">
            <div class="card-body text-center">
                <div class="stat-icon" style="background: rgba(220, 53, 69, 0.1);">
                    <i class="mdi mdi-alert-circle" style="color: #dc3545;"></i>
                </div>
                <div class="stat-number text-danger">{{ $belumEnroll }}</div>
                <p class="stat-label">Belum Enroll</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card">
            <div class="card-body text-center">
                <div class="stat-icon" style="background: rgba(23, 162, 184, 0.1);">
                    <i class="mdi mdi-account-multiple-check" style="color: #17a2b8;"></i>
                </div>
                <div class="stat-number text-info">{{ $totalEnrolled }}</div>
                <p class="stat-label">Total Enrolled</p>
                <div class="progress progress-thin mt-2">
                    <!-- <div class="progress-bar bg-info" style="width: {{ $totalKaryawan > 0 ? ($totalEnrolled / $totalKaryawan * 100) : 0 }}%"></div> -->
                </div>
                <small class="text-muted">{{ $totalKaryawan > 0 ? number_format(($totalEnrolled / $totalKaryawan * 100), 1) : 0 }}% dari total karyawan</small>
                <hr class="my-2">
                <div class="d-flex justify-content-around mt-2">
                    <div>
                        <small class="text-muted d-block">Avg Enrollment</small>
                        <strong class="text-info">{{ number_format($avgEnrollmentCount ?? 0, 1) }}x</strong>
                    </div>
                    <div>
                        <small class="text-muted d-block">High Quality</small>
                        <strong class="text-success">{{ $highQualityEnrollment ?? 0 }}</strong>
                    </div>
                    <div>
                        <small class="text-muted d-block">7 Hari Terakhir</small>
                        <strong class="text-primary">{{ $recentEnrollment ?? 0 }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart 7 Hari Terakhir -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card chart-card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="mdi mdi-chart-line me-2"></i>Grafik Presensi 7 Hari Terakhir</h6>
                <small class="text-muted">{{ \Carbon\Carbon::now()->subDays(6)->format('d M') }} - {{ \Carbon\Carbon::now()->format('d M Y') }}</small>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="presensiChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ranking & Karyawan Terlambat -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card table-card">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="mdi mdi-trophy text-warning me-2"></i>Top 5 Cabang</h6>
                <small class="text-muted">Berdasarkan presensi bulan ini</small>
            </div>
            <div class="card-body">
                @forelse($rankingCabang as $index => $item)
                <div class="ranking-item">
                    <div class="d-flex align-items-center">
                        <div class="ranking-number {{ $index == 0 ? 'gold' : ($index == 1 ? 'silver' : ($index == 2 ? 'bronze' : 'other')) }}">
                            {{ $index + 1 }}
                        </div>
                        <div class="ms-3">
                            <strong>{{ $item->nama_cabang }}</strong>
                        </div>
                    </div>
                    <span class="badge bg-primary">{{ $item->total_presensi }}</span>
                </div>
                @empty
                <p class="text-muted text-center mb-0">Tidak ada data</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card table-card">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="mdi mdi-star text-success me-2"></i>Top 5 Departemen</h6>
                <small class="text-muted">Berdasarkan presensi bulan ini</small>
            </div>
            <div class="card-body">
                @forelse($rankingDepartemen as $index => $item)
                <div class="ranking-item">
                    <div class="d-flex align-items-center">
                        <div class="ranking-number {{ $index == 0 ? 'gold' : ($index == 1 ? 'silver' : ($index == 2 ? 'bronze' : 'other')) }}">
                            {{ $index + 1 }}
                        </div>
                        <div class="ms-3">
                            <strong>{{ $item->nama_dept }}</strong>
                        </div>
                    </div>
                    <span class="badge bg-success">{{ $item->total_presensi }}</span>
                </div>
                @empty
                <p class="text-muted text-center mb-0">Tidak ada data</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card table-card">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="mdi mdi-clock-alert text-danger me-2"></i>Karyawan Terlambat</h6>
                <small class="text-muted">Hari ini</small>
            </div>
            <div class="card-body">
                @forelse($karyawanTerlambat as $item)
                <div class="ranking-item">
                    <div>
                        <strong>{{ $item->nama_lengkap }}</strong><br>
                        <small class="text-muted">{{ $item->nama_cabang }}</small>
                    </div>
                    <span class="badge bg-danger">{{ $item->jam_in }}</span>
                </div>
                @empty
                <p class="text-muted text-center mb-0">
                    <i class="mdi mdi-emoticon-happy-outline" style="font-size: 48px;"></i><br>
                    Tidak ada keterlambatan
                </p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Data Presensi Terbaru -->
<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="card table-card">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="mdi mdi-map-marker-check text-primary me-2"></i>Presensi GPS Terbaru</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Cabang</th>
                                <th>Jam Masuk</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($presensiGPSTerbaru as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->nama_lengkap }}</strong><br>
                                    <small class="text-muted">{{ $item->jabatan }}</small>
                                </td>
                                <td><small>{{ $item->nama_cabang }}</small></td>
                                <td><small>{{ $item->jam_in }}</small></td>
                                <td>
                                    @if($item->status == 'h')
                                    <span class="status-badge bg-success text-white">Hadir</span>
                                    @elseif($item->status == 'i')
                                    <span class="status-badge bg-info text-white">Izin</span>
                                    @elseif($item->status == 's')
                                    <span class="status-badge bg-warning text-dark">Sakit</span>
                                    @else
                                    <span class="status-badge bg-secondary text-white">{{ $item->status }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Tidak ada data presensi</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="card table-card">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="mdi mdi-face-recognition text-success me-2"></i>Presensi Face Terbaru</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Cabang</th>
                                <th>Waktu</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($presensiFaceTerbaru as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->nama_lengkap }}</strong><br>
                                    <small class="text-muted">{{ $item->jabatan }}</small>
                                </td>
                                <td><small>{{ $item->nama_cabang }}</small></td>
                                <td>
                                    <small>
                                        @if($item->jam_masuk)
                                        In: {{ $item->jam_masuk }}
                                        @endif
                                        @if($item->jam_pulang)
                                        <br>Out: {{ $item->jam_pulang }}
                                        @endif
                                    </small>
                                </td>
                                <td>
                                    @if($item->status == 'verified')
                                    <span class="status-badge bg-success text-white">
                                        <i class="mdi mdi-check-circle"></i> Verified
                                    </span>
                                    @else
                                    <span class="status-badge bg-danger text-white">
                                        <i class="mdi mdi-close-circle"></i> Failed
                                    </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Tidak ada data presensi</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chart Configuration
        const ctx = document.getElementById('presensiChart');

        if (!ctx) {
            console.error('Canvas element not found');
            return;
        }

        // Parse data dengan benar
        // const labels = @json($last7Days ?? []);
        // const gpsData = @json($presensiGPSChart ?? []);
        // const faceData = @json($presensiFaceChart ?? []);

        // Check if data exists
        if (!labels.length || !gpsData.length || !faceData.length) {
            console.warn('Chart data is empty');
            ctx.parentElement.innerHTML = '<div class="text-center py-5"><i class="mdi mdi-chart-line text-muted" style="font-size: 48px;"></i><p class="text-muted mt-2">Tidak ada data untuk ditampilkan</p></div>';
            return;
        }

        try {
            const presensiChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Presensi GPS',
                        data: gpsData,
                        borderColor: '#0053C5',
                        backgroundColor: 'rgba(0, 83, 197, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#0053C5',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointHoverBackgroundColor: '#0053C5',
                        pointHoverBorderColor: '#fff',
                        pointHoverBorderWidth: 3
                    }, {
                        label: 'Presensi Face',
                        data: faceData,
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#28a745',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointHoverBackgroundColor: '#28a745',
                        pointHoverBorderColor: '#fff',
                        pointHoverBorderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    size: 14,
                                    weight: '600',
                                    family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif"
                                },
                                color: '#333'
                            }
                        },
                        tooltip: {
                            enabled: true,
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: {
                                size: 14,
                                weight: 'bold',
                                family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif"
                            },
                            bodyFont: {
                                size: 13,
                                family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif"
                            },
                            borderColor: 'rgba(255, 255, 255, 0.2)',
                            borderWidth: 1,
                            displayColors: true,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += context.parsed.y + ' presensi';
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: Math.max(...gpsData, ...faceData) > 50 ? 10 : 5,
                                font: {
                                    size: 12,
                                    family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif"
                                },
                                color: '#666',
                                callback: function(value) {
                                    if (Number.isInteger(value)) {
                                        return value;
                                    }
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: false
                            },
                            border: {
                                display: false
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    size: 12,
                                    family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif"
                                },
                                color: '#666'
                            },
                            grid: {
                                display: false
                            },
                            border: {
                                display: false
                            }
                        }
                    }
                }
            });

            console.log('Chart created successfully');
        } catch (error) {
            console.error('Error creating chart:', error);
            ctx.parentElement.innerHTML = '<div class="text-center py-5"><i class="mdi mdi-alert-circle text-danger" style="font-size: 48px;"></i><p class="text-danger mt-2">Error membuat grafik</p></div>';
        }
    });
</script>
@endpush