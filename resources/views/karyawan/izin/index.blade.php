@extends('karyawan.layouts.presensi')

@section('content')

<style>
    /* ===== MODERN IZIN PAGE ===== */
    :root {
        --primary: #0053C5;
        --primary-dark: #003d94;
        --primary-light: #2E7CE6;
        --primary-gradient: linear-gradient(135deg, #0053C5 0%, #2E7CE6 100%);
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --info: #06b6d4;
        --bg-main: #f0f4f8;
        --bg-card: #ffffff;
        --text-primary: #1e293b;
        --text-secondary: #64748b;
    }

    body {
        background: var(--bg-main);
    }

    /* ===== PAGE HEADER ===== */
    .page-header {
        background: var(--primary-gradient);
        padding: 24px 20px 70px 20px;
        position: relative;
        overflow: hidden;
        margin: 0;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -40%;
        right: -15%;
        width: 250px;
        height: 250px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
        filter: blur(50px);
    }

    .header-content {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .btn-back {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-back ion-icon {
        font-size: 24px;
        color: white;
    }

    .btn-back:active {
        transform: scale(0.95);
        background: rgba(255, 255, 255, 0.25);
    }

    .header-title h1 {
        font-size: 22px;
        font-weight: 700;
        color: white;
        margin: 0 0 4px 0;
    }

    .header-title p {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.8);
        margin: 0;
    }

    .btn-add {
        width: 40px;
        height: 40px;
        background: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
    }

    .btn-add ion-icon {
        font-size: 24px;
        color: var(--primary);
    }

    .btn-add:active {
        transform: scale(0.95);
    }

    /* ===== STATS SECTION ===== */
    .stats-section {
        padding: 0 20px;
        margin-top: -55px;
        margin-bottom: 20px;
        position: relative;
        z-index: 10;
    }

    .stats-card {
        background: var(--bg-card);
        border-radius: 20px;
        padding: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 83, 197, 0.08);
    }

    .stats-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .stats-title ion-icon {
        font-size: 18px;
        color: var(--primary);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
    }

    .stat-item {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 12px;
        padding: 12px;
        text-align: center;
        border-left: 3px solid var(--stat-color);
    }

    .stat-value {
        font-size: 20px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 4px;
    }

    .stat-label {
        font-size: 10px;
        color: var(--text-secondary);
        font-weight: 600;
    }

    .stat-item.izin {
        --stat-color: var(--warning);
    }

    .stat-item.sakit {
        --stat-color: var(--info);
    }

    .stat-item.cuti {
        --stat-color: var(--success);
    }

    .stat-item.pending {
        --stat-color: var(--warning);
    }

    .stat-item.approved {
        --stat-color: var(--success);
    }

    .stat-item.rejected {
        --stat-color: var(--danger);
    }

    /* ===== LIST SECTION ===== */
    .list-section {
        padding: 0 20px 100px;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .section-title ion-icon {
        font-size: 20px;
        color: var(--primary);
    }

    .izin-card {
        background: var(--bg-card);
        border-radius: 16px;
        padding: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(0, 0, 0, 0.04);
        margin-bottom: 12px;
        border-left: 4px solid var(--status-color);
        transition: all 0.3s ease;
    }

    .izin-card:active {
        transform: translateX(4px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .izin-card.status-pending {
        --status-color: var(--warning);
    }

    .izin-card.status-approved {
        --status-color: var(--success);
    }

    .izin-card.status-rejected {
        --status-color: var(--danger);
    }

    .izin-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }

    .izin-type {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .type-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--icon-bg);
    }

    .type-icon ion-icon {
        font-size: 20px;
        color: white;
    }

    .izin-card.type-izin .type-icon {
        --icon-bg: linear-gradient(135deg, var(--warning) 0%, #fb923c 100%);
    }

    .izin-card.type-sakit .type-icon {
        --icon-bg: linear-gradient(135deg, var(--info) 0%, #0ea5e9 100%);
    }

    .izin-card.type-cuti .type-icon {
        --icon-bg: linear-gradient(135deg, var(--success) 0%, #34d399 100%);
    }

    .type-info h4 {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0 0 4px 0;
    }

    .type-info span {
        font-size: 11px;
        color: var(--text-secondary);
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 600;
        background: var(--badge-bg);
        color: var(--badge-color);
    }

    .izin-card.status-pending .status-badge {
        --badge-bg: #fef3c7;
        --badge-color: #d97706;
    }

    .izin-card.status-approved .status-badge {
        --badge-bg: #dcfce7;
        --badge-color: #16a34a;
    }

    .izin-card.status-rejected .status-badge {
        --badge-bg: #fee2e2;
        --badge-color: #dc2626;
    }

    .izin-dates {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
        padding: 10px;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 10px;
    }

    .date-item {
        flex: 1;
        text-align: center;
    }

    .date-label {
        font-size: 10px;
        color: var(--text-secondary);
        font-weight: 600;
        margin-bottom: 4px;
    }

    .date-value {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-primary);
    }

    .date-separator {
        color: var(--primary);
        font-size: 16px;
        font-weight: 700;
    }

    .izin-description {
        font-size: 12px;
        color: var(--text-secondary);
        line-height: 1.5;
        margin-bottom: 12px;
    }

    .izin-actions {
        display: flex;
        gap: 8px;
    }

    .btn-action {
        flex: 1;
        padding: 10px 16px;
        border: none;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-action ion-icon {
        font-size: 16px;
    }

    .btn-detail {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        color: var(--primary);
        border: 1px solid #e2e8f0;
    }

    .btn-detail:active {
        background: var(--primary);
        color: white;
        transform: scale(0.98);
    }

    .btn-delete {
        background: white;
        color: var(--danger);
        border: 1px solid var(--danger);
    }

    .btn-delete:active {
        background: var(--danger);
        color: white;
        transform: scale(0.98);
    }

    /* ===== EMPTY STATE ===== */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-secondary);
    }

    .empty-state ion-icon {
        font-size: 80px;
        color: #cbd5e1;
        margin-bottom: 16px;
    }

    .empty-state h3 {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 8px;
    }

    .empty-state p {
        font-size: 14px;
        margin-bottom: 20px;
    }

    .btn-empty-action {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: var(--primary-gradient);
        color: white;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(0, 83, 197, 0.3);
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 375px) {

        .stats-section,
        .list-section {
            padding-left: 16px;
            padding-right: 16px;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <div class="header-content">
        <div class="header-left">
            <a href="{{ route('dashboard') }}" class="btn-back">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
            <div class="header-title">
                <h1>Pengajuan Izin</h1>
                <p>Kelola izin, sakit & cuti</p>
            </div>
        </div>
        <a href="{{ route('izin.create') }}" class="btn-add">
            <ion-icon name="add"></ion-icon>
        </a>
    </div>
</div>

<!-- Stats Section -->
<div class="stats-section">
    <div class="stats-card">
        <div class="stats-title">
            <ion-icon name="bar-chart"></ion-icon>
            Statistik Tahun Ini
        </div>
        <div class="stats-grid" id="stats-grid">
            <div class="stat-item pending">
                <div class="stat-value">-</div>
                <div class="stat-label">Pending</div>
            </div>
            <div class="stat-item approved">
                <div class="stat-value">-</div>
                <div class="stat-label">Disetujui</div>
            </div>
            <div class="stat-item rejected">
                <div class="stat-value">-</div>
                <div class="stat-label">Ditolak</div>
            </div>
            <div class="stat-item izin">
                <div class="stat-value">-</div>
                <div class="stat-label">Izin</div>
            </div>
            <div class="stat-item sakit">
                <div class="stat-value">-</div>
                <div class="stat-label">Sakit</div>
            </div>
            <div class="stat-item cuti">
                <div class="stat-value">-</div>
                <div class="stat-label">Cuti</div>
            </div>
        </div>
    </div>
</div>

<!-- List Section -->
<div class="list-section">
    <div class="section-header">
        <div class="section-title">
            <ion-icon name="calendar"></ion-icon>
            Riwayat Pengajuan
        </div>
    </div>

    @if($dataizin->isEmpty())
    <div class="empty-state">
        <ion-icon name="document-text-outline"></ion-icon>
        <h3>Belum Ada Pengajuan</h3>
        <p>Anda belum pernah mengajukan izin, sakit, atau cuti</p>
        <a href="{{ route('izin.create') }}" class="btn-empty-action">
            <ion-icon name="add-circle"></ion-icon>
            Buat Pengajuan Baru
        </a>
    </div>
    @else
    @foreach($dataizin as $d)
    @php
    $statusClass = '';
    $statusText = '';
    if ($d->status_approved == 0) {
    $statusClass = 'status-pending';
    $statusText = 'Menunggu';
    } elseif ($d->status_approved == 1) {
    $statusClass = 'status-approved';
    $statusText = 'Disetujui';
    } else {
    $statusClass = 'status-rejected';
    $statusText = 'Ditolak';
    }

    $typeClass = '';
    $typeText = '';
    $typeIcon = '';
    if ($d->status == 'i') {
    $typeClass = 'type-izin';
    $typeText = 'Izin';
    $typeIcon = 'calendar';
    } elseif ($d->status == 's') {
    $typeClass = 'type-sakit';
    $typeText = 'Sakit';
    $typeIcon = 'medkit';
    } else {
    $typeClass = 'type-cuti';
    $typeText = 'Cuti';
    $typeIcon = 'time';
    }
    @endphp

    <div class="izin-card {{ $statusClass }} {{ $typeClass }}">
        <div class="izin-header">
            <div class="izin-type">
                <div class="type-icon">
                    <ion-icon name="{{ $typeIcon }}"></ion-icon>
                </div>
                <div class="type-info">
                    <h4>{{ $typeText }}</h4>
                    <span>{{ $d->kode_izin }}</span>
                </div>
            </div>
            <div class="status-badge">{{ $statusText }}</div>
        </div>

        <div class="izin-dates">
            <div class="date-item">
                <div class="date-label">Dari</div>
                <div class="date-value">{{ \Carbon\Carbon::parse($d->tgl_izin_dari)->format('d M Y') }}</div>
            </div>
            <div class="date-separator">→</div>
            <div class="date-item">
                <div class="date-label">Sampai</div>
                <div class="date-value">{{ \Carbon\Carbon::parse($d->tgl_izin_sampai)->format('d M Y') }}</div>
            </div>
        </div>

        <div class="izin-description">
            {{ Str::limit($d->keterangan, 100) }}
        </div>

        <div class="izin-actions">
            <a href="{{ route('izin.show', $d->kode_izin) }}" class="btn-action btn-detail">
                <ion-icon name="eye"></ion-icon>
                <span>Detail</span>
            </a>
            @if($d->status_approved == 0)
            <button type="button" class="btn-action btn-delete" onclick="confirmDelete('{{ $d->kode_izin }}')">
                <ion-icon name="trash"></ion-icon>
                <span>Hapus</span>
            </button>
            @endif
        </div>
    </div>
    @endforeach
    @endif
</div>

<!-- Form Delete (Hidden) -->
<form id="form-delete" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('myscript')
<script>
    $(document).ready(function() {
        // Load statistik
        loadStatistik();
    });

    // Load statistik
    function loadStatistik() {
        $.ajax({
            type: 'GET',
            url: '{{ route("izin.statistik") }}',
            data: {
                tahun: new Date().getFullYear()
            },
            success: function(response) {
                if (response.success) {
                    const data = response.data;
                    const statsHtml = `
                        <div class="stat-item pending">
                            <div class="stat-value">${data.pending}</div>
                            <div class="stat-label">Pending</div>
                        </div>
                        <div class="stat-item approved">
                            <div class="stat-value">${data.disetujui}</div>
                            <div class="stat-label">Disetujui</div>
                        </div>
                        <div class="stat-item rejected">
                            <div class="stat-value">${data.ditolak}</div>
                            <div class="stat-label">Ditolak</div>
                        </div>
                        <div class="stat-item izin">
                            <div class="stat-value">${data.total_izin}</div>
                            <div class="stat-label">Izin</div>
                        </div>
                        <div class="stat-item sakit">
                            <div class="stat-value">${data.total_sakit}</div>
                            <div class="stat-label">Sakit</div>
                        </div>
                        <div class="stat-item cuti">
                            <div class="stat-value">${data.total_cuti}</div>
                            <div class="stat-label">Cuti</div>
                        </div>
                    `;
                    $('#stats-grid').html(statsHtml);
                }
            },
            error: function() {
                console.log('Gagal memuat statistik');
            }
        });
    }

    // Confirm delete
    function confirmDelete(kodeIzin) {
        Swal.fire({
            title: 'Hapus Pengajuan?',
            text: 'Pengajuan izin akan dihapus secara permanen',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('form-delete');
                form.action = '/presensi/izin/' + kodeIzin;
                form.submit();
            }
        });
    }
</script>
@endpush