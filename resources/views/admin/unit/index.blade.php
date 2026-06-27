@extends('admin.layouts.admin')

@section('title', 'Data Unit')
@section('page-title', 'Data Unit')

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

    .unit-wrap { display: flex; flex-direction: column; gap: 20px; }

    /* ── PAGE HEADER ── */
    .unit-header {
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

    .unit-header-left { display: flex; align-items: center; gap: 14px; }

    .unit-header-icon {
        width: 46px; height: 46px;
        border-radius: 12px;
        background: var(--blue-soft);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .unit-header-icon i { font-size: 22px; color: var(--blue); }

    .unit-header-title { font-size: 17px; font-weight: 800; color: var(--slate-900); letter-spacing: -0.2px; }
    .unit-header-sub   { font-size: 12px; color: var(--slate-400); margin-top: 2px; }

    .btn-hdr {
        display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px;
        border: none; border-radius: 9px; font-family: 'Inter', sans-serif;
        font-size: 13px; font-weight: 700; cursor: pointer; transition: background 0.15s;
        text-decoration: none; white-space: nowrap;
    }
    .btn-hdr i { font-size: 17px; }
    .btn-hdr-primary { background: var(--blue); color: var(--white); box-shadow: 0 2px 8px rgba(37,99,235,0.2); }
    .btn-hdr-primary:hover { background: var(--blue-dark); color: var(--white); }

    /* ── ALERTS ── */
    .alert-c { display: flex; align-items: center; gap: 10px; padding: 12px 16px; border-radius: var(--radius-sm); font-size: 13px; font-weight: 600; margin-bottom: 5px; }
    .alert-c i { font-size: 18px; flex-shrink: 0; }
    .alert-success-c { background: var(--green-soft); color: #065F46; border: 1px solid #A7F3D0; }
    .alert-danger-c  { background: var(--red-soft);   color: #991B1B; border: 1px solid #FECACA; }
    .alert-warning-c { background: var(--amber-soft); color: #B45309; border: 1px solid #FDE68A; }

    /* ── FILTER CARD ── */
    .filter-card { background: var(--white); border: 1px solid var(--slate-200); border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; }
    .filter-head { display: flex; align-items: center; gap: 8px; padding: 13px 20px; border-bottom: 1px solid var(--slate-100); font-size: 11px; font-weight: 700; color: var(--slate-600); text-transform: uppercase; letter-spacing: 0.5px; }
    .filter-head i { font-size: 15px; color: var(--blue); }
    .filter-body { padding: 16px 20px; }

    .filter-grid { display: grid; grid-template-columns: 1fr auto; gap: 12px; align-items: end; }
    @media (max-width: 640px) { .filter-grid { grid-template-columns: 1fr; } }

    .fg { display: flex; flex-direction: column; gap: 5px; }
    .fg label { font-size: 10.5px; font-weight: 700; color: var(--slate-600); text-transform: uppercase; letter-spacing: 0.4px; }
    .fg input { height: 38px; border: 1.5px solid var(--slate-200); border-radius: 9px; padding: 0 12px; font-family: 'Inter', sans-serif; font-size: 13px; color: var(--slate-900); background: var(--white); outline: none; transition: border-color 0.15s, box-shadow 0.15s; width: 100%; }
    .fg input:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(37,99,235,0.10); }
    .fg input::placeholder { color: var(--slate-400); }

    .btn-filter { height: 38px; padding: 0 20px; background: var(--blue); color: var(--white); border: none; border-radius: 9px; font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px; transition: background 0.15s; white-space: nowrap; }
    .btn-filter:hover { background: var(--blue-dark); }

    /* ── TABLE CARD ── */
    .tbl-card { background: var(--white); border: 1px solid var(--slate-200); border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; }
    .tbl-card-head { display: flex; align-items: center; justify-content: space-between; padding: 14px 20px; border-bottom: 1px solid var(--slate-100); }
    .tbl-card-title { font-size: 13px; font-weight: 700; color: var(--slate-900); display: flex; align-items: center; gap: 7px; }
    .tbl-card-title i { font-size: 17px; color: var(--blue); }
    .tbl-meta { font-size: 11px; color: var(--slate-400); background: var(--slate-100); padding: 3px 10px; border-radius: 50px; font-weight: 600; }

    .tbl-wrap { overflow-x: auto; }
    .tbl-wrap table { width: 100%; border-collapse: collapse; min-width: 800px; }
    .tbl-wrap thead th { padding: 12px 16px; background: var(--slate-50); font-size: 10.5px; font-weight: 700; color: var(--slate-400); text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid var(--slate-200); white-space: nowrap; text-align: left; }
    .tbl-wrap tbody td { padding: 14px 16px; font-size: 13px; color: var(--slate-700); border-bottom: 1px solid var(--slate-100); vertical-align: middle; }
    .tbl-wrap tbody tr:last-child td { border-bottom: none; }
    .tbl-wrap tbody tr:hover td { background: var(--slate-50); transition: background 0.12s; }

    .no-cell { font-size: 12px; font-weight: 700; color: var(--slate-400); text-align: center; }

    .unit-name { font-size: 13.5px; font-weight: 800; color: var(--slate-900); display: flex; align-items: center; gap: 8px; }
    .unit-name i { color: var(--blue); font-size: 16px; }
    
    .cabang-text { font-size: 12.5px; color: var(--slate-600); display: flex; align-items: center; gap: 6px; }
    .cabang-text i { color: var(--slate-400); font-size: 15px; }

    /* Actions */
    .action-cell { display: flex; align-items: center; justify-content: center; gap: 6px; }
    .btn-act { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; border: 1px solid transparent; cursor: pointer; transition: background 0.15s; padding: 0; text-decoration: none; }
    .btn-act i { font-size: 16px; }
    .btn-act-blue { background: var(--blue-soft); color: var(--blue); border-color: var(--blue-mid); }
    .btn-act-blue:hover { background: #DBEAFE; }
    .btn-act-yellow { background: var(--amber-soft); color: #D97706; border-color: #FDE68A; }
    .btn-act-yellow:hover { background: #FEF3C7; }
    .btn-act-red { background: var(--red-soft); color: var(--red); border-color: #FECACA; }
    .btn-act-red:hover { background: #FEE2E2; }

    /* Empty state */
    .tbl-empty { padding: 56px 16px; text-align: center; color: var(--slate-400); }
    .tbl-empty i { font-size: 44px; display: block; margin-bottom: 10px; color: var(--slate-200); }
    .tbl-empty p { font-size: 13px; font-weight: 600; margin: 0; color: var(--slate-600); }

    /* ── PAGINATION ── */
    .pag-wrap { display: flex; align-items: center; justify-content: space-between; padding: 14px 20px; border-top: 1px solid var(--slate-100); background: var(--slate-50); flex-wrap: wrap; gap: 10px; }
    .pag-info { font-size: 12px; color: var(--slate-400); font-weight: 600; }
    .pag-info strong { color: var(--slate-900); }
    .pag-list { display: flex; align-items: center; gap: 4px; list-style: none; margin: 0; padding: 0; }
    .pag-list .pag-item a, .pag-list .pag-item span { display: flex; align-items: center; justify-content: center; min-width: 32px; height: 32px; padding: 0 8px; border-radius: 7px; font-size: 12px; font-weight: 700; border: 1.5px solid var(--slate-200); background: var(--white); color: var(--slate-600); text-decoration: none; transition: all 0.12s; cursor: pointer; }
    .pag-list .pag-item a:hover { background: var(--blue-soft); border-color: var(--blue-mid); color: var(--blue); }
    .pag-list .pag-item.active span { background: var(--blue); border-color: var(--blue); color: var(--white); }
    .pag-list .pag-item.disabled span { background: var(--slate-100); color: var(--slate-300); cursor: default; }
</style>
@endpush

@section('content')
<div class="unit-wrap">

    {{-- HEADER --}}
    <div class="unit-header">
        <div class="unit-header-left">
            <div class="unit-header-icon">
                <i class="mdi mdi-office-building"></i>
            </div>
            <div>
                <div class="unit-header-title">Master Data Unit</div>
                <div class="unit-header-sub">Kelola data unit kerja di bawah setiap cabang</div>
            </div>
        </div>
        <div>
            <a href="{{ route('panel.unit.create') }}" class="btn-hdr btn-hdr-primary">
                <i class="mdi mdi-plus"></i> Tambah Unit
            </a>
        </div>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="alert-c alert-success-c"><i class="mdi mdi-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-c alert-danger-c"><i class="mdi mdi-alert-circle"></i> {{ session('error') }}</div>
    @endif
    @if(session('warning'))
        <div class="alert-c alert-warning-c"><i class="mdi mdi-alert"></i> {{ session('warning') }}</div>
    @endif

    {{-- SEARCH --}}
    <div class="filter-card">
        <div class="filter-head">
            <i class="mdi mdi-magnify"></i> Pencarian
        </div>
        <div class="filter-body">
            <form action="{{ route('panel.unit.index') }}" method="GET">
                <div class="filter-grid">
                    <div class="fg">
                        <label>Cari Unit</label>
                        <input type="text" name="search" placeholder="Cari berdasarkan nama atau kode unit..." value="{{ request('search') }}">
                    </div>
                    <div>
                        <button type="submit" class="btn-filter">
                            <i class="mdi mdi-magnify"></i> Cari Data
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="tbl-card">
        <div class="tbl-card-head">
            <div class="tbl-card-title"><i class="mdi mdi-format-list-bulleted"></i> Daftar Unit</div>
            <span class="tbl-meta">{{ $units->total() }} Unit</span>
        </div>
        <div class="tbl-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width:40px; text-align:center;">No</th>
                        <th>Kode</th>
                        <th>Nama Unit</th>
                        <th>Induk Cabang</th>
                        <th>Status</th>
                        <th style="text-align:center; width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($units as $index => $item)
                    <tr>
                        <td class="no-cell">{{ $units->firstItem() + $index }}</td>
                        <td>{{ $item->code ?? '-' }}</td>
                        <td>
                            <div class="unit-name">
                                <i class="mdi mdi-domain"></i>
                                {{ $item->name }}
                            </div>
                        </td>
                        <td>
                            <div class="cabang-text">
                                <i class="mdi mdi-office-building-marker"></i>
                                <span>{{ $item->branch->name ?? '-' }}</span>
                            </div>
                        </td>
                        <td>
                            @if($item->is_sekretariat)
                                <span class="badge bg-primary">Sekretariat</span>
                            @else
                                <span class="badge bg-secondary" style="background:var(--slate-400)">Umum</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-cell">
                                <a href="{{ route('panel.unit.edit', $item->id) }}" class="btn-act btn-act-yellow" title="Edit">
                                    <i class="mdi mdi-pencil"></i>
                                </a>
                                <form action="{{ route('panel.unit.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus unit ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-act btn-act-red" title="Hapus">
                                        <i class="mdi mdi-delete"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="tbl-empty">
                                <i class="mdi mdi-domain-off"></i>
                                <p>Tidak ada data unit</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($units->hasPages())
        <div class="pag-wrap">
            <div class="pag-info">
                Menampilkan <strong>{{ $units->firstItem() ?? 0 }}</strong>–<strong>{{ $units->lastItem() ?? 0 }}</strong> dari <strong>{{ $units->total() }}</strong> unit
            </div>
            <ul class="pag-list">
                @if($units->onFirstPage())
                    <li class="pag-item disabled"><span><i class="mdi mdi-chevron-left"></i></span></li>
                @else
                    <li class="pag-item"><a href="{{ $units->previousPageUrl() }}"><i class="mdi mdi-chevron-left"></i></a></li>
                @endif

                @foreach(range(1, $units->lastPage()) as $i)
                    @if($i == $units->currentPage())
                        <li class="pag-item active"><span>{{ $i }}</span></li>
                    @else
                        <li class="pag-item"><a href="{{ $units->url($i) }}">{{ $i }}</a></li>
                    @endif
                @endforeach

                @if($units->hasMorePages())
                    <li class="pag-item"><a href="{{ $units->nextPageUrl() }}"><i class="mdi mdi-chevron-right"></i></a></li>
                @else
                    <li class="pag-item disabled"><span><i class="mdi mdi-chevron-right"></i></span></li>
                @endif
            </ul>
        </div>
        @endif
    </div>

</div>
@endsection
