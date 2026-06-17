<svg xmlns="http://www.w3.org/2000/svg" width="{{ $svgWidth }}" height="{{ $svgHeight }}">

@php
$countWeek = max(count($weeks),1);

$stepX = $svgWidth / $countWeek;
$safeMax = max($maxValue ?? 0, 1);
@endphp

@for($i=0;$i<=10;$i++)

    @php
        $y = ($svgHeight/10) * $i;
    @endphp

    <line
        x1="0"
        y1="{{ $y }}"
        x2="{{ $svgWidth }}"
        y2="{{ $y }}"
        stroke="#dddddd"
        stroke-width="1"
    />

@endfor

@for($i=0;$i<$countWeek;$i++)

    @php
        $x = ($i * $stepX) + ($stepX / 2);
    @endphp

    <line
        x1="{{ $x }}"
        y1="0"
        x2="{{ $x }}"
        y2="{{ $svgHeight }}"
        stroke="#dddddd"
        stroke-width="1"
    />

@endfor
@foreach($weeks as $i => $w)

    @php
        $x = ($i * $stepX) + ($stepX / 2);
    @endphp

    <text
        x="{{ $x }}"
        y="{{ $svgHeight - 5 }}"
        font-size="10"
        text-anchor="middle"
        fill="#555">

        M{{ $w['week_no'] }}

    </text>

@endforeach

<polyline
fill="none"
stroke="#16a34a"
stroke-width="3"
stroke-dasharray="8 5"
points="{{ $svgPlan }}"
/>

<polyline
    fill="none"
    stroke="#0000ff"
    stroke-width="3"
    points="{{ $svgReal }}"
/>

@foreach($plan as $i => $value)

    @php

        $x = ($i * $stepX) + ($stepX / 2);

        $y = $svgHeight - (($value / 100) * $svgHeight);

    @endphp

    <circle
        cx="{{ $x }}"
        cy="{{ $y }}"
        r="3"
        fill="#00aa00"
    />

    <text
        x="{{ $x }}"
        y="{{ $y - 8 }}"
        font-size="8"
        text-anchor="middle"
        fill="#00aa00">

        {{ round($value,1) }}

    </text>

@endforeach

@foreach($realisasi as $i => $value)

    @php

        $x = ($i * $stepX) + ($stepX / 2);

        $y = $svgHeight - (($value / 100) * $svgHeight);

    @endphp

    <circle
        cx="{{ $x }}"
        cy="{{ $y }}"
        r="4"
        fill="#0000ff"
    />

    <text
        x="{{ $x }}"
        y="{{ $y - 8 }}"
        font-size="8"
        text-anchor="middle"
        fill="#0000ff">

        {{ round($value,1) }}

    </text>

@endforeach

</svg>