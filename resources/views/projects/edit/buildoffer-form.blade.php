@php
    $offer = $project->offer;
@endphp

<div class="card mb-4">
    <div class="card-header fw-bold"> Edit Data Penawaran</div>
    <div class="card-body">
        <form action="{{ route('offer-build.update', $offer->id) }}" method="POST">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <input type="hidden" name="project_id" value="{{ $project->id }}">

            <h4 class="fw-bold mb-3">Informasi Penawaran</h4>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label>Nomor Penawaran</label>
                    <input type="text" name="offer_number" 
                        class="form-control"
                        value="{{ old('offer_number', $offer->offer_number) }}"
                        readonly>
                </div>

                <div class="col-md-4">
                    <label>Tanggal Penawaran</label>
                    <input type="date" name="offer_date" class="form-control"
                        value="{{ old('offer_date', $offer->offer_date) }}">
                </div>

                <div class="col-md-4">
                    <label>Nama Customer</label>
                    <input type="text" name="contact_name"
                        class="form-control" readonly
                        value="{{ old('contact_name', $offer->contact_name) }}">
                </div>
            </div>

            <div class="row mb-4 mt-4">
                <div class="col-md-4">
                    <label class="form-label">Pilih RAB</label>

                    <select name="rab_process_id"
                            id="rab_process_id"
                            class="form-select select2"
                            required>

                        <option value="">-- pilih RAB --</option>

                        @foreach($rabProcesses as $rab)
                            <option value="{{ $rab->id }}"
                                {{ old('rab_process_id', $offer->rab_process_id) == $rab->id ? 'selected' : '' }}>
                                
                                {{ $rab->project->project_name ?? '-' }}
                                —
                                {{ $rab->project->customer->user->fullname ?? '-' }}

                            </option>
                        @endforeach

                    </select>
                </div>
            </div>

            <div class="row mb-4">

                <h4 class="fw-bold mb-3">Rincian Pekerjaan</h4>

                <table class="table table-bordered align-middle">

                    <thead>
                        <tr>
                            <th width="50">NO</th>
                            <th>URAIAN PEKERJAAN</th>
                            <th>SAT</th>
                            <th>VOL</th>
                            <th>HARGA SATUAN</th>
                            <th>JUMLAH HARGA</th>
                        </tr>
                    </thead>

                    <tbody id="buildItemsBodyEdit">
                    </tbody>

                    <tfoot>

                        <tr>
                            <th colspan="5" class="text-end">
                                SUBTOTAL
                            </th>
                            <th id="subtotalDisplayBuild">
                                Rp 0
                            </th>
                        </tr>

                        <tr>
                            <th colspan="5" class="text-end">
                                DISCOUNT
                            </th>

                            <th>
                                <input
                                    type="text"
                                    class="form-control rupiah"
                                    id="discount_display_build"
                                    readonly
                                >

                                <input
                                    type="hidden"
                                    name="discount"
                                    id="discount_build"
                                >
                            </th>
                        </tr>

                        <tr>
                            <th colspan="5" class="text-end">
                                SUBTOTAL AFTER DISCOUNT
                            </th>

                            <th id="subAfterDiscountDisplayBuild">
                                Rp 0
                            </th>
                        </tr>

                        <tr>
                            <th colspan="5" class="text-end">
                                TAX RATE (%)
                            </th>

                            <th>
                                <input
                                    type="number"
                                    class="form-control"
                                    name="tax_rate"
                                    id="tax_rate_build"
                                    readonly
                                >
                            </th>
                        </tr>

                        <tr>
                            <th colspan="5" class="text-end">
                                TOTAL TAX
                            </th>

                            <th id="totalTaxDisplayBuild">
                                Rp 0
                            </th>
                        </tr>

                        <tr>
                            <th colspan="5" class="text-end">
                                SHIPPING / HANDLING
                            </th>

                            <th>
                                <input
                                    type="text"
                                    class="form-control rupiah"
                                    id="shipping_display_build"
                                    readonly
                                >

                                <input
                                    type="hidden"
                                    name="shipping"
                                    id="shipping_build"
                                >
                            </th>
                        </tr>

                        {{-- GRAND TOTAL RAB --}}
                        <tr>
                            <th colspan="5" class="text-end">
                                GRAND TOTAL
                            </th>

                            <th id="rabGrandTotalDisplayBuild">
                                Rp 0
                            </th>
                        </tr>

                        {{-- PEMBULATAN --}}
                        <tr>
                            <th colspan="5" class="text-end fw-bold">
                                DIBULATKAN
                            </th>

                            <th
                                id="roundedTotalDisplayBuild"
                                class="fw-bold"
                            >
                                Rp 0
                            </th>
                        </tr>

                        {{-- EXTRA DISCOUNT --}}
                        <tr>
                            <th colspan="5" class="text-end">
                                EXTRA DISCOUNT
                            </th>

                            <th>
                                <input
                                    type="text"
                                    class="form-control rupiah"
                                    id="extra_discount_display_build"
                                    value="Rp 0"
                                >

                                <input
                                    type="hidden"
                                    name="extra_discount"
                                    id="extra_discount_build"
                                    value="0"
                                >
                            </th>
                        </tr>

                        {{-- GRAND TOTAL PENAWARAN --}}
                        <tr>
                            <th colspan="5" class="text-end fw-bold">
                                GRAND TOTAL PENAWARAN
                            </th>

                            <th
                                id="grandTotalDisplayBuild"
                                class="fw-bold"
                            >
                                Rp 0
                            </th>
                        </tr>

                    </tfoot>

                </table>
                <input type="hidden" name="subtotal" id="subtotal_build">
                <input type="hidden" name="subtotal_after_discount" id="subtotal_after_discount_build">
                <input type="hidden" name="tax_total" id="tax_total_build">
                <input type="hidden" name="grand_total" id="grand_total_build">
            </div>

            <h4 class="fw-bold mb-3">Keterangan</h4>
            <textarea name="notes" rows="5" class="form-control">{{ $offer->notes }}</textarea>

            <div class="mt-4">
                <button class="btn btn-dark">Update Penawaran</button>
                <button type="button" class="btn btn-secondary btn-cancel">
                    <i class="ti ti-x"></i> Batal
                </button>
            </div>
        </form>
    </div>
