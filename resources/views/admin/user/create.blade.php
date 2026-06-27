@extends('admin.layouts.admin')

@section('title', 'Tambah Pengguna (User)')
@section('page-title', 'Tambah Pengguna Baru')

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
        --cyan:       #06B6D4;
        --cyan-soft:  #ECFEFF;
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
        width: 46px; height: 46px; border-radius: 12px; background: var(--blue-soft);
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .form-header-icon i { font-size: 22px; color: var(--blue); }
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
    .side-col { display: flex; flex-direction: column; gap: 20px; position: sticky; top: 20px; }

    /* ── CARDS ── */
    .card { background: var(--white); border: 1px solid var(--slate-200); border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; margin-bottom: 20px; }
    .card-head { padding: 18px 24px; border-bottom: 1px solid var(--slate-100); display: flex; align-items: center; gap: 8px; justify-content: space-between; }
    .card-head-left { display: flex; align-items: center; gap: 8px; }
    .card-head i { font-size: 18px; color: var(--blue); }
    .card-title { font-size: 14px; font-weight: 800; color: var(--slate-900); m-0; }
    .card-body { padding: 24px; }

    /* ── FORM ELEMENTS ── */
    .fg-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
    @media (max-width: 640px) { .fg-row { grid-template-columns: 1fr; } }
    
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
    
    .is-invalid { border-color: var(--red) !important; background: var(--red-soft); }
    .invalid-feedback { display: block; font-size: 11.5px; color: var(--red); font-weight: 600; margin-top: 6px; }
    .form-hint { display: block; font-size: 11.5px; color: var(--slate-500); margin-top: 6px; font-weight: 500; line-height: 1.4; }

    /* Action Footer */
    .form-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--slate-100); }
    .btn-save {
        height: 42px; padding: 0 24px; border: none; border-radius: 9px; font-family: 'Inter', sans-serif;
        font-size: 13.5px; font-weight: 700; color: var(--white); background: var(--blue);
        cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: background 0.15s;
    }
    .btn-save:hover { background: var(--blue-dark); }
    .btn-save i { font-size: 18px; }

    /* ── INFO BLOCKS ── */
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
                <i class="mdi mdi-account-plus"></i>
            </div>
            <div>
                <div class="form-header-title">Tambah Pengguna Baru</div>
                <div class="form-header-sub">Buat akun untuk Superadmin, Admin Cabang, atau Pimpinan</div>
            </div>
        </div>
        <div>
            <a href="{{ route('panel.user.index') }}" class="btn-back">
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
            <form action="{{ route('panel.user.store') }}" method="POST" id="formUser">
                @csrf
                
                <div class="card">
                    <div class="card-head">
                        <div class="card-head-left">
                            <i class="mdi mdi-card-account-details-outline"></i>
                            <h3 class="card-title">Informasi Akun</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        
                        <div class="fg">
                            <label>Role Akses <span class="req">*</span></label>
                            <select name="role" id="role-select" class="form-select @error('role') is-invalid @enderror" required>
                                <option value="">-- Pilih Akses Role --</option>
                                <option value="superadmin" {{ old('role') == 'superadmin' ? 'selected' : '' }}>Superadmin (Akses Penuh)</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin Cabang</option>
                                <option value="pimpinan" {{ old('role') == 'pimpinan' ? 'selected' : '' }}>Pimpinan Departemen</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="fg" id="karyawan-container" style="display:none;">
                            <label>Pilih Karyawan <span class="req">*</span></label>
                            <select name="nik_karyawan" id="karyawan-select" class="form-select">
                                <option value="">-- Pilih Karyawan --</option>
                                @foreach($karyawan as $k)
                                    <option value="{{ $k->nik }}" data-nama="{{ $k->nama_lengkap }}" data-email="{{ $k->email }}">
                                        {{ $k->nik }} - {{ $k->nama_lengkap }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-hint"><i class="mdi mdi-information-outline"></i> Nama Lengkap dan Email akan terisi otomatis berdasarkan karyawan yang dipilih.</div>
                        </div>

                        <div class="fg-row" style="margin-bottom:20px;">
                            <div class="fg" id="cabang-container" style="display:none; margin-bottom:0;">
                                <label>Pilih Cabang <span class="req">*</span></label>
                                <select name="branch_id" id="cabang-select" class="form-select @error('branch_id') is-invalid @enderror">
                                    <option value="">-- Pilih Cabang --</option>
                                    @foreach($branches as $item)
                                        <option value="{{ $item->id }}" {{ old('branch_id') == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-hint"><i class="mdi mdi-information-outline"></i> Lokasi penugasan Admin/Pimpinan.</div>
                                @error('branch_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr style="border-color:var(--slate-200); margin:24px 0;">

                        <div class="fg">
                            <label>Nama Lengkap <span class="req">*</span></label>
                            <input type="text" id="name-input" name="name" class="form-control @error('name') is-invalid @enderror" 
                                value="{{ old('name') }}" placeholder="Contoh: Budi Santoso" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="fg-row" style="margin-bottom:0;">
                            <div class="fg" style="margin-bottom:0;">
                                <label>Alamat Email <span class="req">*</span></label>
                                <input type="email" id="email-input" name="email" class="form-control @error('email') is-invalid @enderror" 
                                    value="{{ old('email') }}" placeholder="Contoh: budi@perusahaan.com" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="fg" id="password-container" style="margin-bottom:0;">
                                <label>Password <span class="req">*</span></label>
                                <input type="password" id="password-input" name="password" class="form-control @error('password') is-invalid @enderror" 
                                    placeholder="Minimal 6 karakter" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>

                {{-- FOOTER ACTIONS --}}
                <div class="card" style="background:transparent; border:none; box-shadow:none;">
                    <div class="form-actions" style="margin-top:0; border-top:none; padding-top:0;">
                        <button type="submit" class="btn-save">
                            <i class="mdi mdi-content-save-check"></i> Simpan Pengguna
                        </button>
                    </div>
                </div>

            </form>
        </div>

        {{-- RIGHT COLUMN: INFO --}}
        <div class="side-col">
            <div class="card">
                <div class="card-head">
                    <h3 class="card-title" style="display:flex;align-items:center;gap:8px;">
                        <i class="mdi mdi-shield-check" style="color:var(--blue);font-size:18px;"></i>
                        Tingkat Akses Role
                    </h3>
                </div>
                <div class="card-body">
                    <ul class="info-list">
                        <li class="info-item">
                            <div class="ii-icon" style="color:var(--red);"><i class="mdi mdi-shield-crown"></i></div>
                            <div class="ii-text">
                                <strong>Superadmin</strong><br>
                                Memiliki kontrol penuh terhadap semua sistem, pengaturan, master data cabang, departemen, dan pengguna lain.
                            </div>
                        </li>
                        <li class="info-item">
                            <div class="ii-icon" style="color:var(--blue);"><i class="mdi mdi-shield-account"></i></div>
                            <div class="ii-text">
                                <strong>Admin Cabang</strong><br>
                                Dapat mengelola karyawan, presensi, jam kerja, lembur, serta memantau operasional hanya untuk cabang yang dipilih.
                            </div>
                        </li>
                        <li class="info-item">
                            <div class="ii-icon" style="color:var(--cyan);"><i class="mdi mdi-account-tie"></i></div>
                            <div class="ii-text">
                                <strong>Pimpinan</strong><br>
                                Digunakan untuk level Manajer/Penyelia. Hak akses berfokus pada menyetujui Cuti, Izin, Lembur, dan memantau staf di departemennya.
                            </div>
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
        const roleSelect = $('#role-select');
        const karyawanContainer = $('#karyawan-container');
        const karyawanSelect = $('#karyawan-select');
        const cabangContainer = $('#cabang-container');
        const cabangSelect = $('#cabang-select');
        const nameInput = $('#name-input');
        const emailInput = $('#email-input');
        const passwordContainer = document.getElementById('password-container');
        const passwordInput = document.getElementById('password-input');

        function toggleRole() {
            const role = roleSelect.val();
            
            if(role === 'admin' || role === 'pimpinan') {
                karyawanContainer.slideDown(200);
                passwordContainer.style.display = 'none';
                passwordInput.removeAttribute('required');
                passwordInput.value = '';
                
                if(role === 'admin' || role === 'pimpinan') {
                    cabangContainer.slideDown(200);
                    cabangSelect.prop('required', true);
                }
            } else {
                karyawanContainer.slideUp(200);
                karyawanSelect.val('');
                passwordContainer.style.display = 'block';
                passwordInput.setAttribute('required', 'required');
                
                cabangContainer.slideUp(200);
                cabangSelect.prop('required', false);
                cabangSelect.val('');
                
                // Clear auto-filled inputs for superadmin
                nameInput.val('');
                nameInput.prop('readonly', false);
                emailInput.val('');
                emailInput.prop('readonly', false);
            }
        }

        // Initialize state
        toggleRole();

        // Listen for change
        roleSelect.on('change', toggleRole);

        // Autofill dari Karyawan
        karyawanSelect.on('change', function() {
            const selected = $(this).find('option:selected');
            if (selected.val()) {
                nameInput.val(selected.data('nama'));
                
                if(selected.data('email')) {
                    emailInput.val(selected.data('email'));
                }
            }
        });
    });
</script>
@endpush
