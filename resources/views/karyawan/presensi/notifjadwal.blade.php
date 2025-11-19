@extends('karyawan.layouts.presensi')

@section('content')

<style>
    /* ===== NOTIF JADWAL PAGE ===== */
    :root {
        --primary: #0053C5;
        --primary-gradient: linear-gradient(135deg, #0053C5 0%, #2E7CE6 100%);
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
    }

    .btn-back ion-icon {
        font-size: 24px;
        color: white;
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

    /* ===== NOTIF SECTION ===== */
    .notif-section {
        padding: 0 20px;
        margin-top: -85px;
        position: relative;
        z-index: 10;
    }

    .notif-card {
        background: var(--bg-card);
        border-radius: 20px;
        padding: 40px 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 83, 197, 0.08);
        text-align: center;
    }

    .notif-icon {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(251, 146, 60, 0.1) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px;
        border: 3px solid rgba(245, 158, 11, 0.2);
    }

    .notif-icon ion-icon {
        font-size: 50px;
        color: var(--warning);
    }

    .notif-title {
        font-size: 22px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 12px;
    }

    .notif-message {
        font-size: 15px;
        color: var(--text-secondary);
        line-height: 1.6;
        margin-bottom: 24px;
    }

    .notif-details {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 24px;
        text-align: left;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .detail-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .detail-label {
        font-size: 13px;
        color: var(--text-secondary);
        font-weight: 600;
    }

    .detail-value {
        font-size: 13px;
        color: var(--text-primary);
        font-weight: 700;
    }

    .info-box {
        background: rgba(245, 158, 11, 0.1);
        border: 1px solid rgba(245, 158, 11, 0.3);
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 24px;
    }

    .info-box p {
        margin: 0;
        font-size: 13px;
        color: #92400e;
        line-height: 1.6;
        display: flex;
        align-items: flex-start;
        gap: 8px;
    }

    .info-box ion-icon {
        font-size: 18px;
        color: var(--warning);
        flex-shrink: 0;
        margin-top: 2px;
    }

    .btn-action {
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
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 83, 197, 0.3);
        margin-bottom: 12px;
    }

    .btn-action ion-icon {
        font-size: 20px;
    }

    .btn-action:active {
        transform: scale(0.98);
    }

    .btn-secondary {
        width: 100%;
        padding: 14px 20px;
        background: white;
        color: var(--primary);
        border: 2px solid var(--primary);
        border-radius: 12px;
        font-size: 15px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-secondary:active {
        background: var(--primary);
        color: white;
        transform: scale(0.98);
    }

    /* ===== ILUSTRASI ===== */
    .illustration {
        margin: 20px 0;
        opacity: 0.3;
    }

    .illustration svg {
        width: 100%;
        max-width: 200px;
        height: auto;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 375px) {
        .notif-section {
            padding-left: 16px;
            padding-right: 16px;
        }

        .notif-card {
            padding: 30px 20px;
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
            <h1>Jadwal Tidak Tersedia</h1>
            <p>Hari {{ $hari }}</p>
        </div>
    </div>
</div>

<!-- Notif Section -->
<div class="notif-section">
    <div class="notif-card">
        <!-- Icon -->
        <div class="notif-icon">
            <ion-icon name="calendar-outline"></ion-icon>
        </div>

        <!-- Title -->
        <h2 class="notif-title">Belum Ada Jadwal Kerja</h2>

        <!-- Message -->
        <p class="notif-message">
            Maaf, Anda belum memiliki jadwal kerja untuk hari <strong>{{ $hari }}</strong>.
            Silakan hubungi HRD atau admin untuk pengaturan jadwal kerja Anda.
        </p>

        <!-- Details -->
        <div class="notif-details">
            <div class="detail-row">
                <span class="detail-label">NIK</span>
                <span class="detail-value">{{ $nik }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Nama</span>
                <span class="detail-value">{{ $nama }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Hari</span>
                <span class="detail-value">{{ $hari }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Tanggal</span>
                <span class="detail-value">{{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</span>
            </div>
        </div>

        <!-- Info Box -->
        <div class="info-box">
            <p>
                <ion-icon name="information-circle"></ion-icon>
                <span>
                    Jadwal kerja diatur oleh admin berdasarkan departemen dan cabang Anda.
                    Pastikan data Anda sudah terdaftar dengan benar.
                </span>
            </p>
        </div>

        <!-- Illustration (Optional) -->
        <div class="illustration">
            <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                <circle cx="100" cy="100" r="80" fill="none" stroke="currentColor" stroke-width="2" stroke-dasharray="5,5" opacity="0.3" />
                <circle cx="100" cy="100" r="60" fill="none" stroke="currentColor" stroke-width="2" stroke-dasharray="5,5" opacity="0.2" />
                <circle cx="100" cy="100" r="40" fill="none" stroke="currentColor" stroke-width="2" stroke-dasharray="5,5" opacity="0.1" />
            </svg>
        </div>

        <!-- Actions -->
        <a href="{{ route('dashboard') }}" class="btn-action">
            <ion-icon name="home"></ion-icon>
            <span>Kembali ke Dashboard</span>
        </a>

        <button type="button" class="btn-secondary" onclick="contactAdmin()">
            <ion-icon name="call"></ion-icon>
            <span>Hubungi Admin</span>
        </button>
    </div>
</div>

@endsection

@push('myscript')
<script>
    function contactAdmin() {
        Swal.fire({
            title: 'Hubungi Admin',
            html: `
                <div style="text-align: left; padding: 10px;">
                    <p style="margin-bottom: 15px;">Silakan hubungi admin melalui:</p>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <a href="tel:+6281234567890" style="display: flex; align-items: center; gap: 10px; padding: 12px; background: #f8fafc; border-radius: 8px; text-decoration: none; color: #0053C5;">
                            <ion-icon name="call" style="font-size: 24px;"></ion-icon>
                            <div>
                                <strong>Telepon</strong><br>
                                <small>+62 812-3456-7890</small>
                            </div>
                        </a>
                        <a href="mailto:admin@ypialazhar.com" style="display: flex; align-items: center; gap: 10px; padding: 12px; background: #f8fafc; border-radius: 8px; text-decoration: none; color: #0053C5;">
                            <ion-icon name="mail" style="font-size: 24px;"></ion-icon>
                            <div>
                                <strong>Email</strong><br>
                                <small>admin@ypialazhar.com</small>
                            </div>
                        </a>
                        <a href="https://wa.me/6281234567890?text=Halo%20admin,%20saya%20butuh%20bantuan%20untuk%20jadwal%20kerja" target="_blank" style="display: flex; align-items: center; gap: 10px; padding: 12px; background: #dcfce7; border-radius: 8px; text-decoration: none; color: #16a34a;">
                            <ion-icon name="logo-whatsapp" style="font-size: 24px;"></ion-icon>
                            <div>
                                <strong>WhatsApp</strong><br>
                                <small>+62 812-3456-7890</small>
                            </div>
                        </a>
                    </div>
                </div>
            `,
            showConfirmButton: false,
            showCloseButton: true,
            width: '400px'
        });
    }
</script>
@endpush