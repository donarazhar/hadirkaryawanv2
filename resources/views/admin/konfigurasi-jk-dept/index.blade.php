@extends('admin.layouts.admin')

@section('title', 'Konfigurasi Jam Kerja Departemen')
@section('page-title', 'Konfigurasi Jam Kerja Departemen')

@section('content')
<!-- Search & Filter Card -->
<div class="card mb-3">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="mdi mdi-magnify"></i> Pencarian & Filter
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('panel.konfigurasi-jk-dept.index') }}" method="GET">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Cari Konfigurasi</label>
                        <input type="text" name="search" class="form-control"
                            placeholder="Cari kode konfigurasi..."
                            value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">Filter Cabang</label>
                        <select name="kode_cabang" class="form-select">
                            <option value="">Semua Cabang</option>
                            @foreach($cabang as $cbg)
                            <option value="{{ $cbg->kode_cabang }}" {{ request('kode_cabang') == $cbg->kode_cabang ? 'selected' : '' }}>
                                {{ $cbg->nama_cabang }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-magnify"></i> Cari
                            </button>
                            <a href="{{ route('panel.konfigurasi-jk-dept.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-refresh"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label">&nbsp;</label>
                        <a href="{{ route('panel.konfigurasi-jk-dept.create') }}" class="btn btn-success w-100">
                            <i class="mdi mdi-plus"></i> Tambah
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Data Table Card -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-vcenter card-table table-striped">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="12%">Kode</th>
                        <th width="18%">Cabang</th>
                        <th width="18%">Departemen</th>
                        <th width="10%" class="text-center">Hari Kerja</th>
                        <th width="22%">Detail Jam Kerja</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($konfigurasi as $index => $config)
                    <tr>
                        <td class="text-center">{{ $konfigurasi->firstItem() + $index }}</td>
                        <td>
                            <span class="badge bg-info">{{ $config->kode_jk_dept }}</span>
                        </td>
                        <td>
                            <i class="mdi mdi-office-building text-primary"></i>
                            <strong>{{ $config->cabang->nama_cabang ?? 'N/A' }}</strong>
                        </td>
                        <td>
                            <i class="mdi mdi-file-tree text-success"></i>
                            <strong>{{ $config->departemen->nama_dept ?? 'N/A' }}</strong>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-primary" style="font-size: 14px;">
                                {{ $config->details->count() }} hari
                            </span>
                        </td>
                        <td>
                            @if($config->details->count() > 0)
                            <div class="small">
                                @foreach($config->details->take(2) as $detail)
                                <div class="mb-2">
                                    <span class="badge bg-secondary me-1">{{ $detail->hari }}</span>
                                    @if($detail->jamKerja)
                                    @if($detail->jamKerja->tipe_jam_kerja == 'multi_shift')
                                    <!-- Multi Shift Badge -->
                                    <span class="badge bg-info">
                                        <i class="mdi mdi-layers"></i> {{ $detail->jamKerja->nama_jam_kerja }}
                                    </span>
                                    <small class="text-muted d-block mt-1">
                                        {{ $detail->jamKerja->total_shift }} shift:
                                        @foreach($detail->jamKerja->shifts->take(3) as $shift)
                                        {{ $shift->nama_shift }}{{ !$loop->last ? ', ' : '' }}
                                        @endforeach
                                        @if($detail->jamKerja->shifts->count() > 3)
                                        <span class="text-muted">+{{ $detail->jamKerja->shifts->count() - 3 }}</span>
                                        @endif
                                    </small>
                                    @else
                                    <!-- Regular Badge -->
                                    <span class="badge bg-warning text-dark">{{ $detail->jamKerja->nama_jam_kerja }}</span>
                                    <small class="text-muted">
                                        ({{ date('H:i', strtotime($detail->jamKerja->jam_masuk)) }} -
                                        {{ date('H:i', strtotime($detail->jamKerja->jam_pulang)) }})
                                    </small>
                                    @endif
                                    @else
                                    <span class="badge bg-secondary">N/A</span>
                                    @endif
                                </div>
                                @endforeach

                                @if($config->details->count() > 2)
                                <small class="text-muted">
                                    <i class="mdi mdi-dots-horizontal"></i>
                                    +{{ $config->details->count() - 2 }} hari lainnya
                                </small>
                                @endif
                            </div>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('panel.konfigurasi-jk-dept.show', $config->kode_jk_dept) }}"
                                    class="btn btn-sm btn-info" title="Detail">
                                    <i class="mdi mdi-eye"></i>
                                </a>
                                <a href="{{ route('panel.konfigurasi-jk-dept.edit', $config->kode_jk_dept) }}"
                                    class="btn btn-sm btn-warning" title="Edit">
                                    <i class="mdi mdi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger"
                                    onclick="confirmDelete('{{ $config->kode_jk_dept }}')" title="Hapus">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="mdi mdi-calendar-clock" style="font-size: 48px; color: #ccc;"></i>
                            <p class="text-muted mt-2">Tidak ada data konfigurasi jam kerja departemen</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination Footer - IMPROVED -->
    @if($konfigurasi->hasPages())
    <div class="card-footer">
        <div class="d-flex justify-content-between align-items-center">
            <!-- Showing Info -->
            <div class="text-muted">
                <small>
                    Menampilkan <strong>{{ $konfigurasi->firstItem() }}</strong>
                    sampai <strong>{{ $konfigurasi->lastItem() }}</strong>
                    dari <strong>{{ $konfigurasi->total() }}</strong> data
                </small>
            </div>

            <!-- Pagination Links -->
            <nav aria-label="Page navigation">
                {{ $konfigurasi->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
            </nav>
        </div>
    </div>
    @endif
</div>

<!-- Delete Form -->
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('styles')
<style>
    /* Pagination Improvements */
    .pagination {
        margin-bottom: 0;
    }

    .pagination .page-link {
        border-radius: 6px;
        margin: 0 3px;
        padding: 6px 12px;
        color: #0053C5;
        border: 1px solid #dee2e6;
        transition: all 0.3s;
    }

    .pagination .page-link:hover {
        background-color: #e3f2fd;
        border-color: #0053C5;
        color: #003d94;
    }

    .pagination .page-item.active .page-link {
        background-color: #0053C5;
        border-color: #0053C5;
        color: white;
        font-weight: 600;
    }

    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        background-color: #fff;
        border-color: #dee2e6;
        cursor: not-allowed;
    }

    /* Card Footer Styling */
    .card-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
        padding: 15px 20px;
    }

    /* Responsive Pagination */
    @media (max-width: 768px) {
        .card-footer .d-flex {
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }

        .pagination {
            justify-content: center;
        }

        .pagination .page-link {
            padding: 4px 8px;
            font-size: 12px;
            margin: 0 2px;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(kode) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: 'Apakah Anda yakin ingin menghapus konfigurasi ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
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

@endsection