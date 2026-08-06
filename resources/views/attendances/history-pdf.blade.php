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
    <div class="header">
        <img src="{{ public_path('images/header-penawaran.jpg') }}" style="width:100%;">
    </div>

    <!-- ================= FOOTER ================= -->
    <div class="footer">
        <img src="{{ public_path('images/footer-penawaran.jpg') }}" style="width:100%;">
    </div>
<h2>
    Riwayat Absensi
</h2>

<p>
    Nama    :
    {{ $employee->user->fullname }}
    <br>
    Jabatan :
     {{ $employee->user->roles->pluck('name')->implode(', ') }}
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