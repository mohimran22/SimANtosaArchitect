<button type="button"
    class="btn btn-secondary mb-4 btn-back-history"
    data-employee="{{ $attendance->employee_id }}">

    <i class="ti ti-arrow-left"></i>
    Kembali

</button>

<div class="alert alert-danger">

    <h3 class="mb-2">
        <i class="ti ti-alert-triangle"></i>

        Konfirmasi Hapus Absensi

    </h3>

    Data ini akan dihapus.

</div>

<div class="card">

<div class="card-body">

<div class="row">

<div class="col-md-6">

<label class="form-label">
Tanggal
</label>

<div>

{{ $attendance->attendance_date->translatedFormat('d F Y') }}

</div>

</div>

<div class="col-md-3">

<label class="form-label">
Masuk
</label>

<div>

{{ optional($attendance->check_in)->format('H:i') }}

</div>

</div>

<div class="col-md-3">

<label class="form-label">
Pulang
</label>

<div>

{{ optional($attendance->check_out)->format('H:i') }}

</div>

</div>

</div>

<hr>

<form id="attendanceDeleteForm">

<input
type="hidden"
id="delete_attendance_id"
value="{{ $attendance->id }}">

<div class="mb-3">

<label class="form-label">

Alasan Penghapusan

</label>

<textarea
class="form-control"
name="reason"
rows="4"
required
placeholder="Masukkan alasan penghapusan..."></textarea>

</div>

<div class="text-end">

<button
type="submit"
class="btn btn-danger">

<i class="ti ti-trash"></i>

Hapus Absensi

</button>

</div>

</form>

</div>

</div>