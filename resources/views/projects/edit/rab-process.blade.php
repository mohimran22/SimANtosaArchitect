@php
    $rab = $project->rab->load('items.category');
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

    <div class="row mb-3">
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
    </div>

    <div class="row g-4">
                <div class="col-md-4">
                    <label class="form-label">Pilih Pekerjaan</label>
                    <select class="form-select select2" id="jobCategorySelect">
                        <option value="">-- Pilih Paket --</option>
                        @foreach($jobCategories as $job) 
                        <option value="{{ $job->id }}" > 
                            {{ $job->nama_pekerjaan }} 
                        </option> 
                        @endforeach
                    </select>
                </div>
        <div class="col-md-2">
            <label class="form-label">Volume</label>
            <input type="number" name="volume" id="rab_add_volume" class="form-control" step="0.01" min="0">
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
            <input type="text" class="form-control" id="rab_profit_display_edit" value="{{ old('profit', $rab->profit) }}">
            <input type="hidden" name="profit" id="rab_profit_edit">
        </div>
        <div class="col-md-2">
            <label class="form-label">Overhead</label>
            <input type="text" class="form-control" id="rab_overhead_display_edit" value="{{ old('overhead', $rab->overhead) }}">
            <input type="hidden" name="overhead" id="rab_overhead_edit">
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
                    {{-- <th>Profit (%)</th>
                    <th>Overhead (%)</th> --}}
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
                        <input type="text" class="form-control"
                            id="rab_discount_display"
                            value="{{ number_format($rab->discount,0,',','.') }}">
                        <input type="hidden" name="discount" id="rab_discount" value="{{ $rab->discount }}">
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
                            id="rab_tax_rate_input"
                            value="{{ $rab->tax_rate }}">
                        <input type="hidden" name="tax_rate" id="rab_tax_rate" value="{{ $rab->tax_rate }}">
                    </th>
                </tr>

                <tr>
                    <th colspan="5" class="text-end">TOTAL TAX</th>
                    <th id="rab_totalTaxDisplay">Rp 0</th>
                </tr>

                <tr>
                    <th colspan="5" class="text-end">SHIPPING / HANDLING</th>
                    <th>
                        <input type="text" class="form-control"
                            id="rab_shipping_display"
                            value="{{ number_format($rab->shipping,0,',','.') }}">
                        <input type="hidden" name="shipping" id="rab_shipping" value="{{ $rab->shipping }}">
                    </th>
                </tr>

                <tr>
                    <th colspan="5" class="text-end">GRAND TOTAL</th>
                    <th id="rab_grandTotalDisplay">Rp 0</th>
                </tr>
            </tfoot>
        </table>
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
    window.existingRabItems = @json($rab->items);
    window.existingRabMeta = {
        discount: {{ $rab->discount }},
        tax_rate: {{ $rab->tax_rate }},
        shipping: {{ $rab->shipping }},
        profit: {{ $rab->profit }},
        overhead: {{ $rab->overhead }}
    };
    window.marginLocked = {{ $rab->is_locked_margin ? 'true' : 'false' }};
    </script>
    <script>
        // $(document).ready(function() {
        //     $('.select2').select2({
        //         placeholder: "-- Pilih --",
        //         width: '100%'
        //     });
        // });

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
        let globalProfit = 0;
        let globalOverhead = 0;


        function calculateItemTotal(volume, harga, profitPercent, overheadPercent) {
            let base = volume * harga;
            let profitValue = base * (profitPercent / 100);
            let overheadValue = base * (overheadPercent / 100);
            return base + profitValue + overheadValue;
        }

        $('#jobCategorySelect').on('change', function () {
            const jobId = $(this).val();
            if (!jobId) return;

            fetch(`/job-categories/${jobId}/simple`)
                .then(res => res.json())
                .then(job => {

                    currentRabJob = job;

                    let baseHarga = parseFloat(job.harga ?? job.price ?? 0);

                    document.getElementById('rab_satuan').value = job.satuan;
                    document.getElementById('rab_priceMeter').value = baseHarga;
                    document.getElementById('rab_priceMeterFormatted').value = formatRp(baseHarga);

                    document.querySelector('input[name="volume"]').value = '';
                    document.getElementById('rab_totalPriceFormatted').value = '';
                });
        });

        // document.addEventListener('input', function(e) {

        //     if (e.target.name !== 'volume') return;
        //     if (!currentRabJob) return;

        //     const volume = parseFloat(e.target.value) || 0;
        //     if (volume <= 0) return;

        //     const harga = parseFloat(currentRabJob.harga ?? currentRabJob.price ?? 0);
            
        //     let profitPercent = globalProfit;
        //     let overheadPercent = globalOverhead;

        //     let total = calculateItemTotal(volume, harga, globalProfit, globalOverhead);

        //     document.getElementById('rab_totalPrice').value = total;
        //     document.getElementById('rab_totalPriceFormatted').value = formatRp(total);

        //     const jobId = currentRabJob.id;

        //     rabItems[jobId] = {
        //         ...currentRabJob,
        //         volume: volume,
        //         base_price: harga,
        //         harga: applyGlobalMarginToUnit(harga),
        //         profit: profitPercent,
        //         overhead: overheadPercent,
        //         total: total
        //     };

        //     renderRabTable();
        // });
