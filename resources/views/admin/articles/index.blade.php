{{-- Simpan di: resources/views/admin/articles/index.blade.php --}}
{{-- Ganti @extends dengan layout admin yang kamu pakai (mis. Tablar: 'tablar::page') --}}
@extends('tablar::page')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
    <h2>Artikel</h2>
    <a href="{{ route('articles.create') }}">+ Tulis Artikel</a>
</div>

@if(session('success'))
    <div style="color:#188038;margin-bottom:12px">{{ session('success') }}</div>
@endif

<table style="width:100%;border-collapse:collapse" border="1" cellpadding="8">
    <thead>
        <tr>
            <th>Judul</th>
            <th>Status</th>
            <th>Tanggal terbit</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($articles as $a)
            <tr>
                <td>{{ $a->title }}</td>
                <td>{{ $a->status === 'published' ? 'Terbit' : 'Draft' }}</td>
                <td>{{ $a->published_at?->format('d M Y') ?? '-' }}</td>
                <td>
                    <a href="{{ route('admin.articles.edit', $a) }}">Edit</a>
                    @if($a->status === 'published')
                        | <a href="{{ route('articles.show', $a) }}" target="_blank">Lihat</a>
                    @endif
                    <form action="{{ route('admin.articles.destroy', $a) }}" method="POST"
                          style="display:inline" onsubmit="return confirm('Hapus artikel ini?')">
                        @csrf @method('DELETE')
                        <button type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" style="text-align:center">Belum ada artikel.</td></tr>
        @endforelse
    </tbody>
</table>

<div style="margin-top:12px">{{ $articles->links() }}</div>
@endsection