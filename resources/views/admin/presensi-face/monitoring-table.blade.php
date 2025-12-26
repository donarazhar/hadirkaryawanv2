<div class="table-responsive">
    <table class="table table-hover monitoring-table mb-0">
        <thead class="table-light">
            <tr>
                <th width="5%">No</th>
                <th width="10%">Waktu</th>
                <th width="15%">NIK</th>
                <th width="20%">Nama</th>
                <th width="15%">Shift</th>
                <th width="10%">Jam Masuk</th>
                <th width="10%">Jam Pulang</th>
                <th width="10%">Cabang</th>
                <th width="5%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($presensi as $item)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>
                    <small class="text-muted">
                        {{ $item->created_at->format('H:i:s') }}
                    </small>
                </td>
                <td>
                    <strong>{{ $item->nik }}</strong>
                </td>
                <td>
                    {{ $item->karyawan->nama_lengkap ?? 'N/A' }}
                    <br>
                    <small class="text-muted">
                        {{ $item->karyawan->jabatan ?? '-' }}
                    </small>
                </td>
                <td>
                    @if($item->shift_ke)
                    <span class="badge shift-badge shift-{{ $item->shift_ke }}">
                        <i class="mdi mdi-numeric-{{ $item->shift_ke }}-box"></i>
                        {{ $item->nama_shift }}
                    </span>
                    @else
                    <span class="badge bg-secondary">
                        <i class="mdi mdi-clock-outline"></i>
                        Regular
                    </span>
                    @endif
                </td>
                <td>
                    @if($item->jam_masuk)
                    <i class="mdi mdi-login text-success"></i>
                    {{ \Carbon\Carbon::parse($item->jam_masuk)->format('H:i') }}
                    @else
                    <span class="text-muted">-</span>
                    @endif
                </td>
                <td>
                    @if($item->jam_pulang)
                    <i class="mdi mdi-logout text-danger"></i>
                    {{ \Carbon\Carbon::parse($item->jam_pulang)->format('H:i') }}
                    @else
                    <span class="text-muted">-</span>
                    @endif
                </td>
                <td>
                    <small>{{ $item->karyawan->cabang->nama_cabang ?? '-' }}</small>
                </td>
                <td class="text-center">
                    @if($item->status == 'verified')
                    <span class="badge bg-success">
                        <i class="mdi mdi-check-circle"></i>
                    </span>
                    @else
                    <span class="badge bg-danger">
                        <i class="mdi mdi-alert-circle"></i>
                    </span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center py-4 text-muted">
                    <i class="mdi mdi-information-outline" style="font-size: 2rem;"></i>
                    <div class="mt-2">Belum ada data presensi hari ini</div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>