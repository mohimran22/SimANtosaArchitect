<div class="text-center">

<h2 class="text-warning mb-3">
    🟠 Sedang Lembur
</h2>

<div class="row">

    <div class="col">
        <small>Jam Masuk</small>
        <h4>{{ $attendanceToday->check_in->format('H:i') }}</h4>
    </div>

    <div class="col">
        <small>Jam Pulang</small>
        <h4>{{ $attendanceToday->check_out->format('H:i') }}</h4>
    </div>

</div>

<hr>

<div class="mb-3">

    <small class="text-secondary">
        Mulai Lembur
    </small>

    <h3 class="text-primary">
        {{ $attendanceToday->overtime->start_time->format('H:i') }}
    </h3>

</div>

<button
    class="btn btn-danger btn-md rounded-pill"
    data-bs-toggle="modal"
    data-bs-target="#finishOvertimeModal">

    <i class="ti ti-clock-stop me-2"></i>
    Selesai Lembur

</button>

</div>