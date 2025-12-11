@php
    $offer = $project->offer->first();
@endphp
@if(isset($offer))
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header fw-bold">
        Detail Penawaran
    </div>
    <div class="card-body">
        <div class="row g-4">
            <h4 class="fw-bold mb-4">Detail Penawaran Jasa Desain</h4>

            {{-- Informasi Utama --}}
            <div class="col-md-4">
                <label class="fw-semibold">Nomor Penawaran</label>
                <input type="text" class="form-control"
                       value="{{ $project->offer->offer_number ?? '-' }}" readonly>
            </div>

            <div class="col-md-4">
                <label class="fw-semibold">Tanggal Penawaran</label>
                <input type="text" class="form-control"
                       value="{{ optional($project->offer)->offer_date ? \Carbon\Carbon::parse($project->offer->offer_date)->format('d/m/Y') : '-' }}" readonly>
            </div>

            <div class="col-md-4">
                <label class="fw-semibold">Nama Customer</label>
                <input type="text" class="form-control"
                       value="{{ $project->offer->contact_name ?? '-' }}" readonly>
            </div>

        <div class="row mb-4 mt-4">
            <div class="col-md-4">
                <label class="fw-semibold">Paket Desain</label>
                <input type="text" class="form-control"
                       value="{{ $project->offer->package->name ?? '-' }}" readonly>
            </div>

            <div class="col-md-2">
                <label class="fw-semibold">Volume</label>
                <input type="text" class="form-control"
                       value="{{ $project->offer->volume ?? '-' }}" readonly>
            </div>

            <div class="col-md-2">
                <label class="fw-semibold">Satuan</label>
                <input type="text" class="form-control"
                       value="{{ $project->offer->satuan ?? '-' }}" readonly>
            </div>

            <div class="col-md-2">
                <label class="fw-semibold">Harga Satuan</label>
                <input type="text" class="form-control"
                       value="Rp {{ number_format($project->offer->price_meter ?? 0, 0, ',', '.') }}" readonly>
            </div>

            <div class="col-md-2">
                <label class="fw-semibold">Total Harga</label>
                <input type="text" class="form-control"
                       value="Rp {{ number_format($project->offer->total_price ?? 0, 0, ',', '.') }}" readonly>
            </div>
        </div>
            {{-- <table class="table table-sm mb-4">
                <tr>
                    <th width="200">Nomor Penawaran</th>
                    <td></td>
                </tr>
                <tr>
                    <th>Tanggal Penawaran</th>
                    <td></td>
                </tr>
                <tr>
                    <th>Nama Customer</th>
                    <td></td>
                </tr>
                <tr>
                    <th>Paket Desain</th>
                    <td></td>
                </tr>
            </table> --}}

            {{-- Harga Utama --}}
            {{-- <h5 class="fw-bold mb-2">Informasi Harga</h5>
            <table class="table table-bordered table-sm mb-4">
                <tr>
                    <th>Volume</th>
                    <td></td>
                    <th>Satuan</th>
                    <td></td>
                </tr>
                <tr>
                    <th>Harga Satuan</th>
                    <td>Rp {{ number_format($project->offer->price_meter ?? 0, 0, ',', '.') }}</td>
                    <th>Total Harga</th>
                    <td></td>
                </tr>
            </table> --}}

            {{-- Rincian Pekerjaan --}}
            <h5 class="fw-bold mb-3">Rincian Pekerjaan</h5>

            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Uraian Pekerjaan</th>
                        <th>Kategori</th>
                        <th width="120">Status</th>
                        <th>Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($project->offer->items as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->item_name }}</td>
                            <td>{{ $item->category ?? '-' }}</td>
                            <td>
                                @if($item->is_optional)
                                    <span class="badge bg-warning">Optional</span>
                                @else
                                    <span class="badge bg-success">Include</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                Tidak ada rincian pekerjaan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-end">SUBTOTAL</th>
                                            <td>Rp {{ number_format($project->offer->total_price ?? 0, 0, ',', '.') }}</td>
                    </tr>

                    <tr>
                        <th colspan="5" class="text-end">DISCOUNT</th>
                                            <td>Rp {{ number_format($project->offer->discount ?? 0, 0, ',', '.') }}</td>
                    </tr>

                    <tr>
                        <th colspan="5" class="text-end">SUBTOTAL AFTER DISCOUNT</th>
<td>
                        Rp {{ number_format(($project->offer->total_price ?? 0) - ($project->offer->discount ?? 0), 0, ',', '.') }}
                    </td>
                    </tr>

                    <tr>
                        <th colspan="5" class="text-end">TAX RATE (%)</th>
                                            <td>{{ $project->offer->tax_rate ?? 0 }}%</td>
                    </tr>

                    <tr>
                        <th colspan="5" class="text-end">TOTAL TAX</th>
                                            <td>Rp {{ number_format($project->offer->total_tax ?? 0, 0, ',', '.') }}</td>
                    </tr>

                    <tr>
                        <th colspan="5" class="text-end">SHIPPING / HANDLING</th>
                                            <td>Rp {{ number_format($project->offer->shipping ?? 0, 0, ',', '.') }}</td>
                    </tr>

                    <tr>
                        <th colspan="5" class="text-end">GRAND TOTAL</th>
                    <td class="fw-bold">
                        Rp {{ number_format($project->offer->grand_total ?? 0, 0, ',', '.') }}
                    </td>
                    </tr>
                </tfoot>
            </table>

            {{-- Perhitungan Harga --}}
            <h5 class="fw-bold mt-4 mb-2">Ringkasan Harga</h5>

            <table class="table table-end table-bordered table-sm w-50">
                <tr>
                    <th>Subtotal</th>

                </tr>
                <tr>
                    <th>Discount</th>

                </tr>

                <tr>
                    <th>Subtotal - Discount</th>
                    
                </tr>

                <tr>
                    <th>Tax Rate</th>

                </tr>

                <tr>
                    <th>Total Tax</th>

                </tr>

                <tr>
                    <th>Shipping / Handling</th>

                </tr>

                <tr class="table-dark">
                    <th>GRAND TOTAL</th>
                    
                </tr>
            </table>
        </div>

        {{-- Notes --}}
        @if($project->offer->notes)
        <div class="mt-4">
            <h5 class="fw-bold">Keterangan</h5>
            <div class="border p-3">{{ $project->offer->notes }}</div>
        </div>
        @endif

        {{-- Tombol --}}
        <div class="mt-4">
            <a href="{{ route('offers.show', $project->offer->id) }}" class="btn btn-dark">
                <i class="ti ti-eye"></i> Lihat Detail Lengkap
            </a>

            <a href="{{ route('offers.pdf', $project->offer->id) }}" class="btn btn-danger">
                <i class="ti ti-file"></i> Download PDF
            </a>
        </div>

    </div>
</div>
@endif
