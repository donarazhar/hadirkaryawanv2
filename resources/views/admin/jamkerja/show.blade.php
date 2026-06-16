@extends('admin.layouts.admin')

@section('title', 'Detail Jam Kerja')
@section('page-title', 'Detail Jam Kerja')

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
        --purple:     #8B5CF6;
        --purple-soft:#F5F3FF;
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
        width: 46px; height: 46px; border-radius: 12px; background: var(--blue-soft);
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .detail-header-icon i { font-size: 22px; color: var(--blue); }
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
    .meta-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
    @media (max-width: 992px) { .meta-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 576px) { .meta-grid { grid-template-columns: 1fr; } }

    .meta-card {
        background: var(--white); border: 1px solid var(--slate-200); border-radius: var(--radius);
        padding: 20px; box-shadow: var(--shadow); display: flex; flex-direction: column; align-items: flex-start;
    }
    .meta-icon { width: 36px; height: 36px; border-radius: 10px; background: var(--slate-50); display: flex; align-items: center; justify-content: center; font-size: 18px; color: var(--slate-600); margin-bottom: 12px; }
    .meta-label { font-size: 11.5px; font-weight: 700; color: var(--slate-500); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
    .meta-value { font-size: 16px; font-weight: 800; color: var(--slate-900); }
    
    .code-pill { display: inline-block; background: var(--purple-soft); color: var(--purple); font-family: monospace; font-size: 14px; font-weight: 700; padding: 2px 8px; border-radius: 6px; border: 1px solid #DDD6FE; }
    .badge-pill { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 50px; font-size: 11.5px; font-weight: 700; }
    
    .bp-multi { background: var(--blue-soft); color: var(--blue-dark); border: 1px solid var(--blue-mid); }
    .bp-reg { background: var(--slate-100); color: var(--slate-700); border: 1px solid var(--slate-200); }
    .bp-yes { background: var(--amber-soft); color: #B45309; }
    .bp-no { background: var(--slate-100); color: var(--slate-500); }

    /* ── SCHEDULE SECTION ── */
    .sched-card { background: var(--white); border: 1px solid var(--slate-200); border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; }
    .sched-head { padding: 18px 24px; border-bottom: 1px solid var(--slate-100); display: flex; align-items: center; justify-content: space-between; }
    .sched-head-title { display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 800; color: var(--slate-900); }
    .sched-head-title i { font-size: 18px; color: var(--purple); }
    .sched-body { padding: 24px; }

    /* Regular Schedule Grid */
    .reg-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
    @media (max-width: 768px) { .reg-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 480px) { .reg-grid { grid-template-columns: 1fr; } }

    .time-box { background: var(--slate-50); border: 1px solid var(--slate-200); border-radius: 12px; padding: 16px; text-align: center; }
    .tb-icon { font-size: 24px; margin-bottom: 8px; }
    .tb-icon.bgn { color: var(--blue); }
    .tb-icon.in { color: var(--green); }
    .tb-icon.end { color: var(--amber); }
    .tb-icon.out { color: var(--red); }
    .tb-label { font-size: 11.5px; font-weight: 700; color: var(--slate-500); text-transform: uppercase; margin-bottom: 4px; }
    .tb-val { font-size: 20px; font-weight: 800; color: var(--slate-900); font-family: monospace; }

    .duration-box { margin-top: 20px; background: var(--blue-soft); border: 1px solid var(--blue-mid); border-radius: 12px; padding: 16px 20px; display: flex; align-items: center; gap: 12px; }
    .db-icon { width: 36px; height: 36px; border-radius: 10px; background: var(--white); display: flex; align-items: center; justify-content: center; font-size: 18px; color: var(--blue); }
    .db-text { font-size: 13px; color: var(--blue-dark); font-weight: 500; }
    .db-text strong { font-weight: 800; font-size: 14px; }

    /* Multi Shift List */
    .shift-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
    @media (max-width: 768px) { .shift-grid { grid-template-columns: 1fr; } }

    .shift-card { background: var(--white); border: 1px solid var(--slate-200); border-radius: 12px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
    .sc-head { background: var(--slate-50); border-bottom: 1px solid var(--slate-200); padding: 12px 16px; display: flex; align-items: center; gap: 10px; }
    .sc-no { width: 24px; height: 24px; background: var(--purple); color: var(--white); border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 800; }
    .sc-name { font-size: 13px; font-weight: 800; color: var(--slate-900); }
    
    .sc-body { padding: 16px; display: flex; flex-direction: column; gap: 10px; }
    .sc-row { display: flex; justify-content: space-between; align-items: center; padding-bottom: 10px; border-bottom: 1px dashed var(--slate-200); }
    .sc-row:last-child { padding-bottom: 0; border-bottom: none; }
    .sc-lbl { font-size: 12px; font-weight: 600; color: var(--slate-600); display: flex; align-items: center; gap: 6px; }
    .sc-lbl i { font-size: 14px; }
    .sc-val { font-size: 13px; font-weight: 700; color: var(--slate-900); font-family: monospace; background: var(--slate-50); padding: 2px 8px; border-radius: 4px; border: 1px solid var(--slate-200); }

    /* ── ALERT BOXES ── */
    .alert-box { display: flex; align-items: flex-start; gap: 12px; padding: 16px; border-radius: 10px; margin-top: 20px; }
    .alert-box i { font-size: 20px; line-height: 1; }
    .alert-box-warning { background: var(--amber-soft); border: 1px solid #FDE68A; color: #B45309; }
    .ab-text { font-size: 12.5px; font-weight: 500; line-height: 1.5; padding-top: 2px; }
    .ab-text strong { font-weight: 800; }
</style>
@endpush

@section('content')
<div class="detail-wrap">

    {{-- HEADER --}}
    <div class="detail-header">
        <div class="detail-header-left">
            <div class="detail-header-icon">
                <i class="mdi mdi-clock-check-outline"></i>
            </div>
            <div>
                <div class="detail-header-title">Detail Jam Kerja</div>
                <div class="detail-header-sub">Informasi lengkap konfigurasi jam kerja</div>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('panel.jamkerja.index') }}" class="btn-hdr">
                <i class="mdi mdi-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('panel.jamkerja.edit', $jamkerja->kode_jam_kerja) }}" class="btn-hdr btn-edit">
                <i class="mdi mdi-pencil"></i> Edit
            </a>
        </div>
    </div>

    {{-- META CARDS --}}
    <div class="meta-grid">
        <div class="meta-card">
            <div class="meta-icon"><i class="mdi mdi-barcode-scan"></i></div>
            <div class="meta-label">Kode Jam Kerja</div>
            <div class="meta-value"><span class="code-pill">{{ $jamkerja->kode_jam_kerja }}</span></div>
        </div>
        <div class="meta-card">
            <div class="meta-icon"><i class="mdi mdi-text-box-outline"></i></div>
            <div class="meta-label">Nama Jam Kerja</div>
            <div class="meta-value">{{ $jamkerja->nama_jam_kerja }}</div>
        </div>
        <div class="meta-card">
            <div class="meta-icon"><i class="mdi mdi-shape-outline"></i></div>
            <div class="meta-label">Tipe Jam Kerja</div>
            <div class="meta-value">
                @if($jamkerja->tipe_jam_kerja == 'multi_shift')
                    <span class="badge-pill bp-multi"><i class="mdi mdi-layers"></i> Multi Shift</span>
                @else
                    <span class="badge-pill bp-reg"><i class="mdi mdi-clock-outline"></i> Regular</span>
                @endif
            </div>
        </div>
        <div class="meta-card">
            <div class="meta-icon"><i class="mdi mdi-moon-waning-crescent"></i></div>
            <div class="meta-label">Lintas Hari (Malam)</div>
            <div class="meta-value">
                @if($jamkerja->lintashari == '1')
                    <span class="badge-pill bp-yes"><i class="mdi mdi-weather-night"></i> Ya</span>
                @else
                    <span class="badge-pill bp-no"><i class="mdi mdi-minus"></i> Tidak</span>
                @endif
            </div>
        </div>
    </div>

    {{-- SCHEDULE SECTION --}}
    <div class="sched-card">
        <div class="sched-head">
            <div class="sched-head-title">
                <i class="mdi mdi-clock-time-four-outline"></i>
                Konfigurasi Waktu Jadwal
            </div>
        </div>
        <div class="sched-body">
            
            @if($jamkerja->tipe_jam_kerja == 'multi_shift')
                {{-- MULTI SHIFT --}}
                <div style="font-size: 13px; font-weight: 700; color: var(--slate-600); margin-bottom: 16px;">
                    Daftar Shift (Total {{ $jamkerja->shifts->count() }} Shift/Hari)
                </div>
                
                <div class="shift-grid">
                    @foreach($jamkerja->shifts as $shift)
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
                            <div class="sc-row" style="background:var(--slate-50); padding:10px; border-radius:8px; border-bottom:none;">
                                <span class="sc-lbl" style="color:var(--slate-700);"><i class="mdi mdi-timer-outline"></i> Durasi Shift</span>
                                <span class="sc-val" style="background:var(--white); border-color:var(--slate-300);">
                                    {{ \Carbon\Carbon::parse($shift->jam_masuk)->diffInHours(\Carbon\Carbon::parse($shift->jam_pulang)) }}J {{ \Carbon\Carbon::parse($shift->jam_masuk)->diffInMinutes(\Carbon\Carbon::parse($shift->jam_pulang)) % 60 }}M
                                </span>
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
                        <div class="tb-val">{{ date('H:i', strtotime($jamkerja->awal_jam_masuk)) }}</div>
                    </div>
                    <div class="time-box" style="background:var(--green-soft); border-color:#A7F3D0;">
                        <i class="mdi mdi-login tb-icon in"></i>
                        <div class="tb-label" style="color:#065F46;">Jam Masuk</div>
                        <div class="tb-val" style="color:#065F46;">{{ date('H:i', strtotime($jamkerja->jam_masuk)) }}</div>
                    </div>
                    <div class="time-box">
                        <i class="mdi mdi-ray-end-arrow tb-icon end"></i>
                        <div class="tb-label">Akhir Jam Masuk</div>
                        <div class="tb-val">{{ date('H:i', strtotime($jamkerja->akhir_jam_masuk)) }}</div>
                    </div>
                    <div class="time-box" style="background:var(--red-soft); border-color:#FECACA;">
                        <i class="mdi mdi-logout tb-icon out"></i>
                        <div class="tb-label" style="color:#991B1B;">Jam Pulang</div>
                        <div class="tb-val" style="color:#991B1B;">{{ date('H:i', strtotime($jamkerja->jam_pulang)) }}</div>
                    </div>
                </div>

                <div class="duration-box">
                    <div class="db-icon"><i class="mdi mdi-timer-outline"></i></div>
                    <div class="db-text">
                        Total durasi jam kerja dari jam masuk ke jam pulang adalah 
                        <strong>
                            {{ \Carbon\Carbon::parse($jamkerja->jam_masuk)->diffInHours(\Carbon\Carbon::parse($jamkerja->jam_pulang)) }} Jam 
                            {{ \Carbon\Carbon::parse($jamkerja->jam_masuk)->diffInMinutes(\Carbon\Carbon::parse($jamkerja->jam_pulang)) % 60 }} Menit
                        </strong>.
                    </div>
                </div>
            @endif

            @if($total_konfigurasi_dept > 0)
                <div class="alert-box alert-box-warning">
                    <i class="mdi mdi-alert-circle-outline"></i>
                    <div class="ab-text">
                        <strong>Perhatian:</strong> Jam kerja ini sedang aktif digunakan oleh <strong>{{ $total_konfigurasi_dept }}</strong> konfigurasi departemen/karyawan. Perubahan apa pun akan memengaruhi laporan absensi mereka secara langsung.
                    </div>
                </div>
            @endif

        </div>
    </div>

</div>
@endsection