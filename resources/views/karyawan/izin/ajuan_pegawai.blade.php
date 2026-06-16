@extends('karyawan.layouts.presensi')

@section('content')

<style>

    :root {
        --primary:       #2563EB;
        --primary-soft:  #EFF6FF;
        --primary-mid:   #BFDBFE;
        --success:       #10B981;
        --success-soft:  #ECFDF5;
        --danger:        #EF4444;
        --danger-soft:   #FEF2F2;
        --warning:       #F59E0B;
        --warning-soft:  #FFFBEB;
        --info:          #06B6D4;
        --info-soft:     #ECFEFF;
        --purple:        #8B5CF6;
        --purple-soft:   #F5F3FF;
        --text-900:      #111827;
        --text-600:      #4B5563;
        --text-400:      #9CA3AF;
        --border:        #F1F5F9;
        --border-med:    #E2E8F0;
        --surface:       #FFFFFF;
        --bg:            #F8FAFC;
        --radius-sm:     10px;
        --radius-md:     14px;
        --radius-lg:     18px;
        --shadow-sm:     0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
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
        justify-content: space-between;
        position: sticky;
        top: 0;
        z-index: 50;
    }

    .pg-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .btn-back {
        width: 36px; height: 36px;
        background: var(--bg);
        border: 1px solid var(--border-med);
        border-radius: var(--radius-sm);
        display: flex; align-items: center; justify-content: center;
        text-decoration: none;
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
        color: var(--primary);
        display: block;
        margin-top: 1px;
    }

    /* ── PAGE BODY ── */
    .pg-body {
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 20px;
        min-height: calc(100vh - 70px);
    }

    .sec-label {
        font-size: 13px;
        font-weight: 700;
        color: var(--text-900);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* ── LIST CARDS ── */
    .izin-card {
        background: var(--surface);
        border: 1px solid var(--border-med);
        border-radius: var(--radius-md);
        padding: 16px;
        box-shadow: var(--shadow-sm);
        position: relative;
        overflow: hidden;
    }

    .top-stripe {
        position: absolute; top: 0; left: 0; right: 0; height: 4px;
        background: var(--border-med);
    }
    .izin-card.type-cuti .top-stripe { background: var(--purple); }

    .izin-head {
        display: flex; align-items: flex-start; justify-content: space-between; gap: 12px;
    }

    .izin-type-row {
        display: flex; align-items: center; gap: 10px;
    }

    .type-dot {
        width: 36px; height: 36px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        background: var(--purple-soft);
    }

    .type-dot ion-icon { font-size: 18px; color: var(--purple); }

    .izin-type-name {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-900);
        line-height: 1.2;
    }

    .izin-code {
        font-size: 10px;
        font-weight: 500;
        color: var(--text-400);
        margin-top: 1px;
    }

    /* Status badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-badge.pending  { background: var(--warning-soft); color: #B45309; }
    .status-badge.approved { background: var(--success-soft); color: #047857; }
    .status-badge.rejected { background: var(--danger-soft); color: #B91C1C; }

    .h-div {
        height: 1px; background: var(--border); margin: 14px 0;
    }

    /* Employee Info */
    .emp-info {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
    }
    
    .emp-avatar {
        width: 32px; height: 32px; border-radius: 50%;
        background: var(--primary-soft); color: var(--primary);
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 12px;
    }

    .emp-details {
        display: flex; flex-direction: column;
    }

    .emp-name { font-size: 13px; font-weight: 700; color: var(--text-900); }
    .emp-jabatan { font-size: 11px; color: var(--text-600); }

    /* Dates */
    .izin-dates {
        display: flex; align-items: center; gap: 8px;
    }

    .date-pill {
        flex: 1;
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 8px 10px;
    }

    .date-pill-lbl { font-size: 10px; font-weight: 600; color: var(--text-400); margin-bottom: 2px; text-transform: uppercase; }
    .date-pill-val { font-size: 12px; font-weight: 600; color: var(--text-900); }

    .date-arrow { font-size: 16px; color: var(--text-400); flex-shrink: 0; }

    /* Description */
    .izin-desc {
        font-size: 12px;
        color: var(--text-600);
        line-height: 1.5;
        background: var(--bg);
        padding: 10px;
        border-radius: 8px;
    }

    /* Actions */
    .izin-actions {
        display: flex; gap: 8px;
    }

    .btn-act {
        flex: 1; height: 38px;
        display: flex; align-items: center; justify-content: center; gap: 6px;
        border: none; border-radius: 8px;
        font-family: 'Inter', sans-serif; font-size: 12.5px; font-weight: 600;
        cursor: pointer; text-decoration: none;
        -webkit-tap-highlight-color: transparent;
    }

    .btn-approve { background: var(--success); color: white; }
    .btn-reject  { background: var(--danger-soft); color: var(--danger); }
    .btn-disabled { background: var(--bg); color: var(--text-400); cursor: not-allowed; border: 1px solid var(--border-med); }

    /* Empty state */
    .empty-state {
        background: var(--surface);
        border: 1px dashed var(--border-med);
        border-radius: var(--radius-md);
        padding: 40px 20px;
        text-align: center;
    }

    .empty-box ion-icon {
        font-size: 56px;
        color: #CBD5E1;
        margin-bottom: 12px;
        display: block;
    }

    .empty-box h3 { font-size: 16px; font-weight: 700; color: var(--text-900); margin-bottom: 6px; }
    .empty-box p  { font-size: 13px; color: var(--text-600); margin-bottom: 16px; }

