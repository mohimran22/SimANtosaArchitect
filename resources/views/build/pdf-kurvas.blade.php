<h2 style="text-align:center; margin-bottom:20px;">
    TIME SCHEDULE & KURVA S
</h2>

<table style="margin-bottom:15px; border:none;">

<tr>
    <td style="border:none; width:20%;">
        Nama Proyek
    </td>

    <td style="border:none;">
        : {{ $project->project_name }}
    </td>
</tr>

<tr>
    <td style="border:none;">
        Lokasi
    </td>

    <td style="border:none;">
        : {{ $project->project_location }}
    </td>
</tr>

<tr>
    <td style="border:none;">
        Durasi
    </td>

    <td style="border:none;">
        : {{ $project->job_duration }}
    </td>
</tr>

</table>
<div style="position:relative">
    <table>
        <thead>
            {{-- <tr>

                <td colspan="{{ 3 + count($weeks) }}"
                    style="
                        padding:0;
                        height:160px;
                        border:1px solid #000;
                    ">

                    <svg
                        width="100%"
                        height="160"
                        viewBox="0 0 {{ $svgWidth }} {{ $svgHeight }}"
                        xmlns="http://www.w3.org/2000/svg">

                        @for($i=0;$i<=10;$i++)

                            @php
                                $y = ($svgHeight/10)*$i;
                            @endphp

                            <line
                                x1="0"
                                y1="{{ $y }}"
                                x2="{{ $svgWidth }}"
                                y2="{{ $y }}"
                                stroke="#ddd"
                                stroke-width="1"
                            />

                        @endfor

                        @foreach($weeks as $i => $w)

                            @php
                                $x =
                                ($i / max(count($weeks)-1,1))
                                * $svgWidth;
                            @endphp

                            <line
                                x1="{{ $x }}"
                                y1="0"
                                x2="{{ $x }}"
                                y2="{{ $svgHeight }}"
                                stroke="#ddd"
                                stroke-width="1"
                            />

                        @endforeach

                        <polyline
                            fill="none"
                            stroke="green"
                            stroke-width="3"
                            points="{{ $svgPlan }}"
                        />

                        <polyline
                            fill="none"
                            stroke="blue"
                            stroke-width="3"
                            points="{{ $svgReal }}"
                        />

                    </svg>

                </td>

            </tr> --}}
            <tr style="background:#d9d9d9; text-align:center; font-weight:bold;">
                <th width="4%">
                    NO
                </th>
                <th>
                    URAIAN PEKERJAAN
                </th>
                <th width="8%">
                    BOBOT
                </th>
                @foreach($weeks as $w)
                    <th width="4%">
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
                <tr style="font-weight:bold;">
                    <td align="center">
                        {{ $loop->iteration }}
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

                            @if($nilai > 0)

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
</div>