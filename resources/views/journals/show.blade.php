@extends('tablar::page')

@section('content')
<div class="page-header d-print-none mb-2">
    <div class="container-xl">
        <div class="row align-items-center">
            <div class="col d-flex align-items-center">
                    <h2>Jurnal</h2>

                    <a href="{{ route('journals.export', $journal->id) }}" class="btn btn-dark">
                        <i class="ti ti-file-export"></i> Ekspor
                    </a>
                
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
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <label class="form-label fw-bold">No. Transaksi</label>
                                <div>{{ $journal->journal_code }}</div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Tanggal Transaksi</label>
                                <div>{{ \Carbon\Carbon::parse($journal->transaction_date)->format('d/m/Y') }}</div>
                            </div>
                            {{-- <div class="col-md-3">
                                <label class="form-label fw-bold">Lisensi</label>
                                <div>{{ $journal->license->name ?? '-' }}</div>
                            </div> --}}
                        </div>

                        {{-- Tabel --}}
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>No. Akun</th>
                                        <th>Nama Akun</th>
                                        <th>Deskripsi</th>
                                        <th>User</th>
                                        <th>Debit</th>
                                        <th>Kredit</th>
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
                                            <td>{{ $detail->account->account_code ?? '-' }}</td>
                                            <td>{{ $detail->account->account_name ?? '-' }}</td>
                                            <td>{{ $detail->description ?? '-' }}</td>
                                            <td>{{ $detail->person_name ?? '-' }}</td>
                                            <td class="text-end">Rp. {{ number_format($detail->debit, 2, ',', '.') }}</td>
                                            <td class="text-end">Rp. {{ number_format($detail->credit, 2, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="fw-bold">
                                    <tr>
                                        <td colspan="4">Total</td>
                                        <td class="text-end">Rp. {{ number_format($totalDebit, 2, ',', '.') }}</td>
                                        <td class="text-end">Rp. {{ number_format($totalCredit, 2, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Keterangan</label>
                                <div>{{ $journal->description}}</div>
                            </div>
                            <div class="col-md-3">
                                @if ($journal->enclosure)
                                    <div class="card mt-4">
                                        <div class="card-header fw-bold">
                                            Lampiran Jurnal
                                        </div>
                                        <div class="card-body">
                                            @php
                                                $ext = pathinfo($journal->enclosure, PATHINFO_EXTENSION);
                                            @endphp

                                            @if(in_array(strtolower($ext), ['jpg','jpeg','png','gif']))
                                                <img src="{{ asset('storage/' . $journal->enclosure) }}" 
                                                    alt="Lampiran" 
                                                    class="img-fluid rounded shadow-sm">
                                            @elseif(strtolower($ext) === 'pdf')
                                                <embed src="{{ asset('storage/' . $journal->enclosure) }}" 
                                                    type="application/pdf" 
                                                    width="100%" 
                                                    height="500px" 
                                                    class="border rounded" />
                                                <div class="mt-2">
                                                    <a href="{{ asset('storage/' . $journal->enclosure) }}" target="_blank" class="btn btn-sm btn-dark">
                                                        Buka PDF
                                                    </a>
                                                </div>
                                            @else
                                                <a href="{{ asset('storage/' . $journal->enclosure) }}" target="_blank" class="btn btn-sm btn-secondary">
                                                    Download Lampiran
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Tombol --}}
                        <div class="d-flex justify-content-start gap-2 mt-3">
                            <a href="{{ route('journals.edit', $journal->id) }}" class="btn btn-dark">
                                <i class="ti ti-pencil"></i> Ubah
                            </a>
                            
                            <a href="{{ route('journals.print', $journal->id) }}" target="_blank" class="btn btn-dark">
                                <i class="ti ti-printer"></i> Cetak
                            </a>
                        </div>

                        {{-- Footer --}}
                        <div class="text-end text-muted small mt-3" >
                            Terakhir diubah oleh <strong>{{ $journal->creator->fullname ?? 'Sistem' }}</strong> 
                            pada {{ \Carbon\Carbon::parse($journal->updated_at)->translatedFormat('d F Y H:i') }} GMT+7
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection