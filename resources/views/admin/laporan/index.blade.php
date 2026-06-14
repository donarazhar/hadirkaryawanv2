@extends('admin.layouts.admin')

@section('page-title', 'Manajemen Data Presensi')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Notifikasi -->
        @if (Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="mdi mdi-check-circle me-1"></i> {{ Session::get('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (Session::get('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="mdi mdi-alert me-1"></i> {{ Session::get('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title">Filter Data Presensi</h5>
            </div>
            <div class="card-body">
                <form action="/panel/laporan" method="GET">
                    <div class="row align-items-end">
                        <div class="col-md-3 mb-3 mb-md-0">
                            <label class="form-label">Bulan</label>
                            <select name="bulan" id="bulan" class="form-select">
                                <option value="">Semua Bulan</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                                        {{ $namabulan[$i] }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-2 mb-3 mb-md-0">
                            <label class="form-label">Tahun</label>
                            <select name="tahun" id="tahun" class="form-select">
                                @php
                                    $tahunMulai = 2022;
                                    $tahunSekarang = date('Y');
                                @endphp
                                @for ($t = $tahunMulai; $t <= $tahunSekarang; $t++)
                                    <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>
                                        {{ $t }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <label class="form-label">Cabang</label>
                            <select name="kode_cabang" id="kode_cabang" class="form-select">
                                <option value="">Semua Cabang</option>
                                @foreach ($cabang as $c)
                                    <option value="{{ $c->kode_cabang }}" {{ $kode_cabang == $c->kode_cabang ? 'selected' : '' }}>
                                        {{ $c->nama_cabang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <label class="form-label">Departemen</label>
                            <select name="kode_dept" id="kode_dept" class="form-select">
                                <option value="">Semua Departemen</option>
                                @foreach ($departemen as $d)
                                    <option value="{{ $d->kode_dept }}" {{ $kode_dept == $d->kode_dept ? 'selected' : '' }}>
                                        {{ $d->nama_dept }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="mdi mdi-magnify me-1"></i> Tampilkan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabel Data -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Data Kehadiran</h5>
                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal-create">
                    <i class="mdi mdi-plus-circle me-1"></i> Tambah Absensi Manual
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Karyawan</th>
                                <th>Jadwal / Shift</th>
                                <th>Jam Masuk</th>
                                <th>Jam Pulang</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($presensi) === 0)
                                <tr>
                                    <td colspan="8" class="text-center py-4">Tidak ada data presensi ditemukan.</td>
                                </tr>
                            @else
                                @foreach($presensi as $d)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ date('d-m-Y', strtotime($d->tgl_presensi)) }}</td>
                                        <td>
                                            <strong>{{ $d->nama_lengkap }}</strong><br>
                                            <small class="text-muted">{{ $d->nik }}</small>
                                        </td>
                                        <td>
                                            {{ $d->nama_jam_kerja }}
                                            @if($d->shift_ke)
                                                <span class="badge bg-info ms-1">Shift {{ $d->shift_ke }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $d->jam_in ?: '-' }}</td>
                                        <td>{{ $d->jam_out ?: '-' }}</td>
                                        <td>
                                            @if($d->status == 'h')
                                                <span class="badge bg-success">Hadir</span>
                                            @elseif($d->status == 'i')
                                                <span class="badge bg-warning text-dark">Izin</span>
                                            @elseif($d->status == 's')
                                                <span class="badge bg-primary">Sakit</span>
                                            @endif
                                        </td>
                                        <td>{{ $d->keterangan ?: '-' }}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-warning edit" id="{{ $d->id }}">
                                                <i class="mdi mdi-pencil"></i> Edit
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modal-edit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Presensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadeditform">
                <!-- Data Edit Form -->
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Manual -->
<div class="modal fade" id="modal-create" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Absensi Manual</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('panel.laporan.store') }}" method="POST">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Cabang (Filter)</label>
                            <select id="create_cabang" class="form-select filter-karyawan-create">
                                <option value="">Semua Cabang</option>
                                @foreach ($cabang as $c)
                                    <option value="{{ $c->kode_cabang }}">{{ $c->nama_cabang }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Departemen (Filter)</label>
                            <select id="create_dept" class="form-select filter-karyawan-create">
                                <option value="">Semua Departemen</option>
                                @foreach ($departemen as $d)
                                    <option value="{{ $d->kode_dept }}">{{ $d->nama_dept }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Karyawan</label>
                        <select name="nik" id="create_nik" class="form-select" required>
                            <option value="">Pilih Karyawan</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Presensi</label>
                        <input type="date" name="tgl_presensi" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jam Masuk (In)</label>
                            <input type="time" name="jam_in" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jam Pulang (Out)</label>
                            <input type="time" name="jam_out" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status Kehadiran</label>
                        <select name="status" class="form-select" required>
                            <option value="h">Hadir</option>
                            <option value="i">Izin</option>
                            <option value="s">Sakit</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan / Alasan</label>
                        <input type="text" name="keterangan" class="form-control" placeholder="Contoh: Mesin error, lupa absen">
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Presensi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.edit').click(function() {
            var id = $(this).attr('id');
            $.ajax({
                type: 'POST',
                url: '/panel/laporan/edit',
                cache: false,
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                success: function(respond) {
                    $('#loadeditform').html(respond);
                    $('#modal-edit').modal('show');
                }
            });
        });

        // Load Karyawan for Create form
        loadKaryawanCreate();

        $('.filter-karyawan-create').change(function() {
            loadKaryawanCreate();
        });

        function loadKaryawanCreate() {
            var kode_cabang = $('#create_cabang').val();
            var kode_dept = $('#create_dept').val();

            $.ajax({
                type: 'POST',
                url: '/panel/laporan/getkaryawan',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: kode_cabang,
                    kode_dept: kode_dept
                },
                cache: false,
                success: function(respond) {
                    $('#create_nik').html(respond);
                }
            });
        }
    });
</script>
@endpush
