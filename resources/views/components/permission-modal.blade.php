<div class="modal fade" id="izinModal">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('attendances.permission.store') }}"
              enctype="multipart/form-data">

            @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Ajukan Izin</h5>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>

                        <input type="date"
                            name="attendance_date"
                            class="form-control"
                            value="{{ now()->toDateString() }}"
                            required>
                    </div>
                    <div class="mb-3">
                        <label>Jenis</label>

                        <select name="request_type" class="form-select">
                            <option value="permission">Izin</option>
                            <option value="sick">Sakit</option>
                            <option value="leave">Cuti</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Alasan</label>
                        <textarea
                            name="reason"
                            class="form-control"
                            rows="4" required></textarea>
                    </div>

                    {{-- <div class="mb-3">
                        <label>Lampiran (Opsional)</label>
                        <input
                            type="file"
                            name="attachment"
                            class="form-control"
                            accept=".jpg,.jpeg,.png,.pdf">
                    </div> --}}

                </div>

                <div class="modal-footer">

                    <button class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit" class="btn btn-warning">
                        <i class="ti ti-send me-1"></i>
                        Kirim Pengajuan Izin
                    </button>

                </div>

            </div>

        </form>
    </div>
</div>