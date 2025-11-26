@extends('tablar::page')

@section('content')
<div class="container">
    <h2>Daftar Permission</h2>
    <table class="table table-bordered" id="actionTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Permission</th>
                <th>Dibuat Pada</th>
                <th>Aksi</th>
            </tr>
        </thead>
    </table>
</div>
@endsection

@push('js')
<!-- jQuery & DataTables CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

<script>
$(function() {
    $('#actionTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('permissions.data') }}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});
</script>
@endpush
