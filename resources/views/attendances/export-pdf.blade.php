<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <style>
        body{
            font-family: DejaVu Sans;
            font-size:10px;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        th,td{
            border:1px solid #000;
            padding:5px;
        }

        th{
            background:#eee;
            text-align:center;
        }
    </style>

</head>
<body>

<h2 style="text-align:center">
    Laporan Absensi
</h2>

<table>

    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Nama</th>
            <th>Masuk</th>
            <th>Pulang</th>
            <th>Status</th>
            <th>Terlambat</th>
            <th>Lembur</th>
        </tr>
    </thead>

    <tbody>

        @foreach($employees as $employee)
        @php
            $summary = $summaries[$employee->id] ?? [];
        @endphp

        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $employee->user?->fullname }}</td>
            <td>{{ $employee->user?->roles?->pluck('name')->implode(', ') }}</td>

            <td>{{ $summary['H'] ?? 0 }}</td>
            <td>{{ $summary['TL A'] ?? 0 }}</td>
            <td>{{ $summary['TL B'] ?? 0 }}</td>
            <td>{{ $summary['TL C'] ?? 0 }}</td>
            <td>{{ $summary['DL'] ?? 0 }}</td>
            <td>{{ $summary['I'] ?? 0 }}</td>
            <td>{{ $summary['S'] ?? 0 }}</td>
            <td>{{ $summary['C'] ?? 0 }}</td>
            <td>{{ $summary['A'] ?? 0 }}</td>

            <td>{{ $summary['total_hari_kerja'] ?? 0 }}</td>
            <td>{{ $summary['total_hari_kehadiran'] ?? 0 }}</td>
            <td>{{ $summary['kehadiran'] ?? 0 }}%</td>
            <td>{{ $summary['ketepatan_waktu'] ?? 0 }}%</td>
            <td>{{ round(($summary['total_jam_lembur'] ?? 0)/60,2) }}</td>
            <td>{{ $summary['keterangan'] ?? '-' }}</td>
        </tr>
        @endforeach

    </tbody>

</table>

</body>
</html>