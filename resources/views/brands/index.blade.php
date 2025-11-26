@extends('tablar::page')

@section('content')
<div class="container">
    <h2 class="mb-3">Brand Management</h2>

    <a href="{{ route('product_brands.create') }}" class="btn btn-primary mb-3">+ Add brand</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                {{-- <th>#</th> --}}
                <th>Id</th>
                <th>Nama Merk</th>
                <th>Asal pabrik</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($product_brands as $brand)
                <tr>
                    {{-- <td>{{ $brand+ 1 }}</td> --}}
                    <td>{{ $brand->id }}</td>
                    <td>{{ $brand->name }}</td>
                    <td>{{ $brand->factory_origin }}</td>
                    <td>
                        <a href="{{ route('product_brands.edit', $brand) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('product_brands.destroy', $brand) }}" method="POST" style="display:inline-block">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Delete this brand?')" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
