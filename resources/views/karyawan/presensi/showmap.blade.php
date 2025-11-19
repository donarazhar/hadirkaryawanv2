<style>
    .map-container {
        background: white;
        border-radius: 12px;
        overflow: hidden;
    }

    #map-detail {
        height: 400px;
        border-radius: 12px 12px 0 0;
    }

    .map-info-card {
        padding: 20px;
        background: white;
    }

    .map-info-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding-bottom: 16px;
        border-bottom: 2px solid #f1f5f9;
        margin-bottom: 16px;
    }

    .map-info-avatar {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        object-fit: cover;
        border: 3px solid #0053C5;
    }

    .map-info-user h6 {
        margin: 0 0 4px 0;
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
    }

    .map-info-user p {
        margin: 0;
        font-size: 12px;
        color: #64748b;
    }

    .map-info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    .map-info-item {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 12px;
        border-radius: 10px;
    }

    .map-info-label {
        font-size: 11px;
        color: #64748b;
        font-weight: 600;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .map-info-label ion-icon {
        font-size: 14px;
        color: #0053C5;
    }

    .map-info-value {
        font-size: 13px;
        font-weight: 700;
        color: #1e293b;
    }

    .map-stats {
        display: flex;
        gap: 8px;
        margin-top: 16px;
    }

    .map-stat-item {
        flex: 1;
        padding: 10px;
        background: linear-gradient(135deg, rgba(0, 83, 197, 0.05) 0%, rgba(46, 124, 230, 0.05) 100%);
        border: 1px solid rgba(0, 83, 197, 0.2);
        border-radius: 10px;
        text-align: center;
    }

    .map-stat-value {
        font-size: 16px;
        font-weight: 700;
        color: #0053C5;
        margin-bottom: 2px;
    }

    .map-stat-label {
        font-size: 10px;
        color: #64748b;
        font-weight: 600;
    }

    .distance-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 6px 12px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
    }

    .distance-badge.warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }

    .distance-badge.danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    }
</style>

<div class="map-container">
    <!-- Map -->
    <div id="map-detail"></div>

    <!-- Info Card -->
    <div class="map-info-card">
        <!-- Header -->
        <div class="map-info-header">
            @php
            $foto_in_path = !empty($presensi->foto_in) ? 'uploads/absensi/' . $presensi->foto_in : null;
            $foto_in_exists = $foto_in_path && Storage::disk('public')->exists($foto_in_path);
            @endphp

            @if($foto_in_exists)
            <img src="{{ Storage::url($foto_in_path) }}" class="map-info-avatar" alt="Foto"
                onerror="this.src='{{ asset('assets/img/sample/avatar/noprofile.png') }}'">
            @else
            <div class="map-info-avatar" style="background: linear-gradient(135deg, #0053C5 0%, #2E7CE6 100%); display: flex; align-items: center; justify-content: center;">
                <ion-icon name="person" style="font-size: 24px; color: white;"></ion-icon>
            </div>
            @endif
            <div class="map-info-user">
                <h6>{{ $presensi->nama_lengkap }}</h6>
                <p>NIK: {{ $presensi->nik }}</p>
            </div>
        </div>

        <!-- Info Grid -->
        <div class="map-info-grid">
            <div class="map-info-item">
                <div class="map-info-label">
                    <ion-icon name="calendar-outline"></ion-icon>
                    Tanggal
                </div>
                <div class="map-info-value">
                    {{ \Carbon\Carbon::parse($presensi->tgl_presensi)->isoFormat('DD MMM Y') }}
                </div>
            </div>
            <div class="map-info-item">
                <div class="map-info-label">
                    <ion-icon name="time-outline"></ion-icon>
                    Jam Masuk
                </div>
                <div class="map-info-value">
                    {{ $presensi->jam_in }}
                </div>
            </div>
            <div class="map-info-item">
                <div class="map-info-label">
                    <ion-icon name="location-outline"></ion-icon>
                    Koordinat
                </div>
                <div class="map-info-value" style="font-size: 11px;">
                    {{ $presensi->lokasi_in }}
                </div>
            </div>
            <div class="map-info-item">
                <div class="map-info-label">
                    <ion-icon name="business-outline"></ion-icon>
                    Jarak
                </div>
                <div class="map-info-value">
                    <span class="distance-badge" id="distance-badge">
                        <ion-icon name="navigate-outline"></ion-icon>
                        <span id="distance-text">Menghitung...</span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="map-stats">
            <div class="map-stat-item">
                <div class="map-stat-value">
                    {{ $cabang ? $cabang->radius_cabang : '0' }}m
                </div>
                <div class="map-stat-label">RADIUS KANTOR</div>
            </div>
            <div class="map-stat-item">
                <div class="map-stat-value">
                    {{ $cabang ? $cabang->nama_cabang : 'N/A' }}
                </div>
                <div class="map-stat-label">LOKASI KANTOR</div>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>



