@extends('karyawan.layouts.presensi')

@section('content')

<style>
    /* ===== MODERN PROFILE PAGE ===== */
    :root {
        --primary: #0053C5;
        --primary-dark: #003d94;
        --primary-light: #2E7CE6;
        --primary-gradient: linear-gradient(135deg, #0053C5 0%, #2E7CE6 100%);
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --bg-main: #f0f4f8;
        --bg-card: #ffffff;
        --text-primary: #1e293b;
        --text-secondary: #64748b;
    }

    body {
        background: var(--bg-main);
    }

    /* ===== PAGE HEADER ===== */
    .page-header {
        background: var(--primary-gradient);
        padding: 24px 20px 100px 20px;
        position: relative;
        overflow: hidden;
        margin: 0;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -40%;
        right: -15%;
        width: 250px;
        height: 250px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
        filter: blur(50px);
    }

    .header-content {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .btn-back {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-back ion-icon {
        font-size: 24px;
        color: white;
    }

    .btn-back:active {
        transform: scale(0.95);
        background: rgba(255, 255, 255, 0.25);
    }

    .header-title {
        flex: 1;
    }

    .header-title h1 {
        font-size: 22px;
        font-weight: 700;
        color: white;
        margin: 0 0 4px 0;
    }

    .header-title p {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.8);
        margin: 0;
    }

    /* ===== PROFILE CARD ===== */
    .profile-section {
        padding: 0 20px;
        margin-top: -85px;
        margin-bottom: 20px;
        position: relative;
        z-index: 10;
    }

    .profile-card {
        background: var(--bg-card);
        border-radius: 20px;
        padding: 24px 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 83, 197, 0.08);
        text-align: center;
    }

    .photo-wrapper {
        position: relative;
        width: 120px;
        height: 120px;
        margin: 0 auto 16px;
    }

    .profile-photo {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid white;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .photo-upload-btn {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 40px;
        height: 40px;
        background: var(--primary-gradient);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0, 83, 197, 0.4);
        transition: all 0.3s ease;
    }

    .photo-upload-btn ion-icon {
        font-size: 20px;
        color: white;
    }

    .photo-upload-btn:active {
        transform: scale(0.95);
    }

    .profile-name {
        font-size: 20px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 4px;
    }

    .profile-nik {
        font-size: 13px;
        color: var(--text-secondary);
        font-weight: 500;
    }

    .profile-info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #f1f5f9;
    }

    .info-item {
        text-align: center;
        padding: 12px;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 12px;
    }

    .info-label {
        font-size: 11px;
        color: var(--text-secondary);
        font-weight: 600;
        margin-bottom: 4px;
    }

    .info-value {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-primary);
    }

    /* ===== FORM SECTION ===== */
    .form-section {
        padding: 0 20px 20px;
    }

    .form-card {
        background: var(--bg-card);
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(0, 83, 197, 0.08);
        margin-bottom: 16px;
    }

    .form-card-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-card-title ion-icon {
        font-size: 20px;
        color: var(--primary);
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-group:last-child {
        margin-bottom: 0;
    }

    .form-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-secondary);
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .form-label ion-icon {
        font-size: 16px;
        color: var(--primary);
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        font-size: 14px;
        color: var(--text-primary);
        background: #f8fafc;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        background: white;
        box-shadow: 0 0 0 3px rgba(0, 83, 197, 0.1);
    }

    .form-hint {
        font-size: 11px;
        color: var(--text-secondary);
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .form-hint ion-icon {
        font-size: 14px;
    }

    /* ===== BUTTONS ===== */
    .btn-primary {
        width: 100%;
        padding: 14px 20px;
        background: var(--primary-gradient);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 83, 197, 0.3);
        margin-top: 20px;
    }

    .btn-primary ion-icon {
        font-size: 20px;
    }

    .btn-primary:active {
        transform: scale(0.98);
        box-shadow: 0 2px 8px rgba(0, 83, 197, 0.3);
    }

    .btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .btn-danger {
        width: 100%;
        padding: 12px 20px;
        background: white;
        color: var(--danger);
        border: 1px solid var(--danger);
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 12px;
    }

    .btn-danger:active {
        background: var(--danger);
        color: white;
        transform: scale(0.98);
    }

    /* ===== ALERTS ===== */
    .alert {
        padding: 14px 16px;
        border-radius: 12px;
        margin-bottom: 16px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        animation: slideDown 0.3s ease;
    }

    .alert ion-icon {
        font-size: 20px;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .alert-success {
        background: #dcfce7;
        color: #16a34a;
        border: 1px solid #86efac;
    }

    .alert-error {
        background: #fee2e2;
        color: #dc2626;
        border: 1px solid #fca5a5;
    }

    .alert-content {
        flex: 1;
    }

    .alert-content strong {
        display: block;
        margin-bottom: 4px;
    }

    .alert-content p {
        margin: 0;
        font-size: 13px;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ===== BOTTOM SPACING ===== */
    .bottom-spacer {
        padding-bottom: 100px;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 375px) {

        .profile-section,
        .form-section {
            padding-left: 16px;
            padding-right: 16px;
        }

        .profile-info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <div class="header-content">
        <a href="{{ route('dashboard') }}" class="btn-back">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
        <div class="header-title">
            <h1>Edit Profil</h1>
            <p>Kelola informasi akun Anda</p>
        </div>
    </div>
</div>

<!-- Profile Card -->
<div class="profile-section">
    <div class="profile-card">
        <form id="form-profile" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="photo-wrapper">
                @if (!empty($karyawan->foto))
                @php
                $path = Storage::url('uploads/karyawan/' . $karyawan->foto);
                @endphp
                <img src="{{ url($path) }}" class="profile-photo" id="photo-preview" alt="Avatar">
                @else
                <img src="https://ui-avatars.com/api/?name={{ urlencode($karyawan->nama_lengkap) }}&background=0053C5&color=fff&size=240"
                    class="profile-photo" id="photo-preview" alt="Avatar">
                @endif

                <input type="file" name="foto" id="foto-input" accept="image/*" style="display: none;" onchange="previewPhoto(event)">
                <label for="foto-input" class="photo-upload-btn">
                    <ion-icon name="camera"></ion-icon>
                </label>
            </div>

            <div class="profile-name">{{ $karyawan->nama_lengkap }}</div>
            <div class="profile-nik">NIK: {{ $karyawan->nik }}</div>

            <div class="profile-info-grid">
                <div class="info-item">
                    <div class="info-label">Jabatan</div>
                    <div class="info-value">{{ $karyawan->jabatan }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Departemen</div>
                    <div class="info-value">{{ $karyawan->nama_dept ?? 'N/A' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Cabang</div>
                    <div class="info-value">{{ $karyawan->nama_cabang ?? 'N/A' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">No. HP</div>
                    <div class="info-value">{{ $karyawan->no_hp }}</div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Form Section -->
<div class="form-section bottom-spacer">
    <!-- Alerts -->
    @if(session('success'))
    <div class="alert alert-success">
        <ion-icon name="checkmark-circle"></ion-icon>
        <div class="alert-content">
            <strong>Berhasil!</strong>
            <p>{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-error">
        <ion-icon name="alert-circle"></ion-icon>
        <div class="alert-content">
            <strong>Gagal!</strong>
            <p>{{ session('error') }}</p>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-error">
        <ion-icon name="alert-circle"></ion-icon>
        <div class="alert-content">
            <strong>Periksa kembali form Anda:</strong>
            <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                <li style="font-size: 13px;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <!-- Form Edit Informasi -->
    <form action="/updateprofile" method="POST" enctype="multipart/form-data" id="form-update">
        @csrf

        <div class="form-card">
            <div class="form-card-title">
                <ion-icon name="person-circle"></ion-icon>
                Informasi Pribadi
            </div>

            <div class="form-group">
                <label class="form-label">
                    <ion-icon name="person"></ion-icon>
                    Nama Lengkap
                </label>
                <input type="text" name="nama_lengkap" class="form-control"
                    value="{{ old('nama_lengkap', $karyawan->nama_lengkap) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">
                    <ion-icon name="call"></ion-icon>
                    Nomor HP
                </label>
                <input type="text" name="no_hp" class="form-control"
                    value="{{ old('no_hp', $karyawan->no_hp) }}" required>
            </div>

            <input type="file" name="foto" id="foto" accept="image/*" style="display: none;">
        </div>

        <div class="form-card">
            <div class="form-card-title">
                <ion-icon name="lock-closed"></ion-icon>
                Ubah Password
            </div>

            <div class="form-group">
                <label class="form-label">
                    <ion-icon name="lock-closed"></ion-icon>
                    Password Baru
                </label>
                <input type="password" name="password" class="form-control"
                    placeholder="Masukkan password baru">
                <div class="form-hint">
                    <ion-icon name="information-circle"></ion-icon>
                    Kosongkan jika tidak ingin mengubah password
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">
                    <ion-icon name="lock-closed"></ion-icon>
                    Konfirmasi Password
                </label>
                <input type="password" name="password_confirmation" class="form-control"
                    placeholder="Konfirmasi password baru">
            </div>
        </div>

        <button type="submit" class="btn-primary" id="btn-save">
            <ion-icon name="save"></ion-icon>
            <span>Simpan Perubahan</span>
        </button>
    </form>

    <!-- Button Hapus Foto -->
    @if(!empty($karyawan->foto))
    <button type="button" class="btn-danger" onclick="confirmDeletePhoto()">
        <ion-icon name="trash"></ion-icon>
        <span>Hapus Foto Profil</span>
    </button>
    @endif
</div>

@endsection

@push('myscript')
<script>
    // Preview foto saat dipilih
    function previewPhoto(event) {
        const file = event.target.files[0];

        if (file) {
            // Validasi ukuran (max 2MB)
            if (file.size > 2048000) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Terlalu Besar',
                    text: 'Ukuran file maksimal 2MB',
                    confirmButtonColor: '#0053C5'
                });
                event.target.value = '';
                return;
            }

            // Validasi tipe file
            if (!file.type.match('image.*')) {
                Swal.fire({
                    icon: 'error',
                    title: 'Format File Salah',
                    text: 'File harus berupa gambar (PNG, JPG, JPEG)',
                    confirmButtonColor: '#0053C5'
                });
                event.target.value = '';
                return;
            }

            // Preview
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('photo-preview').src = e.target.result;

                // Copy file ke form utama
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                document.getElementById('foto').files = dataTransfer.files;

                Swal.fire({
                    icon: 'success',
                    title: 'Foto Dipilih',
                    text: 'Klik "Simpan Perubahan" untuk mengupload foto',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
            reader.readAsDataURL(file);
        }
    }

    // Confirm hapus foto
    function confirmDeletePhoto() {
        Swal.fire({
            title: 'Hapus Foto Profil?',
            text: 'Foto profil Anda akan dihapus',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Menghapus...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Create form dan submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/deleteprofilefoto';

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';

                form.appendChild(csrfInput);
                form.appendChild(methodInput);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    // Simple form validation
    $(document).ready(function() {
        $('#form-update').on('submit', function(e) {
            const password = $(this).find('[name="password"]').val();
            const password_confirmation = $(this).find('[name="password_confirmation"]').val();

            // Validasi password jika diisi
            if (password !== '') {
                if (password.length < 6) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Password Terlalu Pendek',
                        text: 'Password minimal 6 karakter',
                        confirmButtonColor: '#0053C5'
                    });
                    return false;
                }

                if (password !== password_confirmation) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Password Tidak Cocok',
                        text: 'Konfirmasi password tidak sesuai',
                        confirmButtonColor: '#0053C5'
                    });
                    return false;
                }
            }

            // Show loading
            const btnSave = $('#btn-save');
            btnSave.prop('disabled', true);
            btnSave.html('<ion-icon name="hourglass-outline"></ion-icon> <span>Menyimpan...</span>');

            // Form akan submit secara natural
        });
    });

    // Auto hide alerts
    setTimeout(function() {
        $('.alert').fadeOut(300, function() {
            $(this).remove();
        });
    }, 5000);
</script>
@endpush