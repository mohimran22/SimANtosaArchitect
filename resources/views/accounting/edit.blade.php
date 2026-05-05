@extends('tablar::page')

@section('content')
    <div class="page-header d-print-none mb-4">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col d-flex align-items-center">
                    <a href="{{ route('accounting.index') }}" class="btn btn-dark d-flex align-items-center">
                        <i class="ti ti-arrow-left"></i>
                    </a>      
                        <h2 class="page-title mb-0">Edit Akun Akuntansi</h2> 
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
                            <form action="{{ route('accounting.update', $account->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul class="mb-0">
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                <div class="mb-3">
                                    <label>Kode Akun</label>
                                    <input type="text" name="account_code" value="{{ $account->account_code }}" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label>Nama Akun</label>
                                    <input type="text" name="account_name" value="{{ $account->account_name }}" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label>Kategori</label>
                                    <input type="text" name="category" value="{{ $account->category }}" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label>Sub Kategori</label>
                                    <input type="text" name="sub_category" value="{{ $account->sub_category }}" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label>Saldo Awal</label>
                                    <input type="number" step="0.01" name="initial_balance" value="{{ $account->initial_balance }}" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label>Apakah Akun Induk?</label>
                                    <input type="hidden" name="is_parent" value="0">
                                    <input type="checkbox" name="is_parent" value="1" {{ $account->is_parent ? 'checked' : '' }}>
                                </div>

                                <div class="mb-3">
                                    <label>Akun Induk</label>
                                    <select name="parent_id" class="form-select select2">
                                        <option value="">-- Pilih Akun Induk --</option>
                                        @foreach ($parentAccounts as $parent)
                                            <option value="{{ $parent->id }}" {{ $account->parent_id == $parent->id ? 'selected' : '' }}>
                                                {{ $parent->account_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="text-end mt-5">
                                            <button type="submit" class="btn btn-dark px-4">
                                                <i class="ti ti-device-floppy me-1"></i>Simpan Perubahan
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
        $('.select2').select2({width: '100%'});
    });
</script>
@endpush