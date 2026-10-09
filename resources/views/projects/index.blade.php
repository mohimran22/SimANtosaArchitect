@extends('tablar::page')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                @can('tambah data proyek')       
                        <a href="{{ route("projects.create") }}" class="btn btn-dark" >
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            Tambah Data Proyek
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
                    {{-- Header --}}
                    <div class="card-header">
                        <h2 class="card-title mb-0">
                            Daftar Proyek
                        </h2>
                    </div>

                    <div class="card-body border-bottom">
                        <div class="row g-3 align-items-end">

                            {{-- Tanggal Mulai Dari --}}
                            <div class="col-12 col-md-6 col-lg-3">
                                <label for="filter_start_date_from" class="form-label">
                                    Tanggal Mulai Dari
                                </label>
                                <input type="date"
                                    id="filter_start_date_from"
                                    class="form-control">
                            </div>

                            {{-- Tanggal Mulai Sampai --}}
                            <div class="col-12 col-md-6 col-lg-3">
                                <label for="filter_start_date_to" class="form-label">
                                    Tanggal Mulai Sampai
                                </label>
                                <input type="date"
                                    id="filter_start_date_to"
                                    class="form-control">
                            </div>

                            {{-- Jenis Proyek --}}
                            <div class="col-12 col-md-6 col-lg-3">
                                <label for="filter_project_type" class="form-label">
                                    Jenis Proyek
                                </label>
                                <select id="filter_project_type" class="form-select">
                                    <option value="">Semua Jenis Proyek</option>
                                    <option value="1">Desain</option>
                                    <option value="2">RAB</option>
                                    <option value="3">Build</option>
                                </select>
                            </div>

                            {{-- Tombol Filter --}}
                            <div class="col-12 col-md-6 col-lg-3">
                                <div class="d-flex gap-2">
                                    <button type="button"
                                        id="btnFilterProjects"
                                        class="btn btn-dark">
                                        <i class="ti ti-filter me-1"></i>
                                        Filter
                                    </button>

                                    <button type="button"
                                        id="btnResetProjects"
                                        class="btn btn-outline-secondary">
                                        <i class="ti ti-refresh me-1"></i>
                                        Reset
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="tableProjects"
                            class="table card-table table-vcenter text-nowrap">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Proyek</th>
                                    <th>Jenis Proyek</th>
                                    <th>Customer</th>
                                    <th>Karyawan</th>
                                    <th>Tanggal</th>
                                    <th>Lokasi</th>
                                    <th>Tahapan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
@can('tambah data proyek')
<a href="{{ route('projects.create') }}"
   class="mobile-fab d-md-none">

    <svg xmlns="http://www.w3.org/2000/svg"
         width="26"
         height="26"
         viewBox="0 0 24 24"
         stroke-width="2"
         stroke="currentColor"
         fill="none"
         stroke-linecap="round"
         stroke-linejoin="round">

        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
        <line x1="12" y1="5" x2="12" y2="19"/>
        <line x1="5" y1="12" x2="19" y2="12"/>

    </svg>

</a>
@endcan
@endsection

@push('js')
    <script>
        $(function() {
            const isMobile = window.innerWidth < 576;
            let projectType = new URLSearchParams(window.location.search).get('type');
            if (projectType) $('#filter_project_type').val(projectType);
            const table = $('#tableProjects').DataTable({
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
                    url: '{{ route("projects.index") }}',
                    data: function (d) {
                        d.type = projectType;
                        d.start_date_from = $('#filter_start_date_from').val();
                        d.start_date_to = $('#filter_start_date_to').val();
                        d.filter_project_type = $('#filter_project_type').val();
                    }
                },
                order: [[5, 'desc']],
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'project_name', name: 'project_name' },
                    { data: 'project_type', name: 'project_type', orderable: true, searchable: true },
                    { data: 'customer', name: 'customer.user.fullname' },
                    { data: 'employee', name: 'employee.user.fullname' },
                    { data: 'start_date', name: 'start_date', orderable: true },
                    { data: 'project_location', name: 'project_location' },
                    { data: 'current_level', name: 'current_level', orderable: true, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                language: {
                    search: "",
                    searchPlaceholder: "Cari proyek...",
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
            $('#btnFilterProjects').on('click', function () {
                table.ajax.reload();
            });

            $('#btnResetProjects').on('click', function () {
                $('#filter_start_date_from').val('');
                $('#filter_start_date_to').val('');
                $('#filter_project_type').val('');
                projectType = null;
                table.ajax.reload();
            });
            $('table').on('click', '.delete-projects', function () {
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

                            url: `/projects/${projectId}`,
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