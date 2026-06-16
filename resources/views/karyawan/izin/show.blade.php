@extends('karyawan.layouts.presensi')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

    :root {
        --primary:      #2563EB;
        --primary-soft: #EFF6FF;
        --primary-mid:  #BFDBFE;
        --success:      #10B981;
        --success-soft: #ECFDF5;
        --danger:       #EF4444;
        --danger-soft:  #FEF2F2;
        --warning:      #F59E0B;
        --warning-soft: #FFFBEB;
        --info:         #06B6D4;
        --info-soft:    #ECFEFF;
        --purple:       #8B5CF6;
        --purple-soft:  #F5F3FF;
        --text-900:     #111827;
        --text-600:     #4B5563;
        --text-400:     #9CA3AF;
        --border:       #F1F5F9;
        --border-med:   #E2E8F0;
        --surface:      #FFFFFF;
        --bg:           #F8FAFC;
        --radius-sm:    10px;
        --radius-md:    14px;
        --radius-lg:    18px;
        --shadow-sm:    0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: 'Inter', -apple-system, sans-serif;
        background: var(--bg);
        color: var(--text-900);
        -webkit-font-smoothing: antialiased;
    }

    /* ── PAGE HEADER ── */
    .pg-header {
        background: var(--surface);
        border-bottom: 1px solid var(--border);
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        position: sticky;
        top: 0;
        z-index: 50;
    }

    .btn-back {
        width: 36px; height: 36px;
        background: var(--bg);
        border: 1px solid var(--border-med);
        border-radius: var(--radius-sm);
        display: flex; align-items: center; justify-content: center;
        text-decoration: none;
        flex-shrink: 0;
        transition: background 0.2s;
        -webkit-tap-highlight-color: transparent;
    }

    .btn-back:active { background: var(--border-med); }
    .btn-back ion-icon { font-size: 20px; color: var(--text-600); }

    .pg-title {
        font-size: 17px;
        font-weight: 700;
        color: var(--text-900);
        line-height: 1.2;
    }

    .pg-sub {
        font-size: 11px;
        font-weight: 500;
        color: var(--text-400);
        display: block;
        margin-top: 1px;
        font-family: monospace;
    }

    /* ── PAGE BODY ── */
    .pg-body {
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        padding-bottom: 100px;
    }

    /* ── STATUS HERO CARD ── */
    .status-hero {
        border-radius: var(--radius-lg);
        padding: 24px 20px;
        text-align: center;
        border: 1px solid transparent;
        position: relative;
        overflow: hidden;
    }

    .status-hero::before {
        content: '';
        position: absolute;
        top: -30px; right: -30px;
        width: 120px; height: 120px;
        border-radius: 50%;
        opacity: 0.08;
        background: currentColor;
    }

    .status-hero.pending  { background: var(--warning-soft); border-color: #FDE68A; color: var(--warning); }
    .status-hero.approved { background: var(--success-soft); border-color: #A7F3D0; color: var(--success); }
    .status-hero.rejected { background: var(--danger-soft);  border-color: #FECACA; color: var(--danger); }

    .status-icon-ring {
        width: 72px; height: 72px;
        border-radius: 50%;
        margin: 0 auto 14px;
        display: flex; align-items: center; justify-content: center;
        position: relative;
    }

    .status-hero.pending  .status-icon-ring { background: #FEF3C7; box-shadow: 0 0 0 8px rgba(245,158,11,0.12); }
    .status-hero.approved .status-icon-ring { background: #D1FAE5; box-shadow: 0 0 0 8px rgba(16,185,129,0.12); }
    .status-hero.rejected .status-icon-ring { background: #FEE2E2; box-shadow: 0 0 0 8px rgba(239,68,68,0.12); }

    .status-icon-ring ion-icon { font-size: 36px; }
    .status-hero.pending  .status-icon-ring ion-icon { color: #D97706; }
    .status-hero.approved .status-icon-ring ion-icon { color: var(--success); }
    .status-hero.rejected .status-icon-ring ion-icon { color: var(--danger); }

    .status-title {
        font-size: 17px;
        font-weight: 800;
        line-height: 1.2;
        margin-bottom: 5px;
    }

    .status-hero.pending  .status-title { color: #92400E; }
    .status-hero.approved .status-title { color: #065F46; }
    .status-hero.rejected .status-title { color: #991B1B; }

    .status-sub {
        font-size: 12px;
        font-weight: 500;
        opacity: 0.75;
    }

    /* ── DETAIL CARD ── */
    .detail-card {
        background: var(--surface);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    .card-head {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 13px 16px;
        border-bottom: 1px solid var(--border);
    }

    .card-head-icon {
        width: 28px; height: 28px;
        border-radius: 8px;
        background: var(--primary-soft);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }

    .card-head-icon ion-icon { font-size: 14px; color: var(--primary); }

    .card-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--text-900);
    }

    /* ── INFO ROWS ── */
    .info-row {
        display: flex;
        align-items: flex-start;
        padding: 11px 16px;
        border-bottom: 1px solid var(--border);
        gap: 12px;
    }

    .info-row:last-child { border-bottom: none; }

    .info-label {
        flex: 0 0 108px;
        font-size: 12px;
        font-weight: 600;
        color: var(--text-400);
        padding-top: 1px;
    }

    .info-value {
        flex: 1;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-900);
        line-height: 1.5;
    }

    /* ── TYPE BADGE ── */
    .type-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 700;
    }

    .type-badge ion-icon { font-size: 13px; }
    .type-badge.izin  { background: var(--warning-soft); color: #D97706; }
    .type-badge.sakit { background: var(--info-soft);    color: #0891B2; }
    .type-badge.cuti  { background: var(--purple-soft);  color: var(--purple); }

    /* ── DURATION PILL ── */
    .duration-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        background: var(--primary-soft);
        border: 1px solid var(--primary-mid);
        border-radius: 50px;
        font-size: 12px;
        font-weight: 700;
        color: var(--primary);
    }

    .duration-pill ion-icon { font-size: 13px; }

    /* ── KETERANGAN BOX ── */
    .ket-box {
        padding: 13px 16px;
        font-size: 13px;
        line-height: 1.7;
        color: var(--text-600);
    }

    /* ── DOCUMENT SECTION ── */
    .doc-img {
        width: 100%;
        aspect-ratio: 16/9;
        object-fit: cover;
        display: block;
        border-bottom: 1px solid var(--border);
    }

    .btn-download {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 13px 16px;
        background: var(--info-soft);
        color: #0891B2;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        font-family: 'Inter', sans-serif;
        border-top: 1px solid #A5F3FC;
        transition: background 0.2s;
        -webkit-tap-highlight-color: transparent;
    }

    .btn-download ion-icon { font-size: 17px; }
    .btn-download:active { background: #CFFAFE; }

    /* ── DELETE BUTTON ── */
    .btn-delete-full {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 14px;
        background: var(--surface);
        color: var(--danger);
        border: 1.5px solid #FECACA;
        border-radius: var(--radius-lg);
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s, transform 0.15s;
        -webkit-tap-highlight-color: transparent;
        box-shadow: var(--shadow-sm);
    }

    .btn-delete-full ion-icon { font-size: 18px; }
    .btn-delete-full:active { background: var(--danger-soft); transform: scale(0.98); }

    /* ── Animations ── */
    @keyframes fadeSlide {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .pg-body > * {
        animation: fadeSlide 0.28s ease both;
    }
    .pg-body > *:nth-child(1) { animation-delay: 0.04s; }
    .pg-body > *:nth-child(2) { animation-delay: 0.08s; }
    .pg-body > *:nth-child(3) { animation-delay: 0.12s; }
    .pg-body > *:nth-child(4) { animation-delay: 0.16s; }
    .pg-body > *:nth-child(5) { animation-delay: 0.20s; }
</style>

@php
    switch($dataizin->status_approved) {
        case 0:  $sClass = 'pending';  $sText = 'Menunggu Persetujuan'; $sIcon = 'time-outline';          $sSub = 'Menunggu konfirmasi dari atasan'; break;
        case 1:  $sClass = 'approved'; $sText = 'Pengajuan Disetujui';  $sIcon = 'checkmark-circle-outline'; $sSub = 'Pengajuan Anda telah disetujui'; break;
        default: $sClass = 'rejected'; $sText = 'Pengajuan Ditolak';    $sIcon = 'close-circle-outline';  $sSub = 'Silakan hubungi atasan untuk informasi lebih lanjut'; break;
    }

    switch($dataizin->status) {
        case 'i': $tClass = 'izin';  $tText = 'Izin';  $tIcon = 'calendar-outline'; break;
        case 's': $tClass = 'sakit'; $tText = 'Sakit'; $tIcon = 'medkit-outline';   break;
        default:  $tClass = 'cuti';  $tText = 'Cuti';  $tIcon = 'leaf-outline';      break;
    }

    $durasi = \Carbon\Carbon::parse($dataizin->tgl_izin_dari)->diffInDays(\Carbon\Carbon::parse($dataizin->tgl_izin_sampai)) + 1;

    $ext = !empty($dataizin->doc_sid) ? strtolower(pathinfo($dataizin->doc_sid, PATHINFO_EXTENSION)) : '';
    $isImage = in_array($ext, ['jpg','jpeg','png','webp']);
@endphp

{{-- ── PAGE HEADER ── --}}
<div class="pg-header">
    <a href="{{ route('izin.index') }}" class="btn-back">
        <ion-icon name="chevron-back-outline"></ion-icon>
    </a>
    <div>
        <div class="pg-title">Detail Pengajuan</div>
        <span class="pg-sub">{{ $dataizin->kode_izin }}</span>
    </div>
</div>

{{-- ── PAGE BODY ── --}}
<div class="pg-body">

    {{-- Status Hero --}}
    <div class="status-hero {{ $sClass }}">
        <div class="status-icon-ring">
            <ion-icon name="{{ $sIcon }}"></ion-icon>
        </div>
        <div class="status-title">{{ $sText }}</div>
        <div class="status-sub">{{ $sSub }}</div>
    </div>

    {{-- Informasi Umum --}}
    <div class="detail-card">
        <div class="card-head">
            <div class="card-head-icon"><ion-icon name="information-circle-outline"></ion-icon></div>
            <div class="card-title">Informasi Umum</div>
        </div>

        <div class="info-row">
            <div class="info-label">Tipe</div>
            <div class="info-value">
                <span class="type-badge {{ $tClass }}">
                    <ion-icon name="{{ $tIcon }}"></ion-icon>
                    {{ $tText }}
                    @if($dataizin->status == 'c' && !empty($dataizin->nama_cuti))
                        — {{ $dataizin->nama_cuti }}
                    @endif
                </span>
            </div>
        </div>

        <div class="info-row">
            <div class="info-label">Tanggal Mulai</div>
            <div class="info-value">
                {{ \Carbon\Carbon::parse($dataizin->tgl_izin_dari)->isoFormat('dddd, D MMMM Y') }}
            </div>
        </div>

        <div class="info-row">
            <div class="info-label">Tanggal Selesai</div>
            <div class="info-value">
                {{ \Carbon\Carbon::parse($dataizin->tgl_izin_sampai)->isoFormat('dddd, D MMMM Y') }}
            </div>
        </div>

        <div class="info-row">
            <div class="info-label">Durasi</div>
            <div class="info-value">
                <span class="duration-pill">
                    <ion-icon name="hourglass-outline"></ion-icon>
                    {{ $durasi }} hari
                </span>
            </div>
        </div>

        <div class="info-row">
            <div class="info-label">Diajukan</div>
            <div class="info-value">
                {{ \Carbon\Carbon::parse($dataizin->created_at)->isoFormat('D MMM Y, HH:mm') }}
            </div>
        </div>
    </div>

    {{-- Keterangan --}}
    <div class="detail-card">
        <div class="card-head">
            <div class="card-head-icon"><ion-icon name="chatbubble-outline"></ion-icon></div>
            <div class="card-title">Keterangan</div>
        </div>
        <div class="ket-box">
            {{ $dataizin->keterangan }}
        </div>
    </div>

    {{-- Dokumen --}}
    @if(!empty($dataizin->doc_sid))
    <div class="detail-card">
        <div class="card-head">
            <div class="card-head-icon"><ion-icon name="document-attach-outline"></ion-icon></div>
            <div class="card-title">Dokumen Pendukung</div>
        </div>

        @if($isImage)
        <img src="{{ Storage::url('uploads/sid/' . $dataizin->doc_sid) }}"
             alt="Dokumen Pendukung" class="doc-img"
             onclick="previewDoc('{{ Storage::url('uploads/sid/' . $dataizin->doc_sid) }}')">
        @endif

        <a href="{{ route('izin.download', $dataizin->kode_izin) }}" class="btn-download">
            <ion-icon name="download-outline"></ion-icon>
            Download Dokumen
        </a>
    </div>
    @endif

    {{-- Delete button (pending only) --}}
    @if($dataizin->status_approved == 0)
    <button type="button" class="btn-delete-full" onclick="confirmDelete()">
        <ion-icon name="trash-outline"></ion-icon>
        Hapus Pengajuan
    </button>
    @endif

</div>

{{-- Hidden delete form --}}
<form id="form-delete" action="{{ route('izin.delete', $dataizin->kode_izin) }}" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('myscript')
<script>
    function previewDoc(src) {
        Swal.fire({
            imageUrl: src,
            imageAlt: 'Dokumen Pendukung',
            showCloseButton: true,
            showConfirmButton: false,
            width: '92%',
            padding: '12px'
        });
    }

    function confirmDelete() {
        Swal.fire({
            title: 'Hapus Pengajuan?',
            text: 'Pengajuan izin akan dihapus secara permanen dan tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then(function (result) {
            if (result.isConfirmed) {
                document.getElementById('form-delete').submit();
            }
        });
    }
</script>
@endpush