<svg
    xmlns="http://www.w3.org/2000/svg"
    width="100%"
    height="100%"
    viewBox="0 0 {{ $svgWidth }} {{ $svgHeight }}"
    preserveAspectRatio="none">

@php
$countWeek = max(count($weeks),1);
@endphp

@for($i=0;$i<=10;$i++)

    @php
        $y = $paddingTop + ($chartHeight/10) * $i;
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
        $x = $paddingLeft + ($i * $stepX);
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

<polyline
fill="none"
stroke="#16a34a"
stroke-width="3"
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

        $x = $paddingLeft + ($i * $stepX);

        $y = $paddingTop + $chartHeight - (($value / $safeMax) * $chartHeight);

    @endphp

    <circle
        cx="{{ $x }}"
        cy="{{ $y }}"
        r="3"
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

@foreach($realisasi as $i => $value)
    @if(($i + 1) > $weekNow)
        @break
    @endif

    @php
        $x = $paddingLeft + ($i * $stepX);
        $y = $paddingTop + $chartHeight - (($value / $safeMax) * $chartHeight);
    @endphp

    <circle
        cx="{{ $x }}"
        cy="{{ $y }}"
        r="4"
        fill="#ff0000"
    />

    <text
        x="{{ $x }}"
        y="{{ $y - 8 }}"
        font-size="8"
        fill="#ff0000">

        {{ round($value,1) }}

    </text>

@endforeach

</svg>