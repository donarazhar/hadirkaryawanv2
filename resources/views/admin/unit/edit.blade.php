@extends('admin.layouts.admin')

@section('title', 'Edit Unit')
@section('page-title', 'Edit Unit')

@section('content')
<div class="row">
    <div class="col-md-6 col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Form Edit Unit</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('panel.unit.update', $unit->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Induk Cabang <span class="text-danger">*</span></label>
                        <select name="branch_id" class="form-select" required>
                            <option value="">-- Pilih Cabang --</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ $unit->branch_id == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kode Unit</label>
                        <input type="text" name="code" class="form-control" value="{{ $unit->code }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Unit <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ $unit->name }}" required>
                    </div>

                    <div class="mb-4 form-check">
                        <input type="hidden" name="is_sekretariat" value="0">
                        <input type="checkbox" class="form-check-input" id="is_sekretariat" name="is_sekretariat" value="1" {{ $unit->is_sekretariat ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_sekretariat">Tandai sebagai unit Sekretariat Pusat</label>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('panel.unit.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
