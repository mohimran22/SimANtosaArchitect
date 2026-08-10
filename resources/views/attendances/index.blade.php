@extends('tablar::page')

@section('content')

<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row align-items-center">
            <div class="col-12 col-md-auto ms-auto d-print-none">
                <div class="d-flex align-items-center gap-2">
                    @can('ajukan izin absensi')
                        <button
                            type="button"
                            class="btn btn-warning"
                            data-bs-toggle="modal"
                            data-bs-target="#izinFutureModal">

                            <i class="ti ti-calendar-plus me-1"></i>
                            Ajukan Izin / Cuti
                        </button>
                    @endcan
                    @role('Super-Admin')
                            <a href="{{ route('attendances.create') }}" class="btn btn-dark">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <line x1="12" y1="5" x2="12" y2="19"/>
                                    <line x1="5" y1="12" x2="19" y2="12"/>
                                </svg>

                                Tambah Data Absensi
                            </a>
                    @endrole

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
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">

                        <h3 class="card-title mb-0">
                            Daftar Absensi Karyawan
                        </h3>

                        <div class="btn-list">
                            <a href="{{ route('attendance.export') }}"
                            class="btn btn-dark"
                            target="_blank">
                                <i class="ti ti-file-export me-1"></i>
                                Ekspor Excel
                            </a>

                            <a href="{{ route('attendance.export.pdf') }}"
                            class="btn btn-outline-dark"
                            target="_blank">
                                <i class="ti ti-printer me-1"></i>
                                Cetak
                            </a>
                        </div>

                    </div>
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        <form id="filterForm" class="row g-2 align-items-center px-3 pt-3">

                            <div class="col-md-3">
                                <select name="month" id="month" class="form-select">
                                    @foreach(range(1,12) as $m)
                                        <option value="{{ $m }}" {{ now()->month == $m ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <select name="year" id="year" class="form-select">
                                    @foreach(range(now()->year-5, now()->year+1) as $y)
                                        <option value="{{ $y }}" {{ now()->year == $y ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </form>
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
@can('tambah data absensi')
<a href="{{ route('attendances.create') }}"
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
@can('ajukan izin absensi')
<button
                            type="button"
                            class="mobile-fab d-md-none"
                            data-bs-toggle="modal"
                            data-bs-target="#izinFutureModal">

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

</button>
@endcan
<div
    class="modal fade"
    id="izinFutureModal"
    tabindex="-1"
    aria-labelledby="izinFutureModalLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <form
            action="{{ route('attendances.permission.store') }}"
            method="POST">

            @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="izinFutureModalLabel">
                        <i class="ti ti-calendar-plus me-2"></i>
                        Ajukan Izin / Cuti
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                    </button>
                </div>

                <div class="modal-body">

                    <div class="alert alert-info">
                        <i class="ti ti-info-circle me-1"></i>

                        Pengajuan ini digunakan untuk izin, sakit, atau cuti
                        pada tanggal yang akan datang.
                    </div>

                    <div class="row">

                        {{-- Tanggal Mulai --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label required">
                                Tanggal Mulai
                            </label>

                            <input
                                type="date"
                                name="start_date"
                                class="form-control"
                                min="{{ today()->addDay()->toDateString() }}"
                                required>

                            <div class="form-text">
                                Minimal mulai besok.
                            </div>

                        </div>

                        {{-- Tanggal Selesai --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label required">
                                Tanggal Selesai
                            </label>

                            <input
                                type="date"
                                name="end_date"
                                class="form-control"
                                min="{{ today()->addDay()->toDateString() }}"
                                required>

                        </div>

                    </div>

                    {{-- Jenis --}}
                    <div class="mb-3">

                        <label class="form-label required">
                            Jenis Pengajuan
                        </label>

                        <select
                            name="request_type"
                            class="form-select"
                            required>

                            <option value="">
                                -- Pilih Jenis --
                            </option>

                            <option value="permission">
                                Izin
                            </option>

                            <option value="sick">
                                Sakit
                            </option>

                            <option value="leave">
                                Cuti
                            </option>

                        </select>

                    </div>

                    {{-- Alasan --}}
                    <div class="mb-3">

                        <label class="form-label required">
                            Alasan
                        </label>

                        <textarea
                            name="reason"
                            class="form-control"
                            rows="4"
                            placeholder="Masukkan alasan pengajuan..."
                            required></textarea>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Batal

                    </button>

                    <button
                        type="submit"
                        class="btn btn-warning">

                        <i class="ti ti-send me-1"></i>
                        Kirim Pengajuan

                    </button>

                </div>

            </div>

        </form>

    </div>
</div>
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
                    data: function (d) {
                        d.month = $('#month').val();
                        d.year  = $('#year').val();
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
                        data: 'fullname',
                        searchable: false,
                        orderable: false
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
            function updateExportLinks() {

                const month = $('#month').val();
                const year  = $('#year').val();

                $('.btn-export-excel').attr(
                    'href',
                    "{{ route('attendance.export') }}" +
                    "?month=" + month +
                    "&year=" + year
                );

                $('.btn-export-pdf').attr(
                    'href',
                    "{{ route('attendance.export.pdf') }}" +
                    "?month=" + month +
                    "&year=" + year
                );
            }

            // Set saat halaman pertama dibuka
            updateExportLinks();

            // Update saat filter berubah
            $('#month, #year').on('change', function () {

                table.ajax.reload();
                updateExportLinks();

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
                updateHistoryExportLink();
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
        function updateHistoryExportLink(){

            let employee = $('#historyModal').data('employee');

            let start = $('#history_start_date').val();

            let end = $('#history_end_date').val();

            let status = $('#history_status').val();

            let pdf =
                "{{ route('attendances.history.pdf', ':id') }}"
                .replace(':id', employee)
                + '?start_date=' + encodeURIComponent(start)
                + '&end_date=' + encodeURIComponent(end)
                + '&attendance_code=' + encodeURIComponent(status);

            $('.btn-history-pdf').attr('href', pdf);
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
        $(document).on('change', '#history_status', function () {
            updateHistoryExportLink();
        });
        $(document).on('change', '#history_start_date,#history_end_date', function () {
        updateHistoryExportLink();
        });
    </script>
@endpush