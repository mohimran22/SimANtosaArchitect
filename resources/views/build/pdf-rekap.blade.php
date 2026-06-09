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
$totalRencana = 0;
$totalRealisasi = 0;
@endphp

@foreach($rekap as $r)

@php

$deviasi = $r['realisasi_sd_minggu_ini'] - $r['rencana'];

$totalBobot += $r['bobot'];
$totalRencana += $r['rencana'];
$totalRealisasi += $r['realisasi_sd_minggu_ini'];

@endphp

<tr>
    <td align="center">
    {{ \PhpOffice\PhpSpreadsheet\Cell\Coordinate
        ::stringFromColumnIndex($loop->iteration) }}
    </td>
    {{-- JENIS --}}
    <td>
        {{ $r['category'] }}
    </td>

    {{-- BOBOT KONTRAK --}}
    <td class="text-end">
        {{ number_format($r['bobot'],2) }}
    </td>

    {{-- RENCANA KUMULATIF --}}
    <td class="text-end">
        {{ number_format($r['rencana'],2) }}
    </td>

    {{-- REALISASI SD MINGGU LALU --}}
    <td class="text-end">
        {{ number_format($r['prestasi_lalu'],2) }}
    </td>

    <td class="text-end">
        {{ number_format($r['bobot_lalu'],2) }}
    </td>

    {{-- REALISASI MINGGU INI --}}
    <td class="text-end">
        {{ number_format($r['prestasi_minggu_ini'],2) }}
    </td>

    <td class="text-end">
        {{ number_format($r['bobot_minggu_ini'],2) }}
    </td>

    {{-- REALISASI SD MINGGU INI --}}
    <td class="text-end">
        {{ number_format($r['prestasi_sd_minggu_ini'],2) }}
    </td>

    <td class="text-end">
        {{ number_format($r['realisasi_sd_minggu_ini'],2) }}
    </td>

    {{-- DEVIASI --}}
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
        {{ number_format($totalBobot) }}
    </th>

    <th class="text-end">
        {{ number_format($totalRencana,2) }}
    </th>

    <th colspan="4"></th>

    <th></th>

    <th class="text-end">
        {{ number_format($totalRealisasi,2) }}
    </th>

    <th class="text-end">
        {{ number_format(
            $totalRealisasi - $totalRencana,
            2
        ) }}
    </th>

</tr>

</tfoot>

</table>