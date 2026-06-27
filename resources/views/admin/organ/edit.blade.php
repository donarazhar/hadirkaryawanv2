@extends('admin.layouts.admin')

@section('title', 'Edit Organ / Jabatan')
@section('page-title', 'Edit Organ / Jabatan')

@section('content')
<div class="row">
    <div class="col-md-6 col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Form Edit Organ</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('panel.organ.update', $organ->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Induk Unit <span class="text-danger">*</span></label>
                        <select name="unit_id" class="form-select" required>
                            <option value="">-- Pilih Unit --</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ $organ->unit_id == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->name }} (Cabang: {{ $unit->branch->name ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Nama Organ (Jabatan) <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ $organ->name }}" required>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('panel.organ.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
