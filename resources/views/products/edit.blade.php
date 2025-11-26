@extends('tablar::page')

@section('content')
<div class="page-header d-print-none mb-4">
    <div class="container-xl">
        <div class="row align-items-center">
            <div class="col d-flex align-items-center">
                <a href="{{ route('products.index') }}" class="btn btn-dark d-flex align-items-center" style="margin-left: 30px;">
                    <i class="ti ti-arrow-left"></i>
                </a>
                
                    <h2 class="page-title mb-0">Edit Data Produk</h2>
                
            </div>
        </div>
    </div>
</div>


<div class="page-body">
    <div class="container-xl">
        <div class="card shadow-sm border-0">
            <div class="card-body px-5 py-4">
                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('put')
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

                    {{-- ========== SECTION 1: INFORMASI PRIBADI ========== --}}
                    <div class="section-block mb-5">
                        <h3 class="fw-semibold mb-3 border-bottom pb-2">🧍 Informasi Pribadi</h3>
                        <div class="row g-4">
                            <div class="col-md-5">
                                <label class="form-label required">Nama Produk</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"  value="{{ old('name', $product->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label required">Kode SKU</label>
                                <input type="text" id="sku_code" name="sku_code" class="form-control" 
                                value="{{ old('sku_code', $product->sku_code) }}" required>
                                @error('sku_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">Deskripsi Produk</label>
                                <input type="text" name="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description', $product->description) }}">
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label required">Merk</label>
                                <select name="product_brand_id" class="form-select select2">
                                    <option value="">Pilih Brand</option>
                                    @foreach ($brands as $b)
                                        <option value="{{ $b->id }}"
                                            {{ $b->id == old('product_brand_id', $product->product_brand_id) ? 'selected' : '' }}>
                                            {{ $b->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_brand_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label required">Kategori</label>
                                <select name="category_id" class="form-select select2">
                            <option value="">Pilih Kategori</option>
                            @foreach ($categories as $c)
                                <option value="{{ $c->id }}"
                                    {{ $c->id == old('category_id', $product->category_id) ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label required">Tipe Produk</label>
                                <select name="type_id" class="form-select select2">
                            <option value="">Pilih Tipe</option>
                            @foreach ($types as $t)
                                <option value="{{ $t->id }}"
                                    {{ $t->id == old('type_id', $product->type_id) ? 'selected' : '' }}>
                                    {{ $t->name }}
                                </option>
                            @endforeach
                        </select>
                                @error('type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label required">Ukuran Produk</label>
                                <input type="text" name="product_size" class="form-control @error('product_size') is-invalid @enderror" value="{{ old('product_size', $product->product_size) }}">
                                @error('product_size')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label required">Volume</label>
                                <input type="text" name="product_volume" class="form-control @error('product_volume') is-invalid @enderror" value="{{ old('product_volume', $product->product_volume) }}">
                                @error('product_volume')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label required">Satuan</label>
                                <select name="color_id" class="form-select select2" required>
                            <option value="">Pilih Satuan</option>
                            @foreach ($colors as $p)
                                <option value="{{ $p->id }}"
                                    {{ $p->id == old('color_id', $product->color_id) ? 'selected' : '' }}>
                                    {{ $p->name }}
                                </option>
                            @endforeach
                        </select>
                                @error('color_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- <div class="col-md-4">
                                <label class="form-label required">Tanggal Lahir</label>
                                            <input type="date" name="birth_date" class="form-control" required
                                                value="{{ old('birth_date') }}"
                                                pattern="\d{4}-\d{2}-\d{2}" placeholder="YYYY-MM-DD">
                            </div> --}}
                            
                            
                            <div class="col-md-4">
                                <label class="form-label required">Warna Produk</label>
                                <input type="text" name="product_color" class="form-control @error('product_color') is-invalid @enderror" value="{{ old('product_color', $product->product_color) }}" required>
                                @error('product_color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Harga Beli</label>
                                <input type="text" name="buying_prices" class="form-control @error('buying_prices') is-invalid @enderror" value="{{ old('buying_prices', $product->buying_prices) }}">
                                @error('buying_prices')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Harga Jual</label>
                                <input type="text" name="selling_prices" class="form-control @error('selling_prices') is-invalid @enderror" value="{{ old('selling_prices', $product->selling_prices) }}">
                                @error('selling_prices')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Harga Spesial</label>
                                <input type="text" name="special_prices" class="form-control @error('special_prices') is-invalid @enderror" value="{{ old('special_prices', $product->special_prices) }}">
                                @error('special_prices')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Pajak</label>
                                <input type="text" name="tax_percentage" class="form-control @error('tax_percentage') is-invalid @enderror" value="{{ old('tax_percentage', $product->tax_percentage) }}">
                                @error('tax_percentage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                            <label class="form-label">Status Barang :</label>
                                            <select name="status" class="form-select">
                                                <option value="">-- Pilih Status --</option>
                                                <option value="1" {{ $product->status == 1 ? 'selected' : '' }}>Tersedia</option>
                                                <option value="2" {{ $product->status == 2 ? 'selected' : '' }}>Stok Terbatas</option>
                                                <option value="3" {{ $product->status == 3 ? 'selected' : '' }}>Habis</option>
                                                <option value="4" {{ $product->status == 4 ? 'selected' : '' }}>Pre-Order</option>
                                            </select>
                            </div>
                        </div>
                    </div>

                    {{-- ========== SECTION 2: KONTAK & ALAMAT ========== --}}
                    <div class="section-block mb-5">
                        <h3 class="fw-semibold mb-3 border-bottom pb-2">📞 Kontak & Alamat</h3>
                        {{-- <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label required">Nomor Telepon</label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required">Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label required">Alamat Lengkap</label>
                                <textarea name="address" rows="2" class="form-control @error('address') is-invalid @enderror" required>{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div> --}}
                        {{-- <div class="row g-4 mt-2">
                            <div class="col-md-6">
                                <label class="form-label required">Supplier</label>
                                <select name="supplier_id" class="form-select select2">
                            <option value="">Pilih Supplier</option>
                            @foreach ($suppliers as $s)
                                <option value="{{ $s->id }}"
                                    {{ $s->id == old('supplier_id', $product->supplier_id) ? 'selected' : '' }}>
                                    {{ $s->name }}
                                </option>
                            @endforeach
                        </select>
                                @error('supplier_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required">Gudang</label>
                                <select name="warehouse_id" class="form-select select2">
                            <option value="">Pilih Gudang</option>
                            @foreach ($warehouses as $w)
                                <option value="{{ $w->id }}"
                                    {{ $w->id == old('warehouse_id', $product->warehouse_id) ? 'selected' : '' }}>
                                    {{ $w->name }}
                                </option>
                            @endforeach
                        </select>
                                @error('warehouse_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            
                            
            
                        </div> --}}
                    </div>

                    {{-- <div class="section-block mb-5">
                        <h3 class="fw-semibold mb-3 border-bottom pb-2">🏦 Data Bank</h3>
                        <p class="small text-muted mb-3">Diperlukan bila terjadi pengembalian dana</p>
                        <div class="row g-4">
                            <div class="col-md-4">
                                <label class="form-label" for="bank_id">Nama Bank</label>
                                <select id="bank_id" name="bank_id" class="form-select">
                                    <option value="">Pilih Bank</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Nomor Rekening</label>
                                <input type="text" id="account_number" name="account_number" class="form-control @error('account_number') is-invalid @enderror" value="{{ old('account_number') }}">
                                @error('account_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Atas Nama</label>
                                <input type="text" id="account_holder" name="account_holder" class="form-control @error('account_holder') is-invalid @enderror" value="{{ old('account_holder') }}">
                                @error('account_holder')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div> --}}

                    {{-- ========== SECTION 4: DATA KEPEGAWAIAN ========== --}}
                    {{-- <div class="section-block mb-5">
                        <h3 class="fw-semibold mb-3 border-bottom pb-2">💼 Data Kepegawaian</h3>
                        <div class="row g-4"> --}}
                
                           
                            
                            
                            {{-- <div class="col-md-12">
                                <label class="form-label required" for="role">Posisi :</label>
                                <select class="form-select select2" name="role[]" multiple required>
                                    @foreach (config('employee_roles.roles') as $role)
                                        <option value="{{ $role }}" 
                                            {{ in_array($role, old('role', [])) ? 'selected' : '' }}>
                                            {{ ucfirst($role) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="section-block mb-5"> 
                                <h3 class="fw-semibold mb-3 border-bottom pb-2">📎 Dokumen Karyawan</h3>   
                                <div class="row g-4">
                                    <div class="col-md-6 mb-3">
                                        <label for="contract_letter_file" class="required">Upload Surat Perjanjian Kerja (PDF)</label>
                                        <input type="file" name="contract_letter_file" class="form-control" accept="application/pdf" required>
                                        @error('contract_letter_file')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="training_certificate">Upload Sertifikat (kalau ada)</label>
                                        <input type="file" name="training_certificate" class="form-control" accept="application/pdf">
                                        @error('training_certificate')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </div>

                    {{-- ========== SECTION 5: PENGHASILAN ========== --}}
                    {{-- <div class="section-block mb-4">
                        <h3 class="fw-semibold mb-3 border-bottom pb-2">💰 Data Penghasilan</h3>
                        <div class="row g-4">
                            <div class="col-md">
                                <label class="form-label">Gaji Pokok</label>
                                <input type="number" id="basic_salary" name="basic_salary" class="form-control @error('basic_salary') is-invalid @enderror" value="{{ old('basic_salary') }}" required>
                                @error('basic_salary')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md">
                                <label class="form-label">Tunjangan</label>
                                <input type="number" name="allowance" class="form-control" value="{{ old('allowance') }}">
                            </div>
                            <div class="col-md">
                                <label class="form-label">Potongan</label>
                                <input type="number" name="deduction" class="form-control" value="{{ old('deduction') }}">
                            </div>
                            <div class="col-md">
                                <label class="form-label">Bonus</label>
                                <input type="number" name="bonus" class="form-control" value="{{ old('bonus') }}">
                            </div>
                            <div class="col-md">
                                <label class="form-label">THR</label>
                                <input type="number" name="thr" class="form-control" value="{{ old('thr') }}">
                            </div>
                        </div>
                    </div> --}}

                    {{-- SUBMIT --}}
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
                                        $('#province').change(function () {
                                        var id = $(this).val();
                                        $('#city').html('<option>Loading...</option>');
                                        $('#district').html('<option value="">-- Pilih kecamatan --</option>');
                                        $('#sub_district').html('<option value="">-- Pilih Kelurahan --</option>');

                                            if (id) {
                                            $.get('/api/cities/' + id, function (data) {
                                            $('#city').empty().append('<option value="">-- Pilih Kabupaten --</option>');
                                            $.each(data, function (i, city) {
                                            $('#city').append('<option value="' + city.id + '">' + city.name + '</option>');
                                                        });
                                                    });
                                                }
                                            });

                                            $('#city').change(function () {
                                                var id = $(this).val();
                                                $('#district').html('<option>Loading...</option>');
                                                $('#sub_district').html('<option value="">-- Pilih Kelurahan --</option>');

                                                if (id) {
                                                    $.get('/api/districts/' + id, function (data) {
                                                        $('#district').empty().append('<option value="">-- Pilih Kecamatan --</option>');
                                                        $.each(data, function (i, district) {
                                                            $('#district').append('<option value="' + district.id + '">' + district.name + '</option>');
                                                        });
                                                    });
                                                }
                                            });

                                            $('#district').change(function () {
                                                var id = $(this).val();
                                                $('#sub_district').html('<option>Loading...</option>');

                                                if (id) {
                                                    $.get('/api/sub_districts/' + id, function (data) {
                                                        $('#sub_district').empty().append('<option value="">-- Pilih Kelurahan --</option>');
                                                        $.each(data, function (i, sub_district) {
                                                            $('#sub_district').append('<option value="' + sub_district.id + '">' + sub_district.name + '</option>');
                                                        });
                                                    });
                                                }
                                            });

                                            $('#sub_district').change(function () {
                                                var id = $(this).val();
                                                $('#postal_code').html('<option>Loading...</option>');

                                                if (id) {
                                                    $.get('/api/postal_codes/' + id, function (data) {
                                                        $('#postal_code').empty().append('<option value="">-- Pilih Kode Pos --</option>');
                                                        $.each(data, function (i, postal_code) {
                                                            $('#postal_code').append('<option value="' + postal_code.id + '">' + postal_code.postal_code + '</option>');
                                                        });
                                                    });
                                                }
                                            });
                                    </script>
@endpush
