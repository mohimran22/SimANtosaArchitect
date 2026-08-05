<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <style>
        @page{
            margin:140px 25px 110px;
        }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        /* ================= HEADER & FOOTER ================= */
        .header {
            position: fixed;
            top: -110px;
            left: 0;
            right: 0;
            width: 100%;
        }

        .footer {
            position: fixed;
            bottom: -90px;
            left: 0;
            right: 0;
            width: 100%;
        }
        h2 {
            text-align: center;
            margin-top:30px;
        }
        .report-title{
            text-align:center;
            font-size:18px;
            font-weight:bold;
            margin-top:30px;
            letter-spacing:.5px;
        }

        .report-subtitle{
            text-align:center;
            font-size:12px;
            margin-top:4px;
            margin-bottom:18px;
            color:#000;
        }
        table{
            width:100%;
            border-collapse:collapse;
            table-layout:fixed;
        }

        th,td{
            border:1px solid #000;
            padding:4px;
        }

        th{
            background:#eee;
            text-align:center;
        }
        thead th{

            background:#f3f4f6;

            font-size:10px;

            font-weight:bold;

            text-align:center;

            padding:7px 4px;

            border:.6px solid #666;
        }
        tbody td{

            border:.5px solid #999;

            padding:6px 2px;

            font-size:10px;

            vertical-align:middle;
        }
        tbody tr:nth-child(even){

            background:#fafafa;

        }
        .text-left{
            text-align:left;
        }

        .text-center{
            text-align:center;
        }
        thead{
            display:table-header-group;
        }

        tfoot{
            display:table-row-group;
        }

        tr{
            page-break-inside:avoid;
        }
    </style>

</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/header-penawaran.jpg') }}" style="width:100%;">
    </div>

    <!-- ================= FOOTER ================= -->
    <div class="footer">
        <img src="{{ public_path('images/footer-penawaran.jpg') }}" style="width:100%;">
    </div>
<div class="report-title">
    REKAP ABSENSI KARYAWAN
</div>

<div class="report-subtitle">
    Periode Bulan :
    {{ \Carbon\Carbon::create()->month((int) $month)->translatedFormat('F') }}
    <br>
    Periode Tahun :
    {{ $year }}
</div>

<table>

    <thead>

        <tr>

            <th style="width:5px">No</th>

            <th style="width:210px">Nama Karyawan</th>

            <th style="width:34px">H</th>

            <th style="width:34px">TL A</th>

            <th style="width:34px">TL B</th>

            <th style="width:34px">TL C</th>

            <th style="width:34px">DL</th>

            <th style="width:28px">I</th>

            <th style="width:28px">S</th>

            <th style="width:28px">C</th>

            <th style="width:28px">A</th>

            <th style="width:55px">
            Hari<br>Kerja
            </th>

            <th style="width:55px">
            Hari<br>Hadir
            </th>

            <th style="width:60px">
            Kehadiran
            </th>

            <th style="width:70px">
            Ketepatan<br>Waktu
            </th>

            <th style="width:55px">
            Lembur<br>(Jam)
            </th>

        </tr>

    </thead>

    <tbody>

        @foreach($employees as $employee)
        @php
            $summary = $summaries[$employee->id] ?? [];
        @endphp

        <tr>
            <td>{{ $loop->iteration }}</td>
            <td class="text-left">
                {{ Str::title($employee->user?->fullname) }}
            </td>
            {{-- <td>{{ $employee->user?->roles?->pluck('name')->implode(', ') }}</td> --}}

            <td class="text-center">{{ $summary['H'] ?? 0 }}</td>
            <td class="text-center">{{ $summary['TL A'] ?? 0 }}</td>
            <td class="text-center">{{ $summary['TL B'] ?? 0 }}</td>
            <td class="text-center">{{ $summary['TL C'] ?? 0 }}</td>
            <td class="text-center">{{ $summary['DL'] ?? 0 }}</td>
            <td class="text-center">{{ $summary['I'] ?? 0 }}</td>
            <td class="text-center">{{ $summary['S'] ?? 0 }}</td>
            <td class="text-center">{{ $summary['C'] ?? 0 }}</td>
            <td class="text-center">{{ $summary['A'] ?? 0 }}</td>

            <td class="text-center">{{ $summary['total_hari_kerja'] ?? 0 }}</td>
            <td class="text-center">{{ $summary['total_hari_kehadiran'] ?? 0 }}</td>
            <td class="text-center">{{ $summary['kehadiran'] ?? 0 }}%</td>
            <td class="text-center">{{ $summary['ketepatan_waktu'] ?? 0 }}%</td>
            <td class="text-center">{{ round(($summary['total_jam_lembur'] ?? 0)/60,2) }}</td>
            {{-- <td>{{ $summary['keterangan'] ?? '-' }}</td> --}}
        </tr>
        @endforeach

    </tbody>

</table>

</body>
</html>