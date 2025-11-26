@extends('tablar::page')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
            
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                @can('tambah data produk')
                  <span class="d-none d-sm-inline">
                  
                        <a href="{{ route("products.create") }}" class="btn btn-dark d-none d-sm-inline-block" >
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            Tambah Data produk
                        </a>
                    </span>
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
                                Data Produk
                            </p>
                        </div>
                        <div class="table-responsive">
                            <table id="tableProducts" class="table card-table table-vcenter text-nowrap" >
                                <thead>
                                    <tr>
                                        <th>Foto Produk</th>
                                        <th>Nama Produk</th>
                                        <th>Kode SKU</th>
                                        <th>Deskripsi</th>
                                        <th>Merek</th>
                                        <th>Kategori</th>
                                        <th>Tipe</th>
                                        <th>Warna</th>
                                        <th>Ukuran</th>
                                        <th>Volume</th>
                                        
                                        <th>Harga Beli</th>
                                        
                                        {{-- <th>Asal Produk (Supplier)</th> --}}

                                        <th>Harga Jual</th>
                                        {{-- <th>Harga Spesial</th>
                                        <th>Status</th> --}}

                                        {{-- <th>Gudang</th> --}}

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
@endsection

@push('js')
    <script>
        $(function() {
            const table = $('#tableProducts').DataTable({
                serverSide: true,
                processing: true,
                ajax: '{{ route("products.index") }}',
                columns: [
                    { data: 'photo', name: 'photo' },
                    { data: 'name', name: 'name' },
                    { data: 'sku_code', name: 'sku_code' },
                    { data: 'description', name: 'description' },

                    { data: 'brand', name: 'brand.name' },
                    { data: 'category', name: 'category.name' },
                    { data: 'type', name: 'type.name' },
                    { data: 'color', name: 'color.name' },

                    { data: 'size', name: 'size' },
                    { data: 'volume', name: 'volume' },
                    

                    { data: 'buying_prices', name: 'buying_prices' },

                    // many-to-many suppliers (comma separated)
                    // { data: 'suppliers', name: 'suppliers' },

                    { data: 'selling_prices', name: 'selling_prices' },
                    // { data: 'special_prices', name: 'special_prices' },

                    // { data: 'status', name: 'status' },

                    // list gudang
                    // { data: 'warehouses', name: 'warehouses' },

                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]    
            });

            // Delete user functionally
            $('table').on('click', '.delete-products', function () {
            const productId = $(this).data('id');

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

                        url: `/products/${productId}`,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}',
                        },

                        success: function (response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'produk telah dihapus.',
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