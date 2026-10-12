@extends('tablar::page')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <h2 class="page-title">Kategori</h2>
    </div>
</div>
@php
    $sortUrl = fn ($col) => request()->fullUrlWithQuery([
        'orderby' => $col,
        'order'   => ($orderby === $col && $order === 'asc') ? 'desc' : 'asc',
    ]);
@endphp
<div class="page-body">
    <div class="container-xl">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row g-3">
            {{-- Form tambah --}}
            <div class="col-md-4">
                <div class="card"><div class="card-body">
                    <h3 class="card-title">Tambah Kategori Baru</h3>
                    <form method="POST" action="{{ route('categories.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label required">Nama</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                   class="form-control @error('name') is-invalid @enderror" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <small class="text-muted">Nama yang tampil di situs.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Slug</label>
                            <input type="text" name="slug" value="{{ old('slug') }}"
                                   class="form-control @error('slug') is-invalid @enderror">
                            @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <small class="text-muted">Versi URL dari nama. Kosongkan untuk otomatis.</small>
                        </div>
                        <button class="btn btn-dark">Tambah Kategori</button>
                    </form>
                </div></div>
            </div>

            {{-- Daftar --}}
            <div class="col-md-8">
                <div class="card"><div class="card-body">
                    <form method="GET" class="d-flex gap-2 mb-3 justify-content-end">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control w-auto" placeholder="Cari kategori...">
                        <button class="btn btn-outline-secondary">Cari</button>
                    </form>

                    {{-- Form hapus per baris (tidak boleh di dalam form bulk) --}}
                    <form id="delete-form" method="POST" style="display:none">@csrf @method('DELETE')</form>

                    <form method="POST" action="{{ route('categories.bulk') }}" onsubmit="return confirm('Hapus kategori terpilih?')">
                        @csrf @method('DELETE')
                        <div class="d-flex gap-2 mb-2">
                            <select name="bulk_action" class="form-select" style="width:auto" required>
                                <option value="">Bulk actions</option>
                                <option value="delete">Hapus</option>
                            </select>
                            <button class="btn btn-outline-secondary">Apply</button>
                            <span class="ms-auto text-muted">{{ $cats->total() }} kategori</span>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-vcenter card-table">
                                <thead>
                                    <tr>
                                        <th style="width:30px"><input type="checkbox" id="check-all"></th>
                                        <th><a href="{{ $sortUrl('name') }}">Nama</a></th>
                                        <th>Slug</th>
                                        <th><a href="{{ $sortUrl('articles_count') }}">Jumlah</a></th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse($cats as $t)
                                    <tr>
                                        <td><input type="checkbox" name="ids[]" value="{{ $t->id }}" class="row-check"></td>
                                        <td style="white-space:normal;word-break:break-word">
                                            <strong><a href="{{ route('categories.edit', $t) }}">{{ $t->name }}</a></strong>
                                            <div class="small">
                                                <a href="{{ route('categories.edit', $t) }}">Edit</a> |
                                                <a href="#" class="text-danger"
                                                   onclick="deleteCat('{{ route('categories.destroy', $t) }}'); return false;">Hapus</a> |
                                                <a href="{{ route('articles.index', ['categories' => $t->slug]) }}">Lihat artikel</a>
                                            </div>
                                        </td>
                                        <td style="white-space:normal;word-break:break-word">{{ $t->slug }}</td>
                                        <td>
                                            <a href="{{ route('articles.index', ['categories' => $t->slug]) }}">{{ $t->articles_count }}</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted">Tidak ada kategori.</td></tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </form>

                    {{ $cats->links() }}
                </div></div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('check-all').addEventListener('change', e =>
    document.querySelectorAll('.row-check').forEach(c => c.checked = e.target.checked));

function deleteCat(url) {
    if (!confirm('Hapus kategori ini?')) return;
    const f = document.getElementById('delete-form');
    f.action = url;
    f.submit();
}
</script>
@endsection