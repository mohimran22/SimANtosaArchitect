<div class="text-center">

<h2 class="text-success mb-4">
    ✅ Absensi Selesai
</h2>

<div class="row">

    <div class="col">
        <small class="text-secondary">Jam Masuk</small>
        <h4>{{ $attendanceToday->check_in->format('H:i') }}</h4>
    </div>

    <div class="col">
        <small class="text-secondary">Jam Pulang</small>
        <h4>{{ $attendanceToday->check_out->format('H:i') }}</h4>
    </div>

</div>
<button
    class="btn btn-warning btn-md rounded-pill"
    data-bs-toggle="modal"
    data-bs-target="#startOvertimeModal">

    <i class="ti ti-clock-play me-2"></i>
    Mulai Lembur

</button>
</div>