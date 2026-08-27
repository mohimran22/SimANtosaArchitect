<form
    action="{{ route('projects.build-termin.store', $project->id) }}"
    method="POST"
    id="build-termin-form"
>
    @csrf

    @php
        $offerTotal = (float) ($project->offer?->grand_total ?? 0);
    @endphp

    <div class="mb-4">
        <label class="form-label fw-bold">
            Total Penawaran Build
        </label>

        <input
            type="text"
            class="form-control"
            value="Rp {{ number_format($offerTotal, 0, ',', '.') }}"
            readonly
        >

        <input
            type="hidden"
            id="build-offer-total"
            value="{{ $offerTotal }}"
        >
    </div>

    <div id="termin-container">

        <div class="row g-2 mb-2 termin-row">

            <div class="col-md-2">
                <label class="form-label">
                    Termin
                </label>

                <input
                    type="text"
                    class="form-control termin-no"
                    value="1"
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
                    placeholder="Contoh: 30"
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
                    value="Rp 0"
                    readonly
                >
            </div>


            <div class="col-md-3">
                <label class="form-label">
                    Keterangan
                </label>

                <input
                    type="text"
                    name="description[]"
                    class="form-control"
                    placeholder="Contoh: DP"
                >
            </div>


            <div class="col-md-1 d-flex align-items-end">
                <button
                    type="button"
                    class="btn btn-dark btn-remove-termin"
                    title="Hapus Termin"
                >
                    <i class="ti ti-trash"></i>
                </button>
            </div>

        </div>

    </div>


    <div class="mt-3">
        <button
            type="button"
            id="btn-add-termin"
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
            <strong id="total-termin-percentage">
                0%
            </strong>
        </div>
    </div>


    <div class="row mt-2">
        <div class="col-md-6">
            <strong>Total Nominal</strong>
        </div>

        <div class="col-md-6 text-end">
            <strong id="total-termin-amount">
                Rp 0
            </strong>
        </div>
    </div>


    <div
        id="termin-warning"
        class="alert alert-warning mt-3 d-none"
    >
        Total persentase termin harus tepat 100%.
    </div>


    <div class="mt-4">
        <button
            type="submit"
            class="btn btn-dark"
            id="btn-save-termin"
        >
            <i class="ti ti-device-floppy"></i>
            Simpan Setting Termin
        </button>
    </div>

</form>

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('build-termin-form');

    if (!form) {
        return;
    }

    const container = document.getElementById('termin-container');
    const addButton = document.getElementById('btn-add-termin');
    const offerTotalInput = document.getElementById('build-offer-total');

    const totalPercentageElement =
        document.getElementById('total-termin-percentage');

    const totalAmountElement =
        document.getElementById('total-termin-amount');

    const warningElement =
        document.getElementById('termin-warning');

    const saveButton =
        document.getElementById('btn-save-termin');


    const offerTotal = parseFloat(
        offerTotalInput?.value || 0
    );


    function formatRupiah(value) {

        value = Number(value) || 0;

        return 'Rp ' + new Intl.NumberFormat('id-ID', {
            maximumFractionDigits: 0
        }).format(value);
    }


    function getRows() {

        return container.querySelectorAll(
            '.termin-row'
        );
    }


    function updateTerminNumbers() {

        const rows = getRows();

        rows.forEach((row, index) => {

            const numberInput =
                row.querySelector('.termin-no');

            if (numberInput) {
                numberInput.value = index + 1;
            }

        });
    }


    function calculateTermin() {

        const rows = getRows();

        let totalPercentage = 0;
        let totalAmount = 0;


        rows.forEach(row => {

            const percentageInput =
                row.querySelector('.termin-percentage');

            const amountInput =
                row.querySelector('.termin-amount');


            const percentage =
                parseFloat(
                    percentageInput?.value || 0
                ) || 0;


            const amount =
                offerTotal * (percentage / 100);


            totalPercentage += percentage;
            totalAmount += amount;


            if (amountInput) {
                amountInput.value =
                    formatRupiah(amount);
            }

        });


        totalPercentageElement.textContent =
            totalPercentage.toFixed(2) + '%';


        totalAmountElement.textContent =
            formatRupiah(totalAmount);


        const isValid =
            Math.abs(totalPercentage - 100) < 0.01;


        if (isValid) {

            warningElement.classList.add('d-none');

            saveButton.disabled = false;

        } else {

            warningElement.classList.remove('d-none');

            saveButton.disabled = true;

        }

    }


    function createTerminRow() {

        const row = document.createElement('div');

        row.className =
            'row g-2 mb-2 termin-row';


        row.innerHTML = `

            <div class="col-md-2">

                <label class="form-label">
                    Termin
                </label>

                <input
                    type="text"
                    class="form-control termin-no"
                    value="1"
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
                    placeholder="Contoh: 30"
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
                    value="Rp 0"
                    readonly
                >

            </div>


            <div class="col-md-3">

                <label class="form-label">
                    Keterangan
                </label>

                <input
                    type="text"
                    name="description[]"
                    class="form-control"
                    placeholder="Contoh: Pelaksanaan Struktur"
                >

            </div>


            <div class="col-md-1 d-flex align-items-end">

                <button
                    type="button"
                    class="btn btn-dark btn-remove-termin"
                    title="Hapus Termin"
                >
                    <i class="ti ti-trash"></i>
                </button>

            </div>

        `;

        return row;
    }


    addButton.addEventListener('click', function () {

        const row = createTerminRow();

        container.appendChild(row);

        updateTerminNumbers();

        calculateTermin();

    });


    container.addEventListener('click', function (event) {

        const removeButton =
            event.target.closest(
                '.btn-remove-termin'
            );


        if (!removeButton) {
            return;
        }


        const rows = getRows();


        // Minimal harus ada 1 termin
        if (rows.length <= 1) {

            return;

        }


        const row =
            removeButton.closest('.termin-row');


        if (row) {

            row.remove();

            updateTerminNumbers();

            calculateTermin();

        }

    });


    container.addEventListener('input', function (event) {

        if (
            event.target.classList.contains(
                'termin-percentage'
            )
        ) {

            calculateTermin();

        }

    });


    form.addEventListener('submit', function (event) {

        const rows = getRows();

        let totalPercentage = 0;


        rows.forEach(row => {

            const input =
                row.querySelector(
                    '.termin-percentage'
                );

            totalPercentage +=
                parseFloat(input?.value || 0) || 0;

        });


        if (
            Math.abs(totalPercentage - 100) > 0.01
        ) {

            event.preventDefault();

            warningElement.classList.remove(
                'd-none'
            );

            alert(
                'Total persentase termin harus tepat 100%.'
            );

            return;

        }

    });


    // Initial calculation
    updateTerminNumbers();

    calculateTermin();

});
</script>
@endpush