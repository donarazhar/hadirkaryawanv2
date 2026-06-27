@extends('admin.layouts.admin')

@section('title', 'Rekap Kehadiran')
@section('page-title', 'Rekap Kehadiran')

@push('styles')
<style>
    :root {
        --blue:       #2563EB;
        --blue-dark:  #1D4ED8;
        --blue-soft:  #EFF6FF;
        --blue-mid:   #BFDBFE;
        --green:      #10B981;
        --green-soft: #ECFDF5;
        --amber:      #F59E0B;
        --amber-soft: #FFFBEB;
        --red:        #EF4444;
        --red-soft:   #FEF2F2;
        --slate-900:  #111827;
        --slate-700:  #374151;
        --slate-600:  #4B5563;
        --slate-400:  #9CA3AF;
        --slate-200:  #E5E7EB;
        --slate-100:  #F3F4F6;
        --slate-50:   #F9FAFB;
        --white:      #FFFFFF;
        --shadow:     0 1px 3px rgba(0,0,0,0.06),0 1px 2px rgba(0,0,0,0.04);
        --radius:     14px;
        --radius-sm:  10px;
    }

    .rekap-wrap {
        display: grid;
        grid-template-columns: 360px 1fr;
        gap: 20px;
        align-items: start;
    }

    @media (max-width: 900px) {
        .rekap-wrap { grid-template-columns: 1fr; }
    }

    /* ── FORM CARD ── */
    .form-card {
        background: var(--white);
        border: 1px solid var(--slate-200);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
        position: sticky;
        top: 80px;
    }

    @media (max-width: 900px) {
        .form-card { position: static; }
    }

    .form-card-head {
        padding: 16px 20px;
        border-bottom: 1px solid var(--slate-100);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-card-icon {
        width: 36px; height: 36px;
        border-radius: 9px;
        background: var(--blue-soft);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .form-card-icon i { font-size: 18px; color: var(--blue); }

    .form-card-title { font-size: 14px; font-weight: 700; color: var(--slate-900); }
    .form-card-sub   { font-size: 11px; color: var(--slate-400); margin-top: 1px; }

    .form-card-body { padding: 20px; display: flex; flex-direction: column; gap: 16px; }

    .fgroup { display: flex; flex-direction: column; gap: 5px; }

    .fgroup label {
        font-size: 11px;
        font-weight: 700;
        color: var(--slate-600);
        text-transform: uppercase;
        letter-spacing: 0.4px;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .fgroup label .req { color: var(--red); }

    .fgroup select, .fgroup input {
        height: 40px;
        border: 1.5px solid var(--slate-200);
        border-radius: 9px;
        padding: 0 12px;
        font-family: 'Inter', sans-serif;
        font-size: 13px;
        color: var(--slate-900);
        background: var(--white);
        outline: none;
        transition: border-color 0.15s, box-shadow 0.15s;
        width: 100%;
        appearance: auto;
    }

    .fgroup select:focus, .fgroup input:focus {
        border-color: var(--blue);
        box-shadow: 0 0 0 3px rgba(37,99,235,0.10);
    }

    .filter-divider {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 10px;
        font-weight: 700;
        color: var(--slate-400);
        text-transform: uppercase;
        letter-spacing: 0.6px;
    }
    .filter-divider::before, .filter-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--slate-200);
    }

    .btn-cetak {
        height: 44px;
        width: 100%;
        border: none;
        border-radius: 10px;
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        font-weight: 700;
        color: var(--white);
        background: var(--blue);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: background 0.15s, transform 0.1s;
        box-shadow: 0 3px 10px rgba(37,99,235,0.25);
    }
    .btn-cetak:hover { background: var(--blue-dark); }
    .btn-cetak:active { transform: scale(0.98); }
    .btn-cetak i { font-size: 18px; }

    /* ── INFO CARD (right side) ── */
    .info-col { display: flex; flex-direction: column; gap: 14px; }

    .info-card {
        background: var(--white);
        border: 1px solid var(--slate-200);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 24px;
    }

    .info-card-icon-wrap {
        width: 56px; height: 56px;
        border-radius: 14px;
        background: var(--blue-soft);
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 16px;
    }
    .info-card-icon-wrap i { font-size: 28px; color: var(--blue); }

    .info-card-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--slate-900);
        margin-bottom: 6px;
        letter-spacing: -0.3px;
    }

    .info-card-desc {
        font-size: 13px;
        color: var(--slate-400);
        line-height: 1.6;
        margin-bottom: 20px;
    }

    /* Feature list */
    .feature-list { display: flex; flex-direction: column; gap: 10px; }

    .feature-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 12px;
        border-radius: 10px;
        background: var(--slate-50);
        border: 1px solid var(--slate-100);
        transition: background 0.15s;
    }
    .feature-item:hover { background: var(--slate-100); }

    .feature-icon {
        width: 32px; height: 32px;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .feature-icon i { font-size: 16px; }

    .feature-title { font-size: 13px; font-weight: 700; color: var(--slate-900); }
    .feature-desc  { font-size: 11px; color: var(--slate-400); margin-top: 2px; }

    /* Tips card */
    .tips-card {
        background: var(--blue-soft);
        border: 1px solid var(--blue-mid);
        border-radius: var(--radius);
        padding: 16px 18px;
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }
    .tips-card i { font-size: 20px; color: var(--blue); flex-shrink: 0; margin-top: 1px; }
    .tips-card-text { font-size: 12.5px; color: var(--blue-dark); line-height: 1.6; font-weight: 500; }
    .tips-card-text strong { font-weight: 800; }

    /* Responsive */
    @media (max-width: 640px) {
        .info-col { display: none; }
        .rekap-wrap { grid-template-columns: 1fr; }
    }

    @media (min-width: 641px) and (max-width: 900px) {
        .rekap-wrap { grid-template-columns: 1fr; }
        .info-col { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .info-card { display: none; }
        .tips-card { display: flex !important; }
    }
</style>
@endpush

@section('content')
<div class="rekap-wrap">

    {{-- ── LEFT: FORM ── --}}
    <div class="form-card">
        <div class="form-card-head">
            <div class="form-card-icon">
                <i class="mdi mdi-printer"></i>
            </div>
            <div>
                <div class="form-card-title">Cetak Rekap</div>
                <div class="form-card-sub">Generate laporan PDF per karyawan</div>
            </div>
        </div>

        <form action="{{ route('panel.rekap.cetak') }}" method="POST" target="_blank" id="formLaporan">
            @csrf
            <div class="form-card-body">

                {{-- Periode --}}
                <div class="filter-divider">Periode</div>

                <div class="fgroup">
                    <label>Bulan <span class="req">*</span></label>
                    <select name="bulan" id="bulan" required>
                        <option value="">Pilih Bulan</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ date('m') == $i ? 'selected' : '' }}>{{ $namabulan[$i] }}</option>
                        @endfor
                    </select>
                </div>

                <div class="fgroup">
                    <label>Tahun <span class="req">*</span></label>
                    <select name="tahun" id="tahun" required>
                        <option value="">Pilih Tahun</option>
                        @php $tahunMulai = 2022; $tahunSekarang = date('Y'); @endphp
                        @for($t = $tahunMulai; $t <= $tahunSekarang; $t++)
                            <option value="{{ $t }}" {{ date('Y') == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endfor
                    </select>
                </div>

                {{-- Filter --}}
                <div class="filter-divider">Filter Karyawan</div>

                <div class="fgroup">
                    <label>Cabang</label>
                    <select name="branch_id" id="branch_id" class="filter-karyawan">
                        <option value="">Semua Cabang</option>
                        @foreach($branches as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="fgroup">
                    <label>Unit</label>
                    <select name="unit_id" id="unit_id" class="filter-karyawan">
                        <option value="">Semua Unit</option>
                        @foreach($units as $u)
                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Karyawan --}}
                <div class="filter-divider">Karyawan</div>

                <div class="fgroup">
                    <label>Pilih Karyawan <span class="req">*</span></label>
                    <select name="nik" id="nik" required>
                        <option value="">Pilih Karyawan...</option>
                    </select>
                </div>

                <button type="submit" class="btn-cetak" id="btnCetak">
                    <i class="mdi mdi-file-pdf-box"></i>
                    Cetak Rekap PDF
                </button>

            </div>
        </form>
    </div>

    {{-- ── RIGHT: INFO ── --}}
    <div class="info-col">

        <div class="info-card">
            <div class="info-card-icon-wrap">
                <i class="mdi mdi-calendar-month"></i>
            </div>
            <div class="info-card-title">Rekap Kehadiran Karyawan</div>
            <div class="info-card-desc">
                Cetak laporan rekap bulanan kehadiran per karyawan dalam format PDF siap cetak, lengkap dengan ringkasan data presensi.
            </div>

            <div class="feature-list">
                <div class="feature-item">
                    <div class="feature-icon" style="background:var(--green-soft);">
                        <i class="mdi mdi-account-check" style="color:var(--green);"></i>
                    </div>
                    <div>
                        <div class="feature-title">Data Kehadiran Lengkap</div>
                        <div class="feature-desc">Jam masuk, jam pulang, dan status per hari</div>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon" style="background:var(--amber-soft);">
                        <i class="mdi mdi-clock-alert" style="color:var(--amber);"></i>
                    </div>
                    <div>
                        <div class="feature-title">Rekap Keterlambatan</div>
                        <div class="feature-desc">Terhitung otomatis berdasarkan jam kerja</div>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon" style="background:var(--purple-soft);">
                        <i class="mdi mdi-file-pdf-box" style="color:#8B5CF6;"></i>
                    </div>
                    <div>
                        <div class="feature-title">Format PDF Siap Cetak</div>
                        <div class="feature-desc">Layout profesional dengan kop dan tanda tangan</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tips-card">
            <i class="mdi mdi-information-outline"></i>
            <div class="tips-card-text">
                <strong>Cara penggunaan:</strong> Pilih periode bulan dan tahun, filter cabang/departemen untuk mempersempit daftar, kemudian pilih karyawan dan klik <strong>Cetak Rekap PDF</strong>. Laporan akan terbuka di tab baru.
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    // Load initial karyawan
    loadKaryawan();

    // Refresh when filter changes
    $('.filter-karyawan').change(function () {
        loadKaryawan();
    });

    function loadKaryawan() {
        $('#nik').html('<option value="ALL">Memuat...</option>');

        $.ajax({
            type: 'POST',
            url: '/panel/rekap/getkaryawan',
            data: {
                _token: "{{ csrf_token() }}",
                branch_id: $('#branch_id').val(),
                unit_id:   $('#unit_id').val()
            },
            cache: false,
            success: function (respond) {
                $('#nik').html(respond);
            },
            error: function () {
                $('#nik').html('<option value="ALL">Gagal memuat karyawan</option>');
            }
        });
    }

    // Validation
    $('#formLaporan').submit(function (e) {
        var nik   = $('#nik').val();
        var bulan = $('#bulan').val();
        var tahun = $('#tahun').val();

        if (!bulan || !tahun || !nik) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Lengkapi Form',
                text: 'Silakan pilih Bulan, Tahun, dan Karyawan terlebih dahulu.',
                confirmButtonColor: '#2563EB',
                confirmButtonText: 'Mengerti'
            });
        }
    });
});
</script>
@endpush
