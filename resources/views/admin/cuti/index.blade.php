@extends('admin.layouts.admin')

@section('title', 'Master Data Cuti')
@section('page-title', 'Master Data Cuti')

@section('content')
<div class="page-header d-print-none mb-3">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Kelola Master Data</div>
                <h2 class="page-title">Data Jenis Cuti</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-cuti">
                        <i class="mdi mdi-plus me-1"></i> Tambah Data
                    </a>
                    <a href="#" class="btn btn-primary d-sm-none btn-icon" data-bs-toggle="modal" data-bs-target="#modal-cuti" aria-label="Create new report">
                        <i class="mdi mdi-plus"></i>
                    </a>
                </div>
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
                <div>{{ session('success') }}</div>
            </div>
            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            <div class="d-flex">
                <div>{{ session('error') }}</div>
            </div>
            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
        @endif
        
        @if($errors->any())
        <div class="alert alert-danger alert-dismissible" role="alert">
            <div class="d-flex">
                <div>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
        @endif

        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-striped table-mobile-md text-nowrap">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Cuti</th>
                            <th>Nama Cuti</th>
                            <th>Jatah Hari</th>
                            <th>Status</th>
                            <th class="text-center w-1">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cuti as $d)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><span class="badge bg-blue-lt">{{ $d->kode_cuti }}</span></td>
                            <td>{{ $d->nama_cuti }}</td>
                            <td>{{ $d->jml_hari }} Hari</td>
                            <td>
                                @if($d->status == 'aktif')
                                <span class="badge bg-success">Aktif</span>
                                @else
                                <span class="badge bg-danger">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-sm btn-info edit-btn" data-kode="{{ $d->kode_cuti }}" data-nama="{{ $d->nama_cuti }}" data-jml="{{ $d->jml_hari }}" data-status="{{ $d->status }}" data-bs-toggle="modal" data-bs-target="#modal-edit">
                                        <i class="mdi mdi-pencil"></i>
                                    </button>
                                    <form action="{{ route('panel.cuti.destroy', $d->kode_cuti) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Tidak ada data cuti ditemukan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal modal-blur fade" id="modal-cuti" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Cuti</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('panel.cuti.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode Cuti</label>
                        <input type="text" class="form-control" name="kode_cuti" required maxlength="5" placeholder="CT001">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Cuti</label>
                        <input type="text" class="form-control" name="nama_cuti" required placeholder="Cuti Tahunan">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah Hari</label>
                        <input type="number" class="form-control" name="jml_hari" required min="1" placeholder="12">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status" required>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary ms-auto">
                        <i class="mdi mdi-content-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal modal-blur fade" id="modal-edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Cuti</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" id="form-edit">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode Cuti</label>
                        <input type="text" class="form-control" name="kode_cuti" id="edit_kode" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Cuti</label>
                        <input type="text" class="form-control" name="nama_cuti" id="edit_nama" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah Hari</label>
                        <input type="number" class="form-control" name="jml_hari" id="edit_jml" required min="1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status" id="edit_status" required>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary ms-auto">
                        <i class="mdi mdi-content-save me-1"></i> Simpan Perubahan
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
