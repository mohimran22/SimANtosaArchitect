@extends('tablar::page')
@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        @can('tambah data proyek')       
                                <a href="{{ route("jual.create") }}" class="btn btn-dark" >
                                    <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <line x1="12" y1="5" x2="12" y2="19"/>
                                        <line x1="5" y1="12" x2="19" y2="12"/>
                                    </svg>
                                    Tambah Data Properti
                                </a>
                        @endcan
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <div class="col-12">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <p class="text-center mb-4" style="font-size: 1.5rem; font-weight: 400; font-family: 'Poppins', sans-serif;">
                                Daftar Properti
                        </p>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr><th>Foto</th><th>Judul</th><th>Kota</th><th>Harga</th><th>Status</th><th>Tayang</th><th></th></tr>
                            </thead>
                            <tbody>
                            @forelse ($items as $it)
                                <tr>
                                    <td>@if($it->foto)<img src="{{ asset('storage/'.$it->foto) }}" width="64" class="rounded">@endif</td>
                                    <td>{{ $it->judul }}<div class="text-muted small">{{ ucfirst($it->tipe) }}</div></td>
                                    <td>{{ $it->kota }}</td>
                                    <td>Rp {{ number_format($it->harga, 0, ',', '.') }}</td>
                                    <td><span class="badge bg-{{ $it->status === 'dijual' ? 'green' : ($it->status === 'disewa' ? 'blue' : 'secondary') }}-lt">{{ ucfirst($it->status) }}</span></td>
                                    <td>{{ $it->is_published ? 'Ya' : 'Draft' }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('jual.edit', $it) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form action="{{ route('jual.destroy', $it) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus properti ini?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-3">{{ $items->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
