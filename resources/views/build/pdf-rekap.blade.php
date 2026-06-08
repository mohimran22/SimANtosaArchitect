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

        <tr style="background:#e5e5e5; text-align:center; font-weight:bold;">
            <th rowspan="3" width="4%">
                NO
            </th>
            <th rowspan="3">
                Jenis Pekerjaan
            </th>
            <th rowspan="3" width="7%">
                Bobot (%)
            </th>
            <th rowspan="3" width="8%">
                Rencana s/d Minggu ini (%)
            </th>
            <th colspan="6">
                Realisasi
            </th>
            <th rowspan="3" width="8%">
                Deviasi (%)
            </th>
        </tr>

        <tr style="background:#f2f2f2; text-align:center; font-weight:bold;">

            <th colspan="2">
                s/d Minggu Lalu
            </th>

            <th colspan="2">
                Minggu ini
            </th>

            <th colspan="2">
                s/d Minggu ini
            </th>

        </tr>

        <tr style="background:#f9f9f9; text-align:center; font-weight:bold;">

            <th width="7%">
                Prestasi (%)
            </th>

            <th width="7%">
                Bobot (%)
            </th>

            <th width="7%">
                Prestasi (%)
            </th>

            <th width="7%">
                Bobot (%)
            </th>

            <th width="7%">
                Prestasi (%)
            </th>

            <th width="7%">
                Bobot (%)
            </th>

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