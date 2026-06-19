@extends('admin.layouts.admin')

@section('title', 'Data Karyawan')
@section('page-title', 'Data Karyawan')

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

    .kar-wrap { display: flex; flex-direction: column; gap: 20px; }

    /* ── PAGE HEADER ── */
    .kar-header {
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

    .kar-header-left { display: flex; align-items: center; gap: 14px; }

    .kar-header-icon {
        width: 46px; height: 46px;
        border-radius: 12px;
        background: var(--blue-soft);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .kar-header-icon i { font-size: 22px; color: var(--blue); }

    .kar-header-title { font-size: 17px; font-weight: 800; color: var(--slate-900); letter-spacing: -0.2px; }
    .kar-header-sub   { font-size: 12px; color: var(--slate-400); margin-top: 2px; }

    .kar-header-actions { display: flex; align-items: center; gap: 8px; }

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
    .btn-hdr-primary { background: var(--blue); color: var(--white); box-shadow: 0 2px 8px rgba(37,99,235,0.2); }
    .btn-hdr-primary:hover { background: var(--blue-dark); color: var(--white); }
    .btn-hdr-green { background: var(--green-soft); color: var(--green); border: 1.5px solid #A7F3D0; }
    .btn-hdr-green:hover { background: #D1FAE5; color: var(--green); }

    /* ── ALERTS ── */
    .alert-c {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        border-radius: var(--radius-sm);
        font-size: 13px;
        font-weight: 600;
    }
    .alert-c i { font-size: 18px; flex-shrink: 0; }
    .alert-success-c { background: var(--green-soft); color: #065F46; border: 1px solid #A7F3D0; }
    .alert-danger-c  { background: var(--red-soft);   color: #991B1B; border: 1px solid #FECACA; }
    .alert-warning-c { background: var(--amber-soft); color: #92400E; border: 1px solid #FDE68A; }

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
        padding: 13px 20px;
        border-bottom: 1px solid var(--slate-100);
        font-size: 11px;
        font-weight: 700;
        color: var(--slate-600);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .filter-head i { font-size: 15px; color: var(--blue); }

    .filter-body { padding: 16px 20px; }

    .filter-grid {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr auto;
        gap: 12px;
        align-items: end;
    }

    @media (max-width: 900px)  { .filter-grid { grid-template-columns: 1fr 1fr; } }
    @media (max-width: 480px)  { .filter-grid { grid-template-columns: 1fr; } }

    .fg { display: flex; flex-direction: column; gap: 5px; }

    .fg label {
        font-size: 10.5px;
        font-weight: 700;
        color: var(--slate-600);
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .fg input, .fg select {
        height: 38px;
        border: 1.5px solid var(--slate-200);
        border-radius: 9px;
        padding: 0 12px;
        font-family: 'Inter', sans-serif;
        font-size: 13px;
        color: var(--slate-900);
        background: var(--white);
        outline: none;
        transition: border-color 0.15s, box-shadow 0.15s;
        width: 100%;
        appearance: auto;
    }
    .fg input:focus, .fg select:focus {
        border-color: var(--blue);
        box-shadow: 0 0 0 3px rgba(37,99,235,0.10);
    }

    .fg input::placeholder { color: var(--slate-400); }

    .btn-filter {
        height: 38px;
        padding: 0 20px;
        background: var(--blue);
        color: var(--white);
        border: none;
        border-radius: 9px;
        font-family: 'Inter', sans-serif;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: background 0.15s;
        white-space: nowrap;
    }
    .btn-filter:hover { background: var(--blue-dark); }
    .btn-filter i { font-size: 16px; }

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
        min-width: 700px;
    }

    .tbl-wrap thead th {
        padding: 10px 16px;
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
        padding: 13px 16px;
        font-size: 13px;
        color: var(--slate-700);
        border-bottom: 1px solid var(--slate-100);
        vertical-align: middle;
    }

    .tbl-wrap tbody tr:last-child td { border-bottom: none; }
    .tbl-wrap tbody tr:hover td { background: var(--slate-50); transition: background 0.12s; }

    /* No cell */
    .no-cell {
        font-size: 12px;
        font-weight: 700;
        color: var(--slate-400);
        text-align: center;
    }

    /* Avatar + user info */
    .user-cell { display: flex; align-items: center; gap: 11px; }

    .ava {
        width: 38px; height: 38px;
        border-radius: 10px;
        object-fit: cover;
        flex-shrink: 0;
        border: 2px solid var(--slate-200);
    }

    .ava-init {
        width: 38px; height: 38px;
        border-radius: 10px;
        background: var(--blue-soft);
        color: var(--blue);
        display: flex; align-items: center; justify-content: center;
        font-size: 13px;
        font-weight: 800;
        flex-shrink: 0;
    }

    .user-name    { font-size: 13px; font-weight: 700; color: var(--slate-900); }
    .user-nik     { font-size: 11px; color: var(--slate-400); }
    .user-jabatan { font-size: 11px; color: var(--slate-400); }
    .user-email   { font-size: 11px; color: var(--slate-500); margin-top: 2px; }

    /* Placement cell */
    .place-cabang { font-size: 13px; font-weight: 700; color: var(--slate-900); }
    .place-dept   { font-size: 11px; color: var(--slate-400); margin-top: 2px; }

    /* HP cell */
    .hp-cell { display: flex; align-items: center; gap: 5px; font-size: 12.5px; color: var(--slate-700); }
    .hp-cell i { font-size: 14px; color: var(--slate-400); }

    /* Action buttons */
    .action-cell { display: flex; align-items: center; gap: 6px; }

    .btn-act {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 5px 10px;
        border-radius: 7px;
        font-size: 11.5px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.15s;
        border: 1.5px solid transparent;
        text-decoration: none;
        font-family: 'Inter', sans-serif;
    }
    .btn-act i { font-size: 14px; }
    .btn-act-edit { background: var(--amber-soft); color: #D97706; border-color: #FDE68A; }
    .btn-act-edit:hover { background: #FEF3C7; color: #D97706; }
    .btn-act-del  { background: var(--red-soft); color: var(--red); border-color: #FECACA; }
    .btn-act-del:hover { background: #FEE2E2; color: var(--red); }

    /* Empty state */
    .tbl-empty {
        padding: 56px 16px;
        text-align: center;
        color: var(--slate-400);
    }
    .tbl-empty i { font-size: 44px; display: block; margin-bottom: 10px; color: var(--slate-200); }
    .tbl-empty p { font-size: 13px; font-weight: 600; margin: 0; color: var(--slate-600); }
    .tbl-empty span { font-size: 12px; color: var(--slate-400); }

    /* ── PAGINATION ── */
    .pag-wrap {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 20px;
        border-top: 1px solid var(--slate-100);
        background: var(--slate-50);
        flex-wrap: wrap;
        gap: 10px;
    }

    .pag-info { font-size: 12px; color: var(--slate-400); font-weight: 600; }
    .pag-info strong { color: var(--slate-900); }

    .pag-list { display: flex; align-items: center; gap: 4px; list-style: none; margin: 0; padding: 0; }

    .pag-list .pag-item a,
    .pag-list .pag-item span {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 32px;
        padding: 0 8px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 700;
        border: 1.5px solid var(--slate-200);
        background: var(--white);
        color: var(--slate-600);
        text-decoration: none;
        transition: background 0.12s, color 0.12s, border-color 0.12s;
        cursor: pointer;
    }

    .pag-list .pag-item a:hover { background: var(--blue-soft); border-color: var(--blue-mid); color: var(--blue); }
    .pag-list .pag-item.active span { background: var(--blue); border-color: var(--blue); color: var(--white); }
    .pag-list .pag-item.disabled span { background: var(--slate-100); color: var(--slate-300); cursor: default; }

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
    }
    .btn-modal-x:hover { background: var(--slate-200); }
    .btn-modal-x i { font-size: 18px; }

    .modal-bd { padding: 20px; }

    .fgroup { display: flex; flex-direction: column; gap: 5px; margin-bottom: 14px; }
    .fgroup:last-child { margin-bottom: 0; }
    .fgroup label { font-size: 11px; font-weight: 700; color: var(--slate-600); text-transform: uppercase; letter-spacing: 0.4px; }
    .fgroup input, .fgroup select {
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
    .fgroup input:focus, .fgroup select:focus {
        border-color: var(--blue);
        box-shadow: 0 0 0 3px rgba(37,99,235,0.10);
    }

    .file-hint {
        font-size: 11.5px;
        color: var(--slate-400);
        margin-top: 6px;
        line-height: 1.5;
    }
    .file-hint a { color: var(--blue); font-weight: 700; text-decoration: none; }
    .file-hint a:hover { text-decoration: underline; }

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
        display: flex;
        align-items: center;
        gap: 5px;
        transition: background 0.15s;
    }
    .btn-save:hover { background: var(--blue-dark); }
    .btn-save i { font-size: 15px; }

    /* Responsive */
    @media (max-width: 640px) {
        .kar-header { padding: 16px; }
        .kar-header-actions { width: 100%; }
        .btn-hdr { flex: 1; justify-content: center; }
        .pag-wrap { flex-direction: column; align-items: flex-start; }
    }
</style>
@endpush

@section('content')
<div class="kar-wrap">

    {{-- ── PAGE HEADER ── --}}
    <div class="kar-header">
        <div class="kar-header-left">
            <div class="kar-header-icon">
                <i class="mdi mdi-account-group"></i>
            </div>
            <div>
                <div class="kar-header-title">Data Karyawan</div>
                <div class="kar-header-sub">Kelola seluruh data karyawan perusahaan</div>
            </div>
        </div>
        <div class="kar-header-actions">
            <a href="#" class="btn-hdr btn-hdr-green" data-bs-toggle="modal" data-bs-target="#modal-import">
                <i class="mdi mdi-file-import"></i> Import
            </a>
            <a href="{{ route('panel.karyawan.create') }}" class="btn-hdr btn-hdr-primary">
                <i class="mdi mdi-account-plus"></i> Tambah Karyawan
            </a>
        </div>
    </div>

    {{-- ── ALERTS ── --}}
    @if(session('success'))
    <div class="alert-c alert-success-c"><i class="mdi mdi-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert-c alert-danger-c"><i class="mdi mdi-alert-circle"></i> {{ session('error') }}</div>
    @endif
    @if(session('warning'))
    <div class="alert-c alert-warning-c"><i class="mdi mdi-alert"></i> {{ session('warning') }}</div>
    @endif

    {{-- ── FILTER ── --}}
    <div class="filter-card">
        <div class="filter-head">
            <i class="mdi mdi-filter-outline"></i> Filter &amp; Pencarian
        </div>
        <div class="filter-body">
            <form action="{{ route('panel.karyawan.index') }}" method="GET">
                <div class="filter-grid">
                    <div class="fg">
                        <label>Cari Karyawan</label>
                        <input type="text" name="search" placeholder="NIK, Nama, atau Jabatan..." value="{{ request('search') }}">
                    </div>
                    <div class="fg">
                        <label>Departemen</label>
                        <select name="kode_dept">
                            <option value="">Semua Departemen</option>
                            @foreach($departemen as $dept)
                                <option value="{{ $dept->kode_dept }}" {{ request('kode_dept') == $dept->kode_dept ? 'selected' : '' }}>{{ $dept->nama_dept }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="fg">
                        <label>Cabang</label>
                        <select name="kode_cabang">
                            <option value="">Semua Cabang</option>
                            @foreach($cabang as $cbg)
                                <option value="{{ $cbg->kode_cabang }}" {{ request('kode_cabang') == $cbg->kode_cabang ? 'selected' : '' }}>{{ $cbg->nama_cabang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="btn-filter">
                            <i class="mdi mdi-magnify"></i> Cari
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ── TABLE ── --}}
    <div class="tbl-card">
        <div class="tbl-card-head">
            <div class="tbl-card-title">
                <i class="mdi mdi-account-multiple"></i>
                Daftar Karyawan
            </div>
            <span class="tbl-meta">{{ $karyawan->total() }} karyawan</span>
        </div>
        <div class="tbl-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width:42px;">No</th>
                        <th>Karyawan</th>
                        <th>Penempatan</th>
                        <th>No. HP</th>
                        <th style="width:120px; text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($karyawan as $index => $item)
                    <tr>
                        <td class="no-cell">{{ $karyawan->firstItem() + $index }}</td>
                        <td>
                            <div class="user-cell">
                                @if($item->foto)
                                    <img src="{{ asset('storage/uploads/karyawan/' . $item->foto) }}"
                                         alt="{{ $item->nama_lengkap }}" class="ava">
                                @else
                                    <div class="ava-init">{{ strtoupper(substr($item->nama_lengkap, 0, 2)) }}</div>
                                @endif
                                <div>
                                    <div class="user-name">{{ $item->nama_lengkap }}</div>
                                    <div class="user-nik">NIK: {{ $item->nik }}</div>
                                    @if($item->jabatan)
                                        <div class="user-jabatan">{{ $item->jabatan }}</div>
                                    @endif
                                    @if($item->email)
                                        <div class="user-email"><i class="mdi mdi-email-outline"></i> {{ $item->email }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($item->cabang)
                                <div class="place-cabang">{{ $item->cabang->nama_cabang }}</div>
                            @endif
                            @if($item->departemen)
                                <div class="place-dept">{{ $item->departemen->nama_dept }}</div>
                            @else
                                <div class="place-dept">—</div>
                            @endif
                            
                            @php
                                $karyawanUser = null;
                                if ($item->nik) {
                                    $karyawanUser = \App\Models\User::where('nik_karyawan', $item->nik)->first();
                                }
                                if (!$karyawanUser && $item->email) {
                                    $karyawanUser = \App\Models\User::where('email', $item->email)->first();
                                }
                            @endphp
                            @if($karyawanUser)
                                <div class="place-role" style="margin-top: 6px;">
                                    <span style="background:var(--blue-soft); color:var(--blue); padding:3px 8px; border-radius:6px; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing: 0.5px; display:inline-block; border: 1px solid var(--blue-mid);">
                                        <i class="mdi mdi-shield-account"></i> {{ $karyawanUser->role }}
                                    </span>
                                </div>
                            @endif
                        </td>
                        <td>
                            @if($item->no_hp)
                                <div class="hp-cell">
                                    <i class="mdi mdi-phone"></i>
                                    {{ $item->no_hp }}
                                </div>
                            @else
                                <span style="color:var(--slate-300); font-weight:700;">—</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-cell">
                                <a href="{{ route('panel.karyawan.edit', $item->nik) }}" class="btn-act btn-act-edit">
                                    <i class="mdi mdi-pencil"></i> Edit
                                </a>
                                <form id="delete-form-{{ $item->nik }}"
                                      action="{{ route('panel.karyawan.destroy', $item->nik) }}"
                                      method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            onclick="confirmDelete('{{ $item->nik }}')"
                                            class="btn-act btn-act-del">
                                        <i class="mdi mdi-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="tbl-empty">
                                <i class="mdi mdi-account-off-outline"></i>
                                <p>Tidak ada data karyawan</p>
                                <span>Tambah karyawan baru atau ubah filter pencarian</span>
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
                Menampilkan <strong>{{ $karyawan->firstItem() ?? 0 }}</strong>–<strong>{{ $karyawan->lastItem() ?? 0 }}</strong>
                dari <strong>{{ $karyawan->total() }}</strong> karyawan
            </div>
            <ul class="pag-list">
                {{-- Prev --}}
                @if($karyawan->onFirstPage())
                    <li class="pag-item disabled"><span><i class="mdi mdi-chevron-left"></i></span></li>
                @else
                    <li class="pag-item"><a href="{{ $karyawan->previousPageUrl() }}"><i class="mdi mdi-chevron-left"></i></a></li>
                @endif

                {{-- Pages --}}
                @foreach(range(1, $karyawan->lastPage()) as $i)
                    @if($i == $karyawan->currentPage())
                        <li class="pag-item active"><span>{{ $i }}</span></li>
                    @else
                        <li class="pag-item"><a href="{{ $karyawan->url($i) }}">{{ $i }}</a></li>
                    @endif
                @endforeach

                {{-- Next --}}
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

{{-- ── MODAL IMPORT ── --}}
<div class="modal fade" id="modal-import" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-hd">
                <div class="modal-hd-title">
                    <i class="mdi mdi-file-import"></i> Import Data Karyawan
                </div>
                <button type="button" class="btn-modal-x" data-bs-dismiss="modal">
                    <i class="mdi mdi-close"></i>
                </button>
            </div>
            <form action="{{ route('panel.karyawan.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-bd">
                    <div class="fgroup">
                        <label>File Excel / CSV <span style="color:var(--red);">*</span></label>
                        <input type="file" name="file" accept=".xlsx,.xls,.csv" required>
                        <div class="file-hint">
                            Pastikan format file sesuai dengan template.<br>
                            <a href="{{ route('panel.karyawan.downloadTemplate') }}">
                                <i class="mdi mdi-download" style="font-size:13px;"></i> Download Template Excel
                            </a>
                        </div>
                    </div>
                </div>
                <div class="modal-foot">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-save">
                        <i class="mdi mdi-upload"></i> Mulai Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection