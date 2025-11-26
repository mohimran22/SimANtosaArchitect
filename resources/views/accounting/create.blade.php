@extends('tablar::page')

@section('title', 'Tambah Akun')

@section('content')
<div class="container">
    <h1 class="mb-4">Tambah Akun</h1>

    <form action="{{ route('accounting.store') }}" method="POST">
        @csrf

        <div class="mb-3">
                @include('components.select-license', [
                    'licenses' => $licenses,
                    'selectedLicenseId' => old('license_id', $yourModel->license_id ?? null)
                ])
        </div>

        <div class="mb-3">
            <label>Kode Akun</label>
            <input type="text" name="account_code" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Nama Akun</label>
            <input type="text" name="account_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Jenis Akun</label>
            <input type="text" name="account_type" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Debit/Kredit</label>
            <select name="balance_type" class="form-select" required>
                <option value="Debit">Debit</option>
                <option value="Kredit">Kredit</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Saldo Awal</label>
            <input type="number" step="0.01" name="initial_balance" class="form-control">
        </div>

        <div class="mb-3">
            <label>Apakah Akun Induk?</label>
            <input type="checkbox" name="is_parent" value="1">
        </div>

        <div class="mb-3">
            <label>Akun Induk</label>
            <select name="parent_id" class="form-select">
                <option value="">-- Tidak Ada --</option>
                @foreach ($parentAccounts as $parent)
                    <option value="{{ $parent->id }}">{{ $parent->account_name }}</option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
