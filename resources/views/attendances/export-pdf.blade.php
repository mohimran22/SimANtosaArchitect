<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Antosa Architect</title>
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

            font-size:9px;

            font-weight:bold;

            text-align:center;

            padding:6px 2px;

            border:.6px solid #666;
        }
        tbody td{

            border:.5px solid #999;

            padding:5px 2px;

            font-size:9px;

            vertical-align:middle;
        }
        tbody tr:nth-child(even){

            background:#fafafa;

        }
        .text-left{
            text-align:left;
        }
        .role{
            font-size:8px;
            line-height:1.2;
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
        .status-col{
            width:20px;
            text-align:center;
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
            <th style="width:4%">No</th>
            <th style="width:24%">Nama Karyawan</th>
            <th style="width:16%">Jabatan</th>

            <th class="status-col">H</th>
            <th class="status-col">TL A</th>
            <th class="status-col">TL B</th>
            <th class="status-col">TL C</th>
            <th class="status-col">DL</th>

            <th class="status-col">I</th>
            <th class="status-col">S</th>
            <th class="status-col">C</th>
            <th class="status-col">A</th>

            <th style="width:5%">Hari<br>Kerja</th>
            <th style="width:5%">Hari<br>Hadir</th>
            <th style="width:6%">Kehadiran</th>
            <th style="width:7%">Ketepatan<br>Waktu</th>
            <th style="width:6%">Lembur<br>(Jam)</th>
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
            <td class="text-left role">{{ $employee->user?->roles?->pluck('name')->implode(', ') }}</td>

            <td class="status-col">{{ $summary['H'] ?? 0 }}</td>
            <td class="status-col">{{ $summary['TL A'] ?? 0 }}</td>
            <td class="status-col">{{ $summary['TL B'] ?? 0 }}</td>
            <td class="status-col">{{ $summary['TL C'] ?? 0 }}</td>
            <td class="status-col">{{ $summary['DL'] ?? 0 }}</td>
            <td class="status-col">{{ $summary['I'] ?? 0 }}</td>
            <td class="status-col">{{ $summary['S'] ?? 0 }}</td>
            <td class="status-col">{{ $summary['C'] ?? 0 }}</td>
            <td class="status-col">{{ $summary['A'] ?? 0 }}</td>

            <td class="status-col">{{ $summary['total_hari_kerja'] ?? 0 }}</td>
            <td class="status-col">{{ $summary['total_hari_kehadiran'] ?? 0 }}</td>
            <td class="status-col">{{ $summary['kehadiran'] ?? 0 }}%</td>
            <td class="status-col">{{ $summary['ketepatan_waktu'] ?? 0 }}%</td>
            <td class="status-col">{{ round(($summary['total_jam_lembur'] ?? 0)/60,2) }}</td>
            {{-- <td>{{ $summary['keterangan'] ?? '-' }}</td> --}}
        </tr>
        @endforeach

    </tbody>

</table>

</body>
</html>