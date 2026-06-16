@extends('admin.layouts.admin')

@section('title', 'Verifikasi Wajah Karyawan')
@section('page-title', 'Verifikasi Wajah Karyawan')

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

    .face-wrap { display: flex; flex-direction: column; gap: 20px; }

    /* ── PAGE HEADER ── */
    .face-header {
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

    .face-header-left { display: flex; align-items: center; gap: 14px; }

    .face-header-icon {
        width: 46px; height: 46px;
        border-radius: 12px;
        background: var(--blue-soft);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .face-header-icon i { font-size: 22px; color: var(--blue); }

    .face-header-title { font-size: 17px; font-weight: 800; color: var(--slate-900); letter-spacing: -0.2px; }
    .face-header-sub   { font-size: 12px; color: var(--slate-400); margin-top: 2px; }

    .btn-hdr {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border: none;
        border-radius: 9px;
        font-family: 'Inter', sans-serif;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.15s;
        text-decoration: none;
        white-space: nowrap;
    }
    .btn-hdr i { font-size: 17px; }
    .btn-hdr-green { background: var(--green-soft); color: var(--green); border: 1.5px solid #A7F3D0; }
    .btn-hdr-green:hover { background: #D1FAE5; }

    /* ── STATS GRID ── */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }
    @media (max-width: 900px) { .stat-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 480px) { .stat-grid { grid-template-columns: 1fr; } }

    .stat-card {
        background: var(--white);
        border: 1px solid var(--slate-200);
        border-radius: var(--radius);
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        box-shadow: var(--shadow);
    }
    .stat-top { display: flex; align-items: center; justify-content: space-between; }
    .stat-title { font-size: 12px; font-weight: 700; color: var(--slate-500); text-transform: uppercase; letter-spacing: 0.5px; }
    .stat-icon {
        width: 32px; height: 32px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center; font-size: 18px;
    }
    .stat-icon.ic-total { background: var(--blue-soft); color: var(--blue); }
    .stat-icon.ic-enrol { background: var(--green-soft); color: var(--green); }
    .stat-icon.ic-notenrol { background: var(--amber-soft); color: var(--amber); }
    .stat-icon.ic-inactive { background: var(--red-soft); color: var(--red); }

    .stat-val-wrap { display: flex; align-items: baseline; gap: 8px; }
    .stat-val { font-size: 28px; font-weight: 800; color: var(--slate-900); letter-spacing: -1px; line-height: 1; }
    .stat-pct { font-size: 12px; font-weight: 700; padding: 2px 6px; border-radius: 4px; }
    .pct-green { background: var(--green-soft); color: var(--green); }

    /* ── ALERTS ── */
    .alert-c {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        border-radius: var(--radius-sm);
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 5px;
    }
    .alert-c i { font-size: 18px; flex-shrink: 0; }
    .alert-success-c { background: var(--green-soft); color: #065F46; border: 1px solid #A7F3D0; }
    .alert-danger-c  { background: var(--red-soft);   color: #991B1B; border: 1px solid #FECACA; }

    /* ── FILTER CARD ── */
    .filter-card {
        background: var(--white);
        border: 1px solid var(--slate-200);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
    }

    .filter-head {
        display: flex; align-items: center; gap: 8px;
        padding: 13px 20px; border-bottom: 1px solid var(--slate-100);
        font-size: 11px; font-weight: 700; color: var(--slate-600);
        text-transform: uppercase; letter-spacing: 0.5px;
    }
    .filter-head i { font-size: 15px; color: var(--blue); }

    .filter-body { padding: 16px 20px; }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr) auto auto;
        gap: 12px;
        align-items: end;
    }

    @media (max-width: 1200px) { .filter-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 640px) { .filter-grid { grid-template-columns: 1fr; } }

    .fg { display: flex; flex-direction: column; gap: 5px; }
    .fg label { font-size: 10.5px; font-weight: 700; color: var(--slate-600); text-transform: uppercase; letter-spacing: 0.4px; }
    .fg input, .fg select {
        height: 38px; border: 1.5px solid var(--slate-200); border-radius: 9px; padding: 0 12px;
        font-family: 'Inter', sans-serif; font-size: 13px; color: var(--slate-900);
        background: var(--white); outline: none; transition: border-color 0.15s, box-shadow 0.15s;
        width: 100%; appearance: auto;
    }
    .fg input:focus, .fg select:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(37,99,235,0.10); }
    .fg input::placeholder { color: var(--slate-400); }

    .btn-filter, .btn-reset {
        height: 38px; padding: 0 16px; border-radius: 9px;
        font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 700;
        cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
        transition: background 0.15s; white-space: nowrap; text-decoration: none; border: none;
    }
    .btn-filter { background: var(--blue); color: var(--white); }
    .btn-filter:hover { background: var(--blue-dark); }
    .btn-reset { background: var(--slate-100); color: var(--slate-600); border: 1px solid var(--slate-200); }
    .btn-reset:hover { background: var(--slate-200); color: var(--slate-700); }

    /* ── TABLE CARD ── */
    .tbl-card {
        background: var(--white);
        border: 1px solid var(--slate-200);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
    }

    .tbl-card-head {
        display: flex; align-items: center; justify-content: space-between;
        padding: 14px 20px; border-bottom: 1px solid var(--slate-100);
    }
    .tbl-card-title { font-size: 13px; font-weight: 700; color: var(--slate-900); display: flex; align-items: center; gap: 7px; }
    .tbl-card-title i { font-size: 17px; color: var(--blue); }
    .tbl-meta { font-size: 11px; color: var(--slate-400); background: var(--slate-100); padding: 3px 10px; border-radius: 50px; font-weight: 600; }

    .tbl-wrap { overflow-x: auto; }
    .tbl-wrap table { width: 100%; border-collapse: collapse; min-width: 900px; }

    .tbl-wrap thead th {
        padding: 12px 16px; background: var(--slate-50); font-size: 10.5px;
        font-weight: 700; color: var(--slate-400); text-transform: uppercase;
        letter-spacing: 0.5px; border-bottom: 1px solid var(--slate-200); white-space: nowrap; text-align: left;
    }

    .tbl-wrap tbody td {
        padding: 14px 16px; font-size: 13px; color: var(--slate-700);
        border-bottom: 1px solid var(--slate-100); vertical-align: middle;
    }
    .tbl-wrap tbody tr:last-child td { border-bottom: none; }
    .tbl-wrap tbody tr:hover td { background: var(--slate-50); transition: background 0.12s; }

    .no-cell { font-size: 12px; font-weight: 700; color: var(--slate-400); text-align: center; }

    /* User Cell */
    .user-cell { display: flex; align-items: center; gap: 10px; }
    .ava { width: 36px; height: 36px; border-radius: 10px; object-fit: cover; flex-shrink: 0; border: 2px solid var(--slate-200); }
    .ava-init {
        width: 36px; height: 36px; border-radius: 10px; background: var(--blue-soft); color: var(--blue);
        display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 800; flex-shrink: 0;
    }
    .user-name { font-size: 13px; font-weight: 700; color: var(--slate-900); }
    .user-nik { font-size: 11px; color: var(--slate-400); }

    /* Placement */
    .plc-cabang { font-size: 12.5px; font-weight: 700; color: var(--slate-900); }
    .plc-dept { font-size: 11px; color: var(--slate-500); }
    .plc-jabatan { font-size: 11px; color: var(--slate-400); }

    /* Enrollment Status Pill */
    .enr-stat { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 50px; font-size: 11px; font-weight: 700; margin-bottom: 4px; }
    .enr-acc { background: var(--green-soft); color: var(--green); border: 1px solid #A7F3D0; }
    .enr-non { background: var(--red-soft); color: var(--red); border: 1px solid #FECACA; }
    .enr-wait { background: var(--amber-soft); color: #D97706; border: 1px solid #FDE68A; }
    .enr-count { font-size: 11px; color: var(--slate-400); font-weight: 600; display: block; padding-left: 2px; }

    /* Time Cell */
    .time-date { font-size: 13px; font-weight: 700; color: var(--slate-900); }
    .time-hour { font-size: 11px; color: var(--slate-400); }

    /* Actions */
    .action-cell { display: flex; align-items: center; justify-content: center; gap: 6px; }

    .btn-act {
        display: inline-flex; align-items: center; justify-content: center;
        width: 32px; height: 32px; border-radius: 8px; border: 1px solid transparent;
        cursor: pointer; transition: background 0.15s; padding: 0; text-decoration: none;
    }
    .btn-act i { font-size: 16px; }
    .btn-act-blue { background: var(--blue-soft); color: var(--blue); border-color: var(--blue-mid); }
    .btn-act-blue:hover { background: #DBEAFE; }
    .btn-act-yellow { background: var(--amber-soft); color: #D97706; border-color: #FDE68A; }
    .btn-act-yellow:hover { background: #FEF3C7; }
    .btn-act-green { background: var(--green-soft); color: var(--green); border-color: #A7F3D0; }
    .btn-act-green:hover { background: #D1FAE5; }
    .btn-act-red { background: var(--red-soft); color: var(--red); border-color: #FECACA; }
    .btn-act-red:hover { background: #FEE2E2; }
    .btn-act-dis { background: var(--slate-50); color: var(--slate-300); border-color: var(--slate-200); cursor: not-allowed; }

    /* Empty state */
    .tbl-empty { padding: 56px 16px; text-align: center; color: var(--slate-400); }
    .tbl-empty i { font-size: 44px; display: block; margin-bottom: 10px; color: var(--slate-200); }
    .tbl-empty p { font-size: 13px; font-weight: 600; margin: 0; color: var(--slate-600); }

    /* ── PAGINATION ── */
    .pag-wrap {
        display: flex; align-items: center; justify-content: space-between;
        padding: 14px 20px; border-top: 1px solid var(--slate-100); background: var(--slate-50);
        flex-wrap: wrap; gap: 10px;
    }

    .pag-info { font-size: 12px; color: var(--slate-400); font-weight: 600; }
    .pag-info strong { color: var(--slate-900); }

    .pag-list { display: flex; align-items: center; gap: 4px; list-style: none; margin: 0; padding: 0; }
    .pag-list .pag-item a, .pag-list .pag-item span {
        display: flex; align-items: center; justify-content: center;
        min-width: 32px; height: 32px; padding: 0 8px; border-radius: 7px;
        font-size: 12px; font-weight: 700; border: 1.5px solid var(--slate-200);
        background: var(--white); color: var(--slate-600); text-decoration: none;
        transition: all 0.12s; cursor: pointer;
    }
    .pag-list .pag-item a:hover { background: var(--blue-soft); border-color: var(--blue-mid); color: var(--blue); }
    .pag-list .pag-item.active span { background: var(--blue); border-color: var(--blue); color: var(--white); }
    .pag-list .pag-item.disabled span { background: var(--slate-100); color: var(--slate-300); cursor: default; }
</style>
@endpush

@section('content')
<div class="face-wrap">

    {{-- HEADER --}}
    <div class="face-header">
        <div class="face-header-left">
            <div class="face-header-icon">
                <i class="mdi mdi-face-recognition"></i>
            </div>
            <div>
                <div class="face-header-title">Verifikasi Wajah Karyawan</div>
                <div class="face-header-sub">Manajemen pendaftaran data biometrik wajah</div>
            </div>
        </div>
        <div class="face-header-actions">
            <form action="{{ route('panel.face-verification.export') }}" method="GET" class="d-inline">
                @if(request('kode_cabang')) <input type="hidden" name="kode_cabang" value="{{ request('kode_cabang') }}"> @endif
                @if(request('kode_dept')) <input type="hidden" name="kode_dept" value="{{ request('kode_dept') }}"> @endif
                @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
                <button type="submit" class="btn-hdr btn-hdr-green">
                    <i class="mdi mdi-file-excel"></i> Export Excel
                </button>
            </form>
        </div>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="alert-c alert-success-c"><i class="mdi mdi-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-c alert-danger-c"><i class="mdi mdi-alert-circle"></i> {{ session('error') }}</div>
    @endif

    {{-- STATS GRID --}}
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-title">Total Karyawan</div>
                <div class="stat-icon ic-total"><i class="mdi mdi-account-group"></i></div>
            </div>
            <div class="stat-val-wrap">
                <div class="stat-val">{{ $stats['total_karyawan'] }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-title">Sudah Terdaftar</div>
                <div class="stat-icon ic-enrol"><i class="mdi mdi-face-man"></i></div>
            </div>
            <div class="stat-val-wrap">
                <div class="stat-val" style="color:var(--green);">{{ $stats['enrolled'] }}</div>
                <div class="stat-pct pct-green">{{ $stats['percentage'] }}%</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-title">Belum Terdaftar</div>
                <div class="stat-icon ic-notenrol"><i class="mdi mdi-account-question"></i></div>
            </div>
            <div class="stat-val-wrap">
                <div class="stat-val" style="color:#D97706;">{{ $stats['not_enrolled'] }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-title">Non-Aktif</div>
                <div class="stat-icon ic-inactive"><i class="mdi mdi-account-cancel"></i></div>
            </div>
            <div class="stat-val-wrap">
                <div class="stat-val" style="color:var(--red);">{{ $stats['inactive'] }}</div>
            </div>
        </div>
    </div>

    {{-- FILTER --}}
    <div class="filter-card">
        <div class="filter-head">
            <i class="mdi mdi-filter-outline"></i> Filter Data
        </div>
        <div class="filter-body">
            <form action="{{ route('panel.face-verification.index') }}" method="GET">
                <div class="filter-grid">
                    <div class="fg">
                        <label>Cabang</label>
                        <select name="kode_cabang">
                            <option value="">Semua Cabang</option>
                            @foreach($cabang as $c)
                                <option value="{{ $c->kode_cabang }}" {{ request('kode_cabang') == $c->kode_cabang ? 'selected' : '' }}>{{ $c->nama_cabang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="fg">
                        <label>Departemen</label>
                        <select name="kode_dept">
                            <option value="">Semua Departemen</option>
                            @foreach($departemen as $d)
                                <option value="{{ $d->kode_dept }}" {{ request('kode_dept') == $d->kode_dept ? 'selected' : '' }}>{{ $d->nama_dept }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="fg">
                        <label>Status Enrollment</label>
                        <select name="status">
                            <option value="">Semua Status</option>
                            <option value="enrolled" {{ request('status') == 'enrolled' ? 'selected' : '' }}>Sudah Terdaftar</option>
                            <option value="not_enrolled" {{ request('status') == 'not_enrolled' ? 'selected' : '' }}>Belum Terdaftar</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Non-aktif</option>
                        </select>
                    </div>
                    <div class="fg">
                        <label>Cari NIK / Nama</label>
                        <input type="text" name="search" placeholder="Ketik kata kunci..." value="{{ request('search') }}">
                    </div>
                    <div>
                        <button type="submit" class="btn-filter">
                            <i class="mdi mdi-magnify"></i> Cari
                        </button>
                    </div>
                    <div>
                        <a href="{{ route('panel.face-verification.index') }}" class="btn-reset">
                            <i class="mdi mdi-refresh"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="tbl-card">
        <div class="tbl-card-head">
            <div class="tbl-card-title"><i class="mdi mdi-format-list-bulleted"></i> Daftar Verifikasi Wajah</div>
            <span class="tbl-meta">{{ $karyawan->total() }} Karyawan</span>
        </div>
        <div class="tbl-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width:40px; text-align:center;">No</th>
                        <th>Karyawan</th>
                        <th>Info Penempatan</th>
                        <th>Status Enrollment</th>
                        <th>Terakhir Update</th>
                        <th style="text-align:center; width:160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($karyawan as $index => $k)
                    <tr>
                        <td class="no-cell">{{ $karyawan->firstItem() + $index }}</td>
                        <td>
                            <div class="user-cell">
                                @if($k->foto)
                                    <img src="{{ Storage::url('uploads/karyawan/'.$k->foto) }}" class="ava" alt="Foto">
                                @else
                                    <div class="ava-init">{{ strtoupper(substr($k->nama_lengkap, 0, 2)) }}</div>
                                @endif
                                <div>
                                    <div class="user-name">{{ $k->nama_lengkap }}</div>
                                    <div class="user-nik">NIK: {{ $k->nik }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="plc-cabang">{{ $k->cabang->nama_cabang ?? '-' }}</div>
                            <div class="plc-dept">{{ $k->departemen->nama_dept ?? '-' }}</div>
                            <div class="plc-jabatan">{{ $k->jabatan }}</div>
                        </td>
                        <td>
                            @if($k->faceData)
                                @if($k->faceData->status == 'active')
                                    <div class="enr-stat enr-acc"><i class="mdi mdi-check-circle"></i> Terdaftar</div>
                                    <span class="enr-count">{{ $k->faceData->enrollment_count }}x enrollment</span>
                                @else
                                    <div class="enr-stat enr-non"><i class="mdi mdi-close-circle"></i> Non-aktif</div>
                                    <span class="enr-count">{{ $k->faceData->enrollment_count }}x enrollment</span>
                                @endif
                            @else
                                <div class="enr-stat enr-wait"><i class="mdi mdi-alert-circle"></i> Belum Terdaftar</div>
                            @endif
                        </td>
                        <td>
                            @if($k->faceData && $k->faceData->last_updated)
                                <div class="time-date">{{ \Carbon\Carbon::parse($k->faceData->last_updated)->format('d M Y') }}</div>
                                <div class="time-hour">{{ \Carbon\Carbon::parse($k->faceData->last_updated)->format('H:i') }} WIB</div>
                            @else
                                <span style="color:var(--slate-300); font-weight:700;">—</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-cell">
                                <a href="{{ route('panel.face-verification.show', $k->nik) }}" class="btn-act btn-act-blue" title="Lihat Detail">
                                    <i class="mdi mdi-eye"></i>
                                </a>

                                @if($k->faceData)
                                    @if($k->faceData->status == 'active')
                                        <form action="{{ route('panel.face-verification.deactivate', $k->nik) }}" method="POST" style="display:inline;">
                                            @csrf @method('PUT')
                                            <button type="submit" class="btn-act btn-act-yellow" title="Nonaktifkan" onclick="return confirm('Yakin ingin menonaktifkan data wajah ini?')">
                                                <i class="mdi mdi-cancel"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('panel.face-verification.activate', $k->nik) }}" method="POST" style="display:inline;">
                                            @csrf @method('PUT')
                                            <button type="submit" class="btn-act btn-act-green" title="Aktifkan" onclick="return confirm('Yakin ingin mengaktifkan data wajah ini?')">
                                                <i class="mdi mdi-check-circle-outline"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('panel.face-verification.destroy', $k->nik) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-act btn-act-red" title="Hapus & Reset" onclick="return confirm('Yakin ingin menghapus data wajah? Karyawan harus mendaftar ulang.')">
                                            <i class="mdi mdi-trash-can"></i>
                                        </button>
                                    </form>
                                @else
                                    <button class="btn-act btn-act-dis" disabled><i class="mdi mdi-minus"></i></button>
                                    <button class="btn-act btn-act-dis" disabled><i class="mdi mdi-minus"></i></button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="tbl-empty">
                                <i class="mdi mdi-face-recognition"></i>
                                <p>Tidak ada data verifikasi wajah</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($karyawan->hasPages())
        <div class="pag-wrap">
            <div class="pag-info">
                Menampilkan <strong>{{ $karyawan->firstItem() ?? 0 }}</strong>–<strong>{{ $karyawan->lastItem() ?? 0 }}</strong> dari <strong>{{ $karyawan->total() }}</strong> data
            </div>
            <ul class="pag-list">
                @if($karyawan->onFirstPage())
                    <li class="pag-item disabled"><span><i class="mdi mdi-chevron-left"></i></span></li>
                @else
                    <li class="pag-item"><a href="{{ $karyawan->previousPageUrl() }}"><i class="mdi mdi-chevron-left"></i></a></li>
                @endif

                @foreach(range(1, $karyawan->lastPage()) as $i)
                    @if($i == $karyawan->currentPage())
                        <li class="pag-item active"><span>{{ $i }}</span></li>
                    @else
                        <li class="pag-item"><a href="{{ $karyawan->url($i) }}">{{ $i }}</a></li>
                    @endif
                @endforeach

                @if($karyawan->hasMorePages())
                    <li class="pag-item"><a href="{{ $karyawan->nextPageUrl() }}"><i class="mdi mdi-chevron-right"></i></a></li>
                @else
                    <li class="pag-item disabled"><span><i class="mdi mdi-chevron-right"></i></span></li>
                @endif
            </ul>
        </div>
        @endif
    </div>

</div>
@endsection