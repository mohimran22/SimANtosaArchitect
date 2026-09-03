<div id="justekEditWrapper">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                Edit Justifikasi Teknis
            </h4>

            <div class="text-muted">
                {{ $technicalJustification->justek_number }}
            </div>
        </div>

        <span class="badge bg-dark">
            MODE EDIT
        </span>

    </div>


    {{-- Error --}}
    <div id="editJustekError"
         class="alert alert-danger d-none">
    </div>


    <form id="editJustekForm"
          action="{{ route(
              'projects.justek.update',
              $technicalJustification->id
          ) }}"
          method="POST">

        @csrf
        @method('PUT')

        {{-- ====================================================
            INFORMASI JUSTEK
        ==================================================== --}}

        <h4 class="fw-bold mb-3">
            Informasi Justifikasi Teknis
        </h4>

        <div class="row g-3 mb-4">

            <div class="col-md-4">

                <label class="form-label">
                    Nomor Penawaran
                </label>

                <input type="text"
                       class="form-control"
                       value="{{ $technicalJustification->justek_number }}"
                       readonly>

            </div>


            <div class="col-md-4">

                <label class="form-label required">
                    Tanggal Penawaran
                </label>

                <input type="date"
                       name="offer_date"
                       class="form-control"
                       value="{{ \Carbon\Carbon::parse(
                           $technicalJustification->offer_date
                       )->format('Y-m-d') }}"
                       required>

            </div>


            <div class="col-md-4">

                <label class="form-label">
                    Nama Customer
                </label>

                <input type="text"
                       class="form-control"
                       value="{{ $technicalJustification->contact_name }}"
                       readonly>

            </div>

        </div>


        {{-- ====================================================
            RINCIAN PEKERJAAN
        ==================================================== --}}

        <div class="rab-detail-header mb-3">

            <h4 class="fw-bold mb-0">
                Rincian Pekerjaan
            </h4>

            <div class="rab-action-buttons">

                <button type="button"
                        id="justekEditModeButton"
                        class="btn btn-dark btn-sm">

                    ✏️ Mode Edit

                </button>


                <button type="button"
                        id="justekEditSortButton"
                        class="btn btn-outline-secondary btn-sm">

                    🔀 Urutkan Daftar Pekerjaan

                </button>


                <button type="button"
                        class="btn btn-dark btn-sm"
                        id="justekEditAddItemButton">

                    + Tambah Item

                </button>

            </div>

        </div>


        <div class="table-responsive">

            <table class="table table-bordered align-middle"
                   id="justekEditItemsTable">

                <colgroup>

                    <col style="width: 60px">

                    <col>

                    <col style="width: 100px">

                    <col style="width: 130px">

                    <col style="width: 180px">

                    <col style="width: 180px">

                    <col style="width: 60px">

                </colgroup>


                <thead>

                    <tr>

                        <th>NO</th>

                        <th>
                            URAIAN PEKERJAAN
                        </th>

                        <th>
                            SAT
                        </th>

                        <th>
                            VOL
                        </th>

                        <th>
                            HARGA SATUAN
                        </th>

                        <th>
                            JUMLAH HARGA
                        </th>

                        <th></th>

                    </tr>

                </thead>


                <tbody id="justekEditItemsBody">
                </tbody>


                <tfoot>

                    {{-- SUBTOTAL --}}
                    <tr>

                        <th colspan="5"
                            class="text-end">

                            SUBTOTAL

                        </th>

                        <th id="justekEditSubtotalDisplay">
                            Rp 0
                        </th>

                        <th></th>

                    </tr>


                    {{-- DISCOUNT --}}
                    <tr>

                        <th colspan="5"
                            class="text-end">

                            DISCOUNT

                        </th>

                        <th>

                            <input type="text"
                                   class="form-control"
                                   id="justekEditDiscountDisplay">

                            <input type="hidden"
                                name="discount"
                                id="justekEditDiscount"
                                value="{{ $technicalJustification->discount ?? 0 }}">

                        </th>

                        <th></th>

                    </tr>


                    {{-- SUBTOTAL AFTER DISCOUNT --}}
                    <tr>

                        <th colspan="5"
                            class="text-end">

                            SUBTOTAL AFTER DISCOUNT

                        </th>

                        <th id="justekEditSubAfterDiscountDisplay">
                            Rp 0
                        </th>

                        <th></th>

                    </tr>
                    <tr>
                        <th colspan="5" class="text-end">
                            TAX RATE (%)
                        </th>
                        <th>
                            <input type="number"
                                   class="form-control"
                                   name="tax_rate"
                                   id="justekEditTaxRate"
                                   min="0"
                                   step="0.01"
                                   value="{{ $technicalJustification->tax_rate ?? 0 }}">
                        </th>
                        <th></th>
                    </tr>
                    <tr>
                        <th colspan="5" class="text-end">
                            TOTAL TAX
                        </th>
                        <th id="justekEditTotalTaxDisplay">
                            Rp 0
                        </th>
                        <th></th>
                    </tr>
                    <tr>

                        <th colspan="5" class="text-end">
                            SHIPPING / HANDLING
                        </th>
                        <th>
                            <input type="text"
                                   class="form-control"
                                   id="justekEditShippingDisplay">
                            <input type="hidden"
                                name="shipping"
                                id="justekEditShipping"
                                value="{{ $technicalJustification->shipping ?? 0 }}">
                        </th>
                        <th></th>
                    </tr>
                    <tr>

                        <th colspan="5" class="text-end">

                            GRAND TOTAL

                        </th>

                        <th id="justekEditGrandTotalDisplay">
                            Rp 0
                        </th>

                        <th></th>

                    </tr>

                </tfoot>

            </table>

        </div>

        <input type="hidden"
               name="subtotal"
               id="justekEditSubtotal">

        <input type="hidden"
               name="subtotal_after_discount"
               id="justekEditSubAfterDiscount">

        <input type="hidden"
               name="tax_total"
               id="justekEditTaxTotal">

        <input type="hidden"
               name="grand_total"
               id="justekEditGrandTotal">

        <div id="justekEditItemsContainer">
        </div>

        <h4 class="fw-bold mb-3 mt-4">
            Keterangan
        </h4>

        <textarea name="notes"
                  rows="3"
                  class="form-control">{{ $technicalJustification->notes }}</textarea>

        <div class="d-flex justify-content-end gap-2 mt-5">

            <button type="button"
                    class="btn btn-light btn-modal-cancel-edit">

                <i class="ti ti-arrow-left me-1"></i>

                Kembali

            </button>


            <button type="submit"
                    class="btn btn-dark">

                <i class="ti ti-device-floppy me-1"></i>

                Simpan Perubahan

            </button>

        </div>

    </form>
    <div class="modal fade"
         id="editJustekItemModal"
         tabindex="-1"
         aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header border-0">

                    <div>

                        <h5 class="modal-title fw-bold">
                            Tambah Item Justifikasi Teknis
                        </h5>

                        <small class="text-muted">
                            Masukkan pekerjaan yang akan ditambahkan
                        </small>

                    </div>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body">


                    {{-- LANTAI --}}
                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Lantai
                        </label>


                        <div id="editFloorSelectWrapper">

                            <select id="edit_justek_item_floor"
                                    class="form-select">

                                <option value="">
                                    -- Pilih Lantai --
                                </option>

                            </select>

                        </div>

                        <div id="editFloorInputWrapper"
                             class="d-none">

                            <div class="input-group">

                                <input type="text"
                                       id="edit_justek_item_floor_new"
                                       class="form-control"
                                       placeholder="Contoh: Lantai 2">


                                <button type="button"
                                        class="btn btn-outline-secondary"
                                        id="editCancelNewFloor">

                                    Batal

                                </button>

                            </div>

                        </div>

                    </div>
                    <div class="mb-3">
                        <label class="form-label required fw-semibold">
                            Kategori
                        </label>
                        <div id="editCategorySelectWrapper">

                            <select id="edit_justek_item_category"
                                    class="form-select">

                                <option value="">
                                    -- Pilih Kategori --
                                </option>

                            </select>

                        </div>

                        <div id="editCategoryInputWrapper" class="d-none">

                            <div class="input-group">

                                <input type="text"
                                       id="edit_justek_item_category_new"
                                       class="form-control"
                                       placeholder="Contoh: PEKERJAAN STRUKTUR">


                                <button type="button"
                                        class="btn btn-outline-secondary"
                                        id="editCancelNewCategory">

                                    Batal

                                </button>

                            </div>

                        </div>

                    </div>

                    <div class="mb-3">

                        <label class="form-label required fw-semibold">
                            Nama Pekerjaan
                        </label>

                        <input type="text"
                               id="edit_justek_item_job_name"
                               class="form-control"
                               placeholder="Contoh: Pekerjaan Pembersihan Lapangan">

                    </div>


                    {{-- DESKRIPSI --}}
                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Deskripsi Pekerjaan
                        </label>

                        <textarea id="edit_justek_item_description"
                                  class="form-control"
                                  rows="2"
                                  placeholder="Keterangan pekerjaan (opsional)"></textarea>

                    </div>


                    {{-- VOLUME + SATUAN --}}
                    <div class="row g-3 mb-3">

                        <div class="col-md-7">

                            <label class="form-label required fw-semibold">
                                Volume
                            </label>

                            <input type="text"
                                   id="edit_justek_item_volume"
                                   class="form-control"
                                   inputmode="decimal"
                                   placeholder="0">

                        </div>


                        <div class="col-md-5">

                            <label class="form-label required fw-semibold">
                                Satuan
                            </label>

                            <input type="text"
                                   id="edit_justek_item_satuan"
                                   class="form-control"
                                   placeholder="m2">

                        </div>

                    </div>


                    {{-- HARGA SATUAN --}}
                    <div class="mb-3">

                        <label class="form-label required fw-semibold">
                            Harga Satuan Dasar
                        </label>

                        <input type="text"
                               id="edit_justek_item_price_display"
                               class="form-control"
                               inputmode="decimal"
                               placeholder="Rp 0,00">

                        <input type="hidden"
                               id="edit_justek_item_price">

                    </div>

                </div>


                <div class="modal-footer border-0">

                    <button type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal">

                        Batal

                    </button>


                    <button type="button"
                            class="btn btn-dark"
                            id="editJustekSaveItemButton">

                        Simpan Item

                    </button>

                </div>

            </div>

        </div>

    </div>

