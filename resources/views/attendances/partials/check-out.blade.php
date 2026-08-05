@if($attendanceToday->status === 'present')

    <div class="text-center">

        <h2 class="text-success mb-3">
            ✅ Sudah Hadir
        </h2>

        <div class="row mt-4">

            <div class="col">
                <small class="text-secondary">Jam Masuk</small>
                <h4>{{ $attendanceToday->check_in->format('H:i') }}</h4>
            </div>

            <div class="col">
                <small class="text-secondary">Jam Pulang</small>
                <h4>
                    {{ optional($attendanceToday->check_out)->format('H:i') ?? '--:--' }}
                </h4>
            </div>

        </div>

        @if(is_null($attendanceToday->check_out))
            <button
                class="btn btn-danger rounded-pill"
                data-bs-toggle="modal"
                data-bs-target="#checkOutModal">

                <i class="ti ti-logout me-2"></i>
                Pulang

            </button>
        @endif

    </div>

@else

    <div class="text-center">

        <h2 class="text-success mb-3">
            ✅ Sudah Absen
        </h2>

        <p class="text-secondary mb-1">
            Status:
            <strong>
                {{ match($attendanceToday->status) {
                    'permission'    => 'Izin',
                    'sick'          => 'Sakit',
                    'leave'         => 'Cuti',
                    'business_trip' => 'Dinas Luar',
                    default         => ucfirst($attendanceToday->status),
                } }}
            </strong>
        </p>

        @if($attendanceToday->notes)
            <div class="alert alert-light mt-3">
                {{ $attendanceToday->notes }}
            </div>
        @endif

    </div>

@endif