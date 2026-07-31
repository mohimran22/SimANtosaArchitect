@if($attendances->isEmpty())

<div class="empty">

    <div class="empty-icon">
        <i class="ti ti-calendar-off fs-1"></i>
    </div>

    <p class="empty-title">
        Belum ada history absensi.
    </p>

</div>

@else

<div class="table-responsive">

<table class="table table-hover table-vcenter">

    <thead>

        <tr>

            <th width="140">Tanggal</th>

            <th width="80">Masuk</th>

            <th width="80">Pulang</th>

            <th width="90">Status</th>

            <th width="120">Jam Kerja</th>

            <th width="90">Lembur</th>
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

        $workMinutes = $attendance->work_minutes ?? 0;

        $workHour = floor($workMinutes / 60);

        $workMinute = $workMinutes % 60;

        $overtimeMinutes = $attendance->overtime_minutes ?? 0;

        $overtimeHour = floor($overtimeMinutes / 60);

        $overtimeMinute = $overtimeMinutes % 60;

    @endphp

    <tr>

        <td>

            {{ \Carbon\Carbon::parse($attendance->attendance_date)->locale('id')->translatedFormat('d/M/Y') }}

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

            {{ $workHour }}j {{ $workMinute }}m

        </td>

        <td>

            @if($overtimeMinutes)

                {{ $overtimeHour }}j {{ $overtimeMinute }}m

            @else

                -

            @endif

        </td>
    </tr>

    @endforeach

    </tbody>

</table>

</div>

@endif