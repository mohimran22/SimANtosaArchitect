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

    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Nama Customer</label>
            <input type="text" name="contact_name" value="{{ old('contact_name', $project->customer->user->fullname ?? '') }}" class="form-control">
        </div>
        <div class="col-md-4">
            <label class="form-label">Lokasi Pekerjaan</label>
            <input type="text" name="job_location" value="{{ old('job_location', $project->city->name ?? '-') }}" class="form-control">
        </div>
        <div class="col-md-4">
            <label class="form-label">Durasi Pekerjaan</label>
            <input type="text" name="job_duration" class="form-control" value="{{ old('job_duration') }}" placeholder="Total rencana pengerjaan berdasarkan hari kerja">
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
    <select style="display:none" id="jobCategorySelect">
        <option value="">-- Tambah AHSP --</option>
        @foreach($jobCategories as $job)
            <option value="{{ $job->id }}">
                {{ $job->nama_pekerjaan }}
            </option>
        @endforeach
    </select>

    {{-- <div class="row g-4">
        <div class="col-md-4">
            <label class="form-label">Pilih Pekerjaan</label>
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
    </div> --}}
  
    <div class="row mb-4 mt-3">
        <h4 class="fw-bold mb-3">Rincian Pekerjaan</h4>

        <table class="table table-bordered align-middle" id="offerItemsTable">
            <colgroup>
                {{-- <col style="width:4%">
                <col style="width:40%"> 
                <col style="width:8%"> 
                <col style="width:12%"> 
                <col style="width:16%"> 
                <col style="width:16%"> 
                <col style="width:4%">  --}}
                <col><col><col><col><col><col><col>
            </colgroup>
            <thead>
                <tr>
                    <th width="50">NO</th>
                    <th>URAIAN PEKERJAAN</th>
                    <th>SAT</th>
                    <th>VOL</th>
                    <th>HARGA SATUAN</th>
                    <th>JUMLAH HARGA</th>
                    <th width="1%"></th>
                </tr>
            </thead>
            <tbody id="rab_offerItemsBody">
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6">
                        <button type="button"
                            class="btn btn-link fw-bold text-decoration-none"
                            onclick="addCategory()">
                            + Kategori Pekerjaan
                        </button>
                    </td>
                </tr>
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
    <div class="modal fade" id="uraianGalleryModal">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content gambar-modal">

                <div class="modal-header border-0">
                    <div>
                    <h5 class="modal-title fw-semibold" id="modalTitle"></h5>
                    <small class="text-muted">Upload dokumentasi pekerjaan</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class= "upload-area mb-3">
                    <input type="file"
                        multiple
                        accept="image/*"
                        class="form-control mb-3"
                        id="uraianImageInput">
                    </div>

                    <div id="uraianGallery" class="gambar-preview">
                    </div>

                </div>

            </div>
        </div>
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
let currentBasePrice = 0;
let currentRabJob = null;
let rabItems = {};
let categoryIndex = 0;
let uraianIndex = {};
let jobIndex = 0;
let draggedGroup = [];
let uraianImages = {}
let activeUraian = null

function parseRupiah(value){

    if(!value) return 0

    return Number(
        value
        .toString()
        .replace(/[^0-9]/g,'')
    )

}

function formatRupiah(number){

    number = Number(number) || 0

    return 'Rp ' + number.toLocaleString('id-ID')

}
function rupiahInput(el){

    let number = parseRupiah(el.value)

    el.dataset.value = number

    el.value = formatRupiah(number)

}

function numberToLetters(num) {
    let letters = '';
    while (num >= 0) {
        letters = String.fromCharCode((num % 26) + 65) + letters;
        num = Math.floor(num / 26) - 1;
    }
    return letters;
}

const tbody = document.getElementById('rab_offerItemsBody')

