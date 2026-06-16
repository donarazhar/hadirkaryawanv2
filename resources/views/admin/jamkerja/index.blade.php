@extends('admin.layouts.admin')

@section('title', 'Data Jam Kerja')
@section('page-title', 'Data Jam Kerja')

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

    .jk-wrap { display: flex; flex-direction: column; gap: 20px; }

    /* ── PAGE HEADER ── */
    .jk-header {
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

    .jk-header-left { display: flex; align-items: center; gap: 14px; }

    .jk-header-icon {
        width: 46px; height: 46px;
        border-radius: 12px;
        background: var(--purple-soft);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .jk-header-icon i { font-size: 22px; color: var(--purple); }

    .jk-header-title { font-size: 17px; font-weight: 800; color: var(--slate-900); letter-spacing: -0.2px; }
    .jk-header-sub   { font-size: 12px; color: var(--slate-400); margin-top: 2px; }

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
    .filter-head i { font-size: 15px; color: var(--purple); }
    .filter-body { padding: 16px 20px; }

    .filter-grid { display: grid; grid-template-columns: 1.5fr 1fr auto auto; gap: 12px; align-items: end; }
    @media (max-width: 768px) { .filter-grid { grid-template-columns: 1fr; } }

    .fg { display: flex; flex-direction: column; gap: 5px; }
    .fg label { font-size: 10.5px; font-weight: 700; color: var(--slate-600); text-transform: uppercase; letter-spacing: 0.4px; }
    .fg input, .fg select { height: 38px; border: 1.5px solid var(--slate-200); border-radius: 9px; padding: 0 12px; font-family: 'Inter', sans-serif; font-size: 13px; color: var(--slate-900); background: var(--white); outline: none; transition: border-color 0.15s, box-shadow 0.15s; width: 100%; }
    .fg input:focus, .fg select:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(37,99,235,0.10); }

    .btn-filter { height: 38px; padding: 0 20px; background: var(--blue); color: var(--white); border: none; border-radius: 9px; font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px; transition: background 0.15s; white-space: nowrap; }
    .btn-filter:hover { background: var(--blue-dark); }
    .btn-reset { height: 38px; padding: 0 20px; background: var(--white); color: var(--slate-700); border: 1.5px solid var(--slate-200); border-radius: 9px; font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px; transition: all 0.15s; text-decoration: none; white-space: nowrap; }
    .btn-reset:hover { background: var(--slate-50); border-color: var(--slate-300); color: var(--slate-900); }

    /* ── TABLE CARD ── */
    .tbl-card { background: var(--white); border: 1px solid var(--slate-200); border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; }
    .tbl-card-head { display: flex; align-items: center; justify-content: space-between; padding: 14px 20px; border-bottom: 1px solid var(--slate-100); }
    .tbl-card-title { font-size: 13px; font-weight: 700; color: var(--slate-900); display: flex; align-items: center; gap: 7px; }
    .tbl-card-title i { font-size: 17px; color: var(--purple); }
    .tbl-meta { font-size: 11px; color: var(--slate-400); background: var(--slate-100); padding: 3px 10px; border-radius: 50px; font-weight: 600; }

    .tbl-wrap { overflow-x: auto; }
    .tbl-wrap table { width: 100%; border-collapse: collapse; min-width: 850px; }
    .tbl-wrap thead th { padding: 12px 16px; background: var(--slate-50); font-size: 10.5px; font-weight: 700; color: var(--slate-400); text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid var(--slate-200); white-space: nowrap; text-align: left; }
    .tbl-wrap tbody td { padding: 14px 16px; font-size: 13px; color: var(--slate-700); border-bottom: 1px solid var(--slate-100); vertical-align: top; }
    .tbl-wrap tbody tr:last-child td { border-bottom: none; }
    .tbl-wrap tbody tr:hover td { background: var(--slate-50); transition: background 0.12s; }

    .no-cell { font-size: 12px; font-weight: 700; color: var(--slate-400); text-align: center; }

    .code-pill { display: inline-flex; background: var(--purple-soft); color: var(--purple); font-family: monospace; font-size: 12.5px; font-weight: 700; padding: 4px 10px; border-radius: 6px; border: 1px solid #DDD6FE; }
    .jk-name { font-size: 13.5px; font-weight: 800; color: var(--slate-900); margin-bottom: 4px; }
    
    .type-pill { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 50px; font-size: 11px; font-weight: 700; }
    .tp-multi { background: var(--blue-soft); color: var(--blue-dark); border: 1px solid var(--blue-mid); }
    .tp-reg { background: var(--slate-100); color: var(--slate-700); border: 1px solid var(--slate-200); }

    /* Schedule Display */
    .sched-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 6px; max-width: 250px; }
    .sched-item { display: flex; align-items: center; justify-content: space-between; font-size: 11.5px; border-radius: 6px; padding: 4px 8px; background: var(--slate-50); border: 1px solid var(--slate-100); }
    .sched-item span.lbl { color: var(--slate-500); font-weight: 600; display: flex; align-items: center; gap: 4px; }
    .sched-item span.lbl i { font-size: 12px; }
    .sched-item span.val { color: var(--slate-900); font-weight: 700; font-family: monospace; }
    .lbl-in i { color: var(--green); }
    .lbl-out i { color: var(--red); }
    .lbl-bgn i { color: var(--blue); }
    .lbl-end i { color: var(--amber); }

    .shift-list { display: flex; flex-direction: column; gap: 4px; }
    .shift-item { display: flex; align-items: center; gap: 8px; font-size: 11.5px; padding: 4px 8px; background: var(--slate-50); border: 1px solid var(--slate-100); border-radius: 6px; }
    .si-no { background: var(--blue); color: var(--white); width: 18px; height: 18px; display: inline-flex; align-items: center; justify-content: center; border-radius: 4px; font-weight: 800; font-size: 10px; }
    .si-name { font-weight: 700; color: var(--slate-800); }
    .si-time { color: var(--slate-500); font-family: monospace; font-weight: 600; margin-left: auto; }
    .si-more { font-size: 11px; font-weight: 600; color: var(--slate-400); margin-top: 2px; }

    .lh-badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; }
    .lh-yes { background: var(--amber-soft); color: #B45309; }
    .lh-no { color: var(--slate-400); }

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
<div class="jk-wrap">

    {{-- HEADER --}}
    <div class="jk-header">
        <div class="jk-header-left">
            <div class="jk-header-icon">
                <i class="mdi mdi-clock-check-outline"></i>
            </div>
            <div>
                <div class="jk-header-title">Master Data Jam Kerja</div>
                <div class="jk-header-sub">Kelola jadwal shift reguler dan multi-shift karyawan</div>
            </div>
        </div>
        <div class="jk-header-actions">
            <a href="{{ route('panel.jamkerja.create') }}" class="btn-hdr btn-hdr-primary">
                <i class="mdi mdi-plus"></i> Tambah Jam Kerja
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
            <i class="mdi mdi-filter-variant"></i> Filter Data
        </div>
        <div class="filter-body">
            <form action="{{ route('panel.jamkerja.index') }}" method="GET">
                <div class="filter-grid">
                    <div class="fg">
                        <label>Cari Jam Kerja</label>
                        <input type="text" name="search" placeholder="Cari berdasarkan kode atau nama jam kerja..." value="{{ request('search') }}">
                    </div>
                    <div class="fg">
                        <label>Tipe Jam Kerja</label>
                        <select name="tipe">
                            <option value="">Semua Tipe</option>
                            <option value="regular" {{ request('tipe') == 'regular' ? 'selected' : '' }}>Regular</option>
                            <option value="multi_shift" {{ request('tipe') == 'multi_shift' ? 'selected' : '' }}>Multi Shift</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="btn-filter">
                            <i class="mdi mdi-magnify"></i> Terapkan
                        </button>
                    </div>
                    <div>
                        <a href="{{ route('panel.jamkerja.index') }}" class="btn-reset">
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
            <div class="tbl-card-title"><i class="mdi mdi-format-list-bulleted"></i> Daftar Jam Kerja</div>
            <span class="tbl-meta">{{ $jamkerja->total() }} Data</span>
        </div>
        <div class="tbl-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width:40px; text-align:center;">No</th>
                        <th style="width:120px;">Kode</th>
                        <th>Info Jam Kerja</th>
                        <th style="width:280px;">Jadwal / Shifts</th>
                        <th style="width:100px; text-align:center;">Lintas Hari</th>
                        <th style="text-align:center; width:150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jamkerja as $index => $jk)
                    <tr>
                        <td class="no-cell">{{ $jamkerja->firstItem() + $index }}</td>
                        <td>
                            <div class="code-pill">{{ $jk->kode_jam_kerja }}</div>
                        </td>
                        <td>
                            <div class="jk-name">{{ $jk->nama_jam_kerja }}</div>
                            @if($jk->tipe_jam_kerja == 'multi_shift')
                                <div class="type-pill tp-multi"><i class="mdi mdi-layers"></i> Multi Shift ({{ $jk->total_shift }} shift/hari)</div>
                            @else
                                <div class="type-pill tp-reg"><i class="mdi mdi-clock-outline"></i> Regular</div>
                            @endif
                        </td>
                        <td>
                            @if($jk->tipe_jam_kerja == 'multi_shift' && $jk->shifts->count() > 0)
                                <div class="shift-list">
                                    @foreach($jk->shifts->take(3) as $shift)
                                    <div class="shift-item">
                                        <div class="si-no">{{ $shift->shift_ke }}</div>
                                        <div class="si-name">{{ $shift->nama_shift }}</div>
                                        <div class="si-time">{{ date('H:i', strtotime($shift->jam_masuk)) }} - {{ date('H:i', strtotime($shift->jam_pulang)) }}</div>
                                    </div>
                                    @endforeach
                                    @if($jk->shifts->count() > 3)
                                        <div class="si-more"><i class="mdi mdi-dots-horizontal"></i> +{{ $jk->shifts->count() - 3 }} shift lainnya</div>
                                    @endif
                                </div>
                            @else
                                <div class="sched-grid">
                                    <div class="sched-item">
                                        <span class="lbl lbl-bgn"><i class="mdi mdi-ray-start-arrow"></i> Awal</span>
                                        <span class="val">{{ date('H:i', strtotime($jk->awal_jam_masuk)) }}</span>
                                    </div>
                                    <div class="sched-item">
                                        <span class="lbl lbl-in"><i class="mdi mdi-login"></i> Masuk</span>
                                        <span class="val">{{ date('H:i', strtotime($jk->jam_masuk)) }}</span>
                                    </div>
                                    <div class="sched-item">
                                        <span class="lbl lbl-end"><i class="mdi mdi-ray-end-arrow"></i> Akhir</span>
                                        <span class="val">{{ date('H:i', strtotime($jk->akhir_jam_masuk)) }}</span>
                                    </div>
                                    <div class="sched-item">
                                        <span class="lbl lbl-out"><i class="mdi mdi-logout"></i> Pulang</span>
                                        <span class="val">{{ date('H:i', strtotime($jk->jam_pulang)) }}</span>
                                    </div>
                                </div>
                            @endif
                        </td>
                        <td style="text-align:center;">
                            @if($jk->lintashari == '1')
                                <div class="lh-badge lh-yes"><i class="mdi mdi-weather-night"></i> Ya</div>
                            @else
                                <div class="lh-badge lh-no"><i class="mdi mdi-minus"></i></div>
                            @endif
                        </td>
                        <td>
                            <div class="action-cell">
                                <a href="{{ route('panel.jamkerja.show', $jk->kode_jam_kerja) }}" class="btn-act btn-act-blue" title="Detail">
                                    <i class="mdi mdi-eye"></i>
                                </a>
                                <a href="{{ route('panel.jamkerja.edit', $jk->kode_jam_kerja) }}" class="btn-act btn-act-yellow" title="Edit">
                                    <i class="mdi mdi-pencil"></i>
                                </a>
                                <button type="button" class="btn-act btn-act-red" onclick="confirmDelete('{{ $jk->kode_jam_kerja }}')" title="Hapus">
                                    <i class="mdi mdi-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="tbl-empty">
                                <i class="mdi mdi-clock-remove-outline"></i>
                                <p>Tidak ada data jam kerja</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($jamkerja->hasPages())
        <div class="pag-wrap">
            <div class="pag-info">
                Menampilkan <strong>{{ $jamkerja->firstItem() ?? 0 }}</strong>–<strong>{{ $jamkerja->lastItem() ?? 0 }}</strong> dari <strong>{{ $jamkerja->total() }}</strong> data
            </div>
            <ul class="pag-list">
                @if($jamkerja->onFirstPage())
                    <li class="pag-item disabled"><span><i class="mdi mdi-chevron-left"></i></span></li>
                @else
                    <li class="pag-item"><a href="{{ $jamkerja->previousPageUrl() }}"><i class="mdi mdi-chevron-left"></i></a></li>
                @endif

                @foreach(range(1, $jamkerja->lastPage()) as $i)
                    @if($i == $jamkerja->currentPage())
                        <li class="pag-item active"><span>{{ $i }}</span></li>
                    @else
                        <li class="pag-item"><a href="{{ $jamkerja->url($i) }}">{{ $i }}</a></li>
                    @endif
                @endforeach

                @if($jamkerja->hasMorePages())
                    <li class="pag-item"><a href="{{ $jamkerja->nextPageUrl() }}"><i class="mdi mdi-chevron-right"></i></a></li>
                @else
                    <li class="pag-item disabled"><span><i class="mdi mdi-chevron-right"></i></span></li>
                @endif
            </ul>
        </div>
        @endif
    </div>

</div>

<!-- Delete Form -->
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(kode) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: 'Apakah Anda yakin ingin menghapus jam kerja ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#9CA3AF',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('delete-form');
                form.action = '{{ url("panel/jamkerja") }}/' + kode;
                form.submit();
            }
        });
    }
</script>
@endpush