@extends('tablar::page')

@section('content')
<div class="container">
    <h2 class="mb-3">color Management</h2>

    <a href="{{ route('product_colors.create') }}" class="btn btn-primary mb-3">+ Add color</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                {{-- <th>#</th> --}}
                <th>Id</th>
                <th>Warna Produk</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($product_colors as $color)
                <tr>
                    {{-- <td>{{ $color+ 1 }}</td> --}}
                    <td>{{ $color->id }}</td>
                    <td>{{ $color->name }}</td>
                    <td>
                        <a href="{{ route('product_colors.edit', $color) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('product_colors.destroy', $color) }}" method="POST" style="display:inline-block">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Delete this color?')" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
