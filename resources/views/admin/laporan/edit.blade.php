<form action="{{ route('panel.laporan.update') }}" method="POST">
    @csrf
    <input type="hidden" name="id" value="{{ $presensi->id }}">
    
    <div class="mb-3">
        <label class="form-label">Karyawan</label>
        <input type="text" class="form-control" value="{{ $presensi->nama_lengkap }} ({{ $presensi->nik }})" readonly>
    </div>

    <div class="mb-3">
        <label class="form-label">Tanggal Presensi</label>
        <input type="text" class="form-control" value="{{ date('d-m-Y', strtotime($presensi->tgl_presensi)) }}" readonly>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Jam Masuk (In)</label>
            <input type="time" name="jam_in" class="form-control" value="{{ $presensi->jam_in }}">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Jam Pulang (Out)</label>
            <input type="time" name="jam_out" class="form-control" value="{{ $presensi->jam_out }}">
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Status Kehadiran</label>
        <select name="status" class="form-select">
            <option value="h" {{ $presensi->status == 'h' ? 'selected' : '' }}>Hadir</option>
            <option value="i" {{ $presensi->status == 'i' ? 'selected' : '' }}>Izin</option>
            <option value="s" {{ $presensi->status == 's' ? 'selected' : '' }}>Sakit</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Keterangan / Alasan Perubahan</label>
        <input type="text" name="keterangan" class="form-control" value="{{ $presensi->keterangan }}" placeholder="Contoh: Lupa absen, mesin error">
    </div>

    <div class="d-flex justify-content-end mt-4">
        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </div>
</form>
