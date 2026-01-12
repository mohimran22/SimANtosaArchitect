<form action="{{ route('projects.rab.store') }}" method="POST">
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

    <h4 class="fw-bold mb-3">Informasi Pembuatan Rab</h4>

    <div class="row mb-3">
        <div class="col-md-4">
            <label>Nama Customer</label>
            <input type="text" name="contact_name" value="{{ $project->customer->user->fullname }}" class="form-control">
        </div>
        <div class="col-md-4">
            <label>Lokasi Pekerjaan</label>
            <input type="text" name="job_location" value="{{ $project->city->name ?? '-' }}" class="form-control">
        </div>
        <div class="col-md-4">
            <label>Durasi Pekerjaan</label>
            <input type="text" name="job_duration" class="form-control">
        </div>
    </div>

    <div class="row mb-4 mt-4">
        <div class="col-md-4">
            <label class="form-label">Pilih Pekerjaan</label>
                <select class="form-select select2" id="jobCategorySelect">
                    <option value="">-- Pilih Pekerjaan --</option>
                    @foreach($jobCategories as $job)
                        <option value="{{ $job->id }}">
                            {{ $job->nama_pekerjaan }}
                        </option>
                    @endforeach
                </select>

        </div>
        <div class="col-md-2">
            <label class="form-label">Volume</label>
            <input type="number" name="volume" class="form-control" step="0.01" min="0">
        </div>
        <div class="col-md-2">
            <label class="form-label">Satuan</label>
            <input type="text" name="satuan" class="form-control" id="rab_satuan" readonly>
        </div>
        <div class="col-md-2">
            <label class="form-label">Harga Satuan (Rp)</label>
            <input type="hidden" name="price_meter" id="rab_priceMeter">
            <span id="rab_priceMeterFormatted" class="form-control bg-light"></span>

        </div>
        <div class="col-md-2">
            <label class="form-label">Total Harga (Rp)</label>
            <input type="hidden" name="total_price" id="rab_totalPrice">
            <span id="rab_totalPriceFormatted" class="form-control bg-light"></span>
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
                    <th width="1%"></th>
                </tr>
            </thead>
            <tbody id="rab_offerItemsBody">
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5" class="text-end">SUBTOTAL</th>
                    <th id="rab_subtotalDisplay">Rp 0</th>
                </tr>
                <tr>
                    <th colspan="5" class="text-end">PROFIT</th>
                    <th>
                        <input type="text" class="form-control" id="rab_profit_display">
                        <input type="hidden" name="profit" id="rab_profit">
                    </th>
                </tr>
                <tr>
                    <th colspan="5" class="text-end">OVERHEAD</th>
                    <th>
                        <input type="text" class="form-control" id="rab_overhead_display">
                        <input type="hidden" name="overhead" id="rab_overhead">
                    </th>
                </tr>
                <tr>
                    <th colspan="5" class="text-end">DISCOUNT</th>
                    <th>
                        <input type="number" class="form-control" id="rab_discount_display">
                        <input type="hidden" name="discount" id="rab_discount">
                    </th>
                </tr>
                <tr>
                    <th colspan="5" class="text-end">SUBTOTAL AFTER DISCOUNT</th>
                    <th id="rab_subAfterDiscountDisplay">Rp 0</th>
                </tr>
                <tr>
                    <th colspan="5" class="text-end">TAX RATE (%)</th>
                    <th>
                        <input type="number" class="form-control"
                            name="tax_rate" id="rab_tax_rate">
                    </th>
                </tr>

                <tr>
                    <th colspan="5" class="text-end">TOTAL TAX</th>
                    <th id="rab_totalTaxDisplay">Rp 0</th>
                </tr>
                <tr>
                    <th colspan="5" class="text-end">SHIPPING / HANDLING</th>
                    <th>
                        <input type="text" class="form-control" id="rab_shipping_display">
                        <input type="hidden" name="shipping" id="rab_shipping">

                    </th>
                </tr>
                <tr>
                    <th colspan="5" class="text-end">GRAND TOTAL</th>
                    <th id="rab_grandTotalDisplay">Rp 0</th>
                </tr>
            </tfoot>
        </table>
    </div>
        <input type="hidden" name="subtotal" id="rab_subtotal">
        <input type="hidden" name="subtotal_after_discount" id="rab_subAfterDiscount">
        <input type="hidden" name="tax_total" id="rab_tax_total">
        <input type="hidden" name="grand_total" id="rab_grand_total">

    <h4 class="fw-bold mb-3">Keterangan</h4>

    <textarea name="notes" rows="3" class="form-control"></textarea>

    <div class="text-end mt-4">
        <button class="btn btn-dark">Simpan RAB</button>
    </div>
