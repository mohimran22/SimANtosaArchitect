    <div class="card mb-3 shadow-sm border-0">

        <div class="card-header bg-primary text-white">
            <i class="ti ti-map-pin me-2"></i>
            Lokasi Absensi
        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-6">

                    <strong>Check In</strong>

                    <div class="small text-secondary mb-2">

                        {{ $attendance->check_in_lat }},
                        {{ $attendance->check_in_lng }}

                    </div>

                    @if($attendance->check_in_lat)

                        <a
                            href="https://maps.google.com/?q={{ $attendance->check_in_lat }},{{ $attendance->check_in_lng }}"
                            target="_blank"
                            class="btn btn-sm btn-outline-primary">

                            <i class="ti ti-map-pin"></i>
                            Lihat Maps

                        </a>

                    @endif

                </div>

                <div class="col-md-6">

                    <strong>Check Out</strong>

                    <div class="small text-secondary mb-2">

                        {{ $attendance->check_out_lat }},
                        {{ $attendance->check_out_lng }}

                    </div>

                    @if($attendance->check_out_lat)

                        <a
                            href="https://maps.google.com/?q={{ $attendance->check_out_lat }},{{ $attendance->check_out_lng }}"
                            target="_blank"
                            class="btn btn-sm btn-outline-primary">

                            <i class="ti ti-map-pin"></i>
                            Lihat Maps

                        </a>

                    @endif

                </div>

            </div>

            @if($attendance->overtime)

            <hr>

            <div class="row g-3">

                <div class="col-md-6">

                    <strong>Mulai Lembur</strong>

                    <div class="small text-secondary mb-2">

                        {{ $attendance->overtime->start_lat }},
                        {{ $attendance->overtime->start_lng }}

                    </div>

                    @if($attendance->overtime->start_lat)

                        <a
                            href="https://maps.google.com/?q={{ $attendance->overtime->start_lat }},{{ $attendance->overtime->start_lng }}"
                            target="_blank"
                            class="btn btn-sm btn-outline-primary">

                            <i class="ti ti-map-pin"></i>
                            Lihat Maps

                        </a>

                    @endif

                </div>

                <div class="col-md-6">

                    <strong>Selesai Lembur</strong>

                    <div class="small text-secondary mb-2">

                        {{ $attendance->overtime->end_lat }},
                        {{ $attendance->overtime->end_lng }}

                    </div>

                    @if($attendance->overtime->end_lat)

                        <a
                            href="https://maps.google.com/?q={{ $attendance->overtime->end_lat }},{{ $attendance->overtime->end_lng }}"
                            target="_blank"
                            class="btn btn-sm btn-outline-primary">

                            <i class="ti ti-map-pin"></i>
                            Lihat Maps

                        </a>

                    @endif

                </div>

            </div>

            @endif

        </div>

    </div>