@extends('admin.layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    /* Reset & Base Dashboard Layout */
    .dashboard-container {
        padding-bottom: 40px;
        background: var(--bg-app);
    }

    /* ============================
       HERO BANNER
    ============================ */
    .hero-banner {
        background: #ffffff;
        border: 1px solid #f3f4f6;
        padding: 40px 32px;
        position: relative;
        overflow: hidden;
        border-radius: 20px;
        margin-bottom: 32px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        display: flex;
        align-items: center;
    }

    .hero-icon-bg {
        width: 80px;
        height: 80px;
        background: #eff6ff;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 24px;
    }

    .hero-icon-bg i {
        font-size: 40px;
        color: #0053C5;
    }

    .hero-banner::before {
        content: '';
        position: absolute;
        top: -50px; right: -50px;
        width: 250px; height: 250px;
        background: radial-gradient(circle, rgba(0,83,197,0.03) 0%, transparent 60%);
        border-radius: 50%;
    }
    .hero-banner::after {
        content: '';
        position: absolute;
        bottom: -80px; left: -20px;
        width: 200px; height: 200px;
        border: 2px solid rgba(0,83,197,0.02);
        border-radius: 50%;
    }
    .hero-text {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex: 1;
    }
    .hero-text-left h1 {
        font-size: 26px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 8px;
        letter-spacing: -0.02em;
    }
    .hero-text-left p {
        font-size: 14px;
        color: #6b7280;
        margin: 0;
    }
    .hero-actions .btn {
        border-radius: 10px;
        font-weight: 600;
        padding: 10px 20px;
        margin-left: 10px;
    }

    /* ============================
       SECTION TITLE
    ============================ */
    .section-title {
        font-size: 18px;
        font-weight: 800;
        color: #111827;
        margin: 0 0 16px 0;
        letter-spacing: -0.01em;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .section-title i {
        color: #0053C5;
    }

    /* ============================
       CATEGORY GRID (Master Data)
    ============================ */
    .category-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 32px;
    }
    @media (max-width: 992px) {
        .category-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    .cat-card {
        background: #ffffff;
        border: 1px solid #f3f4f6;
        border-radius: 16px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        transition: transform 0.2s;
    }
    .cat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.06);
    }
    .cat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }
    .cat-text {
        display: flex;
        flex-direction: column;
    }
    .cat-val {
        font-size: 20px;
        font-weight: 800;
        color: #111827;
    }
    .cat-title {
        font-size: 13px;
        color: #6b7280;
        font-weight: 500;
    }

    /* ============================
       STATUS CARDS (Presensi)
    ============================ */
    .status-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 32px;
    }
    @media (max-width: 992px) {
        .status-grid {
            grid-template-columns: 1fr;
        }
    }
    .status-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #f3f4f6;
        padding: 24px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    }
    .status-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px dashed #e5e7eb;
    }
    .status-header h6 {
        margin: 0;
        font-weight: 700;
        color: #111827;
        font-size: 16px;
    }
    .mini-stat-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
    }
    @media (max-width: 576px) {
        .mini-stat-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    .mini-stat {
        text-align: center;
        padding: 16px 12px;
        border-radius: 12px;
        background: #f9fafb;
        transition: background 0.2s;
    }
    .mini-stat:hover {
        background: #f3f4f6;
    }
    .mini-stat i {
        font-size: 24px;
        margin-bottom: 8px;
        display: block;
    }
    .mini-stat-number {
        font-size: 18px;
        font-weight: 800;
        margin-bottom: 4px;
    }
    .mini-stat-label {
        font-size: 11px;
        color: #6b7280;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    /* ============================
       VERTICAL STACK (Leaderboards)
    ============================ */
    .vertical-stack-wrapper {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 32px;
    }
    @media (max-width: 992px) {
        .vertical-stack-wrapper {
            grid-template-columns: 1fr;
        }
    }
    .vertical-list-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #f3f4f6;
        padding: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    }
    .vertical-list-card h6 {
        margin: 0 0 16px 0;
        font-weight: 700;
        color: #111827;
        font-size: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .v-card {
        display: flex;
        background: #ffffff;
        border: 1px solid #f3f4f6;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 12px;
        transition: transform 0.2s;
    }
    .v-card:last-child {
        margin-bottom: 0;
    }
    .v-card:hover {
        transform: translateX(4px);
        border-color: #e5e7eb;
    }
    .v-img {
        width: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: 800;
        color: white;
    }
    .v-img.gold { background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); }
    .v-img.silver { background: linear-gradient(135deg, #9CA3AF 0%, #6B7280 100%); }
    .v-img.bronze { background: linear-gradient(135deg, #D97706 0%, #92400E 100%); }
    .v-img.other { background: #f3f4f6; color: #6b7280; }
    
    .v-content {
        padding: 12px;
        flex: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .v-title {
        font-size: 14px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 2px;
    }
    .v-subtitle {
        font-size: 12px;
        color: #6b7280;
    }
    .v-badge {
        padding: 4px 8px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
    }

    /* ============================
       TABLE CARD
    ============================ */
    .table-responsive-custom {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #f3f4f6;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        overflow: hidden;
    }
    .table-responsive-custom table {
        margin: 0;
    }
    .table-responsive-custom th {
        background: #f9fafb;
        color: #6b7280;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        padding: 16px;
        border-bottom: 1px solid #e5e7eb;
    }
    .table-responsive-custom td {
        padding: 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f3f4f6;
    }
    .table-responsive-custom tr:last-child td {
        border-bottom: none;
    }
    .user-td {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .user-td-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #eff6ff;
        color: #0053C5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }
    .status-badge-custom {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .status-badge-custom.success { background: #dcfce7; color: #166534; }
    .status-badge-custom.danger { background: #fee2e2; color: #991b1b; }
    .status-badge-custom.warning { background: #fef3c7; color: #92400e; }
    .status-badge-custom.info { background: #dbeafe; color: #1e40af; }

    /* Chart Container */
    .chart-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #f3f4f6;
        padding: 24px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        margin-bottom: 32px;
    }
</style>
@endpush

@section('content')
<div class="dashboard-container">
    
    <!-- HERO BANNER -->
    <div class="hero-banner">
        <div class="hero-icon-bg">
            <i class="mdi mdi-hand-wave"></i>
        </div>
        <div class="hero-text">
            <div class="hero-text-left">
                <h1>Selamat Datang, {{ Auth::guard('user')->user()->name }}!</h1>
                <p>Sistem Presensi YPI Al Azhar - {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</p>
            </div>
            <div class="hero-actions d-none d-md-block">
                <button class="btn btn-primary"><i class="mdi mdi-file-chart me-1"></i> Laporan</button>
            </div>
        </div>
    </div>

    <!-- MASTER DATA -->
    <h3 class="section-title"><i class="mdi mdi-database"></i> Master Data</h3>
    <div class="category-grid">
        <div class="cat-card">
            <div class="cat-icon" style="background: #eff6ff; color: #0053C5;"><i class="mdi mdi-office-building"></i></div>
            <div class="cat-text">
                <span class="cat-val">{{ $totalCabang }}</span>
                <span class="cat-title">Total Cabang</span>
            </div>
        </div>
        <div class="cat-card">
            <div class="cat-icon" style="background: #dcfce7; color: #15803d;"><i class="mdi mdi-file-tree"></i></div>
            <div class="cat-text">
                <span class="cat-val">{{ $totalDepartemen }}</span>
                <span class="cat-title">Total Departemen</span>
            </div>
        </div>
        <div class="cat-card">
            <div class="cat-icon" style="background: #f3e8ff; color: #7e22ce;"><i class="mdi mdi-account-group"></i></div>
            <div class="cat-text">
                <span class="cat-val">{{ $totalKaryawan }}</span>
                <span class="cat-title">Total Karyawan</span>
            </div>
        </div>
        <div class="cat-card">
            <div class="cat-icon" style="background: #fef3c7; color: #b45309;"><i class="mdi mdi-clock-outline"></i></div>
            <div class="cat-text">
                <span class="cat-val">{{ $totalJamKerja }}</span>
                <span class="cat-title">Total Jam Kerja</span>
            </div>
        </div>
    </div>

    <!-- PRESENSI HARI INI -->
    <h3 class="section-title"><i class="mdi mdi-calendar-today"></i> Presensi Hari Ini</h3>
    <div class="status-grid">
        <!-- GPS -->
        <div class="status-card">
            <div class="status-header">
                <h6><i class="mdi mdi-map-marker-check text-primary me-2"></i>Presensi GPS</h6>
                <span class="badge bg-primary rounded-pill">{{ $presensiGPSHariIni }} Total</span>
            </div>
            <div class="mini-stat-grid">
                <div class="mini-stat">
                    <i class="mdi mdi-account-check text-success"></i>
                    <div class="mini-stat-number text-success">{{ $hadirGPS }}</div>
                    <div class="mini-stat-label">Hadir</div>
                </div>
                <div class="mini-stat">
                    <i class="mdi mdi-account-clock text-warning"></i>
                    <div class="mini-stat-number text-warning">{{ $terlambatGPS }}</div>
                    <div class="mini-stat-label">Terlambat</div>
                </div>
                <div class="mini-stat">
                    <i class="mdi mdi-file-document text-info"></i>
                    <div class="mini-stat-number text-info">{{ $izinGPS }}</div>
                    <div class="mini-stat-label">Izin/Sakit</div>
                </div>
                <div class="mini-stat">
                    <i class="mdi mdi-close-octagon text-danger"></i>
                    <div class="mini-stat-number text-danger">{{ $totalKaryawan - $presensiGPSHariIni }}</div>
                    <div class="mini-stat-label">Absen</div>
                </div>
            </div>
        </div>

        <!-- Face -->
        <div class="status-card">
            <div class="status-header">
                <h6><i class="mdi mdi-face-recognition text-success me-2"></i>Presensi Face Recognition</h6>
                <span class="badge bg-success rounded-pill">{{ $presensiFaceHariIni }} Total</span>
            </div>
            <div class="mini-stat-grid">
                <div class="mini-stat">
                    <i class="mdi mdi-login text-success"></i>
                    <div class="mini-stat-number text-success">{{ $checkInFace }}</div>
                    <div class="mini-stat-label">Check In</div>
                </div>
                <div class="mini-stat">
                    <i class="mdi mdi-logout text-danger"></i>
                    <div class="mini-stat-number text-danger">{{ $checkOutFace }}</div>
                    <div class="mini-stat-label">Check Out</div>
                </div>
                <div class="mini-stat">
                    <i class="mdi mdi-check-circle text-primary"></i>
                    <div class="mini-stat-number text-primary">{{ $verifiedFace }}</div>
                    <div class="mini-stat-label">Terverifikasi</div>
                </div>
                <div class="mini-stat">
                    <i class="mdi mdi-close-circle text-warning"></i>
                    <div class="mini-stat-number text-warning">{{ $failedFace }}</div>
                    <div class="mini-stat-label">Gagal</div>
                </div>
            </div>
        </div>
    </div>

    <!-- VERIFIKASI WAJAH STATUS -->
    <h3 class="section-title"><i class="mdi mdi-shield-account"></i> Status Face Enrollment</h3>
    <div class="category-grid">
        <div class="cat-card">
            <div class="cat-icon" style="background: #eff6ff; color: #0053C5;"><i class="mdi mdi-account-multiple-check"></i></div>
            <div class="cat-text">
                <span class="cat-val">{{ $totalEnrolled }}</span>
                <span class="cat-title">Total Enrolled ({{ $totalKaryawan > 0 ? number_format(($totalEnrolled / $totalKaryawan * 100), 1) : 0 }}%)</span>
            </div>
        </div>
        <div class="cat-card">
            <div class="cat-icon" style="background: #dcfce7; color: #15803d;"><i class="mdi mdi-check-all"></i></div>
            <div class="cat-text">
                <span class="cat-val">{{ $enrolledActive }}</span>
                <span class="cat-title">Enrolled Aktif</span>
            </div>
        </div>
        <div class="cat-card">
            <div class="cat-icon" style="background: #f3f4f6; color: #6b7280;"><i class="mdi mdi-cancel"></i></div>
            <div class="cat-text">
                <span class="cat-val">{{ $enrolledInactive }}</span>
                <span class="cat-title">Enrolled Nonaktif</span>
            </div>
        </div>
        <div class="cat-card">
            <div class="cat-icon" style="background: #fee2e2; color: #b91c1c;"><i class="mdi mdi-alert-circle"></i></div>
            <div class="cat-text">
                <span class="cat-val">{{ $belumEnroll }}</span>
                <span class="cat-title">Belum Enroll</span>
            </div>
        </div>
    </div>

    <!-- CHART 7 HARI TERAKHIR -->
    <div class="chart-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h6 class="m-0 fw-bold"><i class="mdi mdi-chart-line text-primary me-2"></i>Grafik Presensi 7 Hari Terakhir</h6>
            <small class="text-muted">{{ \Carbon\Carbon::now()->subDays(6)->format('d M') }} - {{ \Carbon\Carbon::now()->format('d M Y') }}</small>
        </div>
        <div style="position: relative; height: 300px; width: 100%;">
            <canvas id="presensiChart"></canvas>
        </div>
    </div>

    <!-- LEADERBOARDS & TERLAMBAT -->
    <div class="vertical-stack-wrapper">
        <!-- Top Cabang -->
        <div class="vertical-list-card">
            <h6><i class="mdi mdi-trophy text-warning"></i> Top 5 Cabang <small class="text-muted fw-normal ms-auto">Bulan ini</small></h6>
            @forelse($rankingCabang as $index => $item)
            <div class="v-card">
                <div class="v-img {{ $index == 0 ? 'gold' : ($index == 1 ? 'silver' : ($index == 2 ? 'bronze' : 'other')) }}">
                    {{ $index + 1 }}
                </div>
                <div class="v-content">
                    <div>
                        <div class="v-title">{{ $item->nama_cabang }}</div>
                    </div>
                    <div class="v-badge bg-primary text-white">{{ $item->total_presensi }}</div>
                </div>
            </div>
            @empty
            <p class="text-muted text-center py-4">Tidak ada data</p>
            @endforelse
        </div>

        <!-- Top Departemen -->
        <div class="vertical-list-card">
            <h6><i class="mdi mdi-star text-success"></i> Top 5 Departemen <small class="text-muted fw-normal ms-auto">Bulan ini</small></h6>
            @forelse($rankingDepartemen as $index => $item)
            <div class="v-card">
                <div class="v-img {{ $index == 0 ? 'gold' : ($index == 1 ? 'silver' : ($index == 2 ? 'bronze' : 'other')) }}">
                    {{ $index + 1 }}
                </div>
                <div class="v-content">
                    <div>
                        <div class="v-title">{{ $item->nama_dept }}</div>
                    </div>
                    <div class="v-badge bg-success text-white">{{ $item->total_presensi }}</div>
                </div>
            </div>
            @empty
            <p class="text-muted text-center py-4">Tidak ada data</p>
            @endforelse
        </div>

        <!-- Karyawan Terlambat -->
        <div class="vertical-list-card">
            <h6><i class="mdi mdi-clock-alert text-danger"></i> Karyawan Terlambat <small class="text-muted fw-normal ms-auto">Hari ini</small></h6>
            @forelse($karyawanTerlambat as $item)
            <div class="v-card">
                <div class="v-img bg-light text-danger">
                    <i class="mdi mdi-account"></i>
                </div>
                <div class="v-content">
                    <div>
                        <div class="v-title">{{ $item->nama_lengkap }}</div>
                        <div class="v-subtitle">{{ $item->nama_cabang }}</div>
                    </div>
                    <div class="v-badge bg-danger text-white">{{ $item->jam_in }}</div>
                </div>
            </div>
            @empty
            <div class="text-center py-4">
                <i class="mdi mdi-emoticon-happy-outline text-success" style="font-size: 48px;"></i>
                <p class="text-muted mt-2 m-0">Tidak ada keterlambatan</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- DATA TERBARU TABLES -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <h3 class="section-title"><i class="mdi mdi-map-marker-check"></i> GPS Terbaru</h3>
            <div class="table-responsive-custom">
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th>Karyawan</th>
                            <th>Jam Masuk</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($presensiGPSTerbaru as $item)
                        <tr>
                            <td>
                                <div class="user-td">
                                    <div class="user-td-icon"><i class="mdi mdi-account"></i></div>
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size: 14px;">{{ $item->nama_lengkap }}</div>
                                        <div class="text-muted" style="font-size: 12px;">{{ $item->nama_cabang }}</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="fw-semibold text-dark">{{ $item->jam_in }}</span></td>
                            <td>
                                @if($item->status == 'h')
                                <span class="status-badge-custom success"><i class="mdi mdi-check-circle"></i> Hadir</span>
                                @elseif($item->status == 'i')
                                <span class="status-badge-custom info"><i class="mdi mdi-information"></i> Izin</span>
                                @elseif($item->status == 's')
                                <span class="status-badge-custom warning"><i class="mdi mdi-hospital-box"></i> Sakit</span>
                                @else
                                <span class="status-badge-custom bg-light text-dark">{{ $item->status }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">Tidak ada data presensi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <h3 class="section-title"><i class="mdi mdi-face-recognition"></i> Face Terbaru</h3>
            <div class="table-responsive-custom">
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th>Karyawan</th>
                            <th>Waktu</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($presensiFaceTerbaru as $item)
                        <tr>
                            <td>
                                <div class="user-td">
                                    <div class="user-td-icon" style="background: #dcfce7; color: #15803d;"><i class="mdi mdi-face-recognition"></i></div>
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size: 14px;">{{ $item->nama_lengkap }}</div>
                                        <div class="text-muted" style="font-size: 12px;">{{ $item->nama_cabang }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($item->jam_masuk)
                                <div style="font-size: 13px;"><span class="text-success fw-bold">In:</span> {{ $item->jam_masuk }}</div>
                                @endif
                                @if($item->jam_pulang)
                                <div style="font-size: 13px;"><span class="text-danger fw-bold">Out:</span> {{ $item->jam_pulang }}</div>
                                @endif
                            </td>
                            <td>
                                @if($item->status == 'verified')
                                <span class="status-badge-custom success"><i class="mdi mdi-check-circle"></i> Verified</span>
                                @else
                                <span class="status-badge-custom danger"><i class="mdi mdi-close-circle"></i> Failed</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">Tidak ada data presensi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('presensiChart');
        if (!ctx) return;

        const labels = @json($last7Days ?? []);
        const gpsData = @json($presensiGPSChart ?? []);
        const faceData = @json($presensiFaceChart ?? []);

        if (!labels.length) return;

        new Chart(ctx, {
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
                    pointRadius: 4,
                }, {
                    label: 'Presensi Face',
                    data: faceData,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
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
                            boxWidth: 8,
                            padding: 20,
                            font: { family: "'Segoe UI', sans-serif", size: 13, weight: '600' }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f3f4f6', drawBorder: false },
                        border: { display: false }
                    },
                    x: {
                        grid: { display: false },
                        border: { display: false }
                    }
                }
            }
        });
    });
</script>
@endpush