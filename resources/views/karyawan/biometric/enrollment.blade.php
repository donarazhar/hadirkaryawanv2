@extends('karyawan.layouts.presensi')

@section('content')

<style>
    :root {
        --primary:      #2563EB;
        --success:      #10B981;
        --danger:       #EF4444;
        --text-900:     #111827;
        --text-600:     #4B5563;
        --bg:           #F8FAFC;
    }

    body {
        background-color: var(--bg);
        font-family: 'Inter', sans-serif;
    }

    .top-bar {
        background: white;
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 16px;
        border-bottom: 1px solid #E2E8F0;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .btn-back {
        width: 36px;
        height: 36px;
        background: #F1F5F9;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-900);
        text-decoration: none;
    }

    .page-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-900);
    }

    .enroll-container {
        padding: 24px 16px;
        text-align: center;
    }

    .enroll-icon {
        font-size: 80px;
        color: var(--primary);
        margin-bottom: 16px;
    }

    .enroll-title {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 8px;
        color: var(--text-900);
    }

    .enroll-desc {
        font-size: 14px;
        color: var(--text-600);
        line-height: 1.5;
        margin-bottom: 32px;
    }

    .btn-action {
        width: 100%;
        padding: 14px;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-enroll {
        background: var(--primary);
        color: white;
        margin-bottom: 12px;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }

    .btn-delete {
        background: #FEF2F2;
        color: var(--danger);
        border: 1px solid #FECACA;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 24px;
    }

    .status-active {
        background: #ECFDF5;
        color: var(--success);
    }

    .status-inactive {
        background: #F1F5F9;
        color: var(--text-600);
    }

</style>

<div class="top-bar">
    <a href="{{ route('profile.edit') }}" class="btn-back">
        <ion-icon name="chevron-back-outline"></ion-icon>
    </a>
    <div class="page-title">Fingerprint / Biometrik</div>
</div>

<div class="enroll-container">
    <ion-icon name="finger-print-outline" class="enroll-icon"></ion-icon>
    <h2 class="enroll-title">Daftar Biometrik HP</h2>
    
    @if($karyawan->webauthn_id)
        <div class="status-badge status-active">
            <ion-icon name="checkmark-circle"></ion-icon> Sidik Jari Terdaftar
        </div>
        <p class="enroll-desc">
            Perangkat ini sudah terdaftar. Anda dapat menggunakan sensor sidik jari (Fingerprint / Face ID) di HP Anda untuk mempercepat proses absensi.
        </p>
        
        <form action="{{ route('biometric.delete') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data biometrik ini?');">
            @csrf
            <button type="submit" class="btn-action btn-delete">
                <ion-icon name="trash-outline"></ion-icon> Hapus Pendaftaran
            </button>
        </form>
    @else
        <div class="status-badge status-inactive">
            <ion-icon name="close-circle"></ion-icon> Belum Terdaftar
        </div>
        <p class="enroll-desc">
            Daftarkan perangkat ini untuk mempermudah absensi. Anda akan bisa menggunakan pemindai sidik jari atau Face ID bawaan HP Anda alih-alih mengambil foto wajah.
        </p>

        <button id="btnEnroll" class="btn-action btn-enroll">
            <ion-icon name="finger-print"></ion-icon> Daftarkan Sekarang
        </button>
    @endif
</div>

@endsection

@push('myscript')
<script>
    // Utility for base64 encoding/decoding array buffers
    function bufferToBase64url(buffer) {
        const bytes = new Uint8Array(buffer);
        let str = '';
        for (let charCode of bytes) {
            str += String.fromCharCode(charCode);
        }
        return btoa(str).replace(/\+/g, '-').replace(/\//g, '_').replace(/=/g, '');
    }

    $('#btnEnroll').click(async function() {
        if (!window.PublicKeyCredential) {
            Swal.fire({
                icon: 'error',
                title: 'Tidak Didukung',
                text: 'Browser atau perangkat Anda tidak mendukung fitur WebAuthn/Fingerprint.',
                confirmButtonColor: '#2563EB'
            });
            return;
        }

        try {
            // Generate random challenge (In a real high-security app, this comes from backend)
            const challenge = new Uint8Array(32);
            window.crypto.getRandomValues(challenge);

            // Generate user ID
            const userId = new Uint8Array(16);
            window.crypto.getRandomValues(userId);

            const publicKey = {
                challenge: challenge,
                rp: {
                    name: "PresensiGPS",
                    id: window.location.hostname
                },
                user: {
                    id: userId,
                    name: "{{ $karyawan->nik }}",
                    displayName: "{{ $karyawan->nama_lengkap }}"
                },
                pubKeyCredParams: [
                    { type: "public-key", alg: -7 },  // ES256
                    { type: "public-key", alg: -257 } // RS256
                ],
                authenticatorSelection: {
                    authenticatorAttachment: "platform", // Force device authenticator (FaceID/TouchID/Fingerprint)
                    userVerification: "required"
                },
                timeout: 60000,
                attestation: "none"
            };

            // Call WebAuthn API
            const credential = await navigator.credentials.create({ publicKey });
            
            // Send credential ID to backend
            const rawId = bufferToBase64url(credential.rawId);

            $.ajax({
                type: 'POST',
                url: '{{ route('biometric.store') }}',
                data: {
                    _token: '{{ csrf_token() }}',
                    rawId: rawId
                },
                success: function(res) {
                    if (res.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: res.message,
                            confirmButtonColor: '#10B981'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: res.message });
                    }
                },
                error: function(err) {
                    Swal.fire({ icon: 'error', title: 'Terjadi Kesalahan', text: 'Gagal menyimpan kredensial ke server.' });
                }
            });

        } catch (err) {
            console.error('WebAuthn Error:', err);
            // User cancelled or biometric failed
            if (err.name === 'NotAllowedError') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Dibatalkan',
                    text: 'Pembuatan biometrik dibatalkan atau ditolak.',
                    confirmButtonColor: '#2563EB'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Tidak dapat menginisialisasi sensor biometrik perangkat Anda.',
                    confirmButtonColor: '#EF4444'
                });
            }
        }
    });
</script>
@endpush
