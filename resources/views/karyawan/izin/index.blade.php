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

    .btn-add-new {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        padding: 8px 13px;
        background: var(--primary);
        color: white;
        border-radius: var(--radius-sm);
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        font-family: 'Inter', sans-serif;
        box-shadow: 0 2px 8px rgba(37,99,235,0.25);
        transition: opacity 0.2s;
        -webkit-tap-highlight-color: transparent;
        white-space: nowrap;
    }

    .btn-add-new:active { opacity: 0.85; }
    .btn-add-new ion-icon { font-size: 16px; }

    /* ── PAGE BODY ── */
    .pg-body {
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 14px;
        padding-bottom: 100px;
    }

    /* ── CARD ── */
    .card {
        background: var(--surface);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
    }

    /* ── STATS GRID ── */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
        padding: 14px;
    }

    .stat-box {
        border-radius: var(--radius-md);
        padding: 12px 6px;
        text-align: center;
        border: 1px solid transparent;
    }

    .stat-box.pending  { background: var(--warning-soft);  border-color: #FDE68A; }
    .stat-box.approved { background: var(--success-soft);  border-color: #A7F3D0; }
    .stat-box.rejected { background: var(--danger-soft);   border-color: #FECACA; }
    .stat-box.izin     { background: var(--warning-soft);  border-color: #FDE68A; }
    .stat-box.sakit    { background: var(--info-soft);     border-color: #A5F3FC; }
    .stat-box.cuti     { background: var(--purple-soft);   border-color: #DDD6FE; }

    .stat-val {
        font-size: 22px;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 4px;
    }

    .stat-box.pending  .stat-val { color: var(--warning); }
    .stat-box.approved .stat-val { color: var(--success); }
    .stat-box.rejected .stat-val { color: var(--danger); }
    .stat-box.izin     .stat-val { color: #D97706; }
    .stat-box.sakit    .stat-val { color: var(--info); }
    .stat-box.cuti     .stat-val { color: var(--purple); }

    .stat-lbl {
        font-size: 10px;
        font-weight: 600;
        color: var(--text-400);
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    /* ── SECTION LABEL ── */
    .sec-label {
        font-size: 11px;
        font-weight: 700;
        color: var(--text-400);
        text-transform: uppercase;
        letter-spacing: 0.7px;
        margin-bottom: 6px;
        padding: 0 2px;
    }

    /* ── IZIN CARD ── */
    .izin-card {
        background: var(--surface);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    /* Top stripe */
    .izin-card .top-stripe { height: 3px; width: 100%; }
    .izin-card.type-izin   .top-stripe { background: var(--warning); }
    .izin-card.type-sakit  .top-stripe { background: var(--info); }
    .izin-card.type-cuti   .top-stripe { background: var(--purple); }

    /* Card head */
    .izin-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 14px 10px;
    }

    .izin-type-row {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .type-dot {
        width: 36px; height: 36px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }

    .izin-card.type-izin  .type-dot { background: var(--warning-soft); }
    .izin-card.type-sakit .type-dot { background: var(--info-soft); }
    .izin-card.type-cuti  .type-dot { background: var(--purple-soft); }

    .type-dot ion-icon { font-size: 18px; }
    .izin-card.type-izin  .type-dot ion-icon { color: var(--warning); }
    .izin-card.type-sakit .type-dot ion-icon { color: var(--info); }
    .izin-card.type-cuti  .type-dot ion-icon { color: var(--purple); }

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
        flex-shrink: 0;
    }

    .status-badge.pending  { background: var(--warning-soft); color: #D97706; }
    .status-badge.approved { background: var(--success-soft); color: var(--success); }
    .status-badge.rejected { background: var(--danger-soft);  color: var(--danger); }

    /* Divider */
    .h-div { height: 1px; background: var(--border); margin: 0 14px; }

    /* Date row */
    .izin-dates {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
    }

    .date-pill {
        flex: 1;
        background: var(--bg);
        border: 1px solid var(--border-med);
        border-radius: var(--radius-sm);
        padding: 7px 10px;
        text-align: center;
    }

    .date-pill-lbl {
        font-size: 9px;
        font-weight: 700;
        color: var(--text-400);
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 2px;
    }

    .date-pill-val {
        font-size: 12px;
        font-weight: 700;
        color: var(--text-900);
    }

    .date-arrow { font-size: 16px; color: var(--primary); flex-shrink: 0; }

    /* Description */
    .izin-desc {
        padding: 0 14px 10px;
        font-size: 12px;
        color: var(--text-600);
        line-height: 1.55;
    }

    /* Action row */
    .izin-actions {
        display: flex;
        gap: 8px;
        padding: 10px 14px 12px;
    }

    .btn-act {
        flex: 1;
        padding: 9px 14px;
        border-radius: var(--radius-sm);
        font-family: 'Inter', sans-serif;
        font-size: 12px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        cursor: pointer;
        border: none;
        text-decoration: none;
        transition: opacity 0.2s, transform 0.15s;
        -webkit-tap-highlight-color: transparent;
    }

    .btn-act ion-icon { font-size: 15px; }
    .btn-act:active { opacity: 0.82; transform: scale(0.97); }

    .btn-detail {
        background: var(--primary-soft);
        color: var(--primary);
        border: 1px solid var(--primary-mid);
    }

    .btn-delete {
        background: var(--danger-soft);
        color: var(--danger);
        border: 1px solid #FECACA;
    }

    /* ── EMPTY STATE ── */
    .empty-box {
        text-align: center;
        padding: 52px 20px;
        background: var(--surface);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border);
    }

    .empty-box ion-icon {
        font-size: 56px;
        color: #CBD5E1;
        margin-bottom: 12px;
        display: block;
    }

    .empty-box h3 { font-size: 16px; font-weight: 700; color: var(--text-900); margin-bottom: 6px; }
    .empty-box p  { font-size: 13px; color: var(--text-600); margin-bottom: 16px; }

    .btn-empty {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 11px 20px;
        background: var(--primary);
        color: white;
        border-radius: var(--radius-md);
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        box-shadow: 0 4px 12px rgba(37,99,235,0.28);
        -webkit-tap-highlight-color: transparent;
    }

    .btn-empty ion-icon { font-size: 16px; }

    /* ── Animations ── */
    @keyframes fadeSlide {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .pg-body > * {
        animation: fadeSlide 0.3s ease both;
    }
    .pg-body > *:nth-child(1) { animation-delay: 0.04s; }
    .pg-body > *:nth-child(2) { animation-delay: 0.08s; }
    .pg-body > *:nth-child(3) { animation-delay: 0.12s; }

    @media (max-width: 480px) {
        .btn-add-new {
            padding: 6px 10px;
            font-size: 11px;
            gap: 4px;
        }
        .btn-add-new ion-icon { font-size: 14px; }
        .pg-header { padding: 12px 14px; gap: 8px; }
        .pg-header-left { gap: 8px; }
        .pg-title { font-size: 15px; }
    }

    @media (max-width: 360px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .btn-add-new { padding: 6px 8px; font-size: 10px; }
        .pg-header { padding: 10px; }
    }
</style>

{{-- ── PAGE HEADER ── --}}
<div class="pg-header">
    <div class="pg-header-left">
        <a href="{{ route('dashboard') }}" class="btn-back">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
        <div>
            <div class="pg-title">Pengajuan Izin</div>
            <span class="pg-sub">Kelola izin, sakit & cuti</span>
        </div>
    </div>
    <div style="display: flex; gap: 8px;">
        @if(isset($is_pimpinan) && $is_pimpinan)
            <a href="{{ url('/presensi/izin/ajuan-pegawai') }}" class="btn-add-new" style="background: var(--purple);">
                <ion-icon name="people"></ion-icon>
                Ajuan Pegawai
            </a>
        @endif
        <a href="{{ route('izin.create') }}" class="btn-add-new">
            <ion-icon name="add"></ion-icon>
            Ajukan
        </a>
    </div>
</div>

{{-- ── PAGE BODY ── --}}
<div class="pg-body">

    {{-- Stats Card --}}
    <div>
        <div class="sec-label">Statistik Tahun {{ date('Y') }}</div>
        <div class="card" style="margin-top:6px;">
            <div class="stats-grid" id="stats-grid">
                {{-- Placeholder --}}
                <div class="stat-box pending"><div class="stat-val">—</div><div class="stat-lbl">Pending</div></div>
                <div class="stat-box approved"><div class="stat-val">—</div><div class="stat-lbl">Disetujui</div></div>
                <div class="stat-box rejected"><div class="stat-val">—</div><div class="stat-lbl">Ditolak</div></div>
                <div class="stat-box izin"><div class="stat-val">—</div><div class="stat-lbl">Izin</div></div>
                <div class="stat-box sakit"><div class="stat-val">—</div><div class="stat-lbl">Sakit</div></div>
                <div class="stat-box cuti"><div class="stat-val">—</div><div class="stat-lbl">Cuti</div></div>
            </div>
        </div>
    </div>

    {{-- List --}}
    <div>
        <div class="sec-label">Riwayat Pengajuan</div>

        @if($dataizin->isEmpty())

        <div class="empty-box">
            <ion-icon name="document-text-outline"></ion-icon>
            <h3>Belum Ada Pengajuan</h3>
            <p>Anda belum pernah mengajukan izin, sakit, atau cuti</p>
            <a href="{{ route('izin.create') }}" class="btn-empty">
                <ion-icon name="add-circle-outline"></ion-icon>
                Buat Pengajuan Baru
            </a>
        </div>

        @else

        <div style="display:flex; flex-direction:column; gap:10px; margin-top:6px;">

        @foreach($dataizin as $d)
        @php
            // Status badge: izin/sakit auto-approved tampilkan 'Otomatis Disetujui'
            if (in_array($d->status, ['i', 's'])) {
                // Izin & Sakit selalu otomatis disetujui
                $sClass = 'approved';
                $sText  = '✓ Otomatis';
            } else {
                // Cuti: tampilkan status sesuai approval
                switch($d->status_approved) {
                    case 0:  $sClass = 'pending';  $sText = '● Menunggu';  break;
                    case 1:  $sClass = 'approved'; $sText = '✓ Disetujui'; break;
                    default: $sClass = 'rejected'; $sText = '✕ Ditolak';   break;
                }
            }

            switch($d->status) {
                case 'i': $tClass = 'type-izin';  $tText = 'Izin';  $tIcon = 'calendar-outline'; break;
                case 's': $tClass = 'type-sakit'; $tText = 'Sakit'; $tIcon = 'medkit-outline';   break;
                default:  $tClass = 'type-cuti';  $tText = 'Cuti';  $tIcon = 'leaf-outline';      break;
            }
        @endphp

        <div class="izin-card {{ $tClass }}">
            <div class="top-stripe"></div>

            {{-- Head --}}
            <div class="izin-head">
                <div class="izin-type-row">
                    <div class="type-dot">
                        <ion-icon name="{{ $tIcon }}"></ion-icon>
                    </div>
                    <div>
                        <div class="izin-type-name">{{ $tText }}</div>
                        <div class="izin-code">{{ $d->kode_izin }}</div>
                    </div>
                </div>
                <span class="status-badge {{ $sClass }}">{{ $sText }}</span>
            </div>

            <div class="h-div"></div>

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
            <div class="izin-desc">{{ Str::limit($d->keterangan, 100) }}</div>
            @endif

            {{-- Actions --}}
            <div class="h-div"></div>
            <div class="izin-actions">
                <a href="{{ route('izin.show', $d->kode_izin) }}" class="btn-act btn-detail">
                    <ion-icon name="eye-outline"></ion-icon>
                    Lihat Detail
                </a>
                {{-- Tombol hapus: hanya untuk Cuti yang masih pending --}}
                @if($d->status == 'c' && $d->status_approved == 0)
                <button type="button" class="btn-act btn-delete" onclick="confirmDelete('{{ $d->kode_izin }}')">
                    <ion-icon name="trash-outline"></ion-icon>
                    Hapus
                </button>
                @elseif(in_array($d->status, ['i', 's']))
                {{-- Izin & Sakit bisa dihapus kapan saja --}}
                <button type="button" class="btn-act btn-delete" onclick="confirmDelete('{{ $d->kode_izin }}')">
                    <ion-icon name="trash-outline"></ion-icon>
                    Hapus
                </button>
                @endif
            </div>
        </div>

        @endforeach
        </div>

        @endif
    </div>

</div>

{{-- Hidden delete form --}}
<form id="form-delete" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('myscript')
<script>
    $(function () {
        loadStatistik();
    });

    function loadStatistik() {
        $.ajax({
            type: 'GET',
            url: '{{ route("izin.statistik") }}',
            data: { tahun: new Date().getFullYear() },
            success: function (res) {
                if (!res.success) return;
                var d = res.data;
                $('#stats-grid').html(`
                    <div class="stat-box pending"><div class="stat-val">${d.pending}</div><div class="stat-lbl">Pending</div></div>
                    <div class="stat-box approved"><div class="stat-val">${d.disetujui}</div><div class="stat-lbl">Disetujui</div></div>
                    <div class="stat-box rejected"><div class="stat-val">${d.ditolak}</div><div class="stat-lbl">Ditolak</div></div>
                    <div class="stat-box izin"><div class="stat-val">${d.total_izin}</div><div class="stat-lbl">Izin</div></div>
                    <div class="stat-box sakit"><div class="stat-val">${d.total_sakit}</div><div class="stat-lbl">Sakit</div></div>
                    <div class="stat-box cuti"><div class="stat-val">${d.total_cuti}</div><div class="stat-lbl">Cuti</div></div>
                `);
            }
        });
    }

    function confirmDelete(kodeIzin) {
        Swal.fire({
            title: 'Hapus Pengajuan?',
            text: 'Pengajuan izin akan dihapus secara permanen',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then(function (res) {
            if (res.isConfirmed) {
                var form = document.getElementById('form-delete');
                form.action = '/presensi/izin/' + kodeIzin;
                form.submit();
            }
        });
    }
</script>
@endpush