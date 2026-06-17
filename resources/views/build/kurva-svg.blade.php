<svg xmlns="http://www.w3.org/2000/svg" width="{{ $svgWidth }}" height="{{ $svgHeight }}">

@php
$countWeek = max(count($weeks),1);

$stepX = $svgWidth / $countWeek;

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
        $x = $i * $stepX;
    @endphp

    <line
        x1="{{ $x }}"
        y1="0"
        x2="{{ $x }}"
        y2="{{ $svgHeight }}"
        stroke="#ddddd"
        stroke-width="1"
    />

@endfor

<polyline
    fill="none"
    stroke="#00aa00"
    stroke-width="2"
    points="{{ $svgPlan }}"
/>

<polyline
    fill="none"
    stroke="#0000ff"
    stroke-width="2"
    points="{{ $svgReal }}"
/>

@foreach($plan as $i => $value)

    @php

        $x = ($i * $stepX) + ($stepX / 2);

        $y = $svgHeight - (($value / $maxValue) * $svgHeight);

    @endphp

    <circle
        cx="{{ $x }}"
        cy="{{ $y }}"
        r="2"
        fill="#00aa00"
    />

    <text
        x="{{ $x }}"
        y="{{ $y - 4 }}"
        font-size="7"
        text-anchor="middle"
        fill="#00aa00">

        {{ round($value,1) }}

    </text>

@endforeach

@foreach($realisasi as $i => $value)

    @php

        $x = ($i * $stepX) + ($stepX / 2);

        $y = $svgHeight - (($value / $maxValue) * $svgHeight);

    @endphp

    <circle
        cx="{{ $x }}"
        cy="{{ $y }}"
        r="2"
        fill="#0000ff"
    />

    <text
        x="{{ $x }}"
        y="{{ $y + 10 }}"
        font-size="7"
        text-anchor="middle"
        fill="#0000ff">

        {{ round($value,1) }}

    </text>

@endforeach

</svg>