@php
    $rab = $project->rab;
        $latest = \Illuminate\Support\Facades\Cache::get('job_category_last_updated', 0);

    $needRefresh = $rab->analisa_version < $latest;
@endphp


<form action="{{ route('projects.rab.update', [$project->id, $rab->id]) }}" method="POST">
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
        @if($needRefresh)
        <div class="alert alert-warning d-flex justify-content-between align-items-center">
            <div>
                ⚠️ Harga analisa sudah berubah dari versi terakhir RAB ini dibuat.
            </div>
            <button type="button" class="btn btn-dark" id="btnRefreshRab">
                🔄 Refresh Harga RAB
            </button>
        </div>
        @endif
    <h4 class="fw-bold mb-3">Informasi Pembuatan Rab</h4>

    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Nama Customer</label>
            <input type="text" name="contact_name" value="{{ old('contact_name', $rab->contact_name) }}" class="form-control">
        </div>
        <div class="col-md-4">
            <label class="form-label">Lokasi Pekerjaan</label>
            <input type="text" name="job_location" value="{{ old('job_location', $rab->job_location) }}" class="form-control">
        </div>
        <div class="col-md-4">
            <label class="form-label required">Durasi Pekerjaan</label>
            <input type="text" name="job_duration" class="form-control" value="{{ old('job_duration', $rab->job_duration) }}" placeholder="175 Hari Kerja">
        </div>
        <div class="col-md-2">
            <label class="form-label">Profit</label>
            <input type="number" class="form-control" id="rab_profit_display_edit" value="{{ old('profit', $rab->profit) }}" step="0.01" min="0">
            <input type="hidden" name="profit" id="rab_profit_edit">
        </div>
        <div class="col-md-2">
            <label class="form-label">Overhead</label>
            <input type="number" class="form-control" id="rab_overhead_display_edit" value="{{ old('overhead', $rab->overhead) }}" step="0.01" min="0">
            <input type="hidden" name="overhead" id="rab_overhead_edit">
        </div>
    </div>
    <select style="display:none" id="jobCategorySelect">
        <option value="">-- Pilih AHSP --</option>
        @foreach($jobCategories as $job) 
        <option value="{{ $job->id }}" > 
            {{ $job->nama_pekerjaan }} 
        </option> 
        @endforeach
    </select>
  
    <div class="row mb-4 mt-3">
        <h4 class="fw-bold mb-3">Rincian Pekerjaan</h4>

        <table class="table table-bordered align-middle" id="offerItemsTable">
            <colgroup>
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
            <tbody id="rab_offerItemsBody_edit">
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
                    <th id="rab_subtotalDisplay_edit">Rp 0</th>
                </tr>

                <tr>
                    <th colspan="5" class="text-end">DISCOUNT</th>
                    <th>
                        <input type="text" class="form-control"
                            id="rab_discount_display_edit"
                            value="{{ number_format($rab->discount,0,',','.') }}">
                        <input type="hidden" name="discount" id="rab_discount_edit" value="{{ $rab->discount }}">
                    </th>
                </tr>

                <tr>
                    <th colspan="5" class="text-end">SUBTOTAL AFTER DISCOUNT</th>
                    <th id="rab_subAfterDiscountDisplay_edit">Rp 0</th>
                </tr>

                <tr>
                    <th colspan="5" class="text-end">TAX RATE (%)</th>
                    <th>
                        <input type="number" class="form-control"
                            id="rab_tax_rate_edit"
                            value="{{ $rab->tax_rate }}">
                    </th>
                </tr>

                <tr>
                    <th colspan="5" class="text-end">TOTAL TAX</th>
                    <th id="rab_totalTaxDisplay_edit">Rp 0</th>
                </tr>

                <tr>
                    <th colspan="5" class="text-end">SHIPPING / HANDLING</th>
                    <th>
                        <input type="text" class="form-control"
                            id="rab_shipping_display_edit"
                            value="{{ number_format($rab->shipping,0,',','.') }}">
                        <input type="hidden" name="shipping" id="rab_shipping_edit" value="{{ $rab->shipping }}">
                    </th>
                </tr>

                <tr>
                    <th colspan="5" class="text-end">GRAND TOTAL</th>
                    <th id="rab_grandTotalDisplay_edit">Rp 0</th>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="modal fade" id="uraianGalleryModalEdit">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content gambar-modal">

                <div class="modal-header border-0">
                    <div>
                    <h5 class="modal-title fw-semibold" id="modalTitleEdit"></h5>
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
                        id="uraianImageInputEdit">
                    </div>

                    <div id="uraianGalleryEdit" class="gambar-preview">
                    </div>

                </div>

            </div>
        </div>
    </div>
        <input type="hidden" name="subtotal" id="rab_subtotal" value="{{ $rab->subtotal }}">
        <input type="hidden" name="subtotal_after_discount" id="rab_subAfterDiscount" value="{{ $rab->subtotal_after_discount }}">
        <input type="hidden" name="tax_total" id="rab_tax_total" value="{{ $rab->tax_total }}">
        <input type="hidden" name="grand_total" id="rab_grand_total" value="{{ $rab->grand_total }}">

    <h4 class="fw-bold mb-3">Keterangan</h4>

    <textarea name="notes" rows="3" class="form-control"></textarea>

            <div class="mt-4">
                <button class="btn btn-dark" id="btnSubmitRab">Update Data RAB</button>
                <button type="button" id="btn-cancel-rab" class="btn btn-light btn-sm">Batal</button>
            </div>
