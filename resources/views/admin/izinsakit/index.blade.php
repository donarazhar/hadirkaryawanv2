@extends('admin.layouts.admin')

@section('title', 'Data Izin & Sakit')
@section('page-title', 'Data Izin & Sakit')

@section('content')
<div class="page-header d-print-none mb-3">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Kelola Pengajuan</div>
                <h2 class="page-title">Data Izin & Sakit</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Alert Messages -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <div class="d-flex">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                </div>
                <div>{{ session('success') }}</div>
            </div>
            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            <div class="d-flex">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="12" y1="8" x2="12" y2="12" /><line x1="12" y1="16" x2="12.01" y2="16" /></svg>
                </div>
                <div>{{ session('error') }}</div>
            </div>
            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
        @endif

        <!-- Filter Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Filter Data</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('panel.izinsakit.index') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Dari Tanggal</label>
                            <input type="date" name="dari" class="form-control" value="{{ request('dari') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Sampai Tanggal</label>
                            <input type="date" name="sampai" class="form-control" value="{{ request('sampai') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status Approve</label>
                            <select name="status_approved" class="form-select">
                                <option value="">Semua</option>
                                <option value="0" {{ request('status_approved') === '0' ? 'selected' : '' }}>Menunggu</option>
                                <option value="1" {{ request('status_approved') === '1' ? 'selected' : '' }}>Disetujui</option>
                                <option value="2" {{ request('status_approved') === '2' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Pencarian</label>
                            <input type="text" name="nik_nama" class="form-control" placeholder="NIK / Nama Karyawan..." value="{{ request('nik_nama') }}">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="10" cy="10" r="7" /><line x1="21" y1="21" x2="15" y2="15" /></svg>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Data Table Card -->
        <div class="card mt-3">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-vcenter card-table table-striped table-mobile-md text-nowrap">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>NIK / Karyawan</th>
                                <th>Tipe</th>
                                <th>Keterangan</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($izinsakit as $index => $item)
                            <tr>
                                <td>{{ $izinsakit->firstItem() + $index }}</td>
                                <td>
                                    <strong>{{ date('d-m-Y', strtotime($item->tgl_izin_dari)) }}</strong>
                                    @if($item->tgl_izin_dari != $item->tgl_izin_sampai)
                                    <br><small class="text-muted">s/d {{ date('d-m-Y', strtotime($item->tgl_izin_sampai)) }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $item->karyawan->nama_lengkap ?? 'Unknown' }}</div>
                                    <small class="text-muted">{{ $item->nik }} | {{ $item->karyawan->departemen->nama_dept ?? '-' }}</small>
                                </td>
                                <td>
                                    @if($item->status == 'i')
                                        <span class="badge bg-info text-white">Izin</span>
                                    @elseif($item->status == 's')
                                        <span class="badge bg-warning text-white">Sakit</span>
                                    @elseif($item->status == 'c')
                                        <span class="badge bg-purple text-white">Cuti</span>
                                    @endif
                                </td>
                                <td style="max-width: 250px;">
                                    <div class="text-truncate" title="{{ $item->keterangan }}">
                                        {{ $item->keterangan }}
                                    </div>
                                    @if($item->doc_sid)
                                    <a href="{{ Storage::url('uploads/sid/'.$item->doc_sid) }}" target="_blank" class="badge bg-blue text-white mt-1 text-decoration-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-download" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><line x1="12" y1="11" x2="12" y2="17" /><polyline points="9 14 12 17 15 14" /></svg>
                                        Lihat SID
                                    </a>
                                    @endif
                                </td>
                                <td>
                                    @if($item->status_approved == '0')
                                        <span class="badge bg-warning text-white"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><polyline points="12 7 12 12 15 15" /></svg> Menunggu</span>
                                    @elseif($item->status_approved == '1')
                                        <span class="badge bg-success text-white"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg> Disetujui</span>
                                    @elseif($item->status_approved == '2')
                                        <span class="badge bg-danger text-white"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="18" y1="6" x2="6" y2="18" /><line x1="6" y1="6" x2="18" y2="18" /></svg> Ditolak</span>
                                    @endif

                                    @if($item->catatan_admin)
                                        <div class="mt-1">
                                            <small class="text-muted d-block"><b>Catatan:</b> {{ $item->catatan_admin }}</small>
                                        </div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($item->status_approved == '0')
                                    <button class="btn btn-sm btn-primary" onclick="openApprovalModal('{{ $item->kode_izin }}')">
                                        Action
                                    </button>
                                    @else
                                    <form action="{{ route('panel.izinsakit.cancel', $item->kode_izin) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin membatalkan status pengajuan ini?')">
                                            Batalkan
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <div class="fw-bold">Tidak ada data izin / sakit</div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($izinsakit->hasPages())
            <div class="card-footer d-flex align-items-center">
                {{ $izinsakit->links('pagination::bootstrap-5') }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Approval -->
<div class="modal modal-blur fade" id="modal-approve" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approval Izin / Sakit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" id="form-approve">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">Tindakan</label>
                        <select name="status_approved" class="form-select" required>
                            <option value="">Pilih Tindakan...</option>
                            <option value="1">Setujui Pengajuan</option>
                            <option value="2">Tolak Pengajuan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan Admin <small class="text-muted">(Opsional, disarankan jika menolak)</small></label>
                        <textarea name="catatan_admin" class="form-control" rows="3" placeholder="Masukkan alasan penolakan atau catatan tambahan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary ms-auto">
                        Simpan Keputusan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }
    .card-header {
        background: white;
        border-bottom: 1px solid #f0f0f0;
        padding: 20px;
    }
    .card-body {
        padding: 20px;
    }
    .alert {
        border-radius: 8px;
        border: none;
        padding: 15px 20px;
    }
    .alert-icon {
        width: 24px;
        height: 24px;
        margin-right: 12px;
    }
    .form-control, .form-select {
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 10px 15px;
    }
    .form-control:focus, .form-select:focus {
        border-color: #0053C5;
        box-shadow: 0 0 0 0.2rem rgba(0, 83, 197, 0.25);
    }
    .btn {
        border-radius: 6px;
        font-weight: 500;
    }
    .table-vcenter td {
        vertical-align: middle;
    }
    .text-truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>

@endsection

@push('scripts')
<script>
    function openApprovalModal(kode_izin) {
        var form = document.getElementById('form-approve');
        // Gunakan format route Laravel dengan replace string
        var url = "{{ route('panel.izinsakit.approve', ':kode') }}";
        url = url.replace(':kode', kode_izin);
        
        form.action = url;
        
        // Reset form
        form.reset();
        
        // Tampilkan modal
        var modal = new bootstrap.Modal(document.getElementById('modal-approve'));
        modal.show();
    }
</script>
@endpush
