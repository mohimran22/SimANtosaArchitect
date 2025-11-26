@extends('tablar::page')

@section('content')
<div class="container-xl">

    {{-- 🔹 Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3 mt-4">
        <h2 class="page-title">Detail supplier</h2>
        <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- 🔹 Tabs --}}
    <ul class="nav nav-tabs mb-3" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" data-bs-toggle="tab" href="#tab-personal" role="tab">
                <i class="ti ti-user"></i> Detail Pribadi
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" data-bs-toggle="tab" href="#tab-loyalty" role="tab">
                <i class="ti ti-map-pin"></i> Detail Usaha
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" data-bs-toggle="tab" href="#tab-catalogue" role="tab">
                <i class="ti ti-shopping-cart"></i> Katalog Produk
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" data-bs-toggle="tab" href="#tab-employment" role="tab">
                <i class="ti ti-briefcase"></i> Riwayat Pembayaran
            </a>
        </li>
    </ul>

    <div class="tab-content">

        {{-- ================= TAB 1: PERSONAL ================= --}}
        <div class="tab-pane fade show active" id="tab-personal" role="tabpanel">

            <div class="card mb-4 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Informasi Personal</h3>
                    <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-outline-dark btn-sm">
                        <i class="ti ti-edit"></i> Ubah Detail
                    </a>
                </div>

                <div class="card-body">
                    <div class="row align-items-center mb-4">
                        <div class="col-md-3 text-center">
                            @if ($user->photo)
                            <img id="previewImage" src="{{ asset('storage/photos/'.$user->photo) }}" alt="Profile" 
                                 class="rounded-3 shadow-sm border" width="150" height="150"
                                 style="object-fit: cover;">
                        @else
                            <div id="previewImage"
                                 class="rounded-3 shadow-sm bg-light d-flex align-items-center justify-content-center"
                                 style="width:150px; height:150px;">
                                 <i class="ti ti-user" style="font-size: 64px; color:#aaa;"></i>
                            </div>
                        @endif
                        </div>
                        <div class="col-md-6">
                            <div class="row g-3">
                                <div class="col-md">
                                    <div class="text-muted small">Nama Lengkap</div>
                                    <div class="fw-bold">{{ $user->fullname ?? '-' }}</div>
                                </div>
                                <div class="col-md">
                                    <div class="text-muted small">Email</div>
                                    <div class="fw-bold">{{ $user->email ?? '-' }}</div>
                                </div>
                                {{-- <div class="col-md-6">
                                    <div class="text-muted small mt-2">Kategori supplier</div>
                                    <div class="fw-bold">{{ $supplier->readable_category ?? '-' }}</div>
                                </div> --}}
                                <div class="col-md">
                                    <div class="text-muted small">Telepon</div>
                                    <div class="fw-bold">{{ $user->phone ?? '-' }}</div>
                                </div>
                                
                                {{-- <div class="col-md-4">
                                    <div class="text-muted small mt-2">Status</div>
                                    <span class="badge {{ $supplier->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $supplier->status_text }}
                                    </span>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section: Address --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <h3 class="card-title mb-0">Informasi Alamat</h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="text-muted small">Alamat Lengkap</div>
                            <div class="fw-bold">{{ $user->address ?? '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted small">Kelurahan</div>
                            <div class="fw-bold">{{ $user->subDistrict->name ?? '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted small">Kecamatan</div>
                            <div class="fw-bold">{{ $user->district->name ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Kabupaten/Kota</div>
                            <div class="fw-bold">{{ $user->city->name ?? '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted small">Provinsi</div>
                            <div class="fw-bold">{{ $user->province->name ?? '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted small">Kode Pos</div>
                            <div class="fw-bold">{{ $user->postalCode->postal_code ?? '-' }}</div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Section: Bank Information (ganti HR section lama) --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <h3 class="card-title mb-0">Informasi Bank</h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="text-muted small">Nama Bank</div>
                            <div class="fw-bold">{{ $user->bank->name ?? '-' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-muted small">Nomor Rekening</div>
                            <div class="fw-bold">{{ $user->account_number ?? '-' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-muted small">Atas Nama</div>
                            <div class="fw-bold">{{ $user->account_holder ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ================= TAB 2: LOYALTY ================= --}}
        <div class="tab-pane fade" id="tab-catalogue" role="tabpanel">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title mb-0">Katalog Produk</h3>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <div class="col-md-4">
                        <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modalAddProduct">
                            <i class="ti ti-plus"></i> Tambah Produk
                        </button>       
                    </div>
                </div>

                <div class="row" id="productCardContainer">
                    @foreach($products as $product)
                        <div class="col-md-4 col-lg-3 mb-4">
                            <div class="card shadow-sm border-0 h-100">

                                @if ($product->photo)
                                    <img src="{{ asset('storage/' . $product->photo) }}"
                                        class="rounded me-3"
                                        style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <div class="rounded me-3 bg-light d-flex align-items-center justify-content-center"
                                        style="width:60px; height:60px;">
                                        <i class="ti ti-photo" style="font-size:28px; color:#999;"></i>
                                    </div>
                                @endif


                                <div class="card-body">

                                    {{-- NAMA PRODUK --}}
                                    <h2 class="fw-bold">{{ $product->name }}</h2>

                                    {{-- SKU --}}
                                    @if($product->sku_code)
                                        <small class="text-muted">SKU: {{ $product->sku_code }}</small>
                                    @endif

                                    {{-- HARGA FINAL --}}
                                    <div class="mt-3">
                                        @php
                                            $pivot = $product->pivot;
                                            $buy   = $pivot->buying_prices;
                                            $sell  = $pivot->selling_prices;
                                            $spec  = $pivot->special_prices;

                                            // PRIORITAS HARGA:
                                            // 1. Harga Spesial
                                            // 2. Harga Jual
                                            // 3. Harga Beli (fallback)
                                            $final = $spec ?: ($sell ?: $buy);
                                        @endphp

                                        <p class="mb-1">
                                            <strong class="text-dark">Rp {{ number_format($final) }}</strong>
                                        </p>

                                        <p class="mb-1">
                                            <strong class="text-dark">Stok: {{ $product->pivot->stock }}</strong>
                                        </p>


                                        
                                        {{-- <div class="small text-muted">
                                            @if($sell)
                                                Harga Jual: Rp {{ number_format($sell) }} <br>
                                            @endif

                                            @if($spec)
                                                Harga Spesial: Rp {{ number_format($spec) }} <br>
                                            @endif

                                            Harga Beli: Rp {{ number_format($buy) }}
                                        </div> --}}
                                    </div>
                                </div>

                                {{-- FOOTER --}}
                                <div class="card-footer bg-white border-0 d-flex justify-content-between">
                                    <a href="#" class="btn btn-sm btn-dark">
                                        <button class="btn btn-sm btn-dark">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                    </a>
                                    <form action="#" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-dark">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    @endforeach

                </div>

            </div>
        </div>

        {{-- ================= TAB 3: SHIPPING ================= --}}
        <div class="tab-pane fade" id="tab-loyalty" role="tabpanel">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title mb-0">Detail Usaha</h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="text-muted small">Nama Usaha</div>
                            <div class="fw-bold">{{ $supplier->name ?? '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted small">Nomor Handphone</div>
                            <div class="fw-bold">{{ $supplier->phone ?? '-' }}</div>
                        </div>
                        <div class="col-12">
                            <div class="text-muted small">Alamat Lengkap</div>
                            <div class="fw-bold">{{ $supplier->address ?? '-' }}</div>
                        </div>

                        <div class="col-md-3">
                            <div class="text-muted small">Kelurahan</div>
                            <div class="fw-bold">{{ $supplier->subDistrict->name ?? '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted small">Kecamatan</div>
                            <div class="fw-bold">{{ $supplier->district->name ?? '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted small">Kabupaten/Kota</div>
                            <div class="fw-bold">{{ $supplier->city->name ?? '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted small">Provinsi</div>
                            <div class="fw-bold">{{ $supplier->province->name ?? '-' }}</div>
                        </div>

                        <div class="col-md-3">
                            <div class="text-muted small">Kode Pos</div>
                            <div class="fw-bold">{{ $supplier->postalCode->postal_code ?? '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted small">Catatan</div>
                            <div class="fw-bold">{{ $supplier->notes ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         <div class="tab-pane fade" id="tab-employment" role="tabpanel">
            <div class="card">
                <div class="card-body text-center text-muted">
                    <em>Belum ada data riwayat pembayaran.</em>
                </div>
            </div>
        </div>

    </div>
</div>
<div class="modal fade" id="modalAddProduct" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Tambah Produk Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <!-- PENCARIAN -->
                <label class="form-label">Cari Produk</label>
                <input type="text" id="searchProduct" class="form-control" placeholder="Ketik nama produk...">

                <!-- LIST HASIL CARI -->
                <div id="searchResult" class="border rounded mt-2 p-2"
                     style="max-height:230px; overflow-y:auto; display:none;">
                </div>

                <!-- FORM PRODUK BARU -->
                <form id="formCreateProduct"  enctype="multipart/form-data" style="display:none;">
                    @csrf
                    @include('products.partials.product-form')
                </form>

                <hr>

                <!-- FORM PRODUK SUPPLIER -->
                <form id="formSupplier">
                    @csrf

                    <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
                    <input type="hidden" name="product_id" id="product_id">

                    <div id="supplierSelectedProduct" class="alert alert-info" style="display:none;">
                        Produk dipilih: <strong id="selectedProductName"></strong>
                    </div>

                    <div id="supplierFormArea" style="display:none;">
                        <div class="mb-3">
                            <label>Stok</label>
                            <input type="number" class="form-control" name="stock" required>
                        </div>

                        <div class="mb-3">
                            <label>Harga</label>
                            <input type="number" class="form-control" name="buying_prices" required>
                        </div>

                        <div class="mb-3">
                            <label>PPN</label>
                            <input type="number" class="form-control" name="tax_percentage">
                        </div>

                        <div class="mb-3">
                            <label>Diskon</label>
                            <input type="number" class="form-control" name="discount">
                        </div>

                        <button class="btn btn-dark w-100" id="btnSaveSupplierProduct">
                            Simpan Produk Supplier
                        </button>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>

@endsection

@push('js')
<script>
$(document).ready(function () {

    $('#searchProduct').on('keyup', function () {
        let keyword = $(this).val().trim();

        if (keyword.length < 1) {
            $('#searchResult').hide();
            $('#formCreateProduct').hide();
            $('#supplierFormArea').hide();
            $("#product_id").val("");
            return;
        }

        $.get("{{ route('supplier.searchProduct') }}", { keyword }, function (res) {
            console.log(res);

            if (!res.found) {
                $('#formCreateProduct')[0].reset();
                $("#previewImage").attr("src", "");
                $("[name]").prop("readonly", false).prop("disabled", false);
                $('#searchResult').hide();
                $('#formCreateProduct').show();
                $('#supplierFormArea').show();
                $("#product_id").val("");

                $("#selectedProductName").text("Produk Baru");
                $("#supplierSelectedProduct").show();
                return;
            }

            // PRODUK DITEMUKAN
            $('#formCreateProduct').hide();
            $('#supplierFormArea').hide();
            $('#supplierSelectedProduct').hide();

            $('#searchResult').html(res.html).show();
        });
    });

    $(document).on("click", ".product-item", function () {

        let id = $(this).data("id");

        $.get("/supplier/product-detail/" + id, function (p) {

            // SET PRODUCT_ID
            $("#product_id").val(p.id);

            $("#selectedProductName").text(p.name);
            $("#supplierSelectedProduct").show();

            // DETAIL FORM PRODUCT (readonly)
            $('#formCreateProduct').show();

            $("[name='name']").val(p.name).prop("readonly", true);
            $("[name='description']").val(p.description).prop("readonly", true);

            $("[name='brand_id']").val(p.brand_id).prop("disabled", true);
            $("[name='category_id']").val(p.category_id).prop("disabled", true);
            $("[name='type_id']").val(p.type_id).prop("disabled", true);
            $("[name='color_id']").val(p.color_id).prop("disabled", true);

            $("[name='product_size']").val(p.size).prop("readonly", true);
            $("[name='product_volume']").val(p.volume).prop("readonly", true);

            $("[name='unit_1_name']").val(p.unit_1_name).prop("readonly", true);
            $("[name='unit_1_value']").val(p.unit_1_value).prop("readonly", true);

            $("[name='unit_2_name']").val(p.unit_2_name).prop("readonly", true);
            $("[name='unit_2_value']").val(p.unit_2_value).prop("readonly", true);

            $("[name='unit_3_name']").val(p.unit_3_name).prop("readonly", true);
            $("[name='unit_3_value']").val(p.unit_3_value).prop("readonly", true);

            $("[name='unit_4_name']").val(p.unit_4_name).prop("readonly", true);
            $("[name='unit_4_value']").val(p.unit_4_value).prop("readonly", true);

            $("#previewImage").attr("src", p.photo_url);

            // FILL SUPPLIER DEFAULTS
            $("[name='buying_prices']").val(p.default_buying_prices);
            $("[name='discount']").val(p.default_discount);
            $("[name='tax_percentage']").val(p.tax_percentage);

            $('#supplierFormArea').show();
        });
    });

    $("#btnSaveSupplierProduct").click(function (e) {
        e.preventDefault();

        let productId = $("#product_id").val();

        if (!productId || productId === "") {

            let formCreate = document.getElementById("formCreateProduct");
            let formData = new FormData(formCreate);

            $.ajax({
                url: "{{ route('products.store.ajax') }}",
                method: "POST",
                data: formData,
                processData: false,   // WAJIB untuk upload file
                contentType: false,   // WAJIB untuk upload file
                success: function(res) {

                    if (!res.success) {
                        alert("Gagal membuat produk baru.");
                        return;
                    }

                    // SET ID PRODUK BARU
                    $("#product_id").val(res.product_id);

                    // LANJUTKAN SIMPAN SUPPLIER
                    submitSupplierProduct();
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    alert("Gagal membuat produk.");
                }
            });

        }

    });

    function submitSupplierProduct() {

        let form = $("#formSupplier");

        $.ajax({
            url: "{{ route('supplier.products.store') }}",
            method: "POST",
            data: form.serialize(),
            success: function (res) {
                if (res.success) location.reload();
            },
            error: function (xhr) {
                alert(xhr.responseJSON?.message ?? "Gagal menyimpan");
            }
        });
    }

});
</script>

@endpush


@push('css')
    <style>
    .nav nav-tabs .nav-item .nav-link {
    align-items: center;
    gap: 10px;
    margin: 0 10px;
}

    .nav-link i {
    align-items: center;
    gap: 5px;
    margin: 0 10px;
}
    .page-title {
    margin: 0 10px;
}
</style>
@endpush
