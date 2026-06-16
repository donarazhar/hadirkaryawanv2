@extends('admin.layouts.admin')

@section('title', 'Detail Verifikasi Wajah')
@section('page-title', 'Detail Verifikasi Wajah')

@push('styles')
<style>
    :root {
        --blue:       #2563EB;
        --blue-dark:  #1D4ED8;
        --blue-soft:  #EFF6FF;
        --blue-mid:   #BFDBFE;
        --green:      #10B981;
        --green-soft: #ECFDF5;
        --red:        #EF4444;
        --red-soft:   #FEF2F2;
        --amber:      #F59E0B;
        --amber-soft: #FFFBEB;
        --purple:     #8B5CF6;
        --purple-soft:#F5F3FF;
        --slate-900:  #111827;
        --slate-700:  #374151;
        --slate-600:  #4B5563;
        --slate-400:  #9CA3AF;
        --slate-300:  #D1D5DB;
        --slate-200:  #E5E7EB;
        --slate-100:  #F3F4F6;
        --slate-50:   #F9FAFB;
        --white:      #FFFFFF;
        --shadow:     0 1px 3px rgba(0,0,0,0.06),0 1px 2px rgba(0,0,0,0.04);
        --radius:     14px;
        --radius-sm:  10px;
    }

    .face-wrap { display: flex; flex-direction: column; gap: 20px; }

    /* ── PAGE HEADER ── */
    .face-header {
        background: var(--white);
        border: 1px solid var(--slate-200);
        border-radius: var(--radius);
        padding: 20px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        box-shadow: var(--shadow);
        flex-wrap: wrap;
    }

    .face-header-left { display: flex; align-items: center; gap: 14px; }

    .face-header-icon {
        width: 46px; height: 46px;
        border-radius: 12px;
        background: var(--blue-soft);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .face-header-icon i { font-size: 22px; color: var(--blue); }

    .face-header-title { font-size: 17px; font-weight: 800; color: var(--slate-900); letter-spacing: -0.2px; }
    .face-header-sub   { font-size: 12px; color: var(--slate-400); margin-top: 2px; }

    .btn-hdr {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border: 1.5px solid var(--slate-200);
        border-radius: 9px;
        font-family: 'Inter', sans-serif;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.15s;
        text-decoration: none;
        white-space: nowrap;
        background: var(--white);
        color: var(--slate-700);
    }
    .btn-hdr i { font-size: 17px; }
    .btn-hdr:hover { background: var(--slate-50); color: var(--slate-900); border-color: var(--slate-300); }

    /* ── ALERTS ── */
    .alert-c {
        display: flex; align-items: center; gap: 10px; padding: 12px 16px;
        border-radius: var(--radius-sm); font-size: 13px; font-weight: 600; margin-bottom: 5px;
    }
    .alert-c i { font-size: 18px; flex-shrink: 0; }
    .alert-success-c { background: var(--green-soft); color: #065F46; border: 1px solid #A7F3D0; }
    .alert-danger-c  { background: var(--red-soft);   color: #991B1B; border: 1px solid #FECACA; }
    .alert-warning-c { background: var(--amber-soft); color: #B45309; border: 1px solid #FDE68A; }

    /* ── LAYOUT ── */
    .face-grid {
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 20px;
        align-items: start;
    }
    @media (max-width: 992px) { .face-grid { grid-template-columns: 1fr; } }

    /* ── PROFILE CARD ── */
    .prof-card {
        background: var(--white);
        border: 1px solid var(--slate-200);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
        position: sticky;
        top: 20px;
    }

    .prof-top {
        padding: 30px 20px 20px;
        display: flex; flex-direction: column; align-items: center; text-align: center;
        border-bottom: 1px solid var(--slate-100);
    }
    .prof-ava {
        width: 110px; height: 110px; border-radius: 50%; object-fit: cover;
        border: 4px solid var(--white); box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        margin-bottom: 16px;
    }
    .prof-ava-init {
        width: 110px; height: 110px; border-radius: 50%;
        background: var(--blue-soft); color: var(--blue);
        display: flex; align-items: center; justify-content: center;
        font-size: 36px; font-weight: 800; border: 4px solid var(--white);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin-bottom: 16px;
    }
    .prof-name { font-size: 18px; font-weight: 800; color: var(--slate-900); letter-spacing: -0.3px; margin-bottom: 4px; }
    .prof-jabatan { font-size: 13px; font-weight: 600; color: var(--blue); background: var(--blue-soft); padding: 4px 12px; border-radius: 50px; }

    .prof-list { padding: 0; margin: 0; list-style: none; }
    .prof-list li {
        padding: 14px 20px; border-bottom: 1px solid var(--slate-100);
        display: flex; flex-direction: column; gap: 4px;
    }
    .prof-list li:last-child { border-bottom: none; }
    .pl-label { font-size: 11px; font-weight: 700; color: var(--slate-400); text-transform: uppercase; letter-spacing: 0.5px; }
    .pl-val { font-size: 13.5px; font-weight: 600; color: var(--slate-900); }

    /* ── RIGHT CONTENT ── */
    .face-content { display: flex; flex-direction: column; gap: 20px; }

    .card { background: var(--white); border: 1px solid var(--slate-200); border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; }
    .card-head { padding: 16px 20px; border-bottom: 1px solid var(--slate-100); display: flex; align-items: center; gap: 8px; }
    .card-head i { font-size: 18px; color: var(--blue); }
    .card-title { font-size: 14px; font-weight: 800; color: var(--slate-900); m-0; }
    .card-body { padding: 20px; }

    /* Stats Verification */
    .v-stat-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px; }
    @media (max-width: 576px) { .v-stat-grid { grid-template-columns: 1fr; } }

    .v-stat-box {
        background: var(--slate-50); border: 1px solid var(--slate-200);
        border-radius: 12px; padding: 16px; display: flex; align-items: center; gap: 16px;
    }
    .vs-icon {
        width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px;
    }
    .vs-icon.ic-green { background: var(--green-soft); color: var(--green); }
    .vs-icon.ic-red { background: var(--red-soft); color: var(--red); }
    .vs-icon.ic-blue { background: var(--blue-soft); color: var(--blue); }

    .vs-label { font-size: 11.5px; font-weight: 700; color: var(--slate-500); text-transform: uppercase; margin-bottom: 2px; }
    .vs-val { font-size: 20px; font-weight: 800; letter-spacing: -0.5px; line-height: 1; }
    .vs-val.v-green { color: var(--green); }
    .vs-val.v-red { color: var(--red); }
    .vs-val.v-blue { color: var(--slate-900); }

    .update-info { font-size: 13px; color: var(--slate-600); margin-bottom: 20px; padding: 12px; background: var(--slate-50); border-radius: 8px; border: 1px solid var(--slate-200); }
    .update-info strong { color: var(--slate-900); }

    .ref-img-wrap { text-align: center; margin-bottom: 24px; }
    .ref-img-wrap img { max-width: 100%; max-height: 300px; border-radius: 12px; border: 2px solid var(--slate-200); box-shadow: 0 4px 12px rgba(0,0,0,0.08); }

    /* Action Buttons Area */
    .action-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px; }
    @media (max-width: 576px) { .action-grid { grid-template-columns: 1fr; } }

    .btn-ac {
        width: 100%; display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        height: 44px; border-radius: 10px; font-family: 'Inter', sans-serif; font-size: 13.5px; font-weight: 700;
        cursor: pointer; transition: all 0.15s; border: none; text-decoration: none;
    }
    .btn-ac i { font-size: 18px; }
    .btn-ac-green { background: var(--green); color: var(--white); }
    .btn-ac-green:hover { background: #059669; }
    .btn-ac-amber { background: var(--amber); color: var(--white); }
    .btn-ac-amber:hover { background: #D97706; }
    .btn-ac-red { background: var(--red-soft); color: var(--red); border: 1.5px solid #FECACA; }
    .btn-ac-red:hover { background: #FEE2E2; }

    /* Table History */
    .tbl-wrap { overflow-x: auto; }
    .tbl-wrap table { width: 100%; border-collapse: collapse; min-width: 500px; }
    .tbl-wrap thead th {
        padding: 12px 16px; background: var(--slate-50); font-size: 10.5px;
        font-weight: 700; color: var(--slate-400); text-transform: uppercase;
        letter-spacing: 0.5px; border-bottom: 1px solid var(--slate-200); white-space: nowrap; text-align: left;
    }
    .tbl-wrap tbody td {
        padding: 14px 16px; font-size: 13px; color: var(--slate-700);
        border-bottom: 1px solid var(--slate-100); vertical-align: middle; font-weight: 600;
    }
    .tbl-wrap tbody tr:last-child td { border-bottom: none; }

    .time-badge { display: inline-flex; padding: 4px 10px; border-radius: 6px; font-family: monospace; font-size: 13px; font-weight: 700; }
    .tb-in { background: var(--green-soft); color: var(--green); border: 1px solid #A7F3D0; }
    .tb-out { background: var(--blue-soft); color: var(--blue); border: 1px solid var(--blue-mid); }
    .tb-null { color: var(--slate-400); }

    .stat-pill { display: inline-flex; padding: 4px 10px; border-radius: 50px; font-size: 11px; font-weight: 700; }
    .sp-ok { background: var(--green-soft); color: var(--green); }
    .sp-fail { background: var(--red-soft); color: var(--red); }
</style>
@endpush

@section('content')
<div class="face-wrap">

    {{-- HEADER --}}
    <div class="face-header">
        <div class="face-header-left">
            <div class="face-header-icon">
                <i class="mdi mdi-face-recognition"></i>
            </div>
            <div>
                <div class="face-header-title">Detail Verifikasi Wajah</div>
                <div class="face-header-sub">Lihat detail dan kelola status wajah karyawan</div>
            </div>
        </div>
        <div>
            <a href="{{ route('panel.face-verification.index') }}" class="btn-hdr">
                <i class="mdi mdi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="alert-c alert-success-c"><i class="mdi mdi-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-c alert-danger-c"><i class="mdi mdi-alert-circle"></i> {{ session('error') }}</div>
    @endif

    {{-- LAYOUT --}}
    <div class="face-grid">
        
        {{-- LEFT COLUMN: PROFILE --}}
        <div>
            <div class="prof-card">
                <div class="prof-top">
                    @if($karyawan->foto)
                        <img src="{{ Storage::url('uploads/karyawan/'.$karyawan->foto) }}" class="prof-ava" alt="Foto">
                    @else
                        <div class="prof-ava-init">{{ strtoupper(substr($karyawan->nama_lengkap, 0, 2)) }}</div>
                    @endif
                    <div class="prof-name">{{ $karyawan->nama_lengkap }}</div>
                    <div class="prof-jabatan">{{ $karyawan->jabatan }}</div>
                </div>
                <ul class="prof-list">
                    <li>
                        <span class="pl-label">NIK</span>
                        <span class="pl-val">{{ $karyawan->nik }}</span>
                    </li>
                    <li>
                        <span class="pl-label">Departemen</span>
                        <span class="pl-val">{{ $karyawan->departemen->nama_dept ?? '-' }}</span>
                    </li>
                    <li>
                        <span class="pl-label">Cabang</span>
                        <span class="pl-val">{{ $karyawan->cabang->nama_cabang ?? '-' }}</span>
                    </li>
                    <li>
                        <span class="pl-label">No. HP</span>
                        <span class="pl-val">{{ $karyawan->no_hp ?? '-' }}</span>
                    </li>
                </ul>
            </div>
        </div>

        {{-- RIGHT COLUMN: FACE DATA & HISTORY --}}
        <div class="face-content">
            
            {{-- FACE DATA --}}
            <div class="card">
                <div class="card-head">
                    <i class="mdi mdi-shield-account"></i>
                    <h3 class="card-title">Informasi Verifikasi Wajah</h3>
                </div>
                <div class="card-body">
                    @if($karyawan->faceData)
                        <div class="v-stat-grid">
                            <div class="v-stat-box">
                                @if($karyawan->faceData->status == 'active')
                                    <div class="vs-icon ic-green"><i class="mdi mdi-check-circle"></i></div>
                                    <div>
                                        <div class="vs-label">Status Akses</div>
                                        <div class="vs-val v-green">Aktif</div>
                                    </div>
                                @else
                                    <div class="vs-icon ic-red"><i class="mdi mdi-close-circle"></i></div>
                                    <div>
                                        <div class="vs-label">Status Akses</div>
                                        <div class="vs-val v-red">Non-Aktif</div>
                                    </div>
                                @endif
                            </div>
                            <div class="v-stat-box">
                                <div class="vs-icon ic-blue"><i class="mdi mdi-scan-helper"></i></div>
                                <div>
                                    <div class="vs-label">Jumlah Enrollment</div>
                                    <div class="vs-val v-blue">{{ $karyawan->faceData->enrollment_count }}<span style="font-size:14px; font-weight:600; color:var(--slate-400);">x</span></div>
                                </div>
                            </div>
                        </div>

                        <div class="update-info">
                            Terakhir Update: <strong>{{ \Carbon\Carbon::parse($karyawan->faceData->last_updated)->format('d F Y, H:i:s') }}</strong> 
                            <span style="color:var(--slate-400);">({{ \Carbon\Carbon::parse($karyawan->faceData->last_updated)->diffForHumans() }})</span>
                        </div>

                        @if($karyawan->faceData->face_image)
                            <div class="ref-img-wrap">
                                <div style="font-size:12px; font-weight:700; color:var(--slate-500); text-transform:uppercase; margin-bottom:8px;">Foto Referensi AI</div>
                                <img src="{{ route('panel.face-verification.view-image', $karyawan->nik) }}" alt="Face Reference">
                            </div>
                        @endif

                        <div class="action-grid">
                            @if($karyawan->faceData->status == 'active')
                                <form action="{{ route('panel.face-verification.deactivate', $karyawan->nik) }}" method="POST">
                                    @csrf @method('PUT')
                                    <button type="submit" class="btn-ac btn-ac-amber" onclick="return confirm('Yakin ingin menonaktifkan data wajah?')">
                                        <i class="mdi mdi-cancel"></i> Nonaktifkan
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('panel.face-verification.activate', $karyawan->nik) }}" method="POST">
                                    @csrf @method('PUT')
                                    <button type="submit" class="btn-ac btn-ac-green" onclick="return confirm('Yakin ingin mengaktifkan data wajah?')">
                                        <i class="mdi mdi-check-circle-outline"></i> Aktifkan
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('panel.face-verification.destroy', $karyawan->nik) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-ac btn-ac-red" onclick="return confirm('Yakin ingin mengizinkan karyawan merekam wajah baru? Data saat ini akan dihapus.')">
                                    <i class="mdi mdi-refresh"></i> Reset & Izinkan Rekam Baru
                                </button>
                            </form>
                        </div>

                    @else
                        {{-- NO DATA --}}
                        <div class="alert-c alert-warning-c">
                            <i class="mdi mdi-alert-circle"></i>
                            <div>
                                <div style="font-size:14px; font-weight:800; margin-bottom:2px;">Belum Terdaftar</div>
                                <div style="font-weight:500;">Karyawan ini belum mendaftarkan data wajahnya ke sistem.</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- HISTORY TABLE --}}
            @if($presensiHistory->isNotEmpty())
            <div class="card">
                <div class="card-head">
                    <i class="mdi mdi-history"></i>
                    <h3 class="card-title">Riwayat Presensi Wajah (10 Terakhir)</h3>
                </div>
                <div class="tbl-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jam Masuk</th>
                                <th>Jam Pulang</th>
                                <th>Status AI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($presensiHistory as $p)
                            <tr>
                                <td style="color:var(--slate-900);">{{ \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y') }}</td>
                                <td>
                                    @if($p->jam_masuk)
                                        <div class="time-badge tb-in">{{ \Carbon\Carbon::parse($p->jam_masuk)->format('H:i') }}</div>
                                    @else
                                        <span class="tb-null">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($p->jam_pulang)
                                        <div class="time-badge tb-out">{{ \Carbon\Carbon::parse($p->jam_pulang)->format('H:i') }}</div>
                                    @else
                                        <span class="tb-null">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($p->status == 'verified')
                                        <div class="stat-pill sp-ok">Verified</div>
                                    @else
                                        <div class="stat-pill sp-fail">Failed</div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

        </div>
    </div>

</div>
@endsection