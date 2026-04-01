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
                                                <label>Kategori</label>
                                                <select name="account_type" class="form-select select2" required>
                                                    <option value="">-- Pilih Kategori --</option>
                                                    <option value="AKTIVA">AKTIVA</option>
                                                    <option value="KEWAJIBAN">KEWAJIBAN</option>
                                                    <option value="EKUITAS">EKUITAS</option>
                                                    <option value="PENDAPATAN">PENDAPATAN</option>
                                                    <option value="Beban">BEBAN</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label>Sub Kategori</label>
                                                <select name="balance_type" class="form-select select2" required>
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
                                                    <option value="Pendapatan Lisensi">Pendapatan Lisensi</option>
                                                    <option value="Pendapatan Modul">Pendapatan Modul</option>
                                                    <option value="Pendapatan Siswa">Pendapatan Siswa</option>
                                                    <option value="Pendapatan Merchandise">Pendapatan Merchandise</option>
                                                    <option value="Pendapatan Lainnya">Pendapatan Lainnya</option>
                                                    <option value="Biaya Lisensi">Biaya Lisensi</option>
                                                    <option value="Biaya Pembelian Modul">Biaya Pembelian Modul</option>
                                                    <option value="Biaya Pembelian Merchandise">Biaya Pembelian Merchandise</option>
                                                    <option value="Biaya Produksi Modul">Biaya Produksi Modul</option>
                                                    <option value="Biaya Produksi Merchandise">Biaya Produksi Merchandise</option>
                                                    <option value="Beban Penjualan & Pemasaran">Beban Penjualan & Pemasaran</option>
                                                    <option value="Beban Administrasi & Umum">Beban Administrasi & Umum</option>
                                                </select>
                                            </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-6 mb-3">
                                            <label>Kode Akun</label>
                                            <input type="text" name="account_code" class="form-control" required>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label>Nama Akun</label>
                                            <input type="text" name="account_name" class="form-control" required>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label>Saldo Awal</label>
                                            <input type="number" step="0.01" name="initial_balance" class="form-control">
                                        </div>
                                    </div>

                                    {{-- <div class="row mb-4">
                                        <div class="col-md-6 mb-3">
                                            <label>Apakah Akun Induk?</label>
                                            <input type="checkbox" name="is_parent" value="1">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label>Akun Induk</label>
                                            <select name="parent_id" class="form-select">
                                                <option value="">-- Tidak Ada --</option>
                                                @foreach ($parentAccounts as $parent)
                                                    <option value="{{ $parent->id }}">{{ $parent->account_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> --}}

                                    <div class="text-end">
                                        <button class="btn btn-dark text-white">Simpan</button>
                                    </div>
                                </form>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{-- <div class="container">
    <h1 class="mb-4">Tambah Akun</h1>

    
</div> --}}
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
@endpush