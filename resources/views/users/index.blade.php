@extends('tablar::page')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                {{-- <div class="col">
                    <h2 class="page-title">
                        Data Pengguna
                    </h2>
                </div> --}}
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <span class="d-none d-sm-inline">
                  @can('tambah data user')
                        <a href="{{ route("users.create") }}" class="btn btn-dark d-none d-sm-inline-block" >
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            Tambah Pengguna Baru
                        </a>
                    @endcan
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <p class="text-center mb-4" style="font-size: 1.4rem; font-weight: 400; font-family: 'Poppins', sans-serif;">
                                 Daftar Pengguna
                            </p>
                        </div>
                        <div class="table-responsive">
                            <table id="tableUsers" class="table card-table table-vcenter text-nowrap">
                                <thead>
                                    <tr>
                                        <th class="w-1">No.</th>
                                        <th>Nama Lengkap</th>
                                        <th>Nama Panggilan</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Tempat Lahir</th>
                                        <th>Tanggal Lahir</th>
                                        <th>Agama</th>
                                        <th>No KTP</th>
                                        <th>NPWP</th>
                                        <th>Email</th>
                                        <th>Alamat Lengkap</th>
                                        <th>Provinsi</th>
                                        <th>Kabupaten</th>
                                        <th>Kecamatan</th>
                                        <th>Kelurahan</th>
                                        <th>Kode Pos</th>
                                        <th>Telepon</th>
                                        <th class="action">Aksi</th>
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
@endsection

@push('js')
    <script>
        $(function() {
            const table = $('#tableUsers').DataTable({
                scrollY: '500px',
                scrollX: true,
                scrollCollapse: true,
                fixedColumns: {
                    leftColumns: 3
                },
                serverSide: true,
                processing: true,
                ajax: '{{ route("users.index") }}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'fullname', name: 'fullname' },
                    { data: 'nickname', name: 'nickname' },
                    { data: 'gender', name: 'gender' },
                    { data: 'birth_place', name: 'birth_place' },
                    { data: 'birth_date', name: 'birth_date' },
                    { data: 'religion_name', name: 'religion.name' },
                    { data: 'identity_number', name: 'identity_number' },
                    { data: 'npwp', name: 'npwp' },
                    { data: 'email', name: 'email' },
                    { data: 'address', name: 'address'},
                    { data: 'province_name', name: 'province.name' },
                    { data: 'city_name', name: 'city.name'},
                    { data: 'district_name', name: 'district.name' },
                    { data: 'sub_district_name', name: 'sub_district_name' },
                    { data: 'postal_code', name: 'postal_code' },
                    { data: 'phone', name: 'phone' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
                    language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Cari user...",
                    lengthMenu: "Tampilkan data _MENU_",
                    paginate: {
                        first: "Awal",
                        last: "Akhir",
                    }
                    },
            });

            // Delete user functionally
            $('table').on('click', '.delete-user', function () {
            const userId = $(this).data('id');

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

                        url: `/users/${userId}`,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}',
                        },

                        success: function (response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'User telah dihapus.',
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

    @if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Sukses!',
            text: '{{ session('success') }}',
            timer: 2000,
            showConfirmButton: false
        });
    </script>
    @endif
@endpush
@push('css')
<style>

.dt-search .dataTables_filter input {
    height: 42px !important;
    min-width: 280px !important;
    font-size: 14px !important;
    padding: 8px 14px !important;
    border-radius: 8px !important;
}

</style>
@endpush
