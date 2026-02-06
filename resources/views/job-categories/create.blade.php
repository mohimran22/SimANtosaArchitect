@extends('tablar::page')

@section('content')
<div class="page-body">
    <div class="container-xl">

        <div class="card shadow-sm border-0">
            <div class="card-body p-5">

                <h2 class="fw-bold mb-4">Tambah Data Pekerjaan</h2>

                <form action="{{ route('job-categories.store') }}" method="POST">
                    @csrf

                    <div class="row g-4">

                        {{-- Bidang --}}
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Bidang</label>
                            <input type="text"
                                   name="bidang"
                                   class="form-control @error('bidang') is-invalid @enderror"
                                   value="{{ old('bidang') }}"
                                   required>
                            @error('bidang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kode Group --}}
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Kode Group</label>
                            <input type="text"
                                   name="kode_group"
                                   class="form-control @error('kode_group') is-invalid @enderror"
                                   value="{{ old('kode_group') }}"
                                   required>
                            @error('kode_group')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nama Group --}}
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Nama Group</label>

                            {{-- <select name="nama_group"
                                    class="form-select @error('nama_group') is-invalid @enderror"
                                    required>

                                <option value="">-- Pilih Group --</option>

                                @foreach($groups as $group)
                                    <option value="{{ $group }}"
                                        {{ old('nama_group') === $group ? 'selected' : '' }}>
                                        {{ $group }}
                                    </option>
                                @endforeach
                            </select> --}}
                                <input type="text"
                                   name="nama_group"
                                   class="form-control @error('nama_group') is-invalid @enderror"
                                   value="{{ old('nama_group') }}"
                                   required>

                            @error('nama_group')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kode --}}
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Kode</label>
                            <input type="text"
                                   name="kode"
                                   class="form-control @error('kode') is-invalid @enderror"
                                   value="{{ old('kode') }}"
                                   required>
                            @error('kode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kode Urut --}}
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Kode Urut</label>
                            <input type="text"
                                   name="kode_urut"
                                   class="form-control @error('kode_urut') is-invalid @enderror"
                                   value="{{ old('kode_urut') }}"
                                   required>
                            @error('kode_urut')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Satuan --}}
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Satuan</label>
                            <input type="text"
                                   name="satuan"
                                   class="form-control @error('satuan') is-invalid @enderror"
                                   value="{{ old('satuan') }}"
                                   required>
                            @error('satuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nama Pekerjaan --}}
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Nama Pekerjaan</label>
                            <input type="text"
                                   name="nama_pekerjaan"
                                   class="form-control @error('nama_pekerjaan') is-invalid @enderror"
                                   value="{{ old('nama_pekerjaan') }}"
                                   required>
                            @error('nama_pekerjaan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('job-categories.index') }}"
                           class="btn btn-outline-secondary me-2">
                            Batal
                        </a>

                        <button class="btn btn-dark px-4">
                            Simpan
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>
@endsection
