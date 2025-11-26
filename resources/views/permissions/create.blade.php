@extends('tablar::page')

@section('content')
<div class="container">
    <h2>Tambah Permission Baru</h2>

    <form action="{{ route('permissions.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Nama Permission</label>
            <input type="text" name="name" class="form-control" placeholder="contoh: manage users" required>
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
