<div class="row">

    <div class="col-md-6">

        <strong>Tanggal</strong>

        <div>

            {{ $attendance->attendance_date }}

        </div>

    </div>

    <div class="col-md-6">

        <strong>Status</strong>

        <div>

            {{ $attendance->attendance_code }}

        </div>

    </div>

    <div class="col-md-6 mt-3">

        <strong>Jam Masuk</strong>

        <div>

            {{ optional($attendance->check_in)->format('H:i') }}

        </div>

    </div>

    <div class="col-md-6 mt-3">

        <strong>Jam Pulang</strong>

        <div>

            {{ optional($attendance->check_out)->format('H:i') }}

        </div>

    </div>

    <div class="col-md-6 mt-3">

        <strong>Foto Masuk</strong>

        @if($attendance->check_in_photo)

            <img
                src="{{ asset('storage/'.$attendance->check_in_photo) }}"
                class="img-fluid rounded">

        @endif

    </div>

    <div class="col-md-6 mt-3">

        <strong>Foto Pulang</strong>

        @if($attendance->check_out_photo)

            <img
                src="{{ asset('storage/'.$attendance->check_out_photo) }}"
                class="img-fluid rounded">

        @endif

    </div>
    <button
        class="btn btn-secondary mb-3 btn-back-history"
        data-employee="{{ $attendance->employee_id }}">

        <i class="ti ti-arrow-left"></i>

        Kembali

    </button>
</div>