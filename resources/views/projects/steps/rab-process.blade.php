@can('lihat daftar proyek')
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
            <label class="form-label">Nama Customer</label>
            <input type="text" name="contact_name" value="{{ old('contact_name', $project->customer->user->fullname ?? '') }}" class="form-control">
        </div>
        <div class="col-md-4">
            <label class="form-label">Lokasi Pekerjaan</label>
            <input type="text" name="job_location" value="{{ old('job_location', $project->city->name ?? '-') }}" class="form-control">
        </div>
        <div class="col-md-4">
            <label class="form-label required">Durasi Pekerjaan</label>
            <input type="text" name="job_duration" class="form-control" value="{{ old('job_duration') }}" placeholder="Total rencana pengerjaan berdasarkan hari kerja" required>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <label class="form-label">Pilih Pekerjaan</label>
                <select class="form-select select2" id="jobCategorySelect" required>
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
            <input type="text" id="rab_priceMeterFormatted" class="form-control bg-light" readonly>
        </div>
        <div class="col-md-2">
            <label class="form-label">Total Harga (Rp)</label>
            <input type="hidden" name="total_price" id="rab_totalPrice">
            <input type="text" id="rab_totalPriceFormatted" class="form-control bg-light" readonly>
        </div>
        
        <div class="col-md-2">
            <label class="form-label">Profit</label>
            <input type="number" class="form-control" id="rab_profit_display">
        </div>
        <div class="col-md-2">
            <label class="form-label">Overhead</label>
            <input type="number" class="form-control" id="rab_overhead_display">
        </div>
    </div>
  
    <div class="row mb-4 mt-3">
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
                    <th colspan="5" class="text-end">DISCOUNT</th>
                    <th>
                        <input type="text" class="form-control" id="rab_discount_display">
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
        <input type="hidden" name="profit" id="final_profit">
        <input type="hidden" name="overhead" id="final_overhead">

    <h4 class="fw-bold mb-3">Keterangan</h4>

    <textarea name="notes" rows="3" class="form-control"></textarea>

    <div class="text-end mt-4">
        <button class="btn btn-dark">Simpan RAB</button>
    </div>
</form>
@endcan

@push('js')
<script>
let globalProfit = 0;
let globalOverhead = 0;
let currentBasePrice = 0; // harga asli dari DB
</script>

<script>

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

function numberToLetters(num) {
    let letters = '';
    while (num >= 0) {
        letters = String.fromCharCode((num % 26) + 65) + letters;
        num = Math.floor(num / 26) - 1;
    }
    return letters;
}

let currentRabJob = null;
let rabItems = {};

// function calculateItemTotal(volume, harga) {
//     let base = volume * harga;
//     let profitValue = base * (globalProfit / 100);
//     let overheadValue = base * (globalOverhead / 100);
//     return base + profitValue + overheadValue;
// }


$('#jobCategorySelect').on('change', function () {
    const jobId = $(this).val();
    if (!jobId) return;

    fetch(`/job-categories/${jobId}/simple`)
        .then(res => res.json())
        .then(job => {

            currentRabJob = job;

            let harga = parseFloat(job.harga) || 0;
            currentBasePrice = harga;
            document.getElementById('rab_satuan').value = job.satuan;
            document.getElementById('rab_priceMeter').value = harga;
            document.getElementById('rab_priceMeterFormatted').value = formatRp(harga);

            document.querySelector('input[name="volume"]').value = '';
            document.getElementById('rab_totalPriceFormatted').value = '';
        });
});

const profitInput = document.getElementById('rab_profit_display');
const overheadInput = document.getElementById('rab_overhead_display');

profitInput.addEventListener('input', function () {
    globalProfit = parseFloat(this.value) || 0;
    if (globalProfit < 0) globalProfit = 0;
    if (globalProfit > 100) globalProfit = 100;
    applyProfitOverheadToAll();
});

