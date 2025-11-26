@extends('tablar::page')

@section('content')
<div class="container">
    <h1 class="mb-4">Daftar Akun</h1>

    <a href="{{ route("accounting.create") }}" class="btn btn-primary mb-3">Tambah Akun</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tipe Lisensi</th>
                <th>Nama Lisensi</th>
                <th>Kode Akun</th>
                <th>Nama Akun</th>
                <th>Jenis</th>
                <th>Debit/Kredit</th>
                <th>Saldo Awal</th>
                <th>Akun Induk</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($accounts as $account)
                <tr>
                    <td>{{ $account->license ? $account->license->license_type : '-' }}</td>
                    <td>{{ $account->license ? $account->license->name : '-' }}</td>
                    <td>{{ $account->account_code }}</td>
                    <td>{{ $account->account_name }}</td>
                    <td>{{ $account->account_type }}</td>
                    <td>{{ $account->balance_type }}</td>
                    <td>{{ number_format($account->initial_balance, 2) }}</td>
                    <td>
                        @if ($account->parent)
                            {{ $account->parent->account_name }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if ($account->is_active)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('accounting.edit', $account->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('accounting.destroy', $account->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirmDelete(event)">
                                    @csrf
                                    @method('DELETE')
                                     <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Belum ada akun.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
