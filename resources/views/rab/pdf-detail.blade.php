<h3 style="text-align:center; margin-bottom:15px;">
    RINCIAN RENCANA ANGGARAN BIAYA
</h3>

<table width="100%" cellspacing="0" cellpadding="6" border="1">
<thead style="background:#c4c4c4; font-weight:bold; text-align:center;">
<tr>
    <th width="4%">NO</th>
    <th>URAIAN PEKERJAAN</th>
    <th width="6%">SAT</th>
    <th width="8%">VOL</th>
    <th width="15%">HARGA SATUAN</th>
    <th width="17%">JUMLAH HARGA</th>
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
            $categoryLetter =
                number_to_letters($categoryIndex);

            $categorySubtotal =
                $categoryItems->sum('total');

            // RESET ITEM SETIAP KATEGORI
            $itemNo = 1;
        @endphp

        <tr style="font-weight:bold; background:#c4c4c4;">

            <td align="center">
                {{ $categoryLetter }}
            </td>

            <td colspan="4">
                {{ strtoupper(
                    $categoryName ?: 'Tanpa Kategori'
                ) }}
            </td>

            <td align="right">
                Rp {{ number_format(
                    $categorySubtotal,
                    0,
                    ',',
                    '.'
                ) }}
            </td>

        </tr>
        @php
            $itemNo = 1;
            $lastDescription = null;
        @endphp

        @foreach($categoryItems as $item)

            @php
                $description = trim((string) $item->description);

                // Tampilkan nomor hanya jika:
                // 1. description kosong
                // 2. atau description berbeda dari baris sebelumnya
                $showNumber = false;

                if ($description === '') {
                    $showNumber = true;
                } elseif ($description !== $lastDescription) {
                    $showNumber = true;
                }

                $currentNo = $itemNo;

                if ($showNumber) {
                    $itemNo++;
                }

                $lastDescription = $description;
            @endphp

            <tr>
                <td align="center">
                    @if($showNumber)
                        {{ $currentNo }}
                    @endif
                </td>

                <td>
                    {{ $item->job_name }}

                    @if(!empty($item->description))
                        <br>
                        <span style="font-size:11px; color:#666;">
                            {{ $item->description }}
                        </span>
                    @endif
                </td>

                <td align="center">
                    {{ $item->satuan }}
                </td>

                <td align="right">
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

                <td align="right">
                    Rp {{ number_format(
                        $item->price,
                        0,
                        ',',
                        '.'
                    ) }}
                </td>

                <td align="right">
                    Rp {{ number_format(
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
<tfoot>
<tr>
    <th colspan="5" align="right">SUBTOTAL</th>
    <th align="right">{{ number_format($rab->subtotal,0,',','.') }}</th>
</tr>
<tr>
    <th colspan="5" align="right">DISCOUNT</th>
    <th align="right">{{ number_format($rab->discount,0,',','.') }}</th>
</tr>
<tr>
    <th colspan="5" align="right">SUBTOTAL AFTER DISCOUNT</th>
    <th align="right">{{ number_format($rab->subtotal_after_discount,0,',','.') }}</th>
</tr>
<tr>
    <th colspan="5" align="right">TAX ({{ $rab->tax_rate }}%)</th>
    <th align="right">{{ number_format($rab->tax_total,0,',','.') }}</th>
</tr>
<tr>
    <th colspan="5" align="right">SHIPPING</th>
    <th align="right">{{ number_format($rab->shipping,0,',','.') }}</th>
</tr>
<tr style="font-weight:bold;">
    <th colspan="5" align="right">GRAND TOTAL</th>
    <th align="right">{{ number_format($rab->grand_total,0,',','.') }}</th>
</tr>
<tr style="font-weight:bold;">
    <th colspan="5" align="right">DIBULATKAN</th>
    <th align="right">
        Rp {{ number_format(floor($rab->grand_total / 100000) * 100000,0,',','.') }}
    </th>
</tr>
</tfoot>

</table>
        {{-- @php
            $descriptionNumbers = [];
            $itemNo = 1;
        @endphp

        @foreach($categoryItems as $item)

            @php
                $description = trim((string) $item->description);

                if ($description === '') {
                    $currentNo = $itemNo;
                    $itemNo++;
                } else {
                    if (!isset($descriptionNumbers[$description])) {
                        $descriptionNumbers[$description] = $itemNo;
                        $itemNo++;
                    }

                    $currentNo = $descriptionNumbers[$description];
                }
            @endphp

            <tr>
                <td align="center">
                    {{ $currentNo }}
                </td>

                <td>
                    {{ $item->job_name }}

                    @if(!empty($item->description))
                        <br>
                        <span style="font-size:11px; color:#666;">
                            {{ $item->description }}
                        </span>
                    @endif
                </td>

                <td align="center">
                    {{ $item->satuan }}
                </td>

                <td align="right">
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

                <td align="right">
                    Rp {{ number_format(
                        $item->price,
                        0,
                        ',',
                        '.'
                    ) }}
                </td>

                <td align="right">
                    Rp {{ number_format(
                        $item->total,
                        0,
                        ',',
                        '.'
                    ) }}
                </td>
            </tr>

        @endforeach --}}