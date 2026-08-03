@extends('tablar::page')

@section('content')

<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row align-items-center">
            <div class="col-12 col-md-auto ms-auto d-print-none">
                {{-- <div class="btn-list">

                    @can('tambah data absensi')
                            <a href="{{ route('attendances.create') }}" class="btn btn-dark">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <line x1="12" y1="5" x2="12" y2="19"/>
                                    <line x1="5" y1="12" x2="19" y2="12"/>
                                </svg>

                                Tambah Menu Baru
                            </a>
                    @endcan

                </div> --}}
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
                            <h3 class="mb-0 fw-semibold">
                                Daftar Absensi Karyawan
                            </h3>
                    </div>
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        <div class="table-responsive">
                            <table id="absenTable" class="table table-bordered table-striped align-middle w-100">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Jabatan</th>
                                        <th>H</th>
                                        <th>TL A</th>
                                        <th>TL B</th>
                                        <th>TL C</th>
                                        <th>DL</th>
                                        <th>I</th>
                                        <th>S</th>
                                        <th>C</th>
                                        <th>A</th>
                                        <th>Total Hari Kerja</th>
                                        <th>Total Hari Kehadiran</th>
                                        <th>Kehadiran</th>
                                        <th>Ketepatan Waktu</th>
                                        <th>Total Jam Lembur</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="historyModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    History Absensi
                </h5>
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">

                <div id="history-content">
                </div>

            </div>

        </div>
    </div>
