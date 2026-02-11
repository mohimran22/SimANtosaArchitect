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
            <label>Nomor Penawaran</label>
            <input type="text" name="offer_number" class="form-control" value="{{ old('offer_number') ?? '' }}" placeholder="AUTO GENARATE" readonly>

        </div>
        <div class="col-md-4">
            <label>Tanggal Penawaran</label>
            <input type="date" name="offer_date" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label>Nama Customer</label>
            <input type="text" name="contact_name" value="{{ $project->customer->user->fullname }}" class="form-control">
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
        {{-- <div class="col-md-2">
            <label class="form-label">Volume</label>
            <input type="text" name="volume" class="form-control">
            <small class="text-muted">
                Minimal order 100 m2
            </small>
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
                <small class="text-warning d-none" id="minOrderNote">
                    * Volume < 100 m² dihitung sebagai 100 m²
                </small>
        </div> --}}
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

                <tbody id="buildItemsBody">
                </tbody>

                <tfoot>
                    <tr>
                        <th colspan="5" class="text-end">SUBTOTAL</th>
                        <th id="subtotalDisplay">Rp 0</th>
                    </tr>

                    <tr>
                        <th colspan="5" class="text-end">DISCOUNT</th>
                        <th>
                        <input type="text" class="form-control rupiah" id="discount_display" readonly>
                        <input type="hidden" name="discount" id="discount">

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
                                name="tax_rate" id="tax_rate">
                        </th>
                    </tr>

                    <tr>
                        <th colspan="5" class="text-end">TOTAL TAX</th>
                        <th id="totalTaxDisplay">Rp 0</th>
                    </tr>

                    <tr>
                        <th colspan="5" class="text-end">SHIPPING / HANDLING</th>
                        <th>
                        <input type="text" class="form-control rupiah" id="shipping_display" readonly>
                        <input type="hidden" name="shipping" id="shipping">
                        </th>
                    </tr>

                    <tr>
                        <th colspan="5" class="text-end">GRAND TOTAL</th>
                        <th id="grandTotalDisplay">Rp 0</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        @if(optional($rab)->notes)
            <div class="mt-4">
                <h5 class="fw-bold">Keterangan</h5>
                <div class="border p-3">{{ $rab->notes }}</div>
            </div>
        @endif

    <div class="text-end mt-4">
        <button class="btn btn-dark">Simpan Penawaran</button>
    </div>
</form>
@endcan

@push('js')
<script>

function formatRupiah(n) {
    return 'Rp ' + Number(n).toLocaleString('id-ID');
}

function setRupiah(selector, val) {
    $(selector).val(formatRupiah(val));
}

function loadRabItems() {

    let rabId = $('#rab_process_id').val();
    if (!rabId) return;

    $.get(`/rab-process/${rabId}/items`, function(res) {

        const tbody = $('#buildItemsBody');
        tbody.empty();

        let no = 1;

        res.groups.forEach(group => {

            tbody.append(`
                <tr class="table-secondary fw-bold">
                    <td colspan="6">
                        ${group.category.code} ${group.category.name}
                    </td>
                </tr>
            `);

            group.items.forEach(item => {
                tbody.append(`
                    <tr>
                        <td>${no++}</td>
                        <td>${item.job_name}</td>
                        <td>${item.volume}</td>
                        <td>${item.satuan}</td>
                        <td>${formatRupiah(item.price)}</td>
                        <td>${formatRupiah(item.total)}</td>
                    </tr>
                `);
            });

            tbody.append(`
                <tr class="fw-bold text-end">
                    <td colspan="5">Subtotal ${group.category.name}</td>
                    <td>${formatRupiah(group.subtotal)}</td>
                </tr>
            `);
        });

        // ✅ LANGSUNG DARI HEADER RAB
        $('#tax_rate').val(res.header.tax_rate);

        setRupiah('#discount_display', res.header.discount);
        setRupiah('#shipping_display', res.header.shipping);
        $('#discount').val(res.header.discount);
        $('#shipping').val(res.header.shipping);

        $('#subtotalDisplay').text(formatRupiah(res.header.subtotal));
        $('#subAfterDiscountDisplay').text(formatRupiah(res.header.subtotal_after_discount));
        $('#totalTaxDisplay').text(formatRupiah(res.header.tax_total));
        $('#grandTotalDisplay').text(formatRupiah(res.header.grand_total));

    });
}

// trigger change
$('#rab_process_id').on('change', loadRabItems);

// auto load jika sudah ada value
$(document).ready(function () {
    if ($('#rab_process_id').val()) {
        loadRabItems();
    }
});

</script>
@endpush