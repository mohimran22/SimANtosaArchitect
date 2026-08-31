<h2 style="text-align:center; margin-bottom:20px;">
    REKAPITULASI RENCANA ANGGARAN BIAYA
</h2>

<table width="100%" style="margin-bottom:20px;">
<tr>
    <td width="20%">PEKERJAAN</td>
    <td>: {{ $rab->project->project_name ?? '-' }}</td>
</tr>
<tr>
    <td>LOKASI</td>
    <td>: {{ $rab->project->city->name ?? '-' }}</td>
</tr>
<tr>
    <td>DURASI</td>
    <td>: {{ $rab->job_duration ?? '-' }}</td>
</tr>
</table>

<table width="100%" border="1" cellpadding="6">

    <thead class="thead-dark">
        <tr>
            <th width="5%">NO</th>
            <th>URAIAN PEKERJAAN</th>
            <th width="30%">JUMLAH HARGA</th>
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

            {{-- LANTAI --}}

            <tr style="font-weight:bold; background:#e0e0e0;">

                <td></td>

                <td>
                    {{ strtoupper($floorName ?: 'Tanpa Lantai') }}
                </td>

                <td></td>

            </tr>


            @php
                $categoryGroups = $floorItems->groupBy('category_name');
            @endphp


            @foreach($categoryGroups as $categoryName => $categoryItems)

                @php
                    $categoryLetter =
                        number_to_letters($categoryIndex);

                    $subtotal =
                        $categoryItems->sum('total');
                @endphp

                <tr>

                    <td align="center"
                        style="font-weight:bold;">

                        {{ $categoryLetter }}

                    </td>

                    <td style="font-weight:bold;">

                        {{ strtoupper(
                            $categoryName ?: 'Tanpa Kategori'
                        ) }}

                    </td>

                    <td align="right">

                        Rp {{ number_format(
                            $subtotal,
                            0,
                            ',',
                            '.'
                        ) }}

                    </td>

                </tr>


                @php
                    $categoryIndex++;
                @endphp

            @endforeach

        @endforeach

    </tbody>

    <tfoot>

        <tr style="font-weight:bold;">
            <th colspan="2" align="right">
                SUBTOTAL
            </th>

            <th align="right">
                Rp {{ number_format(
                    $rab->subtotal,
                    0,
                    ',',
                    '.'
                ) }}
            </th>
        </tr>


        <tr style="font-weight:bold;">
            <th colspan="2" align="right">
                DISKON
            </th>

            <th align="right">
                Rp {{ number_format(
                    $rab->discount,
                    0,
                    ',',
                    '.'
                ) }}
            </th>
        </tr>


        <tr style="font-weight:bold;">
            <th colspan="2" align="right">
                SUBTOTAL AFTER DISKON
            </th>

            <th align="right">
                Rp {{ number_format(
                    $rab->subtotal_after_discount,
                    0,
                    ',',
                    '.'
                ) }}
            </th>
        </tr>


        <tr style="font-weight:bold;">
            <th colspan="2" align="right">
                TAX ({{ $rab->tax_rate }}%)
            </th>

            <th align="right">
                Rp {{ number_format(
                    $rab->tax_total,
                    0,
                    ',',
                    '.'
                ) }}
            </th>
        </tr>


        <tr style="font-weight:bold;">
            <th colspan="2" align="right">
                SHIPPING
            </th>

            <th align="right">
                Rp {{ number_format(
                    $rab->shipping,
                    0,
                    ',',
                    '.'
                ) }}
            </th>
        </tr>


        <tr style="font-weight:bold;">
            <th colspan="2" align="right">
                GRAND TOTAL
            </th>

            <th align="right">
                Rp {{ number_format(
                    $rab->grand_total,
                    0,
                    ',',
                    '.'
                ) }}
            </th>
        </tr>


        <tr style="font-weight:bold;">
            <th colspan="2" align="right">
                DIBULATKAN
            </th>

            <th align="right">
                Rp {{ number_format(
                    floor($rab->grand_total / 100000) * 100000,
                    0,
                    ',',
                    '.'
                ) }}
            </th>
        </tr>

    </tfoot>

</table>