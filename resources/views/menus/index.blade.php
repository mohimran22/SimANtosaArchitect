@extends('tablar::page')

@section('content')

<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col-12 col-md-auto ms-auto d-print-none">
                <div class="btn-list">
                {{-- @can('tambah data karyawan')        --}}
                <span class="d-none d-sm-inline">
                    <a href="{{ route("menus.create") }}" class="btn btn-dark d-none d-sm-inline-block" >
                        <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <line x1="12" y1="5" x2="12" y2="19"/>
                            <line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        Tambah Data Menu
                    </a>
                </span>
                {{-- @endcan --}}
                    
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <p class="text-center mb-4" style="font-size: 1.5rem; font-weight: 400; font-family: 'Poppins', sans-serif;">
                                Daftar Karyawan
                        </p>
                    </div>
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter text-nowrap">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Title</th>
                                    <th>URL / Route Name</th>
                                    <th>Parent</th>
                                    <th>Order</th>
                                    <th>Active</th>
                                    <th>Permission</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($menus as $menu)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $menu->text }}</td>
                                        <td>{{ $menu->url }}</td>
                                        <td>{{ $menu->parent?->text ?? '-' }}</td>
                                        <td>{{ $menu->order }}</td>
                                        
                                        <td>
                                            {!! $menu->is_active ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>' !!}
                                        </td>

                                        <td>{{ $menu->permission_name }}</td>
                                        <td>
                                            <a href="{{ route('menus.edit', $menu) }}" class="btn btn-sm btn-warning">Edit</a>
                                            <form action="{{ route('menus.destroy', $menu) }}" method="POST" style="display:inline-block">
                                                @csrf @method('DELETE')
                                                <button onclick="return confirm('Delete this menu?')" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    
    

    {{ $menus->links() }}
</div>
@endsection
