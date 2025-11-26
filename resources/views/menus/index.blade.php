@extends('tablar::page')

@section('content')
<div class="container">
    <h2 class="mb-3">Menu Management</h2>

    <a href="{{ route('menus.create') }}" class="btn btn-primary mb-3">+ Add Menu</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                {{-- <th>#</th> --}}
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
                    {{-- <td>{{ $menu+ 1 }}</td> --}}
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

    {{ $menus->links() }}
</div>
@endsection
