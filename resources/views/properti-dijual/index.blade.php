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
                <div class="card">
                    <div class="card-header">
                        <p class="text-center mb-4" style="font-size: 1.5rem; font-weight: 400; font-family: 'Poppins', sans-serif;">
                                Daftar Properti
                        </p>
                    </div>
                    <div class="table-responsive">
                        <table id="tableProperties" class="table table-vcenter card-table">
                            <thead>
                                <tr><th>No</th><th>Foto</th><th>Judul</th><th>Kota</th><th>Harga</th><th>Status</th><th>Tayang</th><th>Aksi</th></tr>
                            </thead>
                            <tbody>
                            {{-- @forelse ($items as $it)
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
                            @endforelse --}}
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- <div class="mt-3">{{ $items->links() }}</div> --}}
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
        <script>
        $(function() {
            const isMobile = window.innerWidth < 576;
            const projectType = new URLSearchParams(window.location.search).get('type');
            const table = $('#tableProperties').DataTable({
                scrollY: '500px',
                scrollX: true,
                scrollCollapse: true,
                fixedColumns: !isMobile ? {
                    leftColumns: 4
                } : false,
                serverSide: true,
                processing: true,
                responsive: false,
                ajax: {
                    url: '{{ route("jual.index") }}',
                    data: function (d) {
                        d.type = projectType;
                    }
                },
                order: [],
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'foto',  orderable: false, searchable: false },
                    { data: 'judul' },
                    { data: 'kota' },
                    { data: 'harga' },
                    { data: 'status'},
                    { data: 'is_published', orderable: false, searchable: false },
                    { data: 'action', orderable: false, searchable: false}
                ],
                language: {
                    search: "",
                    searchPlaceholder: "Cari properti...",
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
                    if (projectType) {

                        const text = {
                            1: 'Desain',
                            2: 'RAB',
                            3: 'Build'
                        };

                        input.val(text[projectType] ?? projectType);

                    }
                }
            });

            // Delete user functionally
        $('#tableProperties').on('click', '.delete-properties', function () {
            const projectId = $(this).data('id');

            Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Data akan hilang secara permanen.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'

            }).then((result) => {

                if (result.isConfirmed) {
                    $.ajax({

                        url: `/jual/${projectId}`,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}',
                        },

                        success: function (response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Data Proyek telah dihapus.',
                                    timer: 2000,
                                    showConfirmButton: false
                            });

                        table.ajax.reload(null, false); // refresh datatable
                        } else {

                            Swal.fire('Gagal', response.message || 'Tidak bisa menghapus data.', 'error');
                        }
                        },

                    error: function () {

                    Swal.fire('Error', 'Terjadi kesalahan saat menghapus.', 'error');
                    }

                    });
                }
            });
        });


           
        });
    </script>
@endpush