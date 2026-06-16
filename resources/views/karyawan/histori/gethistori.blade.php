@php
function selisih($jam_masuk, $jam_keluar)
{
    [$h, $m, $s] = explode(':', $jam_masuk);
    $dtAwal = mktime($h, $m, $s, '1', '1', '1');
    [$h, $m, $s] = explode(':', $jam_keluar);
    $dtAkhir = mktime($h, $m, $s, '1', '1', '1');
    $dtSelisih = $dtAkhir - $dtAwal;
    $totalmenit = $dtSelisih / 60;
    $jam = explode('.', $totalmenit / 60);
    $sisamenit = $totalmenit / 60 - $jam[0];
    $sisamenit2 = $sisamenit * 60;
    return $jam[0] . 'j ' . round($sisamenit2) . 'm';
}
@endphp

<style>
    /* ── HISTORY ITEM CARD ── */
    .h-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #F1F5F9;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        overflow: hidden;
        margin-bottom: 10px;
        transition: box-shadow 0.2s;
    }

    .h-card:active { box-shadow: 0 3px 10px rgba(0,0,0,0.08); }

    /* Accent stripe based on status */
    .h-card .h-stripe {
        height: 3px;
        width: 100%;
    }

    .h-card.s-hadir    .h-stripe { background: #10B981; }
    .h-card.s-izin     .h-stripe { background: #F59E0B; }
    .h-card.s-sakit    .h-stripe { background: #06B6D4; }
    .h-card.s-cuti     .h-stripe { background: #8B5CF6; }
    .h-card.s-alpa     .h-stripe { background: #9CA3AF; }

    /* ── Header row ── */
    .h-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 14px 10px;
    }

    .h-date {
        display: flex;
        align-items: center;
        gap: 7px;
        font-size: 13px;
        font-weight: 700;
        color: #111827;
    }

    .h-date-icon {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        background: #EFF6FF;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .h-date-icon ion-icon { font-size: 16px; color: #2563EB; }

    .h-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .h-badge ion-icon { font-size: 12px; }

    .h-badge.hadir  { background: #ECFDF5; color: #10B981; }
    .h-badge.izin   { background: #FFFBEB; color: #D97706; }
    .h-badge.sakit  { background: #ECFEFF; color: #0891B2; }
    .h-badge.cuti   { background: #F5F3FF; color: #7C3AED; }
    .h-badge.alpa   { background: #F9FAFB; color: #6B7280; }

    /* Divider */
    .h-div {
        height: 1px;
        background: #F8FAFC;
        margin: 0 14px;
    }

    /* ── Time row ── */
    .h-times {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0;
    }

    .h-time-col {
        padding: 10px 14px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .h-time-col + .h-time-col {
        border-left: 1px solid #F1F5F9;
    }

    .h-time-entry {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .h-time-dot {
        width: 30px;
        height: 30px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .h-time-dot.in  { background: #ECFDF5; }
    .h-time-dot.out { background: #FEF2F2; }
    .h-time-dot ion-icon { font-size: 16px; }
    .h-time-dot.in  ion-icon { color: #10B981; }
    .h-time-dot.out ion-icon { color: #EF4444; }

    .h-time-lbl  { font-size: 10px; font-weight: 600; color: #9CA3AF; line-height: 1; }
    .h-time-val  { font-size: 14px; font-weight: 800; color: #111827; line-height: 1; margin-top: 2px; }
    .h-time-val.muted { color: #9CA3AF; font-weight: 600; font-size: 13px; }

    /* ── Photo strip ── */
    .h-photos {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0;
    }

    .h-photo-wrap {
        padding: 0 14px 12px;
        cursor: pointer;
    }

    .h-photo-wrap + .h-photo-wrap {
        border-left: 1px solid #F1F5F9;
    }

    .h-photo {
        width: 100%;
        aspect-ratio: 4/3;
        object-fit: cover;
        border-radius: 10px;
        border: 1.5px solid #F1F5F9;
        transition: transform 0.2s, box-shadow 0.2s;
        display: block;
    }

    .h-photo:active { transform: scale(0.98); box-shadow: 0 4px 12px rgba(0,0,0,0.10); }

    .h-photo-empty {
        width: 100%;
        aspect-ratio: 4/3;
        border-radius: 10px;
        background: #F8FAFC;
        border: 1.5px dashed #CBD5E1;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .h-photo-empty ion-icon { font-size: 28px; color: #CBD5E1; }

    /* ── Footer ── */
    .h-foot {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 14px 12px;
    }

    .h-keterangan {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 10px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 700;
    }

    .h-keterangan ion-icon { font-size: 13px; }

    .h-keterangan.telat { background: #FEF2F2; color: #EF4444; }
    .h-keterangan.tepat { background: #ECFDF5; color: #10B981; }

    .h-duration {
        font-size: 11px;
        font-weight: 600;
        color: #9CA3AF;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .h-duration ion-icon { font-size: 13px; color: #2563EB; }

    .btn-map {
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 6px 11px;
        background: #EFF6FF;
        color: #2563EB;
        border: none;
        border-radius: 8px;
        font-family: 'Inter', -apple-system, sans-serif;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s;
        -webkit-tap-highlight-color: transparent;
    }

    .btn-map ion-icon { font-size: 14px; }
    .btn-map:active { background: #BFDBFE; }

    /* ── Non-hadir card body ── */
    .h-nonhadir-body {
        padding: 10px 14px 14px;
    }

    .h-nonhadir-info {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 10px 12px;
        border-radius: 10px;
        background: #F8FAFC;
        border: 1px solid #F1F5F9;
    }

    .h-nonhadir-icon { font-size: 20px; flex-shrink: 0; margin-top: 1px; }
    .h-nonhadir-icon.izin  { color: #D97706; }
    .h-nonhadir-icon.sakit { color: #0891B2; }
    .h-nonhadir-icon.cuti  { color: #7C3AED; }

    .h-nonhadir-text {
        font-size: 12px;
        line-height: 1.6;
        color: #4B5563;
        font-weight: 500;
    }

    /* ── Empty state ── */
    .h-empty {
        text-align: center;
        padding: 52px 24px;
        background: #fff;
        border-radius: 16px;
        border: 1px solid #F1F5F9;
    }

    .h-empty ion-icon {
        font-size: 60px;
        color: #CBD5E1;
        margin-bottom: 12px;
        display: block;
    }

    .h-empty h3 {
        font-size: 15px;
        font-weight: 700;
        color: #374151;
        margin-bottom: 6px;
    }

    .h-empty p {
        font-size: 13px;
        color: #9CA3AF;
        margin: 0;
    }

    @media (max-width: 360px) {
        .h-times, .h-photos { grid-template-columns: 1fr; }
        .h-time-col + .h-time-col { border-left: none; border-top: 1px solid #F1F5F9; }
        .h-photo-wrap + .h-photo-wrap { border-left: none; }
    }
</style>

@if($histori->isEmpty())

<div class="h-empty">
    <ion-icon name="calendar-outline"></ion-icon>
    <h3>Tidak Ada Data</h3>
    <p>Belum ada riwayat presensi<br>pada periode yang dipilih</p>
</div>

@else
@foreach($histori as $d)
@php
    $foto_in_path   = 'uploads/absensi/' . $d->foto_in;
    $foto_in_exists = !empty($d->foto_in) && Storage::disk('public')->exists($foto_in_path);

    $foto_out_path   = 'uploads/absensi/' . $d->foto_out;
    $foto_out_exists = !empty($d->foto_out) && Storage::disk('public')->exists($foto_out_path);

    switch($d->status) {
        case 'h': $sClass = 'hadir'; $sText = 'Hadir'; $sIcon = 'checkmark-circle'; break;
        case 'i': $sClass = 'izin';  $sText = 'Izin';  $sIcon = 'document-text';   break;
        case 's': $sClass = 'sakit'; $sText = 'Sakit'; $sIcon = 'medkit';          break;
        case 'c': $sClass = 'cuti';  $sText = 'Cuti';  $sIcon = 'leaf';            break;
        default:  $sClass = 'alpa';  $sText = 'Alpa';  $sIcon = 'close-circle';    break;
    }
@endphp

<div class="h-card s-{{ $sClass }}">
    {{-- Stripe --}}
    <div class="h-stripe"></div>

    {{-- Header --}}
    <div class="h-head">
        <div class="h-date">
            <div class="h-date-icon">
                <ion-icon name="calendar-outline"></ion-icon>
            </div>
            {{ \Carbon\Carbon::parse($d->tgl_presensi)->isoFormat('dddd, D MMM Y') }}
        </div>
        <span class="h-badge {{ $sClass }}">
            <ion-icon name="{{ $sIcon }}"></ion-icon>
            {{ $sText }}
        </span>
    </div>

    @if($d->status == 'h')
    {{-- ── HADIR ── --}}
    <div class="h-div"></div>

    {{-- Times --}}
    <div class="h-times">
        <div class="h-time-col">
            <div class="h-time-entry">
                <div class="h-time-dot in">
                    <ion-icon name="log-in-outline"></ion-icon>
                </div>
                <div>
                    <div class="h-time-lbl">Jam Masuk</div>
                    <div class="h-time-val">{{ $d->jam_in }}</div>
                </div>
            </div>
        </div>
        <div class="h-time-col">
            <div class="h-time-entry">
                <div class="h-time-dot out">
                    <ion-icon name="log-out-outline"></ion-icon>
                </div>
                <div>
                    <div class="h-time-lbl">Jam Pulang</div>
                    @if($d->jam_out)
                        <div class="h-time-val">{{ $d->jam_out }}</div>
                    @else
                        <div class="h-time-val muted">—</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Photos --}}
    <div class="h-div"></div>
    <div class="h-photos">
        {{-- Foto Masuk --}}
        <div class="h-photo-wrap">
            @if($foto_in_exists)
                <img src="{{ Storage::url($foto_in_path) }}" class="h-photo" alt="Foto Masuk"
                     onclick="previewImage('{{ Storage::url($foto_in_path) }}', 'Foto Masuk')"
                     onerror="this.src='{{ asset('assets/img/sample/avatar/noprofile.svg') }}'">
            @else
                <div class="h-photo-empty">
                    <ion-icon name="image-outline"></ion-icon>
                </div>
            @endif
        </div>

        {{-- Foto Pulang --}}
        <div class="h-photo-wrap">
            @if(!empty($d->jam_out) && $foto_out_exists)
                <img src="{{ Storage::url($foto_out_path) }}" class="h-photo" alt="Foto Pulang"
                     onclick="previewImage('{{ Storage::url($foto_out_path) }}', 'Foto Pulang')"
                     onerror="this.src='{{ asset('assets/img/sample/avatar/noprofile.svg') }}'">
            @else
                <div class="h-photo-empty">
                    <ion-icon name="time-outline"></ion-icon>
                </div>
            @endif
        </div>
    </div>

    {{-- Footer --}}
    <div class="h-div"></div>
    <div class="h-foot">
        @if($d->keterangan == 'T')
            <span class="h-keterangan telat">
                <ion-icon name="alert-circle-outline"></ion-icon>
                Terlambat
            </span>
        @else
            <span class="h-keterangan tepat">
                <ion-icon name="checkmark-circle-outline"></ion-icon>
                Tepat Waktu
            </span>
        @endif

        @if($d->jam_out)
            <span class="h-duration">
                <ion-icon name="time-outline"></ion-icon>
                {{ selisih($d->jam_in, $d->jam_out) }}
            </span>
        @endif

        <button type="button" class="btn-map tampilkanpeta" data-id="{{ $d->id }}">
            <ion-icon name="location-outline"></ion-icon>
            Lokasi
        </button>
    </div>

    @else
    {{-- ── NON-HADIR (Izin / Sakit / Cuti / Alpa) ── --}}
    <div class="h-div"></div>
    <div class="h-nonhadir-body">
        <div class="h-nonhadir-info">
            <ion-icon name="{{ $sIcon }}" class="h-nonhadir-icon {{ $sClass }}"></ion-icon>
            <div class="h-nonhadir-text">
                @if($d->status == 'i')
                    Karyawan mengambil izin pada tanggal ini.
                    @if(!empty($d->keterangan)) <br><strong>Ket:</strong> {{ $d->keterangan }} @endif
                @elseif($d->status == 's')
                    Karyawan tidak masuk karena sakit.
                    @if(!empty($d->keterangan)) <br><strong>Ket:</strong> {{ $d->keterangan }} @endif
                @elseif($d->status == 'c')
                    Karyawan sedang dalam periode cuti.
                    @if(!empty($d->keterangan)) <br><strong>Ket:</strong> {{ $d->keterangan }} @endif
                @else
                    Karyawan tidak hadir tanpa keterangan.
                @endif
            </div>
        </div>
    </div>
    @endif

</div>

@endforeach
@endif

<script>
    /* ── Preview image ── */
    function previewImage(src, title) {
        Swal.fire({
            title: title,
            imageUrl: src,
            imageAlt: title,
            showCloseButton: true,
            showConfirmButton: false,
            width: '92%',
            padding: '16px',
            customClass: { image: 'img-fluid rounded' }
        });
    }

    /* ── Tampilkan peta ── */
    $(function () {
        $('.tampilkanpeta').click(function (e) {
            e.preventDefault();
            var id = $(this).data('id');

            Swal.fire({ title: 'Memuat peta…', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

            $.ajax({
                type: 'POST', url: '/tampilkanpeta',
                data: { _token: '{{ csrf_token() }}', id: id },
                cache: false,
                success: function (respond) {
                    Swal.close();
                    Swal.fire({
                        html: respond, width: '92%',
                        showCloseButton: true, showConfirmButton: false, padding: '16px'
                    });
                },
                error: function () {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat memuat peta.', confirmButtonColor: '#2563EB' });
                }
            });
        });
    });
</script>