</div>
@push('js')
<script>

    function formatRupiahBuild(n){
        return 'Rp ' + Number(n || 0).toLocaleString('id-ID')
    }
    function parseRupiahBuild(value) {

        if (value === null || value === undefined) {
            return 0;
        }

        if (typeof value === 'number') {
            return value;
        }

        let str = String(value)
            .replace(/Rp/gi, '')
            .replace(/\s/g, '')
            .replace(/\./g, '')
            .replace(',', '.');

        const number = parseFloat(str);

        return isNaN(number) ? 0 : number;
    }
    function formatNumberBuild(n){
        return Number(n || 0).toLocaleString('id-ID', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        })
    }

    function setRupiahBuild(selector,val){
        $(selector).val(formatRupiahBuild(val))
    }
    function numberToLettersBuild(num){
        let letters = ''
        num = num + 1

        while(num > 0){
            let rem = (num - 1) % 26
            letters = String.fromCharCode(65 + rem) + letters
            num = Math.floor((num - 1) / 26)
        }

        return letters
    }

    function loadRabItemsEdit() {

        let rabId = $('#rab_process_id').val();

        if (!rabId) return;

        $.get(`/rab-process/${rabId}/items`, function(res) {
            const tbody = $('#buildItemsBodyEdit');

            tbody.empty();

            const grouped = {};

            res.items.forEach(item => {

                const floor = item.floor_name ?? 'Tanpa Lantai';
                const category = item.category_name ?? 'Tanpa Kategori';

                if (!grouped[floor]) {
                    grouped[floor] = {};
                }

                if (!grouped[floor][category]) {
                    grouped[floor][category] = [];
                }

                grouped[floor][category].push(item);
            });
            
            let categoryIndex = 0;
            Object.entries(grouped).forEach(([floor, categories]) => {

                tbody.append(`
                    <tr class="table-secondary fw-bold">
                        <td colspan="6">
                            ${floor}
                        </td>
                    </tr>
                `);


                Object.entries(categories).forEach(
                    ([categoryName, items]) => {

                        const categoryLetter =
                            numberToLettersBuild(categoryIndex);


                        // CATEGORY
                        tbody.append(`
                            <tr class="table-secondary">
                                <td style="font-weight:600" colspan="6">
                                    ${categoryLetter}. ${categoryName}
                                </td>
                            </tr>
                        `);

                        let itemNo = 1;
                        let lastDescription = null;

                        items.forEach(item => {

                            const description = (item.description ?? '').trim();

                            let showNumber = false;

                            if (description === '') {

                                showNumber = true;

                            } else if (description !== lastDescription) {

                                showNumber = true;

                            }

                            const currentNo = itemNo;

                            if (showNumber) {
                                itemNo++;
                            }

                            lastDescription = description;

                            const total =
                                item.total ??
                                (
                                    (parseFloat(item.volume) || 0) *
                                    (parseFloat(item.price) || 0)
                                );

                            const descriptionHtml = description
                                ? `
                                    <br>
                                    <span style="
                                        font-size:11px;
                                        color:#666;
                                    ">
                                        ${description}
                                    </span>
                                `
                                : '';

                            tbody.append(`
                                <tr>

                                    <td class="text-center">
                                        ${showNumber ? currentNo : ''}
                                    </td>

                                    <td style="padding-left:30px">
                                        ${item.job_name ?? ''}
                                        ${descriptionHtml}
                                    </td>

                                    <td class="text-center">
                                        ${item.satuan ?? ''}
                                    </td>

                                    <td class="text-end">
                                        ${formatNumberBuild(item.volume)}
                                    </td>

                                    <td class="text-end">
                                        ${formatRupiahBuild(item.price)}
                                    </td>

                                    <td class="text-end">
                                        ${formatRupiahBuild(total)}
                                    </td>

                                </tr>
                            `);

                        });
                        categoryIndex++;
                    }
                );

            });

            $('#tax_rate_build').val(res.header.tax_rate);

            setRupiahBuild(
                '#discount_display_build',
                res.header.discount
            );

            setRupiahBuild(
                '#shipping_display_build',
                res.header.shipping
            );
            const extraDiscount =
                parseFloat(res.header.extra_discount) || 0;

            $('#extra_discount_build').val(extraDiscount);

            setRupiahBuild(
                '#extra_discount_display_build',
                extraDiscount
            );
            $('#discount_build').val(res.header.discount);
            $('#shipping_build').val(res.header.shipping);

            $('#subtotalDisplayBuild')
                .data('value', parseFloat(res.header.subtotal) || 0)
                .text(formatRupiahBuild(res.header.subtotal));

            $('#subAfterDiscountDisplayBuild')
                .text(formatRupiahBuild(
                    res.header.subtotal_after_discount
                ));

            $('#totalTaxDisplayBuild')
                .text(formatRupiahBuild(
                    res.header.tax_total
                ));

            $('#grandTotalDisplayBuild')
                .text(formatRupiahBuild(
                    res.header.grand_total
                ));
            calculateOfferTotal();
        }).fail(function(xhr) {

            console.error(
                'Gagal mengambil data RAB:',
                xhr.responseText
            );

        });
    }

    function calculateOfferTotal() {

        const subtotal = parseFloat($('#subtotalDisplayBuild').data('value')) || 0;

        const discount = parseFloat($('#discount_build').val()) || 0;

        const extraDiscount = parseFloat($('#extra_discount_build').val()) || 0;

        const taxRate = parseFloat($('#tax_rate_build').val()) || 0;

        const shipping = parseFloat($('#shipping_build').val()) || 0;

        const subtotalAfterDiscount = subtotal - discount;

        const taxTotal = subtotalAfterDiscount * (taxRate / 100);

        const grandTotalRab = subtotalAfterDiscount + taxTotal + shipping;

        const roundedTotal = Math.floor(grandTotalRab / 1000000) * 1000000;

        const grandTotalOffer = Math.max(0,roundedTotal - extraDiscount);
        $('#subAfterDiscountDisplayBuild').text(formatRupiahBuild(subtotalAfterDiscount));

        $('#totalTaxDisplayBuild').text(formatRupiahBuild(taxTotal));

        $('#rabGrandTotalDisplayBuild').text(formatRupiahBuild(grandTotalRab));

        $('#roundedTotalDisplayBuild').text(formatRupiahBuild(roundedTotal));

        $('#grandTotalDisplayBuild').text(formatRupiahBuild(grandTotalOffer));
    }

    $('#extra_discount_display_build').on('input', function () {

        const value = parseRupiahBuild($(this).val());

        $('#extra_discount_build').val(value);

        calculateOfferTotal();
    });
    $('#rab_process_id').on(
        'change',
        loadRabItemsEdit
    );

    $(document).ready(function() {

        if ($('#rab_process_id').val()) {
            loadRabItemsEdit();
        }

    });

</script>
@endpush