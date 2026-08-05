<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <style>
        body{
            font-family: DejaVu Sans, sans-serif;
            font-size:12px;
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
        }
    </style>

</head>
<body>

<h2 style="text-align:center">
    Riwayat Absensi
</h2>

<p>
    Nama :
    {{ $employee->user->fullname }}
</p>

<table class="table table-hover table-vcenter">

    <thead>
        <tr>
            <th width="80">Tanggal</th>
            <th width="80">Masuk</th>
            <th width="80">Pulang</th>
            <th width="60">Status</th>
            <th width="80">Jam Kerja</th>
            <th width="80">Lembur</th>
        </tr>
    </thead>

    <tbody>

    @foreach($attendances as $attendance)

    @php

        $badge = match($attendance->attendance_code){

            'H' => 'bg-success',

            'TL A' => 'bg-warning',

            'TL B' => 'bg-orange',

            'TL C' => 'bg-danger',

            'DL' => 'bg-info',

            'I' => 'bg-secondary',

            'S' => 'bg-cyan',

            'C' => 'bg-purple',

            default => 'bg-dark'

        };
    @endphp

    <tr>
        <td>
            {{ \Carbon\Carbon::parse($attendance->attendance_date)->translatedFormat('d F Y') }}
        </td>
        <td>
            {{ optional($attendance->check_in)->format('H:i') ?? '-' }}
        </td>
        <td>
            {{ optional($attendance->check_out)->format('H:i') ?? '-' }}
        </td>
        <td>
            <span class="badge {{ $badge }}">
                {{ $attendance->attendance_code ?? '-' }}
            </span>
        </td>
        <td>
            {{ $attendance->work_duration }}
        </td>
        <td>{{ $attendance->overtime?->duration ?? '-' }}</td>
    </tr>

    @endforeach

    </tbody>

</table>

</body>
</html>