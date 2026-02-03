@extends('tablar::page')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                {{-- <div class="col">
            
                    <h2 class="page-title">
                        Akun
                    </h2>
                </div> --}}
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                  <span class="d-none d-sm-inline">
                  
                        {{-- <a href="{{ route("accounts.create") }}" class="btn btn-dark text-white d-none d-sm-inline-block" >
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            Tambah Akun
                        </a> --}}
                        
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
                                 Daftar Akun
                            </p>
                        </div>
                        <div class="table-responsive">
                            <table id="accountsTable" class="table card-table table-vcenter text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Nama Akun</th>
                                        <th>Role</th>                                      
                                    </tr>
                                </thead>
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
$(document).ready(function () {
    let table = $('#accountsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('accounts.index') }}",
        columns: [
            { data: 'fullname', name: 'fullname' },
            { data: 'role_dropdown', name: 'role', orderable: false, searchable: false },
        ],
        language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Cari akun...",
                    lengthMenu: "Tampilkan data _MENU_",
                    paginate: {
                        first: "Awal",
                        last: "Akhir",
                    }
                    },
                    initComplete: function() {
                    // Tambah Tailwind ke elemen filter
                    $('.dataTables_filter input').addClass('border-gray-300 rounded-lg px-4 py-2');
                    $('.dataTables_length select').addClass('border-gray-300 rounded-lg px-2 py-1');
                    }
    });

    // Saat dropdown role berubah
    $(document).on('change', '.role-dropdown', function() {
        let userId = $(this).data('user-id');
        let selectedRoles = $(this).val();

        $.ajax({
            url: "{{ route('accounts.update-role') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                user_id: userId,
                roles: selectedRoles
            },
            success: function (res) {
                toastr.success(res.message);
                table.ajax.reload(null, false); // refresh data tanpa reload halaman
            },
            error: function (xhr) {
                toastr.error(xhr.responseJSON?.message || 'Gagal mengubah role');
            }
        });
    });

    $('#accountsTable').on('draw.dt', function() {
        $('.select2').select2({
            width: '80%',
            placeholder: "Pilih role",
            allowClear: true
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