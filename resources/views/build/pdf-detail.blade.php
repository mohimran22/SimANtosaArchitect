<h3 style="text-align:center; margin-bottom:15px;">
    BOBOT KEMAJUAN PEKERJAAN
</h3>

<table width="100%" cellspacing="0" cellpadding="5" border="1">

<thead style="background:#c4c4c4; font-weight:bold; text-align:center;">

<tr>

    <th width="4%">NO</th>

    <th>URAIAN PEKERJAAN</th>

    <th width="6%">SAT</th>

    <th width="8%">VOL</th>

    <th width="12%">BOBOT</th>

    @foreach($weeks as $w)

        <th width="10%">
            M{{ $w['week_no'] }}
            <br>
            VOL
        </th>

        <th width="10%">
            M{{ $w['week_no'] }}
            <br>
            BOBOT
        </th>

    @endforeach

</tr>

</thead>

<tbody>

@php $noGroup = 'A'; @endphp

@foreach($groupedItems as $category)

    @php

        $items = collect($category['uraians'])
            ->flatMap(fn($u) => $u['items']);

        $subtotalBobot =
            $items->sum('bobot_percent');

    @endphp

    <tr style="font-weight:bold; background:#c4c4c4;">

        <td align="center">
            {{ $noGroup }}
        </td>

        <td colspan="{{ 4 + ($weeks->count() * 2) }}">
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

            <td colspan="{{ 5 + ($weeks->count() * 2) }}">
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
                        $prog =
                            $item->progress_map[$w['week_no']]
                            ?? null;
                    @endphp

                    <td align="right">
                        {{ $prog->volume ?? 0 }}
                    </td>

                    <td align="right">
                        {{ number_format(
                            $prog->bobot_percent ?? 0,
                            2
                        ) }}%
                    </td>

                @endforeach

            </tr>

            @php $itemNo++; @endphp

        @endforeach

        @php $no++; @endphp

    @endforeach

    @php $noGroup++; @endphp

@endforeach

</tbody>

</table>