</div>
{{-- <div class="modal fade" id="attendanceModal"tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
         <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Detail Absensi
                </h5>
                <button class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>
            <div class="modal-body">
                <div id="attendanceModalContent">
                    Loading...
                </div>
            </div>
        </div>
    </div>
</div> --}}
@endsection
@push('js')
    <script>
        $(function() {
            const isMobile = window.innerWidth < 576;
            const table = $('#absenTable').DataTable({
                scrollY: '500px',
                scrollX: true,
                scrollCollapse: true,
                fixedColumns: !isMobile ? {
                    leftColumns: 3
                } : false,

                serverSide: true,
                processing: true,
                responsive: false,
                
                ajax: {

                    url: "{{ route('attendances.datatable') }}",

                    data: function(d){

                        const month = $('#month').val();

                        if(month){

                            const split = month.split('-');

                            d.year = split[0];

                            d.month = split[1];

                        }

                    }

                },
                columns:[

                    {
                        data:'DT_RowIndex',
                        name:'DT_RowIndex',
                        searchable:false,
                        orderable:false
                    },

                    {
                        data:'fullname',
                        name:'fullname'
                    },

                    { data: 'roles', name: 'roles', defaultContent:'-',
                        render: function(data) {

                            if (!data) return '';

                            if (data.length > 35) {
                                return `
                                    <span title="${data}">
                                        ${data.substring(0,35)}...
                                    </span>
                                `;
                            }

                            return data;
                        }
                    },  

                    {
                        data:'h'
                    },

                    {
                        data:'tla'
                    },

                    {
                        data:'tlb'
                    },

                    {
                        data:'tlc'
                    },

                    {
                        data:'dl'
                    },

                    {
                        data:'izin'
                    },

                    {
                        data:'sakit'
                    },

                    {
                        data:'cuti'
                    },

                    {
                        data:'alpha'
                    },

                    {
                        data:'total_hari_kerja'
                    },

                    {
                        data:'total_hari_kehadiran'
                    },

                    {
                        data:'kehadiran'
                    },

                    {
                        data:'ketepatan_waktu'
                    },

                    {
                        data:'lembur'
                    },

                    {
                        data:'keterangan'
                    }

                ],
                language: {
                    search: "",
                    searchPlaceholder: "Cari dafta absen...",
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
        function loadHistory(employeeId, startDate = '', endDate = '', status='') {

            let url = "{{ route('attendances.history', ':id') }}";
            url = url.replace(':id', employeeId);

            $('#history-content').html(`
                <div class="text-center p-5">
                    <div class="spinner-border"></div>
                </div>
            `);

            $.get(url, {
                start_date: startDate,
                end_date: endDate,
                attendance_code: status
            }, function (html) {

                $('#history-content').html(html);
                initHistoryFilter();

            });

        }
        function initHistoryFilter() {

            if ($('#history_status').hasClass('select2-hidden-accessible')) {
                $('#history_status').select2('destroy');
            }

            $('#history_status').select2({
                placeholder: 'Semua Status',
                allowClear: true,
                width: '100%',
                dropdownParent: $('#historyModal')
            });

        }
        $(document).on('click', '.btn-history', function () {

            let employeeId = $(this).data('id');

            $('#historyModal').data('employee', employeeId);

            loadHistory(employeeId);

            $('#historyModal').modal('show');

        });
        $(document).on('click','.btn-filter-history',function(){

            let employeeId=$(this).data('employee');

            loadHistory(
                employeeId,
                $('#history_start_date').val(),
                $('#history_end_date').val(),
                $('#history_status').val()
            );

        });
        $(document).on('click','.btn-reset-history',function(){

            $('#history_start_date').val('');
            $('#history_end_date').val('');
            $('#history_status').val('').trigger('change');

            let employeeId=$(this).data('employee');

            loadHistory(employeeId);

        });
        $(document).on('click', '.btn-detail', function () {

            let id = $(this).data('id');

            let url = "{{ route('attendances.detail', ':id') }}";
            url = url.replace(':id', id);

            $('#history-content').html(`
                <div class="text-center p-5">
                    <div class="spinner-border"></div>
                </div>
            `);

            $.get(url, function (html) {
                $('#history-content').html(html);
            });

        });
        $(document).on('click', '.btn-back-history', function () {

            let employeeId = $(this).data('employee');

            loadHistory(employeeId);

        });
        $(document).on('click','.btn-edit',function(){

            let id=$(this).data('id');

            let url="{{ route('attendances.edit',':id') }}";

            url=url.replace(':id',id);

            $('#history-content').html(`
                <div class="text-center p-5">
                    <div class="spinner-border"></div>
                </div>
            `);

            $.get(url,function(html){

                $('#history-content').html(html);

            });

        });
        $(document).on('submit','#attendanceEditForm',function(e){

            e.preventDefault();

            let id=$("#attendance_id").val();

            let url="{{ route('attendances.update',':id') }}";

            url=url.replace(':id',id);

            $.ajax({

                url:url,

                type:"POST",

                data:$(this).serialize(),

                success:function(){

                    Swal.fire({

                        icon:"success",

                        title:"Berhasil",

                        text:"Data berhasil diperbarui."

                    });

                    $(".btn-back-history").click();

                },

                error:function(){

                    Swal.fire({

                        icon:"error",

                        title:"Gagal",

                        text:"Terjadi kesalahan."

                    });

                }

            });

        });
        $(document).on('click','.btn-revisions',function(){

            let id=$(this).data('id');

            let url="{{ route('attendances.revisions',':id') }}";

            url=url.replace(':id',id);

            $('#history-content').html(`
                <div class="text-center p-5">
                    <div class="spinner-border"></div>
                </div>
            `);

            $.get(url,function(html){

                $('#history-content').html(html);

            });

        });
        $(document).on('click','.btn-delete',function(){

            let id=$(this).data('id');

            let url="{{ route('attendances.delete',':id') }}";

            url=url.replace(':id',id);

            $('#history-content').html(`
                <div class="text-center p-5">
                    <div class="spinner-border"></div>
                </div>
            `);

            $.get(url,function(html){

                $('#history-content').html(html);

            });

        });
        $(document).on('submit','#attendanceDeleteForm',function(e){

            e.preventDefault();

            let id=$("#delete_attendance_id").val();

            let url="{{ route('attendances.destroy',':id') }}";

            url=url.replace(':id',id);

            $.ajax({

                url:url,

                type:'DELETE',

                data:{

                    _token:"{{ csrf_token() }}",

                    reason:$('[name=reason]').val()

                },

                success:function(){

                    Swal.fire({

                        icon:'success',

                        title:'Berhasil',

                        text:'Absensi berhasil dihapus.'

                    });

                    $('.btn-back-history').click();

                    $('#absenTable').DataTable().ajax.reload(null,false);

                }

            });

        });
    </script>
@endpush