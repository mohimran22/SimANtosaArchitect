window.initJustekEditForm = function () {

    'use strict';

    let justekEditItems = [];

    let justekEditCurrentItemIndex = null;

    let justekEditModal = null;

    let justekEditMode = false;

    let justekEditSortMode = false;

    const body =
        document.getElementById('justekEditItemsBody');

    const container =
        document.getElementById('justekEditItemsContainer');

    const itemModalEl =
        document.getElementById('editJustekItemModal');


    if (!body || !container) {
        return;
    }

    const dataEl =
        document.getElementById('justekEditItemsData');


    try {

        justekEditItems =
            JSON.parse(dataEl?.textContent || '[]');

    } catch (error) {

        console.error(
            'Gagal membaca item Justek:',
            error
        );

        justekEditItems = [];
    }

    if (!Array.isArray(justekEditItems)) {

        justekEditItems = [];

    }

    justekEditItems = justekEditItems.map(function (item, index) {

            return {

                id:
                    item.id ?? null,

                temp_id:
                    item.temp_id ??
                    ('item_' + Date.now() + '_' + index),

                floor_name:
                    item.floor_name ?? '',

                category_name:
                    item.category_name ?? '',

                job_name:
                    item.job_name ?? '',

                description:
                    item.description ?? '',

                satuan:
                    item.satuan ?? '',

                volume:
                    parseDecimal(item.volume),

                base_price:
                    parseRupiah(item.base_price),

                price:
                    parseRupiah(
                        item.price ??
                        item.base_price
                    ),

                total:
                    parseRupiah(item.total),

                order_no:
                    item.order_no ?? index + 1

            };

        });


    /* ============================================================
        RUPIAH
    ============================================================ */

    function parseRupiah(value) {

        if (value === null ||
            value === undefined ||
            value === '') {

            return 0;
        }


        if (typeof value === 'number') {

            return value;
        }


        let str =
            String(value)
                .trim()
                .replace(/Rp/gi, '')
                .replace(/\s/g, '');


        /*
         * Format Indonesia:
         *
         * 1.500.000,50
         *
         * menjadi:
         *
         * 1500000.50
         */
        if (
            str.includes('.') &&
            str.includes(',')
        ) {

            str =
                str
                    .replace(/\./g, '')
                    .replace(',', '.');

        } else if (str.includes(',')) {

            /*
             * Jika hanya koma:
             *
             * 1000,50
             */
            str =
                str.replace(',', '.');

        } else {

            /*
             * Jika hanya titik dan merupakan
             * pemisah ribuan:
             *
             * 1.500.000
             */
            const parts =
                str.split('.');

            if (
                parts.length > 1 &&
                parts.every(function (part, index) {

                    return index === 0 ||
                        /^\d{3}$/.test(part);

                })
            ) {

                str =
                    str.replace(/\./g, '');

            }

        }


        const number =
            parseFloat(str);


        return Number.isFinite(number)
            ? number
            : 0;
    }


    function formatRupiah(value) {

        const number =
            Number(value) || 0;


        return 'Rp ' +
            number.toLocaleString(
                'id-ID',
                {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }
            );
    }


    /* ============================================================
        DECIMAL
    ============================================================ */

    function parseDecimal(value) {

        if (
            value === null ||
            value === undefined ||
            value === ''
        ) {

            return 0;
        }


        if (typeof value === 'number') {

            return value;
        }


        let str =
            String(value)
                .trim()
                .replace(/\s/g, '');


        /*
         * Volume dapat mempunyai
         * banyak angka desimal.
         */
        if (
            str.includes('.') &&
            str.includes(',')
        ) {

            str =
                str
                    .replace(/\./g, '')
                    .replace(',', '.');

        } else if (str.includes(',')) {

            str =
                str.replace(',', '.');

        }


        const result =
            parseFloat(str);


        return Number.isFinite(result)
            ? result
            : 0;
    }


    function formatDecimal(value) {

        const number =
            Number(value) || 0;


        return number.toLocaleString(
            'id-ID',
            {
                maximumFractionDigits: 10
            }
        );
    }


    /* ============================================================
        PRICE
    ============================================================ */

    /*
     * Justek menggunakan Harga Satuan Dasar
     * sebagai harga satuan item.
     *
     * Tidak ada AHSP / analisa / kategori harga
     * tambahan di sini.
     */
    function calculateItemPrice(basePrice) {

        return Number(basePrice) || 0;

    }


    /* ============================================================
        FLOOR
    ============================================================ */

    function getRabFloors() {

        return [
            ...new Set(
                justekEditItems
                    .map(function (item) {
                        return String(
                            item.floor_name || ''
                        ).trim();
                    })
                    .filter(Boolean)
            )
        ];

    }


    function renderFloorOptions() {

        const select =
            document.getElementById(
                'edit_justek_item_floor'
            );


        if (!select) {
            return;
        }


        const currentValue =
            select.value;


        select.innerHTML = `
            <option value="">
                -- Pilih Lantai --
            </option>
        `;


        getRabFloors().forEach(function (floor) {

            const option =
                document.createElement('option');

            option.value = floor;
            option.textContent = floor;

            select.appendChild(option);

        });


        /*
         * Opsi lantai baru.
         */
        const newOption =
            document.createElement('option');

        newOption.value = '__new__';
        newOption.textContent = '+ Tambah Lantai Baru';

        select.appendChild(newOption);


        if (currentValue) {

            select.value = currentValue;

        }

    }


    function showNewFloorInput() {

        document
            .getElementById('editFloorSelectWrapper')
            ?.classList.add('d-none');


        document
            .getElementById('editFloorInputWrapper')
            ?.classList.remove('d-none');


        document
            .getElementById('edit_justek_item_floor_new')
            ?.focus();

    }


    function cancelNewFloor() {

        document
            .getElementById('editFloorSelectWrapper')
            ?.classList.remove('d-none');


        document
            .getElementById('editFloorInputWrapper')
            ?.classList.add('d-none');


        const input =
            document.getElementById(
                'edit_justek_item_floor_new'
            );


        if (input) {
            input.value = '';
        }


        const select =
            document.getElementById(
                'edit_justek_item_floor'
            );


        if (select) {
            select.value = '';
        }

    }


    function getSelectedFloor() {

        const select =
            document.getElementById(
                'edit_justek_item_floor'
            );


        if (!select) {
            return '';
        }


        if (select.value === '__new__') {

            return (
                document.getElementById(
                    'edit_justek_item_floor_new'
                )?.value || ''
            ).trim();

        }


        return select.value.trim();

    }


    /* ============================================================
        CATEGORY
    ============================================================ */

    function getRabCategories(floor) {

        return [
            ...new Set(
                justekEditItems
                    .filter(function (item) {

                        return (
                            String(
                                item.floor_name || ''
                            ).trim() ===
                            String(
                                floor || ''
                            ).trim()
                        );

                    })
                    .map(function (item) {

                        return String(
                            item.category_name || ''
                        ).trim();

                    })
                    .filter(Boolean)
            )
        ];

    }


    function renderCategoryOptions(floor) {

        const select =
            document.getElementById(
                'edit_justek_item_category'
            );


        if (!select) {
            return;
        }


        select.innerHTML = `
            <option value="">
                -- Pilih Kategori --
            </option>
        `;


        if (floor) {

            getRabCategories(floor)
                .forEach(function (category) {

                    const option =
                        document.createElement('option');

                    option.value = category;
                    option.textContent = category;

                    select.appendChild(option);

                });

        }


        const newOption =
            document.createElement('option');

        newOption.value = '__new__';
        newOption.textContent =
            '+ Tambah Kategori Baru';

        select.appendChild(newOption);

    }


    function showNewCategoryInput() {

        document
            .getElementById(
                'editCategorySelectWrapper'
            )
            ?.classList.add('d-none');


        document
            .getElementById(
                'editCategoryInputWrapper'
            )
            ?.classList.remove('d-none');


        document
            .getElementById(
                'edit_justek_item_category_new'
            )
            ?.focus();

    }


    function cancelNewCategory() {

        document
            .getElementById(
                'editCategorySelectWrapper'
            )
            ?.classList.remove('d-none');


        document
            .getElementById(
                'editCategoryInputWrapper'
            )
            ?.classList.add('d-none');


        const input =
            document.getElementById(
                'edit_justek_item_category_new'
            );


        if (input) {
            input.value = '';
        }


        const select =
            document.getElementById(
                'edit_justek_item_category'
            );


        if (select) {
            select.value = '';
        }

    }


    function getSelectedCategory() {

        const select =
            document.getElementById(
                'edit_justek_item_category'
            );


        if (!select) {
            return '';
        }


        if (select.value === '__new__') {

            return (
                document.getElementById(
                    'edit_justek_item_category_new'
                )?.value || ''
            ).trim();

        }


        return select.value.trim();

    }


    /* ============================================================
        RESET ITEM FORM
    ============================================================ */

    function resetItemForm() {

        justekEditCurrentItemIndex = null;


        const fields = [

            'edit_justek_item_floor_new',
            'edit_justek_item_category_new',
            'edit_justek_item_job_name',
            'edit_justek_item_description',
            'edit_justek_item_volume',
            'edit_justek_item_satuan',
            'edit_justek_item_price_display',
            'edit_justek_item_price'

        ];


        fields.forEach(function (id) {

            const el =
                document.getElementById(id);

            if (el) {
                el.value = '';
            }

        });


        const floor =
            document.getElementById(
                'edit_justek_item_floor'
            );


        if (floor) {
            floor.value = '';
        }


        const category =
            document.getElementById(
                'edit_justek_item_category'
            );


        if (category) {

            category.innerHTML = `
                <option value="">
                    -- Pilih Kategori --
                </option>
            `;

        }


        cancelNewFloor();
        cancelNewCategory();

    }


    /* ============================================================
        OPEN ADD ITEM
    ============================================================ */

    function openAddJustekEditItemModal() {

        resetItemForm();

        renderFloorOptions();


        if (!justekEditModal) {

            justekEditModal =
                new bootstrap.Modal(
                    itemModalEl
                );

        }


        justekEditModal.show();

    }


    /* ============================================================
        OPEN EDIT ITEM
    ============================================================ */

    function openEditJustekItem(index) {

        const item =
            justekEditItems[index];


        if (!item) {
            return;
        }


        justekEditCurrentItemIndex =
            index;


        renderFloorOptions();


        const floor =
            document.getElementById(
                'edit_justek_item_floor'
            );


        const category =
            document.getElementById(
                'edit_justek_item_category'
            );


        /*
         * Lantai
         */
        if (floor) {

            floor.value =
                item.floor_name || '';

        }


        /*
         * Kategori
         */
        renderCategoryOptions(
            item.floor_name
        );


        if (category) {

            category.value =
                item.category_name || '';

        }


        document.getElementById(
            'edit_justek_item_job_name'
        ).value =
            item.job_name || '';


        document.getElementById(
            'edit_justek_item_description'
        ).value =
            item.description || '';


        document.getElementById(
            'edit_justek_item_volume'
        ).value =
            formatDecimal(item.volume);


        document.getElementById(
            'edit_justek_item_satuan'
        ).value =
            item.satuan || '';


        document.getElementById(
            'edit_justek_item_price'
        ).value =
            item.base_price || 0;


        document.getElementById(
            'edit_justek_item_price_display'
        ).value =
            formatRupiah(item.base_price);


        if (!justekEditModal) {

            justekEditModal =
                new bootstrap.Modal(
                    itemModalEl
                );

        }


        justekEditModal.show();

    }


    /* ============================================================
        SAVE ITEM
    ============================================================ */

    function saveJustekEditItem() {

        const floor =
            getSelectedFloor();


        const category =
            getSelectedCategory();


        const jobName =
            document.getElementById(
                'edit_justek_item_job_name'
            )?.value.trim();


        const description =
            document.getElementById(
                'edit_justek_item_description'
            )?.value.trim() || '';


        const volume =
            parseDecimal(
                document.getElementById(
                    'edit_justek_item_volume'
                )?.value
            );


        const satuan =
            document.getElementById(
                'edit_justek_item_satuan'
            )?.value.trim();


        const basePrice =
            parseRupiah(
                document.getElementById(
                    'edit_justek_item_price'
                )?.value
            );


        /* --------------------------------------------------------
            VALIDASI
        -------------------------------------------------------- */

        if (!floor) {

            alert('Lantai harus dipilih.');

            return;

        }


        if (!category) {

            alert('Kategori harus dipilih.');

            return;

        }


        if (!jobName) {

            alert('Nama pekerjaan harus diisi.');

            return;

        }


        if (!volume || volume <= 0) {

            alert('Volume harus lebih dari 0.');

            return;

        }


        if (!satuan) {

            alert('Satuan harus diisi.');

            return;

        }


        if (!basePrice || basePrice < 0) {

            alert('Harga satuan harus diisi.');

            return;

        }


        const price =
            calculateItemPrice(basePrice);


        const total =
            volume * price;


        /* --------------------------------------------------------
            UPDATE ITEM
        -------------------------------------------------------- */

        if (
            justekEditCurrentItemIndex !== null
        ) {

            const oldItem =
                justekEditItems[
                    justekEditCurrentItemIndex
                ];


            justekEditItems[
                justekEditCurrentItemIndex
            ] = {

                ...oldItem,

                floor_name:
                    floor,

                category_name:
                    category,

                job_name:
                    jobName,

                description:
                    description,

                satuan:
                    satuan,

                volume:
                    volume,

                base_price:
                    basePrice,

                price:
                    price,

                total:
                    total

            };

        }


        /* --------------------------------------------------------
            ADD ITEM
        -------------------------------------------------------- */

        else {

            justekEditItems.push({

                id: null,

                temp_id:
                    'new_' +
                    Date.now() +
                    '_' +
                    Math.random()
                        .toString(36)
                        .substring(2, 8),

                floor_name:
                    floor,

                category_name:
                    category,

                job_name:
                    jobName,

                description:
                    description,

                satuan:
                    satuan,

                volume:
                    volume,

                base_price:
                    basePrice,

                price:
                    price,

                total:
                    total,

                order_no:
                    justekEditItems.length + 1

            });

        }


        renderJustekEditItems();

        recalculateJustekEditAll();

        renderFloorOptions();

        justekEditModal?.hide();

    }


    /* ============================================================
        DELETE ITEM
    ============================================================ */

    function deleteJustekEditItem(index) {

        const item =
            justekEditItems[index];


        if (!item) {
            return;
        }


        const confirmed =
            confirm(
                'Hapus pekerjaan "' +
                item.job_name +
                '"?'
            );


        if (!confirmed) {
            return;
        }


        justekEditItems.splice(index, 1);


        /*
         * Reset nomor urut.
         */
        justekEditItems =
            justekEditItems.map(
                function (item, i) {

                    return {

                        ...item,

                        order_no:
                            i + 1

                    };

                }
            );


        renderJustekEditItems();

        recalculateJustekEditAll();

    }


    /* ============================================================
        RENDER ITEM
    ============================================================ */

    function renderJustekEditItems() {

        if (!body) {
            return;
        }


        body.innerHTML = '';


        if (justekEditItems.length === 0) {

            body.innerHTML = `
                <tr>
                    <td colspan="7"
                        class="text-center text-muted py-5">

                        Belum ada pekerjaan.

                    </td>
                </tr>
            `;


            renderHiddenJustekEditItems();

            return;

        }


        /*
         * Urutkan berdasarkan order_no.
         */
        const sortedItems =
            [...justekEditItems]
                .sort(function (a, b) {

                    return (
                        Number(a.order_no || 0) -
                        Number(b.order_no || 0)
                    );

                });


        sortedItems.forEach(function (item) {

            const originalIndex =
                justekEditItems.indexOf(item);


            const tr =
                document.createElement('tr');


            tr.dataset.index =
                originalIndex;


            /*
             * Mode sorting.
             */
            if (justekEditSortMode) {

                tr.draggable = true;

                tr.classList.add(
                    'justek-sortable-row'
                );

            }


            tr.innerHTML = `

                <td class="text-center">
                    ${item.order_no || ''}
                </td>


                <td>

                    <div class="fw-semibold">
                        ${escapeHtml(
                            item.job_name || '-'
                        )}
                    </div>


                    ${
                        item.description
                            ? `
                                <div class="small text-muted mt-1">
                                    ${escapeHtml(
                                        item.description
                                    )}
                                </div>
                              `
                            : ''
                    }


                    <div class="small text-muted mt-1">

                        ${
                            item.floor_name
                                ? escapeHtml(
                                    item.floor_name
                                  )
                                : ''
                        }

                        ${
                            item.floor_name &&
                            item.category_name
                                ? ' / '
                                : ''
                        }

                        ${
                            item.category_name
                                ? escapeHtml(
                                    item.category_name
                                  )
                                : ''
                        }

                    </div>

                </td>


                <td class="text-center">
                    ${escapeHtml(
                        item.satuan || ''
                    )}
                </td>


                <td class="text-end">
                    ${formatDecimal(
                        item.volume
                    )}
                </td>


                <td class="text-end">
                    ${formatRupiah(
                        item.price
                    )}
                </td>


                <td class="text-end">
                    ${formatRupiah(
                        item.total
                    )}
                </td>


                <td class="text-center">

                    <div class="d-flex justify-content-center gap-1">

                        <button type="button"
                                class="btn btn-icon btn-sm btn-dark justek-edit-item-edit"
                                data-index="${originalIndex}"
                                title="Edit Item">

                            <i class="ti ti-edit"></i>

                        </button>


                        <button type="button"
                                class="btn btn-icon btn-sm btn-dark justek-edit-item-delete"
                                data-index="${originalIndex}"
                                title="Hapus Item">

                            <i class="ti ti-trash"></i>

                        </button>

                    </div>

                </td>

            `;


            body.appendChild(tr);

        });


        bindJustekEditItemButtons();

        bindJustekEditDragDrop();

        renderHiddenJustekEditItems();

    }


    /* ============================================================
        ESCAPE HTML
    ============================================================ */

    function escapeHtml(value) {

        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');

    }


    /* ============================================================
        ITEM BUTTON
    ============================================================ */

    function bindJustekEditItemButtons() {

        document
            .querySelectorAll(
                '.justek-edit-item-edit'
            )
            .forEach(function (button) {

                button.addEventListener(
                    'click',
                    function () {

                        const index =
                            Number(
                                this.dataset.index
                            );


                        openEditJustekItem(index);

                    }
                );

            });


        document
            .querySelectorAll(
                '.justek-edit-item-delete'
            )
            .forEach(function (button) {

                button.addEventListener(
                    'click',
                    function () {

                        const index =
                            Number(
                                this.dataset.index
                            );


                        deleteJustekEditItem(index);

                    }
                );

            });

    }


    /* ============================================================
        DRAG DROP SORT
    ============================================================ */

    let draggedIndex = null;


    function bindJustekEditDragDrop() {

        if (!justekEditSortMode) {
            return;
        }


        document
            .querySelectorAll(
                '.justek-sortable-row'
            )
            .forEach(function (row) {

                row.addEventListener(
                    'dragstart',
                    function () {

                        draggedIndex =
                            Number(
                                this.dataset.index
                            );

                    }
                );


                row.addEventListener(
                    'dragover',
                    function (event) {

                        event.preventDefault();

                    }
                );


                row.addEventListener(
                    'drop',
                    function (event) {

                        event.preventDefault();


                        const targetIndex =
                            Number(
                                this.dataset.index
                            );


                        if (
                            draggedIndex === null ||
                            draggedIndex === targetIndex
                        ) {

                            return;

                        }


                        const movedItem =
                            justekEditItems.splice(
                                draggedIndex,
                                1
                            )[0];


                        justekEditItems.splice(
                            targetIndex,
                            0,
                            movedItem
                        );


                        /*
                         * Update order_no.
                         */
                        justekEditItems =
                            justekEditItems.map(
                                function (
                                    item,
                                    index
                                ) {

                                    return {

                                        ...item,

                                        order_no:
                                            index + 1

                                    };

                                }
                            );


                        draggedIndex = null;


                        renderJustekEditItems();

                        recalculateJustekEditAll();

                    }
                );

            });

    }


    /* ============================================================
        SORT MODE
    ============================================================ */

    const sortButton =
        document.getElementById(
            'justekEditSortButton'
        );


    if (sortButton) {

        sortButton.addEventListener(
            'click',
            function () {

                justekEditSortMode =
                    !justekEditSortMode;


                this.classList.toggle(
                    'active',
                    justekEditSortMode
                );


                this.innerHTML =
                    justekEditSortMode
                        ? '✅ Selesai Mengurutkan'
                        : '🔀 Urutkan Daftar Pekerjaan';


                renderJustekEditItems();

            }
        );

    }


    /* ============================================================
        MODE EDIT
    ============================================================ */

    const modeButton =
        document.getElementById(
            'justekEditModeButton'
        );


    if (modeButton) {

        modeButton.addEventListener(
            'click',
            function () {

                justekEditMode =
                    !justekEditMode;


                document
                    .querySelectorAll(
                        '#justekEditItemsTable .justek-edit-item-edit, #justekEditItemsTable .justek-edit-item-delete'
                    )
                    .forEach(function (button) {

                        button.classList.toggle(
                            'd-none',
                            !justekEditMode
                        );

                    });


                this.innerHTML =
                    justekEditMode
                        ? '✅ Mode Edit Aktif'
                        : '✏️ Mode Edit';


                this.classList.toggle(
                    'active',
                    justekEditMode
                );

            }
        );

    }


    /* ============================================================
        RECALCULATE
    ============================================================ */

    function recalculateJustekEditAll() {

        let subtotal = 0;


        justekEditItems =
            justekEditItems.map(
                function (item) {

                    const volume =
                        parseDecimal(
                            item.volume
                        );


                    const price =
                        calculateItemPrice(
                            item.base_price
                        );


                    const total =
                        volume * price;


                    subtotal += total;


                    return {

                        ...item,

                        volume:
                            volume,

                        price:
                            price,

                        total:
                            total

                    };

                }
            );


        /*
         * Discount
         */
        const discount =
            parseRupiah(
                document.getElementById(
                    'justekEditDiscount'
                )?.value
            );


        const subtotalAfterDiscount =
            Math.max(
                0,
                subtotal - discount
            );


        /*
         * Tax
         */
        const taxRate =
            parseDecimal(
                document.getElementById(
                    'justekEditTaxRate'
                )?.value
            );


        const taxTotal =
            subtotalAfterDiscount *
            taxRate /
            100;


        /*
         * Shipping
         */
        const shipping =
            parseRupiah(
                document.getElementById(
                    'justekEditShipping'
                )?.value
            );


        /*
         * Grand total
         */
        const grandTotal =
            subtotalAfterDiscount +
            taxTotal +
            shipping;


        /*
         * Display
         */
        document.getElementById(
            'justekEditSubtotalDisplay'
        ).textContent =
            formatRupiah(subtotal);


        document.getElementById(
            'justekEditSubAfterDiscountDisplay'
        ).textContent =
            formatRupiah(
                subtotalAfterDiscount
            );


        document.getElementById(
            'justekEditTotalTaxDisplay'
        ).textContent =
            formatRupiah(taxTotal);


        document.getElementById(
            'justekEditGrandTotalDisplay'
        ).textContent =
            formatRupiah(grandTotal);


        /*
         * Hidden value
         */
        document.getElementById(
            'justekEditSubtotal'
        ).value =
            subtotal;


        document.getElementById(
            'justekEditSubAfterDiscount'
        ).value =
            subtotalAfterDiscount;


        document.getElementById(
            'justekEditTaxTotal'
        ).value =
            taxTotal;


        document.getElementById(
            'justekEditGrandTotal'
        ).value =
            grandTotal;


        /*
         * Render hidden item fields.
         */
        renderHiddenJustekEditItems();

    }


    /* ============================================================
        HIDDEN ITEM INPUT
    ============================================================ */

    function renderHiddenJustekEditItems() {

        if (!container) {
            return;
        }


        container.innerHTML = '';


        justekEditItems.forEach(
            function (item, index) {

                const fields = {

                    id:
                        item.id ?? '',

                    floor_name:
                        item.floor_name ?? '',

                    category_name:
                        item.category_name ?? '',

                    job_name:
                        item.job_name ?? '',

                    description:
                        item.description ?? '',

                    satuan:
                        item.satuan ?? '',

                    volume:
                        item.volume ?? 0,

                    base_price:
                        item.base_price ?? 0,

                    price:
                        item.price ?? 0,

                    total:
                        item.total ?? 0,

                    order_no:
                        index + 1

                };


                Object.entries(fields)
                    .forEach(function (
                        [name, value]
                    ) {

                        const input =
                            document.createElement(
                                'input'
                            );


                        input.type =
                            'hidden';


                        input.name =
                            `items[${index}][${name}]`;


                        input.value =
                            value;


                        container.appendChild(
                            input
                        );

                    });

            }
        );

    }


    /* ============================================================
        INPUT FORMAT RUPIAH
    ============================================================ */

    function bindCurrencyInput(
        displayId,
        hiddenId
    ) {

        const display =
            document.getElementById(
                displayId
            );


        const hidden =
            document.getElementById(
                hiddenId
            );


        if (!display || !hidden) {
            return;
        }


        display.addEventListener(
            'input',
            function () {

                const raw =
                    this.value;


                const value =
                    parseRupiah(raw);


                hidden.value =
                    value;


                /*
                 * Jangan langsung format ketika
                 * user sedang mengetik agar cursor
                 * tidak lompat.
                 */
                recalculateJustekEditAll();

            }
        );


        display.addEventListener(
            'blur',
            function () {

                const value =
                    parseRupiah(
                        this.value
                    );


                hidden.value =
                    value;


                this.value =
                    value
                        ? formatRupiah(value)
                        : '';


                recalculateJustekEditAll();

            }
        );

    }


    /* ============================================================
        INPUT CHANGE
    ============================================================ */

    const taxRate =
        document.getElementById(
            'justekEditTaxRate'
        );


    if (taxRate) {

        taxRate.addEventListener(
            'input',
            recalculateJustekEditAll
        );

    }


    /*
     * Discount
     */
    bindCurrencyInput(
        'justekEditDiscountDisplay',
        'justekEditDiscount'
    );


    /*
     * Shipping
     */
    bindCurrencyInput(
        'justekEditShippingDisplay',
        'justekEditShipping'
    );


    /* ============================================================
        FLOOR CHANGE
    ============================================================ */

    const floorSelect =
        document.getElementById(
            'edit_justek_item_floor'
        );


    if (floorSelect) {

        floorSelect.addEventListener(
            'change',
            function () {

                if (
                    this.value === '__new__'
                ) {

                    showNewFloorInput();

                    renderCategoryOptions('');

                    return;

                }


                renderCategoryOptions(
                    this.value
                );

            }
        );

    }


    /* ============================================================
        CATEGORY CHANGE
    ============================================================ */

    const categorySelect =
        document.getElementById(
            'edit_justek_item_category'
        );


    if (categorySelect) {

        categorySelect.addEventListener(
            'change',
            function () {

                if (
                    this.value === '__new__'
                ) {

                    showNewCategoryInput();

                }

            }
        );

    }


    /* ============================================================
        NEW FLOOR / CATEGORY
    ============================================================ */

    document
        .getElementById(
            'editCancelNewFloor'
        )
        ?.addEventListener(
            'click',
            cancelNewFloor
        );


    document
        .getElementById(
            'editCancelNewCategory'
        )
        ?.addEventListener(
            'click',
            cancelNewCategory
        );


    /* ============================================================
        ADD ITEM BUTTON
    ============================================================ */

    document
        .getElementById(
            'justekEditAddItemButton'
        )
        ?.addEventListener(
            'click',
            openAddJustekEditItemModal
        );


    document
        .getElementById(
            'editJustekSaveItemButton'
        )
        ?.addEventListener(
            'click',
            saveJustekEditItem
        );


    /* ============================================================
        PRICE DISPLAY
    ============================================================ */

    const priceDisplay =
        document.getElementById(
            'edit_justek_item_price_display'
        );


    const priceHidden =
        document.getElementById(
            'edit_justek_item_price'
        );


    if (priceDisplay && priceHidden) {

        priceDisplay.addEventListener(
            'input',
            function () {

                priceHidden.value =
                    parseRupiah(
                        this.value
                    );

            }
        );


        priceDisplay.addEventListener(
            'blur',
            function () {

                const value =
                    parseRupiah(
                        this.value
                    );


                priceHidden.value =
                    value;


                this.value =
                    value
                        ? formatRupiah(value)
                        : '';

            }
        );

    }

    const discountHidden =
        document.getElementById(
            'justekEditDiscount'
        );

    const shippingHidden =
        document.getElementById(
            'justekEditShipping'
        );

    const initialDiscount = parseRupiah(discountHidden ? discountHidden.value : 0);
    const initialShipping = parseRupiah(shippingHidden ? shippingHidden.value : 0);



    const discountDisplay =
        document.getElementById(
            'justekEditDiscountDisplay'
        );


    const shippingDisplay = document.getElementById('justekEditShippingDisplay');

    if (discountHidden) {

        discountHidden.value =
            initialDiscount;

    }

    if (discountDisplay) {

        discountDisplay.value =
            initialDiscount
                ? formatRupiah(initialDiscount)
                : '';

    }

    if (shippingHidden) {

        shippingHidden.value =
            initialShipping;

    }

    if (shippingDisplay) {

        shippingDisplay.value =
            initialShipping
                ? formatRupiah(initialShipping)
                : '';

    }

    renderJustekEditItems();

    recalculateJustekEditAll();

    const form =
        document.getElementById(
            'editJustekForm'
        );


    if (form) {

        form.addEventListener(
            'submit',
            async function (event) {

                event.preventDefault();

                recalculateJustekEditAll();


                if (
                    justekEditItems.length === 0
                ) {

                    alert(
                        'Minimal harus ada 1 pekerjaan.'
                    );

                    return;

                }

                renderHiddenJustekEditItems();

                const button =
                    form.querySelector(
                        'button[type="submit"]'
                    );


                if (button) {

                    button.disabled =
                        true;

                    button.innerHTML = `
                        <span class="spinner-border spinner-border-sm me-1"></span>
                        Menyimpan...
                    `;

                }

                const errorBox =
                    document.getElementById(
                        'editJustekError'
                    );


                if (errorBox) {

                    errorBox.classList.add(
                        'd-none'
                    );

                    errorBox.innerHTML =
                        '';

                }

                try {

                    const response =
                        await fetch(
                            form.action,
                            {

                                method: 'POST',

                                headers: {

                                    'X-CSRF-TOKEN':
                                        document
                                            .querySelector(
                                                'meta[name="csrf-token"]'
                                            )
                                            ?.getAttribute(
                                                'content'
                                            ) || '',

                                    'Accept':
                                        'application/json'

                                },

                                body:
                                    new FormData(form)

                            }
                        );


                    const data = await response.json();

                    if (!response.ok) {

                        if (
                            data.errors
                        ) {

                            const messages =
                                Object.values(
                                    data.errors
                                )
                                .flat();


                            throw new Error(
                                messages.join(
                                    '<br>'
                                )
                            );

                        }

                        throw new Error(
                            data.message ||
                            'Gagal menyimpan perubahan.'
                        );

                    }

                    justekEditModal?.hide();

                    if (
                        typeof window.onJustekEditSuccess === 'function'
                    ) {

                        window.onJustekEditSuccess(
                            data
                        );

                    }

                } catch (error) {

                    console.error(
                        'Update Justek:',
                        error
                    );

                    if (errorBox) {

                        errorBox.innerHTML =
                            error.message ||
                            'Gagal menyimpan perubahan.';

                        errorBox.classList.remove(
                            'd-none'
                        );

                    }

                } finally {

                    if (button) {

                        button.disabled = false;

                        button.innerHTML = `
                            <i class="ti ti-device-floppy me-1"></i>
                            Simpan Perubahan
                        `;

                    }

                }

            }
        );

    }

    window.onJustekEditSuccess =
        function (data) {

            if (
                typeof window
                    .loadJustekDetailAfterEdit ===
                'function'
            ) {

                window.loadJustekDetailAfterEdit();

            }

        };

    renderJustekEditItems();

};