new Sortable(tbody,{
    animation:150,
    handle:'.drag-handle,.drag-ahsp',
    draggable:'tr',

    onStart:function(evt){

            const row = evt.item
            draggedGroup = [row]

            if(row.classList.contains('category-row')){

                let next = row.nextElementSibling

                while(next && !next.classList.contains('category-row')){
                    draggedGroup.push(next)
                    next = next.nextElementSibling
                }

            }

            if(row.classList.contains('uraian-row')){

                const uraianId = row.id

                document.querySelectorAll(`[data-parent="${uraianId}"]`)
                    .forEach(r=>draggedGroup.push(r))

            }

    },

    onEnd:function(evt){

        const row = evt.item

        if(draggedGroup.length > 1){

            let insertPoint = row.nextElementSibling

            draggedGroup.slice(1).forEach(r=>{
                tbody.insertBefore(r, insertPoint)
            })

        }

        renumberAll()
        recalcAfterDrag()
    },
    onMove:function(evt){

        const dragged = evt.dragged
        const related = evt.related

        if(!related) return true

        // CATEGORY hanya boleh bertemu CATEGORY
        if(dragged.classList.contains('category-row')){
            if(!related.classList.contains('category-row')){
                return false
            }
        }

        // AHSP tidak boleh keluar dari uraian
        if(dragged.classList.contains('job-row')){
            if(!related.classList.contains('job-row') &&
            !related.classList.contains('uraian-row')){
                return false
            }
        }

        // URAIAN tidak boleh keluar kategori
        if(dragged.classList.contains('uraian-row')){
            if(related.classList.contains('category-row')){
                return false
            }
        }
        if(related.classList.contains('no-drag')){
            return false
        }

        return true
    }
})
function addCategory(){

    const tbody = document.getElementById('rab_offerItemsBody')

    let letter = String.fromCharCode(65 + categoryIndex)
    let catId = 'cat_'+categoryIndex

    uraianIndex[catId] = 1

    tbody.insertAdjacentHTML('beforeend',`

    <tr class="table-secondary fw-bold category-row" id="${catId}" data-category="${catId}">
        <td>
            <span class="drag-handle me-2" style="cursor:move">
                <i class="ti ti-grip-vertical"></i>
            </span>
            ${letter}
        </td>

        <td colspan="5">
            <input type="text" class="form-control fw-bold"
                placeholder="Nama kategori pekerjaan"
                onkeydown="if(event.key==='Enter') saveCategory('${catId}')">
        </td>

        <td></td>
    </tr>

    <tr class="no-drag" id="addUraian_${catId}">
        <td></td>
        <td colspan="6">
            <button type="button" class="btn btn-sm btn-link"
                onclick="addUraian('${catId}')">
                + Uraian Pekerjaan
            </button>
        </td>
    </tr>
    `)

    categoryIndex++
}

function saveCategory(catId){

    const row = document.getElementById(catId)
    const input = row.querySelector('input');

    const name = input.value || 'Kategori Baru';

    row.innerHTML = `
        <td>
            <span class="drag-handle me-2" style="cursor:move">
                <i class="ti ti-grip-vertical"></i>
            </span>
            ${row.cells[0].innerText}
        </td>

        <td colspan="3" class="fw-bold">
            ${name}
        </td>

        <td></td>

        <td>
            <input type="text"
                class="form-control subtotal-category" data-category="${catId}"
                value="Rp 0"
                readonly>
        </td>

        <td></td>
    `;
}

function addUraian(catId){

    const addRow = document.getElementById('addUraian_'+catId)

    let uraianNo = uraianIndex[catId]++
    let uraianId = 'uraian_'+(jobIndex++)

    addRow.insertAdjacentHTML('beforebegin',`

    <tr class="uraian-row" id="${uraianId}" data-category="${catId}">
        <td class="text-center fw-bold">${uraianNo}</td>

        <td colspan="5">
            <div class="d-flex align-items-center gap-2">

                <span class="drag-handle" style="cursor:move">
                    <i class="ti ti-grip-vertical"></i>
                </span>

                <input class="form-control"
                    placeholder="Uraian pekerjaan"
                    onkeydown="if(event.key==='Enter') saveUraian('${uraianId}')">
            </div>
        </td>

        <td>
            <button class="btn btn-sm btn-secondary"
                onclick="removeUraian('${uraianId}')">
                -
            </button>
        </td>
    </tr>

    `)
}

function saveUraian(uraianId){

    const row = document.getElementById(uraianId)
    const input = row.querySelector('input')

    const name = input.value || 'Uraian'

    row.cells[1].innerHTML = `
    <div class="d-flex align-items-center gap-2">

        <span class="drag-handle" style="cursor:move">
            <i class="ti ti-grip-vertical"></i>
        </span>

        <span>${name}</span>

        <button type="button" class="btn btn-sm btn-gambar"         data-bs-toggle="modal"
        data-bs-target="#uraianGalleryModal"
        data-uraian="name"
            onclick="openUraianGallery('${uraianId}')">

            <i class="ti ti-photo"></i>

        </button>

    </div>
    `

    addJobRow(uraianId)
}

