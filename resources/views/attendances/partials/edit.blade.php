<form id="attendanceEditForm">
    @csrf
    @method('PUT')
    <input type="hidden" id="attendance_id" value="{{ $attendance->id }}">
    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label required">
                Tanggal
            </label>
            <input
                type="date"
                class="form-control"
                name="attendance_date"
                value="{{ $attendance->attendance_date->format('Y-m-d') }}">
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">
                Jam Masuk
            </label>

            <input
                type="time"
                class="form-control"
                name="check_in"
                value="{{ optional($attendance->check_in)->format('H:i') }}">

        </div>

        <div class="col-md-4 mb-3">

            <label class="form-label">

                Jam Pulang

            </label>

            <input
                type="time"
                class="form-control"
                name="check_out"
                value="{{ optional($attendance->check_out)->format('H:i') }}">
        </div>
        <div class="col-12 mb-3">
            <label class="form-label">
                Catatan
            </label>
            <textarea
                class="form-control"
                rows="3"
                name="notes">{{ $attendance->notes }}</textarea>
        </div>
        <div class="mb-3">

            <label class="form-label required">
                Alasan Perubahan
            </label>

            <textarea
                name="edit_reason"
                rows="3"
                class="form-control"
                placeholder="Contoh: Karyawan lupa checkout karena aplikasi force close."
                required></textarea>

        </div>
    </div>
    <div class="text-end">
        <button class="btn btn-secondary btn-back-history" data-employee="{{ $attendance->employee_id }}">
            <i class="ti ti-arrow-left"></i>
            Kembali
        </button>
        <button type="submit" class="btn btn-dark">
            <i class="ti ti-device-floppy"></i>
            Simpan Perubahan
        </button>
    </div>
</form>