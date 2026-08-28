<form
    action="{{ route('projects.build-termin.update', $project->id) }}"
    method="POST"
    id="build-termin-edit-form"
>
    @csrf
    @method('PUT')

    <div class="mb-4">

        <label class="form-label fw-bold">
            Total Penawaran Build
        </label>

        <input
            type="text"
            class="form-control"
            value="Rp {{ number_format($project->offer->grand_total, 0, ',', '.') }}"
            readonly
        >

    </div>


    <div id="termin-edit-container">

        @foreach(
            $project->buildTermins->sortBy('termin_no')
            as $termin
        )

            <div class="row g-2 mb-2 termin-row">

                <div class="col-md-2">

                    <label class="form-label">
                        Termin
                    </label>

                    <input
                        type="number"
                        name="termin_no[]"
                        class="form-control"
                        value="{{ $termin->termin_no }}"
                        readonly
                    >

                </div>


                <div class="col-md-3">

                    <label class="form-label">
                        Persentase (%)
                    </label>

                    <input
                        type="number"
                        name="percentage[]"
                        class="form-control termin-percentage"
                        min="0"
                        max="100"
                        step="0.01"
                        value="{{ $termin->percentage }}"
                        required
                    >

                </div>


                <div class="col-md-3">

                    <label class="form-label">
                        Nominal
                    </label>

                    <input
                        type="text"
                        class="form-control termin-amount"
                        value="Rp {{ number_format($termin->amount, 0, ',', '.') }}"
                        readonly
                    >

                </div>


                <div class="col-md-3">

                    <label class="form-label">
                        Keterangan
                    </label>

                    <input
                        type="text"
                        name="termin_description[]"
                        class="form-control"
                        value="{{ $termin->description }}"
                        placeholder="Keterangan"
                    >

                </div>


                <div class="col-md-1 d-flex align-items-end">

                    <button
                        type="button"
                        class="btn btn-danger btn-remove-termin"
                    >
                        <i class="ti ti-trash"></i>
                    </button>

                </div>

            </div>

        @endforeach

    </div>


    <div class="mt-3">

        <button
            type="button"
            id="btn-add-edit-termin"
            class="btn btn-outline-dark"
        >
            <i class="ti ti-plus"></i>
            Tambah Termin
        </button>

    </div>


    <hr>


    <div class="row">

        <div class="col-md-6">
            <strong>Total Persentase</strong>
        </div>

        <div class="col-md-6 text-end">

            <strong id="total-edit-termin-percentage">
                0%
            </strong>

        </div>

    </div>


    <div class="row mt-2">

        <div class="col-md-6">
            <strong>Total Nominal</strong>
        </div>

        <div class="col-md-6 text-end">

            <strong id="total-edit-termin-amount">
                Rp 0
            </strong>

        </div>

    </div>


    <div class="mt-4">

        <button type="submit" class="btn btn-dark">
            <i class="ti ti-device-floppy"></i>
            Simpan Perubahan
        </button>
        <button type="button" class="btn btn-secondary btn-cancel">
            <i class="ti ti-x"></i> Batal
        </button>
    </div>

</form>