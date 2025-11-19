@extends('karyawan.layouts.presensi-face')

@section('content')

<style>
    .notif-container {
        padding: 40px 20px;
        text-align: center;
        min-height: 70vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .notif-icon {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px;
        box-shadow: 0 10px 30px rgba(139, 92, 246, 0.3);
    }

    .notif-icon ion-icon {
        font-size: 64px;
        color: white;
    }

    .notif-title {
        font-size: 22px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 12px;
    }

    .notif-message {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 32px;
        line-height: 1.6;
    }

    .btn-back-dashboard {
        padding: 14px 32px;
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 6px 20px rgba(139, 92, 246, 0.3);
    }
</style>

<div class="notif-container">
    <div class="notif-icon">
        <ion-icon name="calendar-outline"></ion-icon>
    </div>
    
    <h1 class="notif-title">Tidak Ada Jadwal Kerja</h1>
    
    <p class="notif-message">
        Maaf <strong>{{ $nama }}</strong>,<br>
        Tidak ada jadwal kerja untuk hari <strong>{{ $hari }}</strong>.<br>
        Silakan hubungi HRD atau admin untuk informasi lebih lanjut.
    </p>

    <a href="{{ route('presensi-face.dashboard') }}" class="btn-back-dashboard">
        <ion-icon name="arrow-back"></ion-icon>
        Kembali ke Dashboard
    </a>
</div>

@endsection