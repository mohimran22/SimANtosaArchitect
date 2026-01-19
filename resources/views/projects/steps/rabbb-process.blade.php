<form action="{{ route('projects.offer.store') }}" method="POST">
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

    <h4 class="fw-bold mb-3">Informasi Pembuatan</h4>

    <div class="row mb-3">
        <div class="col-md-4">
            <label>Nama Customer</label>
            <input type="text" name="contact_name" value="{{ $project->customer->user->fullname }}" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label>Lokasi Pekerjaan</label>
            <input type="text" name="contact_name" value="{{ $project->city->name ?? '-' }}" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label>Durasi Pekerjaan</label>
            <input type="text" name="contact_name" class="form-control" required>
        </div>
        {{-- <div class="col-md-4">
            <label>Nomor Penawaran</label>
            <input type="text" name="offer_number" class="form-control" value="{{ old('offer_number') ?? '' }}" placeholder="Auto Generate" readonly>
        </div> --}}
        {{-- <div class="col-md-4">
            <label>Tanggal Penawaran</label>
            <input type="date" name="offer_date" class="form-control">
        </div> --}}
    </div>

    <div class="row mb-4 mt-4">
        <div class="col-md-4">
            <label class="form-label">Pilih Pekerjaan</label>
            <select name="rab_category_id" class="form-select select2" id="jobCategorySelect" required>
                <option value="">-- Pilih Paket --</option>
                @foreach($jobCategories as $package)
                    <option value="{{ $package->kode_urut }}">{{ $package->nama_pekerjaan }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Satuan</label>
            <input type="text" name="satuan" class="form-control" id="satuan" readonly>
        </div>
        <div class="col-md-2">
            <label class="form-label">Volume</label>
            <input type="text" name="volume" class="form-control">
        </div>

        <div class="col-md-2">
            <label class="form-label">Harga Satuan (Rp)</label>
            <input type="hidden" name="base_unit_price" id="priceMeter">
                <input type="text" id="priceMeterFormatted"
            class="form-control bg-light" readonly>
        </div>
        <div class="col-md-2">
            <label class="form-label">Total Harga (Rp)</label>
            <input type="hidden" name="total_price" id="totalPrice">
                <input type="text" id="totalPriceFormatted"
            class="form-control bg-light" readonly>
        </div>
    </div>

        <div class="row mb-4">
            <h4 class="fw-bold mb-3">Rencana Anggaran Biaya</h4>

            <table class="table table-bordered align-middle" id="offerItemsTable">
                <thead>
                    <tr>
                        <th width="50">No.</th>
                        <th>Uraian Pekerjaan</th>
                        <th>Satuan</th>
                        <th>Volume</th>
                        <th>Harga Satuan (Rp)</th>
                        <th>Total Harga</th>
                        
                    </tr>
                </thead>

                <tbody id="offerItemsBody">
                </tbody>

                <tfoot>
                    <tr>
                        <th colspan="5" class="text-end">SUBTOTAL</th>
                        <th id="subtotalDisplay">Rp 0</th>
                    </tr>

                    <tr>
                        <th colspan="5" class="text-end">DISCOUNT</th>
                        <th>
                            <input type="number" class="form-control"
                                name="discount" id="discount" value="0">
                        </th>
                    </tr>

                    <tr>
                        <th colspan="5" class="text-end">SUBTOTAL AFTER DISCOUNT</th>
                        <th id="subAfterDiscountDisplay">Rp 0</th>
                    </tr>

                    <tr>
                        <th colspan="5" class="text-end">TAX RATE (%)</th>
                        <th>
                            <input type="number" class="form-control"
                                name="tax_rate" id="tax_rate" value="0">
                        </th>
                    </tr>

                    <tr>
                        <th colspan="5" class="text-end">TOTAL TAX</th>
                        <th id="totalTaxDisplay">Rp 0</th>
                    </tr>

                    <tr>
                        <th colspan="5" class="text-end">SHIPPING / HANDLING</th>
                        <th>
                            <input type="number" class="form-control"
                                name="shipping" id="shipping" value="0">
                        </th>
                    </tr>

                    <tr>
                        <th colspan="5" class="text-end">GRAND TOTAL</th>
                        <th id="grandTotalDisplay">Rp 0</th>
                    </tr>
                </tfoot>
            </table>


        </div>

    <h4 class="fw-bold mb-3">Keterangan</h4>

    <textarea name="notes" rows="5" class="form-control"></textarea>

    <div class="mt-4">
        <button class="btn btn-dark">Simpan Penawaran</button>
    </div>
</form>

@push('js')
<script>
$(document).ready(function () {
    console.log('SCRIPT OFFER LOADED');
    $('.select2').select2({
        width: '100%'
    });

    const priceMeterInput = $('#priceMeter');
    const priceMeterFormatted = $('#priceMeterFormatted');
    const volumeInput = $('input[name="volume"]');
    const satuanInput = $('#satuan');
    const totalPriceInput = $('#totalPrice');
    const totalPriceFormatted = $('#totalPriceFormatted');
    const tableBody = $('#offerItemsBody');

    const discountInput = $('#discount');
    const taxRateInput = $('#tax_rate');
    const shippingInput = $('#shipping');

    function formatRp(num) {
        num = parseFloat(num) || 0;
        return 'Rp ' + num.toLocaleString('id-ID');
    }

    // ✅ EVENT SELECT2 (INI KUNCI)
    $('#jobCategorySelect').on('select2:select', function (e) {
        const categoryId = e.params.data.id;
        console.log('SELECTED ID =', categoryId);

        console.log('CHANGE FIRED, ID =', categoryId);
        if (!categoryId) return;

        fetch(`/job-categories/json/${categoryId}`)
            .then(res => res.json())
            .then(data => {
                    setTimeout(() => {
        $('#satuan').val(data.satuan ?? '-');

        $('#priceMeter').val(data.grand_total ?? 0);
        $('#priceMeterFormatted').val(
            'Rp ' + Number(data.grand_total ?? 0).toLocaleString('id-ID')
        );
    }, 100);

                // SATUAN
                // satuanInput.val(data.satuan ?? '-');

                // // HARGA SATUAN
                // priceMeterInput.val(data.grand_total ?? 0);
                // priceMeterFormatted.val(formatRp(data.grand_total ?? 0));

                // RESET TABLE
                tableBody.empty();

                const categoryLabel = {
                    labor: 'TENAGA KERJA',
                    product: 'BAHAN',
                    equipment: 'PERALATAN'
                };

                let grouped = {};
                data.items.forEach(item => {
                    grouped[item.category] ??= [];
                    grouped[item.category].push(item);
                });

                let idx = 0;

                Object.keys(grouped).forEach(cat => {

                    tableBody.append(`
                        <tr class="table-secondary">
                            <td></td>
                            <td class="fw-bold">${categoryLabel[cat]}</td>
                            <td colspan="4"></td>
                        </tr>
                    `);

                    grouped[cat].forEach(item => {
                        tableBody.append(`
                            <tr>
                                <td></td>
                                <td>
                                    - ${item.nama_group}
                                    
                                <input type="hidden" name="items[${idx}][item_id]" value="${item.id}">
                                <input type="hidden" name="items[${idx}][category]" value="${cat}">
                                <input type="hidden" name="items[${idx}][name]" value="${item.name}">
                                <input type="hidden" name="items[${idx}][unit]" value="${item.unit}">
                                <input type="hidden" name="items[${idx}][coefisien]" value="${item.coefisien}">
                                <input type="hidden" name="items[${idx}][price]" value="${item.base_unit_price}">
                                </td>
                                <td>${item.satuan}</td>
                                <td>${item.overhead_percent}</td>
                                <td>${formatRp(item.subtotal)}</td>
                                <td>${formatRp(item.grand_total)}</td>
                            </tr>
                        `);
                        idx++;
                    });
                });

                hitungTotal();
            });
    });

    function hitungTotal() {
        let volume = parseFloat(volumeInput.val()) || 0;
        let price = parseFloat(priceMeterInput.val()) || 0;

        if (volume > 0 && volume < 100) volume = 100;

        const subtotal = volume * price;

        totalPriceInput.val(subtotal);
        totalPriceFormatted.val(formatRp(subtotal));

        $('#subtotalDisplay').text(formatRp(subtotal));

        const discount = parseFloat(discountInput.val()) || 0;
        const afterDiscount = subtotal - discount;
        $('#subAfterDiscountDisplay').text(formatRp(afterDiscount));

        const taxRate = parseFloat(taxRateInput.val()) || 0;
        const tax = afterDiscount * (taxRate / 100);
        $('#totalTaxDisplay').text(formatRp(tax));

        const shipping = parseFloat(shippingInput.val()) || 0;
        $('#grandTotalDisplay').text(formatRp(afterDiscount + tax + shipping));
    }

    volumeInput.add(discountInput)
        .add(taxRateInput)
        .add(shippingInput)
        .on('input', hitungTotal);
});
</script>
@endpush