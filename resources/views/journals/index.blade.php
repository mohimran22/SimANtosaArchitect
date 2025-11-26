@extends('tablar::page')

@section('content')
<div class="container">
    <h1>Daftar Jurnal</h1>

    <a href="{{ route('journals.create') }}" class="btn btn-primary mb-3">Tambah Jurnal</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tipe Lisensi</th>
                <th>Nama Lisensi</th>
                <th>Kode</th>
                <th>Tanggal</th>
                <th>Deskripsi</th>
                <th>Dibuat Oleh</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($journals as $journal)
                <tr>
                    <td>{{ $journal->license ? $journal->license->license_type : '-' }}</td>
                    <td>{{ $journal->license ? $journal->license->name : '-' }}</td>
                    <td>{{ $journal->journal_code }}</td>
                    <td>{{ $journal->transaction_date }}</td>
                    <td>{{ $journal->description }}</td>
                    <td>{{ $journal->creator->name ?? '-' }}</td>
                    <td>
                        <a href="{{ route('journals.show', $journal->id) }}" class="btn btn-info btn-sm">Detail</a>
                        <a href="{{ route('journals.edit', $journal->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('journals.destroy', $journal->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus jurnal ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">Belum ada jurnal.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
