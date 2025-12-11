<form action="{{ route('projects.offers.store') }}" method="POST">
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
            <label>Nomor Penawaran</label>
            <input type="text" name="offer_number" class="form-control">
        </div>
        <div class="col-md-4">
            <label>Tanggal Penawaran</label>
            <input type="date" name="offer_date" class="form-control">
        </div>
        <div class="col-md-4">
            <label>Nama Customer</label>
            <input type="text" name="contact_name" value="{{ $project->customer->user->fullname }}" class="form-control">
        </div>
    </div>

    {{-- <div class="mb-3">
        <label>Alamat / Lokasi</label>
        <input type="text" name="kepada_alamat" class="form-control" value="{{ $project->city->name ?? '' }}">
    </div>

    <div class="mb-3">
        <label>Jenis Pekerjaan</label>
        <input type="text" name="jenis_pekerjaan" class="form-control" value="{{ $project->project_name ?? '' }}">
    </div>

    <div class="mb-3">
        <label>Lokasi Pekerjaan</label>
        <input type="text" name="lokasi" class="form-control" value="{{ $project->project_location ?? '' }}">
    </div> --}}

    <div class="row mb-4 mt-4">
        <div class="col-md-4">
            <label class="form-label">Pilih Paket Desain</label>
            <select name="design_package_id" class="form-select" id="designPackageSelect" required>
                <option value="">-- Pilih Paket --</option>
                @foreach($designPackages as $package)
                    <option value="{{ $package->id }}">{{ $package->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Volume</label>
            <input type="text" name="volume" class="form-control">
        </div>
        <div class="col-md-2">
            <label class="form-label">Satuan</label>
            <input type="text" name="satuan" class="form-control" id="satuan" readonly>
        </div>
        <div class="col-md-2">
            <label class="form-label">Harga Satuan (Rp)</label>
            <input type="hidden" name="price_meter" id="priceMeter">
            <span id="priceMeterFormatted" class="form-control bg-light"></span>

        </div>
        <div class="col-md-2">
            <label class="form-label">Total Harga (Rp)</label>
            <input type="hidden" name="total_price" id="totalPrice">
            <span id="totalPriceFormatted" class="form-control bg-light"></span>
        </div>
    </div>

    <div class="row mb-4">

            <h4 class="fw-bold mb-3">Rincian Pekerjaan</h4>

            <table class="table table-bordered align-middle" id="offerItemsTable">
                <thead>
                    <tr>
                        <th width="50">No.</th>
                        <th>Uraian Pekerjaan</th>
                        <th>Volume</th>
                        <th>Satuan</th>
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
                            <input type="number" class="form-control form-control-sm"
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
                            <input type="number" class="form-control form-control-sm"
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
                            <input type="number" class="form-control form-control-sm"
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
document.addEventListener('DOMContentLoaded', function () {

    const packageSelect = document.getElementById('designPackageSelect');
    const priceMeterInput = document.getElementById('priceMeter');
    const priceMeterFormatted = document.getElementById('priceMeterFormatted');
    const volumeInput = document.querySelector('input[name="volume"]');
    const satuanInput = document.getElementById('satuan');
    const totalPriceInput = document.getElementById('totalPrice');
    const totalPriceFormatted = document.getElementById('totalPriceFormatted');

    const tableBody = document.getElementById('offerItemsBody');

    const discountInput = document.getElementById('discount');
    const taxRateInput = document.getElementById('tax_rate');
    const shippingInput = document.getElementById('shipping');

    function formatRp(num) {
        num = parseFloat(num) || 0;
        return "Rp " + num.toLocaleString('id-ID');
    }

    // Fetch package info
    packageSelect.addEventListener('change', function () {

        let packageId = this.value;

        if (!packageId) {
            priceMeterInput.value = "";
            priceMeterFormatted.innerText = "";
            tableBody.innerHTML = "";
            hitungTotal();
            return;
        }

        fetch(`/design-packages/json/${packageId}`)
            .then(res => res.json())
            .then(data => {

                priceMeterInput.value = data.price_meter ?? 0;
                priceMeterFormatted.innerText = formatRp(data.price_meter);
                satuanInput.value = data.satuan ?? "-";

                tableBody.innerHTML = "";
                data.items.forEach((item, i) => {
                    tableBody.innerHTML += `
                        <tr>
                            <td>${i + 1}</td>
                            <td>${item.item_name}
                            <input type="hidden" name="items[${i}][item_name]" value="${item.item_name}">
                            <input type="hidden" name="items[${i}][category]" value="${item.category ?? ''}">

                            </td>
                        </tr>
                    `;
                });

                hitungTotal();
            });
    });

    // Trigger perhitungan ulang
    [volumeInput, discountInput, taxRateInput, shippingInput].forEach(input => {
        input.addEventListener('input', hitungTotal);
    });

    function hitungTotal() {

        let volume = parseFloat(volumeInput.value) || 0;
        let price = parseFloat(priceMeterInput.value) || 0;

        // 1. HITUNG SUBTOTAL (volume × harga satuan)
        let subtotal = volume * price;

        // Set total main
        totalPriceInput.value = subtotal;
        totalPriceFormatted.innerText = formatRp(subtotal);

        document.getElementById("subtotalDisplay").innerText = formatRp(subtotal);

        // 2. DISCOUNT
        let discount = parseFloat(discountInput.value) || 0;

        // 3. SUBTOTAL AFTER DISCOUNT
        let subAfterDiscount = subtotal - discount;
        document.getElementById("subAfterDiscountDisplay").innerText = formatRp(subAfterDiscount);

        // 4. TAX RATE (%)
        let taxRate = parseFloat(taxRateInput.value) || 0;
        let totalTax = subAfterDiscount * (taxRate / 100);
        document.getElementById("totalTaxDisplay").innerText = formatRp(totalTax);

        // 5. SHIPPING
        let shippingCost = parseFloat(shippingInput.value) || 0;

        // 6. GRAND TOTAL
        let grandTotal = subAfterDiscount + totalTax + shippingCost;
        document.getElementById("grandTotalDisplay").innerText = formatRp(grandTotal);
    }

});
</script>
@endpush





