@extends('tablar::page')

@section('title', 'Create Menu')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">Create Menu</h2>
                <div class="text-muted mt-1">Kelola struktur navigasi sistem Antosa Architect</div>
            </div>
        </div>
    </div>

    <div class="card mt-3 shadow-sm">
        <div class="card-body">
            <form action="{{ route('menus.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Judul Menu</label>
                        <input type="text" name="text" class="form-control" placeholder="Misal: Beranda" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Tipe</label>
                        <select name="type" class="form-select">
                            <option value="route">Route</option>
                            <option value="url">URL</option>
                            <option value="label">Label</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Urutan</label>
                        <input type="number" name="order" class="form-control" value="0">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">URL / Route Name</label>
                        <input type="text" name="url" class="form-control" placeholder="dashboard.index atau https://...">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Parent Menu</label>
                        <select name="parent_id" class="form-select">
                            <option value="">-- None --</option>
                            @foreach($parents as $menu)
                                <option value="{{ $menu->id }}">{{ $menu->text }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Key</label>
                        <input type="text" name="key" class="form-control" value="0">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Icon (class)</label>
                        <input type="text" name="icon" class="form-control" placeholder="e.g., ti ti-home">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Aktif</label>
                        <select name="is_active" class="form-select">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Permission yang Dibutuhkan (opsional)</label>
                        <select name="permission_name[]" class="form-select select2" multiple>
                            <option value="">-- Tanpa batasan permission --</option>
                            @foreach($permissions as $perm)
                                <option value="{{ $perm->name }}">{{ $perm->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 text-end mt-3">
                        <a href="{{ route('menus.index') }}" class="btn btn-light">
                            <i class="ti ti-arrow-left me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-check me-1"></i> Simpan Menu
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
