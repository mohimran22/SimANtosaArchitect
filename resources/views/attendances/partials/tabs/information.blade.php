    <div class="card mb-3 shadow-sm border-0">
        <div class="card-header bg-dark text-white">
            <i class="ti ti-file-description me-2"></i>
            Informasi Absensi
        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-4">
                    <small class="text-secondary">Tanggal</small>
                    <div class="fw-bold">
                        {{ $attendance->attendance_date->translatedFormat('d F Y') }}
                    </div>
                </div>

                <div class="col-md-4">
                    <small class="text-secondary">Status</small>
                    <div class="fw-bold">
                        {{ $attendance->attendance_code }}
                    </div>
                </div>
                <div class="col-md-4">
                    <small class="text-secondary">Catatan</small>
                    <div class="fw-bold">
                        {{ $attendance->notes }}
                    </div>
                </div>
            </div>
            <hr>
            <div class="row g-3">
                <div class="col-md-4">
                    <small class="text-secondary">Jam Masuk</small>
                    <div class="fw-bold">
                        {{ optional($attendance->check_in)->format('H:i') }}
                    </div>
                </div>

                <div class="col-md-4">
                    <small class="text-secondary">Jam Pulang</small>
                    <div class="fw-bold">
                        {{ optional($attendance->check_out)->format('H:i') }}
                    </div>
                </div>
                <div class="col-md-4">
                    <small class="text-secondary">Durasi Kerja</small>
                    <div class="fw-bold">

                        {{ $attendance->work_duration ?? '-' }}

                    </div>
                </div>
            </div>

            @if($attendance->overtime)

            <hr>

            <div class="row g-3">

                <div class="col-md-4">
                    <small class="text-secondary">Mulai Lembur</small>
                    <div class="fw-bold">
                        {{ optional($attendance->overtime->start_time)->format('H:i') }}
                    </div>
                </div>

                <div class="col-md-4">
                    <small class="text-secondary">Selesai Lembur</small>
                    <div class="fw-bold">
                        {{ optional($attendance->overtime->end_time)->format('H:i') }}
                    </div>
                </div>

                <div class="col-md-4">
                    <small class="text-secondary">Durasi Lembur</small>
                    <div class="fw-bold">

                        {{ $attendance->overtime?->duration ?? '-' }}

                    </div>
                </div>

            </div>

            @endif

        </div>
    </div>