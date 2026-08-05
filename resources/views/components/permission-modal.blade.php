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
                        <label>Jenis</label>

                        <select name="status" class="form-select">
                            <option value="permission">Izin</option>
                            <option value="sick">Sakit</option>
                            <option value="leave">Cuti</option>
                            <option value="business_trip">Dinas Luar</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Alasan</label>
                        <textarea
                            name="reason"
                            class="form-control"
                            rows="4"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Lampiran (Opsional)</label>
                        <input
                            type="file"
                            name="attachment"
                            class="form-control">
                    </div>

                </div>

                <div class="modal-footer">

                    <button class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button class="btn btn-warning">
                        Ajukan Izin
                    </button>

                </div>

            </div>

        </form>
    </div>
</div>