@extends('karyawan.layouts.presensi')
@section('content')

<style>
    /* ===== SIMPLE PROFESSIONAL DASHBOARD ===== */
    :root {
        --primary: #0053C5;
        --primary-dark: #003d94;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --orange: #f97316;
        --gray-bg: #f8f9fa;
        --gray-text: #6c757d;
        --dark-text: #212529;
    }

    body {
        background-color: var(--gray-bg);
    }

    /* === USER SECTION === */
    #user-section {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        padding: 20px 16px;
        margin: 0 -16px 20px -16px;
    }

    #user-detail {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    #user-detail .avatar img {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        border: 3px solid rgba(255, 255, 255, 0.3);
        object-fit: cover;
    }

    #user-info h2 {
        font-size: 18px;
        font-weight: 700;
        color: white;
        margin: 0 0 4px 0;
    }

    #user-info span {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.9);
        font-weight: 500;
    }

    /* === HEADER === */
    .appHeader.bg-primary {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%) !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .greeting {
        font-size: 15px;
        font-weight: 600;
    }

    .logo-app {
        display: flex;
        align-items: center;
    }

    .headerButton.logout {
        color: white;
        background: none;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        transition: background 0.2s;
    }

    .headerButton.logout:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .headerButton.logout ion-icon {
        font-size: 22px;
    }

    /* === MENU SECTION === */
    #menu-section {
        margin-bottom: 20px;
    }

    #menu-section .card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    #menu-section .card-body {
        padding: 16px;
    }

    .list-menu {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
    }

    .item-menu {
        text-align: center;
    }

    .menu-icon a {
        width: 54px;
        height: 54px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 8px;
        border-radius: 14px;
        background: #f8f9fa;
        transition: transform 0.2s;
    }

    .menu-icon a:active {
        transform: scale(0.95);
    }

    .menu-icon a ion-icon {
        font-size: 26px;
    }

    .menu-icon a.green {
        color: var(--success);
    }

    .menu-icon a.danger {
        color: var(--danger);
    }

    .menu-icon a.warning {
        color: var(--warning);
    }

    .menu-icon a.orange {
        color: var(--orange);
    }

    .menu-name span {
        font-size: 12px;
        font-weight: 600;
        color: var(--dark-text);
    }

    /* === PRESENCE SECTION === */
    #presence-section {
        margin-bottom: 20px;
    }

    .todaypresence .card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .gradasigreen {
        background: linear-gradient(135deg, var(--success) 0%, #059669 100%);
    }

    .gradasired {
        background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);
    }

    .presencecontent {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .iconpresence {
        width: 46px;
        height: 46px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
    }

    .iconpresence ion-icon {
        font-size: 26px;
        color: white;
    }

    .iconpresence img {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        object-fit: cover;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .presencedetail h4 {
        font-size: 12px;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.95);
        margin: 0 0 4px 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .presencedetail span {
        font-size: 17px;
        font-weight: 700;
        color: white;
    }

    /* === REKAP PRESENSI === */
    #rekappresensi {
        background: white;
        border-radius: 16px;
        padding: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        margin-bottom: 20px;
    }

    #rekappresensi h5 {
        font-size: 15px;
        font-weight: 700;
        color: var(--dark-text);
        margin-bottom: 12px;
    }

    #rekappresensi .card {
        border-radius: 12px;
        border: 1px solid #e9ecef;
        box-shadow: none;
        transition: all 0.2s;
    }

    #rekappresensi .card:active {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    #rekappresensi .card-body {
        padding: 12px 8px !important;
        position: relative;
    }

    #rekappresensi .badge {
        position: absolute;
        top: 6px;
        right: 6px;
        font-size: 10px;
        font-weight: 700;
        padding: 3px 7px;
        border-radius: 6px;
        min-width: 20px;
    }

    #rekappresensi ion-icon {
        font-size: 28px !important;
        margin-bottom: 6px;
    }

    #rekappresensi span {
        font-size: 12px !important;
        font-weight: 600 !important;
        color: var(--dark-text);
        display: block;
    }

    /* === TABS === */
    .presencetab {
        background: white;
        border-radius: 16px;
        padding: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        margin-bottom: 100px;
    }

    .nav-tabs.style1 {
        border: none;
        background: #f8f9fa;
        padding: 4px;
        border-radius: 10px;
        margin-bottom: 16px;
    }

    .nav-tabs.style1 .nav-item {
        flex: 1;
    }

    .nav-tabs.style1 .nav-link {
        border: none;
        background: transparent;
        color: var(--gray-text);
        font-weight: 600;
        font-size: 13px;
        padding: 8px 12px;
        border-radius: 8px;
        transition: all 0.2s;
        text-align: center;
    }

    .nav-tabs.style1 .nav-link.active {
        background: white;
        color: var(--primary);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    /* === LIST VIEW === */
    .listview.image-listview {
        background: transparent;
    }

    .listview.image-listview>li {
        background: #f8f9fa;
        border-radius: 12px;
        margin-bottom: 10px;
        border: 1px solid #e9ecef;
    }

    .listview.image-listview .item {
        padding: 12px;
    }

    .listview.image-listview .icon-box {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        overflow: hidden;
        background: var(--primary);
    }

    .listview.image-listview .icon-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .listview.image-listview .in>div {
        font-size: 13px;
        font-weight: 600;
        color: var(--dark-text);
        margin-bottom: 4px;
    }

    .listview.image-listview .badge {
        font-size: 11px;
        font-weight: 600;
        padding: 5px 10px;
        border-radius: 6px;
        margin-right: 4px;
    }

    .listview.image-listview .badge-success {
        background: var(--success);
    }

    .listview.image-listview .badge-danger {
        background: var(--danger);
    }

    .listview.image-listview .image {
        width: 46px;
        height: 46px;
        border-radius: 10px;
        border: 2px solid #e9ecef;
    }

    .listview.image-listview .text-muted {
        font-size: 11px;
        color: var(--gray-text) !important;
    }

    /* === LOGOUT MODAL === */
    .logout-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    .logout-modal.active {
        display: flex;
    }

    .logout-modal-content {
        background: white;
        border-radius: 20px;
        padding: 28px;
        max-width: 320px;
        width: 90%;
        text-align: center;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .logout-modal-icon {
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }

    .logout-modal-icon ion-icon {
        font-size: 32px;
        color: white;
    }

    .logout-modal-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--dark-text);
        margin-bottom: 8px;
    }

    .logout-modal-text {
        font-size: 14px;
        color: var(--gray-text);
        margin-bottom: 24px;
    }

    .logout-modal-buttons {
        display: flex;
        gap: 10px;
    }

    .btn-logout-cancel,
    .btn-logout-confirm {
        flex: 1;
        padding: 12px;
        border: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-logout-cancel {
        background: #f8f9fa;
        color: var(--gray-text);
    }

    .btn-logout-cancel:active {
        background: #e9ecef;
    }

    .btn-logout-confirm {
        background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);
        color: white;
    }

    .btn-logout-confirm:active {
        opacity: 0.9;
    }

    /* === RESPONSIVE === */
    @media (max-width: 375px) {
        .list-menu {
            gap: 8px;
        }

        .menu-icon a {
            width: 48px;
            height: 48px;
        }

        .menu-icon a ion-icon {
            font-size: 24px;
        }

        .menu-name span {
            font-size: 11px;
        }
    }
