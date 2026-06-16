@extends('karyawan.layouts.presensi')

@section('content')

<style>

    :root {
        --primary:       #2563EB;
        --primary-soft:  #EFF6FF;
        --primary-mid:   #BFDBFE;
        --success:       #10B981;
        --success-soft:  #ECFDF5;
        --danger:        #EF4444;
        --danger-soft:   #FEF2F2;
        --warning:       #F59E0B;
        --text-900:      #111827;
        --text-600:      #4B5563;
        --text-400:      #9CA3AF;
        --border:        #F1F5F9;
        --border-med:    #E2E8F0;
        --surface:       #FFFFFF;
        --bg:            #F8FAFC;
        --radius-sm:     10px;
        --radius-md:     14px;
        --radius-lg:     18px;
        --radius-full:   9999px;
        --shadow-sm:     0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
        --shadow-md:     0 4px 12px rgba(0,0,0,0.08);
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: 'Inter', -apple-system, sans-serif;
        background: var(--bg);
        color: var(--text-900);
        -webkit-font-smoothing: antialiased;
    }

    /* ── STICKY HEADER ── */
    .pg-header {
        background: var(--surface);
        border-bottom: 1px solid var(--border);
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        position: sticky;
        top: 0;
        z-index: 50;
    }

    .btn-back {
        width: 36px; height: 36px;
        background: var(--bg);
        border: 1px solid var(--border-med);
        border-radius: var(--radius-sm);
        display: flex; align-items: center; justify-content: center;
        text-decoration: none;
        flex-shrink: 0;
        transition: background 0.2s;
        -webkit-tap-highlight-color: transparent;
    }

    .btn-back:active { background: var(--border-med); }
    .btn-back ion-icon { font-size: 20px; color: var(--text-600); }

    .pg-title {
        font-size: 17px;
        font-weight: 700;
        color: var(--text-900);
        line-height: 1.2;
    }

    .pg-sub {
        font-size: 11px;
        font-weight: 500;
        color: var(--primary);
        display: block;
        margin-top: 1px;
    }

    /* ── PAGE BODY ── */
    .pg-body {
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        padding-bottom: 110px;
    }

    /* ── PROFILE HERO CARD ── */
    .profile-hero {
        background: var(--surface);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        padding: 24px 16px 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    /* Subtle top accent band */
    .profile-hero::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 52px;
        background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
    }

    /* Avatar wrapper */
    .avatar-wrap {
        position: relative;
        width: 88px; height: 88px;
        margin-bottom: 12px;
        z-index: 1;
    }

    .avatar-img {
        width: 88px; height: 88px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--surface);
        box-shadow: 0 4px 12px rgba(37,99,235,0.18);
        display: block;
        background: #EFF6FF;
    }

    .avatar-btn {
        position: absolute;
        bottom: 0; right: 0;
        width: 28px; height: 28px;
        background: var(--primary);
        border-radius: 50%;
        border: 2px solid var(--surface);
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(37,99,235,0.35);
        transition: transform 0.2s;
        -webkit-tap-highlight-color: transparent;
    }

    .avatar-btn:active { transform: scale(0.9); }
    .avatar-btn ion-icon { font-size: 14px; color: white; }

    /* Name & NIK */
    .hero-name {
        font-size: 18px;
        font-weight: 800;
        color: var(--text-900);
        margin-bottom: 3px;
    }

    .hero-nik {
        font-size: 12px;
        font-weight: 500;
        color: var(--text-400);
        font-family: monospace;
    }

    /* Info chips row */
    .hero-chips {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 6px;
        margin-top: 14px;
        padding-top: 14px;
        border-top: 1px solid var(--border);
        width: 100%;
    }

    .hero-chip {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 8px 12px;
        background: var(--bg);
        border: 1px solid var(--border-med);
        border-radius: var(--radius-sm);
        flex: 1;
        min-width: 70px;
    }

    .chip-lbl {
        font-size: 10px;
        font-weight: 600;
        color: var(--text-400);
        text-transform: uppercase;
        letter-spacing: 0.4px;
        margin-bottom: 3px;
    }

    .chip-val {
        font-size: 12px;
        font-weight: 700;
        color: var(--text-900);
        text-align: center;
        line-height: 1.3;
    }

    /* ── ALERTS ── */
    .alert-box {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 12px 14px;
        border-radius: var(--radius-md);
        animation: fadeSlide 0.3s ease;
    }

    .alert-box.success { background: var(--success-soft); border: 1px solid #A7F3D0; }
    .alert-box.error   { background: var(--danger-soft);  border: 1px solid #FECACA; }

    .alert-box ion-icon { font-size: 18px; flex-shrink: 0; margin-top: 1px; }
    .alert-box.success ion-icon { color: var(--success); }
    .alert-box.error   ion-icon { color: var(--danger); }

    .alert-body {
        flex: 1;
        font-size: 13px;
        line-height: 1.5;
    }

    .alert-body strong { display: block; margin-bottom: 3px; }
    .alert-body.success strong { color: #065F46; }
    .alert-body.error   strong { color: #991B1B; }
    .alert-body ul { padding-left: 16px; margin-top: 4px; }
    .alert-body ul li { font-size: 12px; color: #991B1B; }

    /* ── FORM CARD ── */
    .form-card {
        background: var(--surface);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    .card-head {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 13px 16px;
        border-bottom: 1px solid var(--border);
        background: var(--surface);
    }

    .card-head-icon {
        width: 28px; height: 28px;
        border-radius: 8px;
        background: var(--primary-soft);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }

    .card-head-icon ion-icon { font-size: 14px; color: var(--primary); }

    .card-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--text-900);
    }

    /* ── FORM FIELDS ── */
    .form-body {
        padding: 14px 16px;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .form-group { display: flex; flex-direction: column; gap: 6px; }

    .form-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--text-600);
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .form-label ion-icon { font-size: 14px; color: var(--primary); }

    /* Input wrapper for icon + eye toggle */
    .input-wrap {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-left-icon {
        position: absolute;
        left: 12px;
        font-size: 16px;
        color: var(--text-400);
        pointer-events: none;
        z-index: 1;
    }

    .form-control {
        width: 100%;
        padding: 11px 14px 11px 38px;
        border: 1.5px solid var(--border-med);
        border-radius: var(--radius-sm);
        font-family: 'Inter', sans-serif;
        font-size: 13px;
        font-weight: 500;
        color: var(--text-900);
        background: var(--surface);
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        -webkit-appearance: none;
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37,99,235,0.10);
    }

    .form-control.no-icon { padding-left: 14px; }

    /* Eye toggle for password */
    .eye-toggle {
        position: absolute;
        right: 12px;
        background: none;
        border: none;
        cursor: pointer;
        padding: 4px;
        display: flex; align-items: center;
        color: var(--text-400);
        -webkit-tap-highlight-color: transparent;
    }

    .eye-toggle ion-icon { font-size: 18px; }

    /* Hint */
    .form-hint {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        color: var(--text-400);
    }

    .form-hint ion-icon { font-size: 13px; color: var(--primary); }

    /* ── PHOTO CHANGE ROW (inside card) ── */
    .photo-change-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        border-bottom: 1px solid var(--border);
    }

    .photo-mini {
        width: 52px; height: 52px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--border-med);
        flex-shrink: 0;
    }

    .photo-change-text {
        flex: 1;
        min-width: 0;
    }

    .photo-change-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--text-900);
        margin-bottom: 2px;
    }

    .photo-change-sub {
        font-size: 11px;
        color: var(--text-400);
    }

    .btn-photo-change {
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 7px 12px;
        background: var(--primary-soft);
        color: var(--primary);
        border: 1px solid var(--primary-mid);
        border-radius: var(--radius-sm);
        font-family: 'Inter', sans-serif;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        flex-shrink: 0;
        -webkit-tap-highlight-color: transparent;
        transition: background 0.2s;
    }

    .btn-photo-change:active { background: var(--primary-mid); }
    .btn-photo-change ion-icon { font-size: 15px; }

    /* ── SUBMIT BUTTON ── */
    .btn-submit {
        width: 100%;
        padding: 14px;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: var(--radius-lg);
        font-family: 'Inter', sans-serif;
        font-size: 15px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        box-shadow: 0 4px 14px rgba(37,99,235,0.28);
        transition: opacity 0.2s, transform 0.15s;
        -webkit-tap-highlight-color: transparent;
    }

    .btn-submit ion-icon { font-size: 18px; }
    .btn-submit:active   { opacity: 0.88; transform: scale(0.98); }
    .btn-submit:disabled { opacity: 0.55; cursor: not-allowed; transform: none; }

    /* ── DELETE PHOTO BUTTON ── */
    .btn-delete-photo {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 13px;
        background: var(--surface);
        color: var(--danger);
        border: 1.5px solid #FECACA;
        border-radius: var(--radius-lg);
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        box-shadow: var(--shadow-sm);
        transition: background 0.2s, transform 0.15s;
        -webkit-tap-highlight-color: transparent;
    }

    .btn-delete-photo ion-icon { font-size: 17px; }
    .btn-delete-photo:active { background: var(--danger-soft); transform: scale(0.98); }

    /* ── PHOTO CHANGED TOAST ── */
    .photo-toast {
        display: none;
        align-items: center;
        gap: 6px;
        padding: 8px 12px;
        background: var(--success-soft);
        border: 1px solid #A7F3D0;
        border-radius: var(--radius-sm);
        font-size: 12px;
        font-weight: 600;
        color: var(--success);
        margin-top: 8px;
        animation: fadeSlide 0.25s ease;
    }

    .photo-toast.show { display: flex; }
    .photo-toast ion-icon { font-size: 15px; }

    /* ── Animations ── */
    @keyframes fadeSlide {
        from { opacity: 0; transform: translateY(-5px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .pg-body > * {
        animation: fadeSlide 0.28s ease both;
    }
    .pg-body > *:nth-child(1) { animation-delay: 0.04s; }
    .pg-body > *:nth-child(2) { animation-delay: 0.07s; }
    .pg-body > *:nth-child(3) { animation-delay: 0.10s; }
    .pg-body > *:nth-child(4) { animation-delay: 0.13s; }
    .pg-body > *:nth-child(5) { animation-delay: 0.16s; }
    .pg-body > *:nth-child(6) { animation-delay: 0.19s; }

    @media (max-width: 360px) {
        .hero-chips { gap: 5px; }
        .hero-chip  { padding: 7px 8px; }
    }
</style>

{{-- ── STICKY HEADER ── --}}
<div class="pg-header">
    <a href="{{ route('dashboard') }}" class="btn-back">
        <ion-icon name="chevron-back-outline"></ion-icon>
    </a>
    <div>
        <div class="pg-title">Edit Profil</div>
        <span class="pg-sub">Kelola informasi akun Anda</span>
    </div>
</div>

{{-- ── PAGE BODY ── --}}
<div class="pg-body">

    {{-- Profile Hero Card --}}
    <div class="profile-hero">
        <div class="avatar-wrap">
            @if(!empty($karyawan->foto))
                @php $photoUrl = url(Storage::url('uploads/karyawan/' . $karyawan->foto)); @endphp
                <img src="{{ $photoUrl }}" class="avatar-img" id="photo-preview" alt="Foto Profil">
            @else
                <img src="https://ui-avatars.com/api/?name={{ urlencode($karyawan->nama_lengkap) }}&background=2563EB&color=fff&size=176&bold=true"
                     class="avatar-img" id="photo-preview" alt="Foto Profil">
            @endif

            <label for="foto-input" class="avatar-btn" title="Ganti Foto">
                <ion-icon name="camera-outline"></ion-icon>
            </label>

            <input type="file" id="foto-input" name="foto_preview" accept="image/*"
                   style="display:none;" onchange="handlePhotoChange(event)">
        </div>

        <div class="hero-name">{{ $karyawan->nama_lengkap }}</div>
        <div class="hero-nik">NIK: {{ $karyawan->nik }}</div>

        <div class="photo-toast" id="photo-toast">
            <ion-icon name="checkmark-circle-outline"></ion-icon>
            Foto siap diupload — klik Simpan
        </div>

        <div class="hero-chips">
            <div class="hero-chip">
                <div class="chip-lbl">Jabatan</div>
                <div class="chip-val">{{ $karyawan->jabatan ?? '—' }}</div>
            </div>
            <div class="hero-chip">
                <div class="chip-lbl">Departemen</div>
                <div class="chip-val">{{ $karyawan->nama_dept ?? '—' }}</div>
            </div>
            <div class="hero-chip">
                <div class="chip-lbl">Cabang</div>
                <div class="chip-val">{{ $karyawan->nama_cabang ?? '—' }}</div>
            </div>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
    <div class="alert-box success">
        <ion-icon name="checkmark-circle-outline"></ion-icon>
        <div class="alert-body success">
            <strong>Berhasil!</strong>
            {{ session('success') }}
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="alert-box error">
        <ion-icon name="alert-circle-outline"></ion-icon>
        <div class="alert-body error">
            <strong>Gagal!</strong>
            {{ session('error') }}
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="alert-box error">
        <ion-icon name="alert-circle-outline"></ion-icon>
        <div class="alert-body error">
            <strong>Periksa kembali form Anda:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    {{-- Edit Form --}}
    <form action="/updateprofile" method="POST" enctype="multipart/form-data" id="form-update">
        @csrf

        {{-- Hidden foto field (will be filled via JS) --}}
        <input type="file" name="foto" id="foto-hidden" accept="image/*" style="display:none;">

        {{-- Foto Section inside form card --}}
        <div class="form-card" style="margin-bottom: 12px;">
            <div class="card-head">
                <div class="card-head-icon"><ion-icon name="image-outline"></ion-icon></div>
                <div class="card-title">Foto Profil</div>
            </div>
            <div class="photo-change-row">
                @if(!empty($karyawan->foto))
                    <img src="{{ url(Storage::url('uploads/karyawan/' . $karyawan->foto)) }}"
                         class="photo-mini" id="photo-mini" alt="Foto">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($karyawan->nama_lengkap) }}&background=2563EB&color=fff&size=104&bold=true"
                         class="photo-mini" id="photo-mini" alt="Foto">
                @endif
                <div class="photo-change-text">
                    <div class="photo-change-title">Ganti foto profil</div>
                    <div class="photo-change-sub">JPG, PNG — maks. 2MB</div>
                </div>
                <label for="foto-input" class="btn-photo-change">
                    <ion-icon name="camera-outline"></ion-icon>
                    Pilih
                </label>
            </div>
        </div>

        {{-- FaceID --}}
        <div class="form-card" style="margin-bottom: 12px; cursor: pointer;" onclick="window.location.href='{{ route('face.enrollment') }}'">
            <div class="card-head">
                <div class="card-head-icon" style="background: var(--info-soft);"><ion-icon name="scan-outline" style="color: var(--info);"></ion-icon></div>
                <div class="card-title" style="flex: 1;">FaceID / Verifikasi Wajah</div>
                <ion-icon name="chevron-forward-outline" style="color: var(--text-400); font-size: 18px;"></ion-icon>
            </div>
            <div class="form-body" style="padding: 10px 16px;">
                <div class="form-hint" style="color: var(--text-600); font-size: 12px;">
                    Daftar atau perbarui data wajah Anda untuk keperluan absensi.
                </div>
            </div>
        </div>

        {{-- Fingerprint / Biometrik --}}
        <div class="form-card" style="margin-bottom: 12px; cursor: pointer;" onclick="window.location.href='{{ route('biometric.enrollment') }}'">
            <div class="card-head">
                <div class="card-head-icon" style="background: var(--success-soft);"><ion-icon name="finger-print-outline" style="color: var(--success);"></ion-icon></div>
                <div class="card-title" style="flex: 1;">Fingerprint / Biometrik HP</div>
                <ion-icon name="chevron-forward-outline" style="color: var(--text-400); font-size: 18px;"></ion-icon>
            </div>
            <div class="form-body" style="padding: 10px 16px;">
                <div class="form-hint" style="color: var(--text-600); font-size: 12px;">
                    Gunakan sensor sidik jari bawaan HP Anda sebagai alternatif Face ID.
                </div>
            </div>
        </div>

        {{-- Informasi Pribadi --}}
        <div class="form-card" style="margin-bottom: 12px;">
            <div class="card-head">
                <div class="card-head-icon"><ion-icon name="person-outline"></ion-icon></div>
                <div class="card-title">Informasi Pribadi</div>
            </div>
            <div class="form-body">
                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="person-outline"></ion-icon>
                        Nama Lengkap
                    </label>
                    <div class="input-wrap">
                        <ion-icon name="person-outline" class="input-left-icon"></ion-icon>
                        <input type="text" name="nama_lengkap" class="form-control"
                               value="{{ old('nama_lengkap', $karyawan->nama_lengkap) }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="call-outline"></ion-icon>
                        Nomor HP
                    </label>
                    <div class="input-wrap">
                        <ion-icon name="call-outline" class="input-left-icon"></ion-icon>
                        <input type="tel" name="no_hp" class="form-control"
                               value="{{ old('no_hp', $karyawan->no_hp) }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="mail-outline"></ion-icon>
                        Email
                    </label>
                    <div class="input-wrap">
                        <ion-icon name="mail-outline" class="input-left-icon"></ion-icon>
                        <input type="email" name="email" class="form-control"
                               value="{{ old('email', $karyawan->email) }}"
                               placeholder="Masukkan alamat email">
                    </div>
                </div>
            </div>
        </div>

        {{-- Ubah Password --}}
        <div class="form-card" style="margin-bottom: 12px;">
            <div class="card-head">
                <div class="card-head-icon"><ion-icon name="lock-closed-outline"></ion-icon></div>
                <div class="card-title">Ubah Password</div>
            </div>
            <div class="form-body">
                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        Password Baru
                    </label>
                    <div class="input-wrap">
                        <ion-icon name="lock-closed-outline" class="input-left-icon"></ion-icon>
                        <input type="password" name="password" id="password" class="form-control"
                               placeholder="Masukkan password baru">
                        <button type="button" class="eye-toggle" onclick="toggleEye('password','eye1')">
                            <ion-icon name="eye-outline" id="eye1"></ion-icon>
                        </button>
                    </div>
                    <div class="form-hint">
                        <ion-icon name="information-circle-outline"></ion-icon>
                        Kosongkan jika tidak ingin mengubah password
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        Konfirmasi Password
                    </label>
                    <div class="input-wrap">
                        <ion-icon name="lock-closed-outline" class="input-left-icon"></ion-icon>
                        <input type="password" name="password_confirmation" id="password-confirm" class="form-control"
                               placeholder="Ulangi password baru">
                        <button type="button" class="eye-toggle" onclick="toggleEye('password-confirm','eye2')">
                            <ion-icon name="eye-outline" id="eye2"></ion-icon>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn-submit" id="btn-save">
            <ion-icon name="checkmark-circle-outline"></ion-icon>
            Simpan Perubahan
        </button>

    </form>

    {{-- Delete photo button (only if has photo) --}}
    @if(!empty($karyawan->foto))
    <button type="button" class="btn-delete-photo" onclick="confirmDeletePhoto()">
        <ion-icon name="trash-outline"></ion-icon>
        Hapus Foto Profil
    </button>
    @endif

</div>

@endsection

@push('myscript')
<script>
    /* ── Password eye toggle ── */
    function toggleEye(inputId, iconId) {
        var inp  = document.getElementById(inputId);
        var icon = document.getElementById(iconId);
        if (inp.type === 'password') {
            inp.type = 'text';
            icon.setAttribute('name', 'eye-off-outline');
        } else {
            inp.type = 'password';
            icon.setAttribute('name', 'eye-outline');
        }
    }

    /* ── Handle photo change ── */
    function handlePhotoChange(event) {
        var file = event.target.files[0];
        if (!file) return;

        if (file.size > 2048000) {
            Swal.fire({ icon:'error', title:'File Terlalu Besar', text:'Ukuran file maksimal 2MB', confirmButtonColor:'#2563EB' });
            event.target.value = '';
            return;
        }

        if (!file.type.match('image.*')) {
            Swal.fire({ icon:'error', title:'Format Salah', text:'File harus berupa gambar (PNG, JPG, JPEG)', confirmButtonColor:'#2563EB' });
            event.target.value = '';
            return;
        }

        var reader = new FileReader();
        reader.onload = function(e) {
            /* Update hero avatar & mini photo */
            document.getElementById('photo-preview').src = e.target.result;
            document.getElementById('photo-mini').src    = e.target.result;

            /* Copy file to hidden input used by form */
            var dt = new DataTransfer();
            dt.items.add(file);
            document.getElementById('foto-hidden').files = dt.files;

            /* Show toast */
            document.getElementById('photo-toast').classList.add('show');
        };
        reader.readAsDataURL(file);
    }

    /* ── Form validation ── */
    $(function () {
        $('#form-update').on('submit', function (e) {
            var pwd  = $('[name="password"]').val();
            var conf = $('[name="password_confirmation"]').val();

            if (pwd !== '') {
                if (pwd.length < 6) {
                    e.preventDefault();
                    return Swal.fire({ icon:'error', title:'Password Terlalu Pendek', text:'Password minimal 6 karakter', confirmButtonColor:'#2563EB' });
                }
                if (pwd !== conf) {
                    e.preventDefault();
                    return Swal.fire({ icon:'error', title:'Password Tidak Cocok', text:'Konfirmasi password tidak sesuai', confirmButtonColor:'#2563EB' });
                }
            }

            var btn = $('#btn-save');
            btn.prop('disabled', true).html('<ion-icon name="hourglass-outline"></ion-icon> Menyimpan…');
        });

        /* Auto-dismiss alerts */
        setTimeout(function () {
            $('.alert-box').fadeOut(400, function () { $(this).remove(); });
        }, 5000);
    });

    /* ── Delete photo confirm ── */
    function confirmDeletePhoto() {
        Swal.fire({
            title: 'Hapus Foto Profil?',
            text: 'Foto profil Anda akan dihapus secara permanen',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then(function (result) {
            if (!result.isConfirmed) return;

            Swal.fire({ title: 'Menghapus…', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '/deleteprofilefoto';

            var csrf = document.createElement('input');
            csrf.type = 'hidden'; csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';

            var method = document.createElement('input');
            method.type = 'hidden'; method.name = '_method';
            method.value = 'DELETE';

            form.appendChild(csrf);
            form.appendChild(method);
            document.body.appendChild(form);
            form.submit();
        });
    }
</script>
@endpush