overheadInput.addEventListener('input', function () {
    globalOverhead = parseFloat(this.value) || 0;
    if (globalOverhead < 0) globalOverhead = 0;
    if (globalOverhead > 100) globalOverhead = 100;
    applyProfitOverheadToAll();
});

document.addEventListener('input', function(e) {

    if (e.target.name !== 'volume') return;
    if (!currentRabJob) return;

    const volume = parseFloat(e.target.value) || 0;
    if (volume <= 0) return;

    let hargaFinal = parseFloat(document.getElementById('rab_priceMeter').value) || 0;
    let total = volume * hargaFinal;

    document.getElementById('rab_totalPrice').value = total;
    document.getElementById('rab_totalPriceFormatted').value = formatRp(total);

    const jobId = currentRabJob.id;

    rabItems[jobId] = {
        ...currentRabJob,
        base_price: currentBasePrice,
        volume: volume,
        harga: hargaFinal,
        total: total
    };

    document.getElementById('rab_discount_display').value = '';
    document.getElementById('rab_shipping_display').value = '';
    document.getElementById('rab_discount').value = 0;
    document.getElementById('rab_shipping').value = 0;

    applyProfitOverheadToAll();
});

function renderRabTable() {

    const tbody = document.getElementById('rab_offerItemsBody');
    tbody.innerHTML = '';

    let grouped = {};
    let rowIndex = 0;

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
    Object.keys(grouped).forEach((groupCode, index) => {

        const group = grouped[groupCode];
        const cleanGroupName = group.name.replace(/^HARGA SATUAN\s*/i, '');
        const groupLabel = numberToLetters(index);

        // HEADER GROUP
        tbody.insertAdjacentHTML('beforeend', `
            <tr class="table-secondary fw-bold">
                <td>${groupLabel}</td>
                <td colspan="7">${cleanGroupName}</td>
            </tr>
        `);

        let no = 1;

        group.items.forEach(item => {

            tbody.insertAdjacentHTML('beforeend', `
                <tr>
                    <td>${no}</td>
                    <td>${item.nama}</td>
                    <td width="100">
                        <input type="number" class="form-control text-center volume-input"
                            value="${item.volume}"
                            min="0.01" step="0.01"
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
                    <input type="hidden" name="items[${rowIndex}][base_price]" value="${item.base_price}">
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
                <td colspan="5" class="text-end">Subtotal ${cleanGroupName}</td>
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
// function applyProfitOverheadToAll() {

//     Object.values(rabItems).forEach(item => {
// // let multiplier = 1 + (globalProfit / 100) + (globalOverhead / 100);
// let baseTotal = item.volume * item.base_price;
// item.harga = item.base_price * multiplier;
// item.total = item.volume * item.harga;

//     });

//     document.getElementById('final_profit').value = globalProfit;
//     document.getElementById('final_overhead').value = globalOverhead;

//     renderRabTable();
// }

function applyProfitOverheadToAll() {

    Object.values(rabItems).forEach(item => {

        let baseTotal = item.volume * item.base_price;

        let profitValue   = baseTotal * (globalProfit / 100);
        let overheadValue = baseTotal * (globalOverhead / 100);

        let finalTotal = baseTotal + profitValue + overheadValue;

        item.harga = finalTotal / item.volume; // harga final PER SATUAN (tabel)
        item.total = finalTotal;
    });

    document.getElementById('final_profit').value = globalProfit;
    document.getElementById('final_overhead').value = globalOverhead;

    renderRabTable();
}

function updateVolume(jobId, newVolume) {

    newVolume = parseFloat(newVolume) || 0;
    if (newVolume <= 0) return;

    let item = rabItems[jobId];
    item.volume = newVolume;

    applyProfitOverheadToAll();
}

function removeItem(itemId) {

    if (!confirm('Hapus item ini dari RAB?')) return;

    delete rabItems[itemId];

    renderRabTable();
}
</script>
<script>
document.querySelector('form').addEventListener('submit', function () {
    applyProfitOverheadToAll();   // 🔒 paksa update semua item ke harga final
});
</script>
@endpush