</style>

<div class="section" id="user-section">
    <div id="user-detail" style="display: flex; align-items: center; padding: 20px; border-radius: 20px; margin: 0 16px;">
        <div class="avatar" style="position: relative;">
            @if (!empty(Auth::guard('karyawan')->user()->foto))
            @php
            $path = Storage::url('uploads/karyawan/' . Auth::guard('karyawan')->user()->foto);
            @endphp
            <img src="{{ url($path) }}" alt="avatar" class="imaged w64 rounded" style="border: 3px solid #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
            @else
            <img src="assets/img/sample/avatar/avatar1.jpg" alt="avatar" class="imaged w64 rounded" style="border: 3px solid #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
            @endif
            <div style="position: absolute; bottom: 0; right: 0; width: 18px; height: 18px; background: #10b981; border: 3px solid #fff; border-radius: 50%;"></div>
        </div>

        <div id="user-info" style="margin-left: 16px; flex: 1;">
            <h2 id="user-name" style="margin: 0; font-size: 18px; font-weight: 700; color: #fff; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                {{ Auth::guard('karyawan')->user()->nama_lengkap }}
            </h2>
            <span id="user-role" style="font-size: 13px; color: rgba(255,255,255,0.9); font-weight: 500; display: flex; align-items: center; margin-top: 4px;">
                <ion-icon name="briefcase-outline" style="font-size: 14px; margin-right: 4px;"></ion-icon>
                {{ Auth::guard('karyawan')->user()->jabatan }}
            </span>
        </div>

        <div style="margin-left: auto;">
            <form method="POST" action="/proseslogout" style="display: inline;">
                @csrf
                <a href="#" onclick="event.preventDefault(); this.closest('form').submit();"
                    style="display: flex; align-items: center; justify-content: center; width: 44px; height: 44px; background: rgba(255,255,255,0.2); border-radius: 12px; backdrop-filter: blur(10px); transition: all 0.3s ease;"
                    onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='scale(1.05)';"
                    onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='scale(1)';">
                    <ion-icon name="log-out-outline" style="font-size: 24px; color: #fff;"></ion-icon>
                </a>
            </form>
        </div>
    </div>