</form>

@push('js')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "-- Pilih --",
            width: '100%'
        });
    });

function formatRp(num) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(num || 0);
}
function cleanNumber(val) {
    if (!val) return 0;
    return parseFloat(
        val.toString().replace(/[^0-9.-]+/g, '')
    ) || 0;
}

let currentRabJob = null;
let rabItems = {};

// document.addEventListener('change', function(e) {

//     if (e.target.id !== 'jobCategorySelect') return;

//     const jobId = e.target.value;
//     if (!jobId) return;

//     fetch(`/job-categories/${jobId}/simple`)
//         .then(res => res.json())
//         .then(job => {

//             currentRabJob = job;

//             let harga = parseFloat(job.harga) || 0;

//             document.getElementById('rab_satuan').value = job.satuan;
//             document.getElementById('rab_priceMeter').value = harga;
//             document.getElementById('rab_priceMeterFormatted').innerText = formatRp(harga);

//             // reset volume
//             document.querySelector('input[name="volume"]').value = '';
//             document.getElementById('rab_totalPriceFormatted').innerText = '';
//         });
// });
$('#jobCategorySelect').on('change', function () {
    const jobId = $(this).val();
    if (!jobId) return;

    fetch(`/job-categories/${jobId}/simple`)
        .then(res => res.json())
        .then(job => {

            currentRabJob = job;

            let harga = parseFloat(job.harga) || 0;

            document.getElementById('rab_satuan').value = job.satuan;
            document.getElementById('rab_priceMeter').value = harga;
            document.getElementById('rab_priceMeterFormatted').innerText = formatRp(harga);

            document.querySelector('input[name="volume"]').value = '';
            document.getElementById('rab_totalPriceFormatted').innerText = '';
        });
});


document.addEventListener('input', function(e) {

    if (e.target.name !== 'volume') return;
    if (!currentRabJob) return;

    const volume = parseFloat(e.target.value) || 0;
    if (volume <= 0) return;

    const harga = parseFloat(currentRabJob.harga) || 0;

    let total = volume * harga;
    document.getElementById('rab_totalPrice').value = total;
    document.getElementById('rab_totalPriceFormatted').innerText = formatRp(total);

    const jobId = currentRabJob.id;

    rabItems[jobId] = {
        ...currentRabJob,
        volume: volume,
        harga: harga,
        total: total
    };

    document.getElementById('rab_discount_display').value = '';
    document.getElementById('rab_shipping_display').value = '';
    document.getElementById('rab_discount').value = 0;
    document.getElementById('rab_shipping').value = 0;

    renderRabTable();
});

function renderRabTable() {

    const tbody = document.getElementById('rab_offerItemsBody');
    tbody.innerHTML = '';

    let grouped = {};
    let rowIndex = 0;

    // 🔹 GROUPING
    Object.values(rabItems).forEach(item => {
        if (!grouped[item.kode_group]) {
            grouped[item.kode_group] = {
                name: item.nama_group,
                items: [],
                subtotal: 0
            };
        }

        grouped[item.kode_group].items.push(item);
        grouped[item.kode_group].subtotal += Number(item.total);
    });

    // 🔹 RENDER
    Object.keys(grouped).forEach(groupCode => {

        const group = grouped[groupCode];

        // HEADER GROUP
        tbody.insertAdjacentHTML('beforeend', `
            <tr class="table-secondary fw-bold">
                <td>${groupCode}</td>
                <td colspan="5">${group.name}</td>
            </tr>
        `);

        let no = 1;

        group.items.forEach(item => {

            tbody.insertAdjacentHTML('beforeend', `
                <tr>
                    <td>${no}</td>
                    <td>${item.nama}</td>
                    <td>
                        <input type="number" class="form-control"
                            value="${item.volume}"
                            onchange="updateVolume(${item.id}, this.value)">
                    </td>
                    <td>${item.satuan}</td>
                    <td>${formatRp(item.harga)}</td>
                    <td class="text-end">${formatRp(item.total)}</td>
                    <td class="text-center">
                        <button type="button"
                            class="btn btn-sm btn-danger"
                            onclick="removeItem(${item.id})">
                            -
                        </button>
                    </td>

                    <input type="hidden" name="items[${rowIndex}][job_category_id]" value="${item.id}">
                    <input type="hidden" name="items[${rowIndex}][job_name]" value="${item.nama}">
                    <input type="hidden" name="items[${rowIndex}][satuan]" value="${item.satuan}">
                    <input type="hidden" name="items[${rowIndex}][volume]" value="${item.volume}">
                    <input type="hidden" name="items[${rowIndex}][price]" value="${item.harga}">
                    <input type="hidden" name="items[${rowIndex}][total]" value="${item.total}">
                </tr>
            `);

            rowIndex++;
            no++;
        });

        // 🔹 SUBTOTAL PER GROUP
        tbody.insertAdjacentHTML('beforeend', `
            <tr class="fw-bold">
                <td colspan="5" class="text-end">Subtotal ${group.name}</td>
                <td class="text-end">${formatRp(group.subtotal)}</td>
            </tr>
        `);

    });

    recalculateSummary();
}