<script>
    // Parse lokasi presensi
    var lokasi = "{{ $presensi->lokasi_in }}";
    var lok = lokasi.split(",");
    var latitude = parseFloat(lok[0]);
    var longitude = parseFloat(lok[1]);

    var lokasi_kantor = "{{ $lokasi_kantor }}";
    var lok_kantor = lokasi_kantor.split(",");
    var lat_kantor = parseFloat(lok_kantor[0]);
    var long_kantor = parseFloat(lok_kantor[1]);
    var radius = {{ $lok_kantor->radius_cabang }};

    // Initialize map
    var map = L.map('map-detail').setView([latitude, longitude], 17);

    // Tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    // User location marker
    var userIcon = L.divIcon({
        className: 'custom-div-icon',
        html: `<div style='background: linear-gradient(135deg, #10b981 0%, #059669 100%); 
               width: 40px; height: 40px; border-radius: 50%; 
               border: 4px solid white; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4); 
               display: flex; align-items: center; justify-content: center;'>
               <ion-icon name='person' style='color: white; font-size: 22px;'></ion-icon>
               </div>`,
        iconSize: [40, 40],
        iconAnchor: [20, 20]
    });

    var userMarker = L.marker([latitude, longitude], {
        icon: userIcon
    }).addTo(map);
    userMarker.bindPopup(`
        <div style="text-align: center; padding: 8px; min-width: 150px;">
            <strong style="color: #10b981; font-size: 14px;">Lokasi Presensi</strong><br>
            <small style="color: #64748b;">{{ $presensi->nama_lengkap }}</small><br>
            <small style="color: #0053C5; font-weight: 600;">{{ $presensi->jam_in }}</small>
        </div>
    `).openPopup();

    // Office circle
    var circle = L.circle([lat_kantor, long_kantor], {
        color: '#0053C5',
        fillColor: '#0053C5',
        fillOpacity: 0.1,
        radius: radius,
        weight: 2,
        dashArray: '5, 5'
    }).addTo(map);

    // Office marker
    var officeIcon = L.divIcon({
        className: 'custom-div-icon',
        html: `<div style='background: linear-gradient(135deg, #0053C5 0%, #003d94 100%); 
               width: 40px; height: 40px; border-radius: 50%; 
               border: 4px solid white; box-shadow: 0 4px 15px rgba(0, 83, 197, 0.4); 
               display: flex; align-items: center; justify-content: center;'>
               <ion-icon name='business' style='color: white; font-size: 22px;'></ion-icon>
               </div>`,
        iconSize: [40, 40],
        iconAnchor: [20, 20]
    });

    var officeMarker = L.marker([lat_kantor, long_kantor], {
        icon: officeIcon
    }).addTo(map);
    officeMarker.bindPopup(`
        <div style="text-align: center; padding: 8px; min-width: 150px;">
            <strong style="color: #0053C5; font-size: 14px;">Kantor</strong><br>
            <small style="color: #64748b;">{{ $cabang ? $cabang->nama_cabang : 'Kantor Pusat' }}</small><br>
            <small style="color: #f59e0b; font-weight: 600;">Radius: ${radius}m</small>
        </div>
    `);

    // Draw line between user and office
    var polyline = L.polyline([
        [latitude, longitude],
        [lat_kantor, long_kantor]
    ], {
        color: '#0053C5',
        weight: 2,
        opacity: 0.6,
        dashArray: '10, 10'
    }).addTo(map);

    // Calculate distance
    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371e3; // Earth radius in meters
        const φ1 = lat1 * Math.PI / 180;
        const φ2 = lat2 * Math.PI / 180;
        const Δφ = (lat2 - lat1) * Math.PI / 180;
        const Δλ = (lon2 - lon1) * Math.PI / 180;

        const a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
            Math.cos(φ1) * Math.cos(φ2) *
            Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

        return Math.round(R * c);
    }

    var distance = calculateDistance(latitude, longitude, lat_kantor, long_kantor);

    // Update distance text
    document.getElementById('distance-text').textContent = distance + 'm';

    // Update badge color based on distance
    var badge = document.getElementById('distance-badge');
    if (distance <= radius) {
        badge.classList.remove('warning', 'danger');
    } else if (distance <= radius * 1.5) {
        badge.classList.add('warning');
        badge.classList.remove('danger');
    } else {
        badge.classList.add('danger');
        badge.classList.remove('warning');
    }

    // Fit bounds to show all markers
    var group = L.featureGroup([userMarker, officeMarker, circle]);
    map.fitBounds(group.getBounds().pad(0.2));

    // Add scale control
    L.control.scale({
        imperial: false,
        metric: true
    }).addTo(map);
</script>