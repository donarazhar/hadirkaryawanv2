@extends('admin.layouts.admin')

@section('title', 'Data Izin & Sakit')
@section('page-title', 'Data Izin & Sakit')

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

    .izin-wrap { display: flex; flex-direction: column; gap: 20px; }

    /* ── PAGE HEADER ── */
    .izin-header {
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

    .izin-header-left { display: flex; align-items: center; gap: 14px; }

    .izin-header-icon {
        width: 46px; height: 46px;
        border-radius: 12px;
        background: var(--purple-soft);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .izin-header-icon i { font-size: 22px; color: var(--purple); }

    .izin-header-title { font-size: 17px; font-weight: 800; color: var(--slate-900); letter-spacing: -0.2px; }
    .izin-header-sub   { font-size: 12px; color: var(--slate-400); margin-top: 2px; }

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
        grid-template-columns: 1fr 1fr 1fr 1.5fr auto;
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
        min-width: 900px;
    }

    .tbl-wrap thead th {
        padding: 12px 16px;
        background: var(--slate-50);
        font-size: 10.5px;
        font-weight: 700;
        color: var(--slate-400);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid var(--slate-200);
        white-space: nowrap;
        text-align: left;
    }

    .tbl-wrap tbody td {
        padding: 14px 16px;
        font-size: 13px;
        color: var(--slate-700);
        border-bottom: 1px solid var(--slate-100);
        vertical-align: middle;
    }

    .tbl-wrap tbody tr:last-child td { border-bottom: none; }
    .tbl-wrap tbody tr:hover td { background: var(--slate-50); transition: background 0.12s; }

    .no-cell { font-size: 12px; font-weight: 700; color: var(--slate-400); text-align: center; }

    /* Date Cell */
    .date-cell strong { color: var(--slate-900); font-size: 13px; display: block; }
    .date-cell small { color: var(--slate-400); font-size: 11px; }

    /* User Cell */
    .user-cell .user-name { font-size: 13px; font-weight: 700; color: var(--slate-900); }
    .user-cell .user-nik { font-size: 11px; color: var(--slate-400); }

    /* Type Pill */
    .pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 700;
    }
    .pill-izin { background: var(--blue-soft); color: var(--blue); border: 1px solid var(--blue-mid); }
    .pill-sakit { background: var(--amber-soft); color: #D97706; border: 1px solid #FDE68A; }
    .pill-cuti { background: var(--purple-soft); color: var(--purple); border: 1px solid #DDD6FE; }

    /* Desc Cell */
    .desc-cell { max-width: 200px; }
    .desc-text {
        font-size: 12.5px; color: var(--slate-700);
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        margin-bottom: 4px;
    }
    .doc-link {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: 11px; font-weight: 600; color: var(--blue);
        background: var(--blue-soft); padding: 2px 8px; border-radius: 4px;
        text-decoration: none; border: 1px solid var(--blue-mid);
    }
    .doc-link:hover { background: var(--blue); color: var(--white); }

    /* Appr Status */
    .appr-group { display: flex; flex-direction: column; gap: 6px; }
    .appr-item { display: flex; flex-direction: column; gap: 2px; }
    .appr-label { font-size: 10px; font-weight: 700; color: var(--slate-400); text-transform: uppercase; }
    .appr-stat {
        display: inline-flex; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 4px; width: fit-content;
    }
    .st-wait { background: var(--amber-soft); color: #D97706; border: 1px solid #FDE68A; }
    .st-acc { background: var(--green-soft); color: var(--green); border: 1px solid #A7F3D0; }
    .st-rej { background: var(--red-soft); color: var(--red); border: 1px solid #FECACA; }
    .appr-note { font-size: 10.5px; color: var(--slate-500); font-style: italic; }

    /* Actions */
    .btn-act {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 6px 12px; border-radius: 7px; font-size: 11.5px; font-weight: 700;
        cursor: pointer; border: none; font-family: 'Inter', sans-serif;
        text-decoration: none; transition: background 0.15s;
    }
    .btn-act-blue { background: var(--blue); color: var(--white); }
    .btn-act-blue:hover { background: var(--blue-dark); }
    .btn-act-blue:disabled { background: var(--slate-200); color: var(--slate-400); cursor: not-allowed; }

    .btn-act-outline { background: transparent; color: var(--red); border: 1px solid var(--red); }
    .btn-act-outline:hover { background: var(--red-soft); }

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

    /* ── MODAL ── */
    .modal-content { border: none; border-radius: 16px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.15); }
    .modal-hd {
        display: flex; align-items: center; justify-content: space-between;
        padding: 16px 20px; border-bottom: 1px solid var(--slate-200);
    }
    .modal-hd-title { font-size: 15px; font-weight: 700; color: var(--slate-900); display: flex; align-items: center; gap: 8px; }
    .modal-hd-title i { font-size: 18px; color: var(--blue); }
    .btn-modal-x {
        width: 30px; height: 30px; border-radius: 8px; background: var(--slate-100); border: none;
        display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--slate-600);
    }
    .btn-modal-x:hover { background: var(--slate-200); }

    .modal-bd { padding: 20px; }
    .m-group { display: flex; flex-direction: column; gap: 6px; margin-bottom: 16px; }
    .m-group label { font-size: 11.5px; font-weight: 700; color: var(--slate-700); }
    .m-group select, .m-group textarea {
        border: 1.5px solid var(--slate-200); border-radius: 9px; padding: 10px 12px;
        font-family: 'Inter', sans-serif; font-size: 13px; width: 100%; outline: none;
    }
    .m-group select:focus, .m-group textarea:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(37,99,235,0.10); }

    .modal-foot {
        display: flex; justify-content: flex-end; gap: 8px;
        padding: 14px 20px; border-top: 1px solid var(--slate-100); background: var(--slate-50);
    }
    .btn-cancel {
        height: 36px; padding: 0 16px; border: 1.5px solid var(--slate-200); border-radius: 8px;
        font-family: 'Inter', sans-serif; font-size: 12.5px; font-weight: 700; color: var(--slate-600);
        background: var(--white); cursor: pointer;
    }
    .btn-cancel:hover { background: var(--slate-100); }
    .btn-save-m {
        height: 36px; padding: 0 20px; border: none; border-radius: 8px;
        font-family: 'Inter', sans-serif; font-size: 12.5px; font-weight: 700; color: var(--white);
        background: var(--blue); cursor: pointer;
    }
    .btn-save-m:hover { background: var(--blue-dark); }
</style>
@endpush

@section('content')
<div class="izin-wrap">

    {{-- HEADER --}}
    <div class="izin-header">
        <div class="izin-header-left">
            <div class="izin-header-icon">
                <i class="mdi mdi-calendar-clock"></i>
            </div>
            <div>
                <div class="izin-header-title">Data Izin & Sakit</div>
                <div class="izin-header-sub">Kelola pengajuan izin, sakit, dan cuti karyawan</div>
            </div>
        </div>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="alert-c alert-success-c"><i class="mdi mdi-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-c alert-danger-c"><i class="mdi mdi-alert-circle"></i> {{ session('error') }}</div>
    @endif

    {{-- FILTER --}}
    <div class="filter-card">
        <div class="filter-head">
            <i class="mdi mdi-filter-outline"></i> Filter Pengajuan
        </div>
        <div class="filter-body">
            <form action="{{ route('panel.izinsakit.index') }}" method="GET">
                <div class="filter-grid">
                    <div class="fg">
                        <label>Dari Tanggal</label>
                        <input type="date" name="dari" value="{{ request('dari') }}">
                    </div>
                    <div class="fg">
                        <label>Sampai Tanggal</label>
                        <input type="date" name="sampai" value="{{ request('sampai') }}">
                    </div>
                    <div class="fg">
                        <label>Status</label>
                        <select name="status_approved">
                            <option value="">Semua Status</option>
                            <option value="0" {{ request('status_approved') === '0' ? 'selected' : '' }}>Menunggu</option>
                            <option value="1" {{ request('status_approved') === '1' ? 'selected' : '' }}>Disetujui</option>
                            <option value="2" {{ request('status_approved') === '2' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    <div class="fg">
                        <label>Cari Karyawan</label>
                        <input type="text" name="nik_nama" placeholder="NIK atau Nama..." value="{{ request('nik_nama') }}">
                    </div>
                    <div>
                        <button type="submit" class="btn-filter">
                            <i class="mdi mdi-magnify"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="tbl-card">
        <div class="tbl-card-head">
            <div class="tbl-card-title"><i class="mdi mdi-format-list-bulleted"></i> Daftar Pengajuan</div>
            <span class="tbl-meta">{{ $izinsakit->total() }} Data</span>
        </div>
        <div class="tbl-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width:40px; text-align:center;">No</th>
                        <th>Tanggal</th>
                        <th>Karyawan</th>
                        <th>Tipe</th>
                        <th>Keterangan & Lampiran</th>
                        <th>Status Approval</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($izinsakit as $index => $item)
                    <tr>
                        <td class="no-cell">{{ $izinsakit->firstItem() + $index }}</td>
                        <td class="date-cell">
                            <strong>{{ date('d M Y', strtotime($item->tgl_izin_dari)) }}</strong>
                            @if($item->tgl_izin_dari != $item->tgl_izin_sampai)
                                <small>s/d {{ date('d M Y', strtotime($item->tgl_izin_sampai)) }}</small>
                            @endif
                        </td>
                        <td class="user-cell">
                            <div class="user-name">{{ $item->karyawan->nama_lengkap ?? 'Unknown' }}</div>
                            <div class="user-nik">{{ $item->nik }} • {{ $item->karyawan->departemen->nama_dept ?? '-' }}</div>
                        </td>
                        <td>
                            @if($item->status == 'i')
                                <div class="pill pill-izin"><i class="mdi mdi-information-outline"></i> Izin</div>
                            @elseif($item->status == 's')
                                <div class="pill pill-sakit"><i class="mdi mdi-hospital-box-outline"></i> Sakit</div>
                            @elseif($item->status == 'c')
                                <div class="pill pill-cuti"><i class="mdi mdi-calendar-arrow-right"></i> Cuti</div>
                            @endif
                        </td>
                        <td class="desc-cell">
                            <div class="desc-text" title="{{ $item->keterangan }}">{{ $item->keterangan }}</div>
                            @if($item->doc_sid)
                                <a href="{{ Storage::url('uploads/sid/'.$item->doc_sid) }}" target="_blank" class="doc-link">
                                    <i class="mdi mdi-paperclip"></i> Lihat Bukti
                                </a>
                            @endif
                        </td>
                        <td>
                            <div class="appr-group">
                                {{-- Pimpinan --}}
                                <div class="appr-item">
                                    <div class="appr-label">Pimpinan</div>
                                    @if($item->status_approved_atasan == '0')
                                        <div class="appr-stat st-wait">Menunggu</div>
                                    @elseif($item->status_approved_atasan == '1')
                                        <div class="appr-stat st-acc">Disetujui</div>
                                    @elseif($item->status_approved_atasan == '2')
                                        <div class="appr-stat st-rej">Ditolak</div>
                                    @endif
                                    @if($item->catatan_atasan)
                                        <div class="appr-note">"{{ $item->catatan_atasan }}"</div>
                                    @endif
                                </div>
                                {{-- HRD / Admin --}}
                                <div class="appr-item mt-1">
                                    <div class="appr-label">HRD</div>
                                    @if($item->status_approved == '0')
                                        <div class="appr-stat st-wait">Menunggu</div>
                                    @elseif($item->status_approved == '1')
                                        <div class="appr-stat st-acc">Disetujui</div>
                                    @elseif($item->status_approved == '2')
                                        <div class="appr-stat st-rej">Ditolak</div>
                                    @endif
                                    @if($item->catatan_admin)
                                        <div class="appr-note">"{{ $item->catatan_admin }}"</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td style="text-align:center;">
                            @php
                                $userRole = \Illuminate\Support\Facades\Auth::guard('user')->user()->role ?? 'admin';
                            @endphp

                            @if($userRole == 'pimpinan')
                                @if($item->status_approved_atasan == '0')
                                    <button class="btn-act btn-act-blue" onclick="openApprovalModal('{{ $item->kode_izin }}')">
                                        <i class="mdi mdi-check-decagram"></i> Action
                                    </button>
                                @else
                                    <form action="{{ route('panel.izinsakit.cancel', $item->kode_izin) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn-act btn-act-outline" onclick="return confirm('Yakin batalkan status pengajuan ini?')">
                                            <i class="mdi mdi-close"></i> Batal
                                        </button>
                                    </form>
                                @endif
                            @else
                                {{-- HRD / Admin --}}
                                @if($item->status_approved == '0')
                                    <button class="btn-act btn-act-blue" onclick="openApprovalModal('{{ $item->kode_izin }}')" {{ $item->status_approved_atasan != '1' ? 'disabled' : '' }} title="{{ $item->status_approved_atasan != '1' ? 'Menunggu Approval Pimpinan' : 'Lakukan Action' }}">
                                        <i class="mdi mdi-check-decagram"></i> Action
                                    </button>
                                @else
                                    <form action="{{ route('panel.izinsakit.cancel', $item->kode_izin) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn-act btn-act-outline" onclick="return confirm('Yakin batalkan status pengajuan ini?')">
                                            <i class="mdi mdi-close"></i> Batal
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="tbl-empty">
                                <i class="mdi mdi-calendar-remove"></i>
                                <p>Tidak ada data pengajuan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($izinsakit->hasPages())
        <div class="pag-wrap">
            <div class="pag-info">
                Menampilkan <strong>{{ $izinsakit->firstItem() ?? 0 }}</strong>–<strong>{{ $izinsakit->lastItem() ?? 0 }}</strong> dari <strong>{{ $izinsakit->total() }}</strong> data
            </div>
            <ul class="pag-list">
                @if($izinsakit->onFirstPage())
                    <li class="pag-item disabled"><span><i class="mdi mdi-chevron-left"></i></span></li>
                @else
                    <li class="pag-item"><a href="{{ $izinsakit->previousPageUrl() }}"><i class="mdi mdi-chevron-left"></i></a></li>
                @endif

                @foreach(range(1, $izinsakit->lastPage()) as $i)
                    @if($i == $izinsakit->currentPage())
                        <li class="pag-item active"><span>{{ $i }}</span></li>
                    @else
                        <li class="pag-item"><a href="{{ $izinsakit->url($i) }}">{{ $i }}</a></li>
                    @endif
                @endforeach

                @if($izinsakit->hasMorePages())
                    <li class="pag-item"><a href="{{ $izinsakit->nextPageUrl() }}"><i class="mdi mdi-chevron-right"></i></a></li>
                @else
                    <li class="pag-item disabled"><span><i class="mdi mdi-chevron-right"></i></span></li>
                @endif
            </ul>
        </div>
        @endif
    </div>

</div>

{{-- MODAL APPROVAL --}}
<div class="modal fade" id="modal-approve" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-hd">
                <div class="modal-hd-title">
                    <i class="mdi mdi-check-decagram text-blue"></i> Approval Pengajuan
                </div>
                <button type="button" class="btn-modal-x" data-bs-dismiss="modal">
                    <i class="mdi mdi-close"></i>
                </button>
            </div>
            <form action="" method="POST" id="form-approve">
                @csrf
                <div class="modal-bd">
                    <div class="m-group">
                        <label>Tindakan <span style="color:var(--red);">*</span></label>
                        <select name="status_approved" required>
                            <option value="">Pilih Tindakan...</option>
                            <option value="1">Setujui Pengajuan</option>
                            <option value="2">Tolak Pengajuan</option>
                        </select>
                    </div>
                    <div class="m-group mb-0">
                        <label>Catatan <span style="font-weight:400; color:var(--slate-400);">(Opsional, disarankan jika menolak)</span></label>
                        <textarea name="catatan_admin" rows="3" placeholder="Masukkan alasan penolakan atau catatan tambahan..."></textarea>
                    </div>
                </div>
                <div class="modal-foot">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-save-m">
                        <i class="mdi mdi-content-save"></i> Simpan Keputusan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openApprovalModal(kode_izin) {
        var form = document.getElementById('form-approve');
        var url = "{{ route('panel.izinsakit.approve', ':kode') }}";
        url = url.replace(':kode', kode_izin);
        
        form.action = url;
        form.reset();
        
        var modal = new bootstrap.Modal(document.getElementById('modal-approve'));
        modal.show();
    }
</script>
@endpush
