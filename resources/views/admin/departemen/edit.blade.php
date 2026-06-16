@extends('admin.layouts.admin')

@section('title', 'Edit Data Departemen')
@section('page-title', 'Edit Data Departemen')

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
        grid-template-columns: 1fr 360px;
        gap: 20px;
        align-items: start;
    }
    @media (max-width: 992px) { .form-grid { grid-template-columns: 1fr; } }
    .side-col { display: flex; flex-direction: column; gap: 20px; position: sticky; top: 20px; }

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
    
    .form-control:disabled {
        background-color: var(--slate-50);
        color: var(--slate-500);
        border-color: var(--slate-200);
        cursor: not-allowed;
    }

    .is-invalid { border-color: var(--red) !important; background: var(--red-soft); }
    .invalid-feedback { display: block; font-size: 11.5px; color: var(--red); font-weight: 600; margin-top: 6px; }
    .form-hint { display: block; font-size: 11.5px; color: var(--slate-500); margin-top: 6px; font-weight: 500; }
    .form-hint i { font-size: 13px; margin-right: 2px; vertical-align: text-bottom; }

    /* Action Footer */
    .form-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 30px; padding-top: 20px; border-top: 1px solid var(--slate-100); }
    .btn-save {
        height: 42px; padding: 0 24px; border: none; border-radius: 9px; font-family: 'Inter', sans-serif;
        font-size: 13.5px; font-weight: 700; color: var(--white); background: var(--amber);
        cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: background 0.15s;
    }
    .btn-save:hover { background: #D97706; }
    .btn-save i { font-size: 18px; }

    /* ── STATS CARD ── */
    .stat-total {
        background: var(--blue-soft); border: 1px solid var(--blue-mid); border-radius: 10px;
        padding: 16px; display: flex; align-items: center; gap: 16px; margin-bottom: 20px;
    }
    .st-icon { width: 44px; height: 44px; border-radius: 10px; background: var(--white); color: var(--blue); display: flex; align-items: center; justify-content: center; font-size: 22px; }
    .st-label { font-size: 11.5px; font-weight: 700; color: var(--slate-600); text-transform: uppercase; margin-bottom: 2px; }
    .st-val { font-size: 22px; font-weight: 800; color: var(--slate-900); line-height: 1; }

    .karyawan-list {
        display: flex; flex-direction: column; gap: 0;
        max-height: 300px; overflow-y: auto;
        padding-right: 4px; /* for scrollbar */
    }
    .karyawan-list::-webkit-scrollbar { width: 4px; }
    .karyawan-list::-webkit-scrollbar-thumb { background: var(--slate-200); border-radius: 4px; }
    
    .kl-item {
        display: flex; align-items: center; gap: 12px; padding: 10px 0;
        border-bottom: 1px solid var(--slate-100);
    }
    .kl-item:last-child { border-bottom: none; }
    
    .kl-ava { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; }
    .kl-ava-init {
        width: 36px; height: 36px; border-radius: 50%; background: var(--slate-100);
        color: var(--slate-600); font-size: 13px; font-weight: 700;
        display: flex; align-items: center; justify-content: center;
    }
    .kl-info { flex: 1; min-width: 0; }
    .kl-name { font-size: 13px; font-weight: 700; color: var(--slate-900); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .kl-sub { font-size: 11px; color: var(--slate-500); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    
    .kl-more { font-size: 12px; font-weight: 600; color: var(--slate-400); text-align: center; padding: 12px 0 0; }

    /* ── ALERT CARD ── */
    .alert-box {
        display: flex; align-items: flex-start; gap: 12px; padding: 16px; border-radius: 10px;
    }
    .alert-box i { font-size: 20px; line-height: 1; }
    .alert-box-warning { background: var(--amber-soft); border: 1px solid #FDE68A; color: #B45309; }
    .alert-box-info { background: var(--blue-soft); border: 1px solid var(--blue-mid); color: var(--blue-dark); }
    .ab-text { font-size: 12.5px; font-weight: 500; line-height: 1.5; padding-top: 2px; }
    .ab-text strong { font-weight: 800; }
</style>
@endpush

@section('content')
<div class="form-wrap">

    {{-- HEADER --}}
    <div class="form-header">
        <div class="form-header-left">
            <div class="form-header-icon">
                <i class="mdi mdi-domain-edit"></i>
            </div>
            <div>
                <div class="form-header-title">Edit Data Departemen</div>
                <div class="form-header-sub">Perbarui informasi nama unit departemen</div>
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
            <form action="{{ route('panel.departemen.update', $departemen->kode_dept) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-head">
                        <i class="mdi mdi-text-box-edit-outline"></i>
                        <h3 class="card-title">Form Data Departemen</h3>
                    </div>
                    <div class="card-body">
                        
                        <div class="fg">
                            <label>Kode Departemen</label>
                            <input type="text" class="form-control" value="{{ $departemen->kode_dept }}" disabled>
                            <div class="form-hint"><i class="mdi mdi-lock-outline"></i> Kode departemen tidak dapat diubah setelah dibuat.</div>
                        </div>

                        <div class="fg">
                            <label>Nama Departemen <span class="req">*</span></label>
                            <input type="text" name="nama_dept" class="form-control @error('nama_dept') is-invalid @enderror" 
                                value="{{ old('nama_dept', $departemen->nama_dept) }}" maxlength="255" required>
                            @error('nama_dept')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-save">
                                <i class="mdi mdi-content-save-edit"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- RIGHT COLUMN: SIDEBAR (STATS & WARNINGS) --}}
        <div class="side-col">
            
            {{-- STATS CARD --}}
            <div class="card">
                <div class="card-head">
                    <i class="mdi mdi-chart-donut"></i>
                    <h3 class="card-title">Statistik Departemen</h3>
                </div>
                <div class="card-body">
                    <div class="stat-total">
                        <div class="st-icon"><i class="mdi mdi-account-group"></i></div>
                        <div>
                            <div class="st-label">Total Karyawan</div>
                            <div class="st-val">{{ $departemen->karyawan->count() }}</div>
                        </div>
                    </div>

                    @if($departemen->karyawan->count() > 0)
                        <div style="font-size:11px; font-weight:800; color:var(--slate-500); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:10px;">Daftar Karyawan (Top 10)</div>
                        <div class="karyawan-list">
                            @foreach($departemen->karyawan->take(10) as $karyawan)
                                <div class="kl-item">
                                    @if($karyawan->foto)
                                        <img src="{{ Storage::url('uploads/karyawan/'.$karyawan->foto) }}" class="kl-ava" alt="{{ $karyawan->nama_lengkap }}">
                                    @else
                                        <div class="kl-ava-init">{{ strtoupper(substr($karyawan->nama_lengkap, 0, 2)) }}</div>
                                    @endif
                                    <div class="kl-info">
                                        <div class="kl-name">{{ $karyawan->nama_lengkap }}</div>
                                        <div class="kl-sub">{{ $karyawan->nik }} • {{ $karyawan->jabatan }}</div>
                                    </div>
                                </div>
                            @endforeach

                            @if($departemen->karyawan->count() > 10)
                                <div class="kl-more">
                                    +{{ $departemen->karyawan->count() - 10 }} karyawan lainnya
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            {{-- ALERT CARD --}}
            <div class="card">
                <div class="card-head">
                    <i class="mdi mdi-alert-circle-outline" style="{{ $departemen->karyawan->count() > 0 ? 'color:var(--amber);' : 'color:var(--blue);' }}"></i>
                    <h3 class="card-title">Informasi Penghapusan</h3>
                </div>
                <div class="card-body">
                    @if($departemen->karyawan->count() > 0)
                        <div class="alert-box alert-box-warning">
                            <i class="mdi mdi-alert"></i>
                            <div class="ab-text">
                                Departemen ini memiliki <strong>{{ $departemen->karyawan->count() }} karyawan aktif</strong>. <br><br>Anda tidak dapat menghapus departemen ini sebelum memindahkan karyawan ke departemen lain.
                            </div>
                        </div>
                    @else
                        <div class="alert-box alert-box-info">
                            <i class="mdi mdi-information"></i>
                            <div class="ab-text">
                                Departemen ini belum memiliki karyawan. Anda dapat menghapus data ini dengan aman kapan saja.
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>

    </div>

</div>
@endsection