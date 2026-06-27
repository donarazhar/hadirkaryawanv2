@extends('admin.layouts.admin')

@section('title', 'Edit Konfigurasi Jam Kerja Departemen')
@section('page-title', 'Edit Konfigurasi Jam Kerja Departemen')

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

    .form-wrap { display: flex; flex-direction: column; gap: 20px; }

    /* ── PAGE HEADER ── */
    .form-header {
        background: var(--white);
        border: 1px solid var(--slate-200);
        border-radius: var(--radius);
        padding: 20px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        box-shadow: var(--shadow);
    }
    .form-header-left { display: flex; align-items: center; gap: 14px; }
    .form-header-icon {
        width: 46px; height: 46px; border-radius: 12px; background: var(--amber-soft);
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .form-header-icon i { font-size: 22px; color: #D97706; }
    .form-header-title { font-size: 17px; font-weight: 800; color: var(--slate-900); letter-spacing: -0.2px; }
    .form-header-sub   { font-size: 12px; color: var(--slate-400); margin-top: 2px; }
    
    .btn-back {
        display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px;
        border: 1.5px solid var(--slate-200); border-radius: 9px; font-family: 'Inter', sans-serif;
        font-size: 13px; font-weight: 700; cursor: pointer; transition: all 0.15s;
        text-decoration: none; background: var(--white); color: var(--slate-700);
    }
    .btn-back i { font-size: 16px; }
    .btn-back:hover { background: var(--slate-50); color: var(--slate-900); border-color: var(--slate-300); }

    /* ── LAYOUT ── */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 20px;
        align-items: start;
    }
    @media (max-width: 992px) { .form-grid { grid-template-columns: 1fr; } }

    /* ── CARDS ── */
    .card { background: var(--white); border: 1px solid var(--slate-200); border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; margin-bottom: 20px; }
    .card-head { padding: 18px 24px; border-bottom: 1px solid var(--slate-100); display: flex; align-items: center; gap: 8px; justify-content: space-between; }
    .card-head-left { display: flex; align-items: center; gap: 8px; }
    .card-head i { font-size: 18px; color: var(--amber); }
    .card-title { font-size: 14px; font-weight: 800; color: var(--slate-900); m-0; }
    .card-body { padding: 24px; }

    /* ── FORM ELEMENTS ── */
    .fg-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 20px; }
    @media (max-width: 768px) { .fg-row { grid-template-columns: 1fr; } }
    
    .fg { margin-bottom: 20px; }
    .fg:last-child { margin-bottom: 0; }
    .fg label {
        display: block; font-size: 11.5px; font-weight: 700; color: var(--slate-700);
        margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;
    }
    .req { color: var(--red); }
    
    .form-control, .form-select {
        width: 100%; height: 42px; padding: 0 14px; border: 1.5px solid var(--slate-200);
        border-radius: 9px; font-family: 'Inter', sans-serif; font-size: 13.5px;
        color: var(--slate-900); background: var(--white); transition: all 0.15s; outline: none;
    }
    .form-control:focus, .form-select:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(37,99,235,0.10); }
    .form-control:disabled { background-color: var(--slate-50); color: var(--slate-500); cursor: not-allowed; font-family: monospace; font-weight: 700; }
    
    .is-invalid { border-color: var(--red) !important; background: var(--red-soft); }
    .invalid-feedback { display: block; font-size: 11.5px; color: var(--red); font-weight: 600; margin-top: 6px; }
    .form-hint { display: block; font-size: 11.5px; color: var(--slate-500); margin-top: 6px; font-weight: 500; line-height: 1.4; }

    /* Action Footer */
    .form-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--slate-100); }
    .btn-save {
        height: 42px; padding: 0 24px; border: none; border-radius: 9px; font-family: 'Inter', sans-serif;
        font-size: 13.5px; font-weight: 700; color: var(--white); background: var(--amber);
        cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: background 0.15s;
    }
    .btn-save:hover { background: #D97706; }

    /* ── DYNAMIC TABLE ── */
    .btn-add-day {
        height: 36px; padding: 0 16px; border: none; border-radius: 8px; font-family: 'Inter', sans-serif;
        font-size: 12.5px; font-weight: 700; color: var(--white); background: var(--amber);
        cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: background 0.15s;
    }
    .btn-add-day:hover { background: #D97706; }

    .dyn-table { width: 100%; border-collapse: separate; border-spacing: 0; border: 1px solid var(--slate-200); border-radius: 12px; overflow: hidden; }
    .dyn-table th { background: var(--slate-50); padding: 12px 16px; font-size: 11px; font-weight: 800; color: var(--slate-600); text-transform: uppercase; border-bottom: 1px solid var(--slate-200); }
    .dyn-table td { padding: 16px; vertical-align: top; border-bottom: 1px solid var(--slate-100); }
    .dyn-table tr:last-child td { border-bottom: none; }
    .row-number { font-weight: 800; color: var(--amber); background: var(--amber-soft); width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; font-size: 12px; }
    
    .btnHapusRow {
        width: 34px; height: 34px; border-radius: 8px; background: var(--white); border: 1px solid var(--red-soft);
        color: var(--red); display: inline-flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.15s;
    }
    .btnHapusRow:hover { background: var(--red-soft); border-color: #FECACA; }

    .info-box { background: var(--slate-50); border: 1px solid var(--slate-200); padding: 8px 12px; border-radius: 8px; font-size: 11.5px; line-height: 1.5; color: var(--slate-700); }

    /* ── INFO CARD ── */
    .info-card { position: sticky; top: 20px; }
    .info-list { margin: 0; padding: 0; list-style: none; display: flex; flex-direction: column; gap: 14px; }
    .info-item { display: flex; gap: 12px; }
    .ii-icon {
        width: 32px; height: 32px; border-radius: 8px; background: var(--slate-50);
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        border: 1px solid var(--slate-200); color: var(--slate-600); font-size: 16px;
    }
    .ii-text { font-size: 12.5px; color: var(--slate-600); line-height: 1.5; padding-top: 4px; }
    .ii-text strong { color: var(--slate-900); font-weight: 700; }
</style>
@endpush

@section('content')
<div class="form-wrap">

    {{-- HEADER --}}
    <div class="form-header">
        <div class="form-header-left">
            <div class="form-header-icon">
                <i class="mdi mdi-calendar-edit"></i>
            </div>
            <div>
                <div class="form-header-title">Edit Konfigurasi</div>
                <div class="form-header-sub">Perbarui jadwal hari dan jam kerja departemen</div>
            </div>
        </div>
        <div>
            <a href="{{ route('panel.konfigurasi-jk-unit.index') }}" class="btn-back">
                <i class="mdi mdi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    {{-- ALERTS (Error Summary) --}}
    @if($errors->any())
    <div class="card" style="border-color:var(--red); background:var(--red-soft); box-shadow:none;">
        <div class="card-body" style="padding:16px;">
            <div style="font-size:13px; font-weight:800; color:var(--red); margin-bottom:8px; display:flex; align-items:center; gap:6px;">
                <i class="mdi mdi-alert-circle" style="font-size:18px;"></i> Terdapat kesalahan pada input:
            </div>
            <ul style="margin:0; padding-left:24px; color:#991B1B; font-size:12.5px; font-weight:500;">
                @foreach($errors->all() as $error)
                    <li style="margin-bottom:2px;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <div class="form-grid">
        
        {{-- LEFT COLUMN: FORM --}}
        <div>
            <form action="{{ route('panel.konfigurasi-jk-unit.update', $konfigurasi->kode_jk_unit) }}" method="POST" id="formKonfigurasi">
                @csrf
                @method('PUT')
                
                {{-- CARD 1: Master Data --}}
                <div class="card">
                    <div class="card-head">
                        <div class="card-head-left">
                            <i class="mdi mdi-office-building-cog"></i>
                            <h3 class="card-title">Data Konfigurasi</h3>
                        </div>
                    </div>
                    <div class="card-body" style="padding-bottom:4px;">
                        <div class="fg-row">
                            <div class="fg">
                                <label>Kode Konfigurasi</label>
                                <input type="text" class="form-control" value="{{ $konfigurasi->kode_jk_unit }}" disabled>
                                <div class="form-hint"><i class="mdi mdi-lock-outline"></i> Tidak dapat diubah.</div>
                            </div>
                            <div class="fg">
                                <label>Cabang <span class="req">*</span></label>
                                <select name="branch_id" class="form-select @error('branch_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Cabang --</option>
                                    @foreach($branches as $cbg)
                                    <option value="{{ $cbg->id }}" {{ old('branch_id', $konfigurasi->branch_id) == $cbg->id ? 'selected' : '' }}>
                                        {{ $cbg->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('branch_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="fg">
                                <label>Unit <span class="req">*</span></label>
                                <select name="unit_id" class="form-select @error('unit_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Unit --</option>
                                    @foreach($units as $dept)
                                    <option value="{{ $dept->id }}" {{ old('unit_id', $konfigurasi->unit_id) == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('unit_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CARD 2: Daily Schedule --}}
                <div class="card">
                    <div class="card-head">
                        <div class="card-head-left">
                            <i class="mdi mdi-calendar-week"></i>
                            <h3 class="card-title">Jadwal Per Hari</h3>
                        </div>
                        <button type="button" class="btn-add-day" id="btnTambahHari">
                            <i class="mdi mdi-plus"></i> Tambah Hari
                        </button>
                    </div>
                    <div class="card-body">
                        
                        <div style="overflow-x:auto;">
                            <table class="dyn-table" id="tableJamKerja">
                                <thead>
                                    <tr>
                                        <th style="width:50px; text-align:center;">No</th>
                                        <th style="width:160px;">Hari <span class="req">*</span></th>
                                        <th>Pilih Jam Kerja <span class="req">*</span></th>
                                        <th style="width:60px; text-align:center;">Hapus</th>
                                    </tr>
                                </thead>
                                <tbody id="bodyJamKerja">
                                    @foreach($konfigurasi->details as $index => $detail)
                                    <tr>
                                        <td style="text-align:center;"><div class="row-number">{{ $index + 1 }}</div></td>
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
                                            <select name="kode_jam_kerja[]" class="form-select select-jam-kerja" required>
                                                <option value="">-- Pilih Jam Kerja --</option>
                                                @foreach($jamkerja as $jk)
                                                <option value="{{ $jk->kode_jam_kerja }}"
                                                    data-tipe="{{ $jk->tipe_jam_kerja }}"
                                                    data-total-shift="{{ $jk->total_shift }}"
                                                    data-shifts="{{ $jk->shifts->pluck('nama_shift')->implode(', ') }}"
                                                    {{ $detail->kode_jam_kerja == $jk->kode_jam_kerja ? 'selected' : '' }}>
                                                    @if($jk->tipe_jam_kerja == 'multi_shift')
                                                    &#128313; {{ $jk->nama_jam_kerja }} ({{ $jk->total_shift }} Shift)
                                                    @else
                                                    &#9200; {{ $jk->nama_jam_kerja }} ({{ date('H:i', strtotime($jk->jam_masuk)) }} - {{ date('H:i', strtotime($jk->jam_pulang)) }})
                                                    @endif
                                                </option>
                                                @endforeach
                                            </select>
                                            <div class="jam-kerja-info mt-2" style="display: none;">
                                                <div class="info-box info-content"></div>
                                            </div>
                                        </td>
                                        <td style="text-align:center;">
                                            <button type="button" class="btnHapusRow" title="Hapus Baris"><i class="mdi mdi-close"></i></button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

                {{-- FOOTER ACTIONS --}}
                <div class="card" style="background:transparent; border:none; box-shadow:none;">
                    <div class="form-actions" style="margin-top:0; border-top:none; padding-top:0;">
                        <button type="submit" class="btn-save">
                            <i class="mdi mdi-content-save-edit"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>

            </form>
        </div>

        {{-- RIGHT COLUMN: INFO --}}
        <div>
            <div class="card info-card">
                <div class="card-head">
                    <i class="mdi mdi-lightbulb-on-outline" style="color:var(--indigo);"></i>
                    <h3 class="card-title">Petunjuk Konfigurasi</h3>
                </div>
                <div class="card-body">
                    <ul class="info-list">
                        <li class="info-item">
                            <div class="ii-icon"><i class="mdi mdi-plus-box-outline text-indigo"></i></div>
                            <div class="ii-text">Gunakan tombol <strong>Tambah Hari</strong> untuk mengatur jam kerja pada hari tertentu (misal: Senin - Jumat).</div>
                        </li>
                        <li class="info-item">
                            <div class="ii-icon"><i class="mdi mdi-calendar-check text-green"></i></div>
                            <div class="ii-text">Maksimal Anda bisa menambahkan <strong>7 hari</strong> kerja untuk satu departemen.</div>
                        </li>
                        <li class="info-item">
                            <div class="ii-icon"><i class="mdi mdi-clock-outline text-blue"></i></div>
                            <div class="ii-text">Pilihan &#9200; berarti jadwal reguler, dan &#128313; berarti multi-shift. Jika Anda memilih jam kerja Multi Shift, pastikan sesuai dengan departemennya.</div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Template untuk row baru
        const jamKerjaOptions = `
            <option value="">-- Pilih Jam Kerja --</option>
            @foreach($jamkerja as $jk)
            <option value="{{ $jk->kode_jam_kerja }}"
                data-tipe="{{ $jk->tipe_jam_kerja }}"
                data-total-shift="{{ $jk->total_shift }}"
                data-shifts="{{ $jk->shifts->pluck('nama_shift')->implode(', ') }}">
                @if($jk->tipe_jam_kerja == 'multi_shift')
                    &#128313; {{ $jk->nama_jam_kerja }} ({{ $jk->total_shift }} Shift)
                @else
                    &#9200; {{ $jk->nama_jam_kerja }} ({{ date('H:i', strtotime($jk->jam_masuk)) }} - {{ date('H:i', strtotime($jk->jam_pulang)) }})
                @endif
            </option>
            @endforeach
        `;

        // Fungsi update urutan nomor
        function updateRowNumbers() {
            $('#bodyJamKerja tr').each(function(index) {
                $(this).find('.row-number').text(index + 1);
            });
        }

        // Fungsi show jam kerja detail
        function updateJamKerjaInfo(selectElement) {
            const selected = $(selectElement).find('option:selected');
            const tipe = selected.data('tipe');
            const infoDiv = $(selectElement).closest('td').find('.jam-kerja-info');
            const infoContent = infoDiv.find('.info-content');

            if (tipe === 'multi_shift') {
                const totalShift = selected.data('total-shift');
                const shifts = selected.data('shifts');
                infoContent.html(`
                    <strong style="color:var(--blue-dark);"><i class="mdi mdi-layers"></i> Tipe Multi Shift</strong><br>
                    Memiliki ${totalShift} jadwal shift dalam sehari:<br>
                    <span style="font-family:monospace;">${shifts}</span>
                `);
                infoDiv.fadeIn(200);
            } else if (tipe === 'regular') {
                infoContent.html(`
                    <strong style="color:var(--slate-700);"><i class="mdi mdi-clock-outline"></i> Tipe Regular</strong><br>
                    Jadwal kerja reguler 1x presensi masuk & pulang.
                `);
                infoDiv.fadeIn(200);
            } else {
                infoDiv.hide();
            }
        }

        // Event listener ganti jam kerja
        $(document).on('change', '.select-jam-kerja', function() {
            updateJamKerjaInfo(this);
        });

        // Init on load
        $('.select-jam-kerja').each(function() {
            updateJamKerjaInfo(this);
        });

        // Add Day Row
        $('#btnTambahHari').click(function() {
            const rowCount = $('#bodyJamKerja tr').length;

            if (rowCount >= 7) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Batas Maksimal',
                    text: 'Anda hanya dapat mengatur jadwal maksimal untuk 7 hari.'
                });
                return;
            }

            const newRow = `
                <tr style="display:none;">
                    <td style="text-align:center;"><div class="row-number">${rowCount + 1}</div></td>
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
                        <select name="kode_jam_kerja[]" class="form-select select-jam-kerja" required>
                            ${jamKerjaOptions}
                        </select>
                        <div class="jam-kerja-info mt-2" style="display: none;">
                            <div class="info-box info-content"></div>
                        </div>
                    </td>
                    <td style="text-align:center;">
                        <button type="button" class="btnHapusRow" title="Hapus Baris"><i class="mdi mdi-close"></i></button>
                    </td>
                </tr>
            `;

            $(newRow).appendTo('#bodyJamKerja').fadeIn(300);
        });

        // Hapus Row
        $(document).on('click', '.btnHapusRow', function() {
            $(this).closest('tr').fadeOut(200, function() {
                $(this).remove();
                updateRowNumbers();
            });
        });

        // Form Submit Validation
        $('#formKonfigurasi').submit(function(e) {
            const rowCount = $('#bodyJamKerja tr').length;

            if (rowCount === 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Jadwal Kosong',
                    text: 'Anda harus menambahkan minimal 1 hari kerja untuk konfigurasi ini.'
                });
                return false;
            }
        });
    });
</script>
@endpush
