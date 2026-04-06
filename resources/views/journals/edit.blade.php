@extends('tablar::page')

@section('content')
<div class="container">
    <h1>Edit Jurnal</h1>

    <form action="{{ route('journals.update', $journal->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Pilih lisensi jika mau diubah -->

        <div class="mb-3">
                @include('components.select-license', [
                    'licenses' => $licenses,
                    'selectedLicenseId' => old('license_id', $yourModel->license_id ?? null)
                ])
        </div>

        <div class="mb-3">
            <label for="transaction_date">Tanggal Transaksi</label>
            <input type="date" name="transaction_date" class="form-control"
                   value="{{ old('transaction_date', $journal->transaction_date) }}" required>
        </div>

        <div class="mb-3">
            <label for="description">Deskripsi Umum</label>
            <textarea name="description" class="form-control">{{ old('description', $journal->description) }}</textarea>
        </div>

        <h4>Detail Akun</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Akun</th>
                    <th>Debit</th>
                    <th>Kredit</th>
                    <th>Keterangan</th>
                    <th><button type="button" id="add-row" class="btn btn-sm btn-dark">Tambah</button></th>
                </tr>
            </thead>
            <tbody id="details-table-body">
                @foreach($journal->details as $index => $detail)
                    <tr>
                        <td>
                            <select name="details[{{ $index }}][account_id]" class="form-control" required>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}"
                                        {{ $account->id == $detail->account_id ? 'selected' : '' }}>
                                        {{ $account->account_name }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" step="0.01" name="details[{{ $index }}][debit]"
                                   class="form-control" value="{{ $detail->debit }}">
                        </td>
                        <td>
                            <input type="number" step="0.01" name="details[{{ $index }}][credit]"
                                   class="form-control" value="{{ $detail->credit }}">
                        </td>
                        <td>
                            <input type="text" name="details[{{ $index }}][description]"
                                   class="form-control" value="{{ $detail->description }}">
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-dark remove-row">Hapus</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-end mt-5">
                                            <button type="submit" class="btn btn-dark px-4">
                                                <i class="ti ti-device-floppy me-1"></i>Simpan Perubahan
                                            </button>
                                </div>
    </form>
</div>

<!-- Script add row dinamis -->
<script>
    let index = {{ $journal->details->count() }};
    document.getElementById('add-row').addEventListener('click', function() {
        const tbody = document.getElementById('details-table-body');

        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td>
                <select name="details[${index}][account_id]" class="form-control" required>
                    <option value="">-- Pilih Akun --</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}">{{ $account->account_name }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" step="0.01" name="details[${index}][debit]" class="form-control"></td>
            <td><input type="number" step="0.01" name="details[${index}][credit]" class="form-control"></td>
            <td><input type="text" name="details[${index}][description]" class="form-control"></td>
            <td><button type="button" class="btn btn-sm btn-danger remove-row">Hapus</button></td>
        `;
        tbody.appendChild(newRow);
        index++;
    });

    // remove row
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-row')) {
            e.target.closest('tr').remove();
        }
    });
</script>
@endsection
