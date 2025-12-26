@extends('admin.layouts.admin')

@section('title', 'Monitoring Presensi')
@section('page-title', 'Real-Time Monitoring Presensi Face Recognition')

@push('styles')
<style>
    /* Stats Card */
    .stats-card {
        border-radius: 10px;
        padding: 1.5rem;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .stats-card::before {
        content: '';
        position: absolute;
        right: -20px;
        top: -20px;
        width: 100px;
        height: 100px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .stats-card .icon {
        font-size: 2.5rem;
        opacity: 0.3;
        position: absolute;
        right: 1rem;
        top: 1rem;
    }

    .stats-card .number {
        font-size: 2rem;
        font-weight: bold;
        margin: 0;
    }

    .stats-card .label {
        font-size: 0.875rem;
        opacity: 0.9;
        margin: 0;
    }

    /* Monitoring Table */
    .monitoring-table {
        font-size: 0.875rem;
    }

    .monitoring-table td {
        padding: 0.75rem 0.5rem;
        vertical-align: middle;
    }

    /* Pulse Animation */
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    .live-indicator {
        display: inline-block;
        width: 10px;
        height: 10px;
        background: #28a745;
        border-radius: 50%;
        margin-right: 0.5rem;
        animation: pulse 2s infinite;
    }

    /* Shift Badge */
    .shift-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }

    .shift-badge.shift-1 { background-color: #e3f2fd; color: #1565c0; }
    .shift-badge.shift-2 { background-color: #f3e5f5; color: #6a1b9a; }
    .shift-badge.shift-3 { background-color: #e8f5e9; color: #2e7d32; }
    .shift-badge.shift-4 { background-color: #fff3e0; color: #e65100; }
    .shift-badge.shift-5 { background-color: #fce4ec; color: #c2185b; }

    /* Auto Refresh */
    .refresh-timer {
        font-size: 0.75rem;
        color: #6c757d;
    }
</style>
@endpush

@section('content')
<!-- Header -->
<div class="row mb-3">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="mb-0">
                    <span class="live-indicator"></span>
                    Live Monitoring
                </h4>
                <small class="text-muted">Data diupdate otomatis setiap 30 detik</small>
            </div>
            <div>
                <button type="button" class="btn btn-success btn-sm" id="refreshBtn">
                    <i class="mdi mdi-refresh"></i> Refresh
                </button>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">
                        <i class="mdi mdi-filter"></i> Filter Monitoring
                    </h6>
                    <button class="btn btn-sm btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                        <i class="mdi mdi-chevron-down"></i> Toggle
                    </button>
                </div>
                
                <div class="collapse show" id="filterCollapse">
                    <form method="GET" id="filterForm">
                        <div class="row g-2">
                            <div class="col-md-2">
                                <label class="form-label small">Tanggal</label>
                                <input type="date" name="tanggal" class="form-control form-control-sm" 
                                    value="{{ $tanggal }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small">NIK</label>
                                <input type="text" name="nik" class="form-control form-control-sm" 
                                    value="{{ request('nik') }}" placeholder="Cari NIK...">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small">Cabang</label>
                                <select name="kode_cabang" class="form-select form-select-sm">
                                    <option value="">Semua Cabang</option>
                                    @if(isset($cabang))
                                    @foreach($cabang as $item)
                                    <option value="{{ $item->kode_cabang }}" {{ request('kode_cabang') == $item->kode_cabang ? 'selected' : '' }}>
                                        {{ $item->nama_cabang }}
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small">Departemen</label>
                                <select name="kode_dept" class="form-select form-select-sm">
                                    <option value="">Semua Departemen</option>
                                    @if(isset($departemen))
                                    @foreach($departemen as $item)
                                    <option value="{{ $item->kode_dept }}" {{ request('kode_dept') == $item->kode_dept ? 'selected' : '' }}>
                                        {{ $item->nama_dept }}
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small">Status</label>
                                <select name="status" class="form-select form-select-sm">
                                    <option value="">Semua Status</option>
                                    <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small">Shift Ke</label>
                                <select name="shift_ke" class="form-select form-select-sm">
                                    <option value="">Semua Shift</option>
                                    @if(isset($availableShifts))
                                    @foreach($availableShifts as $shift)
                                    <option value="{{ $shift->shift_ke }}" {{ request('shift_ke') == $shift->shift_ke ? 'selected' : '' }}>
                                        Shift {{ $shift->shift_ke }} - {{ $shift->nama_shift }}
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="mdi mdi-magnify"></i> Cari
                                </button>
                                <a href="{{ route('panel.presensi-face.monitoring') }}" class="btn btn-secondary btn-sm">
                                    <i class="mdi mdi-refresh"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <i class="mdi mdi-account-multiple icon"></i>
            <p class="number">{{ $stats['total'] }}</p>
            <p class="label">Total Presensi</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <i class="mdi mdi-check-circle icon"></i>
            <p class="number">{{ $stats['verified'] }}</p>
            <p class="label">Verified</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <i class="mdi mdi-layers icon"></i>
            <p class="number">{{ $stats['multi_shift'] }}</p>
            <p class="label">Multi-Shift</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
            <i class="mdi mdi-clock-outline icon"></i>
            <p class="number">{{ $stats['regular'] }}</p>
            <p class="label">Regular</p>
        </div>
    </div>
</div>

<!-- Shift Breakdown (if multi-shift exists) -->
@if($stats['multi_shift'] > 0 && count($stats['by_shift']) > 0)
<div class="card mb-4">
    <div class="card-header bg-light">
        <h6 class="mb-0">
            <i class="mdi mdi-chart-bar"></i> Breakdown Per Shift
        </h6>
    </div>
    <div class="card-body">
        <div class="row">
            @foreach($stats['by_shift'] as $shiftKe => $shiftData)
            <div class="col-md-2">
                <div class="text-center p-3 border rounded">
                    <div class="h2 mb-1 shift-badge shift-{{ $shiftKe }}" style="display: inline-block; padding: 0.5rem 1rem;">
                        {{ $shiftData['count'] }}
                    </div>
                    <div class="small text-muted">
                        <i class="mdi mdi-numeric-{{ $shiftKe }}-box"></i>
                        {{ $shiftData['nama'] }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Data Table -->
<div class="card">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h6 class="mb-0">
            <i class="mdi mdi-table"></i> Data Presensi - {{ \Carbon\Carbon::parse($tanggal)->isoFormat('dddd, D MMMM Y') }}
        </h6>
        <span class="refresh-timer">
            <i class="mdi mdi-clock-outline"></i>
            Auto refresh dalam: <strong id="countdown">30</strong>s
        </span>
    </div>
    <div class="card-body p-0">
        <div id="monitoringTableContainer">
            @include('admin.presensi-face.monitoring-table', ['presensi' => $presensi])
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let countdownTimer = 30;
    let refreshInterval;
    let countdownInterval;

    // Auto refresh function
    function autoRefresh() {
        const tanggal = '{{ $tanggal }}';
        
        $.ajax({
            url: '{{ route("panel.presensi-face.monitoring") }}',
            type: 'GET',
            data: { tanggal: tanggal },
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                $('#monitoringTableContainer').html(response);
                console.log('Data refreshed at:', new Date().toLocaleTimeString());
            },
            error: function(xhr) {
                console.error('Refresh failed:', xhr);
            }
        });
    }

    // Countdown timer
    function startCountdown() {
        countdownTimer = 30;
        $('#countdown').text(countdownTimer);
        
        countdownInterval = setInterval(function() {
            countdownTimer--;
            $('#countdown').text(countdownTimer);
            
            if (countdownTimer <= 0) {
                countdownTimer = 30;
            }
        }, 1000);
    }

    // Manual refresh button
    $('#refreshBtn').on('click', function() {
        $(this).prop('disabled', true);
        $(this).html('<i class="mdi mdi-loading mdi-spin"></i> Refreshing...');
        
        autoRefresh();
        
        setTimeout(function() {
            $('#refreshBtn').prop('disabled', false);
            $('#refreshBtn').html('<i class="mdi mdi-refresh"></i> Refresh');
            
            // Reset countdown
            clearInterval(countdownInterval);
            clearInterval(refreshInterval);
            countdownTimer = 30;
            startCountdown();
            refreshInterval = setInterval(autoRefresh, 30000);
        }, 1000);
    });

    // Start auto refresh on page load
    $(document).ready(function() {
        startCountdown();
        refreshInterval = setInterval(autoRefresh, 30000); // 30 seconds
    });

    // Clear intervals on page unload
    $(window).on('beforeunload', function() {
        clearInterval(refreshInterval);
        clearInterval(countdownInterval);
    });
</script>
@endpush