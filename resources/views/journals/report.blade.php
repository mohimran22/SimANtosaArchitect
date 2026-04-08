@extends('tablar::page')

@section('content')
<div class="container">
    <h1 class="text-center">TRANSAKSI</h1>

   {{-- Filter --}}
    <form action="{{ route('journals.report') }}" method="GET" class="card p-4 mb-4">
        <div class="row mb-3">
            {{-- @if (auth()->user()->hasRole('Super-Admin') || auth()->user()->hasRole('Pemilik Lisensi') || auth()->user()->hasRole('Akuntan'))
                <div class="col-md-3">
                    <label for="license_id" class="form-label">Lisensi</label>
                    <select name="license_id" id="license_id" class="form-select select2">
                        <option value="">-- Semua Lisensi --</option>
                        @foreach ($licenses as $license)
                            <option value="{{ $license->id }}" {{ request('license_id') == $license->id ? 'selected' : '' }}>
                                {{ $license->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif    --}}

           <div class="col-md-3">
                <label for="account_id" class="form-label">Akun</label>
                <select name="account_id" id="account_id" class="form-select select2">
                    <option value="">-- Semua Akun --</option>
                    @foreach ($accounts as $account)
                        <option value="{{ $account->id }}" {{ request('account_id') == $account->id ? 'selected' : '' }}>
                            [{{ $account->account_code }}] {{ $account->account_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label for="start_date" class="form-label">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
            </div>

            <div class="col-md-2">
                <label for="end_date" class="form-label">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
            </div>

            <div class="col-md-2">
                <label>Cari Transaksi</label>
                <input type="text" id="searchInput" class="form-control" placeholder="Cari data...">
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary text-white">Filter</button>
            <a href="{{ route('journals.report') }}" class="btn btn-secondary text-white">Reset</a>
            <a href="{{ route('kas.export.excel', request()->all()) }}" class="btn btn-success text-white">
                <i class="ti ti-file-export"></i> Export Excel
            </a>
        </div>
    </form>

    <div class="table-responsive">
        <table id="kasTable" class="table table-bordered table-striped">
        <thead class="text-center">
            <tr>
                <th>NO</th>
                <th>TANGGAL</th>
                <th class="desc-column">DESKRIPSI</th>
                <th class="code-column">KODE AKUN</th>
                <th class="account-column">AKUN</th>
                <th class="user-column">USER</th>
                <th class="user-column">PIC</th>
                <th>DEBIT (pemasukan)</th>
                <th>KREDIT (pengeluaran)</th>
                <th>SALDO</th>
                <th>KETERANGAN</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $saldo = 0;
                $totalDebit = 0;
                $totalKredit = 0;
            @endphp

            @foreach ($journals as $journal)
                @foreach ($journal->details as $detail)
                    @php
                        $debit = $detail->debit;
                        $kredit = $detail->credit;
                        $saldo += $debit - $kredit;
                        $totalDebit += $debit;
                        $totalKredit += $kredit;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td>{{ \Carbon\Carbon::parse($journal->transaction_date)->format('d/m/Y') }}</td>
                        <td class="desc-column">{{ $detail->description ?? '-'}}</td>
                        <td class="text-center">{{ $detail->account->account_code }}</td>
                        <td class="account-column">{{ $detail->account->account_name }}</td>
                        <td class="user-column">{{ $detail->person_name }}</td>
                        <td>
                            @if($journal->creator)
                                {{ $journal->creator->fullname }}
                            @else
                                <small class="fst-italic text-muted">dibuat oleh sistem</small>
                            @endif
                        </td>

                        <td class="text-end">Rp {{ number_format($debit, 2, ',', '.') }}</td>
                        <td class="text-end">Rp {{ number_format($kredit, 2, ',', '.') }}</td>
                        <td class="text-end">Rp {{ number_format($saldo, 2, ',', '.') }}</td>
                        <td>{{ $journal->description ?? '-' }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
        <tfoot>
            <tr class="fw-bold">
                <td colspan="7" class="text-end">TOTAL</td>
                <td class="text-end">Rp {{ number_format($totalDebit, 2, ',', '.') }}</td>
                <td class="text-end">Rp {{ number_format($totalKredit, 2, ',', '.') }}</td>
                <td class="text-end">Rp {{ number_format($saldo, 2, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
    </div>

</div>
@endsection

@push('js')
    <script>
        $(document).ready(function () {
        $('.select2').select2({
            placeholder: "-- Pilih --",
            width: '100%'
        });
        
        const initialLicenseId = $('#license_id').val();

        if (initialLicenseId) {
            $('#license_id').trigger('change');
        }

        $('#license_id').on('change', function () {
            const licenseId = $(this).val();
            $('#account_id').html('<option value="">Memuat...</option>').prop('disabled', true);

            if (licenseId) {
                $.get(`/get-accounts-by-license/${licenseId}`, function (data) {
                    let options = '<option value="">-- Semua Akun --</option>';
                    data.forEach(function (account) {
                        options += `<option value="${account.id}">[${account.account_code}] ${account.account_name}</option>`;
                    });
                    $('#account_id').html(options).prop('disabled', false);
                });
            } else {
                $('#account_id').html('<option value="">-- Semua Akun --</option>').prop('disabled', false);
            }
        });

        let table = $('#kasTable').DataTable({
            pageLength: 25,
            ordering: true,
            language: {
                lengthMenu: "Tampilkan _MENU_ data",
                zeroRecords: "Tidak ada data ditemukan",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data tersedia",
                infoFiltered: "(difilter dari total _MAX_ data)",
            }
        });

        // Pencarian manual dengan input
        $('#searchInput').on('keyup', function() {
            table.search(this.value).draw();
        });
    });

    </script>
@endpush


