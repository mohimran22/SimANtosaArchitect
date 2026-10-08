@extends('tablar::page')
@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">Master Tipe Properti</h2>
                </div>
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        @can('tambah data properti')
                            <button type="button" class="btn btn-dark btnTambahTipe">
                                <i class="ti ti-plus"></i> Tambah Tipe
                            </button>
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
                                Daftar Tipe Properti
                            </p>
                        </div>
                        <div class="table-responsive">
                            <table id="tableTipe" class="table table-vcenter card-table w-100">
                                <thead>
                                    <tr>
                                        <th style="width:70px">No</th>
                                        <th>Nama Tipe</th>
                                        <th>Dipakai</th>
                                        <th style="width:130px">Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@can('tambah data properti')
<button type="button" class="mobile-fab d-md-none btnTambahTipe">
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
@endsection

@push('js')
<script>
$(function () {
    const urlBase = "{{ url('tipe-properti') }}";
    const csrf    = "{{ csrf_token() }}";
    const isMobile = window.innerWidth < 576;
    const table = $('#tableTipe').DataTable({
        scrollY: '500px',
        scrollX: true,
        scrollCollapse: true,
        fixedColumns: !isMobile ? {
            leftColumns: 1
        } : false,
        serverSide: true,
        processing: true,
        responsive: false,
        ajax: "{{ route('tipe-properti.index') }}",
        order: [],
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'nama' },
            { data: 'jumlah_properti', searchable: false },
            { data: 'action', orderable: false, searchable: false }
        ],
        language: {
            search: "",
            searchPlaceholder: "Cari tipe...",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            infoFiltered: "(difilter dari _MAX_ total data)",
            zeroRecords: "Data tidak ditemukan",
            paginate: { first: "Awal", last: "Akhir", next: "›", previous: "‹" }
        },
        initComplete: function () {
            $('.dt-search input').removeClass('form-control-sm').addClass('form-control');
        }
    });

    function pesanError(xhr) {
        const j = xhr.responseJSON || {};
        if (j.errors) return Object.values(j.errors)[0][0];
        return j.message || 'Terjadi kesalahan.';
    }

    function dialogNama(judul, nilai, teks) {
        return Swal.fire({
            title: judul,
            text: teks || '',
            input: 'text',
            inputValue: nilai || '',
            inputPlaceholder: 'mis. Villa, Gudang, Kos-kosan',
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            inputValidator: function (v) { if (!v || !v.trim()) return 'Nama tipe wajib diisi'; }
        });
    }

    function sukses(judul) {
        Swal.fire({ icon: 'success', title: judul, timer: 1600, showConfirmButton: false });
        table.ajax.reload(null, false);
    }

    // Tambah
    $('.btnTambahTipe').on('click', function () {
        dialogNama('Tambah tipe properti').then(function (r) {
            if (!r.isConfirmed) return;

            $.ajax({
                url: urlBase,
                method: 'POST',
                data: {
                    _token: csrf,
                    nama: r.value.trim()
                },
                success: function () {
                    sukses('Tipe ditambahkan');
                },
                error: function (xhr) {
                    Swal.fire('Gagal', pesanError(xhr), 'error');
                }
            });
        });
    });

    // Ubah
    $('#tableTipe').on('click', '.edit-tipe', function () {
        const id = $(this).data('id');
        dialogNama('Ubah tipe properti', $(this).data('nama'),
                   'Perubahan nama otomatis berlaku untuk semua properti dengan tipe ini.')
            .then(function (r) {
                if (!r.isConfirmed) return;
                $.ajax({
                    url: urlBase + '/' + id, method: 'POST',
                    data: { _token: csrf, _method: 'PUT', nama: r.value },
                    success: function () { sukses('Tipe diperbarui'); },
                    error: function (xhr) { Swal.fire('Gagal', pesanError(xhr), 'error'); }
                });
            });
    });

    // Hapus
    $('#tableTipe').on('click', '.delete-tipe', function () {
        const id = $(this).data('id');
        const nama = $(this).data('nama');
        const jumlah = parseInt($(this).data('jumlah'), 10) || 0;

        if (jumlah > 0) {
            Swal.fire('Tidak bisa dihapus', 'Tipe "' + nama + '" masih dipakai ' + jumlah + ' properti. Ubah tipe propertinya dulu.', 'info');
            return;
        }

        Swal.fire({
            title: 'Hapus tipe "' + nama + '"?',
            text: 'Data akan hilang secara permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then(function (r) {
            if (!r.isConfirmed) return;
            $.ajax({
                url: urlBase + '/' + id, method: 'POST',
                data: { _token: csrf, _method: 'DELETE' },
                success: function () { sukses('Tipe dihapus'); },
                error: function (xhr) { Swal.fire('Gagal', pesanError(xhr), 'error'); }
            });
        });
    });
});
</script>
@endpush