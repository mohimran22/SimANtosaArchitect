<div class="text-center">

<h2 class="text-success mb-4">
    🎉 Lembur Selesai
</h2>

<div class="row mb-3">

    <div class="col">
        <small>Jam Masuk</small>
        <h4>{{ $attendanceToday->check_in->format('H:i') }}</h4>
    </div>

    <div class="col">
        <small>Jam Pulang</small>
        <h4>{{ $attendanceToday->check_out->format('H:i') }}</h4>
    </div>

</div>

<div class="row">

    <div class="col">
        <small>Mulai Lembur</small>
        <h4>{{ $attendanceToday->overtime->start_time->format('H:i') }}</h4>
    </div>

    <div class="col">
        <small>Selesai Lembur</small>
        <h4>{{ $attendanceToday->overtime->end_time->format('H:i') }}</h4>
    </div>

</div>

<hr>

<div class="alert alert-warning">

    <strong>Status :</strong>

    @switch($attendanceToday->overtime->status)

        @case('pending')
            Menunggu Persetujuan Atasan
            @break

        @case('approved')
            Disetujui
            @break

        @case('rejected')
            Ditolak
            @break

    @endswitch

</div>

</div>