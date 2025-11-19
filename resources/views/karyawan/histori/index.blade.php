@extends('karyawan.layouts.presensi')

@section('content')

<style>
    /* ===== MODERN HISTORY PAGE ===== */
    :root {
        --primary: #0053C5;
        --primary-dark: #003d94;
        --primary-light: #2E7CE6;
        --primary-gradient: linear-gradient(135deg, #0053C5 0%, #2E7CE6 100%);
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --info: #06b6d4;
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
        padding: 24px 20px 70px 20px;
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
        transition: all 0.3s ease;
    }

    .btn-back ion-icon {
        font-size: 24px;
        color: white;
    }

    .btn-back:active {
        transform: scale(0.95);
        background: rgba(255, 255, 255, 0.25);
    }

    .header-title {
        flex: 1;
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

    /* ===== FILTER CARD ===== */
    .filter-section {
        padding: 0 20px;
        margin-top: -55px;
        margin-bottom: 20px;
        position: relative;
        z-index: 10;
    }

    .filter-card {
        background: var(--bg-card);
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 83, 197, 0.08);
    }

    .filter-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-title ion-icon {
        font-size: 20px;
        color: var(--primary);
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-group label {
        font-size: 12px;
        font-weight: 600;
        color: var(--text-secondary);
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .form-group label ion-icon {
        font-size: 16px;
        color: var(--primary);
    }

    .form-control {
        width: 100%;
        padding: 14px 16px 14px 44px;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        font-size: 14px;
        color: var(--text-primary);
        background: #f8fafc;
        transition: all 0.3s ease;
        position: relative;
    }

    .input-wrapper {
        position: relative;
    }

    .input-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 20px;
        color: var(--primary);
        z-index: 1;
        pointer-events: none;
    }

    .form-control {
        width: 100%;
        padding: 14px 16px 14px 44px;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        font-size: 14px;
        color: var(--text-primary);
        background: white !important;
        /* Force white background */
        transition: all 0.3s ease;
        cursor: pointer;
        /* Tambahkan cursor pointer */
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        background: white !important;
        box-shadow: 0 0 0 3px rgba(0, 83, 197, 0.1);
    }

    .form-control:hover {
        border-color: var(--primary-light);
    }

    /* Flatpickr custom styling */
    .flatpickr-calendar {
        border-radius: 16px !important;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15) !important;
        border: 1px solid #e2e8f0 !important;
        z-index: 9999 !important;
    }

    .flatpickr-day.selected,
    .flatpickr-day.startRange,
    .flatpickr-day.endRange,
    .flatpickr-day.selected.inRange,
    .flatpickr-day.startRange.inRange,
    .flatpickr-day.endRange.inRange,
    .flatpickr-day.selected:focus,
    .flatpickr-day.startRange:focus,
    .flatpickr-day.endRange:focus,
    .flatpickr-day.selected:hover,
    .flatpickr-day.startRange:hover,
    .flatpickr-day.endRange:hover,
    .flatpickr-day.selected.prevMonthDay,
    .flatpickr-day.startRange.prevMonthDay,
    .flatpickr-day.endRange.prevMonthDay,
    .flatpickr-day.selected.nextMonthDay,
    .flatpickr-day.startRange.nextMonthDay,
    .flatpickr-day.endRange.nextMonthDay {
        background: #0053C5 !important;
        border-color: #0053C5 !important;
    }

    .flatpickr-day.inRange {
        background: rgba(0, 83, 197, 0.1) !important;
        border-color: transparent !important;
        box-shadow: none !important;
    }

    .flatpickr-months .flatpickr-month {
        background: var(--primary-gradient) !important;
        color: white !important;
        border-radius: 12px 12px 0 0 !important;
    }

    .flatpickr-current-month .flatpickr-monthDropdown-months {
        background: transparent !important;
        color: white !important;
    }

    .flatpickr-weekday {
        color: var(--primary) !important;
        font-weight: 600 !important;
    }

    .flatpickr-day.today {
        border-color: var(--primary) !important;
    }

    /* Selected date display */
    .selected-dates-display {
        margin-top: 8px;
        padding: 10px 14px;
        background: linear-gradient(135deg, rgba(0, 83, 197, 0.05) 0%, rgba(46, 124, 230, 0.05) 100%);
        border-radius: 10px;
        border: 1px solid rgba(0, 83, 197, 0.2);
        display: none;
    }

    .selected-dates-display.show {
        display: block;
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .selected-dates-display p {
        margin: 0;
        font-size: 12px;
        color: var(--primary);
        font-weight: 600;
    }

    .selected-dates-display .dates {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 6px;
    }

    .selected-dates-display .date-item {
        flex: 1;
        padding: 6px 10px;
        background: white;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-primary);
        text-align: center;
    }

    .selected-dates-display .separator {
        color: var(--primary);
        font-weight: 700;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        background: white;
        box-shadow: 0 0 0 3px rgba(0, 83, 197, 0.1);
    }

    /* Date Range Picker Custom Style */
    .flatpickr-calendar {
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        border: 1px solid #e2e8f0;
    }

    .flatpickr-day.selected,
    .flatpickr-day.selected:hover {
        background: var(--primary-gradient);
        border-color: var(--primary);
    }

    .flatpickr-day.inRange {
        background: rgba(0, 83, 197, 0.1);
        border-color: transparent;
    }

    .flatpickr-months .flatpickr-month {
        background: var(--primary-gradient);
        color: white;
        border-radius: 12px 12px 0 0;
    }

    .flatpickr-current-month .flatpickr-monthDropdown-months {
        background: transparent;
        color: white;
    }

    .flatpickr-weekday {
        color: var(--primary);
        font-weight: 600;
    }

    .quick-dates {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin-bottom: 16px;
    }

    .btn-quick {
        padding: 10px 14px;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 600;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-quick ion-icon {
        font-size: 16px;
        color: var(--primary);
    }

    .btn-quick:active {
        background: var(--primary-gradient);
        color: white;
        border-color: var(--primary);
        transform: scale(0.98);
    }

    .btn-quick:active ion-icon {
        color: white;
    }

    .btn-search {
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
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 83, 197, 0.3);
    }

    .btn-search ion-icon {
        font-size: 20px;
    }

    .btn-search:active {
        transform: scale(0.98);
        box-shadow: 0 2px 8px rgba(0, 83, 197, 0.3);
    }

    .btn-search:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* ===== STATS SECTION ===== */
    .stats-section {
        padding: 0 20px 20px;
    }

    .stats-card {
        background: var(--bg-card);
        border-radius: 16px;
        padding: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(0, 83, 197, 0.08);
        margin-bottom: 16px;
    }

    .stats-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .stats-title ion-icon {
        font-size: 18px;
        color: var(--primary);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
    }

    .stat-item {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 12px;
        padding: 12px;
        text-align: center;
        border-left: 3px solid var(--stat-color);
    }

    .stat-value {
        font-size: 20px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 4px;
    }

    .stat-label {
        font-size: 10px;
        color: var(--text-secondary);
        font-weight: 600;
    }

    .stat-item.hadir {
        --stat-color: var(--primary);
    }

    .stat-item.terlambat {
        --stat-color: var(--danger);
    }

    .stat-item.izin {
        --stat-color: var(--warning);
    }

    .stat-item.sakit {
        --stat-color: var(--info);
    }

    .stat-item.cuti {
        --stat-color: var(--success);
    }

    .stat-item.alpa {
        --stat-color: #6c757d;
    }

    /* ===== HISTORY CONTENT ===== */
    .history-section {
        padding: 0 20px 100px;
    }

    .history-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .history-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .history-title ion-icon {
        font-size: 20px;
        color: var(--primary);
    }

    .btn-export {
        padding: 8px 16px;
        background: white;
        color: var(--primary);
        border: 1px solid var(--primary);
        border-radius: 10px;
        font-size: 12px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-export ion-icon {
        font-size: 16px;
    }

    .btn-export:active {
        background: var(--primary);
        color: white;
        transform: scale(0.98);
    }

    /* ===== LOADING & EMPTY STATE ===== */
    .loading-state,
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: var(--text-secondary);
    }

    .loading-state ion-icon,
    .empty-state ion-icon {
        font-size: 64px;
        color: #cbd5e1;
        margin-bottom: 16px;
        animation: pulse 2s ease-in-out infinite;
    }

    .empty-state ion-icon {
        animation: none;
    }

    .loading-state p,
    .empty-state p {
        font-size: 14px;
        margin: 0;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.5;
        }
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 375px) {

        .filter-section,
        .stats-section,
        .history-section {
            padding-left: 16px;
            padding-right: 16px;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .quick-dates {
            grid-template-columns: 1fr;
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
            <h1>Histori Presensi</h1>
            <p>Lihat riwayat kehadiran Anda</p>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="filter-section">
    <div class="filter-card">
        <div class="filter-title">
            <ion-icon name="calendar-outline"></ion-icon>
            Pilih Rentang Tanggal
        </div>

        <!-- Quick Date Buttons -->
        <div class="quick-dates">
            <button type="button" class="btn-quick" onclick="setDateRange('week')">
                <ion-icon name="calendar"></ion-icon>
                <span>7 Hari Terakhir</span>
            </button>
            <button type="button" class="btn-quick" onclick="setDateRange('month')">
                <ion-icon name="calendar"></ion-icon>
                <span>30 Hari Terakhir</span>
            </button>
            <button type="button" class="btn-quick" onclick="setDateRange('thismonth')">
                <ion-icon name="calendar"></ion-icon>
                <span>Bulan Ini</span>
            </button>
            <button type="button" class="btn-quick" onclick="setDateRange('lastmonth')">
                <ion-icon name="calendar"></ion-icon>
                <span>Bulan Lalu</span>
            </button>
        </div>

        <div class="form-group">
            <label>
                <ion-icon name="calendar-outline"></ion-icon>
                Atau Pilih Manual
            </label>
            <div class="input-wrapper">
                <ion-icon name="calendar-outline" class="input-icon"></ion-icon>
                <input
                    type="text"
                    id="daterange"
                    class="form-control"
                    placeholder="Klik untuk pilih tanggal...">
            </div>

            <!-- Display Selected Dates -->
            <div class="selected-dates-display" id="selected-dates-display">
                <p>Periode Dipilih:</p>
                <div class="dates">
                    <div class="date-item" id="date-from">-</div>
                    <span class="separator">→</span>
                    <div class="date-item" id="date-to">-</div>
                </div>
            </div>
        </div>

        <button class="btn-search" id="getdata">
            <ion-icon name="search-outline"></ion-icon>
            <span>Tampilkan Data</span>
        </button>
    </div>
</div>

<!-- Stats Section -->
<div class="stats-section" id="stats-container" style="display: none;">
    <div class="stats-card">
        <div class="stats-title">
            <ion-icon name="bar-chart"></ion-icon>
            Ringkasan Kehadiran
        </div>
        <div class="stats-grid" id="stats-grid">
            <!-- Stats akan dimuat via AJAX -->
        </div>
    </div>
</div>

<!-- History Section -->
<div class="history-section">
    <div class="history-header" id="history-header" style="display: none;">
        <div class="history-title">
            <ion-icon name="time"></ion-icon>
            <span id="period-label">Riwayat</span>
        </div>
        <a href="#" class="btn-export" id="btn-export">
            <ion-icon name="download-outline"></ion-icon>
            Export
        </a>
    </div>
    <div id="showhistori">
        <div class="empty-state">
            <ion-icon name="calendar-outline"></ion-icon>
            <p>Pilih rentang tanggal untuk melihat histori presensi</p>
        </div>
    </div>
</div>

@endsection

@push('myscript')
<!-- Flatpickr CSS & JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

<script>
    let dateRangePicker;
    let selectedStartDate = null;
    let selectedEndDate = null;

    $(function() {
        console.log('Initializing date range picker...');

        // Initialize Flatpickr Date Range Picker
        dateRangePicker = flatpickr("#daterange", {
            mode: "range",
            dateFormat: "d M Y",
            locale: "id",
            maxDate: "today",
            defaultDate: [
                new Date(new Date().getFullYear(), new Date().getMonth(), 1),
                new Date()
            ],
            onReady: function(selectedDates, dateStr, instance) {
                console.log('Flatpickr ready');
                // Set initial dates
                if (selectedDates.length === 2) {
                    selectedStartDate = selectedDates[0];
                    selectedEndDate = selectedDates[1];
                    updateSelectedDatesDisplay();
                }
            },
            onChange: function(selectedDates, dateStr, instance) {
                console.log('Date changed:', selectedDates, dateStr);

                if (selectedDates.length === 2) {
                    selectedStartDate = selectedDates[0];
                    selectedEndDate = selectedDates[1];

                    console.log('Both dates selected:', {
                        start: selectedStartDate,
                        end: selectedEndDate
                    });

                    // Update display
                    updateSelectedDatesDisplay();

                    // Enable button
                    $("#getdata").prop('disabled', false);
                } else if (selectedDates.length === 1) {
                    console.log('First date selected:', selectedDates[0]);
                    selectedStartDate = selectedDates[0];
                    selectedEndDate = null;

                    // Update display
                    updateSelectedDatesDisplay();
                }
            },
            onClose: function(selectedDates, dateStr, instance) {
                console.log('Calendar closed with:', selectedDates.length, 'dates');

                // Auto load jika sudah pilih 2 tanggal
                if (selectedDates.length === 2) {
                    setTimeout(function() {
                        loadHistori();
                    }, 100);
                }
            }
        });

        // Test klik pada input
        $("#daterange").on('click', function() {
            console.log('Input clicked!');
        });

        // Auto load data untuk bulan ini
        setTimeout(function() {
            loadHistori();
        }, 500);

        // Event click button search
        $("#getdata").click(function(e) {
            e.preventDefault();
            console.log('Search button clicked');
            loadHistori();
        });
    });

    // Function untuk update display tanggal yang dipilih
    function updateSelectedDatesDisplay() {
        if (selectedStartDate) {
            $("#date-from").text(formatDisplayDate(selectedStartDate));

            if (selectedEndDate) {
                $("#date-to").text(formatDisplayDate(selectedEndDate));
                $("#selected-dates-display").addClass('show');
            } else {
                $("#date-to").text('Pilih tanggal akhir...');
                $("#selected-dates-display").addClass('show');
            }
        } else {
            $("#selected-dates-display").removeClass('show');
        }
    }

    // Function set quick date range
    function setDateRange(type) {
        let startDate, endDate;
        const today = new Date();

        switch (type) {
            case 'week':
                startDate = new Date(today.getTime() - 6 * 24 * 60 * 60 * 1000);
                endDate = today;
                break;
            case 'month':
                startDate = new Date(today.getTime() - 29 * 24 * 60 * 60 * 1000);
                endDate = today;
                break;
            case 'thismonth':
                startDate = new Date(today.getFullYear(), today.getMonth(), 1);
                endDate = today;
                break;
            case 'lastmonth':
                startDate = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                endDate = new Date(today.getFullYear(), today.getMonth(), 0);
                break;
        }

        console.log('Quick date selected:', type, startDate, endDate);

        selectedStartDate = startDate;
        selectedEndDate = endDate;

        dateRangePicker.setDate([startDate, endDate]);
        updateSelectedDatesDisplay();

        // Delay sedikit agar flatpickr selesai set date
        setTimeout(function() {
            loadHistori();
        }, 100);
    }

    // Function untuk load histori
    function loadHistori() {
        console.log('Loading histori...');
        console.log('Selected dates:', selectedStartDate, selectedEndDate);

        // Validasi
        if (!selectedStartDate || !selectedEndDate) {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: 'Silakan pilih rentang tanggal terlebih dahulu (mulai dan akhir)',
                confirmButtonColor: '#0053C5'
            });
            return;
        }

        const dari = formatDate(selectedStartDate);
        const sampai = formatDate(selectedEndDate);

        console.log('Formatted dates - Dari:', dari, 'Sampai:', sampai);

        // Cek maksimal 3 bulan
        const daysDiff = Math.floor((selectedEndDate - selectedStartDate) / (1000 * 60 * 60 * 24));
        console.log('Days difference:', daysDiff);

        if (daysDiff > 93) {
            Swal.fire({
                icon: 'warning',
                title: 'Rentang Terlalu Lama',
                text: 'Maksimal rentang tanggal adalah 3 bulan (93 hari)',
                confirmButtonColor: '#0053C5'
            });
            return;
        }

        // Show loading
        $("#showhistori").html(`
            <div class="loading-state">
                <ion-icon name="hourglass-outline"></ion-icon>
                <p>Memuat data...</p>
            </div>
        `);

        // Disable button
        $("#getdata").prop('disabled', true).html(`
            <ion-icon name="hourglass-outline"></ion-icon>
            <span>Loading...</span>
        `);

        // Prepare data
        const postData = {
            _token: "{{ csrf_token() }}",
            dari: dari,
            sampai: sampai
        };

        console.log('Sending AJAX request with data:', postData);

        // Load histori via AJAX
        $.ajax({
            type: 'POST',
            url: '/gethistori',
            data: postData,
            cache: false,
            success: function(respond) {
                console.log('AJAX Success - Response received');
                $("#showhistori").html(respond);

                // Format tanggal untuk label
                const fromDate = formatDisplayDate(selectedStartDate);
                const toDate = formatDisplayDate(selectedEndDate);

                $("#period-label").text(fromDate + " - " + toDate);
                $("#history-header").show();

                // Load statistik
                loadStatistik(dari, sampai);

                // Update export link
                $("#btn-export").attr('href', '/presensi/histori/export-excel?dari=' + dari + '&sampai=' + sampai);
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', {
                    status: status,
                    error: error,
                    response: xhr.responseText
                });

                $("#showhistori").html(`
                    <div class="empty-state">
                        <ion-icon name="alert-circle-outline"></ion-icon>
                        <p>Gagal memuat data. Silakan coba lagi.</p>
                        <small style="color: #ef4444; font-size: 11px; margin-top: 8px; display: block;">Error: ${error}</small>
                    </div>
                `);
            },
            complete: function() {
                console.log('AJAX Complete');
                // Enable button
                $("#getdata").prop('disabled', false).html(`
                    <ion-icon name="search-outline"></ion-icon>
                    <span>Tampilkan Data</span>
                `);
            }
        });
    }

    // Function untuk format tanggal ke Y-m-d
    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    // Function untuk format tanggal display
    function formatDisplayDate(date) {
        const options = {
            day: 'numeric',
            month: 'short',
            year: 'numeric'
        };
        return date.toLocaleDateString('id-ID', options);
    }

    // Function untuk load statistik
    function loadStatistik(dari, sampai) {
        console.log('Loading statistik untuk:', dari, 'sampai', sampai);

        $.ajax({
            type: 'GET',
            url: '/presensi/histori/statistik',
            data: {
                dari: dari,
                sampai: sampai
            },
            success: function(response) {
                console.log('Statistik loaded:', response);

                if (response.success) {
                    var data = response.data;
                    var statsHtml = `
                        <div class="stat-item hadir">
                            <div class="stat-value">${data.total_hadir}</div>
                            <div class="stat-label">Hadir</div>
                        </div>
                        <div class="stat-item terlambat">
                            <div class="stat-value">${data.total_terlambat}</div>
                            <div class="stat-label">Telat</div>
                        </div>
                        <div class="stat-item izin">
                            <div class="stat-value">${data.total_izin}</div>
                            <div class="stat-label">Izin</div>
                        </div>
                        <div class="stat-item sakit">
                            <div class="stat-value">${data.total_sakit}</div>
                            <div class="stat-label">Sakit</div>
                        </div>
                        <div class="stat-item cuti">
                            <div class="stat-value">${data.total_cuti}</div>
                            <div class="stat-label">Cuti</div>
                        </div>
                        <div class="stat-item alpa">
                            <div class="stat-value">${data.total_alpa}</div>
                            <div class="stat-label">Alpa</div>
                        </div>
                    `;
                    $("#stats-grid").html(statsHtml);
                    $("#stats-container").show();
                }
            },
            error: function(xhr, status, error) {
                console.error('Statistik Error:', error);
            }
        });
    }
</script>
@endpush