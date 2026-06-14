@extends('admin.layouts.admin')

@section('page-title', 'Monitoring Presensi')

@push('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    .filter-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 25px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }
    .table-container {
        background: white;
        border-radius: 10px;
        padding: 0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }
    .table th {
        background-color: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
        color: #475569;
        font-weight: 600;
        padding: 15px;
        text-transform: uppercase;
        font-size: 13px;
    }
    .table td {
        padding: 15px;
        vertical-align: middle;
        border-bottom: 1px solid #e2e8f0;
    }
    .foto-karyawan {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }
    .foto-presensi {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        object-fit: cover;
        cursor: pointer;
        transition: transform 0.2s;
    }
    .foto-presensi:hover {
        transform: scale(1.1);
    }
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .status-hadir {
        background: #dcfce7;
        color: #166534;
    }
    .status-telat {
        background: #fee2e2;
        color: #991b1b;
    }
    .status-izin {
        background: #fef9c3;
        color: #854d0e;
    }
    .status-sakit {
        background: #e0e7ff;
        color: #3730a3;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Filter Section -->
    <div class="filter-card">
        <form action="#" method="GET" id="formFilter" class="row align-items-end">
            <div class="col-md-4">
                <label for="tanggal" class="form-label text-muted">Tanggal Presensi</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ date('Y-m-d') }}">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-primary w-100" id="btn-search">
                    <i class="mdi mdi-magnify"></i> Cari Data
                </button>
            </div>
        </form>
    </div>

    <!-- Data Section -->
    <div class="table-container">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Karyawan</th>
                        <th>Jadwal / Shift</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Lokasi</th>
                    </tr>
                </thead>
                <tbody id="loadpresensi">
                    <!-- Data will be loaded here via AJAX -->
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2 mb-0 text-muted">Memuat data presensi...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Map -->
<div class="modal fade" id="modalMap" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title"><i class="mdi mdi-map-marker"></i> Lokasi Presensi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" id="loadmap">
                <!-- Map will be loaded here via AJAX -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    $(document).ready(function() {
        // Load initial data
        loadpresensi();

        // Search button click
        $('#btn-search').click(function(e) {
            e.preventDefault();
            loadpresensi();
        });

        // Load presensi function
        function loadpresensi() {
            var tanggal = $('#tanggal').val();
            
            $.ajax({
                type: 'POST',
                url: '/panel/monitoring/getpresensi',
                data: {
                    _token: "{{ csrf_token() }}",
                    tanggal: tanggal
                },
                cache: false,
                success: function(respond) {
                    $('#loadpresensi').html(respond);
                },
                error: function(err) {
                    $('#loadpresensi').html('<tr><td colspan="5" class="text-center text-danger py-4"><i class="mdi mdi-alert-circle text-danger fs-3 d-block mb-2"></i>Gagal memuat data</td></tr>');
                }
            });
        }
    });

    // Function to show map
    function tampilkanpeta(id) {
        $.ajax({
            type: 'POST',
            url: '/panel/monitoring/showmap',
            data: {
                _token: "{{ csrf_token() }}",
                id: id
            },
            cache: false,
            success: function(respond) {
                $('#loadmap').html(respond);
                $('#modalMap').modal('show');
            }
        });
    }
</script>
@endpush
