@php
$offer = $project->offer;
$items = $offer?->items ?? collect();

$grouped = [];

foreach ($items as $item) {

    $category = $item->category_name ?? 'Tanpa Kategori';
    $uraian   = $item->uraian_name ?? 'Tanpa Uraian';

    if (!isset($grouped[$category])) {
        $grouped[$category] = [
            'items' => [],
            'subtotal' => 0
        ];
    }

    if (!isset($grouped[$category]['items'][$uraian])) {
        $grouped[$category]['items'][$uraian] = [
            'items' => [],
            'subtotal' => 0
        ];
    }

    $grouped[$category]['items'][$uraian]['items'][] = $item;

    $grouped[$category]['items'][$uraian]['subtotal'] += $item->total;
    $grouped[$category]['subtotal'] += $item->total;
}
@endphp

@can('lihat data proyek')
@if($offer)
<div class="card shadow-sm border-0 mb-4">
    {{-- <div class="card-header fw-bold">Detail Penawaran</div> --}}

    <div class="card-body">

        <h2 class="fw-bold mb-4">{{ $offer->project->project_name }}</h2>

        <div class="row g-4">

            {{-- Informasi utama --}}
            <div class="col-md-4">
                <label class="fw-semibold">Nomor Penawaran</label>
                <input type="text" class="form-control" readonly
                       value="{{ $offer->offer_number }}">
            </div>

            <div class="col-md-4">
                <label class="fw-semibold">Tanggal Penawaran</label>
                <input type="text" class="form-control" readonly
                       value="{{ \Carbon\Carbon::parse($offer->offer_date)->format('d/m/Y') }}">
            </div>

            <div class="col-md-4">
                <label class="fw-semibold">Nama Customer</label>
                <input type="text" class="form-control" readonly
                       value="{{ $offer->contact_name }}">
            </div>

        </div>

        <div class="row mt-4 g-4">

            <div class="col-md-4">
                <label class="fw-semibold">Pilihan RAB</label>
                <input type="text" class="form-control" readonly
                       value="{{ $project->project_name ?? '-' }}">
            </div>

            {{-- <div class="col-md-2">
                <label class="fw-semibold">Volume</label>
                <input type="text" class="form-control" readonly value="{{ $offer->volume }}">
            </div>

            <div class="col-md-2">
                <label class="fw-semibold">Satuan</label>
                <input type="text" class="form-control" readonly value="{{ $offer->satuan }}">
            </div>

            <div class="col-md-2">
                <label class="fw-semibold">Harga Satuan</label>
                <input type="text" class="form-control" disabled
                       value="Rp {{ number_format($offer->price_meter, 0, ',', '.') }}">
            </div>

            <div class="col-md-2">
                <label class="fw-semibold">Total Harga</label>
                <input type="text" class="form-control" disabled
                       value="Rp {{ number_format($offer->total_price, 0, ',', '.') }}">
            </div> --}}
        </div>

        <h5 class="fw-bold mt-5 mb-3">Rincian Pekerjaan</h5>

        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th width="50">NO</th>
                    <th>URAIAN PEKERJAAN</th>
                    <th>SAT</th>
                    <th>VOL</th>
                    <th>HARGA SATUAN</th>
                    <th>JUMAH HARGA</th>
                </tr>
            </thead>

                <tbody>
                    @foreach($grouped as $category => $data)
                      @php
                            $categoryLetter = chr(65 + $loop->index);
                            $uraianNo = 1;
                            $categoryTotal = collect($data['items'])
                                ->flatMap(fn($u) => $u['items'])
                                ->sum('total');
                        @endphp
                        <tr class="table-secondary">
                            <th>{{ $categoryLetter }}</th>
                            <th colspan="4">{{ $category }}</th>
                            <th class="text-end">
                                Rp {{ number_format($categoryTotal,0,',','.') }}
                            </th>
                        </tr>
                        @foreach($data['items'] as $uraian => $uraianData)

                            <tr class="fw-bold">
                                <td>{{ $uraianNo }}</td>
                                <td colspan="5">{{ $uraian }}</td>
                            </tr>
                                @php $itemNo = 1; @endphp
                            @foreach($uraianData['items'] as $item)
                                <tr>
                                    <td>{{ $uraianNo.'.'.$itemNo }}</td>
                                    <td>{{ $item->item_name }}</td>
                                    <td>{{ $item->satuan }}</td>
                                    <td>{{ $item->volume }}</td>
                                    <td>Rp {{ number_format($item->price,0,',','.') }}</td>
                                    <td class="text-end">
                                        Rp {{ number_format($item->total,0,',','.') }}
                                    </td>
                                </tr>
                                @php $itemNo++; @endphp
                            @endforeach
                            @php $uraianNo++; @endphp
                        @endforeach    
                    @endforeach

                </tbody>

            <tfoot>
                <tr>
                    <th colspan="5" class="text-end">SUBTOTAL</th>
                    <th>Rp {{ number_format($offer->subtotal, 0, ',', '.') }}</th>
                </tr>

                <tr>
                    <th colspan="5" class="text-end">DISCOUNT</th>
                    <th>Rp {{ number_format($offer->discount, 0, ',', '.') }}</th>
                </tr>

                <tr>
                    <th colspan="5" class="text-end">SUBTOTAL AFTER DISCOUNT</th>
                    <th>
                        Rp {{ number_format($offer->subtotal_after_discount, 0, ',', '.') }}
                    </th>
                </tr>

                <tr>
                    <th colspan="5" class="text-end">TAX RATE (%)</th>
                    <th>{{ $offer->tax_rate }}%</th>
                </tr>

                <tr>
                    <th colspan="5" class="text-end">TOTAL TAX</th>
                    <th>Rp {{ number_format($offer->total_tax, 0, ',', '.') }}</th>
                </tr>

                <tr>
                    <th colspan="5" class="text-end">SHIPPING / HANDLING</th>
                    <th>Rp {{ number_format($offer->shipping, 0, ',', '.') }}</th>
                </tr>

                <tr>
                    <th colspan="5" class="text-end">GRAND TOTAL</th>
                    <th class="fw-bold">
                        Rp {{ number_format($offer->grand_total, 0, ',', '.') }}
                    </th>
                </tr>
            </tfoot>
        </table>

        {{-- Notes --}}
        @if($offer->notes)
        <div class="mt-4">
            <h5 class="fw-bold">Keterangan</h5>
            <div class="border p-3">{{ $offer->notes }}</div>
        </div>
        @endif
        <div class="d-flex align-items-center gap-2">
            @if($project->offer?->id)
                @if($project->project_type == 3)
                <a href="{{ route('projects.offers.build.pdf', $project->id) }}"
                    class="btn btn-dark"
                    target="_blank"
                    title="Download PDF">
                        <i class="ti ti-file-text"></i>Download PDF
                </a>
                @endif
            @endif
        </div>
    </div>
</div>
@endif
@endcan