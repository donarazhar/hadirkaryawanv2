@extends('karyawan.layouts.presensi')

@section('content')

<style>
    /* ===== MODERN DETAIL IZIN PAGE ===== */
    :root {
        --primary: #0053C5;
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
        padding: 24px 20px 100px 20px;
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
    }

    .btn-back ion-icon {
        font-size: 24px;
        color: white;
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

    /* ===== STATUS CARD ===== */
    .status-section {
        padding: 0 20px;
        margin-top: -85px;
        margin-bottom: 20px;
        position: relative;
        z-index: 10;
    }

    .status-card {
        background: var(--bg-card);
        border-radius: 20px;
        padding: 24px 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 83, 197, 0.08);
        text-align: center;
    }

    .status-icon-wrapper {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        background: var(--status-bg);
        box-shadow: 0 8px 20px var(--status-shadow);
    }

    .status-icon-wrapper ion-icon {
        font-size: 40px;
        color: white;
    }

    .status-card.pending {
        --status-bg: linear-gradient(135deg, var(--warning) 0%, #fb923c 100%);
        --status-shadow: rgba(245, 158, 11, 0.3);
    }

    .status-card.approved {
        --status-bg: linear-gradient(135deg, var(--success) 0%, #34d399 100%);
        --status-shadow: rgba(16, 185, 129, 0.3);
    }

    .status-card.rejected {
        --status-bg: linear-gradient(135deg, var(--danger) 0%, #f87171 100%);
        --status-shadow: rgba(239, 68, 68, 0.3);
    }

    .status-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 4px;
    }

    .status-subtitle {
        font-size: 13px;
        color: var(--text-secondary);
    }

    /* ===== DETAIL SECTION ===== */
    .detail-section {
        padding: 0 20px 100px;
    }

    .detail-card {
        background: var(--bg-card);
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(0, 83, 197, 0.08);
        margin-bottom: 16px;
    }

    .detail-card-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .detail-card-title ion-icon {
        font-size: 20px;
        color: var(--primary);
    }

    .detail-row {
        display: flex;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        flex: 0 0 120px;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-secondary);
    }

    .detail-value {
        flex: 1;
        font-size: 14px;
        font-weight: 600;
        color: var(--text-primary);
    }

    .document-preview {
        width: 100%;
        height: 200px;
        border-radius: 12px;
        object-fit: cover;
        border: 2px solid #e2e8f0;
        margin-top: 12px;
    }

    .btn-download {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 20px;
        background: linear-gradient(135deg, var(--info) 0%, #0ea5e9 100%);
        color: white;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        margin-top: 12px;
        box-shadow: 0 4px 12px rgba(6, 182, 212, 0.3);
    }

    .btn-delete {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 20px;
        background: white;
        color: var(--danger);
        border: 1px solid var(--danger);
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-delete:active {
        background: var(--danger);
        color: white;
        transform: scale(0.98);
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <div class="header-content">
        <a href="{{ route('izin.index') }}" class="btn-back">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
        <div class="header-title">
            <h1>Detail Pengajuan</h1>
            <p>{{ $dataizin->kode_izin }}</p>
        </div>
    </div>
</div>

<!-- Status Card -->
<div class="status-section">
    @php
    $statusClass = '';
    $statusText = '';
    $statusIcon = '';
    if ($dataizin->status_approved == 0) {
    $statusClass = 'pending';
    $statusText = 'Menunggu Persetujuan';
    $statusIcon = 'time';
    } elseif ($dataizin->status_approved == 1) {
    $statusClass = 'approved';
    $statusText = 'Pengajuan Disetujui';
    $statusIcon = 'checkmark-circle';
    } else {
    $statusClass = 'rejected';
    $statusText = 'Pengajuan Ditolak';
    $statusIcon = 'close-circle';
    }
    @endphp

    <div class="status-card {{ $statusClass }}">
        <div class="status-icon-wrapper">
            <ion-icon name="{{ $statusIcon }}"></ion-icon>
        </div>
        <div class="status-title">{{ $statusText }}</div>
        <div class="status-subtitle">
            @if($dataizin->status_approved == 0)
            Menunggu konfirmasi dari atasan
            @elseif($dataizin->status_approved == 1)
            Pengajuan Anda telah disetujui
            @else
            Silakan hubungi atasan untuk informasi lebih lanjut
            @endif
        </div>
    </div>
</div>

<!-- Detail Section -->
<div class="detail-section">
    <!-- Informasi Umum -->
    <div class="detail-card">
        <div class="detail-card-title">
            <ion-icon name="information-circle"></ion-icon>
            Informasi Umum
        </div>

        <div class="detail-row">
            <div class="detail-label">Tipe</div>
            <div class="detail-value">
                @if($dataizin->status == 'i')
                🗓️ Izin
                @elseif($dataizin->status == 's')
                🏥 Sakit
                @else
                🏖️ Cuti {{ $dataizin->nama_cuti ?? '' }}
                @endif
            </div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Tanggal Mulai</div>
            <div class="detail-value">{{ \Carbon\Carbon::parse($dataizin->tgl_izin_dari)->isoFormat('dddd, D MMMM Y') }}</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Tanggal Selesai</div>
            <div class="detail-value">{{ \Carbon\Carbon::parse($dataizin->tgl_izin_sampai)->isoFormat('dddd, D MMMM Y') }}</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Durasi</div>
            <div class="detail-value">
                {{ \Carbon\Carbon::parse($dataizin->tgl_izin_dari)->diffInDays(\Carbon\Carbon::parse($dataizin->tgl_izin_sampai)) + 1 }} hari
            </div>
        </div>
    </div>

    <!-- Keterangan -->
    <div class="detail-card">
        <div class="detail-card-title">
            <ion-icon name="document-text"></ion-icon>
            Keterangan
        </div>
        <p style="margin: 0; font-size: 14px; line-height: 1.6; color: var(--text-primary);">
            {{ $dataizin->keterangan }}
        </p>
    </div>

    <!-- Dokumen -->
    @if(!empty($dataizin->doc_sid))
    <div class="detail-card">
        <div class="detail-card-title">
            <ion-icon name="document-attach"></ion-icon>
            Dokumen Pendukung
        </div>
        @php
        $extension = pathinfo($dataizin->doc_sid, PATHINFO_EXTENSION);
        @endphp
        @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png']))
        <img src="{{ Storage::url('uploads/sid/' . $dataizin->doc_sid) }}"
            alt="Dokumen" class="document-preview">
        @endif
        <a href="{{ route('izin.download', $dataizin->kode_izin) }}" class="btn-download">
            <ion-icon name="download"></ion-icon>
            <span>Download Dokumen</span>
        </a>
    </div>
    @endif

    <!-- Delete Button (hanya jika pending) -->
    @if($dataizin->status_approved == 0)
    <button type="button" class="btn-delete" onclick="confirmDelete()">
        <ion-icon name="trash"></ion-icon>
        <span>Hapus Pengajuan</span>
    </button>
    @endif
</div>

<!-- Form Delete (Hidden) -->
<form id="form-delete" action="{{ route('izin.delete', $dataizin->kode_izin) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('myscript')
<script>
    function confirmDelete() {
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
                document.getElementById('form-delete').submit();
            }
        });
    }
</script>
@endpush