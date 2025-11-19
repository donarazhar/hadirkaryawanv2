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
$jml_jam = $jam[0];
return $jml_jam . ':' . round($sisamenit2);
}
@endphp

<style>
    .history-empty {
        text-align: center;
        padding: 60px 20px;
        color: #64748b;
    }

    .history-empty ion-icon {
        font-size: 80px;
        color: #cbd5e1;
        margin-bottom: 16px;
    }

    .history-empty h3 {
        font-size: 16px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 8px;
    }

    .history-empty p {
        font-size: 14px;
        margin: 0;
    }

    .history-item {
        background: white;
        border-radius: 16px;
        padding: 16px;
        margin-bottom: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(0, 0, 0, 0.04);
        border-left: 4px solid var(--status-color);
    }

    .history-item.status-hadir {
        --status-color: #10b981;
    }

    .history-item.status-izin {
        --status-color: #f59e0b;
    }

    .history-item.status-sakit {
        --status-color: #06b6d4;
    }

    .history-item.status-cuti {
        --status-color: #8b5cf6;
    }

    .history-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f1f5f9;
    }

    .history-date {
        font-size: 14px;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .history-date ion-icon {
        font-size: 18px;
        color: #0053C5;
    }

    .history-status {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .history-status.hadir {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .history-status.izin {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    .history-status.sakit {
        background: rgba(6, 182, 212, 0.1);
        color: #06b6d4;
    }

    .history-status.cuti {
        background: rgba(139, 92, 246, 0.1);
        color: #8b5cf6;
    }

    .history-body {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .history-col {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .history-time {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .history-time-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .history-time-icon.in {
        background: rgba(16, 185, 129, 0.1);
    }

    .history-time-icon.out {
        background: rgba(239, 68, 68, 0.1);
    }

    .history-time-icon ion-icon {
        font-size: 18px;
    }

    .history-time-icon.in ion-icon {
        color: #10b981;
    }

    .history-time-icon.out ion-icon {
        color: #ef4444;
    }

    .history-time-info {
        flex: 1;
    }

    .history-time-label {
        font-size: 11px;
        color: #64748b;
        font-weight: 600;
    }

    .history-time-value {
        font-size: 13px;
        font-weight: 700;
        color: #1e293b;
    }

    .history-photo {
        width: 100%;
        aspect-ratio: 4/3;
        border-radius: 10px;
        object-fit: cover;
        border: 2px solid #f1f5f9;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .history-photo:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .history-footer {
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .history-keterangan {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        padding: 6px 10px;
        border-radius: 8px;
    }

    .history-keterangan.telat {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .history-keterangan.tepat {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .history-keterangan ion-icon {
        font-size: 14px;
    }

    .btn-map-small {
        padding: 8px 12px;
        background: linear-gradient(135deg, #0053C5 0%, #003d94 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 4px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-map-small:active {
        transform: scale(0.95);
    }

    .btn-map-small ion-icon {
        font-size: 16px;
    }

    @media (max-width: 375px) {
        .history-body {
            grid-template-columns: 1fr;
        }
    }
</style>

@if($histori->isEmpty())
<div class="history-empty">
    <ion-icon name="calendar-outline"></ion-icon>
    <h3>Belum Ada Data Presensi</h3>
    <p>Tidak ada riwayat presensi pada periode yang dipilih</p>
</div>
@else
@foreach($histori as $d)
@php
$foto_in = Storage::url('uploads/absensi/' . $d->foto_in);
$foto_out = Storage::url('uploads/absensi/' . $d->foto_out);

$statusClass = '';
$statusText = '';
$statusIcon = '';

if ($d->status == 'h') {
$statusClass = 'hadir';
$statusText = 'Hadir';
$statusIcon = 'checkmark-circle';
} elseif ($d->status == 'i') {
$statusClass = 'izin';
$statusText = 'Izin';
$statusIcon = 'document-text';
} elseif ($d->status == 's') {
$statusClass = 'sakit';
$statusText = 'Sakit';
$statusIcon = 'medkit';
} elseif ($d->status == 'c') {
$statusClass = 'cuti';
$statusText = 'Cuti';
$statusIcon = 'calendar';
}
@endphp

<div class="history-item status-{{ $statusClass }}">
    <!-- Header -->
    <div class="history-header">
        <div class="history-date">
            <ion-icon name="calendar-outline"></ion-icon>
            {{ \Carbon\Carbon::parse($d->tgl_presensi)->isoFormat('dddd, D MMM Y') }}
        </div>
        <div class="history-status {{ $statusClass }}">
            <ion-icon name="{{ $statusIcon }}"></ion-icon>
            {{ $statusText }}
        </div>
    </div>

    @if($d->status == 'h')
    <!-- Body untuk status hadir -->
    <div class="history-body">
        <!-- Jam Masuk -->
        <div class="history-col">
            <div class="history-time">
                <div class="history-time-icon in">
                    <ion-icon name="log-in"></ion-icon>
                </div>
                <div class="history-time-info">
                    <div class="history-time-label">Jam Masuk</div>
                    <div class="history-time-value">{{ $d->jam_in }}</div>
                </div>
            </div>
            @if(!empty($d->foto_in))
            <img src="{{ url($foto_in) }}" class="history-photo" alt="Foto Masuk" onclick="previewImage('{{ url($foto_in) }}', 'Foto Masuk')">
            @endif
        </div>

        <!-- Jam Keluar -->
        <div class="history-col">
            <div class="history-time">
                <div class="history-time-icon out">
                    <ion-icon name="log-out"></ion-icon>
                </div>
                <div class="history-time-info">
                    <div class="history-time-label">Jam Pulang</div>
                    <div class="history-time-value">
                        {{ $d->jam_out ?? '-' }}
                    </div>
                </div>
            </div>
            @if(!empty($d->jam_out) && !empty($d->foto_out))
            <img src="{{ url($foto_out) }}" class="history-photo" alt="Foto Pulang" onclick="previewImage('{{ url($foto_out) }}', 'Foto Pulang')">
            @else
            <div style="width: 100%; aspect-ratio: 4/3; border-radius: 10px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; border: 2px dashed #cbd5e1;">
                <ion-icon name="time-outline" style="font-size: 32px; color: #94a3b8;"></ion-icon>
            </div>
            @endif
        </div>
    </div>

    <!-- Footer -->
    <div class="history-footer">
        @if($d->jam_in >= $d->jam_masuk)
        @php
        $jamterlambat = selisih($d->jam_masuk, $d->jam_in);
        @endphp
        <div class="history-keterangan telat">
            <ion-icon name="time-outline"></ion-icon>
            Terlambat {{ $jamterlambat }}
        </div>
        @else
        <div class="history-keterangan tepat">
            <ion-icon name="checkmark-circle-outline"></ion-icon>
            Tepat Waktu
        </div>
        @endif

        <button class="btn-map-small tampilkanpeta" data-id="{{ $d->id }}">
            <ion-icon name="location"></ion-icon>
            <span>Lihat Lokasi</span>
        </button>
    </div>
    @else
    <!-- Body untuk izin/sakit/cuti -->
    <div style="padding: 12px; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-radius: 10px;">
        <p style="margin: 0; font-size: 13px; color: #64748b;">
            <strong style="color: #1e293b;">Keterangan:</strong><br>
            {{ $d->keterangan }}
        </p>
        @if($d->status == 'c' && !empty($d->nama_cuti))
        <p style="margin: 8px 0 0 0; font-size: 12px; color: #8b5cf6; font-weight: 600;">
            Jenis: {{ $d->nama_cuti }}
        </p>
        @endif
    </div>
    @endif
</div>
@endforeach
@endif

<script>
    // Preview image
    function previewImage(src, title) {
        Swal.fire({
            title: title,
            imageUrl: src,
            imageAlt: title,
            showCloseButton: true,
            showConfirmButton: false,
            width: '90%',
            padding: '20px',
            customClass: {
                image: 'img-fluid rounded'
            }
        });
    }

    // Tampilkan peta
    $(function() {
        $(".tampilkanpeta").click(function(e) {
            e.preventDefault();
            var id = $(this).data("id");

            Swal.fire({
                title: 'Loading...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                type: 'POST',
                url: '/tampilkanpeta',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                cache: false,
                success: function(respond) {
                    Swal.close();

                    Swal.fire({
                        html: respond,
                        width: '90%',
                        showCloseButton: true,
                        showConfirmButton: false,
                        padding: '20px',
                        customClass: {
                            popup: 'rounded-lg'
                        }
                    });
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Gagal memuat peta',
                        confirmButtonColor: '#0053C5'
                    });
                }
            });
        });
    });
</script>
