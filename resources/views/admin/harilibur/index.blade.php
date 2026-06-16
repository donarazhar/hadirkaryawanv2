@extends('admin.layouts.admin')
@section('title', 'Jadwal Libur Nasional')
@section('page-title', 'Jadwal Libur Nasional')

@push('styles')
<style>
    :root {
        --blue:        #2563EB;
        --blue-dark:   #1D4ED8;
        --blue-soft:   #EFF6FF;
        --blue-mid:    #BFDBFE;
        --green:       #10B981;
        --green-soft:  #ECFDF5;
        --red:         #EF4444;
        --red-soft:    #FEF2F2;
        --amber:       #F59E0B;
        --amber-soft:  #FFFBEB;
        --orange:      #F97316;
        --orange-soft: #FFF7ED;
        --purple:      #8B5CF6;
        --purple-soft: #F5F3FF;
        --slate-900:   #111827;
        --slate-700:   #374151;
        --slate-600:   #4B5563;
        --slate-400:   #9CA3AF;
        --slate-300:   #D1D5DB;
        --slate-200:   #E5E7EB;
        --slate-100:   #F3F4F6;
        --slate-50:    #F9FAFB;
        --white:       #FFFFFF;
        --shadow:      0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
        --radius:      14px;
        --radius-sm:   10px;
    }

    .hl-wrap { display: flex; flex-direction: column; gap: 20px; }

    /* ── PAGE HEADER ── */
    .hl-header {
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
    .hl-header-left  { display: flex; align-items: center; gap: 14px; }
    .hl-header-icon  {
        width: 46px; height: 46px;
        border-radius: 12px;
        background: var(--orange-soft);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .hl-header-icon i   { font-size: 22px; color: var(--orange); }
    .hl-header-title    { font-size: 17px; font-weight: 800; color: var(--slate-900); letter-spacing: -0.2px; }
    .hl-header-sub      { font-size: 12px; color: var(--slate-400); margin-top: 2px; }

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
    .alert-success-c { background: var(--green-soft);  color: #065F46; border: 1px solid #A7F3D0; }
    .alert-danger-c  { background: var(--red-soft);    color: #991B1B; border: 1px solid #FECACA; }
    .alert-warning-c { background: var(--amber-soft);  color: #B45309; border: 1px solid #FDE68A; }

    /* ── FILTER / YEAR CARD ── */
    .filter-card { background: var(--white); border: 1px solid var(--slate-200); border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; }
    .filter-head { display: flex; align-items: center; gap: 8px; padding: 13px 20px; border-bottom: 1px solid var(--slate-100); font-size: 11px; font-weight: 700; color: var(--slate-600); text-transform: uppercase; letter-spacing: 0.5px; }
    .filter-head i { font-size: 15px; color: var(--blue); }
    .filter-body { padding: 16px 20px; }

    .year-grid { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
    .fg { display: flex; flex-direction: column; gap: 5px; }
    .fg label { font-size: 10.5px; font-weight: 700; color: var(--slate-600); text-transform: uppercase; letter-spacing: 0.4px; }
    .fg select {
        height: 38px; border: 1.5px solid var(--slate-200); border-radius: 9px; padding: 0 12px;
        font-family: 'Inter', sans-serif; font-size: 13px; color: var(--slate-900);
        background: var(--white); outline: none; cursor: pointer;
        transition: border-color 0.15s, box-shadow 0.15s; min-width: 140px;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24'%3E%3Cpath fill='%239CA3AF' d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
        padding-right: 32px;
    }
    .fg select:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(37,99,235,0.10); }

    /* ── TABLE CARD ── */
    .tbl-card { background: var(--white); border: 1px solid var(--slate-200); border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; }
    .tbl-card-head { display: flex; align-items: center; justify-content: space-between; padding: 14px 20px; border-bottom: 1px solid var(--slate-100); }
    .tbl-card-title { font-size: 13px; font-weight: 700; color: var(--slate-900); display: flex; align-items: center; gap: 7px; }
    .tbl-card-title i { font-size: 17px; color: var(--orange); }
    .tbl-meta { font-size: 11px; color: var(--slate-400); background: var(--slate-100); padding: 3px 10px; border-radius: 50px; font-weight: 600; }

    .tbl-wrap { overflow-x: auto; }
    .tbl-wrap table { width: 100%; border-collapse: collapse; min-width: 520px; }
    .tbl-wrap thead th { padding: 12px 16px; background: var(--slate-50); font-size: 10.5px; font-weight: 700; color: var(--slate-400); text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid var(--slate-200); white-space: nowrap; text-align: left; }
    .tbl-wrap tbody td { padding: 14px 16px; font-size: 13px; color: var(--slate-700); border-bottom: 1px solid var(--slate-100); vertical-align: middle; }
    .tbl-wrap tbody tr:last-child td { border-bottom: none; }
    .tbl-wrap tbody tr:hover td { background: var(--slate-50); transition: background 0.12s; }

    .no-cell { font-size: 12px; font-weight: 700; color: var(--slate-400); text-align: center; }

    /* Date pill */
    .date-pill {
        display: inline-flex; align-items: center; gap: 6px;
        background: var(--orange-soft); color: var(--orange);
        font-size: 12.5px; font-weight: 700; padding: 5px 11px;
        border-radius: 8px; border: 1px solid #FED7AA;
    }
    .date-pill i { font-size: 14px; }

    /* Day badge */
    .day-badge {
        display: inline-flex; align-items: center;
        font-size: 11px; font-weight: 700; padding: 3px 9px;
        border-radius: 50px; background: var(--purple-soft); color: var(--purple);
        border: 1px solid #DDD6FE; white-space: nowrap;
    }

    /* Keterangan */
    .ket-cell { display: flex; align-items: center; gap: 8px; }
    .ket-icon { width: 28px; height: 28px; border-radius: 7px; background: var(--blue-soft); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .ket-icon i { font-size: 14px; color: var(--blue); }
    .ket-text { font-size: 13px; font-weight: 700; color: var(--slate-900); }

    /* Actions */
    .action-cell { display: flex; align-items: center; justify-content: center; gap: 6px; }
    .btn-act { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; border: 1px solid transparent; cursor: pointer; transition: background 0.15s; padding: 0; text-decoration: none; background: none; }
    .btn-act i { font-size: 16px; }
    .btn-act-yellow { background: var(--amber-soft); color: #D97706; border-color: #FDE68A; }
    .btn-act-yellow:hover { background: #FEF3C7; }
    .btn-act-red { background: var(--red-soft); color: var(--red); border-color: #FECACA; }
    .btn-act-red:hover { background: #FEE2E2; }

    /* Empty state */
    .tbl-empty { padding: 56px 16px; text-align: center; }
    .tbl-empty i { font-size: 48px; display: block; margin-bottom: 10px; color: var(--slate-200); }
    .tbl-empty p { font-size: 13px; font-weight: 600; margin: 0; color: var(--slate-400); }
    .tbl-empty small { font-size: 12px; color: var(--slate-300); }

    /* ── MODAL ── */
    .modal-content { border: 1px solid var(--slate-200); border-radius: var(--radius); box-shadow: 0 20px 60px rgba(0,0,0,0.12); }
    .modal-header { border-bottom: 1px solid var(--slate-100); padding: 18px 24px; }
    .modal-title { font-size: 15px; font-weight: 800; color: var(--slate-900); display: flex; align-items: center; gap: 8px; }
    .modal-title i { font-size: 20px; color: var(--orange); }
    .modal-body { padding: 20px 24px; }
    .modal-footer { border-top: 1px solid var(--slate-100); padding: 16px 24px; gap: 8px; }

    .mf { display: flex; flex-direction: column; gap: 5px; margin-bottom: 16px; }
    .mf:last-child { margin-bottom: 0; }
    .mf label { font-size: 10.5px; font-weight: 700; color: var(--slate-600); text-transform: uppercase; letter-spacing: 0.4px; }
    .mf input[type="date"],
    .mf input[type="text"] {
        height: 40px; border: 1.5px solid var(--slate-200); border-radius: 9px;
        padding: 0 12px; font-family: 'Inter', sans-serif; font-size: 13px;
        color: var(--slate-900); background: var(--white); outline: none; width: 100%;
        transition: border-color 0.15s, box-shadow 0.15s;
    }
    .mf input:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(37,99,235,0.10); }
    .mf input::placeholder { color: var(--slate-400); }

    .btn-modal {
        display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px;
        border: none; border-radius: 9px; font-family: 'Inter', sans-serif;
        font-size: 13px; font-weight: 700; cursor: pointer; transition: background 0.15s;
    }
    .btn-modal i { font-size: 16px; }
    .btn-modal-primary { background: var(--blue); color: var(--white); box-shadow: 0 2px 8px rgba(37,99,235,0.2); }
    .btn-modal-primary:hover { background: var(--blue-dark); }
    .btn-modal-cancel { background: var(--slate-100); color: var(--slate-600); border: 1px solid var(--slate-200); }
    .btn-modal-cancel:hover { background: var(--slate-200); }
</style>
@endpush

@section('content')
<div class="hl-wrap">

    {{-- HEADER --}}
    <div class="hl-header">
        <div class="hl-header-left">
            <div class="hl-header-icon">
                <i class="mdi mdi-calendar-star"></i>
            </div>
            <div>
                <div class="hl-header-title">Jadwal Hari Libur Nasional</div>
                <div class="hl-header-sub">Kelola daftar hari libur dan tanggal merah perusahaan</div>
            </div>
        </div>
        <button type="button" class="btn-hdr btn-hdr-primary" data-bs-toggle="modal" data-bs-target="#modalAdd">
            <i class="mdi mdi-plus"></i> Tambah Hari Libur
        </button>
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

    {{-- YEAR FILTER --}}
    <div class="filter-card">
        <div class="filter-head">
            <i class="mdi mdi-calendar-range"></i> Filter Tahun
        </div>
        <div class="filter-body">
            <form action="" method="GET">
                <div class="year-grid">
                    <div class="fg">
                        <label>Pilih Tahun</label>
                        <select name="tahun" id="tahun" onchange="this.form.submit()">
                            @for($i = date('Y') - 2; $i <= date('Y') + 2; $i++)
                                <option value="{{ $i }}" {{ request('tahun', date('Y')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="tbl-card">
        <div class="tbl-card-head">
            <div class="tbl-card-title">
                <i class="mdi mdi-calendar-clock"></i>
                Daftar Hari Libur {{ request('tahun', date('Y')) }}
            </div>
            <span class="tbl-meta">{{ count($harilibur) }} Hari Libur</span>
        </div>

        <div class="tbl-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width:40px; text-align:center;">No</th>
                        <th style="width:190px;">Tanggal</th>
                        <th style="width:110px;">Hari</th>
                        <th>Keterangan</th>
                        <th style="text-align:center; width:110px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($harilibur as $index => $d)
                    @php
                        $carbon = \Carbon\Carbon::parse($d->tanggal);
                    @endphp
                    <tr>
                        <td class="no-cell">{{ $index + 1 }}</td>
                        <td>
                            <div class="date-pill">
                                <i class="mdi mdi-calendar"></i>
                                {{ $carbon->translatedFormat('d F Y') }}
                            </div>
                        </td>
                        <td>
                            <span class="day-badge">{{ $carbon->translatedFormat('l') }}</span>
                        </td>
                        <td>
                            <div class="ket-cell">
                                <div class="ket-icon"><i class="mdi mdi-flag"></i></div>
                                <span class="ket-text">{{ $d->keterangan }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="action-cell">
                                <button type="button" class="btn-act btn-act-yellow" title="Edit"
                                    data-bs-toggle="modal" data-bs-target="#modalEdit{{ $d->id }}">
                                    <i class="mdi mdi-pencil"></i>
                                </button>
                                <form action="{{ route('panel.harilibur.destroy', $d->id) }}" method="POST"
                                    style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus hari libur ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-act btn-act-red" title="Hapus">
                                        <i class="mdi mdi-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    {{-- MODAL EDIT --}}
                    <div class="modal fade" id="modalEdit{{ $d->id }}" tabindex="-1" aria-labelledby="editLabel{{ $d->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form action="{{ route('panel.harilibur.update', $d->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editLabel{{ $d->id }}">
                                            <i class="mdi mdi-pencil-circle"></i> Edit Hari Libur
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mf">
                                            <label>Tanggal</label>
                                            <input type="date" name="tanggal" value="{{ $d->tanggal->format('Y-m-d') }}" required>
                                        </div>
                                        <div class="mf">
                                            <label>Keterangan / Nama Hari Libur</label>
                                            <input type="text" name="keterangan" value="{{ $d->keterangan }}" placeholder="Contoh: Tahun Baru, Idul Fitri" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn-modal btn-modal-cancel" data-bs-dismiss="modal">
                                            <i class="mdi mdi-close"></i> Batal
                                        </button>
                                        <button type="submit" class="btn-modal btn-modal-primary">
                                            <i class="mdi mdi-content-save"></i> Simpan Perubahan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="tbl-empty">
                                <i class="mdi mdi-calendar-remove-outline"></i>
                                <p>Belum ada jadwal libur di tahun {{ request('tahun', date('Y')) }}</p>
                                <small>Tambahkan hari libur menggunakan tombol di atas</small>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- MODAL ADD --}}
<div class="modal fade" id="modalAdd" tabindex="-1" aria-labelledby="addLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('panel.harilibur.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addLabel">
                        <i class="mdi mdi-calendar-plus"></i> Tambah Hari Libur
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mf">
                        <label>Tanggal</label>
                        <input type="date" name="tanggal" required>
                    </div>
                    <div class="mf">
                        <label>Keterangan / Nama Hari Libur</label>
                        <input type="text" name="keterangan" placeholder="Contoh: Tahun Baru, Idul Fitri" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal btn-modal-cancel" data-bs-dismiss="modal">
                        <i class="mdi mdi-close"></i> Batal
                    </button>
                    <button type="submit" class="btn-modal btn-modal-primary">
                        <i class="mdi mdi-content-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
