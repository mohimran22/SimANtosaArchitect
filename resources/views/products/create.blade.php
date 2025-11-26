@extends('tablar::page')

@section('content')
    <div class="page-header d-print-none mb-4">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col d-flex align-items-center">
                    <a href="{{ route('products.index') }}" class="btn btn-dark d-flex align-items-center" style="margin-left: 30px;">
                        <i class="ti ti-arrow-left"></i>
                    </a>
                    
                        <h2 class="page-title mb-0">Tambah Produk</h2>
                    
                </div>
            </div>
        </div>
    </div>


    <div class="page-body">
        <div class="container-xl">
            <div class="card shadow-sm border-0">
                <div class="card-body px-5 py-4">
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul class="mb-0">
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                        <div class="text-center mb-5">
                        <div class="position-relative d-inline-block">
                            @if ($product->photo)
                                <img id="previewImage" src="{{ asset('storage/'.$product->photo) }}" alt="Profile" 
                                    class="rounded-3 shadow-sm border" width="150" height="150"
                                    style="object-fit: cover;">
                            @else
                                <div id="previewImage"
                                    class="rounded-3 shadow-sm bg-light d-flex align-items-center justify-content-center"
                                    style="width:150px; height:150px;">
                                    <i class="ti ti-user" style="font-size: 64px; color:#aaa;"></i>
                                </div>
                            @endif
                            <label for="photo"
                                class="btn btn-sm btn-dark position-absolute bottom-0 end-0 translate-middle rounded-circle"
                                title="Ganti Foto">
                                <i class="ti ti-camera"></i>
                            </label>
                        </div>
                        <input type="file" id="photo" name="photo" class="d-none" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <small class="text-danger fw-semibold">
                                * : Wajib diisi
                            </small>
                        </div>

                        <div class="section-block mb-5">
                            <h3 class="fw-semibold mb-3 border-bottom pb-2">Informasi Produk</h3>
                            <div class="row g-4">
                                <div class="col-md-5">
                                    <label class="form-label required">Nama Produk</label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"  value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label required">Deskripsi Produk</label>
                                    <textarea name="description" rows="2" class="form-control @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required">Merk</label>
                                    <select name="brand_id" class="form-select select2" required>
                                        <option value="brand_id">-- Pilih Merk --</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('brand_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required">Kategori</label>
                                    <select name="category_id" class="form-select select2" required>
                                        <option value="category_id">-- Pilih Kategori --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required">Tipe Produk</label>
                                    <select name="type_id" class="form-select select2" required>
                                        <option value="type_id">-- Pilih Tipe --</option>
                                        @foreach($types as $type)
                                            <option value="{{ $type->id }}" {{ old('type_id') == $type->id ? 'selected' : '' }}>
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('type_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Ukuran Produk</label>
                                    <input type="text" name="product_size" class="form-control @error('product_size') is-invalid @enderror" value="{{ old('product_size') }}">
                                    @error('product_size')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Volume</label>
                                    <input type="text" name="product_volume" class="form-control @error('product_volume') is-invalid @enderror" value="{{ old('product_volume') }}">
                                    @error('product_volume')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required">Warna Produk</label>
                                    <select name="color_id" class="form-select select2" required>
                                        <option value="color_id">-- Pilih Warna --</option>
                                        @foreach($colors as $color)
                                            <option value="{{ $color->id }}" {{ old('_id') == $color->id ? 'selected' : '' }}>
                                                {{ $color->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('color_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                                
                        <div class="section-block mb-4">
                            <h3 class="fw-semibold mb-3 border-bottom pb-2">Pengaturan Satuan Unit</h3>

                            <div class="row g-2">

                                <div class="row g-2">
                                    <div class="col-md-3">
                                        <label class="form-label">Satuan Level 1</label>
                                        <input type="text" name="unit_1_name" 
                                            class="form-control form-control-md" 
                                            value="{{ old('unit_1_name', $product->unit_1_name ?? 'PCS') }}" >
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Nilai Unit 1</label>
                                        <input type="number" name="unit_1_value" 
                                            class="form-control form-control-md" 
                                            value="1" readonly>
                                    </div>
                                </div>

                                <div class="row g-2">
                                    <div class="col-md-3">
                                        <label class="form-label">Satuan Level 2</label>
                                        <input type="text" name="unit_2_name" 
                                            class="form-control form-control-md"
                                            value="{{ old('unit_2_name') }}">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Konversi Unit 2</label>
                                        <input type="number" name="unit_2_value" 
                                            class="form-control form-control-md"
                                            value="{{ old('unit_2_value') }}">
                                        <small class="text-muted" id="unit_2_preview"></small>
                                    </div>
                                </div>

                                <div class="row g-2">
                                    <div class="col-md-3">
                                        <label class="form-label">Satuan Level 3</label>
                                        <input type="text" name="unit_3_name" 
                                            class="form-control form-control-md"
                                            value="{{ old('unit_3_name') }}">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Konversi Unit 3</label>
                                        <input type="number" name="unit_3_value" 
                                            class="form-control form-control-md"
                                            value="{{ old('unit_3_value') }}">
                                        <small class="text-muted" id="unit_3_preview"></small>
                                    </div>
                                </div>

                                <div class="row g-2">
                                    <div class="col-md-3">
                                        <label class="form-label">Satuan Level 4</label>
                                        <input type="text" name="unit_4_name" 
                                            class="form-control form-control-md"
                                            value="{{ old('unit_4_name') }}">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Konversi Unit 4</label>
                                        <input type="number" name="unit_4_value" 
                                            class="form-control form-control-md"
                                            value="{{ old('unit_4_value') }}">
                                        <small class="text-muted" id="unit_4_preview"></small>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- <div class="section-block mb-5 mt-4">
                            <h3 class="fw-semibold mb-3 border-bottom pb-2">Perhitungan Profit (Otomatis)</h3>

                            <div class="row g-3">

                                <div class="col-md-4">
                                    <label class="form-label">Harga Beli</label>
                                    <input type="number" step="0.01" id="buying_prices" name="buying_prices" 
                                        class="form-control form-control-sm" value="{{ old('buying_prices') }}">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Harga Jual</label>
                                    <input type="number" step="0.01" id="selling_prices" name="selling_prices" 
                                        class="form-control form-control-sm" value="{{ old('selling_prices') }}">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Harga Spesial (Opsional)</label>
                                    <input type="number" step="0.01" id="special_prices" name="special_prices" 
                                        class="form-control form-control-sm" value="{{ old('special_prices') }}">
                                </div>
                            </div>

                            <div class="mt-4">
                                <h6 class="fw-semibold">Profit Normal</h6>
                                <p class="mb-1">
                                    Rp <span id="profit_rp">0</span>  
                                    (<span id="profit_percent">0</span>%)
                                </p>

                                <h6 class="fw-semibold mt-3">Profit Spesial</h6>
                                <p class="mb-1">
                                    Rp <span id="profit_special_rp">0</span>  
                                    (<span id="profit_special_percent">0</span>%)
                                </p>

                                <p id="profit_alert" class="text-danger fw-bold mt-3"></p>
                            </div>
                        </div> --}}

                        {{-- <div class="section-block mt-4">
                            <h3 class="fw-semibold mb-3 border-bottom pb-2">Perhitungan Profit</h3>

                            <div class="row g-3">

                                <div class="col-md-4">
                                    <label class="form-label">Harga Beli Supplier</label>
                                    <input type="text" class="form-control" 
                                        value="Rp {{ number_format($product->buying_prices) }}" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Harga Jual Supplier</label>
                                    <input type="text" class="form-control" 
                                        value="Rp {{ number_format($product->selling_prices) }}" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Profit (Rp)</label>
                                    <input type="text" class="form-control" 
                                        value="Rp {{ number_format($profit) }}" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Margin (%)</label>
                                    <input type="text" class="form-control" 
                                        value="{{ number_format($margin, 2) }}%" readonly>
                                </div>

                            </div>
                        </div> --}}




                            <div class="text-end mt-5">
                            <button type="submit" class="btn btn-dark px-4">
                                <i class="ti ti-device-floppy me-1"></i> Simpan Data
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
                                            $(document).ready(function() {
                                                $('.select2').select2({
                                                    placeholder: "-- Pilih --",
                                                    width: '100%'
                                                });
                                            });
                                        </script>

<script>
        document.getElementById('photo').addEventListener('change', function (event) {
        const input = event.target;
        const file = input.files[0];
        const previewContainer = document.getElementById('previewImage');

        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                // Jika sebelumnya preview berupa ikon <div>, ganti jadi <img>
                if (previewContainer.tagName.toLowerCase() === 'div') {
                    const img = document.createElement('img');
                    img.id = 'previewImage';
                    img.src = e.target.result;
                    img.className = 'border rounded-3 shadow-sm';
                    img.width = 150;
                    img.height = 150;
                    img.style.objectFit = 'cover';
                    previewContainer.replaceWith(img);
                } else {
                    previewContainer.src = e.target.result;
                }
            };
            reader.readAsDataURL(file);
        }
    });
</script>

<script>
function updateUnitPreview() {
    let u1 = $('input[name="unit_1_name"]').val() || 'PCS';
    let v1 = parseInt($('input[name="unit_1_value"]').val()) || 1;

    let u2 = $('input[name="unit_2_name"]').val();
    let v2 = parseInt($('input[name="unit_2_value"]').val());

    let u3 = $('input[name="unit_3_name"]').val();
    let v3 = parseInt($('input[name="unit_3_value"]').val());

    let u4 = $('input[name="unit_4_name"]').val();
    let v4 = parseInt($('input[name="unit_4_value"]').val());

    // Unit 2
    if (u2 && v2) {
        let result2 = v2 * v1;
        $('#unit_2_preview').text(`1 ${u2} = ${result2} ${u1}`);
    } else {
        $('#unit_2_preview').text('');
    }

    // Unit 3
    if (u3 && v3 && u2 && v2) {
        let result3 = v3 * v2 * v1;
        $('#unit_3_preview').text(`1 ${u3} = ${result3} ${u1}`);
    } else {
        $('#unit_3_preview').text('');
    }

    // Unit 4
    if (u4 && v4 && u3 && v3 && u2 && v2) {
        let result4 = v4 * v3 * v2 * v1;
        $('#unit_4_preview').text(`1 ${u4} = ${result4} ${u1}`);
    } else {
        $('#unit_4_preview').text('');
    }
}

// Jalankan setiap kali input berubah
$('input[name="unit_1_name"], input[name="unit_1_value"], ' +
  'input[name="unit_2_name"], input[name="unit_2_value"], ' +
  'input[name="unit_3_name"], input[name="unit_3_value"], ' +
  'input[name="unit__4_name"], input[name="unit_4_value"]'
).on('keyup change', updateUnitPreview);

</script>

<script>
    function calculateProfit() {
        let buy = parseFloat($("#buying_prices").val()) || 0;
        let sell = parseFloat($("#selling_prices").val()) || 0;
        let special = parseFloat($("#special_prices").val()) || 0;

        // Profit normal
        let profit = sell - buy;
        let margin = buy > 0 ? (profit / buy * 100) : 0;

        $("#profit_rp").text(profit.toLocaleString());
        $("#profit_percent").text(margin.toFixed(2));

        // Profit spesial
        let profitSpecial = special ? (special - buy) : 0;
        let marginSpecial = (special && buy > 0) ? (profitSpecial / buy * 100) : 0;

        $("#profit_special_rp").text(profitSpecial.toLocaleString());
        $("#profit_special_percent").text(marginSpecial.toFixed(2));

        // Warning jika profit minus
        if (profit < 0 || profitSpecial < 0) {
            $("#profit_alert").text("⚠️ Profit negatif! Periksa kembali harga jual.");
        } else {
            $("#profit_alert").text("");
        }
    }

    // Trigger realtime
    $("#buying_prices, #selling_prices, #special_prices").on("keyup change", calculateProfit);

    // Jalankan pertama kali
    calculateProfit();
</script>



                                    @endpush


