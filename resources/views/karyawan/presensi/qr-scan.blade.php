@extends('karyawan.layouts.presensi')

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
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

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

<!-- Map Section -->
<div class="section mt-2 mb-2">
    <div class="card" style="border-radius: 20px; border: 1px solid rgba(0, 83, 197, 0.05); box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);">
        <div class="card-body">
            <div class="map-title" style="font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                <ion-icon name="location-outline" style="font-size: 20px; color: #0053C5;"></ion-icon>
                Lokasi Anda
            </div>
            <div id="map" style="height: 250px; border-radius: 12px; overflow: hidden; z-index: 1;"></div>
            <div class="location-info" style="margin-top: 12px; padding: 12px; background: linear-gradient(135deg, rgba(0, 83, 197, 0.05) 0%, rgba(46, 124, 230, 0.05) 100%); border-radius: 10px; border: 1px solid rgba(0, 83, 197, 0.2);">
                <p style="margin: 0; font-size: 12px; color: #64748b; display: flex; align-items: center; gap: 6px;">
                    <ion-icon name="business-outline" style="font-size: 16px; color: #0053C5;"></ion-icon>
                    <strong style="color: #0053C5; font-weight: 600;">Radius Kantor:</strong> {{ $lok_kantor->radius_cabang ?? '0' }} meter
                </p>
            </div>
        </div>
    </div>
</div>

<audio id="notifikasi_in" src="{{ asset('assets/sound/notifikasi_in.mp3') }}" preload="auto"></audio>
<audio id="notifikasi_out" src="{{ asset('assets/sound/notifikasi_out.mp3') }}" preload="auto"></audio>
@endsection

@push('myscript')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    var notifikasi_in = document.getElementById('notifikasi_in');
    var notifikasi_out = document.getElementById('notifikasi_out');

    // Dapatkan Lokasi Dulu
    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(successCallback, errorCallback, {
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
        
        // Initialize Map
        try {
            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;
            
            if (typeof window.map === 'undefined') {
                window.map = L.map('map').setView([latitude, longitude], 17);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap'
                }).addTo(window.map);

                // User Marker
                var userIcon = L.divIcon({
                    className: 'custom-div-icon',
                    html: '<div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); width: 40px; height: 40px; border-radius: 50%; border: 4px solid white; box-shadow: 0 3px 10px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center;"><ion-icon name="person" style="color: white; font-size: 22px;"></ion-icon></div>',
                    iconSize: [40, 40],
                    iconAnchor: [20, 20]
                });

                window.marker = L.marker([latitude, longitude], { icon: userIcon }).addTo(window.map);
                window.marker.bindPopup('<strong style="color: #10b981;">Lokasi Anda</strong>').openPopup();

                // Office Location
                var lok_kantor = "{{ $lok_kantor->lokasi_cabang ?? '' }}";
                if(lok_kantor) {
                    var lok = lok_kantor.split(",");
                    if(lok.length >= 2) {
                        var lat_kantor = parseFloat(lok[0]);
                        var long_kantor = parseFloat(lok[1]);
                        var radius = {{ $lok_kantor->radius_cabang ?? 0 }};
                        
                        var circle = L.circle([lat_kantor, long_kantor], {
                            color: '#0053C5',
                            fillColor: '#0053C5',
                            fillOpacity: 0.15,
                            radius: radius,
                            weight: 2,
                            dashArray: '5, 5'
                        }).addTo(window.map);

                        var officeIcon = L.divIcon({
                            className: 'custom-div-icon',
                            html: '<div style="background: linear-gradient(135deg, #0053C5 0%, #003d94 100%); width: 40px; height: 40px; border-radius: 50%; border: 4px solid white; box-shadow: 0 3px 10px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center;"><ion-icon name="business" style="color: white; font-size: 22px;"></ion-icon></div>',
                            iconSize: [40, 40],
                            iconAnchor: [20, 20]
                        });

                        var officeMarker = L.marker([lat_kantor, long_kantor], { icon: officeIcon }).addTo(window.map);
                        officeMarker.bindPopup('<strong style="color: #0053C5;">Kantor</strong><br><small>Radius: ' + radius + 'm</small>');

                        var group = L.featureGroup([window.marker, officeMarker, circle]);
                        window.map.fitBounds(group.getBounds().pad(0.1));
                    }
                }
                
                // Start QR Scanner only once when map is initialized
                startQRScanner();
            } else {
                if (window.marker) {
                    window.marker.setLatLng([latitude, longitude]);
                    window.map.setView([latitude, longitude]);
                }
            }
        } catch (e) {
            console.error('Map initialization error:', e);
        }

        // startQRScanner is now called inside map initialization block
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
            url: '{{ route("presensi.storeQr") }}',
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
                        window.location.href = '{{ route("dashboard") }}';
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