function addJobRow(uraianId){

    const originalSelect = document.getElementById('jobCategorySelect')

    const options = originalSelect.innerHTML

    const jobId = 'job_'+jobIndex++

    const uraianRow = document.getElementById(uraianId)

    let lastRow = uraianRow

    document.querySelectorAll(`[data-parent="${uraianId}"]`)
        .forEach(row => lastRow = row)

    lastRow.insertAdjacentHTML('afterend',`

<tr class="job-row"
    id="${jobId}"
    data-parent="${uraianId}"
    data-category="${document.getElementById(uraianId).dataset.category}">

        <td class="drag-ahsp" style="cursor:move">
            <i class="ti ti-grip-vertical"></i>
        </td>

        <td>
            <select class="form-select select2-row"
                onchange="loadJob('${jobId}', this.value)">
                ${options}
            </select>
        </td>

        <td class="sat"></td>

        <td>
            <input type="number"
                step="0.01"
                class="form-control vol"
                oninput="calculate('${jobId}')">
        </td>

        <td>
            <input type="text"
                class="form-control harga"
                oninput="rupiahInput(this); calculate('${jobId}')">
        </td>

        <td>
            <input type="text" class="form-control total" readonly>
        </td>

        <td>

            <button type="button" class="btn btn-sm btn-dark"
                onclick="addJobRow('${uraianId}')">
                +
            </button>

            <button class="btn btn-sm btn-secondary"
                onclick="removeJob('${jobId}')">
                -
            </button>

        </td>

    </tr>
    `)

    $('.select2-row').select2()
}

function loadJob(rowId, jobId){

    if(!jobId) return

    fetch(`/job-categories/${jobId}/simple`)
    .then(res => res.json())
    .then(job => {

        const row = document.getElementById(rowId)

        row.querySelector('.sat').innerText = job.satuan

        const hargaInput = row.querySelector('.harga')

        // simpan angka asli
        hargaInput.dataset.value = job.harga

        // tampilkan rupiah
        hargaInput.value = formatRupiah(job.harga)

        // langsung hitung ulang
        calculate(rowId)

    })
}

