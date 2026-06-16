@extends('admin.layouts.admin')

@section('title', 'Master Data Cuti')
@section('page-title', 'Master Data Cuti')

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

    .cuti-wrap { display: flex; flex-direction: column; gap: 20px; }

    /* ── PAGE HEADER ── */
    .cuti-header {
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

    .cuti-header-left { display: flex; align-items: center; gap: 14px; }

    .cuti-header-icon {
        width: 46px; height: 46px;
        border-radius: 12px;
        background: var(--purple-soft);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .cuti-header-icon i { font-size: 22px; color: var(--purple); }

    .cuti-header-title { font-size: 17px; font-weight: 800; color: var(--slate-900); letter-spacing: -0.2px; }
    .cuti-header-sub   { font-size: 12px; color: var(--slate-400); margin-top: 2px; }

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

    .err-list { margin: 0; padding-left: 18px; }
    .err-list li { font-size: 12px; color: #991B1B; margin-bottom: 2px; }

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

    .tbl-wrap { overflow-x: auto; }

    .tbl-wrap table {
        width: 100%;
        border-collapse: collapse;
        min-width: 600px;
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

    .code-pill {
        display: inline-flex;
        background: var(--slate-100);
        color: var(--slate-700);
        font-family: monospace;
        font-size: 12.5px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 6px;
        border: 1px solid var(--slate-200);
    }

    .name-cell { font-size: 13.5px; font-weight: 700; color: var(--slate-900); }

    .qty-cell { font-size: 13px; font-weight: 700; color: var(--blue); }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 700;
    }
    .sp-aktif { background: var(--green-soft); color: var(--green); border: 1px solid #A7F3D0; }
    .sp-non { background: var(--red-soft); color: var(--red); border: 1px solid #FECACA; }

    /* Actions */
    .action-cell { display: flex; align-items: center; justify-content: center; gap: 6px; }

    .btn-act {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 6px 10px; border-radius: 7px; font-size: 11.5px; font-weight: 700;
        cursor: pointer; border: 1.5px solid transparent; font-family: 'Inter', sans-serif;
        text-decoration: none; transition: background 0.15s;
    }
    .btn-act i { font-size: 14px; }
    .btn-act-edit { background: var(--amber-soft); color: #D97706; border-color: #FDE68A; }
    .btn-act-edit:hover { background: #FEF3C7; }
    .btn-act-del { background: var(--red-soft); color: var(--red); border-color: #FECACA; }
    .btn-act-del:hover { background: #FEE2E2; }

    /* Empty state */
    .tbl-empty { padding: 56px 16px; text-align: center; color: var(--slate-400); }
    .tbl-empty i { font-size: 44px; display: block; margin-bottom: 10px; color: var(--slate-200); }
    .tbl-empty p { font-size: 13px; font-weight: 600; margin: 0; color: var(--slate-600); }

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
    .m-group:last-child { margin-bottom: 0; }
    .m-group label { font-size: 11.5px; font-weight: 700; color: var(--slate-700); }
    .m-group input, .m-group select {
        border: 1.5px solid var(--slate-200); border-radius: 9px; padding: 10px 12px;
        font-family: 'Inter', sans-serif; font-size: 13px; width: 100%; outline: none;
    }
    .m-group input:focus, .m-group select:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(37,99,235,0.10); }
    .m-group input:disabled { background: var(--slate-50); color: var(--slate-500); cursor: not-allowed; }

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
        background: var(--blue); cursor: pointer; display: flex; align-items: center; gap: 6px;
    }
    .btn-save-m:hover { background: var(--blue-dark); }

    /* Responsive */
    @media (max-width: 640px) {
        .cuti-header { padding: 16px; }
        .cuti-header-actions { width: 100%; }
        .btn-hdr { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')
<div class="cuti-wrap">

    {{-- HEADER --}}
    <div class="cuti-header">
        <div class="cuti-header-left">
            <div class="cuti-header-icon">
                <i class="mdi mdi-calendar-check"></i>
            </div>
            <div>
                <div class="cuti-header-title">Master Data Cuti</div>
                <div class="cuti-header-sub">Kelola jenis-jenis cuti yang tersedia</div>
            </div>
        </div>
        <div class="cuti-header-actions">
            <button type="button" class="btn-hdr btn-hdr-primary" data-bs-toggle="modal" data-bs-target="#modal-cuti">
                <i class="mdi mdi-plus"></i> Tambah Data Cuti
            </button>
        </div>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="alert-c alert-success-c"><i class="mdi mdi-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-c alert-danger-c"><i class="mdi mdi-alert-circle"></i> {{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert-c alert-danger-c align-items-start">
            <i class="mdi mdi-alert-circle mt-1"></i>
            <ul class="err-list">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- TABLE --}}
    <div class="tbl-card">
        <div class="tbl-card-head">
            <div class="tbl-card-title"><i class="mdi mdi-format-list-bulleted"></i> Daftar Jenis Cuti</div>
        </div>
        <div class="tbl-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width:40px; text-align:center;">No</th>
                        <th>Kode Cuti</th>
                        <th>Nama Cuti</th>
                        <th>Jatah Cuti</th>
                        <th>Status</th>
                        <th style="text-align:center; width:140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cuti as $d)
                    <tr>
                        <td class="no-cell">{{ $loop->iteration }}</td>
                        <td><div class="code-pill">{{ $d->kode_cuti }}</div></td>
                        <td class="name-cell">{{ $d->nama_cuti }}</td>
                        <td class="qty-cell">{{ $d->jml_hari }} Hari</td>
                        <td>
                            @if($d->status == 'aktif')
                                <div class="status-pill sp-aktif"><i class="mdi mdi-check-circle-outline"></i> Aktif</div>
                            @else
                                <div class="status-pill sp-non"><i class="mdi mdi-close-circle-outline"></i> Nonaktif</div>
                            @endif
                        </td>
                        <td>
                            <div class="action-cell">
                                <button type="button" class="btn-act btn-act-edit edit-btn"
                                        data-kode="{{ $d->kode_cuti }}"
                                        data-nama="{{ $d->nama_cuti }}"
                                        data-jml="{{ $d->jml_hari }}"
                                        data-status="{{ $d->status }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modal-edit">
                                    <i class="mdi mdi-pencil"></i> Edit
                                </button>
                                <form action="{{ route('panel.cuti.destroy', $d->kode_cuti) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-act btn-act-del" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        <i class="mdi mdi-trash-can"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="tbl-empty">
                                <i class="mdi mdi-calendar-remove"></i>
                                <p>Tidak ada data cuti</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ── MODAL TAMBAH ── --}}
<div class="modal fade" id="modal-cuti" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-hd">
                <div class="modal-hd-title">
                    <i class="mdi mdi-calendar-plus"></i> Tambah Data Cuti
                </div>
                <button type="button" class="btn-modal-x" data-bs-dismiss="modal">
                    <i class="mdi mdi-close"></i>
                </button>
            </div>
            <form action="{{ route('panel.cuti.store') }}" method="POST">
                @csrf
                <div class="modal-bd">
                    <div class="m-group">
                        <label>Kode Cuti <span style="color:var(--red);">*</span></label>
                        <input type="text" name="kode_cuti" required maxlength="5" placeholder="Contoh: CT001">
                    </div>
                    <div class="m-group">
                        <label>Nama Cuti <span style="color:var(--red);">*</span></label>
                        <input type="text" name="nama_cuti" required placeholder="Contoh: Cuti Tahunan">
                    </div>
                    <div class="m-group">
                        <label>Jumlah Hari <span style="color:var(--red);">*</span></label>
                        <input type="number" name="jml_hari" required min="1" placeholder="Contoh: 12">
                    </div>
                    <div class="m-group">
                        <label>Status <span style="color:var(--red);">*</span></label>
                        <select name="status" required>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-foot">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-save-m">
                        <i class="mdi mdi-content-save"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ── MODAL EDIT ── --}}
<div class="modal fade" id="modal-edit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-hd">
                <div class="modal-hd-title">
                    <i class="mdi mdi-pencil"></i> Edit Data Cuti
                </div>
                <button type="button" class="btn-modal-x" data-bs-dismiss="modal">
                    <i class="mdi mdi-close"></i>
                </button>
            </div>
            <form action="" method="POST" id="form-edit">
                @csrf
                @method('PUT')
                <div class="modal-bd">
                    <div class="m-group">
                        <label>Kode Cuti</label>
                        <input type="text" name="kode_cuti" id="edit_kode" disabled>
                        <span style="font-size:11px; color:var(--slate-400); margin-top:2px;">Kode cuti tidak dapat diubah</span>
                    </div>
                    <div class="m-group">
                        <label>Nama Cuti <span style="color:var(--red);">*</span></label>
                        <input type="text" name="nama_cuti" id="edit_nama" required>
                    </div>
                    <div class="m-group">
                        <label>Jumlah Hari <span style="color:var(--red);">*</span></label>
                        <input type="number" name="jml_hari" id="edit_jml" required min="1">
                    </div>
                    <div class="m-group">
                        <label>Status <span style="color:var(--red);">*</span></label>
                        <select name="status" id="edit_status" required>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-foot">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-save-m">
                        <i class="mdi mdi-content-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editBtns = document.querySelectorAll('.edit-btn');
        const formEdit = document.getElementById('form-edit');
        
        editBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const kode = this.getAttribute('data-kode');
                document.getElementById('edit_kode').value = kode;
                document.getElementById('edit_nama').value = this.getAttribute('data-nama');
                document.getElementById('edit_jml').value = this.getAttribute('data-jml');
                document.getElementById('edit_status').value = this.getAttribute('data-status');
                
                formEdit.action = `/panel/cuti/${kode}`;
            });
        });
    });
</script>
@endsection
