<style>
    #map {
        height: 400px;
        width: 100%;
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;
    }
</style>

<div id="map"></div>

<script>
    // Inisialisasi peta
    var map = L.map('map').setView([-6.200000, 106.816666], 13); // Default view (Jakarta)

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    @if ($presensi->lokasi_in)
        var lokasiIn = "{{ $presensi->lokasi_in }}";
        var locIn = lokasiIn.split(",");
        var latIn = parseFloat(locIn[0]);
        var lngIn = parseFloat(locIn[1]);

        var markerIn = L.marker([latIn, lngIn]).addTo(map);
        markerIn.bindPopup("<b>Lokasi Check In</b><br>{{ $presensi->nama_lengkap }}").openPopup();
        map.setView([latIn, lngIn], 16);
    @endif

    @if ($presensi->lokasi_out)
        var lokasiOut = "{{ $presensi->lokasi_out }}";
        var locOut = lokasiOut.split(",");
        var latOut = parseFloat(locOut[0]);
        var lngOut = parseFloat(locOut[1]);

        // Gunakan icon marker yang berbeda untuk Check Out (Merah)
        var redIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        var markerOut = L.marker([latOut, lngOut], {icon: redIcon}).addTo(map);
        markerOut.bindPopup("<b>Lokasi Check Out</b><br>{{ $presensi->nama_lengkap }}");
        
        // Jika ada lokasi In dan Out, set view di antara keduanya
        @if ($presensi->lokasi_in)
            var group = new L.featureGroup([markerIn, markerOut]);
            map.fitBounds(group.getBounds().pad(0.5));
        @else
            map.setView([latOut, lngOut], 16);
        @endif
    @endif
    
    // Fix untuk Leaflet di dalam Bootstrap Modal
    setTimeout(function() {
        map.invalidateSize();
    }, 100);
</script>
