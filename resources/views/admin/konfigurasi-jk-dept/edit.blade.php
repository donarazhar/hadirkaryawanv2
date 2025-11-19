@extends('admin.layouts.admin')

@section('title', 'Edit Konfigurasi Jam Kerja Departemen')
@section('page-title', 'Edit Konfigurasi Jam Kerja Departemen')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Form Edit Konfigurasi Jam Kerja Departemen</h5>
            </div>
            <div class="card-body">
                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <strong>Error!</strong> Terdapat kesalahan pada form:
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <form action="{{ route('panel.konfigurasi-jk-dept.update', $konfigurasi->kode_jk_dept) }}" method="POST" id="formKonfigurasi">
                    @csrf
                    @method('PUT')

                    <!-- Master Data -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="kode_jk_dept" class="form-label">Kode Konfigurasi</label>
                                <input type="text" class="form-control bg-light" id="kode_jk_dept" value="{{ $konfigurasi->kode_jk_dept }}" disabled>
                                <small class="text-muted">Kode konfigurasi tidak dapat diubah</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="kode_cabang" class="form-label">Cabang <span class="text-danger">*</span></label>
                                <select class="form-select @error('kode_cabang') is-invalid @enderror"
                                    id="kode_cabang" name="kode_cabang" required>
                                    <option value="">-- Pilih Cabang --</option>
                                    @foreach($cabang as $cbg)
                                    <option value="{{ $cbg->kode_cabang }}"
                                        {{ old('kode_cabang', $konfigurasi->kode_cabang) == $cbg->kode_cabang ? 'selected' : '' }}>
                                        {{ $cbg->nama_cabang }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('kode_cabang')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="kode_dept" class="form-label">Departemen <span class="text-danger">*</span></label>
                                <select class="form-select @error('kode_dept') is-invalid @enderror"
                                    id="kode_dept" name="kode_dept" required>
                                    <option value="">-- Pilih Departemen --</option>
                                    @foreach($departemen as $dept)
                                    <option value="{{ $dept->kode_dept }}"
                                        {{ old('kode_dept', $konfigurasi->kode_dept) == $dept->kode_dept ? 'selected' : '' }}>
                                        {{ $dept->nama_dept }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('kode_dept')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Detail Jam Kerja Per Hari -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">Konfigurasi Jam Kerja Per Hari</h6>
                        <button type="button" class="btn btn-success btn-sm" id="btnTambahHari">
                            <i class="mdi mdi-plus"></i> Tambah Hari
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered" id="tableJamKerja">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="35%">Hari <span class="text-danger">*</span></th>
                                    <th width="50%">Jam Kerja <span class="text-danger">*</span></th>
                                    <th width="10%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="bodyJamKerja">
                                @foreach($konfigurasi->details as $index => $detail)
                                <tr>
                                    <td class="text-center row-number">{{ $index + 1 }}</td>
                                    <td>
                                        <select name="hari[]" class="form-select" required>
                                            <option value="">-- Pilih Hari --</option>
                                            <option value="Senin" {{ $detail->hari == 'Senin' ? 'selected' : '' }}>Senin</option>
                                            <option value="Selasa" {{ $detail->hari == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                                            <option value="Rabu" {{ $detail->hari == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                                            <option value="Kamis" {{ $detail->hari == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                                            <option value="Jumat" {{ $detail->hari == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                                            <option value="Sabtu" {{ $detail->hari == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                                            <option value="Minggu" {{ $detail->hari == 'Minggu' ? 'selected' : '' }}>Minggu</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="kode_jam_kerja[]" class="form-select" required>
                                            <option value="">-- Pilih Jam Kerja --</option>
                                            @foreach($jamkerja as $jk)
                                            <option value="{{ $jk->kode_jam_kerja }}"
                                                {{ $detail->kode_jam_kerja == $jk->kode_jam_kerja ? 'selected' : '' }}>
                                                {{ $jk->nama_jam_kerja }} ({{ date('H:i', strtotime($jk->jam_masuk)) }} - {{ date('H:i', strtotime($jk->jam_pulang)) }})
                                            </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm btnHapusRow">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="alert alert-info">
                        <i class="mdi mdi-information-outline"></i>
                        <strong>Petunjuk:</strong> Klik tombol "Tambah Hari" untuk menambahkan konfigurasi jam kerja per hari. Anda dapat menambahkan 1-7 hari (Senin-Minggu).
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('panel.konfigurasi-jk-dept.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Template untuk row baru
        const jamKerjaOptions = `
            <option value="">-- Pilih Jam Kerja --</option>
            @foreach($jamkerja as $jk)
            <option value="{{ $jk->kode_jam_kerja }}">
                {{ $jk->nama_jam_kerja }} ({{ date('H:i', strtotime($jk->jam_masuk)) }} - {{ date('H:i', strtotime($jk->jam_pulang)) }})
            </option>
            @endforeach
        `;

        // Fungsi untuk update nomor urut
        function updateRowNumbers() {
            $('#bodyJamKerja tr').each(function(index) {
                $(this).find('.row-number').text(index + 1);
            });
        }

        // Tambah row baru
        $('#btnTambahHari').click(function() {
            const rowCount = $('#bodyJamKerja tr').length;

            if (rowCount >= 7) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Maksimal 7 Hari',
                    text: 'Anda hanya dapat menambahkan maksimal 7 hari (Senin-Minggu)'
                });
                return;
            }

            const newRow = `
                <tr>
                    <td class="text-center row-number">${rowCount + 1}</td>
                    <td>
                        <select name="hari[]" class="form-select" required>
                            <option value="">-- Pilih Hari --</option>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>
                            <option value="Minggu">Minggu</option>
                        </select>
                    </td>
                    <td>
                        <select name="kode_jam_kerja[]" class="form-select" required>
                            ${jamKerjaOptions}
                        </select>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm btnHapusRow">
                            <i class="mdi mdi-delete"></i>
                        </button>
                    </td>
                </tr>
            `;

            $('#bodyJamKerja').append(newRow);
        });

        // Hapus row
        $(document).on('click', '.btnHapusRow', function() {
            $(this).closest('tr').remove();
            updateRowNumbers();
        });

        // Validasi form sebelum submit
        $('#formKonfigurasi').submit(function(e) {
            const rowCount = $('#bodyJamKerja tr').length;

            if (rowCount === 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Data Tidak Lengkap',
                    text: 'Anda harus menambahkan minimal 1 konfigurasi jam kerja per hari'
                });
                return false;
            }
        });
    });
</script>
@endpush
@endsection