</form>

@push('js')
<script>
document.addEventListener("DOMContentLoaded", function(){

    const rabId = {{ $rab->id }};

    fetch(`/rab/${rabId}/structure`)
        .then(res => res.json())
        .then(data => {

            loadExistingRab(data);

        });

});
</script>
    <script>

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

        let currentRabJob = null;
        let rabItems = {}
        let currentBasePrice = 0
        let globalProfit = 0
        let globalOverhead = 0
        let categoryIndex = 0
        let uraianIndex = {}
        let jobIndex = 0
        let draggedGroup = []
        let uraianImages = {}
        let activeUraian = null

        function loadExistingRab(data){

            const tbody = document.getElementById('rab_offerItemsBody_edit')
            tbody.innerHTML = ''

            categoryIndex = 0
            jobIndex = 0
            uraianIndex = {}

            globalProfit = parseFloat(data.meta.profit) || 0
            globalOverhead = parseFloat(data.meta.overhead) || 0

            data.categories.forEach(cat => {

                const catId = 'cat_'+categoryIndex
                uraianIndex[catId] = 1

                // CATEGORY
                tbody.insertAdjacentHTML('beforeend',`
                <tr class="table-secondary fw-bold category-row"
                    id="${catId}"
                    data-category="${catId}">

                    <td>
                        <span class="drag-handle me-2">
                            <i class="ti ti-grip-vertical"></i>
                        </span>
                        ${numberToLetters(categoryIndex)}
                    </td>

                    <td colspan="4" class="fw-bold">
                        ${cat.name}
                    </td>

                    <td>
                        <input class="form-control subtotal-category"
                            data-category="${catId}"
                            value="Rp 0"
                            readonly>
                    </td>

                    <td></td>
                </tr>
                `)

                // URAIAN
                cat.uraians.forEach(uraian => {

                    const uraianId = uraian.uraian_key
                    if(!uraianImages[uraianId]){
                        uraianImages[uraianId] = []
                    }

                    if(uraian.images){
                        uraian.images.forEach(img=>{
                            uraianImages[uraian.uraian_key].push({
                                id: img.id,
                                url: img.image.url
                            })
                        })
                    }

                    tbody.insertAdjacentHTML('beforeend',`

                    <tr class="uraian-row"
                        id="${uraianId}"
                        data-category="${catId}"
                        data-name="${uraian.name}">

                        <td class="text-center fw-bold">
                            ${uraianIndex[catId]++}
                        </td>

                        <td colspan="5">

                            <div class="d-flex align-items-center gap-2">

                                <span class="drag-handle">
                                    <i class="ti ti-grip-vertical"></i>
                                </span>

                                <span>${uraian.name}</span>

                                <button type="button" class="btn btn-sm btn-gambar-edit"
                                    onclick="openUraianGalleryEdit('${uraianId}','${uraian.name}')">

                                    <i class="ti ti-photo"></i>

                                </button>

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

                    // JOB / ITEM
                    uraian.items.forEach(job => {

                        const jobId = 'job_'+jobIndex++

                        tbody.insertAdjacentHTML('beforeend',`

                        <tr class="job-row"
                            id="${jobId}"
                            data-parent="${uraianId}"
                            data-category="${catId}">

                            <td></td>

                            <td>

                                <div class="d-flex align-items-center">

                                    <span class="drag-ahsp me-2">
                                        <i class="ti ti-grip-vertical"></i>
                                    </span>

                                    <select class="form-select select2-row job-select"
                                        onchange="loadJob('${jobId}',this.value)">

                                        ${document.getElementById('jobCategorySelect').innerHTML}

                                    </select>

                                </div>

                            </td>

                            <td>
                                <span class="sat">${job.satuan}</span>
                            </td>

                            <td>
                                <input type="number"
                                    class="form-control vol"
                                    value="${job.volume}"
                                    oninput="calculate('${jobId}')">
                            </td>

                            <td>
                                <input class="form-control harga"
                                    data-value="${job.base_price}"
                                    value="${formatRupiah(job.price)}"
                                    readonly>
                            </td>

                            <td>
                                <input class="form-control total"
                                    data-value="${job.total}"
                                    value="${formatRupiah(job.total)}"
                                    readonly>
                            </td>

                            <td>

                                <button type="button" class="btn btn-sm btn-dark"
                                    onclick="addJobRow('${uraianId}')">+</button>

                                <button type="button" class="btn btn-sm btn-secondary"
                                    onclick="removeJob('${jobId}')">-</button>

                            </td>

                        </tr>
                        `)

                        rabItems[jobId] = {
                            volume: job.volume,
                            base_price: job.base_price,
                            harga: job.price,
                            total: job.total
                        }

                        setTimeout(()=>{
                            $(`#${jobId} .job-select`)
                                .val(job.job_category_id)
                                .trigger('change')
                        },50)

                    })
                        console.log(uraianImages)
                })

                // ADD URAIAN BUTTON
                tbody.insertAdjacentHTML('beforeend',`

                <tr class="no-drag" id="addUraian_${catId}">
                    <td></td>
                    <td colspan="6">

                        <button type="button"
                            class="btn btn-sm btn-link"
                            onclick="addUraian('${catId}')">

                            + Uraian Pekerjaan

                        </button>

                    </td>
                </tr>
                `)

                categoryIndex++

            })

            $('.select2-row').select2()

            setTimeout(()=>{
                recalcAfterDrag()
                calculateSummary()
            },300)

        }

        function applyGlobalMarginToUnit(basePrice) {
            return basePrice
                + basePrice * (globalProfit / 100)
                + basePrice * (globalOverhead / 100);
        }
        function addCategory(){

            const tbody = document.getElementById('rab_offerItemsBody_edit')

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

                <td colspan="4" class="fw-bold">
                    ${name}
                </td>

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

            const name = input.value || 'Uraian Baru'

            row.dataset.name = name

            row.cells[1].innerHTML = `
            <div class="d-flex align-items-center gap-2">

                <span class="drag-handle" style="cursor:move">
                    <i class="ti ti-grip-vertical"></i>
                </span>

                <span>${name}</span>
                <button type="button"
                    class="btn btn-sm btn-gambar-edit"
                    data-uraian="${name}"
                    onclick="openUraianGalleryEdit('${uraianId}', '${name}')">

                    <i class="ti ti-photo"></i>
                </button>

            </div>
            `

            addJobRow(uraianId)
        }

        function addJobRow(uraianId){

            const originalSelect = document.getElementById('jobCategorySelect')
            const options = originalSelect.innerHTML

            const idx = jobIndex++
            const jobId = 'job_'+idx

            const uraianRow = document.getElementById(uraianId)

            let lastRow = uraianRow

            document.querySelectorAll(`[data-parent="${uraianId}"]`)
                .forEach(row => lastRow = row)

            lastRow.insertAdjacentHTML('afterend',`

            <tr class="job-row"
                id="${jobId}"
                data-parent="${uraianId}"
                data-category="${document.getElementById(uraianId).dataset.category}"
                data-index="${idx}">

                <td></td>

                <td>
                    <div class="d-flex align-items-center">

                        <span class="drag-ahsp me-2" style="cursor:move">
                            <i class="ti ti-grip-vertical"></i>
                        </span>

                        <div class="flex-grow-1">

                            <select class="form-select select2-row job-select w-100"
                                onchange="loadJob('${jobId}', this.value)">
                            ${options}
                            </select>
                        </div>
                    </div>
                </td>

                <td>
                    <span class="sat"></span>
                </td>

                <td>
                    <input type="number"
                        step="0.01"
                        class="form-control vol"
                        oninput="calculate('${jobId}')">
                </td>

                <td>
                    <input type="text"
                        class="form-control harga"
                        readonly>

                </td>

                <td>
                    <input type="text"
                        class="form-control total"
                        readonly>
                </td>

                <td>

                    <button type="button"
                        class="btn btn-sm btn-dark"
                        onclick="addJobRow('${uraianId}')">
                    +
                    </button>

                    <button type="button"
                        class="btn btn-sm btn-secondary"
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

                const sat = row.querySelector('.sat')
                if(sat) sat.innerText = job.satuan
                
                const satInput = row.querySelector('.satuan')
                if(satInput) satInput.value = job.satuan

                const jobName = row.querySelector('.job_name')
                if(jobName) jobName.value = job.name

                const basePrice = row.querySelector('.base_price')
                if(basePrice) basePrice.value = job.harga

                const hargaInput = row.querySelector('.harga')
                if(hargaInput){
                    hargaInput.dataset.value = job.harga
                    hargaInput.value = formatRupiah(job.harga)
                }

                calculate(rowId)
                updateHargaSemua()
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

            const hargaEl = row.querySelector('.harga')
            const totalEl = row.querySelector('.total')

            hargaEl.value = formatRupiah(hargaFinal)

            totalEl.dataset.value = total
            totalEl.value = formatRupiah(total)

            rabItems[rowId] = {
                volume: vol,
                base_price: basePrice,
                harga: hargaFinal,
                total: total
            }

            updateCategorySubtotal(row.dataset.category)

            calculateSummary()
        }
        function updateCategorySubtotal(catId){

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
            document.getElementById('rab_subtotalDisplay_edit').innerText = formatRupiah(subtotal)

            // discount
            let discount = Number(document.getElementById('rab_discount_edit').value || 0)

            let subAfterDiscount = subtotal - discount

            document.getElementById('rab_subAfterDiscount').value = subAfterDiscount
            document.getElementById('rab_subAfterDiscountDisplay_edit').innerText = formatRupiah(subAfterDiscount)

            // tax
            let taxRate = Number(document.getElementById('rab_tax_rate_edit').value || 0)

            let taxTotal = subAfterDiscount * taxRate / 100

            document.getElementById('rab_tax_total').value = taxTotal
            document.getElementById('rab_totalTaxDisplay_edit').innerText = formatRupiah(taxTotal)

            // shipping
            let shipping = Number(document.getElementById('rab_shipping_edit').value || 0)

            // grand total
            let grand = subAfterDiscount + taxTotal + shipping

            const grandEl = document.getElementById('rab_grandTotalDisplay_edit')

            grandEl.dataset.value = grand
            grandEl.innerText = formatRupiah(grand)

            document.getElementById('rab_grand_total').value = grand
        }
function removeJob(id){

    const row = document.getElementById(id)

    if(!row) return

    const catId = row.dataset.category || null

    row.remove()

    if(catId){
        updateCategorySubtotal(catId)
    }

    calculateSummary()

}
        function removeUraian(id){
            const row = document.getElementById(id)
            const catId = row.dataset.category
            document.querySelectorAll(`[data-parent="${id}"]`).forEach(e=>e.remove())
            row.remove()
            renumberUraian(catId)
            calculateSummary()
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
        function openUraianGalleryEdit(uraianId, uraianName){

            activeUraian = uraianId

            $("#modalTitleEdit").text(uraianName)

            if(!uraianImages[uraianId]){
                uraianImages[uraianId] = []
            }

            renderGalleryEdit()

            const modal = new bootstrap.Modal(
                document.getElementById('uraianGalleryModalEdit')
            )
            console.log(uraianImages)
        console.log(activeUraian)
            modal.show()
        }

        function renderGalleryEdit(){

            const gallery = document.getElementById('uraianGalleryEdit')

            gallery.innerHTML = ''

            const images = uraianImages[activeUraian] || []

            if(images.length === 0){
                gallery.innerHTML = '<div class="text-muted">Belum ada gambar</div>'
                return
            }

            images.forEach((img,index)=>{

                gallery.insertAdjacentHTML('beforeend',`

                <div class="preview-item">

                    <img src="${img.url}" class="img-thumbnail">

                    <button type="button" class="btn btn-sm remove-img"
                        onclick="removeUraianImage(${index})">
                        ×
                    </button>

                </div>

                `)

            })

        }

        function removeUraianImage(index){

            const img = uraianImages[activeUraian][index]

            fetch('/rab-images/'+img.id,{
                method:'DELETE',
                headers:{
                    'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content
                }
            })

            uraianImages[activeUraian].splice(index,1)

            renderGalleryEdit()

        }
        function updateHargaSemua(){

            const profit = parseFloat(document.getElementById('rab_profit_display_edit').value) || 0
            const overhead = parseFloat(document.getElementById('rab_overhead_display_edit').value) || 0

            document.querySelectorAll('.job-row').forEach(row=>{

                const hargaInput = row.querySelector('.harga')

                const basePrice = parseFloat(hargaInput.dataset.value) || 0

                const newPrice =
                    basePrice +
                    (basePrice * profit / 100) +
                    (basePrice * overhead / 100)

                hargaInput.value = formatRupiah(newPrice)

                calculate(row.id)

            })

        }
        $(document).on("click",".btn-gambar-edit",function(){

            let uraian = $(this).data("uraian");

            $("#modalTitleEdit").text(uraian);

        });
        document.getElementById('uraianImageInputEdit').addEventListener('change',function(){

            const files = this.files

            Array.from(files).forEach(file=>{

                const formData = new FormData()
                formData.append('image', file)

                fetch('/rab-images/upload',{
                    method:'POST',
                    headers:{
                        'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content
                    },
                    body:formData
                })
                .then(res=>res.json())
                .then(img=>{

                    if(!uraianImages[activeUraian]){
                        uraianImages[activeUraian] = []
                    }

                    uraianImages[activeUraian].push(img)

                    renderGalleryEdit()

                })

            })

        })
                document.getElementById('rab_profit_display_edit').addEventListener('input', function(){
                    globalProfit = Number(this.value) || 0
                    updateHargaSemua()
                })

                document.getElementById('rab_overhead_display_edit').addEventListener('input', function(){
                    globalOverhead = Number(this.value) || 0
                    updateHargaSemua()
                })
        document.getElementById('rab_discount_display_edit').addEventListener('input',function(){

            rupiahInput(this)

            document.getElementById('rab_discount_edit').value =
                parseRupiah(this.value)

            calculateSummary()

        })

        document.getElementById('rab_shipping_display_edit').addEventListener('input',function(){

            rupiahInput(this)

            document.getElementById('rab_shipping_edit').value =
                parseRupiah(this.value)

            calculateSummary()

        })

        document.getElementById('rab_tax_rate_edit').addEventListener('input', function () {
            calculateSummary();
        });
        function collectRabStructure(){

            let payload = {
                meta:{
                    profit: globalProfit,
                    overhead: globalOverhead,
                    discount: Number(document.getElementById('rab_discount_edit').value || 0),
                    tax_rate: Number(document.getElementById('rab_tax_rate_edit').value || 0),
                    shipping: Number(document.getElementById('rab_shipping_edit').value || 0),
                    subtotal: Number(document.getElementById('rab_subtotal').value || 0),
                    grand_total: Number(document.getElementById('rab_grand_total').value || 0)
                },
                categories:[]
            }

            document.querySelectorAll('.category-row').forEach((catRow,catIndex)=>{

                const catId = catRow.dataset.category

                const catName = catRow.querySelector('td:nth-child(2)').innerText.trim()

                let category = {
                    name: catName,
                    order: catIndex,
                    uraians:[]
                }

                document
                .querySelectorAll(`.uraian-row[data-category="${catId}"]`)
                .forEach((uraianRow,uraianIndex)=>{

                    const uraianId = uraianRow.id
                    const uraianName = uraianRow.dataset.name || ''

                    let uraian = {
                        name: uraianName,
                        order: uraianIndex,
                        images: uraianImages[uraianId] || [],
                        items:[]
                    }

                    document
                    .querySelectorAll(`.job-row[data-parent="${uraianId}"]`)
                    .forEach((jobRow,itemIndex)=>{

                        const jobSelect = jobRow.querySelector('.job-select')

                        const volume =
                            Number(jobRow.querySelector('.vol')?.value || 0)

                        const hargaInput =
                            jobRow.querySelector('.harga')

                        const totalInput =
                            jobRow.querySelector('.total')

                        const basePrice =
                            Number(hargaInput?.dataset.value || 0)

                        const total =
                            Number(totalInput?.dataset.value || 0)

                        const price =
                            parseRupiah(hargaInput?.value)

                        uraian.items.push({
                            job_category_id: jobSelect.value,
                            volume: volume,
                            base_price: basePrice,
                            price: price,
                            total: total,
                            order: itemIndex
                        })

                    })

                    category.uraians.push(uraian)

                })

                payload.categories.push(category)

            })

            return payload
        }
    </script>
    @if($needRefresh)
        <script>
        document.getElementById('btnRefreshRab').addEventListener('click', function () {

            Swal.fire({
                title: 'Refresh harga dari master?',
                text: 'Dengan merefresh ini, harga RAB akan mengikuti harga analisa terbaru.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Refresh',
                cancelButtonText: 'Batal'
            }).then((result) => {

                if (!result.isConfirmed) return;

                fetch("{{ route('rab.refreshFromMaster', $rab->id) }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Harga RAB berhasil disinkronkan dengan master.',
                        }).then(() => {
                            location.reload(); // reload biar UI sync total
                        });
                    }
                });

            });
        });
        </script>

        <script>
            const needRefresh = @json($needRefresh)
            document.getElementById('btnSubmitRab').addEventListener('click', function(e){

                e.preventDefault()

                if(needRefresh){

                    Swal.fire({
                        icon:'warning',
                        title:'Harga belum disinkronkan',
                        text:'Silakan refresh harga RAB terlebih dahulu.'
                    })

                    return
                }

                const payload = collectRabStructure()

                fetch(`/rab/{{ $rab->id }}`,{
                    method:'PUT',
                    headers:{
                        'Content-Type':'application/json',
                        'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(payload)
                })
                .then(res=>res.json())
                .then(res=>{

                    if(res.success){

                        Swal.fire({
                            icon:'success',
                            title:'Berhasil',
                            text:'RAB berhasil diupdate'
                        }).then(()=>location.reload())

                    }

                })

            })
        </script>
    @endif
@endpush
