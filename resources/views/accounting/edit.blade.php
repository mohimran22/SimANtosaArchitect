@extends('tablar::page')

@section('title', 'Edit Akun')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Akun</h1>

    <form action="{{ route('accounting.update', $account->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
                @include('components.select-license', [
                    'licenses' => $licenses,
                    'selectedLicenseId' => $account->license_id
                ])
        </div>

        <div class="mb-3">
            <label>Kode Akun</label>
            <input type="text" name="account_code" value="{{ $account->account_code }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Nama Akun</label>
            <input type="text" name="account_name" value="{{ $account->account_name }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Jenis Akun</label>
            <input type="text" name="account_type" value="{{ $account->account_type }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Debit/Kredit</label>
            <select name="balance_type" class="form-control" required>
                <option value="Debit" @if($account->balance_type == 'Debit') selected @endif>Debit</option>
                <option value="Kredit" @if($account->balance_type == 'Kredit') selected @endif>Kredit</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Saldo Awal</label>
            <input type="number" step="0.01" name="initial_balance" value="{{ $account->initial_balance }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Apakah Akun Induk?</label>
            <input type="checkbox" name="is_parent" value="1" {{ $account->is_parent ? 'checked' : '' }}>
        </div>

        <div class="mb-3">
            <label>Akun Induk</label>
            <select name="parent_id" class="form-control">
                <option value="">-- Tidak Ada --</option>
                @foreach ($parentAccounts as $parent)
                    <option value="{{ $parent->id }}" @if($account->parent_id == $parent->id) selected @endif>
                        {{ $parent->account_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
