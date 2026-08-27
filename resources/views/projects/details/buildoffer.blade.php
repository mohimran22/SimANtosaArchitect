@php
$offer = $project->offer;
$rab = $offer?->rab;
function numberToLetters($num) {
    $letters = '';
    $num = $num + 1;

    while ($num > 0) {
        $rem = ($num - 1) % 26;
        $letters = chr(65 + $rem) . $letters;
        $num = intdiv(($num - 1), 26);
    }

    return $letters;
}
@endphp

@can('lihat data proyek')
@if($offer)
<div class="card shadow-sm border-0 mb-4">
    {{-- <div class="card-header fw-bold">Detail Penawaran</div> --}}

    <div class="card-body">

        <h2 class="fw-bold mb-4">{{ $offer->project->project_name }}</h2>

        <div class="row g-4">

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
        </div>

        <h5 class="fw-bold mt-5 mb-3">Rincian Pekerjaan</h5>
        <div class="table-responsive">
        <table class="table table-bordered align-middle">

            <thead>
                <tr>
                    <th width="50">NO</th>
                    <th>URAIAN PEKERJAAN</th>
                    <th>SAT</th>
                    <th>VOL</th>
                    <th>HARGA SATUAN</th>
                    <th>JUMLAH HARGA</th>
                </tr>
            </thead>

            <tbody>

                @php
                    $items = $rab->items
                        ->sortBy('order_no')
                        ->values();

                    $floorGroups = $items->groupBy('floor_name');

                    $categoryIndex = 0;
                @endphp


                @foreach($floorGroups as $floorName => $floorItems)

                    <tr style="font-weight:bold; background:#c4c4c4;">

                        <td colspan="6">
                            {{ strtoupper($floorName ?: 'Tanpa Lantai') }}
                        </td>

                    </tr>


                    @php
                        $categoryGroups = $floorItems->groupBy('category_name');
                    @endphp


                    @foreach($categoryGroups as $categoryName => $categoryItems)

                        @php

                            $categoryLetter = numberToLetters($categoryIndex);

                            $categoryTotal = $categoryItems->sum('total');

                            $itemNo = 1;

                            $lastDescription = null;

                        @endphp

                        <tr class="table-secondary">

                            <th>
                                {{ $categoryLetter }}
                            </th>

                            <th colspan="4">

                                {{ strtoupper(
                                    $categoryName ?: 'Tanpa Kategori'
                                ) }}

                            </th>

                            <th class="text-end">

                                Rp
                                {{ number_format(
                                    $categoryTotal,
                                    0,
                                    ',',
                                    '.'
                                ) }}

                            </th>

                        </tr>

                        @foreach($categoryItems as $item)

                            @php

                                $description = trim((string) $item->description);

                                $showNumber = false;

                                if ($description === '') {

                                    $showNumber = true;

                                } elseif (
                                    $description !== $lastDescription
                                ) {

                                    $showNumber = true;

                                }


                                $currentNo = $itemNo;


                                if ($showNumber) {
                                    $itemNo++;
                                }


                                $lastDescription =
                                    $description;

                            @endphp


                            <tr>

                                <td class="text-center">

                                    @if($showNumber)

                                        {{ $currentNo }}

                                    @endif

                                </td>

                                <td>

                                    {{ $item->job_name }}

                                    @if($description !== '')

                                        <br>

                                        <span style="
                                            font-size:11px;
                                            color:#666;
                                        ">

                                            {{ $description }}

                                        </span>

                                    @endif

                                </td>


                                {{-- SAT --}}

                                <td class="text-center">

                                    {{ $item->satuan }}

                                </td>

                                <td class="text-end">

                                    {{ rtrim(
                                        rtrim(
                                            number_format(
                                                $item->volume,
                                                5,
                                                ',',
                                                '.'
                                            ),
                                            '0'
                                        ),
                                        ','
                                    ) }}

                                </td>

                                <td class="text-end">

                                    Rp
                                    {{ number_format(
                                        $item->price,
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </td>


                                {{-- JUMLAH HARGA --}}

                                <td class="text-end">

                                    Rp
                                    {{ number_format(
                                        $item->total,
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </td>

                            </tr>

                        @endforeach


                        @php
                            $categoryIndex++;
                        @endphp

                    @endforeach

                @endforeach

            </tbody>

            @php

                $subtotal =
                    $rab->items->sum('total');

                $discount =
                    $offer->discount ?? 0;

                $subtotalAfterDiscount =
                    $subtotal - $discount;

                $taxRate =
                    $offer->tax_rate ?? 0;

                $totalTax =
                    $subtotalAfterDiscount *
                    ($taxRate / 100);

                $shipping =
                    $offer->shipping ?? 0;

                $grandTotal =
                    $subtotalAfterDiscount +
                    $totalTax +
                    $shipping;

                $roundedTotal =
                    floor(
                        $grandTotal / 1000000
                    ) * 1000000;

                $extraDiscount = $offer->extra_discount ?? 0;

                $grandTotalOffer =
                    max(
                        0,
                        $roundedTotal - $extraDiscount
                    );

            @endphp


            <tfoot>

                <tr>

                    <th colspan="5" class="text-end">
                        SUBTOTAL
                    </th>

                    <th class="text-end">

                        Rp
                        {{ number_format(
                            $subtotal,
                            0,
                            ',',
                            '.'
                        ) }}

                    </th>

                </tr>

                <tr>

                    <th colspan="5" class="text-end">
                        DISCOUNT
                    </th>

                    <th class="text-end">

                        Rp
                        {{ number_format(
                            $discount,
                            0,
                            ',',
                            '.'
                        ) }}

                    </th>

                </tr>

                <tr>

                    <th colspan="5" class="text-end">
                        SUBTOTAL AFTER DISCOUNT
                    </th>

                    <th class="text-end">

                        Rp
                        {{ number_format(
                            $subtotalAfterDiscount,
                            0,
                            ',',
                            '.'
                        ) }}

                    </th>

                </tr>

                <tr>

                    <th colspan="5" class="text-end">
                        TAX RATE (%)
                    </th>

                    <th class="text-end">

                        {{ $taxRate }}%

                    </th>

                </tr>

                <tr>

                    <th colspan="5" class="text-end">
                        TOTAL TAX
                    </th>

                    <th class="text-end">

                        Rp
                        {{ number_format(
                            $totalTax,
                            0,
                            ',',
                            '.'
                        ) }}

                    </th>

                </tr>

                <tr>

                    <th colspan="5" class="text-end">
                        SHIPPING / HANDLING
                    </th>

                    <th class="text-end">

                        Rp
                        {{ number_format(
                            $shipping,
                            0,
                            ',',
                            '.'
                        ) }}

                    </th>

                </tr>

                <tr>

                    <th colspan="5" class="text-end">
                        GRAND TOTAL
                    </th>

                    <th class="text-end fw-bold">

                        Rp
                        {{ number_format(
                            $grandTotal,
                            0,
                            ',',
                            '.'
                        ) }}

                    </th>

                </tr>

                <tr>

                    <th colspan="5" class="text-end fw-bold">
                        DIBULATKAN
                    </th>

                    <th class="text-end fw-bold">

                        Rp
                        {{ number_format(
                            $roundedTotal,
                            0,
                            ',',
                            '.'
                        ) }}

                    </th>

                </tr>

                <tr>

                    <th colspan="5" class="text-end">
                        EXTRA DISCOUNT
                    </th>

                    <th class="text-end">

                        Rp
                        {{ number_format(
                            $extraDiscount,
                            0,
                            ',',
                            '.'
                        ) }}

                    </th>

                </tr>

                <tr>

                    <th colspan="5" class="text-end fw-bold">
                        GRAND TOTAL PENAWARAN
                    </th>

                    <th class="text-end fw-bold">

                        Rp
                        {{ number_format(
                            $grandTotalOffer,
                            0,
                            ',',
                            '.'
                        ) }}

                    </th>

                </tr>

            </tfoot>

        </table>
        </div>
        @if($offer->notes)
        <div class="mt-4">
            <h5 class="fw-bold">Keterangan</h5>
            <div class="border p-3">{{ $offer->notes }}</div>
        </div>
        @endif
        <div class="d-flex align-items-center mt-2">
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