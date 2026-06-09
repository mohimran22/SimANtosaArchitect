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

<table>

<thead>

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

        $subtotalBobot =
            $items->sum('bobot_percent');

    @endphp

    {{-- CATEGORY --}}
    <tr style="background:#c4c4c4; font-weight:bold;">

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

            <td></td>

        @endforeach

    </tr>

    {{-- ITEMS --}}
    @foreach($items as $item)

        <tr>

            <td></td>

            <td>
                {{ $item->uraian }}
            </td>

            <td align="right">
                {{ number_format(
                    $item->bobot_percent,
                    2
                ) }}%
            </td>

            @foreach($weeks as $w)

                @php

                    $plan =
                        $item->plan_week_start
                        <= $w['week_no']

                        &&

                        $item->plan_week_end
                        >= $w['week_no'];

                @endphp

                <td align="center">

                    @if($plan)

                        <div style="
                            height:12px;
                            background:#000;
                            width:100%;
                        "></div>

                    @endif

                </td>

            @endforeach

        </tr>

    @endforeach

@endforeach

</tbody>

</table>

@if(!empty($chartUrl))

<table style="border:none; margin-top:25px;">

<tr>

    <td style="border:none; text-align:center;">

        <img src="{{ $chartUrl }}"
             style="width:100%;">

    </td>

</tr>

</table>

@endif