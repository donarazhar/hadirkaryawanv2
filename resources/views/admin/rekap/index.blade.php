@extends('admin.layouts.admin')

@section('page-title', 'Rekap Presensi Karyawan')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Form Laporan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('panel.rekap.cetak') }}" method="POST" target="_blank" id="formLaporan">
                    @csrf
                    <!-- Bulan -->
                    <div class="mb-3">
                        <label class="form-label">Bulan</label>
                        <select name="bulan" id="bulan" class="form-select" required>
                            <option value="">Pilih Bulan</option>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ date('m') == $i ? 'selected' : '' }}>
                                    {{ $namabulan[$i] }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <!-- Tahun -->
                    <div class="mb-3">
                        <label class="form-label">Tahun</label>
                        <select name="tahun" id="tahun" class="form-select" required>
                            <option value="">Pilih Tahun</option>
                            @php
                                $tahunMulai = 2022;
                                $tahunSekarang = date('Y');
                            @endphp
                            @for ($t = $tahunMulai; $t <= $tahunSekarang; $t++)
                                <option value="{{ $t }}" {{ date('Y') == $t ? 'selected' : '' }}>
                                    {{ $t }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <!-- Filter Cabang -->
                    <div class="mb-3">
                        <label class="form-label">Filter Cabang (Opsional)</label>
                        <select name="kode_cabang" id="kode_cabang" class="form-select filter-karyawan">
                            <option value="">Semua Cabang</option>
                            @foreach ($cabang as $c)
                                <option value="{{ $c->kode_cabang }}">{{ $c->nama_cabang }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Departemen -->
                    <div class="mb-3">
                        <label class="form-label">Filter Departemen (Opsional)</label>
                        <select name="kode_dept" id="kode_dept" class="form-select filter-karyawan">
                            <option value="">Semua Departemen</option>
                            @foreach ($departemen as $d)
                                <option value="{{ $d->kode_dept }}">{{ $d->nama_dept }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Karyawan -->
                    <div class="mb-4">
                        <label class="form-label">Pilih Karyawan</label>
                        <select name="nik" id="nik" class="form-select" required>
                            <option value="">Pilih Karyawan</option>
                        </select>
                    </div>

                    <!-- Submit -->
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="mdi mdi-printer me-1"></i> Cetak Laporan (PDF)
                            </button>
                        </div>
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
        // Load initial data karyawan
        loadKaryawan();

        // Ketika filter cabang atau departemen berubah
        $('.filter-karyawan').change(function() {
            loadKaryawan();
        });

        function loadKaryawan() {
            var kode_cabang = $('#kode_cabang').val();
            var kode_dept = $('#kode_dept').val();

            $.ajax({
                type: 'POST',
                url: '/panel/rekap/getkaryawan',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: kode_cabang,
                    kode_dept: kode_dept
                },
                cache: false,
                success: function(respond) {
                    $('#nik').html(respond);
                }
            });
        }

        // Form Submit Validation
        $('#formLaporan').submit(function(e) {
            var nik = $('#nik').val();
            if (nik == "") {
                Swal.fire({
                    title: 'Peringatan!',
                    text: 'Silakan pilih Karyawan terlebih dahulu',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                });
                return false;
            }
        });
    });
</script>
@endpush
