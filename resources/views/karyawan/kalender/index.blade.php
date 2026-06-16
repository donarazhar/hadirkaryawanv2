@extends('karyawan.layouts.presensi')
@section('header')
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Kalender Kehadiran</div>
    <div class="right"></div>
</div>
@endsection

@section('content')
<div class="section mt-2" style="padding-top: 60px;">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('karyawan.kalender') }}" method="GET" class="mb-3">
                <div class="row">
                    <div class="col-8">
                        <select name="bulan" id="bulan" class="form-control">
                            @for($i=1; $i<=12; $i++)
                                <option value="{{ $i }}" {{ $bulanini == $i ? 'selected' : '' }}>{{ $namabulan[$i] }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-4">
                        <select name="tahun" id="tahun" class="form-control">
                            @for($i = date('Y') - 2; $i <= date('Y') + 1; $i++)
                                <option value="{{ $i }}" {{ $tahunini == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <button class="btn btn-primary btn-block">Cari</button>
                    </div>
                </div>
            </form>

            <div class="kalender-wrapper">
                <style>
                    .kalender-grid {
                        display: grid;
                        grid-template-columns: repeat(7, 1fr);
                        gap: 5px;
                        text-align: center;
                    }
                    .kalender-header {
                        font-weight: bold;
                        color: #555;
                        padding: 10px 0;
                        font-size: 12px;
                    }
                    .kalender-header.sunday {
                        color: red;
                    }
                    .kalender-day {
                        height: 50px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        border-radius: 8px;
                        background: #f1f5f9;
                        position: relative;
                        font-weight: 500;
                        font-size: 14px;
                    }
                    .kalender-day.empty {
                        background: transparent;
                    }
                    .status-h { background: #10B981; color: white; }
                    .status-i { background: #3B82F6; color: white; }
                    .status-s { background: #F59E0B; color: white; }
                    .status-c { background: #8B5CF6; color: white; }
                    .status-libur { background: #EF4444; color: white; }
                    .status-alpa { background: #64748B; color: white; }
                    .legend {
                        display: flex;
                        gap: 10px;
                        justify-content: center;
                        flex-wrap: wrap;
                        margin-top: 15px;
                        font-size: 12px;
                    }
                    .legend-item { display: flex; align-items: center; gap: 4px; }
                    .legend-color { width: 12px; height: 12px; border-radius: 3px; }
                </style>
                
                <div class="kalender-grid">
                    <div class="kalender-header sunday">Min</div>
                    <div class="kalender-header">Sen</div>
                    <div class="kalender-header">Sel</div>
                    <div class="kalender-header">Rab</div>
                    <div class="kalender-header">Kam</div>
                    <div class="kalender-header">Jum</div>
                    <div class="kalender-header">Sab</div>

                    @php
                        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $bulanini, $tahunini);
                        $firstDay = date('w', strtotime("$tahunini-$bulanini-01"));
                        $todayDate = date('Y-m-d');
                    @endphp

                    @for ($i = 0; $i < $firstDay; $i++)
                        <div class="kalender-day empty"></div>
                    @endfor

                    @for ($day = 1; $day <= $daysInMonth; $day++)
                        @php
                            $currentDate = $tahunini . '-' . str_pad($bulanini, 2, '0', STR_PAD_LEFT) . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
                            $statusClass = '';
                            $status = isset($kalender_data[$currentDate]) ? $kalender_data[$currentDate] : '';
                            
                            $isSunday = date('w', strtotime($currentDate)) == 0;
                            $isHoliday = isset($harilibur[$currentDate]);

                            if ($status == 'h') $statusClass = 'status-h';
                            elseif ($status == 'i') $statusClass = 'status-i';
                            elseif ($status == 's') $statusClass = 'status-s';
                            elseif ($status == 'c') $statusClass = 'status-c';
                            elseif ($status == 'a') $statusClass = 'status-alpa';
                            elseif ($isHoliday || $isSunday) $statusClass = 'status-libur';
                            elseif ($currentDate < $todayDate) $statusClass = 'status-alpa'; // Jika lewat dan tidak ada absen = alpa
                        @endphp
                        <div class="kalender-day {{ $statusClass }}">
                            {{ $day }}
                        </div>
                    @endfor
                </div>

                <div class="legend">
                    <div class="legend-item"><div class="legend-color status-h"></div> Hadir</div>
                    <div class="legend-item"><div class="legend-color status-i"></div> Izin</div>
                    <div class="legend-item"><div class="legend-color status-s"></div> Sakit</div>
                    <div class="legend-item"><div class="legend-color status-c"></div> Cuti</div>
                    <div class="legend-item"><div class="legend-color status-alpa"></div> Alpa</div>
                    <div class="legend-item"><div class="legend-color status-libur"></div> Libur</div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
