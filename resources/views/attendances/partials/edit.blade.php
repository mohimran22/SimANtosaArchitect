<button class="btn btn-secondary mb-4 btn-back-history" data-employee="{{ $attendance->employee_id }}">
    <i class="ti ti-arrow-left"></i>
    Kembali
</button>

<form id="attendanceEditForm">
    @csrf
    @method('PUT')
    <input type="hidden" id="attendance_id" value="{{ $attendance->id }}">
    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">
                Tanggal
            </label>
            <input
                type="date"
                class="form-control"
                name="attendance_date"
                value="{{ $attendance->attendance_date->format('Y-m-d') }}">
        </div>

        {{-- <div class="col-md-6 mb-3">
            <label class="form-label">
                Status
            </label>
            <select class="form-select select2" name="attendance_code">
                @foreach([
                    'H',
                    'TL A',
                    'TL B',
                    'TL C',
                    'DL',
                    'I',
                    'S',
                    'C',
                    'A'
                ] as $status)
                <option
                    value="{{ $status }}"
                    @selected($attendance->attendance_code==$status)>
                    {{ $status }}
                </option>
                @endforeach
            </select>
        </div> --}}
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
        <div class="col-12 mb-3">
            <label class="form-label">
                Alasan Perubahan
            </label>
            <textarea
                class="form-control"
                rows="3"
                name="notes">{{ $attendance->edit_reason }}</textarea>
        </div>
    </div>
    <div class="text-end">
        <button type="submit" class="btn btn-dark">
            <i class="ti ti-device-floppy"></i>
            Simpan Perubahan
        </button>
    </div>
</form>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%',
            allowClear: true
        });
    });
</script>