document.getElementById('rab_add_volume')
    ?.addEventListener('input', function(e) {

    if (!currentRabJob) return;

    const volume = parseFloat(this.value) || 0;
    if (volume <= 0) return;

    const basePrice = parseFloat(job.base_price ?? job.harga ?? 0);

    const total = calculateItemTotal(
        volume,
        basePrice,
        globalProfit,
        globalOverhead
    );

    const finalUnit = applyGlobalMarginToUnit(basePrice);

    document.getElementById('rab_totalPrice').value = total;
    document.getElementById('rab_totalPriceFormatted').value = formatRp(total);

    rabItems[currentRabJob.id] = {
        ...currentRabJob,
        volume: volume,
        base_price: basePrice,
        harga: finalUnit,
        profit: globalProfit,
        overhead: globalOverhead,
        total: total
    };

    console.log("ADD ITEM GP:", globalProfit, "GO:", globalOverhead);

    renderRabTable();
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

            Object.keys(grouped).forEach(groupCode => {

                const group = grouped[groupCode];

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
                <td width="110">
                    <input type="number" class="form-control text-center"
                        value="${item.volume}"
                        min="0.01" step="0.01"
                        onchange="updateVolume(${item.id}, this.value)">
                </td>
                            <td>${item.satuan}</td>
                            <td>${formatRp(item.harga)}</td>
                <!-- <td width="90">
                    <input type="number" class="form-control text-center"
                        value="${item.profit}"
                        min="0" max="100" step="0.01"
                        onchange="updateProfit(${item.id}, this.value)">
                </td> -->

                <!-- ✅ OVERHEAD 
                <td width="90">
                    <input type="number" class="form-control text-center"
                        value="${item.overhead}"
                        min="0" max="100" step="0.01"
                        onchange="updateOverhead(${item.id}, this.value)">
                </td> -->
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
                            <input type="hidden" name="items[${rowIndex}][profit]" value="${item.profit}">
                            <input type="hidden" name="items[${rowIndex}][overhead]" value="${item.overhead}">
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

        function loadExistingRab() {

            if (!window.existingRabItems || window.existingRabItems.length === 0) return;
            globalProfit = parseFloat(window.existingRabMeta.profit) || 0;
            globalOverhead = parseFloat(window.existingRabMeta.overhead) || 0;

            rabItems = {}; // reset

            window.existingRabItems.forEach(item => {

                rabItems[item.job_category_id] = {
                    id: item.job_category_id,
                    nama: item.job_name,
                    satuan: item.satuan,
                    volume: parseFloat(item.volume),
                    base_price: parseFloat(item.base_price),
                    harga: parseFloat(item.price), // harga final lama

                    profit: parseFloat(item.profit),
                    overhead: parseFloat(item.overhead),
                    total: parseFloat(item.total),

                    kode_group: item.category?.kode_group ?? 'LAIN',
                    nama_group: item.category?.nama_group ?? 'Lain-lain'
                };
            });

            renderRabTable();

            document.getElementById('rab_profit_display_edit').value = globalProfit;
            document.getElementById('rab_overhead_display_edit').value = globalOverhead;
            document.getElementById('rab_profit_edit').value = globalProfit;
            document.getElementById('rab_overhead_edit').value = globalOverhead;

            document.getElementById('rab_discount').value = window.existingRabMeta.discount || 0;
            document.getElementById('rab_discount_display').value = formatRp(window.existingRabMeta.discount || 0);

            document.getElementById('rab_tax_rate_input').value = window.existingRabMeta.tax_rate || 0;
            document.getElementById('rab_tax_rate').value = window.existingRabMeta.tax_rate || 0;

            document.getElementById('rab_shipping').value = window.existingRabMeta.shipping || 0;
            document.getElementById('rab_shipping_display').value = formatRp(window.existingRabMeta.shipping || 0);

            recalculateSummary();
        }

        function applyGlobalMarginToUnit(basePrice) {
            return basePrice
                + basePrice * (globalProfit / 100)
                + basePrice * (globalOverhead / 100);
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

        document.getElementById('rab_tax_rate_input').addEventListener('input', function () {
            document.getElementById('rab_tax_rate').value = this.value;
            recalculateSummary();
        });

        function applyProfitOverheadToAll() {

            let p = globalProfit;
            let o = globalOverhead;

            Object.values(rabItems).forEach(item => {

                item.profit = p;
                item.overhead = o;

                let baseTotal = item.volume * item.base_price;

                let profitValue   = baseTotal * (p / 100);
                let overheadValue = baseTotal * (o / 100);

                let finalTotal = baseTotal + profitValue + overheadValue;

                item.total = Math.round(finalTotal);
                item.harga = Math.round(finalTotal / item.volume);
            });

            renderRabTable();
        }

        function updateVolume(jobId, newVolume) {

            newVolume = parseFloat(newVolume) || 0;
            if (newVolume <= 0) return;

            rabItems[jobId].volume = newVolume;
            applyProfitOverheadToAll(); // pusat logika
        }

        function removeItem(itemId) {

            if (!confirm('Hapus item ini dari RAB?')) return;

            delete rabItems[itemId];

            renderRabTable();
        }

        document.addEventListener('DOMContentLoaded', function () {
            loadExistingRab();
            initRabEditMargin();
        });

        function initRabEditMargin() {

            const profitInput = document.getElementById('rab_profit_display_edit');
            const overheadInput = document.getElementById('rab_overhead_display_edit');

            if (!profitInput || !overheadInput) {
                console.log("margin inputs not found");
                return;
            }

            profitInput.value = globalProfit;
            overheadInput.value = globalOverhead;

            if (window.marginLocked) {
                profitInput.readOnly = true;
                overheadInput.readOnly = true; 
            return;           
            }

            document.getElementById('rab_profit_edit').value = globalProfit;
            document.getElementById('rab_overhead_edit').value = globalOverhead;

            profitInput.oninput = () => {
                let newProfit = parseFloat(profitInput.value) || 0;
                if (newProfit === globalProfit) return;

                globalProfit = newProfit;
                applyProfitOverheadToAll();
            };

            overheadInput.oninput = () => {
                let newOverhead = parseFloat(profitOverhead.value) || 0;
                if (newOverhead === globalOverhead) return;

                globalOverhead =  newOverhead;
                applyProfitOverheadToAll();
            };

            console.log("INIT GP:", globalProfit, "GO:", globalOverhead);
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
        document.getElementById('btnSubmitRab').addEventListener('click', function(e){
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Harga belum disinkronkan',
                text: 'Silakan refresh harga RAB terlebih dahulu.',
            });
        });
        </script>
    @endif
@endpush
