@extends('tablar::page')

@section('content')
<div class="page-body">
    <div class="container-xl">

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-5">

                <h2 class="fw-bold mb-4">Edit Group: {{ $jobCategory->nama_group }}</h2>

                {{-- Update paket --}}
                <form action="{{ route('job-categories.update', $jobCategory->id) }}"
                      method="POST" class="mb-5">

                    @csrf @method('PUT')

                    <div class="row g-4">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Kode Group</label>
                            <input type="text" name="kode_group" class="form-control" 
                                value="{{ $jobCategory->kode_group }}" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Nama Group</label>

                            <select name="nama_group" class="form-select" required>
                            @foreach($groups as $bidang => $items)
                                <optgroup label="{{ $bidang }}">
                                    @foreach($items as $g)
                                        <option value="{{ $g->nama_group }}"
                                            {{ $jobCategory->nama_group === $g->nama_group ? 'selected' : '' }}>
                                            {{ $g->nama_group }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Kode Urut</label>
                            <input type="text" name="kode_urut" class="form-control" 
                                value="{{ $jobCategory->kode_urut }}" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Nama Pekerjaan</label>
                            <input type="text" name="nama_pekerjaan" class="form-control" 
                                value="{{ $jobCategory->nama_pekerjaan }}" required>
                        </div>
                        
                        <div class="col-md-2 mb-4">
                            <label class="form-label fw-bold">Satuan</label>
                            <input type="text" name="satuan" class="form-control" 
                                value="{{ $jobCategory->satuan }}" required>
                        </div>
                    </div>
                    <div class="text-end">
                        <button class="btn btn-dark px-4">Update Paket</button>
                    </div>
                </form>

                <hr>

                {{-- FORM TAMBAH ITEM --}}
                <h3 class="fw-bold mb-3">Tambah Item Rincian</h3>

                <form action="{{ route('job-categories.store', $jobCategory->id) }}"
                      method="POST" class="mb-5">

                    @csrf

                    <div class="row g-3">
                        <div class="col-md-2">
                            <label class="form-label">Kategori</label>
                                <select name="category" class="form-select" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="1" {{ old('category') == '1' ? 'selected' : '' }}>Produk</option>
                                    <option value="2" {{ old('category') == '2' ? 'selected' : '' }}>Tenaga</option>
                                    <option value="3" {{ old('category') == '3' ? 'selected' : '' }}>Peralatan</option>
                                </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Kode</label>
                            <input type="text" name="code" class="form-control" required>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Satuan</label>
                            <input type="text" name="unit" class="form-control" required>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Koefisien</label>
                            <input type="text" name="coefisien" class="form-control" required>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Harga Satuan</label>
                            <input type="text" name="base_unit_price" class="form-control" required>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Jumlah Harga</label>
                            <input type="text" name="total_price" class="form-control" required>
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn btn-dark w-100">Tambah</button>
                        </div>
                    </div>
                </form>

                <hr>

                {{-- TABLE ITEM --}}
                <h3 class="fw-bold mb-3">Daftar Analisa</h3>

                {{-- @include('job-categories.partials.items-table', [
                    'items' => $jobCategory->items
                ]) --}}

            </div>
        </div>

    </div>
</div>
@endsection
