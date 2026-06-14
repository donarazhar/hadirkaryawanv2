@extends('layouts.presensi')

@section('header')
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="{{ route('dashboard') }}" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Scan QR Code</div>
    <div class="right"></div>
</div>
@endsection

@section('content')
<div class="section full mt-2">
    <div class="section-title">Arahkan Kamera ke QR Code Cabang</div>
    <div class="wide-block pt-2 pb-2">
        <div id="reader" width="100%"></div>
        <input type="hidden" id="lokasi">
    </div>
    <div class="row mt-2">
        <div class="col-12 text-center">
            <p class="text-muted" id="status-text">Menunggu lokasi...</p>
        </div>
    </div>
</div>

<audio id="notifikasi_in" src="{{ asset('assets/sound/notifikasi_in.mp3') }}" preload="auto"></audio>
<audio id="notifikasi_out" src="{{ asset('assets/sound/notifikasi_out.mp3') }}" preload="auto"></audio>
@endsection

@push('myscript')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    var notifikasi_in = document.getElementById('notifikasi_in');
    var notifikasi_out = document.getElementById('notifikasi_out');

    // Dapatkan Lokasi Dulu
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(successCallback, errorCallback, {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
        });
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Geolocation tidak didukung oleh browser Anda.',
        });
    }

    function successCallback(position) {
        $("#lokasi").val(position.coords.latitude + "," + position.coords.longitude);
        $("#status-text").text("Lokasi ditemukan. Silakan scan QR Code.");
        
        // Start QR Scanner
        startQRScanner();
    }

    function errorCallback(error) {
        $("#status-text").text("Gagal mendapatkan lokasi. Aktifkan GPS.");
        Swal.fire({
            icon: 'error',
            title: 'Lokasi Tidak Terdeteksi',
            text: 'Pastikan GPS Anda aktif dan beri izin browser untuk mengakses lokasi.'
        });
    }

    let isScanning = false;

    function startQRScanner() {
        const html5QrCode = new Html5Qrcode("reader");
        const config = { fps: 10, qrbox: { width: 250, height: 250 } };

        html5QrCode.start({ facingMode: "environment" }, config, (decodedText, decodedResult) => {
            if(isScanning) return; // Mencegah double scan
            
            isScanning = true;
            console.log(`Scan result: ${decodedText}`);
            
            // Hentikan scanner sementara
            html5QrCode.stop().then((ignore) => {
                // Kirim data ke server
                prosesAbsen(decodedText);
            }).catch((err) => {
                console.log(err);
            });
            
        }, (errorMessage) => {
            // parse error, ignore it.
        }).catch((err) => {
            console.log(`Error starting scanner: ${err}`);
        });
    }

    function prosesAbsen(qrCodeData) {
        var lokasi = $("#lokasi").val();
        
        $.ajax({
            type: 'POST',
            url: '/presensi/store-qr',
            data: {
                _token: "{{ csrf_token() }}",
                qr_code: qrCodeData,
                lokasi: lokasi
            },
            cache: false,
            success: function(respond) {
                if (respond.success) {
                    // Cek message untuk mengetahui masuk atau pulang
                    if (respond.message.includes('Masuk')) {
                        notifikasi_in.play();
                    } else {
                        notifikasi_out.play();
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: respond.message,
                        timer: 3000
                    }).then(() => {
                        window.location.href = '/dashboard';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: respond.message,
                    }).then(() => {
                        // Restart scanner
                        isScanning = false;
                        startQRScanner();
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan sistem.'
                }).then(() => {
                    isScanning = false;
                    startQRScanner();
                });
            }
        });
    }
</script>
@endpush
