<h3 style="text-align:center; margin-bottom:15px;">
    BOBOT KEMAJUAN PEKERJAAN
</h3>

<table cellspacing="0" cellpadding="5" border="1">

    <thead>

        <tr style="background:#c4c4c4; text-align:center; font-weight:bold;">

            <th rowspan="2" width="4%">
                NO
            </th>

            <th rowspan="2">
                URAIAN PEKERJAAN
            </th>

            <th colspan="2">
                TERKONTRAK
            </th>

            <th rowspan="2" width="10%">
                BOBOT
            </th>

            @foreach($weeks as $w)

                <th colspan="2">
                    Minggu ke-{{ $w['week_no'] }}
                </th>

            @endforeach

        </tr>

        <tr style="background:#d9d9d9; text-align:center; font-weight:bold;">

            <th width="5%">
                SATUAN
            </th>

            <th width="5%">
                VOL
            </th>

            @foreach($weeks as $w)

                <th width="6%">
                    VOL
                </th>

                <th width="7%">
                    BOBOT
                </th>

            @endforeach

        </tr>

    </thead>


    <tbody>

        @foreach($groupedItems as $category)

            @php

                $items = collect($category['uraians'])
                    ->flatMap(fn($u) => $u['items']);

                $subtotalBobot =
                    $items->sum('bobot_percent');

            @endphp

            <tr style="font-weight:bold; background:#c4c4c4;">

                <td align="center"> {{ \PhpOffice\PhpSpreadsheet\Cell\Coordinate ::stringFromColumnIndex($loop->iteration) }} </td>

                <td colspan="{{ $totalCols - 2 }}">
                    {{ strtoupper($category['category_name']) }}
                </td>

                <td align="right">
                    {{ number_format($subtotalBobot,2) }}%
                </td>

            </tr>

            @php $no = 1; @endphp

            @foreach($category['uraians'] as $uraian)

                <tr style="font-size:12px; font-weight:bold;">

                    <td align="center">
                        {{ $no }}
                    </td>

                    <td colspan="{{ $totalCols - 1 }}">
                        {{ $uraian['uraian_name'] }}
                    </td>

                </tr>

                @php $itemNo = 1; @endphp

                @foreach($uraian['items'] as $item)

                    <tr>

                        <td align="center">
                            {{ $no }}.{{ $itemNo }}
                        </td>

                        <td>
                            {{ $item->uraian }}
                        </td>

                        <td align="center">
                            {{ $item->satuan }}
                        </td>

                        <td align="right">
                            {{ number_format($item->volume,2,',','.') }}
                        </td>

                        <td align="right">
                            {{ number_format($item->bobot_percent,2) }}%
                        </td>

                        @foreach($weeks as $w)

                            @php
                                $prog = $item->progress_map[$w['week_no']] ?? null;
                                $volMinggu = $prog->volume ?? 0; $bobotMinggu = $item->volume > 0 ? ($volMinggu / $item->volume) * $item->bobot_percent : 0;
                            @endphp

                            <td align="right">
                                {{ $prog->volume ?? 0 }}
                            </td>

                            <td align="right">
                                {{ number_format($bobotMinggu, 2) }}%
                            </td>

                        @endforeach

                    </tr>

                    @php $itemNo++; @endphp

                @endforeach

                @php $no++; @endphp

            @endforeach

        @endforeach

    </tbody>

</table>