function calculate(rowId){

    const row = document.getElementById(rowId)

    let vol = Number(row.querySelector('.vol').value) || 0

    let hargaInput = row.querySelector('.harga')

    let basePrice = Number(hargaInput.dataset.value || 0)

    let profitValue   = basePrice * (globalProfit / 100)
    let overheadValue = basePrice * (globalOverhead / 100)

    let hargaFinal = basePrice + profitValue + overheadValue

    let total = vol * hargaFinal

    const totalInput = row.querySelector('.total')

    totalInput.dataset.value = total
    totalInput.value = formatRupiah(total)

    rabItems[rowId] = {
        volume: vol,
        base_price: basePrice,
        harga: hargaFinal,
        total: total
    }

    updateCategorySubtotal(row.dataset.parent)

    calculateSummary()
}
function updateCategorySubtotal(uraianId){

    const uraianRow = document.getElementById(uraianId)

    if(!uraianRow) return

    const catId = uraianRow.dataset.category

    let subtotal = 0
    document.querySelectorAll(`.job-row[data-category="${catId}"]`)
    .forEach(row=>{

        const totalInput = row.querySelector('.total')

        subtotal += Number(totalInput.dataset.value || 0)

    })


    const subtotalInput = document.querySelector(
        `.subtotal-category[data-category="${catId}"]`
    )

    if(subtotalInput){

        subtotalInput.dataset.value = subtotal
        subtotalInput.value = formatRupiah(subtotal)

    }

}
function calculateSummary(){

    let subtotal = 0

    document.querySelectorAll('.total').forEach(el=>{
        subtotal += Number(el.dataset.value || 0)
    })

    // tampilkan subtotal
    document.getElementById('rab_subtotal').value = subtotal
    document.getElementById('rab_subtotalDisplay').innerText = formatRupiah(subtotal)

    // discount
    let discount = Number(document.getElementById('rab_discount').value || 0)

    let subAfterDiscount = subtotal - discount

    document.getElementById('rab_subAfterDiscount').value = subAfterDiscount
    document.getElementById('rab_subAfterDiscountDisplay').innerText = formatRupiah(subAfterDiscount)

    // tax
    let taxRate = Number(document.getElementById('rab_tax_rate').value || 0)

    let taxTotal = subAfterDiscount * taxRate / 100

    document.getElementById('rab_tax_total').value = taxTotal
    document.getElementById('rab_totalTaxDisplay').innerText = formatRupiah(taxTotal)

    // shipping
    let shipping = Number(document.getElementById('rab_shipping').value || 0)

    // grand total
    let grand = subAfterDiscount + taxTotal + shipping

    const grandEl = document.getElementById('rab_grandTotalDisplay')

    grandEl.dataset.value = grand
    grandEl.innerText = formatRupiah(grand)

    document.getElementById('rab_grand_total').value = grand
}
function removeJob(id){

    const row = document.getElementById(id)

    if(!row) return

    const uraianId = row.dataset.parent

    row.remove()

    updateCategorySubtotal(uraianId)
    calculateSummary()

}
function removeUraian(id){
    const row = document.getElementById(id)
    const catId = row.dataset.category
    document.querySelectorAll(`[data-parent="${id}"]`).forEach(e=>e.remove())
    row.remove()
    renumberUraian(catId)
    updateGrandTotal()
}
function renumberUraian(catId){
    let rows = document.querySelectorAll(`.uraian-row[data-category="${catId}"]`)
    rows.forEach((row,i)=>{
        row.querySelector('td').innerText = i+1
    })
    uraianIndex[catId] = rows.length + 1
}
function renumberAll(){
    document.querySelectorAll('.category-row').forEach(cat=>{
        const catId = cat.dataset.category
        const uraianRows = document.querySelectorAll(`.uraian-row[data-category="${catId}"]`)
        uraianRows.forEach((row,i)=>{
            row.querySelector('td:first-child').innerText = i+1
        })
        uraianIndex[catId] = uraianRows.length + 1
    })
}
function recalcAfterDrag(){

    document.querySelectorAll('.job-row').forEach(row=>{
        calculate(row.id)
    })

}
function openUraianGallery(uraianId){

    activeUraian = uraianId

    if(!uraianImages[uraianId]){
        uraianImages[uraianId] = []
    }

    renderGallery()

    const modal = new bootstrap.Modal(
        document.getElementById('uraianGalleryModal')
    )

    modal.show()

}
function renderGallery(){

    const gallery = document.getElementById('uraianGallery')

    gallery.innerHTML = ''

    uraianImages[activeUraian].forEach((src,index)=>{

        gallery.insertAdjacentHTML('beforeend',`

        <div class="preview-item">

            <img src="${src}">

            <button type="button" class="btn btn-sm btn-danger remove-image"
                onclick="removeUraianImage(${index})">
                ×
            </button>
        </div>
        `)
    })
}
function removeUraianImage(index){

    uraianImages[activeUraian].splice(index,1)

    renderGallery()

}
document.getElementById('uraianImageInput').addEventListener('change',function(){

    const files = this.files

    Array.from(files).forEach(file=>{

        const reader = new FileReader()

        reader.onload = function(e){

            uraianImages[activeUraian].push(e.target.result)

            renderGallery()

        }

        reader.readAsDataURL(file)

    })

})
$(document).on("click",".btn-gambar",function(){

let uraian = $(this).data("uraian");

$("#modalTitle").text("Gambar - " + uraian);

});
document.getElementById('rab_profit_display').addEventListener('input', function(){

    globalProfit = Number(this.value) || 0

    recalcAllRows()

})

document.getElementById('rab_overhead_display')
.addEventListener('input', function(){

    globalOverhead = Number(this.value) || 0

    recalcAllRows()

})

document.getElementById('rab_discount_display')
.addEventListener('input',function(){

    rupiahInput(this)

    document.getElementById('rab_discount').value =
        parseRupiah(this.value)

    calculateSummary()

})

document.getElementById('rab_shipping_display')
.addEventListener('input',function(){

    rupiahInput(this)

    document.getElementById('rab_shipping').value =
        parseRupiah(this.value)

    calculateSummary()

})

document.getElementById('rab_tax_rate').addEventListener('input', function () {
    calculateSummary();
});

function recalcAllRows(){

    document.querySelectorAll('.job-row').forEach(row=>{
        calculate(row.id)
    })

}
</script>
<script>
document.querySelector('form').addEventListener('submit', function () {
    applyProfitOverheadToAll();
});
</script>
@endpush