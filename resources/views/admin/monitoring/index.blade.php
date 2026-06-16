@extends('admin.layouts.admin')

@section('title', 'Monitoring Presensi')
@section('page-title', 'Monitoring Presensi')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    :root {
        --blue:       #2563EB;
        --blue-dark:  #1D4ED8;
        --blue-soft:  #EFF6FF;
        --blue-mid:   #BFDBFE;
        --green:      #10B981;
        --green-soft: #ECFDF5;
        --amber:      #F59E0B;
        --amber-soft: #FFFBEB;
        --red:        #EF4444;
        --red-soft:   #FEF2F2;
        --purple:     #8B5CF6;
        --purple-soft:#F5F3FF;
        --slate-900:  #111827;
        --slate-700:  #374151;
        --slate-600:  #4B5563;
        --slate-400:  #9CA3AF;
        --slate-200:  #E5E7EB;
        --slate-100:  #F3F4F6;
        --slate-50:   #F9FAFB;
        --white:      #FFFFFF;
        --shadow:     0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
        --shadow-md:  0 4px 12px rgba(0,0,0,0.07);
        --radius:     14px;
        --radius-sm:  10px;
    }

    .mon-wrap { display: flex; flex-direction: column; gap: 20px; }

    /* ── HEADER CARD ── */
    .mon-header {
        background: var(--white);
        border: 1px solid var(--slate-200);
        border-radius: var(--radius);
        padding: 20px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        box-shadow: var(--shadow);
    }

    .mon-header-left { display: flex; align-items: center; gap: 14px; }

    .mon-header-icon {
        width: 46px; height: 46px;
        border-radius: 12px;
        background: var(--blue-soft);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .mon-header-icon i { font-size: 22px; color: var(--blue); }

    .mon-header-title {
        font-size: 17px;
        font-weight: 800;
        color: var(--slate-900);
        letter-spacing: -0.2px;
    }

    .mon-header-sub {
        font-size: 12px;
        color: var(--slate-400);
        margin-top: 2px;
    }

    .live-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--green-soft);
        color: var(--green);
        border: 1px solid #A7F3D0;
        border-radius: 50px;
        padding: 5px 12px;
        font-size: 12px;
        font-weight: 700;
    }

    .live-dot {
        width: 7px; height: 7px;
        border-radius: 50%;
        background: var(--green);
        animation: blink 1.4s infinite;
        flex-shrink: 0;
    }

    @keyframes blink {
        0%, 100% { opacity: 1; }
        50%       { opacity: 0.25; }
    }

    /* ── FILTER CARD ── */
    .filter-card {
        background: var(--white);
        border: 1px solid var(--slate-200);
        border-radius: var(--radius);
        padding: 18px 22px;
        box-shadow: var(--shadow);
    }

    .filter-label {
        font-size: 11px;
        font-weight: 700;
        color: var(--slate-400);
        text-transform: uppercase;
        letter-spacing: 0.6px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .filter-label i { font-size: 14px; color: var(--blue); }

    .filter-row {
        display: flex;
        align-items: flex-end;
        gap: 12px;
        flex-wrap: wrap;
    }

    .filter-group { display: flex; flex-direction: column; gap: 5px; flex: 1; min-width: 160px; }

    .filter-group label {
        font-size: 11px;
        font-weight: 700;
        color: var(--slate-600);
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .filter-input {
        height: 38px;
        border: 1.5px solid var(--slate-200);
        border-radius: 9px;
        padding: 0 12px;
        font-family: 'Inter', sans-serif;
        font-size: 13px;
        color: var(--slate-900);
        background: var(--white);
        outline: none;
        transition: border-color 0.15s, box-shadow 0.15s;
        width: 100%;
    }

    .filter-input:focus {
        border-color: var(--blue);
        box-shadow: 0 0 0 3px rgba(37,99,235,0.10);
    }

    .btn-search {
        height: 38px;
        padding: 0 20px;
        background: var(--blue);
        color: var(--white);
        border: none;
        border-radius: 9px;
        font-family: 'Inter', sans-serif;
        font-size: 13px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        transition: background 0.15s, transform 0.1s;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .btn-search:hover { background: var(--blue-dark); }
    .btn-search:active { transform: scale(0.97); }
    .btn-search i { font-size: 16px; }

    /* ── STATS ROW ── */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
    }

    @media (max-width: 900px) { .stats-row { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 480px) { .stats-row { grid-template-columns: repeat(2, 1fr); } }

    .stat-pill {
        background: var(--white);
        border: 1px solid var(--slate-200);
        border-radius: var(--radius-sm);
        padding: 14px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: var(--shadow);
    }

    .stat-pill-icon {
        width: 38px; height: 38px;
        border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }

    .stat-pill-icon i { font-size: 19px; }

    .stat-pill-val {
        font-size: 20px;
        font-weight: 900;
        color: var(--slate-900);
        line-height: 1;
        letter-spacing: -0.5px;
    }

    .stat-pill-lbl {
        font-size: 11px;
        color: var(--slate-400);
        font-weight: 600;
        margin-top: 2px;
    }

    /* ── TABLE CARD ── */
    .tbl-card {
        background: var(--white);
        border: 1px solid var(--slate-200);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
    }

    .tbl-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 20px;
        border-bottom: 1px solid var(--slate-100);
    }

    .tbl-card-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--slate-900);
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .tbl-card-title i { font-size: 17px; color: var(--blue); }

    .tbl-count {
        font-size: 11px;
        font-weight: 700;
        color: var(--slate-400);
        background: var(--slate-100);
        padding: 3px 10px;
        border-radius: 50px;
    }

    .tbl-wrap { overflow-x: auto; }

    .tbl-wrap table {
        width: 100%;
        border-collapse: collapse;
        min-width: 700px;
    }

    .tbl-wrap thead th {
        padding: 10px 16px;
        background: var(--slate-50);
        font-size: 10.5px;
        font-weight: 700;
        color: var(--slate-400);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid var(--slate-200);
        white-space: nowrap;
    }

    .tbl-wrap tbody td {
        padding: 12px 16px;
        font-size: 13px;
        color: var(--slate-700);
        border-bottom: 1px solid var(--slate-100);
        vertical-align: middle;
    }

    .tbl-wrap tbody tr:last-child td { border-bottom: none; }
    .tbl-wrap tbody tr:hover td { background: var(--slate-50); }

    /* User cell */
    .user-cell { display: flex; align-items: center; gap: 10px; }

    .user-avatar {
        width: 34px; height: 34px;
        border-radius: 9px;
        background: var(--blue-soft);
        display: flex; align-items: center; justify-content: center;
        color: var(--blue);
        font-size: 16px;
        flex-shrink: 0;
        font-weight: 800;
    }

    .user-name { font-size: 13px; font-weight: 700; color: var(--slate-900); }
    .user-sub  { font-size: 11px; color: var(--slate-400); margin-top: 1px; }

    /* Status pills */
    .sp {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 9px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }
    .sp i { font-size: 12px; }
    .sp-hadir   { background: var(--green-soft); color: var(--green); }
    .sp-telat   { background: var(--red-soft);   color: var(--red); }
    .sp-izin    { background: var(--amber-soft);  color: #D97706; }
    .sp-sakit   { background: var(--purple-soft); color: var(--purple); }
    .sp-belum   { background: var(--slate-100);   color: var(--slate-400); }
    .sp-pulang  { background: #FEF3C7; color: #D97706; }

    /* Shift badge */
    .shift-badge {
        display: inline-flex;
        align-items: center;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        background: var(--slate-100);
        color: var(--slate-600);
        margin-bottom: 3px;
    }

    /* Foto presensi */
    .foto-thumb {
        width: 38px; height: 38px;
        border-radius: 8px;
        object-fit: cover;
        cursor: pointer;
        border: 2px solid var(--slate-200);
        transition: border-color 0.15s, transform 0.15s;
        flex-shrink: 0;
    }
    .foto-thumb:hover { border-color: var(--blue); transform: scale(1.08); }

    .time-val {
        font-size: 14px;
        font-weight: 800;
        line-height: 1;
        letter-spacing: -0.3px;
    }
    .time-in  { color: var(--green); }
    .time-out { color: var(--blue); }

    /* Map button */
    .btn-map {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 12px;
        border-radius: 7px;
        font-size: 11.5px;
        font-weight: 700;
        background: var(--blue-soft);
        color: var(--blue);
        border: 1px solid var(--blue-mid);
        cursor: pointer;
        transition: background 0.15s;
        font-family: 'Inter', sans-serif;
    }
    .btn-map:hover { background: var(--blue-mid); }
    .btn-map i { font-size: 14px; }

    /* Loading state */
    .tbl-loading {
        padding: 56px 16px;
        text-align: center;
        color: var(--slate-400);
    }
    .tbl-loading .spinner {
        width: 36px; height: 36px;
        border: 3px solid var(--slate-200);
        border-top-color: var(--blue);
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
        margin: 0 auto 12px;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    .tbl-loading p { font-size: 13px; font-weight: 600; margin: 0; }

    /* Empty state */
    .tbl-empty {
        padding: 56px 16px;
        text-align: center;
        color: var(--slate-400);
    }
    .tbl-empty i { font-size: 44px; display: block; margin-bottom: 10px; color: var(--slate-200); }
    .tbl-empty p { font-size: 13px; font-weight: 600; margin: 0; }

    /* ── MODAL ── */
    .modal-content { border: none; border-radius: 16px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.15); }
    .modal-header-custom {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
        background: var(--white);
        border-bottom: 1px solid var(--slate-200);
    }
    .modal-header-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--slate-900);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .modal-header-title i { font-size: 18px; color: var(--blue); }
    .btn-modal-close {
        width: 30px; height: 30px;
        border-radius: 8px;
        background: var(--slate-100);
        border: none;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        color: var(--slate-600);
        transition: background 0.15s;
    }
    .btn-modal-close:hover { background: var(--slate-200); }
    .btn-modal-close i { font-size: 18px; }

    /* Responsive */
    @media (max-width: 640px) {
        .mon-header { flex-direction: column; align-items: flex-start; gap: 10px; padding: 16px; }
        .filter-row { flex-direction: column; }
        .btn-search { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')
<div class="mon-wrap">

    {{-- ── HEADER ── --}}
    <div class="mon-header">
        <div class="mon-header-left">
            <div class="mon-header-icon">
                <i class="mdi mdi-map-marker-radius"></i>
            </div>
            <div>
                <div class="mon-header-title">Monitoring Presensi</div>
                <div class="mon-header-sub">Pantau kehadiran karyawan secara real-time</div>
            </div>
        </div>
        <div id="auto-refresh-badge" class="live-badge">
            <span class="live-dot"></span>
            Live
        </div>
    </div>

    {{-- ── FILTER ── --}}
    <div class="filter-card">
        <div class="filter-label"><i class="mdi mdi-filter-outline"></i> Filter Data</div>
        <div class="filter-row">
            <div class="filter-group">
                <label>Tanggal Presensi</label>
                <input type="date" id="tanggal" class="filter-input" value="{{ date('Y-m-d') }}">
            </div>
            <button type="button" class="btn-search" id="btn-search">
                <i class="mdi mdi-magnify"></i> Tampilkan Data
            </button>
            <button type="button" class="btn-search" style="background:var(--amber); color:#fff;" data-bs-toggle="modal" data-bs-target="#modalKoreksi">
                <i class="mdi mdi-pencil-outline"></i> Koreksi Manual
            </button>
        </div>
    </div>

    {{-- ── STATS ROW ── --}}
    <div class="stats-row" id="statsRow">
        <div class="stat-pill">
            <div class="stat-pill-icon" style="background:var(--blue-soft);">
                <i class="mdi mdi-account-group" style="color:var(--blue);"></i>
            </div>
            <div>
                <div class="stat-pill-val" id="statTotal">-</div>
                <div class="stat-pill-lbl">Total Data</div>
            </div>
        </div>
        <div class="stat-pill">
            <div class="stat-pill-icon" style="background:var(--green-soft);">
                <i class="mdi mdi-account-check" style="color:var(--green);"></i>
            </div>
            <div>
                <div class="stat-pill-val" id="statHadir">-</div>
                <div class="stat-pill-lbl">Tepat Waktu</div>
            </div>
        </div>
        <div class="stat-pill">
            <div class="stat-pill-icon" style="background:var(--red-soft);">
                <i class="mdi mdi-account-clock" style="color:var(--red);"></i>
            </div>
            <div>
                <div class="stat-pill-val" id="statTelat">-</div>
                <div class="stat-pill-lbl">Terlambat</div>
            </div>
        </div>
        <div class="stat-pill">
            <div class="stat-pill-icon" style="background:var(--amber-soft);">
                <i class="mdi mdi-file-clock" style="color:var(--amber);"></i>
            </div>
            <div>
                <div class="stat-pill-val" id="statIzin">-</div>
                <div class="stat-pill-lbl">Izin / Sakit</div>
            </div>
        </div>
    </div>

    {{-- ── TABLE ── --}}
    <div class="tbl-card">
        <div class="tbl-card-head">
            <div class="tbl-card-title">
                <i class="mdi mdi-table-account"></i>
                Data Presensi
            </div>
            <span class="tbl-count" id="tblCount">Memuat...</span>
        </div>
        <div class="tbl-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Karyawan</th>
                        <th>Shift / Jadwal</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Lokasi</th>
                    </tr>
                </thead>
                <tbody id="loadpresensi">
                    <tr>
                        <td colspan="5">
                            <div class="tbl-loading">
                                <div class="spinner"></div>
                                <p>Memuat data presensi...</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ── MODAL MAP ── --}}
<div class="modal fade" id="modalMap" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header-custom">
                <div class="modal-header-title">
                    <i class="mdi mdi-map-marker"></i> Lokasi Presensi
                </div>
                <button type="button" class="btn-modal-close" data-bs-dismiss="modal">
                    <i class="mdi mdi-close"></i>
                </button>
            </div>
            <div class="modal-body p-0" id="loadmap" style="min-height:300px;">
            </div>
        </div>
    </div>
</div>

{{-- ── MODAL KOREKSI MANUAL ── --}}
<div class="modal fade" id="modalKoreksi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('panel.monitoring.koreksi') }}" method="POST">
                @csrf
                <div class="modal-header-custom">
                    <div class="modal-header-title">
                        <i class="mdi mdi-pencil-outline"></i> Koreksi Presensi Manual
                    </div>
                    <button type="button" class="btn-modal-close" data-bs-dismiss="modal">
                        <i class="mdi mdi-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Karyawan</label>
                        <select name="nik" class="form-select" required>
                            <option value="">Pilih Karyawan</option>
                            @foreach(\App\Models\Karyawan::orderBy('nama_lengkap')->get() as $k)
                                <option value="{{ $k->nik }}">{{ $k->nama_lengkap }} ({{ $k->nik }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">Jam Masuk</label>
                            <input type="time" name="jam_in" class="form-control">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">Jam Pulang</label>
                            <input type="time" name="jam_out" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="h">Hadir (h)</option>
                            <option value="i">Izin (i)</option>
                            <option value="s">Sakit (s)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Koreksi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
$(document).ready(function () {
    loadpresensi();

    $('#btn-search').click(function (e) {
        e.preventDefault();
        countdown = AUTO_REFRESH_SEC;
        loadpresensi();
    });

    $('#tanggal').on('keypress', function(e) {
        if (e.which === 13) { countdown = AUTO_REFRESH_SEC; loadpresensi(); }
    });

    $('#tanggal').on('change', function () { countdown = AUTO_REFRESH_SEC; });

    function loadpresensi() {
        var tanggal = $('#tanggal').val();
        $('#loadpresensi').html('<tr><td colspan="5"><div class="tbl-loading"><div class="spinner"></div><p>Memuat data presensi...</p></div></td></tr>');
        $('#tblCount').text('Memuat...');
        $('#statTotal, #statHadir, #statTelat, #statIzin').text('-');

        $.ajax({
            type: 'POST',
            url: '/panel/monitoring/getpresensi',
            data: { _token: "{{ csrf_token() }}", tanggal: tanggal },
            cache: false,
            success: function (respond) {
                $('#loadpresensi').html(respond);
                var rows  = $('#loadpresensi tr[data-row]').length;
                var hadir = $('#loadpresensi tr[data-status="hadir"]').length;
                var telat = $('#loadpresensi tr[data-status="telat"]').length;
                var izin  = $('#loadpresensi tr[data-status="izin"]').length;
                $('#tblCount').text(rows + ' data');
                $('#statTotal').text(rows);
                $('#statHadir').text(hadir);
                $('#statTelat').text(telat);
                $('#statIzin').text(izin);
            },
            error: function () {
                $('#loadpresensi').html('<tr><td colspan="5"><div class="tbl-empty"><i class="mdi mdi-wifi-off"></i><p>Gagal memuat data. Periksa koneksi Anda.</p></div></td></tr>');
                $('#tblCount').text('Error');
            }
        });
    }

    // ── AUTO-REFRESH 60 detik (hanya jika melihat hari ini) ──
    var AUTO_REFRESH_SEC = 60;
    var countdown = AUTO_REFRESH_SEC;

    function todayStr() { return new Date().toISOString().split('T')[0]; }

    function updateBadge() {
        var isToday = ($('#tanggal').val() === todayStr());
        if (isToday) {
            $('#auto-refresh-badge').html(
                '<span class="live-dot"></span> Live &middot; Refresh <b>' + countdown + 's</b>'
            );
        } else {
            $('#auto-refresh-badge').html(
                '<span class="live-dot" style="background:#9CA3AF;animation:none;"></span> Paused'
            );
        }
    }

    setInterval(function () {
        var isToday = ($('#tanggal').val() === todayStr());
        if (!isToday) { updateBadge(); return; }
        countdown--;
        updateBadge();
        if (countdown <= 0) {
            loadpresensi();
            countdown = AUTO_REFRESH_SEC;
        }
    }, 1000);

    updateBadge();
});

function editPresensi(nik, jam_in, jam_out, status) {
    var form = document.querySelector('#modalKoreksi form');
    form.querySelector('select[name="nik"]').value = nik;
    form.querySelector('input[name="jam_in"]').value = jam_in;
    form.querySelector('input[name="jam_out"]').value = jam_out;
    form.querySelector('select[name="status"]').value = status;
    $('#modalKoreksi').modal('show');
}

function tampilkanpeta(id) {
    $('#loadmap').html('<div class="tbl-loading"><div class="spinner"></div><p>Memuat peta...</p></div>');
    $('#modalMap').modal('show');
    $.ajax({
        type: 'POST', url: '/panel/monitoring/showmap',
        data: { _token: "{{ csrf_token() }}", id: id },
        cache: false,
        success: function (respond) { $('#loadmap').html(respond); },
        error:   function () { $('#loadmap').html('<div class="tbl-empty p-4"><i class="mdi mdi-map-marker-off"></i><p>Gagal memuat peta.</p></div>'); }
    });
}
</script>
@endpush
