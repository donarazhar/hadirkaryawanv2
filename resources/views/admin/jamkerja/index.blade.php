@extends('admin.layouts.admin')

@section('title', 'Data Jam Kerja')
@section('page-title', 'Data Jam Kerja')

@section('content')
<!-- Search & Filter Card -->
<div class="card mb-3">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="mdi mdi-magnify"></i> Pencarian & Filter
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('panel.jamkerja.index') }}" method="GET">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Cari Jam Kerja</label>
                        <input type="text" name="search" class="form-control"
                            placeholder="Cari kode atau nama..."
                            value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">Tipe Jam Kerja</label>
                        <select name="tipe" class="form-select">
                            <option value="">Semua Tipe</option>
                            <option value="regular" {{ request('tipe') == 'regular' ? 'selected' : '' }}>Regular</option>
                            <option value="multi_shift" {{ request('tipe') == 'multi_shift' ? 'selected' : '' }}>Multi Shift</option>
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
                            <a href="{{ route('panel.jamkerja.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-refresh"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label">&nbsp;</label>
                        <a href="{{ route('panel.jamkerja.create') }}" class="btn btn-success w-100">
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
                        <th width="18%">Nama Jam Kerja</th>
                        <th width="10%">Tipe</th>
                        <th width="30%">Jadwal / Shifts</th>
                        <th width="10%" class="text-center">Lintas Hari</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jamkerja as $index => $jk)
                    <tr>
                        <td class="text-center">{{ $jamkerja->firstItem() + $index }}</td>
                        <td>
                            <span class="badge bg-warning text-dark">{{ $jk->kode_jam_kerja }}</span>
                        </td>
                        <td>
                            <strong>{{ $jk->nama_jam_kerja }}</strong>
                        </td>
                        <td>
                            @if($jk->tipe_jam_kerja == 'multi_shift')
                            <span class="badge bg-info text-white">
                                <i class="mdi mdi-layers"></i> Multi Shift
                            </span>
                            <div class="mt-1">
                                <small class="text-muted">{{ $jk->total_shift }} shift/hari</small>
                            </div>
                            @else
                            <span class="badge bg-secondary">
                                <i class="mdi mdi-clock-outline"></i> Regular
                            </span>
                            @endif
                        </td>
                        <td>
                            @if($jk->tipe_jam_kerja == 'multi_shift' && $jk->shifts->count() > 0)
                            <!-- Multi Shift Display -->
                            <div class="shifts-list">
                                @foreach($jk->shifts->take(3) as $shift)
                                <div class="d-flex align-items-center mb-1">
                                    <span class="badge bg-primary me-2" style="min-width: 20px;">{{ $shift->shift_ke }}</span>
                                    <strong class="me-2">{{ $shift->nama_shift }}</strong>
                                    <small class="text-muted">
                                        {{ date('H:i', strtotime($shift->jam_masuk)) }} -
                                        {{ date('H:i', strtotime($shift->jam_pulang)) }}
                                    </small>
                                </div>
                                @endforeach

                                @if($jk->shifts->count() > 3)
                                <small class="text-muted">
                                    <i class="mdi mdi-dots-horizontal"></i>
                                    +{{ $jk->shifts->count() - 3 }} shift lainnya
                                </small>
                                @endif
                            </div>
                            @else
                            <!-- Regular Display -->
                            <div class="time-display">
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="text-muted">
                                        <i class="mdi mdi-clock-outline text-primary"></i> Awal:
                                    </small>
                                    <strong>{{ date('H:i', strtotime($jk->awal_jam_masuk)) }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="text-muted">
                                        <i class="mdi mdi-clock text-success"></i> Masuk:
                                    </small>
                                    <strong>{{ date('H:i', strtotime($jk->jam_masuk)) }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="text-muted">
                                        <i class="mdi mdi-clock-alert text-warning"></i> Akhir:
                                    </small>
                                    <strong>{{ date('H:i', strtotime($jk->akhir_jam_masuk)) }}</strong>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <small class="text-muted">
                                        <i class="mdi mdi-clock-end text-danger"></i> Pulang:
                                    </small>
                                    <strong>{{ date('H:i', strtotime($jk->jam_pulang)) }}</strong>
                                </div>
                            </div>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($jk->lintashari == '1')
                            <span class="badge bg-info">
                                <i class="mdi mdi-weather-night"></i> Ya
                            </span>
                            @else
                            <span class="badge bg-secondary">Tidak</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('panel.jamkerja.show', $jk->kode_jam_kerja) }}"
                                    class="btn btn-sm btn-info" title="Detail">
                                    <i class="mdi mdi-eye"></i>
                                </a>
                                <a href="{{ route('panel.jamkerja.edit', $jk->kode_jam_kerja) }}"
                                    class="btn btn-sm btn-warning" title="Edit">
                                    <i class="mdi mdi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger"
                                    onclick="confirmDelete('{{ $jk->kode_jam_kerja }}')" title="Hapus">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="mdi mdi-clock-outline" style="font-size: 48px; color: #ccc;"></i>
                            <p class="text-muted mt-2">Tidak ada data jam kerja</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($jamkerja->hasPages())
    <div class="card-footer d-flex align-items-center">
        <p class="m-0 text-muted">
            Menampilkan {{ $jamkerja->firstItem() }} sampai {{ $jamkerja->lastItem() }}
            dari {{ $jamkerja->total() }} data
        </p>
        <ul class="pagination m-0 ms-auto">
            @if ($jamkerja->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link">‹ Previous</span>
            </li>
            @else
            <li class="page-item">
                <a class="page-link" href="{{ $jamkerja->previousPageUrl() }}">‹ Previous</a>
            </li>
            @endif

            @foreach(range(1, $jamkerja->lastPage()) as $i)
            @if($i == $jamkerja->currentPage())
            <li class="page-item active">
                <span class="page-link">{{ $i }}</span>
            </li>
            @else
            <li class="page-item">
                <a class="page-link" href="{{ $jamkerja->url($i) }}">{{ $i }}</a>
            </li>
            @endif
            @endforeach

            @if ($jamkerja->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $jamkerja->nextPageUrl() }}">Next ›</a>
            </li>
            @else
            <li class="page-item disabled">
                <span class="page-link">Next ›</span>
            </li>
            @endif
        </ul>
    </div>
    @endif
</div>

<!-- Delete Form -->
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(kode) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: 'Apakah Anda yakin ingin menghapus jam kerja ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
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

@endsection