</div>

    
<script type="application/json" id="justekEditItemsData">
{!! json_encode(
    $technicalJustification->items->map(function ($item) {
        return [
            'category_name' => $item->category_name,
            'job_name'      => $item->job_name,
            'description'   => $item->description,
            'satuan'        => $item->satuan,
            'volume'        => $item->volume,
            'base_price'    => $item->base_price,
            'price'         => $item->price,
            'total'         => $item->total,
            'order_no'      => $item->order_no,
        ];
    })->values()
) !!}
</script>

<script data-justek-edit-script>
(function () {
    if (typeof window.initJustekEditForm === 'function') {
        window.initJustekEditForm();
    }
    const itemsData = JSON.parse(
        document.getElementById('justekEditItemsData').textContent
    );

    const form = document.getElementById('editJustekForm');

    form.addEventListener('submit', function (event) {
        event.preventDefault();

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN':
                    document.querySelector('meta[name="csrf-token"]')
                        ?.getAttribute('content') ?? ''
            },
            body: formData
        })
        .then(async res => {
            const data = await res.json();
            if (!res.ok) throw { validation: true, data };
            return data;
        })

        .then(data => {
            if (data.success && typeof window.loadJustekDetailAfterEdit === 'function') {
                window.loadJustekDetailAfterEdit();
            }
        })
        .catch(err => console.error(err));
    });
})();
</script>