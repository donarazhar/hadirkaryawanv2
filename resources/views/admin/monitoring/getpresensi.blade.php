@if ($presensi->isEmpty())
    <tr>
        <td colspan="5" class="text-center text-muted py-5">
            <i class="mdi mdi-inbox-outline fs-1 d-block mb-2"></i>
            Tidak ada data presensi pada tanggal ini
        </td>
    </tr>
@else
    @foreach ($presensi as $d)
        @php
            // Setup foto
            $foto_in = $d->foto_in;
            if ($foto_in && $foto_in !== 'face_api') {
                $foto_in = Storage::url('uploads/absensi/' . $foto_in);
            }
            $foto_out = $d->foto_out;
            if ($foto_out && $foto_out !== 'face_api') {
                $foto_out = Storage::url('uploads/absensi/' . $foto_out);
            }

            // Hitung Telat
            $jam_masuk_asli = $d->jam_masuk;
            $jam_in_absen = $d->jam_in;
            $status_telat = '';
            if ($jam_in_absen > $jam_masuk_asli && !empty($jam_in_absen)) {
                $status_telat = 'Telat';
            }
        @endphp

        <tr>
            <!-- Profil Karyawan -->
            <td>
                <div class="d-flex align-items-center">
                    <div>
                        <h6 class="mb-0 fw-bold">{{ $d->nama_lengkap }}</h6>
                        <span class="text-muted small">NIK: {{ $d->nik }} | Dept: {{ $d->nama_dept }}</span>
                    </div>
                </div>
            </td>

            <!-- Info Shift -->
            <td>
                <span class="badge bg-light text-dark border">
                    {{ $d->nama_jam_kerja }}
                </span>
                @if($d->shift_ke)
                    <br>
                    <span class="badge bg-info mt-1">Shift {{ $d->shift_ke }} ({{ $d->nama_shift }})</span>
                @endif
            </td>

            <!-- Check In -->
            <td>
                @if ($d->jam_in)
                    <div class="d-flex align-items-center">
                        @if($d->foto_in === 'face_api')
                            <div class="me-2 text-success" title="Face Verified">
                                <i class="mdi mdi-face-recognition fs-3"></i>
                            </div>
                        @else
                            <img src="{{ $foto_in ?: asset('assets/img/nophoto.jpg') }}" class="foto-presensi me-2" alt="Foto Masuk">
                        @endif
                        <div>
                            <span class="d-block fw-bold {{ $status_telat ? 'text-danger' : 'text-success' }}">
                                {{ date('H:i', strtotime($d->jam_in)) }}
                            </span>
                            @if ($status_telat)
                                <span class="badge status-telat">Terlambat</span>
                            @else
                                <span class="badge status-hadir">Tepat Waktu</span>
                            @endif
                        </div>
                    </div>
                @else
                    <span class="badge bg-secondary">Belum Absen</span>
                @endif
            </td>

            <!-- Check Out -->
            <td>
                @if ($d->jam_out)
                    <div class="d-flex align-items-center">
                        @if($d->foto_out === 'face_api')
                            <div class="me-2 text-success" title="Face Verified">
                                <i class="mdi mdi-face-recognition fs-3"></i>
                            </div>
                        @else
                            <img src="{{ $foto_out ?: asset('assets/img/nophoto.jpg') }}" class="foto-presensi me-2" alt="Foto Pulang">
                        @endif
                        <div>
                            <span class="d-block fw-bold text-primary">
                                {{ date('H:i', strtotime($d->jam_out)) }}
                            </span>
                        </div>
                    </div>
                @else
                    @if($d->jam_in)
                        <span class="badge bg-warning text-dark">Belum Pulang</span>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                @endif
            </td>

            <!-- Aksi / Lokasi -->
            <td>
                @if ($d->jam_in || $d->jam_out)
                    <button class="btn btn-sm btn-outline-primary" onclick="tampilkanpeta('{{ $d->id }}')">
                        <i class="mdi mdi-map-marker"></i> Lihat Peta
                    </button>
                @endif
                @if ($d->status == 'i')
                    <span class="badge status-izin ms-1">Izin: {{ $d->keterangan }}</span>
                @elseif($d->status == 's')
                    <span class="badge status-sakit ms-1">Sakit: {{ $d->keterangan }}</span>
                @endif
            </td>
        </tr>
    @endforeach
@endif
