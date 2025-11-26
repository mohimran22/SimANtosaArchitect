@extends('tablar::page')

@section('content')
<div class="container">
    <h3 class="text-center">LAPORAN KAS</h3>

   {{-- Filter --}}
    <form action="{{ route('journals.report') }}" method="GET" class="card p-4 mb-4">
        <div class="row">
            @if (auth()->user()->hasRole('Super-Admin') || auth()->user()->hasRole('Pemilik Lisensi'))
                <div class="col-md-4">
                    <label for="license_id" class="form-label">Lisensi</label>
                    <select name="license_id" id="license_id" class="form-select">
                        <option value="">-- Semua Lisensi --</option>
                        @foreach ($licenses as $license)
                            <option value="{{ $license->id }}" {{ request('license_id') == $license->id ? 'selected' : '' }}>
                                {{ $license->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="col-md-4">
                <label for="account_id" class="form-label">Akun</label>
                <select name="account_id" class="form-select">
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
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('journals.report') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    


    <div class="table-responsive">
        <table class="table table-bordered table-striped">
        <thead class="text-center">
            <tr>
                <th>NO</th>
                <th>TANGGAL</th>
                <th>RINCIAN</th>
                <th>KODE AKUN</th>
                <th>AKUN</th>
                <th>PIC</th>
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
                        <td>{{ $journal->description }}</td>
                        <td>{{ $detail->account->account_code }}</td>
                        <td>{{ $detail->account->account_name }}</td>
                        <td>{{ $journal->creator->name ?? '-' }}</td>
                        <td class="text-end">{{ number_format($debit, 0, ',', '.') }}</td>
                        <td class="text-end">{{ number_format($kredit, 0, ',', '.') }}</td>
                        <td class="text-end">{{ number_format($saldo, 0, ',', '.') }}</td>
                        <td>{{ $detail->description ?? '-' }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
        <tfoot>
            <tr class="fw-bold">
                <td colspan="6" class="text-center">TOTAL</td>
                <td class="text-end">{{ number_format($totalDebit, 0, ',', '.') }}</td>
                <td class="text-end">{{ number_format($totalKredit, 0, ',', '.') }}</td>
                <td class="text-end">{{ number_format($saldo, 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
    </div>

</div>
@endsection
