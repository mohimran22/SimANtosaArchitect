@extends('tablar::page')

@section('content')
<div class="container-fluid mt-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-3">Neraca</h3>
            <a href="{{ route('trial.export', [
                    'start_date' => request('start_date'),
                    'end_date' => request('end_date'),
                    'license_id' => request('license_id')
                ]) }}" 
                class="btn btn-success" target="_blank">
                <i class="ti ti-file-export text-white"></i>Ekspor Excel
            </a> 
    </div>

    {{-- 🔹 Filter --}}
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-center">
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Dari Tanggal</label>
                    <input type="date" name="start_date" id="start_date" class="form-control"
                        value="{{ $startDate }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">Sampai Tanggal</label>
                    <input type="date" name="end_date" id="end_date" class="form-control"
                        value="{{ $endDate }}">
                </div>
                @if(auth()->user()->hasRole('Super-Admin'))
                    <div class="col-md-3">
                        <label for="license_id" class="form-label">Lisensi</label>
                        <select name="license_id" class="form-select select2">
                            <option value="">-- Semua Lisensi --</option>
                            @foreach($licenses as $license)
                                <option value="{{ $license->id }}" 
                                    {{ request('license_id') == $license->id ? 'selected' : '' }}>
                                    {{ $license->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <input type="hidden" name="license_id" value="{{ $activeLicenseId }}">
                @endif

                <div class="col-md-2">
                    <label for="view" class="form-label">Tampilan</label>
                    <select name="view" id="view" class="form-select">
                        <option value="default" {{ $viewType == 'default' ? 'selected' : '' }}>Default</option>
                        <option value="skontro" {{ $viewType == 'skontro' ? 'selected' : '' }}>Skontro</option>
                    </select>
                </div>
                
                <div class="col-md-3 align-self-end">
                    <button type="submit" class="btn btn-primary text-white">
                        <i class="ti ti-filter"></i> Filter
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- 🔹 Table --}}
    <div class="card shadow-sm border-0">
        <div class="card-body table-responsive">

            <div class="row">
                
                <div class="col-md-6">
                    <div class="card shadow-sm mb-3">
                        <div class="card-header bg-primary text-white">
                            <strong>AKTIVA</strong>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <tbody>
                                    <tr>
                                        <td>Aset Lancar</td>
                                        <td class="text-end">Rp {{ number_format($asetLancar, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Persediaan Barang</td>
                                        <td class="text-end">Rp {{ number_format($persediaan, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Piutang</td>
                                        <td class="text-end">Rp {{ number_format($piutang, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Dana Belum Disetor</td>
                                        <td class="text-end">Rp {{ number_format($dana, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Pajak Bayar Dimuka</td>
                                        <td class="text-end">Rp {{ number_format($pajak, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Aset Tetap</td>
                                        <td class="text-end">Rp {{ number_format($asetTetap, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Akumulasi Penyusutan</td>
                                        <td class="text-end text-danger">Rp ({{ number_format($penyusutan, 2, ',', '.') }})</td>
                                    </tr>
                                    <tr>
                                        <td>Beban</td>
                                        <td class="text-end">Rp {{ number_format($beban, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr class="fw-bold table-secondary">
                                        <td>Total Aktiva</td>
                                        <td class="text-end">Rp {{ number_format($totalAktiva, 2, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm mb-3">
                        <div class="card-header bg-success text-white">
                            <strong>PASSIVA</strong>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <tbody>
                                    <tr>
                                        <td>Kewajiban</td>
                                        <td class="text-end">Rp {{ number_format($kewajiban, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Ekuitas</td>
                                        <td class="text-end">Rp {{ number_format($ekuitas, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Pendapatan</td>
                                        <td class="text-end">Rp {{ number_format($pendapatan, 2, ',', '.') }}</td>
                                    </tr>
                                    
                                    <tr class="fw-bold table-secondary">
                                        <td>Total Passiva</td>
                                        <td class="text-end">Rp {{ number_format($totalPassiva, 0, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

                <div class="d-flex justify-content-start gap-2 mt-3">
                    <a href="{{ route('journals.trial.pdf', [
                            'start_date' => request('start_date'),
                            'end_date' => request('end_date'),
                            'license_id' => request('license_id')
                        ]) }}" 
                        class="btn btn-danger" target="_blank">
                        <i class="ti ti-printer"></i>Cetak
                    </a>
                </div>
        </div>
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