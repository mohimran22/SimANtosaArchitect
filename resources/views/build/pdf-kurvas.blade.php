<div style="position:relative">
    <table>
        <thead>
            <tr style="background:#d9d9d9; text-align:center; font-weight:bold;">
                <th style="width:12mm">NO</th>
                <th style="width:80mm">URAIAN PEKERJAAN</th>
                <th style="width:18mm">BOBOT</th>
                @foreach($weeks as $w)
                    <th style="width:6mm">
                        M{{ $w['week_no'] }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($groupedItems as $category)

                @php

                    $items = collect($category['uraians'])
                        ->flatMap(fn($u) => $u['items']);

                    $subtotalBobot = $items->sum('bobot_percent');

                @endphp
                <tr style="height:8mm;font-weight:bold;">
                    <td align="center">
                    {{ \PhpOffice\PhpSpreadsheet\Cell\Coordinate
                        ::stringFromColumnIndex($loop->iteration) }}
                    </td>

                    <td>
                        {{ strtoupper($category['category_name']) }}
                    </td>

                    <td align="right">
                        {{ number_format($subtotalBobot,2) }}%
                    </td>

                    @foreach($weeks as $w)

                        @php

                            $categoryId =
                                collect($category['uraians'])
                                ->flatMap(fn($u) => $u['items'])
                                ->first()
                                ?->category_order;

                            $nilai =
                                collect($planMap[$categoryId] ?? [])
                                    ->where('week_no', $w['week_no'])
                                    ->sum('plan_percent');

                        @endphp

                        <td align="center">

                            @if($nilai)

                                {{ number_format($nilai, 2) }}%

                            @endif
                        </td>                   
                    @endforeach
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="font-weight:bold; background:#f5f5f5;">
                <td colspan="3">
                    RENCANA KUMULATIF
                </td>
                @foreach($plan as $nilai)
                    <td align="center">
                        {{ number_format($nilai,2) }}
                    </td>
                @endforeach
            </tr>
            <tr style="font-weight:bold; background:#f5f5f5;">
                <td colspan="3">
                    REALISASI KUMULATIF
                </td>

                @foreach($realisasi as $nilai)
                    <td align="center">
                        {{ number_format($nilai,2) }}
                    </td>
                @endforeach
            </tr>
        </tfoot>
    </table>
<div style="
    position:absolute;
    left:110mm;
    top:0;
    width:{{ count($weeks)*6 }}mm;
    height:100%;
    z-index:999;
">

    @include('build.kurva-svg')

</div>
</div>