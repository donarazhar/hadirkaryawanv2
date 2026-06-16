@extends('admin.layouts.admin')

@section('title', 'Tambah Data Departemen')
@section('page-title', 'Tambah Data Departemen')

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
        width: 46px; height: 46px; border-radius: 12px; background: var(--green-soft);
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .form-header-icon i { font-size: 22px; color: var(--green); }
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
    .card { background: var(--white); border: 1px solid var(--slate-200); border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; }
    .card-head { padding: 18px 24px; border-bottom: 1px solid var(--slate-100); display: flex; align-items: center; gap: 8px; }
    .card-head i { font-size: 18px; color: var(--blue); }
    .card-title { font-size: 14px; font-weight: 800; color: var(--slate-900); m-0; }
    .card-body { padding: 24px; }

    /* ── FORM ELEMENTS ── */
    .fg { margin-bottom: 20px; }
    .fg:last-child { margin-bottom: 0; }
    .fg label {
        display: block; font-size: 11.5px; font-weight: 700; color: var(--slate-700);
        margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;
    }
    .req { color: var(--red); }
    
    .form-control {
        width: 100%; height: 42px; padding: 0 14px; border: 1.5px solid var(--slate-200);
        border-radius: 9px; font-family: 'Inter', sans-serif; font-size: 13.5px;
        color: var(--slate-900); background: var(--white); transition: all 0.15s; outline: none;
    }
    .form-control:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(37,99,235,0.10); }
    .form-control::placeholder { color: var(--slate-400); }
    
    .is-invalid { border-color: var(--red) !important; background: var(--red-soft); }
    .invalid-feedback { display: block; font-size: 11.5px; color: var(--red); font-weight: 600; margin-top: 6px; }
    .form-hint { display: block; font-size: 11.5px; color: var(--slate-500); margin-top: 6px; font-weight: 500; }
    .form-hint i { font-size: 13px; margin-right: 2px; vertical-align: text-bottom; }

    /* Action Footer */
    .form-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 30px; padding-top: 20px; border-top: 1px solid var(--slate-100); }
    .btn-save {
        height: 42px; padding: 0 24px; border: none; border-radius: 9px; font-family: 'Inter', sans-serif;
        font-size: 13.5px; font-weight: 700; color: var(--white); background: var(--blue);
        cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: background 0.15s;
    }
    .btn-save:hover { background: var(--blue-dark); }
    .btn-save i { font-size: 18px; }

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

    .example-box {
        margin-top: 20px; border-top: 1px solid var(--slate-100); padding-top: 16px;
    }
    .example-title { font-size: 12px; font-weight: 800; color: var(--slate-900); margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px; }
    
    .ex-table { width: 100%; border-collapse: collapse; }
    .ex-table td { padding: 8px 0; border-bottom: 1px dashed var(--slate-200); vertical-align: middle; }
    .ex-table tr:last-child td { border-bottom: none; }
    .ex-code { display: inline-block; background: var(--green-soft); color: var(--green); font-family: monospace; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 4px; border: 1px solid #A7F3D0; }
    .ex-desc { font-size: 12px; color: var(--slate-600); padding-left: 12px; }
</style>
@endpush

@section('content')
<div class="form-wrap">

    {{-- HEADER --}}
    <div class="form-header">
        <div class="form-header-left">
            <div class="form-header-icon">
                <i class="mdi mdi-domain-plus"></i>
            </div>
            <div>
                <div class="form-header-title">Tambah Departemen</div>
                <div class="form-header-sub">Buat unit departemen baru untuk struktur perusahaan</div>
            </div>
        </div>
        <div>
            <a href="{{ route('panel.departemen.index') }}" class="btn-back">
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
            <form action="{{ route('panel.departemen.store') }}" method="POST">
                @csrf
                <div class="card">
                    <div class="card-head">
                        <i class="mdi mdi-text-box-edit-outline"></i>
                        <h3 class="card-title">Form Data Departemen</h3>
                    </div>
                    <div class="card-body">
                        
                        <div class="fg">
                            <label>Kode Departemen <span class="req">*</span></label>
                            <input type="text" name="kode_dept" class="form-control @error('kode_dept') is-invalid @enderror" 
                                placeholder="Contoh: IT, HRD, FIN" value="{{ old('kode_dept') }}" maxlength="10" required>
                            @error('kode_dept')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-hint"><i class="mdi mdi-information-outline"></i> Maksimal 10 karakter, gunakan singkatan unik.</div>
                        </div>

                        <div class="fg">
                            <label>Nama Departemen <span class="req">*</span></label>
                            <input type="text" name="nama_dept" class="form-control @error('nama_dept') is-invalid @enderror" 
                                placeholder="Contoh: Information Technology" value="{{ old('nama_dept') }}" maxlength="255" required>
                            @error('nama_dept')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-save">
                                <i class="mdi mdi-content-save-check"></i> Simpan Departemen
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- RIGHT COLUMN: INFO --}}
        <div>
            <div class="card info-card">
                <div class="card-head">
                    <i class="mdi mdi-lightbulb-on-outline" style="color:var(--amber);"></i>
                    <h3 class="card-title">Petunjuk Pengisian</h3>
                </div>
                <div class="card-body">
                    <ul class="info-list">
                        <li class="info-item">
                            <div class="ii-icon"><i class="mdi mdi-barcode-scan"></i></div>
                            <div class="ii-text"><strong>Kode Departemen</strong> harus unik dan tidak boleh sama dengan yang sudah ada.</div>
                        </li>
                        <li class="info-item">
                            <div class="ii-icon"><i class="mdi mdi-card-account-details-outline"></i></div>
                            <div class="ii-text"><strong>Nama Departemen</strong> diisi dengan lengkap untuk memudahkan identifikasi.</div>
                        </li>
                    </ul>

                    <div class="example-box">
                        <div class="example-title"><i class="mdi mdi-format-list-checks"></i> Referensi Singkatan</div>
                        <table class="ex-table">
                            <tr>
                                <td><div class="ex-code">IT</div></td>
                                <td class="ex-desc">Information Technology</td>
                            </tr>
                            <tr>
                                <td><div class="ex-code">HRD</div></td>
                                <td class="ex-desc">Human Resources Dept</td>
                            </tr>
                            <tr>
                                <td><div class="ex-code">FIN</div></td>
                                <td class="ex-desc">Finance & Accounting</td>
                            </tr>
                            <tr>
                                <td><div class="ex-code">MKT</div></td>
                                <td class="ex-desc">Marketing & Sales</td>
                            </tr>
                            <tr>
                                <td><div class="ex-code">OPS</div></td>
                                <td class="ex-desc">Operations</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection