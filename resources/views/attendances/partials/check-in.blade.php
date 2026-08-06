<div class="text-center py-2">

    <div class="mb-2 text-secondary">
        Status Absensi Hari Ini
    </div>

    <h2 class="text-warning mb-3">
        ⭕ Belum Hadir
    </h2>

    @if($attendanceClosed)

        <div class="alert alert-danger">
            <i class="ti ti-alert-circle me-1"></i>
            Waktu absensi telah berakhir (10.00 WIB). Silakan hubungi HRD apabila terdapat kendala.
        </div>

    @else

        <div class="d-flex justify-content-center gap-2 flex-wrap">

            <button
                class="btn btn-dark btn-md rounded-pill px-4"
                data-bs-toggle="modal"
                data-bs-target="#checkInModal">

                <i class="ti ti-camera me-2"></i>
                Absen Masuk

            </button>

            <button
                class="btn btn-outline-warning btn-md rounded-pill px-4"
                data-bs-toggle="modal"
                data-bs-target="#izinModal">

                <i class="ti ti-file-text me-2"></i>
                Ajukan Izin

            </button>

        </div>

    @endif

</div>