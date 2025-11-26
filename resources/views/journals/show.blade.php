@extends('tablar::page')

@section('content')
<div class="container">
    <h1>Detail Jurnal</h1>

    <div class="card mb-4">
        <div class="card-body">
            <h5>Kode Jurnal: {{ $journal->journal_code }}</h5>
            <p>Nama Lisensi: {{ $journal->license->name ?? '-' }}</p>
            <p>Tanggal Transaksi: {{ $journal->transaction_date }}</p>
            <p>Deskripsi Umum: {{ $journal->description }}</p>          
            <p>Dibuat Oleh: {{ $journal->creator->name ?? '-' }}</p>
        </div>
    </div>

    <h4>Detail Akun</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Akun</th>
                <th>Debit</th>
                <th>Kredit</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalDebit = 0;
                $totalCredit = 0;
            @endphp

            @foreach($journal->details as $detail)
                @php
                    $totalDebit += $detail->debit;
                    $totalCredit += $detail->credit;
                @endphp
                <tr>
                    <td>{{ $detail->account->account_name ?? '-' }}</td>
                    <td>{{ number_format($detail->debit, 2) }}</td>
                    <td>{{ number_format($detail->credit, 2) }}</td>
                    <td>{{ $detail->description ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr>
                <th>Total</th>
                <th>{{ number_format($totalDebit, 2) }}</th>
                <th>{{ number_format($totalCredit, 2) }}</th>
                <th></th>
            </tr>
        </tfoot>
    </table>

    <a href="{{ route('journals.index') }}" class="btn btn-secondary">Kembali</a>
    <a href="{{ route('journals.edit', $journal->id) }}" class="btn btn-primary">Edit Jurnal</a>
</div>
@endsection
