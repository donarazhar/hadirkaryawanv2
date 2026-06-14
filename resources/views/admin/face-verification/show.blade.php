@extends('admin.layouts.admin')

@section('title', 'Detail Verifikasi Wajah')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Detail</div>
                <h2 class="page-title">Verifikasi Wajah Karyawan</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('panel.face-verification.index') }}" class="btn btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                        <line x1="5" y1="12" x2="9" y2="16" />
                        <line x1="5" y1="12" x2="9" y2="8" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <div class="d-flex">
                <div>{{ session('success') }}</div>
            </div>
            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            <div class="d-flex">
                <div>{{ session('error') }}</div>
            </div>
            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
        @endif

        <div class="row row-cards">
            <!-- Informasi Karyawan -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        @if($karyawan->foto)
                        <img src="{{ Storage::url('uploads/karyawan/'.$karyawan->foto) }}"
                            class="rounded-circle mb-3" width="120" height="120"
                            style="object-fit: cover;">
                        @else
                        <div class="avatar avatar-xl mb-3" style="width: 120px; height: 120px; font-size: 48px;">
                            {{ substr($karyawan->nama_lengkap, 0, 2) }}
                        </div>
                        @endif

                        <h3 class="mb-1">{{ $karyawan->nama_lengkap }}</h3>
                        <div class="text-muted mb-3">{{ $karyawan->jabatan }}</div>

                        <div class="list-group list-group-flush">
                            <div class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="text-muted">NIK</span>
                                    </div>
                                    <div class="col text-end">
                                        <strong>{{ $karyawan->nik }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="text-muted">Departemen</span>
                                    </div>
                                    <div class="col text-end">
                                        {{ $karyawan->departemen->nama_dept ?? '-' }}
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="text-muted">Cabang</span>
                                    </div>
                                    <div class="col text-end">
                                        {{ $karyawan->cabang->nama_cabang ?? '-' }}
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="text-muted">No HP</span>
                                    </div>
                                    <div class="col text-end">
                                        {{ $karyawan->no_hp ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Face Data -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Verifikasi Wajah</h3>
                    </div>
                    <div class="card-body">
                        @if($karyawan->faceData)
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                @if($karyawan->faceData->status == 'active')
                                                <span class="avatar bg-success text-white">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M5 12l5 5l10 -10" />
                                                    </svg>
                                                </span>
                                                @else
                                                <span class="avatar bg-danger text-white">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <line x1="18" y1="6" x2="6" y2="18" />
                                                        <line x1="6" y1="6" x2="18" y2="18" />
                                                    </svg>
                                                </span>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="text-muted">Status</div>
                                                <div class="h3 mb-0">
                                                    @if($karyawan->faceData->status == 'active')
                                                    <span class="text-success">Aktif</span>
                                                    @else
                                                    <span class="text-danger">Non-aktif</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <span class="avatar bg-info text-white">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <circle cx="12" cy="12" r="9" />
                                                        <polyline points="12 7 12 12 15 15" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div>
                                                <div class="text-muted">Enrollment</div>
                                                <div class="h3 mb-0">{{ $karyawan->faceData->enrollment_count }}x</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="form-label fw-bold">Terakhir Update:</label>
                                <p class="text-muted">
                                    {{ \Carbon\Carbon::parse($karyawan->faceData->last_updated)->format('d F Y, H:i:s') }}
                                    ({{ \Carbon\Carbon::parse($karyawan->faceData->last_updated)->diffForHumans() }})
                                </p>
                            </div>
                        </div>

                        <!-- Face Image Preview -->
                        @if($karyawan->faceData->face_image)
                        <div class="card mb-4">
                            <div class="card-header">
                                <h4 class="card-title">Foto Referensi Wajah</h4>
                            </div>
                            <div class="card-body text-center">
                                <img src="{{ route('panel.face-verification.view-image', $karyawan->nik) }}"
                                    class="img-fluid rounded"
                                    style="max-height: 300px;"
                                    alt="Face Reference">
                            </div>
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 mb-4">
                            @if($karyawan->faceData->status == 'active')
                            <form action="{{ route('panel.face-verification.deactivate', $karyawan->nik) }}"
                                method="POST" class="flex-fill">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-warning w-100"
                                    onclick="return confirm('Yakin ingin menonaktifkan data wajah?')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <line x1="18" y1="6" x2="6" y2="18" />
                                        <line x1="6" y1="6" x2="18" y2="18" />
                                    </svg>
                                    Nonaktifkan
                                </button>
                            </form>
                            @else
                            <form action="{{ route('panel.face-verification.activate', $karyawan->nik) }}"
                                method="POST" class="flex-fill">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-success w-100"
                                    onclick="return confirm('Yakin ingin mengaktifkan data wajah?')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M5 12l5 5l10 -10" />
                                    </svg>
                                    Aktifkan
                                </button>
                            </form>
                            @endif

                            <form action="{{ route('panel.face-verification.destroy', $karyawan->nik) }}"
                                method="POST" class="flex-fill">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100"
                                    onclick="return confirm('Yakin ingin menghapus data wajah? Karyawan harus mendaftar ulang.')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <line x1="4" y1="7" x2="20" y2="7" />
                                        <line x1="10" y1="11" x2="10" y2="17" />
                                        <line x1="14" y1="11" x2="14" y2="17" />
                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                    </svg>
                                    Hapus & Reset
                                </button>
                            </form>
                        </div>

                        @else
                        <!-- Belum ada data -->
                        <div class="alert alert-warning mb-0">
                            <div class="d-flex">
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 9v2m0 4v.01" />
                                        <path d="M5 19h14a2 2 0 0 1 1.84 2.75l-7.1 12.25a2 2 0 0 1 -3.5 0l-7.1 -12.25a2 2 0 0 1 1.75 -2.75" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="alert-title">Belum Terdaftar</h4>
                                    <div class="text-muted">
                                        Karyawan ini belum mendaftarkan wajahnya.
                                        Silakan minta karyawan untuk melakukan enrollment melalui aplikasi mobile atau web.
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Riwayat Presensi Face -->
                @if($presensiHistory->isNotEmpty())
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Riwayat Presensi Face (10 Terakhir)</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-vcenter">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Jam Masuk</th>
                                        <th>Jam Pulang</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($presensiHistory as $p)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y') }}</td>
                                        <td>
                                            @if($p->jam_masuk)
                                            <span class="badge bg-success">
                                                {{ \Carbon\Carbon::parse($p->jam_masuk)->format('H:i') }}
                                            </span>
                                            @else
                                            <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($p->jam_pulang)
                                            <span class="badge bg-info">
                                                {{ \Carbon\Carbon::parse($p->jam_pulang)->format('H:i') }}
                                            </span>
                                            @else
                                            <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($p->status == 'verified')
                                            <span class="badge bg-success">Verified</span>
                                            @else
                                            <span class="badge bg-danger">Failed</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection