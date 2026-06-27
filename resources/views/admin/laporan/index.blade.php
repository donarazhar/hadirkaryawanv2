@extends('admin.layouts.admin')

@section('title', 'Laporan Presensi')
@section('page-title', 'Laporan Presensi')

@push('styles')
<style>
    :root {
        --blue:       #2563EB;
        --blue-dark:  #1D4ED8;
        --blue-soft:  #EFF6FF;
        --blue-mid:   #BFDBFE;
        --green:      #10B981;
        --green-soft: #ECFDF5;
        --amber:      #F59E0B;
        --amber-soft: #FFFBEB;
        --red:        #EF4444;
        --red-soft:   #FEF2F2;
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

    .lap-wrap { display: flex; flex-direction: column; gap: 20px; }

    /* ── PAGE HEADER ── */
    .lap-header {
        background: var(--white);
        border: 1px solid var(--slate-200);
        border-radius: var(--radius);
        padding: 20px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        box-shadow: var(--shadow);
    }

    .lap-header-left { display: flex; align-items: center; gap: 14px; }

    .lap-header-icon {
        width: 46px; height: 46px;
        border-radius: 12px;
        background: var(--blue-soft);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .lap-header-icon i { font-size: 22px; color: var(--blue); }

    .lap-header-title { font-size: 17px; font-weight: 800; color: var(--slate-900); letter-spacing: -0.2px; }
    .lap-header-sub   { font-size: 12px; color: var(--slate-400); margin-top: 2px; }

    .btn-add {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        background: var(--green);
        color: var(--white);
        border: none;
        border-radius: 9px;
        font-family: 'Inter', sans-serif;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.15s;
        white-space: nowrap;
        text-decoration: none;
    }
    .btn-add:hover { background: #059669; color: var(--white); }
    .btn-add i { font-size: 16px; }

    /* ── ALERTS ── */
    .alert-custom {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        border-radius: var(--radius-sm);
        font-size: 13px;
        font-weight: 600;
    }
    .alert-success-c { background: var(--green-soft); color: #065F46; border: 1px solid #A7F3D0; }
    .alert-warning-c { background: var(--amber-soft); color: #92400E; border: 1px solid #FDE68A; }
    .alert-custom i  { font-size: 18px; flex-shrink: 0; }

    /* ── FILTER CARD ── */
    .filter-card {
        background: var(--white);
        border: 1px solid var(--slate-200);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
    }

    .filter-head {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 14px 20px;
        border-bottom: 1px solid var(--slate-100);
        font-size: 12px;
        font-weight: 700;
        color: var(--slate-600);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .filter-head i { font-size: 15px; color: var(--blue); }

    .filter-body { padding: 18px 20px; }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 12px;
        align-items: end;
    }

    .fg { display: flex; flex-direction: column; gap: 5px; }

    .fg label {
        font-size: 11px;
        font-weight: 700;
        color: var(--slate-600);
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .fg select, .fg input {
        height: 38px;
        border: 1.5px solid var(--slate-200);
        border-radius: 9px;
        padding: 0 11px;
        font-family: 'Inter', sans-serif;
        font-size: 13px;
        color: var(--slate-900);
        background: var(--white);
        outline: none;
        transition: border-color 0.15s, box-shadow 0.15s;
        width: 100%;
        appearance: auto;
    }

    .fg select:focus, .fg input:focus {
        border-color: var(--blue);
        box-shadow: 0 0 0 3px rgba(37,99,235,0.10);
    }

    .filter-actions {
        display: flex;
        gap: 8px;
        align-items: flex-end;
        flex-wrap: wrap;
    }

    .btn-filter {
        height: 38px;
        padding: 0 16px;
        border: none;
        border-radius: 9px;
        font-family: 'Inter', sans-serif;
        font-size: 12.5px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: background 0.15s, opacity 0.15s;
        white-space: nowrap;
        text-decoration: none;
    }

    .btn-filter:active { opacity: 0.85; }
    .btn-filter i { font-size: 15px; }

    .bf-primary { background: var(--blue);  color: var(--white); }
    .bf-primary:hover { background: var(--blue-dark); }

    .bf-red { background: var(--red-soft); color: var(--red); border: 1.5px solid #FECACA; }
    .bf-red:hover { background: #FEE2E2; color: var(--red); }

    .bf-green { background: var(--green-soft); color: var(--green); border: 1.5px solid #A7F3D0; }
    .bf-green:hover { background: #D1FAE5; color: var(--green); }

    /* ── TABLE CARD ── */
    .tbl-card {
        background: var(--white);
        border: 1px solid var(--slate-200);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
    }

    .tbl-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 20px;
        border-bottom: 1px solid var(--slate-100);
        gap: 12px;
        flex-wrap: wrap;
    }

    .tbl-card-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--slate-900);
        display: flex;
        align-items: center;
        gap: 7px;
    }
    .tbl-card-title i { font-size: 17px; color: var(--blue); }

    .tbl-meta {
        font-size: 11px;
        color: var(--slate-400);
        background: var(--slate-100);
        padding: 3px 10px;
        border-radius: 50px;
        font-weight: 600;
    }

    .tbl-wrap { overflow-x: auto; }

    .tbl-wrap table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
    }

    .tbl-wrap thead th {
        padding: 10px 14px;
        background: var(--slate-50);
        font-size: 10.5px;
        font-weight: 700;
        color: var(--slate-400);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid var(--slate-200);
        white-space: nowrap;
    }

    .tbl-wrap tbody td {
        padding: 11px 14px;
        font-size: 13px;
        color: var(--slate-700);
        border-bottom: 1px solid var(--slate-100);
        vertical-align: middle;
    }

    .tbl-wrap tbody tr:last-child td { border-bottom: none; }
    .tbl-wrap tbody tr:hover td { background: var(--slate-50); }

    /* No column */
    .no-cell {
        font-size: 12px;
        font-weight: 700;
        color: var(--slate-400);
        text-align: center;
    }

    /* Date cell */
    .date-cell {
        font-size: 13px;
        font-weight: 700;
        color: var(--slate-900);
        white-space: nowrap;
    }

    /* User cell */
    .user-cell { display: flex; align-items: center; gap: 10px; }
    .user-ava {
        width: 32px; height: 32px;
        border-radius: 8px;
        background: var(--blue-soft);
        display: flex; align-items: center; justify-content: center;
        color: var(--blue);
        font-size: 15px;
        flex-shrink: 0;
    }
    .user-name { font-size: 13px; font-weight: 700; color: var(--slate-900); }
    .user-nik  { font-size: 11px; color: var(--slate-400); }

    /* Shift badge */
    .shift-tag {
        display: inline-block;
        padding: 2px 7px;
        border-radius: 5px;
        font-size: 10px;
        font-weight: 700;
        background: var(--blue-soft);
        color: var(--blue);
        margin-left: 4px;
    }

    /* Time cell */
    .time-in  { font-size: 13px; font-weight: 800; color: var(--green); }
    .time-out { font-size: 13px; font-weight: 800; color: var(--blue); }
    .time-dash { color: var(--slate-300); font-weight: 700; }

    /* Status pill */
    .sp {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 9px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }
    .sp-hadir { background: var(--green-soft); color: var(--green); }
    .sp-izin  { background: var(--amber-soft); color: #D97706; }
    .sp-sakit { background: var(--purple-soft); color: var(--purple); }

    /* Keterangan cell */
    .ket-cell { font-size: 12px; color: var(--slate-500); font-style: italic; }

    /* Edit button */
    .btn-edit {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 5px 11px;
        border-radius: 7px;
        font-size: 11.5px;
        font-weight: 700;
        background: var(--amber-soft);
        color: #D97706;
        border: 1.5px solid #FDE68A;
        cursor: pointer;
        transition: background 0.15s;
        font-family: 'Inter', sans-serif;
    }
    .btn-edit:hover { background: #FEF3C7; }
    .btn-edit i { font-size: 13px; }

    /* Empty state */
    .tbl-empty {
        padding: 56px 16px;
        text-align: center;
        color: var(--slate-400);
    }
    .tbl-empty i { font-size: 44px; display: block; margin-bottom: 10px; color: var(--slate-200); }
    .tbl-empty p { font-size: 13px; font-weight: 600; margin: 0; }

    /* ── MODAL ── */
    .modal-content { border: none; border-radius: 16px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.15); }

    .modal-hd {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
        border-bottom: 1px solid var(--slate-200);
    }

    .modal-hd-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--slate-900);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .modal-hd-title i { font-size: 18px; color: var(--blue); }

    .btn-modal-x {
        width: 30px; height: 30px;
        border-radius: 8px;
        background: var(--slate-100);
        border: none;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        color: var(--slate-600);
        transition: background 0.15s;
        font-size: 0;
    }
    .btn-modal-x:hover { background: var(--slate-200); }
    .btn-modal-x i { font-size: 18px; }

    .modal-bd { padding: 20px; }

    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

    @media (max-width: 480px) { .form-row { grid-template-columns: 1fr; } }

    .fgroup { display: flex; flex-direction: column; gap: 5px; margin-bottom: 14px; }

    .fgroup label {
        font-size: 11px;
        font-weight: 700;
        color: var(--slate-600);
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .fgroup input, .fgroup select, .fgroup textarea {
        border: 1.5px solid var(--slate-200);
        border-radius: 9px;
        padding: 9px 12px;
        font-family: 'Inter', sans-serif;
        font-size: 13px;
        color: var(--slate-900);
        outline: none;
        transition: border-color 0.15s, box-shadow 0.15s;
        width: 100%;
    }

    .fgroup input:focus, .fgroup select:focus, .fgroup textarea:focus {
        border-color: var(--blue);
        box-shadow: 0 0 0 3px rgba(37,99,235,0.10);
    }

    .modal-foot {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        padding: 14px 20px;
        border-top: 1px solid var(--slate-100);
        background: var(--slate-50);
    }

    .btn-cancel {
        height: 36px;
        padding: 0 16px;
        border: 1.5px solid var(--slate-200);
        border-radius: 8px;
        font-family: 'Inter', sans-serif;
        font-size: 12.5px;
        font-weight: 700;
        color: var(--slate-600);
        background: var(--white);
        cursor: pointer;
        transition: background 0.15s;
    }
    .btn-cancel:hover { background: var(--slate-100); }

    .btn-save {
        height: 36px;
        padding: 0 20px;
        border: none;
        border-radius: 8px;
        font-family: 'Inter', sans-serif;
        font-size: 12.5px;
        font-weight: 700;
        color: var(--white);
        background: var(--blue);
        cursor: pointer;
        transition: background 0.15s;
    }
    .btn-save:hover { background: var(--blue-dark); }

    /* Responsive */
    @media (max-width: 640px) {
        .lap-header { flex-direction: column; align-items: flex-start; gap: 10px; padding: 16px; }
        .filter-grid { grid-template-columns: 1fr 1fr; }
        .filter-actions { flex-direction: column; width: 100%; }
        .btn-filter { width: 100%; justify-content: center; }
    }
    @media (max-width: 400px) {
        .filter-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="lap-wrap">

    {{-- ── PAGE HEADER ── --}}
    <div class="lap-header">
        <div class="lap-header-left">
            <div class="lap-header-icon">
                <i class="mdi mdi-file-chart"></i>
            </div>
            <div>
                <div class="lap-header-title">Laporan Presensi</div>
                <div class="lap-header-sub">Kelola & ekspor data kehadiran karyawan</div>
            </div>
        </div>
        <button type="button" class="btn-add" data-bs-toggle="modal" data-bs-target="#modal-create">
            <i class="mdi mdi-plus-circle"></i> Tambah Manual
        </button>
    </div>

    {{-- ── ALERTS ── --}}
    @if(Session::get('success'))
    <div class="alert-custom alert-success-c">
        <i class="mdi mdi-check-circle"></i>
        {{ Session::get('success') }}
    </div>
    @endif
    @if(Session::get('warning'))
    <div class="alert-custom alert-warning-c">
        <i class="mdi mdi-alert"></i>
        {{ Session::get('warning') }}
    </div>
    @endif

    {{-- ── FILTER CARD ── --}}
    <div class="filter-card">
        <div class="filter-head">
            <i class="mdi mdi-filter-outline"></i> Filter & Ekspor Data
        </div>
        <div class="filter-body">
            <form action="/panel/laporan" method="GET">
                <div class="filter-grid">
                    <div class="fg">
                        <label>Bulan</label>
                        <select name="bulan" id="bulan">
                            <option value="">Semua Bulan</option>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>{{ $namabulan[$i] }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="fg">
                        <label>Tahun</label>
                        <select name="tahun" id="tahun">
                            @php $tahunMulai = 2022; $tahunSekarang = date('Y'); @endphp
                            @for($t = $tahunMulai; $t <= $tahunSekarang; $t++)
                                <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="fg">
                        <label>Cabang</label>
                        <select name="branch_id" id="branch_id">
                            <option value="">Semua Cabang</option>
                            @foreach($branches as $c)
                                <option value="{{ $c->id }}" {{ $branch_id == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="fg">
                        <label>Unit</label>
                        <select name="unit_id" id="unit_id">
                            <option value="">Semua Unit</option>
                            @foreach($units as $d)
                                <option value="{{ $d->id }}" {{ $unit_id == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-actions">
                        <button type="submit" class="btn-filter bf-primary">
                            <i class="mdi mdi-magnify"></i> Tampilkan
                        </button>
                        <button type="submit" name="export" value="pdf"
                                formaction="{{ route('panel.laporan.export.pdf') }}"
                                formtarget="_blank"
                                class="btn-filter bf-red">
                            <i class="mdi mdi-file-pdf-box"></i> PDF
                        </button>
                        <button type="submit" name="export" value="excel"
                                formaction="{{ route('panel.laporan.export.excel') }}"
                                class="btn-filter bf-green">
                            <i class="mdi mdi-file-excel"></i> Excel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ── TABLE CARD ── --}}
    <div class="tbl-card">
        <div class="tbl-card-head">
            <div class="tbl-card-title">
                <i class="mdi mdi-table-account"></i>
                Data Kehadiran
            </div>
            <span class="tbl-meta">{{ count($presensi) }} record</span>
        </div>
        <div class="tbl-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width:40px;">No</th>
                        <th>Tanggal</th>
                        <th>Karyawan</th>
                        <th>Shift / Jadwal</th>
                        <th>Jam Masuk</th>
                        <th>Jam Pulang</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                        <th style="width:80px; text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($presensi) === 0)
                    <tr>
                        <td colspan="9">
                            <div class="tbl-empty">
                                <i class="mdi mdi-file-search-outline"></i>
                                <p>Tidak ada data presensi ditemukan.<br><span style="font-weight:400; color:var(--slate-400);">Coba ubah filter pencarian Anda.</span></p>
                            </div>
                        </td>
                    </tr>
                    @else
                        @foreach($presensi as $d)
                        <tr>
                            <td class="no-cell">{{ $loop->iteration }}</td>
                            <td>
                                <div class="date-cell">{{ date('d M Y', strtotime($d->tgl_presensi)) }}</div>
                            </td>
                            <td>
                                <div class="user-cell">
                                    <div class="user-ava"><i class="mdi mdi-account"></i></div>
                                    <div>
                                        <div class="user-name">{{ $d->nama_lengkap }}</div>
                                        <div class="user-nik">NIK: {{ $d->nik }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span style="font-size:13px; color:var(--slate-700); font-weight:600;">{{ $d->nama_jam_kerja }}</span>
                                @if($d->shift_ke)
                                    <span class="shift-tag">Shift {{ $d->shift_ke }}</span>
                                @endif
                            </td>
                            <td>
                                @if($d->jam_in)
                                    <span class="time-in">{{ $d->jam_in }}</span>
                                @else
                                    <span class="time-dash">—</span>
                                @endif
                            </td>
                            <td>
                                @if($d->jam_out)
                                    <span class="time-out">{{ $d->jam_out }}</span>
                                @else
                                    <span class="time-dash">—</span>
                                @endif
                            </td>
                            <td>
                                @if($d->status == 'h')
                                    <span class="sp sp-hadir"><i class="mdi mdi-check-circle" style="font-size:12px;"></i> Hadir</span>
                                @elseif($d->status == 'i')
                                    <span class="sp sp-izin"><i class="mdi mdi-calendar-remove" style="font-size:12px;"></i> Izin</span>
                                @elseif($d->status == 's')
                                    <span class="sp sp-sakit"><i class="mdi mdi-medical-bag" style="font-size:12px;"></i> Sakit</span>
                                @endif
                            </td>
                            <td>
                                <span class="ket-cell">{{ $d->keterangan ?: '—' }}</span>
                            </td>
                            <td style="text-align:center;">
                                <button type="button" class="btn-edit edit" id="{{ $d->id }}">
                                    <i class="mdi mdi-pencil"></i> Edit
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ── MODAL EDIT ── --}}
<div class="modal fade" id="modal-edit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-hd">
                <div class="modal-hd-title">
                    <i class="mdi mdi-pencil-circle"></i> Edit Data Presensi
                </div>
                <button type="button" class="btn-modal-x" data-bs-dismiss="modal">
                    <i class="mdi mdi-close"></i>
                </button>
            </div>
            <div id="loadeditform">
                <div class="modal-bd" style="text-align:center; padding:40px; color:var(--slate-400);">
                    <div style="width:32px;height:32px;border:3px solid #E5E7EB;border-top-color:#2563EB;border-radius:50%;animation:spin .7s linear infinite;margin:0 auto 10px;"></div>
                    <p style="font-size:13px;font-weight:600;margin:0;">Memuat form...</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── MODAL TAMBAH MANUAL ── --}}
<div class="modal fade" id="modal-create" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-hd">
                <div class="modal-hd-title">
                    <i class="mdi mdi-plus-circle"></i> Tambah Absensi Manual
                </div>
                <button type="button" class="btn-modal-x" data-bs-dismiss="modal">
                    <i class="mdi mdi-close"></i>
                </button>
            </div>
            <form action="{{ route('panel.laporan.store') }}" method="POST">
                @csrf
                <div class="modal-bd">

                    <div class="form-row">
                        <div class="fgroup">
                            <label>Cabang (Filter)</label>
                            <select id="create_cabang" class="filter-karyawan-create">
                                <option value="">Semua Cabang</option>
                                @foreach($branches as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="fgroup">
                            <label>Unit (Filter)</label>
                            <select id="create_dept" class="filter-karyawan-create">
                                <option value="">Semua Unit</option>
                                @foreach($units as $d)
                                    <option value="{{ $d->id }}">{{ $d->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="fgroup">
                        <label>Karyawan <span style="color:var(--red);">*</span></label>
                        <select name="nik" id="create_nik" required>
                            <option value="">Pilih Karyawan...</option>
                        </select>
                    </div>

                    <div class="fgroup">
                        <label>Tanggal Presensi <span style="color:var(--red);">*</span></label>
                        <input type="date" name="tgl_presensi" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="form-row">
                        <div class="fgroup">
                            <label>Jam Masuk</label>
                            <input type="time" name="jam_in">
                        </div>
                        <div class="fgroup">
                            <label>Jam Pulang</label>
                            <input type="time" name="jam_out">
                        </div>
                    </div>

                    <div class="fgroup">
                        <label>Status <span style="color:var(--red);">*</span></label>
                        <select name="status" required>
                            <option value="h">Hadir</option>
                            <option value="i">Izin</option>
                            <option value="s">Sakit</option>
                        </select>
                    </div>

                    <div class="fgroup" style="margin-bottom:0;">
                        <label>Keterangan / Alasan</label>
                        <input type="text" name="keterangan" placeholder="Contoh: Mesin error, lupa absen">
                    </div>

                </div>
                <div class="modal-foot">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-save"><i class="mdi mdi-content-save" style="font-size:15px;margin-right:4px;"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    // Edit button
    $('.edit').click(function () {
        var id = $(this).attr('id');
        $('#loadeditform').html('<div class="modal-bd" style="text-align:center;padding:40px;color:#9CA3AF;"><div style="width:32px;height:32px;border:3px solid #E5E7EB;border-top-color:#2563EB;border-radius:50%;animation:spin .7s linear infinite;margin:0 auto 10px;"></div><p style="font-size:13px;font-weight:600;margin:0;">Memuat form...</p></div>');
        $('#modal-edit').modal('show');

        $.ajax({
            type: 'POST',
            url: '/panel/laporan/edit',
            cache: false,
            data: { _token: "{{ csrf_token() }}", id: id },
            success: function (respond) {
                $('#loadeditform').html(respond);
            }
        });
    });

    // Load Karyawan for Create form
    loadKaryawanCreate();

    $('.filter-karyawan-create').change(function () {
        loadKaryawanCreate();
    });

    function loadKaryawanCreate() {
        $.ajax({
            type: 'POST',
            url: '/panel/laporan/getkaryawan',
            data: {
                _token: "{{ csrf_token() }}",
                branch_id: $('#create_cabang').val(),
                unit_id: $('#create_dept').val()
            },
            cache: false,
            success: function (respond) {
                $('#create_nik').html(respond);
            }
        });
    }
});
</script>
@endpush
