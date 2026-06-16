@extends('karyawan.layouts.presensi')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

    :root {
        --primary:       #2563EB;
        --primary-soft:  #EFF6FF;
        --primary-mid:   #BFDBFE;
        --success:       #10B981;
        --success-soft:  #ECFDF5;
        --danger:        #EF4444;
        --danger-soft:   #FEF2F2;
        --warning:       #F59E0B;
        --warning-soft:  #FFFBEB;
        --info:          #06B6D4;
        --info-soft:     #ECFEFF;
        --purple:        #8B5CF6;
        --purple-soft:   #F5F3FF;
        --text-900:      #111827;
        --text-600:      #4B5563;
        --text-400:      #9CA3AF;
        --border:        #F1F5F9;
        --border-med:    #E2E8F0;
        --surface:       #FFFFFF;
        --bg:            #F8FAFC;
        --radius-sm:     10px;
        --radius-md:     14px;
        --radius-lg:     18px;
        --shadow-sm:     0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
        --shadow-md:     0 4px 6px rgba(0,0,0,0.05), 0 2px 4px rgba(0,0,0,0.04);
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: 'Inter', -apple-system, sans-serif;
        background: var(--bg);
        color: var(--text-900);
        -webkit-font-smoothing: antialiased;
    }

    /* ── PAGE HEADER ── */
    .pg-header {
        background: var(--surface);
        border-bottom: 1px solid var(--border);
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: sticky;
        top: 0;
        z-index: 50;
    }

    .pg-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .btn-back {
        width: 36px;
        height: 36px;
        background: var(--bg);
        border: 1px solid var(--border-med);
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        flex-shrink: 0;
        transition: background 0.2s;
        -webkit-tap-highlight-color: transparent;
    }

    .btn-back:active { background: var(--border-med); }
    .btn-back ion-icon { font-size: 20px; color: var(--text-600); }

    .pg-title {
        font-size: 17px;
        font-weight: 700;
        color: var(--text-900);
        line-height: 1.2;
    }

    .pg-sub {
        font-size: 11px;
        font-weight: 500;
        color: var(--primary);
        display: block;
        margin-top: 1px;
    }

    .btn-export-top {
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 7px 12px;
        background: var(--primary-soft);
        color: var(--primary);
        border: 1px solid var(--primary-mid);
        border-radius: var(--radius-sm);
        font-family: 'Inter', sans-serif;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: background 0.2s;
        -webkit-tap-highlight-color: transparent;
        display: none; /* hidden until data loaded */
    }

    .btn-export-top.show { display: flex; }
    .btn-export-top:active { background: var(--primary-mid); }
    .btn-export-top ion-icon { font-size: 15px; }

    /* ── PAGE BODY ── */
    .pg-body {
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 14px;
        padding-bottom: 100px;
    }

    /* ── CARD BASE ── */
    .card {
        background: var(--surface);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
    }

    /* ── SECTION LABEL ── */
    .sec-label {
        font-size: 11px;
        font-weight: 700;
        color: var(--text-400);
        text-transform: uppercase;
        letter-spacing: 0.7px;
        margin-bottom: 6px;
        padding: 0 2px;
    }

    /* ── QUICK DATE CHIPS ── */
    .quick-chips {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }

    .chip-btn {
        padding: 10px 12px;
        background: var(--bg);
        border: 1px solid var(--border-med);
        border-radius: var(--radius-sm);
        font-family: 'Inter', sans-serif;
        font-size: 12px;
        font-weight: 600;
        color: var(--text-600);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        cursor: pointer;
        transition: all 0.18s ease;
        -webkit-tap-highlight-color: transparent;
    }

    .chip-btn ion-icon { font-size: 15px; color: var(--primary); }
    .chip-btn:active,
    .chip-btn.active {
        background: var(--primary-soft);
        border-color: var(--primary-mid);
        color: var(--primary);
    }

    /* ── DATE INPUT ── */
    .date-input-wrap {
        position: relative;
        margin-top: 10px;
    }

    .date-input-icon {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 18px;
        color: var(--primary);
        pointer-events: none;
        z-index: 1;
    }

    .date-input {
        width: 100%;
        padding: 13px 14px 13px 40px;
        border: 1.5px solid var(--border-med);
        border-radius: var(--radius-md);
        font-family: 'Inter', sans-serif;
        font-size: 13px;
        font-weight: 500;
        color: var(--text-900);
        background: var(--surface);
        outline: none;
        cursor: pointer;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .date-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37,99,235,0.10);
    }

    /* Flatpickr custom */
    .flatpickr-calendar {
        border-radius: 16px !important;
        box-shadow: 0 10px 40px rgba(0,0,0,0.12) !important;
        border: 1px solid var(--border-med) !important;
        font-family: 'Inter', sans-serif !important;
        z-index: 9999 !important;
    }

    .flatpickr-months .flatpickr-month {
        background: var(--primary) !important;
        border-radius: 14px 14px 0 0 !important;
        color: white !important;
    }

    .flatpickr-current-month .flatpickr-monthDropdown-months {
        background: transparent !important;
        color: white !important;
    }

    .flatpickr-current-month input.cur-year {
        color: white !important;
        font-weight: 700 !important;
    }

    .flatpickr-weekday {
        color: var(--primary) !important;
        font-weight: 600 !important;
    }

    .flatpickr-day.selected,
    .flatpickr-day.startRange,
    .flatpickr-day.endRange,
    .flatpickr-day.selected:hover,
    .flatpickr-day.startRange:hover,
    .flatpickr-day.endRange:hover {
        background: var(--primary) !important;
        border-color: var(--primary) !important;
        color: white !important;
    }

    .flatpickr-day.inRange {
        background: var(--primary-soft) !important;
        border-color: transparent !important;
        box-shadow: none !important;
        color: var(--primary) !important;
    }

    .flatpickr-day.today:not(.selected) {
        border-color: var(--primary) !important;
        color: var(--primary) !important;
    }

    /* Selected range pill */
    .date-range-pill {
        display: none;
        align-items: center;
        gap: 8px;
        padding: 10px 12px;
        background: var(--primary-soft);
        border: 1px solid var(--primary-mid);
        border-radius: var(--radius-sm);
        margin-top: 8px;
        animation: fadeIn 0.2s ease;
    }

    .date-range-pill.show { display: flex; }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-4px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .date-pill-icon { font-size: 16px; color: var(--primary); flex-shrink: 0; }

    .date-pill-text {
        font-size: 12px;
        font-weight: 600;
        color: var(--primary);
        flex: 1;
    }

    /* ── SEARCH BUTTON ── */
    .btn-search {
        width: 100%;
        padding: 14px;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: var(--radius-md);
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        box-shadow: 0 4px 14px rgba(37,99,235,0.28);
        transition: opacity 0.2s, transform 0.15s;
        -webkit-tap-highlight-color: transparent;
        margin-top: 4px;
    }

    .btn-search ion-icon { font-size: 18px; }
    .btn-search:active { opacity: 0.88; transform: scale(0.98); }
    .btn-search:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

    /* ── STATS GRID ── */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
        padding: 14px;
    }

    .stat-box {
        border-radius: var(--radius-md);
        padding: 12px 8px;
        text-align: center;
        border: 1px solid transparent;
    }

    .stat-box.hadir    { background: var(--primary-soft);  border-color: var(--primary-mid); }
    .stat-box.terlambat{ background: var(--danger-soft);   border-color: #FECACA; }
    .stat-box.izin     { background: var(--warning-soft);  border-color: #FDE68A; }
    .stat-box.sakit    { background: var(--info-soft);     border-color: #A5F3FC; }
    .stat-box.cuti     { background: var(--success-soft);  border-color: #A7F3D0; }
    .stat-box.alpa     { background: #F9FAFB;              border-color: #E5E7EB; }

    .stat-val {
        font-size: 22px;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 4px;
    }

    .stat-box.hadir     .stat-val { color: var(--primary); }
    .stat-box.terlambat .stat-val { color: var(--danger); }
    .stat-box.izin      .stat-val { color: var(--warning); }
    .stat-box.sakit     .stat-val { color: var(--info); }
    .stat-box.cuti      .stat-val { color: var(--success); }
    .stat-box.alpa      .stat-val { color: var(--text-600); }

    .stat-lbl {
        font-size: 10px;
        font-weight: 600;
        color: var(--text-400);
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    /* ── HISTORY LIST ── */
    .history-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    /* ── PERIOD LABEL ── */
    .period-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2px;
    }

    .period-text {
        font-size: 12px;
        font-weight: 600;
        color: var(--text-600);
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .period-text ion-icon { font-size: 15px; color: var(--primary); }

    /* ── LOADING / EMPTY ── */
    .state-box {
        text-align: center;
        padding: 48px 20px;
        background: var(--surface);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border);
    }

    .state-box ion-icon {
        font-size: 56px;
        color: #CBD5E1;
        margin-bottom: 12px;
        display: block;
    }

    .state-box p {
        font-size: 14px;
        color: var(--text-600);
        font-weight: 500;
    }

    .state-box small {
        font-size: 12px;
        color: var(--text-400);
        margin-top: 4px;
        display: block;
    }

    /* ── Animations ── */
    @keyframes fadeSlide {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .pg-body > * {
        animation: fadeSlide 0.3s ease both;
    }
    .pg-body > *:nth-child(1) { animation-delay: 0.04s; }
    .pg-body > *:nth-child(2) { animation-delay: 0.08s; }
    .pg-body > *:nth-child(3) { animation-delay: 0.12s; }
    .pg-body > *:nth-child(4) { animation-delay: 0.16s; }

    /* ── Responsive ── */
    @media (max-width: 360px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .quick-chips { grid-template-columns: 1fr 1fr; }
    }
</style>

{{-- ── PAGE HEADER ── --}}
<div class="pg-header">
    <div class="pg-header-left">
        <a href="{{ route('dashboard') }}" class="btn-back">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
        <div>
            <div class="pg-title">Histori Presensi</div>
            <span class="pg-sub">Riwayat kehadiran Anda</span>
        </div>
    </div>
    <a href="#" class="btn-export-top" id="btn-export">
        <ion-icon name="download-outline"></ion-icon>
        Export
    </a>
</div>

{{-- ── PAGE BODY ── --}}
<div class="pg-body">

    {{-- Filter Card --}}
    <div>
        <div class="sec-label">Filter Tanggal</div>
        <div class="card" style="padding: 14px;">

            {{-- Quick chips --}}
            <div class="quick-chips" style="margin-bottom: 12px;">
                <button type="button" class="chip-btn" id="chip-week"      onclick="setDateRange('week')">
                    <ion-icon name="calendar-outline"></ion-icon> 7 Hari
                </button>
                <button type="button" class="chip-btn" id="chip-month"     onclick="setDateRange('month')">
                    <ion-icon name="calendar-outline"></ion-icon> 30 Hari
                </button>
                <button type="button" class="chip-btn" id="chip-thismonth" onclick="setDateRange('thismonth')">
                    <ion-icon name="today-outline"></ion-icon> Bulan Ini
                </button>
                <button type="button" class="chip-btn" id="chip-lastmonth" onclick="setDateRange('lastmonth')">
                    <ion-icon name="chevron-back-circle-outline"></ion-icon> Bulan Lalu
                </button>
            </div>

            {{-- Date input --}}
            <div class="date-input-wrap">
                <ion-icon name="calendar-outline" class="date-input-icon"></ion-icon>
                <input type="text" id="daterange" class="date-input" placeholder="Pilih rentang tanggal…" readonly>
            </div>

            {{-- Date range pill --}}
            <div class="date-range-pill" id="date-range-pill">
                <ion-icon name="swap-horizontal-outline" class="date-pill-icon"></ion-icon>
                <div class="date-pill-text" id="date-pill-text">—</div>
            </div>

            {{-- Search button --}}
            <button class="btn-search" id="getdata" style="margin-top: 12px;">
                <ion-icon name="search-outline"></ion-icon>
                Tampilkan Data
            </button>
        </div>
    </div>

    {{-- Stats Section (hidden until data loaded) --}}
    <div id="stats-container" style="display:none;">
        <div class="sec-label">Ringkasan Periode</div>
        <div class="card" style="margin-top: 6px;">
            <div class="stats-grid" id="stats-grid">
                {{-- filled via AJAX --}}
            </div>
        </div>
    </div>

    {{-- History List --}}
    <div id="history-wrap">

        {{-- Period row (hidden until data loaded) --}}
        <div class="period-row" id="period-row" style="display:none; margin-bottom: 8px;">
            <div class="period-text">
                <ion-icon name="time-outline"></ion-icon>
                <span id="period-label">Riwayat</span>
            </div>
        </div>

        {{-- Default empty state --}}
        <div class="state-box" id="default-state">
            <ion-icon name="calendar-outline"></ion-icon>
            <p>Pilih rentang tanggal</p>
            <small>untuk melihat riwayat presensi Anda</small>
        </div>

        {{-- AJAX result --}}
        <div id="showhistori" class="history-list"></div>
    </div>

</div>

@endsection

@push('myscript')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

<script>
    let dateRangePicker;
    let selectedStartDate = null;
    let selectedEndDate   = null;
    let activeChip = null;

    $(function () {
        /* ── Flatpickr init ── */
        dateRangePicker = flatpickr('#daterange', {
            mode: 'range',
            dateFormat: 'd M Y',
            locale: 'id',
            maxDate: 'today',
            defaultDate: [
                new Date(new Date().getFullYear(), new Date().getMonth(), 1),
                new Date()
            ],
            onReady: function (dates) {
                if (dates.length === 2) {
                    selectedStartDate = dates[0];
                    selectedEndDate   = dates[1];
                    updatePill();
                }
            },
            onChange: function (dates) {
                if (dates.length === 2) {
                    selectedStartDate = dates[0];
                    selectedEndDate   = dates[1];
                    updatePill();
                    clearActiveChip();
                } else if (dates.length === 1) {
                    selectedStartDate = dates[0];
                    selectedEndDate   = null;
                    updatePill();
                }
            },
            onClose: function (dates) {
                if (dates.length === 2) setTimeout(loadHistori, 100);
            }
        });

        /* ── Auto-load bulan ini on ready ── */
        setTimeout(loadHistori, 400);

        /* ── Search button ── */
        $('#getdata').click(function (e) {
            e.preventDefault();
            loadHistori();
        });
    });

    /* ── Chip helpers ── */
    function clearActiveChip() {
        $('.chip-btn').removeClass('active');
        activeChip = null;
    }

    function setActiveChip(id) {
        clearActiveChip();
        $('#' + id).addClass('active');
        activeChip = id;
    }

    /* ── Update range pill ── */
    function updatePill() {
        var pill = $('#date-range-pill');
        var txt  = $('#date-pill-text');
        if (selectedStartDate) {
            var from = formatDisplay(selectedStartDate);
            var to   = selectedEndDate ? formatDisplay(selectedEndDate) : '—';
            txt.text(from + '  →  ' + to);
            pill.addClass('show');
        } else {
            pill.removeClass('show');
        }
    }

    /* ── Quick date range ── */
    function setDateRange(type) {
        var today = new Date();
        var start, end;

        switch (type) {
            case 'week':
                start = new Date(today.getTime() - 6 * 864e5);
                end   = today;
                setActiveChip('chip-week');
                break;
            case 'month':
                start = new Date(today.getTime() - 29 * 864e5);
                end   = today;
                setActiveChip('chip-month');
                break;
            case 'thismonth':
                start = new Date(today.getFullYear(), today.getMonth(), 1);
                end   = today;
                setActiveChip('chip-thismonth');
                break;
            case 'lastmonth':
                start = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                end   = new Date(today.getFullYear(), today.getMonth(), 0);
                setActiveChip('chip-lastmonth');
                break;
        }

        selectedStartDate = start;
        selectedEndDate   = end;
        dateRangePicker.setDate([start, end]);
        updatePill();
        setTimeout(loadHistori, 100);
    }

    /* ── Load histori ── */
    function loadHistori() {
        if (!selectedStartDate || !selectedEndDate) {
            Swal.fire({ icon: 'warning', title: 'Pilih Tanggal', text: 'Pilih rentang tanggal mulai dan akhir terlebih dahulu.', confirmButtonColor: '#2563EB' });
            return;
        }

        var daysDiff = Math.floor((selectedEndDate - selectedStartDate) / 864e5);
        if (daysDiff > 93) {
            Swal.fire({ icon: 'warning', title: 'Rentang Terlalu Lama', text: 'Maksimal rentang tanggal adalah 3 bulan (93 hari).', confirmButtonColor: '#2563EB' });
            return;
        }

        var dari   = formatDate(selectedStartDate);
        var sampai = formatDate(selectedEndDate);

        /* Show loading */
        $('#default-state').hide();
        $('#showhistori').html('');
        $('#showhistori').html(`
            <div class="state-box">
                <ion-icon name="hourglass-outline" style="animation: spin 1.2s linear infinite;"></ion-icon>
                <p>Memuat data…</p>
            </div>
        `);

        $('#getdata').prop('disabled', true).html('<ion-icon name="hourglass-outline"></ion-icon> Memuat…');

        $.ajax({
            type: 'POST', url: '/gethistori',
            data: { _token: '{{ csrf_token() }}', dari: dari, sampai: sampai },
            cache: false,
            success: function (respond) {
                $('#showhistori').html(respond);

                /* Period row */
                $('#period-label').text(formatDisplay(selectedStartDate) + ' – ' + formatDisplay(selectedEndDate));
                $('#period-row').show();

                /* Export button */
                $('#btn-export').attr('href', '/presensi/histori/export-excel?dari=' + dari + '&sampai=' + sampai).addClass('show');

                /* Load stats */
                loadStatistik(dari, sampai);
            },
            error: function () {
                $('#showhistori').html(`
                    <div class="state-box">
                        <ion-icon name="alert-circle-outline" style="color:#EF4444;"></ion-icon>
                        <p>Gagal memuat data</p>
                        <small>Periksa koneksi internet dan coba lagi</small>
                    </div>
                `);
            },
            complete: function () {
                $('#getdata').prop('disabled', false).html('<ion-icon name="search-outline"></ion-icon> Tampilkan Data');
            }
        });
    }

    /* ── Load statistik ── */
    function loadStatistik(dari, sampai) {
        $.ajax({
            type: 'GET', url: '/presensi/histori/statistik',
            data: { dari: dari, sampai: sampai },
            success: function (res) {
                if (!res.success) return;
                var d = res.data;
                $('#stats-grid').html(`
                    <div class="stat-box hadir">
                        <div class="stat-val">${d.total_hadir}</div>
                        <div class="stat-lbl">Hadir</div>
                    </div>
                    <div class="stat-box terlambat">
                        <div class="stat-val">${d.total_terlambat}</div>
                        <div class="stat-lbl">Telat</div>
                    </div>
                    <div class="stat-box izin">
                        <div class="stat-val">${d.total_izin}</div>
                        <div class="stat-lbl">Izin</div>
                    </div>
                    <div class="stat-box sakit">
                        <div class="stat-val">${d.total_sakit}</div>
                        <div class="stat-lbl">Sakit</div>
                    </div>
                    <div class="stat-box cuti">
                        <div class="stat-val">${d.total_cuti}</div>
                        <div class="stat-lbl">Cuti</div>
                    </div>
                    <div class="stat-box alpa">
                        <div class="stat-val">${d.total_alpa}</div>
                        <div class="stat-lbl">Alpa</div>
                    </div>
                `);
                $('#stats-container').show();
            }
        });
    }

    /* ── Helpers ── */
    function formatDate(d) {
        return d.getFullYear() + '-' + String(d.getMonth()+1).padStart(2,'0') + '-' + String(d.getDate()).padStart(2,'0');
    }

    function formatDisplay(d) {
        return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
    }

    /* Spinner keyframe */
    const style = document.createElement('style');
    style.textContent = '@keyframes spin { to { transform: rotate(360deg); } }';
    document.head.appendChild(style);
</script>
@endpush