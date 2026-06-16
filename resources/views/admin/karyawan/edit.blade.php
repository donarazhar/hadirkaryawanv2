@extends('admin.layouts.admin')

@section('title', 'Edit Karyawan')
@section('page-title', 'Edit Karyawan')

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
        --slate-200:  #E5E7EB;
        --slate-100:  #F3F4F6;
        --slate-50:   #F9FAFB;
        --white:      #FFFFFF;
        --shadow:     0 1px 3px rgba(0,0,0,0.06),0 1px 2px rgba(0,0,0,0.04);
        --radius:     14px;
    }

    .edit-wrap {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 20px;
        align-items: start;
    }

    @media (max-width: 900px)  { .edit-wrap { grid-template-columns: 1fr; } }

    /* ── BREADCRUMB ── */
    .breadcrumb-bar {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: var(--slate-400);
        margin-bottom: 4px;
    }
    .breadcrumb-bar a { color: var(--blue); text-decoration: none; font-weight: 600; }
    .breadcrumb-bar a:hover { text-decoration: underline; }
    .breadcrumb-bar i { font-size: 14px; }

    /* ── FORM CARD ── */
    .form-card {
        background: var(--white);
        border: 1px solid var(--slate-200);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
    }

    .form-card-head {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 18px 22px;
        border-bottom: 1px solid var(--slate-100);
    }

    .form-card-icon {
        width: 40px; height: 40px;
        border-radius: 10px;
        background: var(--amber-soft);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .form-card-icon i { font-size: 20px; color: var(--amber); }

    .form-card-title { font-size: 15px; font-weight: 700; color: var(--slate-900); }
    .form-card-sub   { font-size: 12px; color: var(--slate-400); margin-top: 1px; }

    .form-card-body { padding: 24px 22px; display: flex; flex-direction: column; gap: 0; }

    /* ── SECTION ── */
    .form-section {
        padding-bottom: 22px;
        margin-bottom: 22px;
        border-bottom: 1px solid var(--slate-100);
    }
    .form-section:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }

    .section-label {
        display: flex;
        align-items: center;
        gap: 7px;
        font-size: 11px;
        font-weight: 700;
        color: var(--slate-400);
        text-transform: uppercase;
        letter-spacing: 0.6px;
        margin-bottom: 16px;
    }
    .section-label i { font-size: 14px; color: var(--blue); }

    /* ── FORM GRID ── */
    .form-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }
    @media (max-width: 640px) { .form-grid-2 { grid-template-columns: 1fr; } }

    .form-grid-3 {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 16px;
    }
    @media (max-width: 900px) { .form-grid-3 { grid-template-columns: 1fr 1fr; } }
    @media (max-width: 480px) { .form-grid-3 { grid-template-columns: 1fr; } }

    /* ── FIELD GROUP ── */
    .fgroup { display: flex; flex-direction: column; gap: 5px; }

    .fgroup label {
        font-size: 11px;
        font-weight: 700;
        color: var(--slate-600);
        text-transform: uppercase;
        letter-spacing: 0.4px;
        display: flex;
        align-items: center;
        gap: 3px;
    }
    .req { color: var(--red); }

    .fgroup input, .fgroup select {
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

    .fgroup input:disabled { background: var(--slate-50); color: var(--slate-500); cursor: not-allowed; }

    .fgroup input:focus:not(:disabled), .fgroup select:focus {
        border-color: var(--blue);
        box-shadow: 0 0 0 3px rgba(37,99,235,0.10);
    }

    .fgroup input.is-invalid, .fgroup select.is-invalid {
        border-color: var(--red);
        box-shadow: 0 0 0 3px rgba(239,68,68,0.10);
    }

    .fgroup input::placeholder { color: var(--slate-400); font-size: 12.5px; }

    .field-hint {
        font-size: 11px;
        color: var(--slate-400);
        font-style: italic;
    }

    .field-error {
        font-size: 11px;
        color: var(--red);
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 3px;
    }
    .field-error i { font-size: 12px; }

    /* ── PASSWORD FIELD ── */
    .pw-wrap { position: relative; }
    .pw-wrap input { padding-right: 44px; }
    .pw-toggle {
        position: absolute;
        right: 12px; top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--slate-400);
        cursor: pointer;
        font-size: 18px;
        padding: 0;
        transition: color 0.15s;
    }
    .pw-toggle:hover { color: var(--blue); }

    /* ── PHOTO UPLOAD ── */
    .photo-upload-area {
        border: 2px dashed var(--slate-200);
        border-radius: 12px;
        padding: 24px;
        text-align: center;
        cursor: pointer;
        transition: border-color 0.15s, background 0.15s;
        position: relative;
    }
    .photo-upload-area:hover { border-color: var(--blue); background: var(--blue-soft); }
    .photo-upload-area input[type="file"] {
        position: absolute; inset: 0;
        opacity: 0; cursor: pointer;
        width: 100%; height: 100%;
        border: none; background: none;
    }

    .photo-upload-icon { font-size: 32px; color: var(--slate-300); margin-bottom: 8px; }
    .photo-upload-text { font-size: 13px; color: var(--slate-600); font-weight: 600; }
    .photo-upload-hint { font-size: 11px; color: var(--slate-400); margin-top: 4px; }

    .photo-preview {
        display: none;
        margin-top: 12px;
        text-align: center;
    }
    .photo-preview img {
        width: 80px; height: 80px;
        border-radius: 12px;
        object-fit: cover;
        border: 2px solid var(--slate-200);
    }
    .photo-preview-name {
        font-size: 11px;
        color: var(--slate-600);
        font-weight: 600;
        margin-top: 6px;
    }

    .current-photo-wrap {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        border: 1px solid var(--slate-200);
        border-radius: 10px;
        background: var(--slate-50);
        margin-bottom: 12px;
    }
    .current-photo-wrap img {
        width: 50px; height: 50px;
        border-radius: 8px;
        object-fit: cover;
        border: 1px solid var(--slate-300);
    }
    .current-photo-info {
        font-size: 12px;
        color: var(--slate-600);
        font-weight: 600;
    }
    .current-photo-info span {
        display: block;
        font-size: 11px;
        font-weight: 400;
        color: var(--slate-400);
    }

    /* ── ERROR BLOCK ── */
    .error-block {
        background: var(--red-soft);
        border: 1px solid #FECACA;
        border-radius: 10px;
        padding: 14px 16px;
        margin-bottom: 20px;
    }
    .error-block-title {
        display: flex;
        align-items: center;
        gap: 7px;
        font-size: 13px;
        font-weight: 700;
        color: var(--red);
        margin-bottom: 8px;
    }
    .error-block-title i { font-size: 16px; }
    .error-block ul { margin: 0; padding-left: 18px; }
    .error-block ul li { font-size: 12.5px; color: #991B1B; margin-bottom: 3px; }

    /* ── FORM FOOTER ── */
    .form-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 22px;
        border-top: 1px solid var(--slate-100);
        background: var(--slate-50);
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        height: 40px;
        padding: 0 18px;
        border: 1.5px solid var(--slate-200);
        border-radius: 9px;
        font-family: 'Inter', sans-serif;
        font-size: 13px;
        font-weight: 700;
        color: var(--slate-600);
        background: var(--white);
        text-decoration: none;
        transition: background 0.15s;
    }
    .btn-back:hover { background: var(--slate-100); color: var(--slate-700); }
    .btn-back i { font-size: 17px; }

    .btn-save {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        height: 40px;
        padding: 0 24px;
        border: none;
        border-radius: 9px;
        font-family: 'Inter', sans-serif;
        font-size: 13px;
        font-weight: 700;
        color: var(--white);
        background: var(--amber);
        cursor: pointer;
        box-shadow: 0 3px 10px rgba(245,158,11,0.25);
        transition: background 0.15s, transform 0.1s;
    }
    .btn-save:hover { background: #D97706; }
    .btn-save:active { transform: scale(0.98); }
    .btn-save i { font-size: 17px; }

    /* ── INFO PANEL ── */
    .info-panel {
        display: flex;
        flex-direction: column;
        gap: 14px;
        position: sticky;
        top: 80px;
    }
    @media (max-width: 900px) { .info-panel { position: static; } }

    .profile-card {
        background: var(--white);
        border: 1px solid var(--slate-200);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
        text-align: center;
        padding: 24px;
    }

    .profile-ava {
        width: 100px; height: 100px;
        border-radius: 50%;
        object-fit: cover;
        margin: 0 auto 16px;
        border: 3px solid var(--white);
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    }

    .profile-ava-init {
        width: 100px; height: 100px;
        border-radius: 50%;
        background: var(--blue-soft);
        color: var(--blue);
        display: flex; align-items: center; justify-content: center;
        font-size: 32px;
        font-weight: 800;
        margin: 0 auto 16px;
        border: 3px solid var(--white);
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    }

    .profile-name { font-size: 16px; font-weight: 800; color: var(--slate-900); }
    .profile-jabatan { font-size: 13px; color: var(--slate-500); margin-top: 2px; }
    .profile-nik {
        display: inline-block;
        background: var(--slate-100);
        color: var(--slate-600);
        font-size: 11px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 50px;
        margin-top: 10px;
    }

    .profile-details {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px dashed var(--slate-200);
        text-align: left;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .pd-item { display: flex; align-items: flex-start; gap: 8px; font-size: 12px; color: var(--slate-600); }
    .pd-item i { font-size: 15px; margin-top: 1px; flex-shrink: 0; }
    .pd-item strong { color: var(--slate-900); }

    .tips-box {
        background: var(--red-soft);
        border: 1px solid #FECACA;
        border-radius: var(--radius);
        padding: 14px 16px;
        display: flex;
        gap: 10px;
        align-items: flex-start;
    }
    .tips-box i { font-size: 18px; color: var(--red); flex-shrink: 0; margin-top: 1px; }
    .tips-box-text { font-size: 12px; color: #991B1B; line-height: 1.6; }
    .tips-box-text strong { font-weight: 800; }
</style>
@endpush

@section('content')
{{-- Breadcrumb --}}
<div class="breadcrumb-bar">
    <a href="{{ route('panel.karyawan.index') }}"><i class="mdi mdi-account-group"></i> Data Karyawan</a>
    <i class="mdi mdi-chevron-right"></i>
    <span>Edit Karyawan</span>
</div>

<div class="edit-wrap">

    {{-- ── LEFT: FORM ── --}}
    <div>
        <div class="form-card">
            <div class="form-card-head">
                <div class="form-card-icon">
                    <i class="mdi mdi-pencil-outline"></i>
                </div>
                <div>
                    <div class="form-card-title">Edit Data Karyawan</div>
                    <div class="form-card-sub">Perbarui informasi karyawan</div>
                </div>
            </div>

            <form action="{{ route('panel.karyawan.update', $karyawan->nik) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-card-body">

                    {{-- Error Block --}}
                    @if($errors->any())
                    <div class="error-block">
                        <div class="error-block-title">
                            <i class="mdi mdi-alert-circle"></i> Terdapat kesalahan pada form:
                        </div>
                        <ul>
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    {{-- SECTION: Identitas --}}
                    <div class="form-section">
                        <div class="section-label">
                            <i class="mdi mdi-card-account-details"></i> Identitas Karyawan
                        </div>
                        <div class="form-grid-2">
                            <div class="fgroup">
                                <label>NIK</label>
                                <input type="text" value="{{ $karyawan->nik }}" disabled>
                                <div class="field-hint">NIK tidak dapat diubah</div>
                            </div>
                            <div class="fgroup">
                                <label>Email</label>
                                <input type="email" name="email" id="email"
                                    placeholder="Contoh: ahmad@gmail.com"
                                    value="{{ old('email', $karyawan->email) }}"
                                    maxlength="100"
                                    class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
                                @error('email')
                                    <div class="field-error"><i class="mdi mdi-alert-circle"></i> {{ $message }}</div>
                                @else
                                    <div class="field-hint">Opsional, untuk login Google</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- SECTION: Data Pribadi --}}
                    <div class="form-section">
                        <div class="section-label">
                            <i class="mdi mdi-account"></i> Data Pribadi
                        </div>
                        <div class="form-grid-3">
                            <div class="fgroup" style="grid-column: span 2;">
                                <label>Nama Lengkap <span class="req">*</span></label>
                                <input type="text" name="nama_lengkap" id="nama_lengkap"
                                    placeholder="Contoh: Ahmad Rizki Pratama"
                                    value="{{ old('nama_lengkap', $karyawan->nama_lengkap) }}"
                                    maxlength="100" required
                                    class="{{ $errors->has('nama_lengkap') ? 'is-invalid' : '' }}">
                                @error('nama_lengkap')
                                    <div class="field-error"><i class="mdi mdi-alert-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>
                            <div class="fgroup">
                                <label>Jabatan <span class="req">*</span></label>
                                <input type="text" name="jabatan" id="jabatan"
                                    placeholder="Contoh: IT Manager"
                                    value="{{ old('jabatan', $karyawan->jabatan) }}"
                                    maxlength="20" required
                                    class="{{ $errors->has('jabatan') ? 'is-invalid' : '' }}">
                                @error('jabatan')
                                    <div class="field-error"><i class="mdi mdi-alert-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="fgroup" style="margin-top:16px;">
                            <label>No. HP <span class="req">*</span></label>
                            <input type="text" name="no_hp" id="no_hp"
                                placeholder="Contoh: 081234567890"
                                value="{{ old('no_hp', $karyawan->no_hp) }}"
                                maxlength="15" required
                                class="{{ $errors->has('no_hp') ? 'is-invalid' : '' }}">
                            @error('no_hp')
                                <div class="field-error"><i class="mdi mdi-alert-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- SECTION: Penempatan --}}
                    <div class="form-section">
                        <div class="section-label">
                            <i class="mdi mdi-office-building"></i> Penempatan
                        </div>
                        <div class="form-grid-2">
                            <div class="fgroup">
                                <label>Departemen <span class="req">*</span></label>
                                <select name="kode_dept" id="kode_dept" required
                                    class="{{ $errors->has('kode_dept') ? 'is-invalid' : '' }}">
                                    <option value="">-- Pilih Departemen --</option>
                                    @foreach($departemen as $dept)
                                        <option value="{{ $dept->kode_dept }}"
                                            {{ old('kode_dept', $karyawan->kode_dept) == $dept->kode_dept ? 'selected' : '' }}>
                                            {{ $dept->nama_dept }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kode_dept')
                                    <div class="field-error"><i class="mdi mdi-alert-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>
                            <div class="fgroup">
                                <label>Cabang <span class="req">*</span></label>
                                <select name="kode_cabang" id="kode_cabang" required
                                    class="{{ $errors->has('kode_cabang') ? 'is-invalid' : '' }}">
                                    <option value="">-- Pilih Cabang --</option>
                                    @foreach($cabang as $cbg)
                                        <option value="{{ $cbg->kode_cabang }}"
                                            {{ old('kode_cabang', $karyawan->kode_cabang) == $cbg->kode_cabang ? 'selected' : '' }}>
                                            {{ $cbg->nama_cabang }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kode_cabang')
                                    <div class="field-error"><i class="mdi mdi-alert-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- SECTION: Akun --}}
                    <div class="form-section">
                        <div class="section-label">
                            <i class="mdi mdi-lock"></i> Akun &amp; Keamanan
                        </div>
                        <div class="fgroup">
                            <label>Password Baru</label>
                            <div class="pw-wrap">
                                <input type="password" name="password" id="password"
                                    placeholder="Kosongkan jika tidak ingin mengubah password"
                                    class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
                                <button type="button" class="pw-toggle" id="pwToggle" onclick="togglePw()">
                                    <i class="mdi mdi-eye-off" id="pwIcon"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="field-error"><i class="mdi mdi-alert-circle"></i> {{ $message }}</div>
                            @else
                                <div class="field-hint">Hanya isi jika ingin mengganti password karyawan</div>
                            @enderror
                        </div>
                    </div>

                    {{-- SECTION: Foto --}}
                    <div class="form-section">
                        <div class="section-label">
                            <i class="mdi mdi-image"></i> Foto Profil <span style="font-weight:400; text-transform:none; font-size:10px;">(Opsional)</span>
                        </div>

                        @if($karyawan->foto)
                        <div class="current-photo-wrap">
                            <img src="{{ asset('storage/uploads/karyawan/' . $karyawan->foto) }}" alt="Foto">
                            <div class="current-photo-info">
                                Foto Saat Ini
                                <span>{{ $karyawan->foto }}</span>
                            </div>
                        </div>
                        @endif

                        <div class="photo-upload-area" id="uploadArea">
                            <input type="file" name="foto" id="foto"
                                accept="image/jpeg,image/png,image/jpg"
                                onchange="previewImage(event)">
                            <div id="uploadPlaceholder">
                                <div class="photo-upload-icon"><i class="mdi mdi-cloud-upload-outline"></i></div>
                                <div class="photo-upload-text">Klik atau seret foto baru ke sini</div>
                                <div class="photo-upload-hint">Format: JPG, JPEG, PNG · Maks. 2MB</div>
                            </div>
                            <div class="photo-preview" id="photoPreview">
                                <img id="preview" src="" alt="Preview">
                                <div class="photo-preview-name" id="photoName"></div>
                            </div>
                        </div>
                        @error('foto')
                            <div class="field-error mt-2"><i class="mdi mdi-alert-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="form-footer">
                    <a href="{{ route('panel.karyawan.index') }}" class="btn-back">
                        <i class="mdi mdi-arrow-left"></i> Batal
                    </a>
                    <button type="submit" class="btn-save">
                        <i class="mdi mdi-content-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── RIGHT: INFO PANEL ── --}}
    <div class="info-panel">
        <div class="profile-card">
            @if($karyawan->foto)
                <img src="{{ asset('storage/uploads/karyawan/' . $karyawan->foto) }}" class="profile-ava" alt="Foto">
            @else
                <div class="profile-ava-init">{{ strtoupper(substr($karyawan->nama_lengkap, 0, 2)) }}</div>
            @endif

            <div class="profile-name">{{ $karyawan->nama_lengkap }}</div>
            <div class="profile-jabatan">{{ $karyawan->jabatan }}</div>
            <div class="profile-nik">{{ $karyawan->nik }}</div>

            <div class="profile-details">
                <div class="pd-item">
                    <i class="mdi mdi-email text-warning"></i>
                    <div>
                        <strong>Email:</strong><br>
                        {{ $karyawan->email ?? 'N/A' }}
                    </div>
                </div>
                <div class="pd-item">
                    <i class="mdi mdi-phone text-primary"></i>
                    <div>
                        <strong>No HP:</strong><br>
                        {{ $karyawan->no_hp }}
                    </div>
                </div>
                <div class="pd-item">
                    <i class="mdi mdi-file-tree text-success"></i>
                    <div>
                        <strong>Departemen:</strong><br>
                        {{ $karyawan->departemen->nama_dept ?? 'N/A' }}
                    </div>
                </div>
                <div class="pd-item">
                    <i class="mdi mdi-office-building text-info"></i>
                    <div>
                        <strong>Cabang:</strong><br>
                        {{ $karyawan->cabang->nama_cabang ?? 'N/A' }}
                    </div>
                </div>
            </div>
        </div>

        <div class="tips-box">
            <i class="mdi mdi-alert-circle-outline"></i>
            <div class="tips-box-text">
                <strong>Peringatan:</strong> Perubahan data karyawan akan langsung mempengaruhi sistem presensi. Pastikan data yang diinput sudah benar.
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function togglePw() {
        const pw   = document.getElementById('password');
        const icon = document.getElementById('pwIcon');
        if (pw.type === 'password') {
            pw.type = 'text';
            icon.className = 'mdi mdi-eye';
        } else {
            pw.type = 'password';
            icon.className = 'mdi mdi-eye-off';
        }
    }

    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('preview').src = e.target.result;
                document.getElementById('photoName').textContent = file.name;
                document.getElementById('uploadPlaceholder').style.display = 'none';
                document.getElementById('photoPreview').style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endpush