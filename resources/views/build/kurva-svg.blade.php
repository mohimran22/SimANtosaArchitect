@php

$width = 1000;
$height = 120;

$stepX = $width / max(count($plan)-1,1);

$planPoints = [];

foreach($plan as $i => $value){

    $x = $i * $stepX;

    $y = $height -
        (($value / 100) * $height);

    $planPoints[] = $x.','.$y;
}

$svgPlan = implode(' ', $planPoints);

$stepX = $width / max(count($realisasi)-1,1);

$realPoints = [];

foreach($realisasi as $i => $value){

    $x = $i * $stepX;

    $y = $height -
        (($value / 100) * $height);

    $realPoints[] = $x.','.$y;
}

$svgReal = implode(' ', $realPoints);
@endphp



<svg
xmlns="http://www.w3.org/2000/svg"
width="1000"
height="120">

@php

$width = 1000;
$height = 120;

$countWeek = max(count($weeks),1);
$stepX = $width / $countWeek;

@endphp

{{-- Grid Horizontal --}}
@for($i=0;$i<=10;$i++)

    @php
        $y = ($height/10) * $i;
    @endphp

    <line
        x1="0"
        y1="{{ $y }}"
        x2="{{ $width }}"
        y2="{{ $y }}"
        stroke="#dddddd"
        stroke-width="1"
    />

@endfor

{{-- Grid Vertical --}}
@for($i=0;$i<=$countWeek;$i++)

    @php
        $x = $i * $stepX;
    @endphp

    <line
        x1="{{ $x }}"
        y1="0"
        x2="{{ $x }}"
        y2="{{ $height }}"
        stroke="#eeeeee"
        stroke-width="1"
    />

@endfor

{{-- Garis Rencana --}}
<polyline
    fill="none"
    stroke="#00aa00"
    stroke-width="2"
    points="{{ $svgPlan }}"
/>

{{-- Garis Realisasi --}}
<polyline
    fill="none"
    stroke="#0000ff"
    stroke-width="2"
    points="{{ $svgReal }}"
/>

{{-- Titik + Angka Rencana --}}
@foreach($plan as $i => $value)

    @php

        $x = $i * 20;

        $y = $height -
            (($value/100) * $height);

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

{{-- Titik + Angka Realisasi --}}
@foreach($realisasi as $i => $value)

    @php

        $x = $i * 20;

        $y = $height -
            (($value/100) * $height);

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