@extends('admin.layouts.admin')

@section('title', 'Detail Konfigurasi Jam Kerja Departemen')
@section('page-title', 'Detail Konfigurasi Jam Kerja Departemen')

@push('styles')
<style>
    :root {
        --blue:       #2563EB;
        --blue-dark:  #1D4ED8;
        --blue-soft:  #EFF6FF;
        --blue-mid:   #BFDBFE;
        --green:      #10B981;
        --green-soft: #ECFDF5;
        --red:        #EF4444;
        --red-soft:   #FEF2F2;
        --amber:      #F59E0B;
        --amber-soft: #FFFBEB;
        --indigo:     #6366F1;
        --indigo-soft:#EEF2FF;
        --slate-900:  #111827;
        --slate-700:  #374151;
        --slate-600:  #4B5563;
        --slate-400:  #9CA3AF;
        --slate-300:  #D1D5DB;
        --slate-200:  #E5E7EB;
        --slate-100:  #F3F4F6;
        --slate-50:   #F9FAFB;
        --white:      #FFFFFF;
        --shadow:     0 1px 3px rgba(0,0,0,0.06),0 1px 2px rgba(0,0,0,0.04);
        --radius:     14px;
        --radius-sm:  10px;
    }

    .detail-wrap { display: flex; flex-direction: column; gap: 20px; }

    /* ── PAGE HEADER ── */
    .detail-header {
        background: var(--white);
        border: 1px solid var(--slate-200);
        border-radius: var(--radius);
        padding: 20px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        box-shadow: var(--shadow);
        flex-wrap: wrap;
    }

    .detail-header-left { display: flex; align-items: center; gap: 14px; }
    .detail-header-icon {
        width: 46px; height: 46px; border-radius: 12px; background: var(--indigo-soft);
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .detail-header-icon i { font-size: 22px; color: var(--indigo); }
    .detail-header-title { font-size: 17px; font-weight: 800; color: var(--slate-900); letter-spacing: -0.2px; }
    .detail-header-sub   { font-size: 12px; color: var(--slate-400); margin-top: 2px; }

    .header-actions { display: flex; gap: 10px; }
    .btn-hdr {
        display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px;
        border: 1.5px solid var(--slate-200); border-radius: 9px; font-family: 'Inter', sans-serif;
        font-size: 13px; font-weight: 700; cursor: pointer; transition: all 0.15s;
        text-decoration: none; background: var(--white); color: var(--slate-700);
    }
    .btn-hdr:hover { background: var(--slate-50); color: var(--slate-900); border-color: var(--slate-300); }
    .btn-edit { background: var(--amber-soft); border-color: #FDE68A; color: #D97706; }
    .btn-edit:hover { background: #FEF3C7; border-color: #FCD34D; color: #B45309; }

    /* ── META CARDS ── */
    .meta-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
    @media (max-width: 768px) { .meta-grid { grid-template-columns: 1fr; } }

    .meta-card {
        background: var(--white); border: 1px solid var(--slate-200); border-radius: var(--radius);
        padding: 20px; box-shadow: var(--shadow); display: flex; flex-direction: column; align-items: flex-start;
    }
    .meta-icon { width: 36px; height: 36px; border-radius: 10px; background: var(--slate-50); display: flex; align-items: center; justify-content: center; font-size: 18px; color: var(--slate-600); margin-bottom: 12px; }
    .meta-label { font-size: 11.5px; font-weight: 700; color: var(--slate-500); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
    .meta-value { font-size: 15px; font-weight: 800; color: var(--slate-900); }
    
    .code-pill { display: inline-block; background: var(--indigo-soft); color: var(--indigo); font-family: monospace; font-size: 14px; font-weight: 700; padding: 2px 8px; border-radius: 6px; border: 1px solid #E0E7FF; }
    .m-item { display: flex; align-items: center; gap: 8px; }

    /* ── SCHEDULE SECTION ── */
    .sched-card { background: var(--white); border: 1px solid var(--slate-200); border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; }
    .sched-head { padding: 18px 24px; border-bottom: 1px solid var(--slate-100); display: flex; align-items: center; justify-content: space-between; }
    .sched-head-title { display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 800; color: var(--slate-900); }
    .sched-head-title i { font-size: 18px; color: var(--indigo); }
    .sched-body { padding: 24px; }

    /* Day Block Layout */
    .day-block { display: flex; flex-direction: column; gap: 20px; }
    .day-item { background: var(--slate-50); border: 1px solid var(--slate-200); border-radius: 12px; overflow: hidden; }
    .day-header { background: var(--white); padding: 16px 20px; border-bottom: 1px solid var(--slate-200); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; }
    .day-title { display: flex; align-items: center; gap: 10px; font-size: 14px; font-weight: 800; color: var(--slate-900); }
    .day-num { width: 26px; height: 26px; background: var(--slate-200); color: var(--slate-700); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 12px; }
    
    .day-badges { display: flex; align-items: center; gap: 8px; }
    .bdg { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; border: 1px solid transparent; }
    .bdg-multi { background: var(--blue-soft); color: var(--blue-dark); border-color: var(--blue-mid); }
    .bdg-reg { background: var(--amber-soft); color: #B45309; border-color: #FDE68A; }
    .bdg-night { background: var(--purple-soft); color: var(--purple); border-color: #DDD6FE; }
    
    .day-body { padding: 20px; }

    /* Regular Schedule Grid */
    .reg-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
    @media (max-width: 768px) { .reg-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 480px) { .reg-grid { grid-template-columns: 1fr; } }

    .time-box { background: var(--white); border: 1px solid var(--slate-200); border-radius: 10px; padding: 14px; text-align: center; }
    .tb-icon { font-size: 20px; margin-bottom: 6px; display: block; }
    .tb-icon.bgn { color: var(--blue); }
    .tb-icon.in { color: var(--green); }
    .tb-icon.end { color: var(--amber); }
    .tb-icon.out { color: var(--red); }
    .tb-label { font-size: 11px; font-weight: 700; color: var(--slate-500); text-transform: uppercase; margin-bottom: 4px; }
    .tb-val { font-size: 16px; font-weight: 800; color: var(--slate-900); font-family: monospace; }

    /* Multi Shift List */
    .shift-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
    @media (max-width: 768px) { .shift-grid { grid-template-columns: 1fr; } }

    .shift-card { background: var(--white); border: 1px solid var(--slate-200); border-radius: 10px; overflow: hidden; }
    .sc-head { background: var(--slate-50); border-bottom: 1px solid var(--slate-200); padding: 10px 14px; display: flex; align-items: center; gap: 8px; }
    .sc-no { width: 22px; height: 22px; background: var(--blue); color: var(--white); border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 800; }
    .sc-name { font-size: 12px; font-weight: 800; color: var(--slate-900); }
    
    .sc-body { padding: 12px 14px; display: flex; flex-direction: column; gap: 8px; }
    .sc-row { display: flex; justify-content: space-between; align-items: center; padding-bottom: 8px; border-bottom: 1px dashed var(--slate-200); }
    .sc-row:last-child { padding-bottom: 0; border-bottom: none; }
    .sc-lbl { font-size: 11px; font-weight: 600; color: var(--slate-600); display: flex; align-items: center; gap: 6px; }
    .sc-lbl i { font-size: 13px; }
    .sc-val { font-size: 12px; font-weight: 700; color: var(--slate-900); font-family: monospace; background: var(--slate-50); padding: 2px 6px; border-radius: 4px; border: 1px solid var(--slate-200); }

    /* ── ALERT BOXES ── */
    .alert-box { display: flex; align-items: flex-start; gap: 12px; padding: 16px; border-radius: 10px; margin-top: 20px; }
    .alert-box i { font-size: 20px; line-height: 1; }
    .alert-box-info { background: var(--indigo-soft); border: 1px solid #E0E7FF; color: var(--indigo); }
    .ab-text { font-size: 12.5px; font-weight: 500; line-height: 1.5; padding-top: 2px; }
    .ab-text strong { font-weight: 800; }

    /* Empty State */
    .empty-state { text-align: center; padding: 40px 20px; color: var(--slate-500); }
    .empty-state i { font-size: 48px; color: var(--slate-300); margin-bottom: 10px; display: block; }
    .empty-state p { margin: 0; font-size: 14px; font-weight: 600; }
</style>
@endpush

@section('content')
<div class="detail-wrap">

    {{-- HEADER --}}
    <div class="detail-header">
        <div class="detail-header-left">
            <div class="detail-header-icon">
                <i class="mdi mdi-calendar-search"></i>
            </div>
            <div>
                <div class="detail-header-title">Detail Konfigurasi</div>
                <div class="detail-header-sub">Rincian jadwal hari kerja untuk departemen</div>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('panel.konfigurasi-jk-dept.index') }}" class="btn-hdr">
                <i class="mdi mdi-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('panel.konfigurasi-jk-dept.edit', $konfigurasi->kode_jk_dept) }}" class="btn-hdr btn-edit">
                <i class="mdi mdi-pencil"></i> Edit
            </a>
        </div>
    </div>

    {{-- META CARDS --}}
    <div class="meta-grid">
        <div class="meta-card">
            <div class="meta-icon"><i class="mdi mdi-barcode-scan"></i></div>
            <div class="meta-label">Kode Konfigurasi</div>
            <div class="meta-value"><span class="code-pill">{{ $konfigurasi->kode_jk_dept }}</span></div>
        </div>
        <div class="meta-card">
            <div class="meta-icon"><i class="mdi mdi-office-building text-blue"></i></div>
            <div class="meta-label">Nama Cabang</div>
            <div class="meta-value m-item">{{ $konfigurasi->cabang->nama_cabang ?? 'N/A' }}</div>
        </div>
        <div class="meta-card">
            <div class="meta-icon"><i class="mdi mdi-sitemap text-green"></i></div>
            <div class="meta-label">Departemen</div>
            <div class="meta-value m-item">{{ $konfigurasi->departemen->nama_dept ?? 'N/A' }}</div>
        </div>
    </div>

    {{-- SCHEDULE SECTION --}}
    <div class="sched-card">
        <div class="sched-head">
            <div class="sched-head-title">
                <i class="mdi mdi-calendar-week"></i>
                Daftar Jadwal Per Hari ({{ $konfigurasi->details->count() }} Hari)
            </div>
        </div>
        <div class="sched-body">
            
            @if($konfigurasi->details->count() > 0)
                <div class="day-block">
                    @foreach($konfigurasi->details as $index => $detail)
                    <div class="day-item">
                        <div class="day-header">
                            <div class="day-title">
                                <div class="day-num">{{ $index + 1 }}</div>
                                <div>
                                    <i class="mdi mdi-calendar text-indigo"></i> Hari {{ $detail->hari }}
                                </div>
                            </div>
                            <div class="day-badges">
                                @if($detail->jamKerja)
                                    <span class="bdg bdg-reg" style="background:var(--slate-100); border-color:var(--slate-300); color:var(--slate-800);">
                                        <i class="mdi mdi-tag"></i> {{ $detail->jamKerja->kode_jam_kerja }} : {{ $detail->jamKerja->nama_jam_kerja }}
                                    </span>

                                    @if($detail->jamKerja->tipe_jam_kerja == 'multi_shift')
                                        <span class="bdg bdg-multi"><i class="mdi mdi-layers"></i> Multi Shift</span>
                                    @else
                                        <span class="bdg bdg-reg"><i class="mdi mdi-clock-outline"></i> Regular</span>
                                    @endif

                                    @if($detail->jamKerja->lintashari == '1')
                                        <span class="bdg bdg-night"><i class="mdi mdi-weather-night"></i> Lintas Hari</span>
                                    @endif
                                @else
                                    <span class="bdg" style="background:var(--red-soft); color:var(--red); border-color:#FECACA;">
                                        <i class="mdi mdi-alert"></i> Tidak Ada Jadwal
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="day-body">
                            @if($detail->jamKerja)
                                @if($detail->jamKerja->tipe_jam_kerja == 'multi_shift')
                                    {{-- MULTI SHIFT --}}
                                    <div class="shift-grid">
                                        @foreach($detail->jamKerja->shifts as $shift)
                                        <div class="shift-card">
                                            <div class="sc-head">
                                                <div class="sc-no">{{ $shift->shift_ke }}</div>
                                                <div class="sc-name">{{ $shift->nama_shift }}</div>
                                            </div>
                                            <div class="sc-body">
                                                <div class="sc-row">
                                                    <span class="sc-lbl"><i class="mdi mdi-ray-start-arrow text-primary"></i> Awal Masuk</span>
                                                    <span class="sc-val">{{ date('H:i', strtotime($shift->awal_jam_masuk)) }}</span>
                                                </div>
                                                <div class="sc-row">
                                                    <span class="sc-lbl"><i class="mdi mdi-login text-success"></i> Jam Masuk</span>
                                                    <span class="sc-val">{{ date('H:i', strtotime($shift->jam_masuk)) }}</span>
                                                </div>
                                                <div class="sc-row">
                                                    <span class="sc-lbl"><i class="mdi mdi-ray-end-arrow text-warning"></i> Akhir Masuk</span>
                                                    <span class="sc-val">{{ date('H:i', strtotime($shift->akhir_jam_masuk)) }}</span>
                                                </div>
                                                <div class="sc-row">
                                                    <span class="sc-lbl"><i class="mdi mdi-logout text-danger"></i> Jam Pulang</span>
                                                    <span class="sc-val">{{ date('H:i', strtotime($shift->jam_pulang)) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    {{-- REGULAR --}}
                                    <div class="reg-grid">
                                        <div class="time-box">
                                            <i class="mdi mdi-ray-start-arrow tb-icon bgn"></i>
                                            <div class="tb-label">Awal Jam Masuk</div>
                                            <div class="tb-val">{{ date('H:i', strtotime($detail->jamKerja->awal_jam_masuk)) }}</div>
                                        </div>
                                        <div class="time-box" style="background:var(--green-soft); border-color:#A7F3D0;">
                                            <i class="mdi mdi-login tb-icon in"></i>
                                            <div class="tb-label" style="color:#065F46;">Jam Masuk</div>
                                            <div class="tb-val" style="color:#065F46;">{{ date('H:i', strtotime($detail->jamKerja->jam_masuk)) }}</div>
                                        </div>
                                        <div class="time-box">
                                            <i class="mdi mdi-ray-end-arrow tb-icon end"></i>
                                            <div class="tb-label">Akhir Jam Masuk</div>
                                            <div class="tb-val">{{ date('H:i', strtotime($detail->jamKerja->akhir_jam_masuk)) }}</div>
                                        </div>
                                        <div class="time-box" style="background:var(--red-soft); border-color:#FECACA;">
                                            <i class="mdi mdi-logout tb-icon out"></i>
                                            <div class="tb-label" style="color:#991B1B;">Jam Pulang</div>
                                            <div class="tb-val" style="color:#991B1B;">{{ date('H:i', strtotime($detail->jamKerja->jam_pulang)) }}</div>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <div class="empty-state" style="padding: 10px;">
                                    <p>Detail jam kerja tidak ditemukan atau telah dihapus.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                
                @php
                    $multiShiftCount = $konfigurasi->details->filter(function($detail) {
                        return $detail->jamKerja && $detail->jamKerja->tipe_jam_kerja == 'multi_shift';
                    })->count();
                @endphp

                <div class="alert-box alert-box-info">
                    <i class="mdi mdi-information-outline"></i>
                    <div class="ab-text">
                        <strong>Ringkasan:</strong>
                        Konfigurasi ini menetapkan <strong>{{ $konfigurasi->details->count() }} hari kerja aktif</strong> untuk departemen <strong>{{ $konfigurasi->departemen->nama_dept ?? 'N/A' }}</strong> di cabang <strong>{{ $konfigurasi->cabang->nama_cabang ?? 'N/A' }}</strong>.
                        @if($multiShiftCount > 0)
                            Terdapat <strong>{{ $multiShiftCount }} hari</strong> yang menggunakan jadwal <strong>Multi Shift</strong>.
                        @endif
                    </div>
                </div>

            @else
                <div class="empty-state">
                    <i class="mdi mdi-calendar-blank"></i>
                    <p>Tidak ada konfigurasi jadwal hari kerja yang ditemukan.</p>
                </div>
            @endif

        </div>
    </div>

</div>
@endsection