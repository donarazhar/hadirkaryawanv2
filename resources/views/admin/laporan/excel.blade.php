<table>
    <thead>
        <tr>
            <th colspan="7" style="text-align: center; font-weight: bold; font-size: 14px;">
                Laporan Presensi Karyawan Bulan {{ $namabulan }} Tahun {{ $tahun }}
            </th>
        </tr>
        <tr></tr>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>NIK</th>
            <th>Nama Karyawan</th>
            <th>Departemen</th>
            <th>Jam Masuk</th>
            <th>Jam Pulang</th>
            <th>Status</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($presensi as $d)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ date('d-m-Y', strtotime($d->tgl_presensi)) }}</td>
            <td>'{{ $d->nik }}</td>
            <td>{{ $d->nama_lengkap }}</td>
            <td>{{ $d->kode_dept }}</td>
            <td>{{ $d->jam_in }}</td>
            <td>{{ $d->jam_out }}</td>
            <td>
                @if($d->status == 'h') Hadir 
                @elseif($d->status == 'i') Izin 
                @elseif($d->status == 's') Sakit 
                @elseif($d->status == 'c') Cuti 
                @endif
            </td>
            <td>{{ $d->keterangan }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
