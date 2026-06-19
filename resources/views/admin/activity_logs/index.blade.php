@extends('admin.layouts.admin')

@section('title', 'Audit Trail & Log Aktivitas')
@section('page-title', 'Log Aktivitas Sistem')

@push('styles')
<style>
    :root {
        --blue:       #2563EB;
        --blue-dark:  #1D4ED8;
        --blue-soft:  #EFF6FF;
        --green:      #10B981;
        --green-soft: #ECFDF5;
        --red:        #EF4444;
        --red-soft:   #FEF2F2;
        --amber:      #F59E0B;
        --amber-soft: #FFFBEB;
        --indigo:     #6366F1;
        --indigo-soft:#EEF2FF;
        --cyan:       #06B6D4;
        --cyan-soft:  #ECFEFF;
        --slate-900:  #111827;
        --slate-800:  #1E293B;
        --slate-700:  #374151;
        --slate-600:  #4B5563;
        --slate-500:  #64748B;
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

    .log-wrap { display: flex; flex-direction: column; gap: 20px; }

    /* ── PAGE HEADER ── */
    .log-header {
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

    .log-header-left { display: flex; align-items: center; gap: 14px; }
    .log-header-icon {
        width: 46px; height: 46px; border-radius: 12px; background: var(--slate-800);
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .log-header-icon i { font-size: 22px; color: var(--white); }
    .log-header-title { font-size: 17px; font-weight: 800; color: var(--slate-900); letter-spacing: -0.2px; }
    .log-header-sub   { font-size: 12px; color: var(--slate-500); margin-top: 2px; }

    .btn-hdr {
        display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px;
        border: 1.5px solid var(--slate-200); border-radius: 9px; font-family: 'Inter', sans-serif;
        font-size: 13px; font-weight: 700; cursor: pointer; transition: all 0.15s;
        text-decoration: none; background: var(--white); color: var(--slate-700); white-space: nowrap;
    }
    .btn-hdr:hover { background: var(--slate-50); color: var(--slate-900); border-color: var(--slate-300); }

    /* ── TABLE CARD ── */
    .tbl-card { background: var(--white); border: 1px solid var(--slate-200); border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; }
    .tbl-card-head { display: flex; align-items: center; justify-content: space-between; padding: 14px 20px; border-bottom: 1px solid var(--slate-100); }
    .tbl-card-title { font-size: 13px; font-weight: 700; color: var(--slate-900); display: flex; align-items: center; gap: 7px; }
    .tbl-card-title i { font-size: 17px; color: var(--slate-700); }
    .tbl-meta { font-size: 11px; color: var(--slate-500); background: var(--slate-100); padding: 3px 10px; border-radius: 50px; font-weight: 600; }

    .tbl-wrap { overflow-x: auto; }
    .tbl-wrap table { width: 100%; border-collapse: collapse; min-width: 900px; }
    .tbl-wrap thead th { padding: 12px 16px; background: var(--slate-50); font-size: 10.5px; font-weight: 700; color: var(--slate-500); text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid var(--slate-200); white-space: nowrap; text-align: left; }
    .tbl-wrap tbody td { padding: 14px 16px; font-size: 13px; color: var(--slate-700); border-bottom: 1px solid var(--slate-100); vertical-align: middle; }
    .tbl-wrap tbody tr:last-child td { border-bottom: none; }
    .tbl-wrap tbody tr:hover td { background: var(--slate-50); transition: background 0.12s; }

    /* Table cells */
    .time-cell { display: flex; flex-direction: column; gap: 3px; }
    .tc-date { font-weight: 700; color: var(--slate-900); font-size: 12.5px; }
    .tc-time { font-size: 11px; color: var(--slate-500); font-family: monospace; }

    .user-cell { display: flex; align-items: center; gap: 10px; }
    .uc-icon { width: 28px; height: 28px; border-radius: 50%; background: var(--slate-100); border: 1px solid var(--slate-200); display: flex; align-items: center; justify-content: center; font-size: 13px; color: var(--slate-600); }
    .uc-name { font-weight: 700; color: var(--slate-800); }

    .desc-cell { font-size: 12.5px; line-height: 1.4; color: var(--slate-600); max-width: 300px; white-space: normal; }

    .ip-cell { display: inline-flex; align-items: center; gap: 4px; font-family: monospace; font-size: 12px; color: var(--slate-500); background: var(--slate-50); padding: 3px 8px; border-radius: 6px; border: 1px solid var(--slate-200); }
    .ip-cell i { font-size: 12px; }

    /* Badges */
    .role-badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 50px; font-size: 11px; font-weight: 700; text-transform: capitalize; border: 1px solid transparent; }
    .rb-superadmin { background: var(--red-soft); color: var(--red); border-color: #FECACA; }
    .rb-admin { background: var(--blue-soft); color: var(--blue-dark); border-color: var(--blue-mid); }
    .rb-pimpinan { background: var(--cyan-soft); color: #0891B2; border-color: #CFFAFE; }
    .rb-default { background: var(--slate-100); color: var(--slate-600); border-color: var(--slate-300); }

    .act-badge { display: inline-block; padding: 3px 8px; border-radius: 6px; font-size: 11px; font-weight: 800; font-family: monospace; letter-spacing: 0.5px; border: 1px solid transparent; text-transform: uppercase; }
    .ab-create, .ab-store, .ab-insert { background: var(--green-soft); color: #065F46; border-color: #A7F3D0; }
    .ab-update, .ab-edit { background: var(--amber-soft); color: #B45309; border-color: #FDE68A; }
    .ab-delete, .ab-destroy { background: var(--red-soft); color: #991B1B; border-color: #FECACA; }
    .ab-login, .ab-auth { background: var(--indigo-soft); color: var(--indigo); border-color: #E0E7FF; }
    .ab-default { background: var(--slate-100); color: var(--slate-700); border-color: var(--slate-300); }

    /* Empty state */
    .tbl-empty { padding: 56px 16px; text-align: center; color: var(--slate-400); }
    .tbl-empty i { font-size: 44px; display: block; margin-bottom: 10px; color: var(--slate-200); }
    .tbl-empty p { font-size: 13px; font-weight: 600; margin: 0; color: var(--slate-600); }

    /* ── PAGINATION ── */
    .pag-wrap { display: flex; align-items: center; justify-content: space-between; padding: 14px 20px; border-top: 1px solid var(--slate-100); background: var(--slate-50); flex-wrap: wrap; gap: 10px; }
    .pag-info { font-size: 12px; color: var(--slate-500); font-weight: 600; }
    .pag-info strong { color: var(--slate-900); }
    
    .pagination { margin: 0; display: flex; gap: 4px; list-style: none; padding: 0; }
    .pagination .page-item .page-link { display: flex; align-items: center; justify-content: center; min-width: 32px; height: 32px; padding: 0 8px; border-radius: 7px; font-size: 12px; font-weight: 700; border: 1.5px solid var(--slate-200); background: var(--white); color: var(--slate-600); text-decoration: none; transition: all 0.12s; box-shadow: none; }
    .pagination .page-item .page-link:hover { background: var(--slate-100); border-color: var(--slate-300); color: var(--slate-900); }
    .pagination .page-item.active .page-link { background: var(--slate-800); border-color: var(--slate-800); color: var(--white); z-index: 1; }
    .pagination .page-item.disabled .page-link { background: var(--slate-50); color: var(--slate-300); cursor: default; border-color: var(--slate-200); }
</style>
@endpush

@section('content')
<div class="log-wrap">

    {{-- HEADER --}}
    <div class="log-header">
        <div class="log-header-left">
            <div class="log-header-icon">
                <i class="mdi mdi-text-box-search-outline"></i>
            </div>
            <div>
                <div class="log-header-title">Audit Trail & Log Aktivitas</div>
                <div class="log-header-sub">Pantau seluruh riwayat aktivitas yang terjadi pada sistem</div>
            </div>
        </div>
        <div>
            <button onclick="window.location.reload();" class="btn-hdr">
                <i class="mdi mdi-refresh"></i> Segarkan Data
            </button>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="tbl-card">
        <div class="tbl-card-head">
            <div class="tbl-card-title"><i class="mdi mdi-history"></i> Riwayat Terbaru</div>
            <span class="tbl-meta">{{ $logs->total() }} Catatan</span>
        </div>
        <div class="tbl-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width: 130px;">Waktu Kejadian</th>
                        <th>Pelaku (User)</th>
                        <th>Tingkat Akses</th>
                        <th>Aksi</th>
                        <th style="width: 250px;">Deskripsi Aktivitas</th>
                        <th style="width: 200px;">Informasi Akses</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td>
                            <div class="time-cell">
                                <span class="tc-date">{{ $log->created_at->format('d M Y') }}</span>
                                <span class="tc-time"><i class="mdi mdi-clock-outline"></i> {{ $log->created_at->format('H:i:s') }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="user-cell">
                                <div class="uc-icon"><i class="mdi mdi-account"></i></div>
                                <span class="uc-name">{{ $log->user_name ?? 'Sistem / Anonim' }}</span>
                            </div>
                        </td>
                        <td>
                            @if($log->role == 'superadmin')
                                <span class="role-badge rb-superadmin"><i class="mdi mdi-shield-crown"></i> Superadmin</span>
                            @elseif($log->role == 'admin')
                                <span class="role-badge rb-admin"><i class="mdi mdi-shield-account"></i> Admin</span>
                            @elseif($log->role == 'pimpinan')
                                <span class="role-badge rb-pimpinan"><i class="mdi mdi-account-tie"></i> Pimpinan</span>
                            @else
                                <span class="role-badge rb-default"><i class="mdi mdi-account-circle"></i> {{ ucfirst($log->role ?? 'N/A') }}</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $act = strtolower($log->action);
                                $badgeClass = 'ab-default';
                                if (in_array($act, ['create', 'store', 'insert', 'add'])) $badgeClass = 'ab-create';
                                elseif (in_array($act, ['update', 'edit', 'modify'])) $badgeClass = 'ab-update';
                                elseif (in_array($act, ['delete', 'destroy', 'remove'])) $badgeClass = 'ab-delete';
                                elseif (in_array($act, ['login', 'auth', 'logout'])) $badgeClass = 'ab-auth';
                            @endphp
                            <span class="act-badge {{ $badgeClass }}">{{ $log->action }}</span>
                        </td>
                        <td>
                            <div class="desc-cell">{{ $log->description }}</div>
                        </td>
                        <td>
                            <div style="display: flex; flex-direction: column; gap: 4px;">
                                <span class="ip-cell" title="IP Address"><i class="mdi mdi-laptop"></i> {{ $log->ip_address ?? 'Unknown IP' }}</span>
                                @if($log->location)
                                    <span style="font-size: 11px; color: var(--slate-600);"><i class="mdi mdi-map-marker"></i> {{ $log->location }}</span>
                                @endif
                                @if($log->user_agent)
                                    <span style="font-size: 10px; color: var(--slate-400); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 180px;" title="{{ $log->user_agent }}">
                                        <i class="mdi mdi-web"></i> {{ $log->user_agent }}
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="tbl-empty">
                                <i class="mdi mdi-history"></i>
                                <p>Belum ada riwayat aktivitas yang tercatat pada sistem.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($logs->hasPages())
        <div class="pag-wrap">
            <div class="pag-info">
                Menampilkan <strong>{{ $logs->firstItem() ?? 0 }}</strong>–<strong>{{ $logs->lastItem() ?? 0 }}</strong> dari <strong>{{ $logs->total() }}</strong> log aktivitas
            </div>
            <div>
                {{ $logs->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>

</div>
@endsection