function recalculateSummary() {

    let subtotal = 0;

    Object.values(rabItems).forEach(item => {
        subtotal += item.total;
    });

        // PROFIT & OVERHEAD (%)
    let profitPercent = parseFloat(document.getElementById('rab_profit').value) || 0;
    let overheadPercent = parseFloat(document.getElementById('rab_overhead').value) || 0;

    let profitValue = subtotal * (profitPercent / 100);
    let overheadValue = subtotal * (overheadPercent / 100);

    let subtotalWithPO = subtotal + profitValue + overheadValue;

    // DISCOUNT
    let discount = cleanNumber(document.getElementById('rab_discount').value);
    let subAfterDiscount = subtotal - discount;
    if (subAfterDiscount < 0) subAfterDiscount = 0;

    // TAX
    let taxRate = parseFloat(document.getElementById('rab_tax_rate').value) || 0;
    let totalTax = subAfterDiscount * (taxRate / 100);

    // SHIPPING
    let shipping = cleanNumber(document.getElementById('rab_shipping').value);

    // GRAND TOTAL
    let grandTotal = subAfterDiscount + totalTax + shipping;

    // DISPLAY
    document.getElementById('rab_subtotalDisplay').innerText = formatRp(subtotal);
    document.getElementById('rab_subAfterDiscountDisplay').innerText = formatRp(subAfterDiscount);
    document.getElementById('rab_totalTaxDisplay').innerText = formatRp(totalTax);
    document.getElementById('rab_grandTotalDisplay').innerText = formatRp(grandTotal);
    document.getElementById('rab_subtotal').value = subtotal;
    document.getElementById('rab_subAfterDiscount').value = subAfterDiscount;
    document.getElementById('rab_tax_total').value = totalTax;
    document.getElementById('rab_grand_total').value = grandTotal;
}

const profitInput = document.getElementById('rab_profit_display');
const overheadInput = document.getElementById('rab_overhead_display');

// PROFIT
profitInput.addEventListener('input', function () {
    let val = parseFloat(this.value) || 0;
    document.getElementById('rab_profit').value = val;
    recalculateSummary();
});

// OVERHEAD
overheadInput.addEventListener('input', function () {
    let val = parseFloat(this.value) || 0;
    document.getElementById('rab_overhead').value = val;
    recalculateSummary();
});

const discountInput = document.getElementById('rab_discount_display');

discountInput.addEventListener('input', function () {
    let val = cleanNumber(this.value);
    document.getElementById('rab_discount').value = val;
    recalculateSummary();
});

discountInput.addEventListener('blur', function () {
    let val = cleanNumber(this.value);
    this.value = formatRp(val);
});


const shippingInput = document.getElementById('rab_shipping_display');

shippingInput.addEventListener('input', function () {
    let val = cleanNumber(this.value);
    document.getElementById('rab_shipping').value = val;
    recalculateSummary();
});

shippingInput.addEventListener('blur', function () {
    let val = cleanNumber(this.value);
    this.value = formatRp(val);
});


document.getElementById('rab_tax_rate').addEventListener('input', function () {
    recalculateSummary();
});

function updateVolume(jobId, newVolume) {

    newVolume = parseFloat(newVolume) || 0;
    if (newVolume <= 0) return;

    let item = rabItems[jobId];

    let total = newVolume * item.harga;

    item.volume = newVolume;
    item.total = total;

    renderRabTable();
}

function removeItem(itemId) {

    if (!confirm('Hapus item ini dari RAB?')) return;

    delete rabItems[itemId];

    renderRabTable();
}

</script>
@endpush