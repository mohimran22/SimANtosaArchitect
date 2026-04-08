@extends('tablar::page')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 style="text-align:center;">Buku Besar</h2>
            
                <a href="{{ route('ledger.export', request()->query()) }}" 
                    class="btn btn-success ">
                    <i class="ti ti-file-export"></i> Ekspor Excel
                </a>
            
    </div>
    
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('journals.ledger') }}" class="row g-3 mb-4">
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Dari Tanggal</label>
                    <input type="date" name="start_date" id="start_date" 
                        class="form-control" value="{{ $startDate }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">Sampai Tanggal</label>
                    <input type="date" name="end_date" id="end_date" 
                        class="form-control" value="{{ $endDate }}">
                </div>
                @if(auth()->user()->hasRole('Super-Admin'))
                    <div class="col-md-3">
                        <label for="license_id" class="form-label">Lisensi</label>
                        <select name="license_id" id="license_id" class="form-select select2">
                            <option value="">-- Semua Lisensi --</option>
                            @foreach ($licenses as $license)
                                <option value="{{ $license->id }}" 
                                    {{ $activeLicenseId == $license->id ? 'selected' : '' }}>
                                    {{ $license->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <input type="hidden" name="license_id" value="{{ $activeLicenseId }}">
                @endif

                <div class="col-md-3 align-self-end">
                    <button type="submit" class="btn btn-primary text-white">Filter</button>
                </div>
            </form>
        </div>
    </div>


    @foreach($ledger as $accountId => $data)
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-light">
                <strong>{{ $data['account']->account_code }} - {{ $data['account']->account_name }}</strong>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered table-striped mb-0">
                    <thead class="table-danger">
                        <tr>
                            <th width="12%">Tanggal</th>
                            <th width="15%">Transaksi</th>
                            <th>Deskripsi</th>
                            <th class="text-end" width="15%">Debit</th>
                            <th class="text-end" width="15%">Kredit</th>
                            <th class="text-end" width="15%">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $lastJournal = null;
                            $lastDate = null;
                        @endphp
                        
                        @foreach($data['rows'] as $row)
                        <tr>
                            <td>
                                @if($lastDate !== $row['transaction_date'])
                                    {{ \Carbon\Carbon::parse($row['transaction_date'])->format('d/m/Y') }}
                                    @php $lastDate = $row['transaction_date']; @endphp
                                @endif
                            </td>
                            <td>
                                @if($lastJournal !== $row['journal_code'])
                                    <a href="{{ route('journals.show', $row['journal_id']) }}" 
                                       class="text-decoration-none fw-bold text-primary" title="{{ $row['journal_code'] }}">
                                        Jurnal Umum
                                    </a>
                                    @php $lastJournal = $row['journal_code']; @endphp
                                @endif
                            </td>
                            <td>{{ $row['description'] }}</td>
                            <td class="text-end">Rp {{ number_format($row['debit'], 2, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($row['credit'], 2, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($row['balance'], 2, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
        </div>
    @endforeach
    <div class="d-flex justify-content-start gap-2 mt-3">
        <a href="{{ route('ledgerpdf', request()->query()) }}" 
                        target="_blank" 
                        class="btn btn-danger">
                        <i class="ti ti-printer"></i> Cetak
        </a>
    </div>
</div>
@endsection

@push('js')
<script>
 $('.select2').select2({
            placeholder: "-- Pilih --",
            width: '100%'
        });
</script>
@endpush
