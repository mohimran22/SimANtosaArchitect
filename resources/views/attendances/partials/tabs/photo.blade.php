    <div class="card shadow-sm border-0">

        <div class="card-header bg-success text-white">
            <i class="ti ti-camera me-2"></i>
            Dokumentasi Foto
        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-3 text-center">

                    <div class="fw-bold mb-2">
                        Check In
                    </div>

                    @if($attendance->check_in_photo)

                        <img
                            src="{{ asset('storage/'.$attendance->check_in_photo) }}"
                            class="img-thumbnail shadow-sm"
                            style="width:180px;height:180px;object-fit:cover;cursor:pointer"
                            onclick="window.open(this.src,'_blank')">

                    @else

                        <div class="text-secondary">-</div>

                    @endif

                </div>

                <div class="col-md-3 text-center">

                    <div class="fw-bold mb-2">
                        Check Out
                    </div>

                    @if($attendance->check_out_photo)

                        <img
                            src="{{ asset('storage/'.$attendance->check_out_photo) }}"
                            class="img-thumbnail shadow-sm"
                            style="width:180px;height:180px;object-fit:cover;cursor:pointer"
                            onclick="window.open(this.src,'_blank')">

                    @else

                        <div class="text-secondary">-</div>

                    @endif

                </div>

                @if($attendance->overtime)

                <div class="col-md-3 text-center">

                    <div class="fw-bold mb-2">
                        Mulai Lembur
                    </div>

                    @if($attendance->overtime->start_photo)

                        <img
                            src="{{ asset('storage/'.$attendance->overtime->start_photo) }}"
                            class="img-thumbnail shadow-sm"
                            style="width:180px;height:180px;object-fit:cover;cursor:pointer"
                            onclick="window.open(this.src,'_blank')">

                    @endif

                </div>

                <div class="col-md-3 text-center">

                    <div class="fw-bold mb-2">
                        Selesai Lembur
                    </div>

                    @if($attendance->overtime->end_photo)

                        <img
                            src="{{ asset('storage/'.$attendance->overtime->end_photo) }}"
                            class="img-thumbnail shadow-sm"
                            style="width:180px;height:180px;object-fit:cover;cursor:pointer"
                            onclick="window.open(this.src,'_blank')">

                    @endif

                </div>

                @endif

            </div>

        </div>

    </div>