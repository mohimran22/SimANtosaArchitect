@extends('tablar::page')

@section('content')
<!-- Page header -->
    <div class="page-header d-print-none mb-4">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col d-flex align-items-center">
                    <a href="{{ route('accounting.index') }}" class="btn btn-dark d-flex align-items-center">
                        <i class="ti ti-arrow-left"></i>
                    </a>      
                        <h2 class="page-title mb-0">Tambah Akun Akuntansi</h2> 
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                            <div class="card-body">
                                <form action="{{ route('accounting.store') }}" method="POST">
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
                                    {{-- <div class="row mb-4">
                                        <div class="col-md-6 mb-3">
                                                @include('components.select-license', [
                                                    'licenses' => $licenses,
                                                    'selectedLicenseId' => old('license_id', $yourModel->license_id ?? null)
                                                ])
                                        </div>
                                    </div> --}}


                                    <div class="row mb-4">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Kode Akun</label>
                                            <input type="text" id="account_code_preview" class="form-control" readonly>
                                            <input type="hidden" name="account_code" id="account_code">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Nama Akun</label>
                                            <input type="text" name="account_name" class="form-control" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Kategori</label>
                                            <select name="category" class="form-select select2" required>
                                                <option value="">-- Pilih Kategori --</option>
                                                <option value="AKTIVA">AKTIVA</option>
                                                <option value="KEWAJIBAN">KEWAJIBAN</option>
                                                <option value="EKUITAS">EKUITAS</option>
                                                <option value="PENDAPATAN">PENDAPATAN</option>
                                                <option value="BEBAN">BEBAN</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Sub Kategori</label>
                                            <select name="sub_category" class="form-select select2" required>
                                                <option value="">-- Pilih Sub kategori --</option>
                                                <option value="Aset Lancar - Kas & Bank">Aset Lancar - Kas & Bank</option>
                                                <option value="Aset Lancar - Persediaan Barang">Aset Lancar - Persediaan Barang</option>
                                                <option value="Aset Lancar - Piutang">Aset Lancar - Piutang</option>
                                                <option value="Aset Lancar - Dana Belum Disetor">Aset Lancar - Dana Belum Disetor</option>
                                                <option value="Aset Lancar - Pajak Bayar Dimuka">Aset Lancar - Pajak Bayar Dimuka</option>
                                                <option value="Aset Tetap">Aset Tetap</option>
                                                <option value="Penyusutan">Penyusutan</option>
                                                <option value="Hutang">Hutang</option>
                                                <option value="Uang Muka Penjualan">Uang Muka Penjualan</option>
                                                <option value="Pajak">Pajak</option>
                                                <option value="Modal">Modal</option>
                                                <option value="Pendapatan Desain">Pendapatan Desain</option>
                                                <option value="Pendapatan RAB">Pendapatan RAB</option>
                                                <option value="Pendapatan Build">Pendapatan Build</option>
                                                <option value="Pendapatan Lainnya">Pendapatan Lainnya</option>
                                                <option value="Biaya Desain">Biaya Desain</option>
                                                <option value="Biaya RAB">Biaya RAB</option>
                                                <option value="Biaya Build">Biaya Build</option>
                                                <option value="Beban Penjualan & Pemasaran">Beban Penjualan & Pemasaran</option>
                                                <option value="Beban Administrasi & Umum">Beban Administrasi & Umum</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Saldo Awal</label>
                                            <input type="number" step="0.01" name="initial_balance" class="form-control">
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Apakah Akun Induk?</label>
                                            <select name="is_parent" 
                                                    class="form-select select2 @error('is_parent') is-invalid @enderror">
                                                <option value="">-- Silahkan dipilih --</option>
                                                <option value="1" {{ old('is_parent') == '1' ? 'selected' : '' }}>Ya (Akun Induk)</option>
                                                <option value="0" {{ old('is_parent') == '0' ? 'selected' : '' }}>Tidak (Akun Anak)</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Akun Induk</label>
                                            <select name="parent_id" class="form-select select2">
                                                <option value="">-- Pilih Akun Induk --</option>
                                                @foreach ($parentAccounts as $parent)
                                                    <option value="{{ $parent->id }}">{{ $parent->account_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                        <div class="text-end mt-5">
                                            <button type="submit" class="btn btn-dark px-4">
                                                <i class="ti ti-device-floppy me-1"></i>Simpan Data Akun
                                            </button>
                                        </div>
                                </form>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%'
        });
    });
</script>
<script>
$(document).ready(function(){

    function generateCode(){
        let category = $('[name="category"]').val()
        let parentId = $('[name="parent_id"]').val() // ✅ ganti

        if(!category){
            $('#account_code_preview').val('')
            return
        }

        $('#account_code_preview').val('Generating...')

        fetch(`/accounting/generate-code?category=${encodeURIComponent(category)}&parent_id=${encodeURIComponent(parentId ?? '')}`)
            .then(res => res.json())
            .then(data => {
                $('#account_code_preview').val(data.code)
            })
    }

    $('[name="category"]').on('change', generateCode)
    $('[name="parent_id"]').on('change', generateCode) // ✅ ganti

})
</script>
@endpush