</div>

@section('header')
<div class="logout-modal" id="logoutModal">
    <div class="logout-modal-content">
        <div class="logout-modal-icon">
            <ion-icon name="log-out-outline"></ion-icon>
        </div>
        <h3 class="logout-modal-title">Konfirmasi Logout</h3>
        <p class="logout-modal-text">Apakah Anda yakin ingin keluar dari aplikasi?</p>
        <div class="logout-modal-buttons">
            <button type="button" class="btn-logout-cancel" onclick="closeLogoutModal()">Batal</button>
            <button type="button" class="btn-logout-confirm" onclick="doLogout()">Ya, Keluar</button>
        </div>
    </div>
</div>

@endsection

<div class="section" id="menu-section">
    <div class="card">
        <div class="card-body">
            <div class="list-menu">
                <div class="item-menu">
                    <div class="menu-icon">
                        <a href="/editprofile" class="green">
                            <ion-icon name="person-sharp"></ion-icon>
                        </a>
                    </div>
                    <div class="menu-name">
                        <span>Profil</span>
                    </div>
                </div>
                <div class="item-menu">
                    <div class="menu-icon">
                        <a href="/presensi/izin" class="danger">
                            <ion-icon name="calendar-number"></ion-icon>
                        </a>
                    </div>
                    <div class="menu-name">
                        <span>Izin</span>
                    </div>
                </div>
                <div class="item-menu">
                    <div class="menu-icon">
                        <a href="/presensi/histori" class="warning">
                            <ion-icon name="document-text"></ion-icon>
                        </a>
                    </div>
                    <div class="menu-name">
                        <span>Histori</span>
                    </div>
                </div>
                <div class="item-menu">
                    <div class="menu-icon">
                        <a href="#" class="orange">
                            <ion-icon name="location"></ion-icon>
                        </a>
                    </div>
                    <div class="menu-name">
                        <span>Lokasi</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="section" id="presence-section">
    <div class="todaypresence">
        <div class="row">
            <div class="col-6">
                <div class="card gradasigreen">
                    <div class="card-body">
                        <div class="presencecontent">
                            <div class="iconpresence">
                                @if ($presensihariini != null)
                                @php
                                $path = Storage::url('public/uploads/absensi/' . $presensihariini->foto_in);
                                @endphp
                                <img src="{{ url($path) }}" alt="">
                                @else
                                <ion-icon name="camera"></ion-icon>
                                @endif
                            </div>
                            <div class="presencedetail">
                                <h4 class="presencetitle">Masuk</h4>
                                <span>{{ $presensihariini != null ? $presensihariini->jam_in : 'Belum Absen' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card gradasired">
                    <div class="card-body">
                        <div class="presencecontent">
                            <div class="iconpresence">
                                @if ($presensihariini != null && $presensihariini->jam_out != null)
                                @php
                                $path = Storage::url('public/uploads/absensi/' . $presensihariini->foto_out);
                                @endphp
                                <img src="{{ url($path) }}" alt="">
                                @else
                                <ion-icon name="camera"></ion-icon>
                                @endif
                            </div>
                            <div class="presencedetail">
                                <h4 class="presencetitle">Pulang</h4>
                                <span>{{ $presensihariini != null && $presensihariini->jam_out != null ? $presensihariini->jam_out : 'Belum Absen' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="rekappresensi">
        <h5>Rekap Presensi Bulan {{ $namabulan[$bulanini] }} Tahun {{ $tahunini }}</h5>
        <div class="row">
            <div class="col-3">
                <div class="card">
                    <div class="card-body text-center">
                        <span class="badge bg-primary">{{ $rekappresensi->jmlhadir }}</span>
                        <ion-icon name="accessibility-outline" class="text-primary"></ion-icon>
                        <span>Hadir</span>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-body text-center">
                        <span class="badge bg-success">0</span>
                        <ion-icon name="newspaper-outline" class="text-success"></ion-icon>
                        <span>Izin</span>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-body text-center">
                        <span class="badge bg-warning">0</span>
                        <ion-icon name="medkit-outline" class="text-warning"></ion-icon>
                        <span>Sakit</span>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-body text-center">
                        <span class="badge bg-danger">0</span>
                        <ion-icon name="alarm-outline" class="text-danger"></ion-icon>
                        <span>Telat</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="presencetab">
        <div class="tab-pane fade show active" id="pilled" role="tabpanel">
            <ul class="nav nav-tabs style1" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#home" role="tab">
                        Bulan Ini
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#profile" role="tab">
                        Leaderboard
                    </a>
                </li>
            </ul>
        </div>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="home" role="tabpanel">
                <ul class="listview image-listview">
                    @foreach ($historibulanini as $d)
                    @php
                    $path = Storage::url('uploads/absensi/' . $d->foto_in);
                    @endphp
                    <li>
                        <div class="item">
                            <div class="icon-box bg-primary">
                                <img src="{{ url($path) }}" alt="">
                            </div>
                            <div class="in">
                                <div>{{ date('d-m-Y', strtotime($d->tgl_presensi)) }}</div>
                                <span class="badge badge-success">{{ $d->jam_in }}</span>
                                <span class="badge badge-danger">{{ $presensihariini != null && $d->jam_out != null ? $d->jam_out : 'Belum Absen' }}</span>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>

            <div class="tab-pane fade" id="profile" role="tabpanel">
                <ul class="listview image-listview">
                    @foreach ($leaderboard as $d)
                    <li>
                        <div class="item">
                            <img src="{{ asset('assets/img/sample/avatar/avatar1.jpg') }}" alt="image" class="image">
                            <div class="in">
                                <div>
                                    <b>{{ $d->nama_lengkap }}</b><br>
                                    <small class="text-muted">{{ $d->jabatan }}</small>
                                </div>
                                <span class="badge {{ $d->jam_in < '07:00' ? 'bg-success' : 'bg-danger' }}">{{ $d->jam_in }}</span>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
<script>
    function confirmLogout() {
        document.getElementById('logoutModal').classList.add('active');
    }

    function closeLogoutModal() {
        document.getElementById('logoutModal').classList.remove('active');
    }

    function doLogout() {
        document.getElementById('logoutForm').submit();
    }

    document.addEventListener('click', function(event) {
        const modal = document.getElementById('logoutModal');
        if (event.target === modal) {
            closeLogoutModal();
        }
    });
</script>