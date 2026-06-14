@extends('admin.layouts.admin')

@section('title', 'Tambah User Baru')
@section('page-title', 'Tambah User')

@section('content')
<div class="page-header d-print-none mb-3">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Kelola Konfigurasi</div>
                <h2 class="page-title">Tambah User Baru</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('panel.user.index') }}" class="btn btn-outline-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                        <line x1="5" y1="12" x2="11" y2="18" />
                        <line x1="5" y1="12" x2="11" y2="6" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Tambah User</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('panel.user.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Nama Lengkap</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                    name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                    name="email" value="{{ old('email') }}" placeholder="Masukkan alamat email" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                    name="password" placeholder="Minimal 6 karakter" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Role User</label>
                                <select class="form-select @error('role') is-invalid @enderror" name="role" id="role-select" required>
                                    <option value="">Pilih Role...</option>
                                    <option value="superadmin" {{ old('role') == 'superadmin' ? 'selected' : '' }}>Superadmin (Semua Akses)</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin Cabang</option>
                                    <option value="pimpinan" {{ old('role') == 'pimpinan' ? 'selected' : '' }}>Pimpinan Cabang</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3" id="cabang-container" style="display: none;">
                                <label class="form-label required">Pilih Cabang</label>
                                <select class="form-select @error('kode_cabang') is-invalid @enderror" name="kode_cabang" id="cabang-select">
                                    <option value="">Pilih Cabang...</option>
                                    @foreach($cabang as $item)
                                        <option value="{{ $item->kode_cabang }}" {{ old('kode_cabang') == $item->kode_cabang ? 'selected' : '' }}>
                                            {{ $item->kode_cabang }} - {{ $item->nama_cabang }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-hint text-muted">Cabang wajib dipilih untuk role Admin dan Pimpinan.</small>
                                @error('kode_cabang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3" id="dept-container" style="display: none;">
                                <label class="form-label required">Pilih Departemen</label>
                                <select class="form-select @error('kode_dept') is-invalid @enderror" name="kode_dept" id="dept-select">
                                    <option value="">Pilih Departemen...</option>
                                    @foreach($departemen as $item)
                                        <option value="{{ $item->kode_dept }}" {{ old('kode_dept') == $item->kode_dept ? 'selected' : '' }}>
                                            {{ $item->kode_dept }} - {{ $item->nama_dept }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-hint text-muted">Departemen wajib dipilih untuk role Pimpinan.</small>
                                @error('kode_dept')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 form-footer">
                        <button type="submit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M5 12l5 5l10 -10" />
                            </svg>
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }
    .card-header {
        background: white;
        border-bottom: 1px solid #f0f0f0;
        padding: 20px;
    }
    .card-body {
        padding: 30px;
    }
    .form-label {
        font-weight: 500;
        color: #333;
        margin-bottom: 8px;
    }
    .form-label.required::after {
        content: "*";
        color: #ef4444;
        margin-left: 4px;
    }
    .form-control, .form-select {
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 10px 15px;
        transition: all 0.3s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #0053C5;
        box-shadow: 0 0 0 0.2rem rgba(0, 83, 197, 0.25);
    }
    .btn-primary {
        background: linear-gradient(135deg, #0053C5 0%, #003d94 100%);
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 83, 197, 0.3);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role-select');
        const cabangContainer = document.getElementById('cabang-container');
        const cabangSelect = document.getElementById('cabang-select');
        const deptContainer = document.getElementById('dept-container');
        const deptSelect = document.getElementById('dept-select');

        function toggleCabang() {
            const role = roleSelect.value;
            
            // Logika Cabang
            if (role === 'admin' || role === 'pimpinan') {
                cabangContainer.style.display = 'block';
                cabangSelect.required = true;
            } else {
                cabangContainer.style.display = 'none';
                cabangSelect.required = false;
                cabangSelect.value = '';
            }

            // Logika Departemen
            if (role === 'pimpinan') {
                deptContainer.style.display = 'block';
                deptSelect.required = true;
            } else {
                deptContainer.style.display = 'none';
                deptSelect.required = false;
                deptSelect.value = '';
            }
        }

        // Run on load
        toggleCabang();

        // Run on change
        roleSelect.addEventListener('change', toggleCabang);
    });
</script>
@endpush
@endsection