</style>

{{-- ── PAGE HEADER ── --}}
<div class="pg-header">
    <div class="pg-header-left">
        <a href="{{ route('izin.index') }}" class="btn-back">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
        <div>
            <div class="pg-title">Ajuan Pegawai</div>
            <span class="pg-sub">Persetujuan Cuti Bawahan</span>
        </div>
    </div>
</div>

{{-- ── PAGE BODY ── --}}
<div class="pg-body">

    <div>
        <div class="sec-label">Daftar Pengajuan Cuti</div>

        @if($dataajuan->isEmpty())

        <div class="empty-state">
            <div class="empty-box">
                <ion-icon name="document-text-outline"></ion-icon>
                <h3>Belum ada ajuan</h3>
                <p>Tidak ada pengajuan cuti dari pegawai Anda saat ini.</p>
            </div>
        </div>

        @else

        <div style="display:flex; flex-direction:column; gap:12px;">

        @foreach($dataajuan as $d)
        @php
            switch($d->status_approved) {
                case 0:  $sClass = 'pending';  $sText = '● Menunggu';  break;
                case 1:  $sClass = 'approved'; $sText = '✓ Disetujui'; break;
                default: $sClass = 'rejected'; $sText = '✕ Ditolak';   break;
            }
        @endphp

        <div class="izin-card type-cuti">
            <div class="top-stripe"></div>

            {{-- Head --}}
            <div class="izin-head">
                <div class="izin-type-row">
                    <div class="type-dot">
                        <ion-icon name="leaf-outline"></ion-icon>
                    </div>
                    <div>
                        <div class="izin-type-name">Cuti {{ $d->nama_cuti ? '- '.$d->nama_cuti : '' }}</div>
                        <div class="izin-code">{{ $d->kode_izin }}</div>
                    </div>
                </div>
                <span class="status-badge {{ $sClass }}">{{ $sText }}</span>
            </div>

            <div class="h-div"></div>

            {{-- Employee --}}
            <div class="emp-info">
                <div class="emp-avatar">
                    {{ substr($d->nama_lengkap, 0, 1) }}
                </div>
                <div class="emp-details">
                    <div class="emp-name">{{ $d->nama_lengkap }}</div>
                    <div class="emp-jabatan">{{ $d->jabatan ?? 'Pegawai' }}</div>
                </div>
            </div>

            {{-- Dates --}}
            <div class="izin-dates">
                <div class="date-pill">
                    <div class="date-pill-lbl">Dari</div>
                    <div class="date-pill-val">{{ \Carbon\Carbon::parse($d->tgl_izin_dari)->isoFormat('D MMM Y') }}</div>
                </div>
                <ion-icon name="arrow-forward-outline" class="date-arrow"></ion-icon>
                <div class="date-pill">
                    <div class="date-pill-lbl">Sampai</div>
                    <div class="date-pill-val">{{ \Carbon\Carbon::parse($d->tgl_izin_sampai)->isoFormat('D MMM Y') }}</div>
                </div>
            </div>

            {{-- Description --}}
            @if($d->keterangan)
            <div class="h-div"></div>
            <div class="izin-desc"><strong>Keterangan:</strong> {{ Str::limit($d->keterangan, 100) }}</div>
            @endif

            {{-- Actions --}}
            <div class="h-div"></div>
            @if($d->status_approved == 0)
            <div class="izin-actions">
                <form action="{{ url('/presensi/izin/ajuan-pegawai/'.$d->kode_izin.'/approve') }}" method="POST" style="flex:1;">
                    @csrf
                    <input type="hidden" name="status_approved" value="1">
                    <button type="submit" class="btn-act btn-approve" style="width:100%;">
                        <ion-icon name="checkmark-circle-outline"></ion-icon> Setujui
                    </button>
                </form>
                <form action="{{ url('/presensi/izin/ajuan-pegawai/'.$d->kode_izin.'/approve') }}" method="POST" style="flex:1;">
                    @csrf
                    <input type="hidden" name="status_approved" value="2">
                    <button type="submit" class="btn-act btn-reject" style="width:100%;">
                        <ion-icon name="close-circle-outline"></ion-icon> Tolak
                    </button>
                </form>
            </div>
            @else
            <div class="izin-actions">
                <div class="btn-act btn-disabled" style="width:100%;">
                    Pengajuan Sudah Diproses
                </div>
            </div>
            @endif

        </div>

        @endforeach
        </div>

        @endif
    </div>

</div>

@endsection
