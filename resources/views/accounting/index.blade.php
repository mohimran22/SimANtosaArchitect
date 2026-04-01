@extends('tablar::page')

@section('content')
<div class="container">
    <h1 class="mb-4">Daftar Akun</h1>

    <a href="{{ route("accounting.create") }}" class="btn btn-dark text-white mb-3">Tambah Akun</a>

    <table id="tableAccounts" class="table card-table table-vcenter text-nowrap" >
        <thead>
            <tr>
                <th>Kode Akun</th>
                <th>Nama Akun</th>
                <th>Kategori</th>
                <th>Sub Kategori</th>
                <th>Saldo Awal</th>
                {{-- <th>Akun Induk</th> --}}
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($accounts as $account)
                <tr>
                    <td>{{ $account->account_code }}</td>
                    <td>{{ $account->account_name }}</td>
                    <td>{{ $account->category }}</td>
                    <td>{{ $account->sub_category }}</td>
                    <td>{{ number_format($account->initial_balance, 2) }}</td>
                    {{-- <td>
                        @if ($account->parent)
                            {{ optional($account->parent)->account_name ?? '-' }}
                        @else
                            -
                        @endif
                    </td> --}}
                    <td>
                        @if ($account->is_active)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        @if (auth()->user()->can('ubah akun-akuntansi')) 
                            <a href="{{ route('accounting.edit', $account->id) }}" class="btn btn-dark btn-sm" title="Edit">
                                <i class="ti ti-edit"></i>
                            </a>
                        @endif
                        @if (auth()->user()->can('hapus akun-akuntansi')) 
                            <form action="{{ route('accounting.destroy', $account->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-dark btn-sm" onclick="return confirm('Hapus jurnal ini?')" title="Hapus">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </form>
                        @endif
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

{{-- @push('js')
<script>
    $(function () {
        $('#tableAccounts').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("accounting.index") }}',
            columns: [
                { data: 'license_type', name: 'license.license_type' },
                { data: 'license_name', name: 'license.name' },
                { data: 'account_code', name: 'account_code' },
                { data: 'account_name', name: 'account_name' },
                { data: 'account_type', name: 'account_type' },
                { data: 'balance_type', name: 'balance_type' },
                { data: 'initial_balance', name: 'initial_balance' },
                { data: 'parent_name', name: 'parent.account_name', orderable: false, searchable: false },
                { data: 'status', name: 'is_active', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });
    });
</script> 
@endpush --}}



