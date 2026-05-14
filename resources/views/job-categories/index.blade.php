@extends('tablar::page')

@section('content')
<div class="page-body">
    <div class="container-xl">

        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3 class="card-title">Daftar Pekerjaan</h3>
                <a href="{{ route('job-categories.create') }}" class="btn btn-dark">
                    + Tambah
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-vcenter" id="jobTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Bidang</th>
                            <th>Group</th>
                            <th>Kode Urut</th>
                            <th>Nama Pekerjaan</th>
                            <th>Satuan</th>
                            <th width="120">Harga</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>

        </div>

    </div>
</div>
@endsection

@push('js')

<script>
$(function () {
    $('#jobTable').DataTable({
        scrollY: '500px',
            scrollX: true,
            scrollCollapse: true,
                fixedColumns: {
                    leftColumns: 3
                },
        processing: true,
        serverSide: true,
        ajax: "{{ route('job-categories.index') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'bidang', name: 'bidang' },
            { data: 'nama_group', name: 'nama_group' },
            { data: 'kode_urut', name: 'kode_urut' },
            { data: 'nama_pekerjaan', name: 'nama_pekerjaan' },
            { data: 'satuan', name: 'satuan' },
            { data: 'grand_total', name: 'grand_total' },
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
        ],
                language: {
                    search: "",
                    searchPlaceholder: "Cari ahsp...",
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
    $(document).on('click', '.btn-delete', function() {
        let url = $(this).data('url');

        Swal.fire({
            title: 'Yakin hapus?',
            text: "Data tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE'
                    },
                    success: function(res) {
                        $('#jobTable').DataTable().ajax.reload(null, false);

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: res.message ?? 'Data berhasil dihapus',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Data tidak bisa dihapus'
                        });
                        console.log(xhr.responseText);
                    }
                });
            }
        });
    });
    $(document).on('click', '.btn-duplicate', function() {
        let id = $(this).data('id');

        Swal.fire({
            title: 'Duplikat data?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, duplikat!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/job-categories/${id}/duplicate`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        $('#jobTable').DataTable().ajax.reload(null, false);

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: res.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                });
            }
        });
    });
});
</script>
@endpush
