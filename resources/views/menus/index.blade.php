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
                                Daftar Menu
                        </p>
                    </div>
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                    <div class="table-responsive">
                        <table id="menuTable" class="table card-table table-vcenter text-nowrap">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Title</th>
                                    <th>URL / Route Name</th>
                                    <th>Parent</th>
                                    <th>Order</th>
                                    <th>Active</th>
                                    <th>Permission</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {

$('#menuTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ route('menus.index') }}",

    columns: [
        { data: 'DT_RowIndex', orderable:false, searchable:false },
        { data: 'text' },
        { data: 'url' },
        { data: 'parent_name' },
        { data: 'order' },
        { data: 'active_badge', orderable:false, searchable:false },
        { data: 'permission_name' },
        { data: 'actions', orderable:false, searchable:false },
    ],
                language: {
                    search: "",
                    searchPlaceholder: "Cari menu...",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    infoEmpty: "Tidak ada data",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    zeroRecords: "Data tidak ditemukan",
                    paginate: {
                        first: "Awal",
                        last: "Akhir",
                        next: "›",
                        previous: "‹"
                    }
                },

                initComplete: function () {
                    const input = $('.dt-search input');
                    input.removeClass('form-control-sm')
                        .addClass('form-control');
                }
});

});
</script>
@endpush