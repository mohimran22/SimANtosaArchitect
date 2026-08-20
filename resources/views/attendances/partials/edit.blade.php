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
        <div class="col-12">
            <hr class="my-3">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0">Lembur</h4>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">
                Jam Mulai Lembur
            </label>

            <input
                type="time"
                class="form-control"
                name="overtime_start_time"
                value="{{ optional($attendance->overtime?->start_time)->format('H:i') }}"
            >
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">
                Jam Selesai Lembur
            </label>

            <input
                type="time"
                class="form-control"
                name="overtime_end_time"
                value="{{ optional($attendance->overtime?->end_time)->format('H:i') }}"
            >
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">
                Tipe Lembur
            </label>

            <select class="form-select" name="overtime_type">
                <option value="weekday"
                    {{ optional($attendance->overtime)->type === 'weekday' ? 'selected' : '' }}>
                    Hari Kerja
                </option>

                <option value="holiday"
                    {{ optional($attendance->overtime)->type === 'holiday' ? 'selected' : '' }}>
                    Hari Libur
                </option>
            </select>
        </div>

        <div class="col-12 mb-3">
            <label class="form-label">
                Alasan Lembur
            </label>

            <textarea
                class="form-control"
                name="overtime_reason"
                rows="3"
                placeholder="Contoh: Menyelesaikan pekerjaan proyek yang belum selesai."
            >{{ optional($attendance->overtime)->reason }}</textarea>
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