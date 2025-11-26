@php
    $product = $product ?? null;
@endphp

{{-- ERROR VALIDATION --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


{{-- FOTO PRODUK --}}
<div class="text-center mb-5">
    <div class="position-relative d-inline-block">
        @if ($product && $product->photo)
            <img id="previewImage" 
                 src="{{ asset('storage/'.$product->photo) }}" 
                 class="rounded-3 shadow-sm border"
                 width="150" height="150"
                 style="object-fit: cover;">
        @else
            <div id="previewImage"
                 class="rounded-3 shadow-sm bg-light d-flex align-items-center justify-content-center"
                 style="width:150px; height:150px;">
                <i class="ti ti-photo" style="font-size: 64px; color:#aaa;"></i>
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


{{-- INFORMASI PRODUK --}}
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
            <input type="text" name="name" 
                   class="form-control"
                   value="{{ old('name', $product->name ?? '') }}" required>
        </div>

        <div class="col-12">
            <label class="form-label required">Deskripsi Produk</label>
            <textarea name="description" rows="2" 
                      class="form-control" required>{{ old('description', $product->description ?? '') }}</textarea>
        </div>

        <div class="col-md-4">
            <label class="form-label required">Merk</label>
            <select name="brand_id" class="form-select select2" required>
                <option value="">-- Pilih Merk --</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}"
                        {{ old('brand_id', $product->brand_id ?? '') == $brand->id ? 'selected' : '' }}>
                        {{ $brand->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label required">Kategori</label>
            <select name="category_id" class="form-select select2" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label required">Tipe Produk</label>
            <select name="type_id" class="form-select select2" required>
                <option value="">-- Pilih Tipe --</option>
                @foreach($types as $type)
                    <option value="{{ $type->id }}"
                        {{ old('type_id', $product->type_id ?? '') == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Ukuran Produk</label>
            <input type="text" name="product_size" 
                   class="form-control"
                   value="{{ old('product_size', $product->product_size ?? '') }}">
        </div>

        <div class="col-md-4">
            <label class="form-label">Volume</label>
            <input type="text" name="product_volume" 
                   class="form-control"
                   value="{{ old('product_volume', $product->product_volume ?? '') }}">
        </div>

        <div class="col-md-4">
            <label class="form-label required">Warna Produk</label>
            <select name="color_id" class="form-select select2" required>
                <option value="">-- Pilih Warna --</option>
                @foreach($colors as $color)
                    <option value="{{ $color->id }}"
                        {{ old('color_id', $product->color_id ?? '') == $color->id ? 'selected' : '' }}>
                        {{ $color->name }}
                    </option>
                @endforeach
            </select>
        </div>

    </div>
</div>


{{-- UNIT SATUAN --}}
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


@push('js')
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
@endpush

