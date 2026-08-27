@can('lihat daftar proyek')
<form action="{{ route('projects.offerbuild.store') }}" method="POST">
    @csrf
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
            <label class="form-label">Nomor Penawaran</label>
            <input type="text" name="offer_number" class="form-control" value="{{ old('offer_number') ?? '' }}" placeholder="AUTO GENARATE" readonly>

        </div>
        <div class="col-md-4">
            <label class="form-label required">Tanggal Penawaran</label>
            <input type="date" name="offer_date" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Nama Customer</label>
            <input type="text" name="contact_name" value="{{ $project->customer->user->fullname }}" class="form-control" readonly>
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
                    <option value="{{ $rab->id }}">
                        {{ $rab->project->project_name ?? '-' }} — {{ $rab->project->customer->user->fullname ?? '-' }}
                    </option>
                @endforeach

            </select>
        </div>
    </div>

        <div class="row mb-4">
            <h4 class="fw-bold mb-3">Rincian Pekerjaan</h4>

            <table class="table table-bordered align-middle" id="offerItemsTable">
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

                <tbody id="buildItemsBody">
                </tbody>

                <tfoot>

                    <tr>
                        <th colspan="5" class="text-end">
                            SUBTOTAL
                        </th>

                        <th id="subtotalDisplay">
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
                                id="discount_display"
                                readonly
                            >

                            <input
                                type="hidden"
                                name="discount"
                                id="discount"
                            >
                        </th>
                    </tr>

                    <tr>
                        <th colspan="5" class="text-end">
                            SUBTOTAL AFTER DISCOUNT
                        </th>

                        <th id="subAfterDiscountDisplay">
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
                                id="tax_rate"
                                readonly
                            >
                        </th>
                    </tr>

                    <tr>
                        <th colspan="5" class="text-end">
                            TOTAL TAX
                        </th>

                        <th id="totalTaxDisplay">
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
                                id="shipping_display"
                                readonly
                            >

                            <input
                                type="hidden"
                                name="shipping"
                                id="shipping"
                            >
                        </th>
                    </tr>

                    <tr>
                        <th colspan="5" class="text-end">
                            GRAND TOTAL
                        </th>

                        <th id="rabGrandTotalDisplay">
                            Rp 0
                        </th>
                    </tr>

                    <tr>
                        <th colspan="5" class="text-end fw-bold">
                            DIBULATKAN
                        </th>

                        <th
                            id="roundedTotalDisplay"
                            class="fw-bold"
                        >
                            Rp 0
                        </th>
                    </tr>

                    <tr>
                        <th colspan="5" class="text-end">
                            EXTRA DISCOUNT
                        </th>

                        <th>
                            <input
                                type="text"
                                class="form-control rupiah"
                                id="extra_discount_display"
                                value="Rp 0"
                            >

                            <input
                                type="hidden"
                                name="extra_discount"
                                id="extra_discount"
                                value="0"
                            >
                        </th>
                    </tr>

                    <tr>
                        <th colspan="5" class="text-end fw-bold">
                            GRAND TOTAL PENAWARAN
                        </th>

                        <th
                            id="grandTotalDisplay"
                            class="fw-bold"
                        >
                            Rp 0
                        </th>
                    </tr>

                </tfoot>
            </table>
        </div>

        {{-- @if(optional($rab)->notes)
            <div class="mt-4">
                <h5 class="fw-bold">Keterangan</h5>
                <div class="border p-3">{{ $rab->notes }}</div>
            </div>
        @endif --}}

    <div class="text-end mt-4">
        <button class="btn btn-dark">Simpan Penawaran</button>
    </div>
</form>
@endcan

@push('js')
<script>

    function formatRupiah(n){
        return 'Rp ' + Number(n || 0).toLocaleString('id-ID')
    }
    function parseRupiah(value) {

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
    function formatNumber(n){
        return Number(n || 0).toLocaleString('id-ID', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        })
    }

    function setRupiah(selector,val){
        $(selector).val(formatRupiah(val))
    }
    function numberToLetters(num){
        let letters = ''
        num = num + 1

        while(num > 0){
            let rem = (num - 1) % 26
            letters = String.fromCharCode(65 + rem) + letters
            num = Math.floor((num - 1) / 26)
        }

        return letters
    }

    function loadRabItems() {

        let rabId = $('#rab_process_id').val();

        console.log('RAB ID:', rabId);

        if (!rabId) {
            console.warn('RAB ID kosong');
            return;
        }

        $.get(`/rab-process/${rabId}/items`, function(res) {
            console.log('Response RAB:', res);
            const tbody = $('#buildItemsBody');

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
                            numberToLetters(categoryIndex);


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
                                        ${formatNumber(item.volume)}
                                    </td>

                                    <td class="text-end">
                                        ${formatRupiah(item.price)}
                                    </td>

                                    <td class="text-end">
                                        ${formatRupiah(total)}
                                    </td>

                                </tr>
                            `);

                        });
                        categoryIndex++;
                    }
                );

            });

            $('#tax_rate').val(res.header.tax_rate);

            setRupiah(
                '#discount_display',
                res.header.discount
            );

            setRupiah(
                '#shipping_display',
                res.header.shipping
            );

            $('#discount').val(res.header.discount);
            $('#shipping').val(res.header.shipping);

            $('#subtotalDisplay')
                .data('value', parseFloat(res.header.subtotal) || 0)
                .text(formatRupiah(res.header.subtotal));

            $('#subAfterDiscountDisplay')
                .text(formatRupiah(
                    res.header.subtotal_after_discount
                ));

            $('#totalTaxDisplay')
                .text(formatRupiah(
                    res.header.tax_total
                ));

            $('#grandTotalDisplay')
                .text(formatRupiah(
                    res.header.grand_total
                ));

        }).fail(function(xhr) {

            console.error(
                'Gagal mengambil data RAB:',
                xhr.responseText
            );

        });
    }
    $('#extra_discount').val(0);
    setRupiah('#extra_discount_display', 0);
    function calculateOfferTotal() {

        const subtotal = parseFloat($('#subtotalDisplay').data('value')) || 0;

        const discount = parseFloat($('#discount').val()) || 0;

        const extraDiscount = parseFloat($('#extra_discount').val()) || 0;

        const taxRate = parseFloat($('#tax_rate').val()) || 0;

        const shipping = parseFloat($('#shipping').val()) || 0;

        const subtotalAfterDiscount = subtotal - discount;

        const taxTotal = subtotalAfterDiscount * (taxRate / 100);

        const grandTotalRab = subtotalAfterDiscount + taxTotal + shipping;

        const roundedTotal = Math.floor(grandTotalRab / 1000000) * 1000000;

        const grandTotalOffer = Math.max(0,roundedTotal - extraDiscount);
        $('#subAfterDiscountDisplay').text(formatRupiah(subtotalAfterDiscount));

        $('#totalTaxDisplay').text(formatRupiah(taxTotal));

        $('#rabGrandTotalDisplay').text(formatRupiah(grandTotalRab));

        $('#roundedTotalDisplay').text(formatRupiah(roundedTotal));

        $('#grandTotalDisplay').text(formatRupiah(grandTotalOffer));
    }

    $('#extra_discount_display').on('input', function () {

        const value = parseRupiah($(this).val());

        $('#extra_discount').val(value);

        calculateOfferTotal();
    });
    $('#rab_process_id').on(
        'change',
        loadRabItems
    );

    $(document).ready(function() {

        if ($('#rab_process_id').val()) {
            loadRabItems();
        }

    });

</script>
@endpush