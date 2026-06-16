@extends('admin.layouts.admin')

@section('title', 'Data Konfigurasi Jam Kerja Departemen')
@section('page-title', 'Data Konfigurasi Jam Kerja Departemen')

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

    .config-wrap { display: flex; flex-direction: column; gap: 20px; }

    /* ── PAGE HEADER ── */
    .config-header {
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

    .config-header-left { display: flex; align-items: center; gap: 14px; }

    .config-header-icon {
        width: 46px; height: 46px;
        border-radius: 12px;
        background: var(--indigo-soft);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .config-header-icon i { font-size: 22px; color: var(--indigo); }

    .config-header-title { font-size: 17px; font-weight: 800; color: var(--slate-900); letter-spacing: -0.2px; }
    .config-header-sub   { font-size: 12px; color: var(--slate-400); margin-top: 2px; }

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
    .filter-head i { font-size: 15px; color: var(--indigo); }
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
    .tbl-card-title i { font-size: 17px; color: var(--indigo); }
    .tbl-meta { font-size: 11px; color: var(--slate-400); background: var(--slate-100); padding: 3px 10px; border-radius: 50px; font-weight: 600; }

    .tbl-wrap { overflow-x: auto; }
    .tbl-wrap table { width: 100%; border-collapse: collapse; min-width: 850px; }
    .tbl-wrap thead th { padding: 12px 16px; background: var(--slate-50); font-size: 10.5px; font-weight: 700; color: var(--slate-400); text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid var(--slate-200); white-space: nowrap; text-align: left; }
    .tbl-wrap tbody td { padding: 14px 16px; font-size: 13px; color: var(--slate-700); border-bottom: 1px solid var(--slate-100); vertical-align: top; }
    .tbl-wrap tbody tr:last-child td { border-bottom: none; }
    .tbl-wrap tbody tr:hover td { background: var(--slate-50); transition: background 0.12s; }

    .no-cell { font-size: 12px; font-weight: 700; color: var(--slate-400); text-align: center; }

    .code-pill { display: inline-flex; background: var(--indigo-soft); color: var(--indigo); font-family: monospace; font-size: 12px; font-weight: 700; padding: 4px 10px; border-radius: 6px; border: 1px solid #E0E7FF; }
    
    .org-info { display: flex; flex-direction: column; gap: 4px; }
    .org-item { display: flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 700; color: var(--slate-800); }
    .org-item i { font-size: 15px; }
    .icon-cabang { color: var(--blue); }
    .icon-dept { color: var(--green); }

    .hk-badge { background: var(--blue); color: var(--white); font-size: 14px; font-weight: 800; padding: 2px 10px; border-radius: 6px; }

    .detail-list { display: flex; flex-direction: column; gap: 8px; }
    .detail-item { display: flex; align-items: flex-start; gap: 8px; font-size: 12px; }
    .di-day { background: var(--slate-100); color: var(--slate-600); font-weight: 700; border: 1px solid var(--slate-200); padding: 2px 8px; border-radius: 6px; min-width: 50px; text-align: center; flex-shrink: 0; }
    .di-info { flex: 1; }
    .di-multi { background: var(--blue-soft); color: var(--blue-dark); padding: 2px 6px; border-radius: 4px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px; border: 1px solid var(--blue-mid); margin-bottom: 4px; }
    .di-reg { background: var(--amber-soft); color: #B45309; padding: 2px 6px; border-radius: 4px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px; border: 1px solid #FDE68A; margin-bottom: 4px; }
    .di-time { color: var(--slate-500); font-family: monospace; font-weight: 600; }
    .di-more { font-size: 11px; font-weight: 600; color: var(--slate-400); margin-top: 4px; }

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
    
    /* Updated Pagination Links to work with Laravel Bootstrap 5 output */
    .pagination { margin: 0; display: flex; gap: 4px; list-style: none; padding: 0; }
    .pagination .page-item .page-link { display: flex; align-items: center; justify-content: center; min-width: 32px; height: 32px; padding: 0 8px; border-radius: 7px; font-size: 12px; font-weight: 700; border: 1.5px solid var(--slate-200); background: var(--white); color: var(--slate-600); text-decoration: none; transition: all 0.12s; box-shadow: none; }
    .pagination .page-item .page-link:hover { background: var(--blue-soft); border-color: var(--blue-mid); color: var(--blue); }
    .pagination .page-item.active .page-link { background: var(--blue); border-color: var(--blue); color: var(--white); z-index: 1; }
    .pagination .page-item.disabled .page-link { background: var(--slate-100); color: var(--slate-300); cursor: default; border-color: var(--slate-200); }
</style>
@endpush

@section('content')
<div class="config-wrap">

    {{-- HEADER --}}
    <div class="config-header">
        <div class="config-header-left">
            <div class="config-header-icon">
                <i class="mdi mdi-calendar-clock-outline"></i>
            </div>
            <div>
                <div class="config-header-title">Konfigurasi Jam Kerja Departemen</div>
                <div class="config-header-sub">Atur jadwal kerja per hari untuk setiap departemen di cabang</div>
            </div>
        </div>
        <div class="config-header-actions">
            <a href="{{ route('panel.konfigurasi-jk-dept.create') }}" class="btn-hdr btn-hdr-primary">
                <i class="mdi mdi-plus"></i> Tambah Konfigurasi
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
            <form action="{{ route('panel.konfigurasi-jk-dept.index') }}" method="GET">
                <div class="filter-grid">
                    <div class="fg">
                        <label>Cari Konfigurasi</label>
                        <input type="text" name="search" placeholder="Cari kode konfigurasi..." value="{{ request('search') }}">
                    </div>
                    <div class="fg">
                        <label>Filter Cabang</label>
                        <select name="kode_cabang">
                            <option value="">Semua Cabang</option>
                            @foreach($cabang as $cbg)
                            <option value="{{ $cbg->kode_cabang }}" {{ request('kode_cabang') == $cbg->kode_cabang ? 'selected' : '' }}>
                                {{ $cbg->nama_cabang }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="btn-filter">
                            <i class="mdi mdi-magnify"></i> Terapkan
                        </button>
                    </div>
                    <div>
                        <a href="{{ route('panel.konfigurasi-jk-dept.index') }}" class="btn-reset">
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
            <div class="tbl-card-title"><i class="mdi mdi-format-list-bulleted"></i> Daftar Konfigurasi</div>
            <span class="tbl-meta">{{ $konfigurasi->total() }} Data</span>
        </div>
        <div class="tbl-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width:40px; text-align:center;">No</th>
                        <th style="width:130px;">Kode</th>
                        <th style="width:250px;">Organisasi (Cabang & Dept)</th>
                        <th style="width:90px; text-align:center;">Hari Kerja</th>
                        <th>Jadwal Terdaftar</th>
                        <th style="text-align:center; width:140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($konfigurasi as $index => $config)
                    <tr>
                        <td class="no-cell">{{ $konfigurasi->firstItem() + $index }}</td>
                        <td>
                            <div class="code-pill">{{ $config->kode_jk_dept }}</div>
                        </td>
                        <td>
                            <div class="org-info">
                                <div class="org-item">
                                    <i class="mdi mdi-office-building icon-cabang"></i>
                                    {{ $config->cabang->nama_cabang ?? 'N/A' }}
                                </div>
                                <div class="org-item">
                                    <i class="mdi mdi-sitemap icon-dept"></i>
                                    {{ $config->departemen->nama_dept ?? 'N/A' }}
                                </div>
                            </div>
                        </td>
                        <td style="text-align:center;">
                            <span class="hk-badge">{{ $config->details->count() }} Hari</span>
                        </td>
                        <td>
                            @if($config->details->count() > 0)
                                <div class="detail-list">
                                    @foreach($config->details->take(2) as $detail)
                                    <div class="detail-item">
                                        <div class="di-day">{{ $detail->hari }}</div>
                                        <div class="di-info">
                                            @if($detail->jamKerja)
                                                @if($detail->jamKerja->tipe_jam_kerja == 'multi_shift')
                                                    <div class="di-multi"><i class="mdi mdi-layers"></i> {{ $detail->jamKerja->nama_jam_kerja }}</div>
                                                    <div class="di-time">
                                                        {{ $detail->jamKerja->total_shift }} shift: 
                                                        @foreach($detail->jamKerja->shifts->take(3) as $shift)
                                                            {{ $shift->nama_shift }}{{ !$loop->last ? ', ' : '' }}
                                                        @endforeach
                                                        @if($detail->jamKerja->shifts->count() > 3)
                                                            (+{{ $detail->jamKerja->shifts->count() - 3 }})
                                                        @endif
                                                    </div>
                                                @else
                                                    <div class="di-reg"><i class="mdi mdi-clock-outline"></i> {{ $detail->jamKerja->nama_jam_kerja }}</div>
                                                    <div class="di-time">
                                                        {{ date('H:i', strtotime($detail->jamKerja->jam_masuk)) }} - {{ date('H:i', strtotime($detail->jamKerja->jam_pulang)) }}
                                                    </div>
                                                @endif
                                            @else
                                                <div class="di-time" style="color:var(--slate-400);">- Tidak Ada Jadwal -</div>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                    @if($config->details->count() > 2)
                                        <div class="di-more"><i class="mdi mdi-dots-horizontal"></i> +{{ $config->details->count() - 2 }} hari lainnya...</div>
                                    @endif
                                </div>
                            @else
                                <span style="font-size:12px; color:var(--slate-400); font-weight:600;">Belum ada jadwal terdaftar</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-cell">
                                <a href="{{ route('panel.konfigurasi-jk-dept.show', $config->kode_jk_dept) }}" class="btn-act btn-act-blue" title="Detail">
                                    <i class="mdi mdi-eye"></i>
                                </a>
                                <a href="{{ route('panel.konfigurasi-jk-dept.edit', $config->kode_jk_dept) }}" class="btn-act btn-act-yellow" title="Edit">
                                    <i class="mdi mdi-pencil"></i>
                                </a>
                                <button type="button" class="btn-act btn-act-red" onclick="confirmDelete('{{ $config->kode_jk_dept }}')" title="Hapus">
                                    <i class="mdi mdi-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="tbl-empty">
                                <i class="mdi mdi-calendar-clock-outline"></i>
                                <p>Tidak ada data konfigurasi jam kerja departemen</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($konfigurasi->hasPages())
        <div class="pag-wrap">
            <div class="pag-info">
                Menampilkan <strong>{{ $konfigurasi->firstItem() ?? 0 }}</strong>–<strong>{{ $konfigurasi->lastItem() ?? 0 }}</strong> dari <strong>{{ $konfigurasi->total() }}</strong> data
            </div>
            <div>
                {{ $konfigurasi->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
            </div>
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
            text: 'Apakah Anda yakin ingin menghapus konfigurasi ini?',
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
                form.action = '{{ url("panel/konfigurasi-jk-dept") }}/' + kode;
                form.submit();
            }
        });
    }
</script>
@endpush