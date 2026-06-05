<h3 style="text-align:center">
    REKAPITULASI BOBOT KEMAJUAN
    PEKERJAAN MINGGUAN
</h3>

<table style="margin-bottom:15px;border:none">

<tr>
    <td style="border:none;width:20%">
        Pemilik Pekerjaan
    </td>

    <td style="border:none">
        : {{ $project->customer->display_name }}
    </td>
</tr>
<tr>
    <td style="border:none">
        Tahun
    </td>

    <td style="border:none">
        : {{ $project->start_date ?? '-' }}
    </td>
</tr>

<tr>
    <td style="border:none">
        Lokasi
    </td>

    <td style="border:none">
        : {{ $project->project_location ?? '-' }}
    </td>
</tr>
<tr>
    <td style="border:none">
        Waktu Pelaksanaan Pekerjaan
    </td>

    <td style="border:none">
        : {{ $project->job_duration ?? '-' }}
    </td>
</tr>

</table>

<table>

<thead>

<tr>

    <th>No</th>

    <th>Jenis Pekerjaan</th>

    <th>Bobot (%)</th>

    <th>Realisasi (%)</th>

    <th>Deviasi</th>

</tr>

</thead>

<tbody>

@php
$totalBobot = 0;
$totalRealisasi = 0;
@endphp

@foreach($rekap as $i => $r)

@php

$deviasi =
    $r['realisasi'] - $r['bobot'];

$totalBobot += $r['bobot'];
$totalRealisasi += $r['realisasi'];

@endphp

<tr>

    <td class="text-center">
        {{ $loop->iteration }}
    </td>

    <td>
        {{ $r['category'] }}
    </td>

    <td class="text-end">
        {{ number_format($r['bobot'],2) }}
    </td>

    <td class="text-end">
        {{ number_format($r['realisasi'],2) }}
    </td>

    <td class="text-end">
        {{ number_format($deviasi,2) }}
    </td>

</tr>

@endforeach

</tbody>

<tfoot>

<tr>

    <th colspan="2">
        TOTAL
    </th>

    <th class="text-end">
        {{ number_format($totalBobot,2) }}
    </th>

    <th class="text-end">
        {{ number_format($totalRealisasi,2) }}
    </th>

    <th class="text-end">
        {{ number_format(
            $totalRealisasi - $totalBobot,
            2
        ) }}
    </th>

</tr>

</tfoot>

</table>