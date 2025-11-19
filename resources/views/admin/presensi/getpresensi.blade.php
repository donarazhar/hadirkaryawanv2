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
    /* ===== MODERN TABLE PRESENSI ===== */
    .table-wrapper {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .table-modern {
        width: 100%;
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-modern thead {
        background: linear-gradient(135deg, #0053C5 0%, #003d94 100%);
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .table-modern thead th {
        padding: 14px 12px;
        font-weight: 600;
        border: none;
        font-size: 12px;
        color: white;
        text-align: center;
        white-space: nowrap;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table-modern tbody td {
        padding: 12px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 13px;
        background: white;
    }

    .table-modern tbody tr {
        transition: all 0.2s ease;
    }

    .table-modern tbody tr:hover {
        background: #f8fafc;
        transform: scale(1.01);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .table-modern tbody tr:last-child td {
        border-bottom: none;
    }

    /* Avatar & Photo */
    .avatar-cell {
        text-align: center;
        padding: 8px !important;
    }

    .avatar {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        object-fit: cover;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid #f1f5f9;
    }

    .avatar:hover {
        transform: scale(1.8);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
        z-index: 100;
        border-color: #0053C5;
    }

    .avatar-placeholder {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }

    .avatar-placeholder ion-icon {
        font-size: 24px;
        color: #94a3b8;
    }

    /* Badges */
    .badge-modern {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .badge-modern ion-icon {
        font-size: 14px;
    }

    .badge-dept {
        background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
        color: white;
    }

    .badge-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .badge-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }

    .badge-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }

    .badge-info {
        background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
        color: white;
    }

    .badge-primary {
        background: linear-gradient(135deg, #0053C5 0%, #003d94 100%);
        color: white;
    }

    .badge-secondary {
        background: linear-gradient(135deg, #64748b 0%, #475569 100%);
        color: white;
    }

    /* Buttons */
    .btn-map {
        background: linear-gradient(135deg, #0053C5 0%, #003d94 100%);
        color: white;
        border: none;
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        box-shadow: 0 2px 6px rgba(0, 83, 197, 0.3);
    }

    .btn-map:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 83, 197, 0.4);
    }

    .btn-map:active {
        transform: translateY(0);
    }

    .btn-map ion-icon {
        font-size: 16px;
    }

    /* Info Cells */
    .nik-cell {
        font-weight: 700;
        color: #0053C5;
    }

    .nama-cell {
        font-weight: 600;
        color: #1e293b;
    }

    .jadwal-cell {
        font-size: 11px;
        line-height: 1.4;
    }

    .jadwal-cell strong {
        color: #1e293b;
        display: block;
        margin-bottom: 2px;
    }

    .jadwal-cell span {
        color: #64748b;
    }

    .keterangan-cell {
        max-width: 200px;
        font-size: 12px;
        color: #64748b;
        line-height: 1.4;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state ion-icon {
        font-size: 80px;
        color: #cbd5e1;
        margin-bottom: 16px;
    }

    .empty-state h3 {
        font-size: 16px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 8px;
    }

    .empty-state p {
        font-size: 14px;
        color: #64748b;
        margin: 0;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .table-wrapper {
            overflow-x: auto;
        }

        .table-modern {
            min-width: 1200px;
        }
    }
</style>

<div class="table-wrapper">
    <table class="table-modern">
        <thead>
            <tr>
                <th style="width: 50px;">No</th>
                <th style="width: 80px;">NIK</th>
                <th style="width: 150px;">Nama</th>
                <th style="width: 80px;">Dept</th>
                <th style="width: 120px;">Jadwal</th>
                <th style="width: 80px;">Jam In</th>
                <th style="width: 80px;">Foto In</th>
                <th style="width: 80px;">Jam Out</th>
                <th style="width: 80px;">Foto Out</th>
                <th style="width: 100px;">Status</th>
                <th style="width: 200px;">Keterangan</th>
                <th style="width: 80px;">Lokasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($presensi as $d)
            @php
            $foto_in = Storage::url('uploads/absensi/' . $d->foto_in);
            $foto_out = Storage::url('uploads/absensi/' . $d->foto_out);
            @endphp

            @if ($d->status == 'h')
            <!-- HADIR -->
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="nik-cell text-center">{{ $d->nik }}</td>
                <td class="nama-cell">{{ $d->nama_lengkap }}</td>
                <td class="text-center">
                    <span class="badge-modern badge-dept">{{ $d->kode_dept }}</span>
                </td>
                <td class="jadwal-cell">
                    <strong>{{ $d->nama_jam_kerja }}</strong>
                    <span>{{ date('H:i', strtotime($d->jam_masuk)) }} - {{ date('H:i', strtotime($d->jam_pulang)) }}</span>
                </td>
                <td class="text-center">
                    <span class="badge-modern badge-success">{{ $d->jam_in }}</span>
                </td>
                <td class="avatar-cell">
                    @if(!empty($d->foto_in))
                    <img src="{{ url($foto_in) }}" class="avatar" alt="Foto In" onclick="previewImage('{{ url($foto_in) }}', 'Foto Masuk - {{ $d->nama_lengkap }}')">
                    @else
                    <div class="avatar-placeholder">
                        <ion-icon name="image-outline"></ion-icon>
                    </div>
                    @endif
                </td>
                <td class="text-center">
                    @if($d->jam_out != null)
                    <span class="badge-modern badge-danger">{{ $d->jam_out }}</span>
                    @else
                    <span class="badge-modern badge-secondary">Belum</span>
                    @endif
                </td>
                <td class="avatar-cell">
                    @if($d->jam_out != null && !empty($d->foto_out))
                    <img src="{{ url($foto_out) }}" class="avatar" alt="Foto Out" onclick="previewImage('{{ url($foto_out) }}', 'Foto Pulang - {{ $d->nama_lengkap }}')">
                    @else
                    <div class="avatar-placeholder">
                        <ion-icon name="time-outline"></ion-icon>
                    </div>
                    @endif
                </td>
                <td class="text-center">
                    <span class="badge-modern badge-primary">
                        <ion-icon name="checkmark-circle"></ion-icon>
                        Hadir
                    </span>
                </td>
                <td>
                    @if ($d->jam_in >= $d->jam_masuk)
                    @php
                    $jamterlambat = selisih($d->jam_masuk, $d->jam_in);
                    @endphp
                    <span class="badge-modern badge-danger">
                        <ion-icon name="time"></ion-icon>
                        Terlambat {{ $jamterlambat }}
                    </span>
                    @else
                    <span class="badge-modern badge-success">
                        <ion-icon name="checkmark-circle"></ion-icon>
                        Tepat Waktu
                    </span>
                    @endif
                </td>
                <td class="text-center">
                    <button class="btn-map tampilkanpeta" data-id="{{ $d->id }}">
                        <ion-icon name="location"></ion-icon>
                        Map
                    </button>
                </td>
            </tr>
            @else
            <!-- IZIN/SAKIT/CUTI -->
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="nik-cell text-center">{{ $d->nik }}</td>
                <td class="nama-cell">{{ $d->nama_lengkap }}</td>
                <td class="text-center">
                    <span class="badge-modern badge-dept">{{ $d->kode_dept }}</span>
                </td>
                <td colspan="5" class="text-center" style="color: #94a3b8; font-style: italic;">
                    Tidak ada data kehadiran
                </td>
                <td class="text-center">
                    @if ($d->status == 'i')
                    <span class="badge-modern badge-warning">
                        <ion-icon name="document-text"></ion-icon>
                        Izin
                    </span>
                    @elseif ($d->status == 's')
                    <span class="badge-modern badge-info">
                        <ion-icon name="medkit"></ion-icon>
                        Sakit
                    </span>
                    @elseif ($d->status == 'c')
                    <span class="badge-modern badge-primary">
                        <ion-icon name="calendar"></ion-icon>
                        Cuti
                    </span>
                    @endif
                </td>
                <td class="keterangan-cell">
                    {{ $d->keterangan }}
                    @if($d->status == 'c' && !empty($d->nama_cuti))
                    <br><small style="color: #8b5cf6; font-weight: 600;">({{ $d->nama_cuti }})</small>
                    @endif
                </td>
                <td class="text-center" style="color: #94a3b8;">-</td>
            </tr>
            @endif
            @empty
            <tr>
                <td colspan="12">
                    <div class="empty-state">
                        <ion-icon name="calendar-outline"></ion-icon>
                        <h3>Tidak Ada Data Presensi</h3>
                        <p>Tidak ada data presensi yang ditemukan untuk periode yang dipilih</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    // Preview image dengan Swal
    function previewImage(src, title) {
        Swal.fire({
            title: title,
            imageUrl: src,
            imageAlt: title,
            showCloseButton: true,
            showConfirmButton: false,
            width: '80%',
            padding: '20px',
            background: '#fff',
            customClass: {
                image: 'img-fluid rounded-3'
            },
            imageWidth: '100%',
            imageHeight: 'auto'
        });
    }

    // Tampilkan peta
    $(function() {
        $(".tampilkanpeta").click(function(e) {
            e.preventDefault();
            var id = $(this).data("id");

            // Show loading
            Swal.fire({
                title: 'Memuat Peta...',
                html: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                allowEscapeKey: false,
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

                    // Show map in modal with Swal
                    Swal.fire({
                        html: respond,
                        width: '90%',
                        showCloseButton: true,
                        showConfirmButton: false,
                        padding: '20px',
                        background: '#fff',
                        customClass: {
                            popup: 'rounded-3'
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error loading map:', error);

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Memuat Peta',
                        text: 'Terjadi kesalahan saat memuat peta. Silakan coba lagi.',
                        confirmButtonColor: '#0053C5'
                    });
                }
            });
        });
    });
</script>