@extends('tablar::page')

@section('content')
<div class="container">
    <h2>Tambah Jurnal</h2>

    {{-- Alert Success --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Alert Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi Kesalahan!</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form --}}
    <form action="{{ route('journals.store') }}" method="POST">
        @csrf

        {{-- License --}}
            <div class="mb-3">
                @include('components.select-license', [
                    'licenses' => $licenses,
                    'selectedLicenseId' => old('license_id', $yourModel->license_id ?? null)
                ])
            </div>

        {{-- Tanggal --}}
        <div class="mb-3">
            <label for="transaction_date">Tanggal Transaksi</label>
            <input type="date" name="transaction_date" class="form-control" required>
        </div>

        {{-- Deskripsi --}}
        <div class="mb-3">
            <label for="description">Deskripsi Umum</label>
            <textarea name="description" class="form-control"></textarea>
        </div>

        {{-- Detail Akun --}}
        <h5>Detail Akun</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Akun</th>
                    <th>Debit</th>
                    <th>Kredit</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="detail-rows">
                <tr>
                    <td>
                        <select name="details[0][account_id]" class="form-select" required>
                            <option value="">-- Pilih Akun --</option>
                            @foreach ($accounts as $account)
                                <option value="{{ $account->id }}">{{ $account->account_code }} - {{ $account->account_name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" step="0.01" name="details[0][debit]" class="form-control debit"></td>
                    <td><input type="number" step="0.01" name="details[0][credit]" class="form-control credit"></td>
                    <td><input type="text" name="details[0][description]" class="form-control"></td>
                    <td><button type="button" class="btn btn-sm btn-danger remove-row">Hapus</button></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5">
                        <button type="button" id="add-row" class="btn btn-sm btn-primary">Tambah Baris</button>
                    </td>
                </tr>
                <tr>
                    <th>Subtotal</th>
                    <th id="subtotal-debit">0</th>
                    <th id="subtotal-credit">0</th>
                    <th colspan="2"></th>
                </tr>
            </tfoot>
        </table>

        {{-- Validasi Total (khusus error total) --}}
        @if ($errors->has('total'))
            <div class="alert alert-danger">
                {{ $errors->first('total') }}
            </div>
        @endif

        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    let index = 1;

    // Tambah Baris
    document.getElementById('add-row').addEventListener('click', function() {
        let newRow = `
            <tr>
                <td>
                    <select name="details[${index}][account_id]" class="form-control" required>
                        <option value="">-- Pilih Akun --</option>
                        @foreach ($accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->account_code }} - {{ $account->account_name }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="number" step="0.01" name="details[${index}][debit]" class="form-control debit"></td>
                <td><input type="number" step="0.01" name="details[${index}][credit]" class="form-control credit"></td>
                <td><input type="text" name="details[${index}][description]" class="form-control"></td>
                <td><button type="button" class="btn btn-sm btn-danger remove-row">Hapus</button></td>
            </tr>
        `;
        document.getElementById('detail-rows').insertAdjacentHTML('beforeend', newRow);
        index++;
    });

    // Hapus Baris
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-row')) {
            e.target.closest('tr').remove();
            updateSubtotals();
        }
    });

    // Hitung Subtotal Debit/Kredit
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('debit') || e.target.classList.contains('credit')) {
            updateSubtotals();
        }
    });

    function updateSubtotals() {
        let debitTotal = 0;
        let creditTotal = 0;

        document.querySelectorAll('.debit').forEach(input => {
            debitTotal += parseFloat(input.value) || 0;
        });

        document.querySelectorAll('.credit').forEach(input => {
            creditTotal += parseFloat(input.value) || 0;
        });

        document.getElementById('subtotal-debit').innerText = debitTotal.toFixed(2);
        document.getElementById('subtotal-credit').innerText = creditTotal.toFixed(2);
    }
</script>
@endsection
