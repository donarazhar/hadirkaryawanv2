@extends('admin.layouts.admin')

@section('title', 'Data User (Pengguna)')
@section('page-title', 'Data User (Pengguna)')

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
        --cyan:       #06B6D4;
        --cyan-soft:  #ECFEFF;
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

    .user-wrap { display: flex; flex-direction: column; gap: 20px; }

    /* ── PAGE HEADER ── */
    .user-header {
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

    .user-header-left { display: flex; align-items: center; gap: 14px; }

    .user-header-icon {
        width: 46px; height: 46px;
        border-radius: 12px;
        background: var(--blue-soft);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .user-header-icon i { font-size: 24px; color: var(--blue); }

    .user-header-title { font-size: 17px; font-weight: 800; color: var(--slate-900); letter-spacing: -0.2px; }
    .user-header-sub   { font-size: 12px; color: var(--slate-400); margin-top: 2px; }

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
    @media (max-width: 768px) { .filter-grid { grid-template-columns: 1fr; } }

    .fg { display: flex; flex-direction: column; gap: 5px; }
    .fg label { font-size: 10.5px; font-weight: 700; color: var(--slate-600); text-transform: uppercase; letter-spacing: 0.4px; }
    .fg input { height: 38px; border: 1.5px solid var(--slate-200); border-radius: 9px; padding: 0 12px; font-family: 'Inter', sans-serif; font-size: 13px; color: var(--slate-900); background: var(--white); outline: none; transition: border-color 0.15s, box-shadow 0.15s; width: 100%; }
    .fg input:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(37,99,235,0.10); }

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

    /* Profile Display */
    .profile-item { display: flex; align-items: center; gap: 12px; }
    .avatar-icon { width: 36px; height: 36px; border-radius: 50%; background: var(--blue-soft); color: var(--blue-dark); display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 800; border: 1px solid var(--blue-mid); }
    .p-name { font-size: 13.5px; font-weight: 800; color: var(--slate-900); }
    .p-email { font-size: 11.5px; color: var(--slate-500); }

    /* Role Badges */
    .role-badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 50px; font-size: 11.5px; font-weight: 700; text-transform: capitalize; border: 1px solid transparent; }
    .rb-superadmin { background: var(--red-soft); color: var(--red); border-color: #FECACA; }
    .rb-admin { background: var(--blue-soft); color: var(--blue-dark); border-color: var(--blue-mid); }
    .rb-pimpinan { background: var(--cyan-soft); color: #0891B2; border-color: #CFFAFE; }

    /* Context Badges */
    .ctx-item { display: flex; align-items: center; gap: 6px; font-size: 12px; color: var(--slate-600); font-weight: 600; margin-bottom: 4px; }
    .ctx-item:last-child { margin-bottom: 0; }
    .ctx-item i { font-size: 14px; }
    .ctx-global { font-style: italic; color: var(--slate-400); }

    /* Actions */
    .action-cell { display: flex; align-items: center; justify-content: center; gap: 6px; }
    .btn-act { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; border: 1px solid transparent; cursor: pointer; transition: background 0.15s; padding: 0; text-decoration: none; }
    .btn-act i { font-size: 16px; }
    .btn-act-yellow { background: var(--amber-soft); color: #D97706; border-color: #FDE68A; }
    .btn-act-yellow:hover { background: #FEF3C7; }
    .btn-act-red { background: var(--red-soft); color: var(--red); border-color: #FECACA; }
    .btn-act-red:hover { background: #FEE2E2; }
    .btn-act-red:disabled { background: var(--slate-100); border-color: var(--slate-200); color: var(--slate-400); cursor: not-allowed; }

    /* Empty state */
    .tbl-empty { padding: 56px 16px; text-align: center; color: var(--slate-400); }
    .tbl-empty i { font-size: 44px; display: block; margin-bottom: 10px; color: var(--slate-200); }
    .tbl-empty p { font-size: 13px; font-weight: 600; margin: 0; color: var(--slate-600); }
    .tbl-empty small { font-size: 12px; color: var(--slate-400); }

    /* ── PAGINATION ── */
    .pag-wrap { display: flex; align-items: center; justify-content: space-between; padding: 14px 20px; border-top: 1px solid var(--slate-100); background: var(--slate-50); flex-wrap: wrap; gap: 10px; }
    .pag-info { font-size: 12px; color: var(--slate-400); font-weight: 600; }
    .pag-info strong { color: var(--slate-900); }
    
    .pagination { margin: 0; display: flex; gap: 4px; list-style: none; padding: 0; }
    .pagination .page-item .page-link { display: flex; align-items: center; justify-content: center; min-width: 32px; height: 32px; padding: 0 8px; border-radius: 7px; font-size: 12px; font-weight: 700; border: 1.5px solid var(--slate-200); background: var(--white); color: var(--slate-600); text-decoration: none; transition: all 0.12s; box-shadow: none; }
    .pagination .page-item .page-link:hover { background: var(--blue-soft); border-color: var(--blue-mid); color: var(--blue); }
    .pagination .page-item.active .page-link { background: var(--blue); border-color: var(--blue); color: var(--white); z-index: 1; }
    .pagination .page-item.disabled .page-link { background: var(--slate-100); color: var(--slate-300); cursor: default; border-color: var(--slate-200); }
</style>
@endpush

@section('content')
<div class="user-wrap">

    {{-- HEADER --}}
    <div class="user-header">
        <div class="user-header-left">
            <div class="user-header-icon">
                <i class="mdi mdi-account-group"></i>
            </div>
            <div>
                <div class="user-header-title">Data Pengguna (User)</div>
                <div class="user-header-sub">Kelola akses admin, pimpinan, dan superadmin</div>
            </div>
        </div>
        <div>
            <a href="{{ route('panel.user.create') }}" class="btn-hdr btn-hdr-primary">
                <i class="mdi mdi-account-plus"></i> Tambah User Baru
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
            <form action="{{ route('panel.user.index') }}" method="GET">
                <div class="filter-grid">
                    <div class="fg">
                        <label>Cari Nama / Email / Role</label>
                        <input type="text" name="search" placeholder="Ketik kata kunci pencarian..." value="{{ request('search') }}">
                    </div>
                    <div>
                        <button type="submit" class="btn-filter">
                            <i class="mdi mdi-magnify"></i> Temukan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="tbl-card">
        <div class="tbl-card-head">
            <div class="tbl-card-title"><i class="mdi mdi-table"></i> Daftar Pengguna</div>
            <span class="tbl-meta">{{ $users->total() }} User</span>
        </div>
        <div class="tbl-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width:50px; text-align:center;">No</th>
                        <th>Profil Pengguna</th>
                        <th>Akses Role</th>
                        <th>Konteks Area</th>
                        <th style="width:120px; text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $item)
                    <tr>
                        <td class="no-cell">{{ $users->firstItem() + $index }}</td>
                        <td>
                            <div class="profile-item">
                                <div class="avatar-icon">
                                    {{ strtoupper(substr($item->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="p-name">{{ $item->name }}</div>
                                    <div class="p-email">{{ $item->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($item->role == 'superadmin')
                                <span class="role-badge rb-superadmin"><i class="mdi mdi-shield-crown"></i> Superadmin</span>
                            @elseif($item->role == 'admin')
                                <span class="role-badge rb-admin"><i class="mdi mdi-shield-account"></i> Admin</span>
                            @else
                                <span class="role-badge rb-pimpinan"><i class="mdi mdi-account-tie"></i> Pimpinan</span>
                            @endif
                        </td>
                        <td>
                            @if($item->role === 'superadmin' || !$item->branch)
                                <div class="ctx-item ctx-global"><i class="mdi mdi-earth"></i> Semua Cabang (Global)</div>
                            @else
                                <div class="ctx-item">
                                    <i class="mdi mdi-office-building text-blue"></i>
                                    <span>{{ $item->branch->name }}</span>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="action-cell">
                                <a href="{{ route('panel.user.edit', $item->id) }}" class="btn-act btn-act-yellow" title="Edit">
                                    <i class="mdi mdi-pencil"></i>
                                </a>
                                <form action="{{ route('panel.user.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-act btn-act-red" title="Hapus" {{ auth('user')->id() === $item->id ? 'disabled' : '' }}>
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
                                <i class="mdi mdi-account-search-outline"></i>
                                <p>Tidak ada data pengguna ditemukan.</p>
                                <small>Silakan buat user baru atau ubah kata kunci pencarian Anda.</small>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($users->hasPages())
        <div class="pag-wrap">
            <div class="pag-info">
                Menampilkan <strong>{{ $users->firstItem() ?? 0 }}</strong>–<strong>{{ $users->lastItem() ?? 0 }}</strong> dari <strong>{{ $users->total() }}</strong> pengguna
            </div>
            <div>
                {{ $users->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>

</div>
@endsection
