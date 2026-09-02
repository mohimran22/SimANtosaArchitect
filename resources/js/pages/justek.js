document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('justekForm');

    // Halaman bukan Justek
    if (!form) {
        return;
    }

    let justekItems = [];

    const changeTypeEl = document.getElementById('justek_change_type');
    const rabItemEl = document.getElementById('justek_rab_process_item_id');

    const rabItemWrapper = document.getElementById('justekRabItemWrapper');
    const rabInfo = document.getElementById('justekRabInfo');
    const newJobWrapper = document.getElementById('justekNewJobWrapper');

    const rabFloorEl = document.getElementById('justekRabFloor');
    const rabCategoryEl = document.getElementById('justekRabCategory');
    const oldVolumeInfoEl = document.getElementById('justekOldVolume');

    const oldVolumeWrapper = document.getElementById('justekOldVolumeWrapper');
    const oldVolumeEl = document.getElementById('justek_old_volume');

    const changeVolumeEl = document.getElementById('justek_change_volume');
    const finalVolumeEl = document.getElementById('justek_final_volume');

    const unitEl = document.getElementById('justek_unit');

    const floorEl = document.getElementById('justek_floor');
    const categoryEl = document.getElementById('justek_category');
    const jobNameEl = document.getElementById('justek_job_name');

    const unitPriceDisplayEl =
        document.getElementById('justek_unit_price_display');

    const unitPriceEl =
        document.getElementById('justek_unit_price');

    const totalPriceDisplayEl =
        document.getElementById('justek_total_price_display');

    const reasonEl = document.getElementById('justek_reason');

    const modalEl = document.getElementById('addJustekItemModal');

    function parseDecimal(value) {
        if (value === null || value === undefined || value === '') {
            return 0;
        }

        let str = String(value)
            .trim()
            .replace(/\s/g, '');

        if (str.includes(',')) {
            str = str.replace(/\./g, '');
            str = str.replace(',', '.');
        }

        return parseFloat(str) || 0;
    }

    function parseNumber(value) {

        if (value === null || value === undefined) {
            return 0;
        }

        if (typeof value === 'number') {
            return Number.isFinite(value) ? value : 0;
        }

        let str = String(value)
            .trim()
            .replace(/[^\d,.-]/g, '');

        if (!str) {
            return 0;
        }

        /*
        |--------------------------------------------------------------------------
        | Support:
        | 1.234,56
        | 1234,56
        | 1234.56
        |--------------------------------------------------------------------------
        */

        if (str.includes(',') && str.includes('.')) {

            // Indonesian format
            if (str.lastIndexOf(',') > str.lastIndexOf('.')) {
                str = str.replace(/\./g, '');
                str = str.replace(',', '.');
            } else {
                str = str.replace(/,/g, '');
            }

        } else if (str.includes(',')) {

            str = str.replace(',', '.');

        }

        const number = parseFloat(str);

        return Number.isFinite(number) ? number : 0;
    }


    /*
    |--------------------------------------------------------------------------
    | FORMAT NUMBER
    |--------------------------------------------------------------------------
    */

    function formatNumber(value, decimals = 2) {

        const number = parseNumber(value);

        return number.toLocaleString('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: decimals
        });
    }


    /*
    |--------------------------------------------------------------------------
    | FORMAT RUPIAH
    |--------------------------------------------------------------------------
    */

    function formatRupiah(value) {

        const number = parseNumber(value);

        return 'Rp ' + number.toLocaleString('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 2
        });

    }


    /*
    |--------------------------------------------------------------------------
    | MODAL
    |--------------------------------------------------------------------------
    */

    function getModalInstance() {

        if (!modalEl) {
            return null;
        }

        if (typeof bootstrap === 'undefined') {
            return null;
        }

        return bootstrap.Modal.getOrCreateInstance(modalEl);
    }


    /*
    |--------------------------------------------------------------------------
    | RESET MODAL
    |--------------------------------------------------------------------------
    */

    function resetJustekModal() {

        if (changeTypeEl) {
            changeTypeEl.value = '';
        }

        if (rabItemEl) {
            rabItemEl.value = '';
        }

        if (rabInfo) {
            rabInfo.classList.add('d-none');
        }

        if (rabItemWrapper) {
            rabItemWrapper.classList.remove('d-none');
        }

        if (newJobWrapper) {
            newJobWrapper.classList.add('d-none');
        }

        if (oldVolumeWrapper) {
            oldVolumeWrapper.classList.remove('d-none');
        }

        if (oldVolumeEl) {
            oldVolumeEl.value = '0';
        }

        if (oldVolumeInfoEl) {
            oldVolumeInfoEl.textContent = '0';
        }

        if (rabFloorEl) {
            rabFloorEl.textContent = '-';
        }

        if (rabCategoryEl) {
            rabCategoryEl.textContent = '-';
        }

        if (floorEl) {
            floorEl.value = '';
        }

        if (categoryEl) {
            categoryEl.value = '';
        }

        if (jobNameEl) {
            jobNameEl.value = '';
        }

        if (changeVolumeEl) {
            changeVolumeEl.value = '';
        }

        if (finalVolumeEl) {
            finalVolumeEl.value = '0';
        }

        if (unitEl) {
            unitEl.value = '';
        }

        if (unitPriceDisplayEl) {
            unitPriceDisplayEl.value = '';
        }

        if (unitPriceEl) {
            unitPriceEl.value = '0';
        }

        if (totalPriceDisplayEl) {
            totalPriceDisplayEl.value = 'Rp 0';
        }

        if (reasonEl) {
            reasonEl.value = '';
        }

        updateChangeVolumeLabel();
    }


    /*
    |--------------------------------------------------------------------------
    | CHANGE TYPE
    |--------------------------------------------------------------------------
    */

    function handleChangeType() {

        const type = changeTypeEl?.value;

        if (!type) {

            rabItemWrapper?.classList.remove('d-none');
            rabInfo?.classList.add('d-none');
            newJobWrapper?.classList.add('d-none');
            oldVolumeWrapper?.classList.remove('d-none');

            return;
        }

        if (type === 'new') {

            // Pekerjaan baru tidak menggunakan item RAB
            rabItemWrapper?.classList.add('d-none');
            rabInfo?.classList.add('d-none');
            newJobWrapper?.classList.remove('d-none');
            oldVolumeWrapper?.classList.add('d-none');

            if (oldVolumeEl) {
                oldVolumeEl.value = '0';
            }

            if (finalVolumeEl) {
                finalVolumeEl.value =
                    formatNumber(parseNumber(changeVolumeEl?.value));
            }

            if (unitEl) {
                unitEl.value = '';
            }

        } else {

            // Increase / Decrease
            rabItemWrapper?.classList.remove('d-none');
            newJobWrapper?.classList.add('d-none');
            oldVolumeWrapper?.classList.remove('d-none');

            if (rabItemEl?.value) {
                loadRabItem();
            }

        }

        updateChangeVolumeLabel();
        calculateModalTotal();
    }


    /*
    |--------------------------------------------------------------------------
    | CHANGE VOLUME LABEL
    |--------------------------------------------------------------------------
    */

    function updateChangeVolumeLabel() {

        const label = document.getElementById('justekChangeVolumeLabel');

        if (!label) {
            return;
        }

        const type = changeTypeEl?.value;

        if (type === 'increase') {

            label.innerHTML =
                'Volume Tambahan <span class="text-danger">*</span>';

        } else if (type === 'decrease') {

            label.innerHTML =
                'Volume Dikurangi <span class="text-danger">*</span>';

        } else if (type === 'new') {

            label.innerHTML =
                'Volume Pekerjaan Baru <span class="text-danger">*</span>';

        } else {

            label.innerHTML =
                'Volume Perubahan <span class="text-danger">*</span>';

        }
    }


    /*
    |--------------------------------------------------------------------------
    | LOAD RAB ITEM
    |--------------------------------------------------------------------------
    */

    function loadRabItem() {

        if (!rabItemEl) {
            return;
        }

        const option =
            rabItemEl.options[rabItemEl.selectedIndex];

        if (!option || !option.value) {

            rabInfo?.classList.add('d-none');

            if (oldVolumeEl) {
                oldVolumeEl.value = '0';
            }

            if (changeVolumeEl) {
                changeVolumeEl.value = '';
            }

            if (finalVolumeEl) {
                finalVolumeEl.value = '0';
            }

            if (unitEl) {
                unitEl.value = '';
            }

            if (unitPriceEl) {
                unitPriceEl.value = '0';
            }

            if (unitPriceDisplayEl) {
                unitPriceDisplayEl.value = '';
            }

            calculateModalTotal();

            return;
        }

        const floor =
            option.dataset.floor || '';

        const category =
            option.dataset.category || '';

        const volume =
            parseNumber(option.dataset.volume);

        const unit =
            option.dataset.unit || '';

        const price =
            parseNumber(option.dataset.price);

        /*
        |--------------------------------------------------------------------------
        | INFO RAB
        |--------------------------------------------------------------------------
        */

        if (rabFloorEl) {
            rabFloorEl.textContent = floor || '-';
        }

        if (rabCategoryEl) {
            rabCategoryEl.textContent = category || '-';
        }

        if (oldVolumeInfoEl) {
            oldVolumeInfoEl.textContent =
                formatNumber(volume, 5);
        }

        rabInfo?.classList.remove('d-none');

        /*
        |--------------------------------------------------------------------------
        | FIELD
        |--------------------------------------------------------------------------
        */

        if (oldVolumeEl) {
            oldVolumeEl.value =
                formatNumber(volume, 5);
        }

        if (unitEl) {
            unitEl.value = unit;
        }

        if (unitPriceEl) {
            unitPriceEl.value = price;
        }

        if (unitPriceDisplayEl) {
            unitPriceDisplayEl.value =
                formatRupiah(price);
        }

        if (changeVolumeEl) {
            changeVolumeEl.value = '';
        }

        if (finalVolumeEl) {
            finalVolumeEl.value =
                formatNumber(volume, 5);
        }

        calculateModalTotal();
    }


    /*
    |--------------------------------------------------------------------------
    | CALCULATE FINAL VOLUME
    |--------------------------------------------------------------------------
    */

    function calculateFinalVolume() {

        const type = changeTypeEl?.value;

        const change =
            parseNumber(changeVolumeEl?.value);

        let oldVolume =
            parseNumber(oldVolumeEl?.value);

        let finalVolume = 0;

        if (type === 'increase') {

            finalVolume =
                oldVolume + change;

        } else if (type === 'decrease') {

            finalVolume =
                oldVolume - change;

        } else if (type === 'new') {

            finalVolume =
                change;

        }

        // Tidak boleh volume akhir negatif
        if (finalVolume < 0) {
            finalVolume = 0;
        }

        if (finalVolumeEl) {
            finalVolumeEl.value =
                formatNumber(finalVolume, 5);
        }

        return {
            oldVolume,
            change,
            finalVolume
        };
    }

    function calculateModalTotal() {

        const type = changeTypeEl?.value;

        const change =
            parseNumber(changeVolumeEl?.value);

        const price =
            parseNumber(unitPriceEl?.value);

        let total = change * price;

        if (type === 'decrease') {
            total *= -1;
        }

        if (totalPriceDisplayEl) {
            totalPriceDisplayEl.value =
                formatRupiah(total);
        }

        calculateFinalVolume();

        return total;
    }

    function handlePriceInput() {

        const value =
            parseNumber(unitPriceDisplayEl?.value);

        if (unitPriceEl) {
            unitPriceEl.value = value;
        }

        if (unitPriceDisplayEl && value > 0) {

            unitPriceDisplayEl.value =
                formatRupiah(value);

        }

        calculateModalTotal();
    }

    function handleVolumeInput() {

        calculateFinalVolume();
        calculateModalTotal();

    }

    function validateModal() {

        const type =
            changeTypeEl?.value;

        if (!type) {
            alert('Silakan pilih jenis perubahan.');
            return false;
        }

        if (type === 'increase' || type === 'decrease') {

            if (!rabItemEl?.value) {
                alert('Silakan pilih pekerjaan RAB.');
                return false;
            }

        }

        if (type === 'new') {

            if (!jobNameEl?.value.trim()) {
                alert('Nama pekerjaan wajib diisi.');
                jobNameEl?.focus();
                return false;
            }

        }

        const change = parseNumber(changeVolumeEl?.value);

        if (change <= 0) {

            alert('Volume perubahan harus lebih besar dari 0.');

            changeVolumeEl?.focus();

            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | DECREASE CANNOT EXCEED OLD VOLUME
        |--------------------------------------------------------------------------
        */

        if (type === 'decrease') {

            const oldVolume =
                parseNumber(oldVolumeEl?.value);

            if (change > oldVolume) {

                alert(
                    'Volume yang dikurangi tidak boleh lebih besar dari volume RAB.'
                );

                changeVolumeEl?.focus();

                return false;
            }

        }

        const price =
            parseNumber(unitPriceEl?.value);

        if (price <= 0) {

            alert('Harga satuan wajib diisi.');

            unitPriceDisplayEl?.focus();

            return false;
        }

        const reason =
            reasonEl?.value.trim();

        if (!reason) {

            alert('Alasan / Justifikasi wajib diisi.');

            reasonEl?.focus();

            return false;
        }

        return true;
    }

    function saveJustekItem() {
        const changeType = document.getElementById('justek_change_type').value;

        if (!changeType) {
            Swal.fire('Perhatian', 'Pilih jenis perubahan.', 'warning');
            return;
        }

        const rabItemSelect = document.getElementById('justek_rab_process_item_id');

        const buildProcessItemId =
            changeType !== 'new'
                ? rabItemSelect.value
                : null;

        const jobName =
            changeType === 'new'
                ? document.getElementById('justek_job_name').value.trim()
                : rabItemSelect.options[rabItemSelect.selectedIndex]?.dataset.jobName || '';

        const floor =
            changeType === 'new'
                ? document.getElementById('justek_floor').value.trim()
                : rabItemSelect.options[rabItemSelect.selectedIndex]?.dataset.floor || '';

        const category =
            changeType === 'new'
                ? document.getElementById('justek_category').value.trim()
                : rabItemSelect.options[rabItemSelect.selectedIndex]?.dataset.category || '';

        const unit =
            changeType === 'new'
                ? document.getElementById('justek_unit').value.trim()
                : rabItemSelect.options[rabItemSelect.selectedIndex]?.dataset.unit || '';

        const oldVolume = parseDecimal(
            document.getElementById('justek_old_volume').value
        );

        const changeVolume = parseDecimal(
            document.getElementById('justek_change_volume').value
        );

        const finalVolume = parseDecimal(
            document.getElementById('justek_final_volume').value
        );

        const unitPrice = parseDecimal(
            document.getElementById('justek_unit_price').value
        );

        const reason =
            document.getElementById('justek_reason').value.trim();

        const description =
            changeType === 'new'
                ? ''
                : rabItemSelect.options[rabItemSelect.selectedIndex]?.dataset.description || '';

        if (!jobName) {
            Swal.fire('Perhatian', 'Nama pekerjaan wajib diisi.', 'warning');
            return;
        }

        if (!unit) {
            Swal.fire('Perhatian', 'Satuan wajib diisi.', 'warning');
            return;
        }

        if (changeVolume <= 0) {
            Swal.fire('Perhatian', 'Volume perubahan harus lebih dari 0.', 'warning');
            return;
        }

        if (unitPrice < 0) {
            Swal.fire('Perhatian', 'Harga satuan tidak valid.', 'warning');
            return;
        }

        if (!reason) {
            Swal.fire('Perhatian', 'Alasan / Justifikasi wajib diisi.', 'warning');
            return;
        }

        let volumeDifference = 0;
        let totalPrice = 0;

        if (changeType === 'increase') {
            volumeDifference = changeVolume;
            totalPrice = changeVolume * unitPrice;

        } else if (changeType === 'decrease') {
            volumeDifference = -changeVolume;
            totalPrice = -(changeVolume * unitPrice);

        } else {
            volumeDifference = finalVolume;
            totalPrice = finalVolume * unitPrice;
        }

        justekItems.push({
            build_process_item_id: buildProcessItemId,
            change_type: changeType,
            floor: floor,
            category: category,
            job_name: jobName,
            item_description: description,
            old_volume: oldVolume,
            change_volume: changeVolume,
            new_volume: finalVolume,
            volume_difference: volumeDifference,
            unit: unit,
            unit_price: unitPrice,
            total_price: totalPrice,
            reason: reason
        });

        console.log('CHANGE TYPE:', changeType);
        console.log('RAB ITEM ID:', buildProcessItemId);
        console.log('RAB SELECT VALUE:', rabItemSelect.value);
        console.log('SELECTED OPTION:', rabItemSelect.options[rabItemSelect.selectedIndex]);

        renderJustekItems();

        const modal = bootstrap.Modal.getInstance(
            document.getElementById('addJustekItemModal')
        );

        modal?.hide();

        resetJustekItemModal();
    }
    function resetJustekItemModal() {

    // Jenis perubahan
    const changeType = document.getElementById('justek_change_type');
    if (changeType) {
        changeType.value = '';
    }

    // Pekerjaan RAB
    const rabItem = document.getElementById('justek_rab_process_item_id');
    if (rabItem) {
        rabItem.value = '';
    }

    // Lantai pekerjaan baru
    const floor = document.getElementById('justek_floor');
    if (floor) {
        floor.value = '';
    }

    // Kategori pekerjaan baru
    const category = document.getElementById('justek_category');
    if (category) {
        category.value = '';
    }

    // Nama pekerjaan baru
    const jobName = document.getElementById('justek_job_name');
    if (jobName) {
        jobName.value = '';
    }

    // Volume RAB
    const oldVolume = document.getElementById('justek_old_volume');
    if (oldVolume) {
        oldVolume.value = '0';
    }

    // Volume perubahan
    const changeVolume = document.getElementById('justek_change_volume');
    if (changeVolume) {
        changeVolume.value = '';
    }

    // Volume setelah perubahan
    const finalVolume = document.getElementById('justek_final_volume');
    if (finalVolume) {
        finalVolume.value = '0';
    }

    // Satuan
    const unit = document.getElementById('justek_unit');
    if (unit) {
        unit.value = '';
    }

    // Harga satuan display
    const unitPriceDisplay =
        document.getElementById('justek_unit_price_display');

    if (unitPriceDisplay) {
        unitPriceDisplay.value = '';
    }

    // Harga satuan asli
    const unitPrice =
        document.getElementById('justek_unit_price');

    if (unitPrice) {
        unitPrice.value = '0';
    }

    // Nilai perubahan
    const totalPrice =
        document.getElementById('justek_total_price_display');

    if (totalPrice) {
        totalPrice.value = 'Rp 0';
    }

    // Alasan
    const reason =
        document.getElementById('justek_reason');

    if (reason) {
        reason.value = '';
    }

    // Info RAB
    const rabInfo =
        document.getElementById('justekRabInfo');

    if (rabInfo) {
        rabInfo.classList.add('d-none');
    }

    // Wrapper pekerjaan baru
    const newJobWrapper =
        document.getElementById('justekNewJobWrapper');

    if (newJobWrapper) {
        newJobWrapper.classList.add('d-none');
    }

    // Wrapper RAB
    const rabItemWrapper =
        document.getElementById('justekRabItemWrapper');

    if (rabItemWrapper) {
        rabItemWrapper.classList.remove('d-none');
    }

    // Wrapper volume RAB
    const oldVolumeWrapper =
        document.getElementById('justekOldVolumeWrapper');

    if (oldVolumeWrapper) {
        oldVolumeWrapper.classList.remove('d-none');
    }

    // Reset label
    const changeVolumeLabel =
        document.getElementById('justekChangeVolumeLabel');

    if (changeVolumeLabel) {
        changeVolumeLabel.innerHTML =
            'Volume Perubahan <span class="text-danger">*</span>';
    }
}
    function removeJustekItem(id) {

        justekItems =
            justekItems.filter(item => item.id !== id);

        renderJustekItems();

        renderJustekHiddenInputs();

        calculateSummary();
    }


    /*
    |--------------------------------------------------------------------------
    | RENDER TABLE
    |--------------------------------------------------------------------------
    */

    function renderJustekItems() {

        const tbody = document.getElementById('justekItemsBody');
        const container = document.getElementById('justekItemsContainer');

        if (!tbody || !container) {
            console.error('Element Justek tidak ditemukan.');
            return;
        }

        tbody.innerHTML = '';
        container.innerHTML = '';

        let subtotal = 0;

        justekItems.forEach((item, index) => {

            const totalPrice = Number(item.total_price) || 0;

            subtotal += totalPrice;

            const typeLabel = {
                increase: 'Tambah Volume',
                decrease: 'Kurangi Volume',
                new: 'Pekerjaan Baru'
            };

            const row = document.createElement('tr');

            row.innerHTML = `
                <td class="text-center">
                    ${index + 1}
                </td>

                <td>
                    ${typeLabel[item.change_type] ?? item.change_type}
                </td>

                <td>
                    <div class="fw-semibold">
                        ${escapeHtml(item.job_name)}
                    </div>

                    ${
                        item.floor
                            ? `<small class="text-muted">
                                ${escapeHtml(item.floor)}
                            </small>`
                            : ''
                    }

                    ${
                        item.category
                            ? `<div>
                                <small class="text-muted">
                                    ${escapeHtml(item.category)}
                                </small>
                            </div>`
                            : ''
                    }
                </td>

                <td class="text-end">
                    ${formatNumber(item.old_volume)}
                </td>

                <td class="text-end">
                    ${formatNumber(item.new_volume)}
                </td>

                <td class="text-end">
                    ${formatNumber(item.volume_difference)}
                </td>

                <td class="text-end">
                    ${formatRupiah(item.unit_price)}
                </td>

                <td class="text-end fw-semibold">
                    ${formatRupiah(totalPrice)}
                </td>

                <td class="text-center">

                    <button
                        type="button"
                        class="btn btn-sm btn-outline-danger"
                        onclick="removeJustekItem(${index})"
                    >
                        <i class="ti ti-trash"></i>
                    </button>

                </td>
            `;

            tbody.appendChild(row);

        });

        updateJustekTotals(subtotal);
        renderJustekHiddenInputs();
        console.log('Justek items:', justekItems);
        console.log(
            'Hidden inputs:',
            container.querySelectorAll('input[type="hidden"]').length
        );
    }
function updateJustekTotals(subtotal) {

    const discount =
        parseDecimal(
            document.getElementById('justek_discount')?.value || 0
        );

    const taxRate =
        parseFloat(
            document.getElementById('justek_tax_rate')?.value || 0
        );

    const shipping =
        parseDecimal(
            document.getElementById('justek_shipping')?.value || 0
        );


    /*
    |--------------------------------------------------------------------------
    | SUBTOTAL
    |--------------------------------------------------------------------------
    */

    const subtotalValue =
        Number(subtotal) || 0;


    /*
    |--------------------------------------------------------------------------
    | DISCOUNT
    |--------------------------------------------------------------------------
    */

    const subtotalAfterDiscount =
        Math.max(
            subtotalValue - discount,
            0
        );


    /*
    |--------------------------------------------------------------------------
    | TAX
    |--------------------------------------------------------------------------
    */

    const taxTotal =
        subtotalAfterDiscount *
        (taxRate / 100);


    /*
    |--------------------------------------------------------------------------
    | GRAND TOTAL
    |--------------------------------------------------------------------------
    */

    const grandTotal =
        subtotalAfterDiscount +
        taxTotal +
        shipping;


    /*
    |--------------------------------------------------------------------------
    | DISPLAY
    |--------------------------------------------------------------------------
    */

    document.getElementById(
        'justek_subtotalDisplay'
    ).textContent =
        formatRupiah(subtotalValue);

    document.getElementById(
        'justek_subAfterDiscountDisplay'
    ).textContent =
        formatRupiah(subtotalAfterDiscount);

    document.getElementById(
        'justek_totalTaxDisplay'
    ).textContent =
        formatRupiah(taxTotal);

    document.getElementById(
        'justek_grandTotalDisplay'
    ).textContent =
        formatRupiah(grandTotal);


    /*
    |--------------------------------------------------------------------------
    | HIDDEN FORM VALUES
    |--------------------------------------------------------------------------
    */

    document.getElementById(
        'justek_subtotal'
    ).value = subtotalValue;

    document.getElementById(
        'justek_subAfterDiscount'
    ).value = subtotalAfterDiscount;

    document.getElementById(
        'justek_tax_total'
    ).value = taxTotal;

    document.getElementById(
        'justek_grand_total'
    ).value = grandTotal;
}
    function addHiddenInput(container, name, value) {

        const input = document.createElement('input');

        input.type = 'hidden';
        input.name = name;
        input.value = value ?? '';

        container.appendChild(input);
    }

function removeJustekItem(index) {

    if (
        index < 0 ||
        index >= justekItems.length
    ) {
        return;
    }

    justekItems.splice(index, 1);

    renderJustekItems();
}
    function escapeHtml(value) {

        if (value === null || value === undefined) {
            return '';
        }

        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');

    }


    /*
    |--------------------------------------------------------------------------
    | HIDDEN INPUTS
    |--------------------------------------------------------------------------
    */

function renderJustekHiddenInputs() {

    const container =
        document.getElementById('justekItemsContainer');

    if (!container) {
        console.error(
            'ERROR: #justekItemsContainer tidak ditemukan.'
        );
        return;
    }

    container.innerHTML = '';

    justekItems.forEach((item, index) => {

        addHiddenInput(
            container,
            `build_process_item_id[${index}]`,
            item.build_process_item_id ?? ''
        );

        addHiddenInput(
            container,
            `change_type[${index}]`,
            item.change_type ?? ''
        );

        addHiddenInput(
            container,
            `floor[${index}]`,
            item.floor ?? ''
        );

        addHiddenInput(
            container,
            `category[${index}]`,
            item.category ?? ''
        );

        addHiddenInput(
            container,
            `job_name[${index}]`,
            item.job_name ?? ''
        );

        addHiddenInput(
            container,
            `item_description[${index}]`,
            item.item_description ?? ''
        );

        addHiddenInput(
            container,
            `old_volume[${index}]`,
            item.old_volume ?? 0
        );

        addHiddenInput(
            container,
            `change_volume[${index}]`,
            item.change_volume ?? 0
        );

        addHiddenInput(
            container,
            `final_volume[${index}]`,
            item.new_volume ?? 0
        );

        addHiddenInput(
            container,
            `unit[${index}]`,
            item.unit ?? ''
        );

        addHiddenInput(
            container,
            `unit_price[${index}]`,
            item.unit_price ?? 0
        );

        addHiddenInput(
            container,
            `total_price[${index}]`,
            item.total_price ?? 0
        );

        addHiddenInput(
            container,
            `reason[${index}]`,
            item.reason ?? ''
        );
    });

    console.log(
        'Jumlah item Justek:',
        justekItems.length
    );

    console.log(
        'Jumlah hidden input:',
        container.querySelectorAll('input').length
    );
}


    /*
    |--------------------------------------------------------------------------
    | ADD HIDDEN
    |--------------------------------------------------------------------------
    */

    function addHidden(container, name, value) {

        const input =
            document.createElement('input');

        input.type = 'hidden';

        input.name = name;

        input.value =
            value ?? '';

        container.appendChild(input);
    }


    /*
    |--------------------------------------------------------------------------
    | SUMMARY
    |--------------------------------------------------------------------------
    */

    function calculateSummary() {

        const subtotal =
            justekItems.reduce(
                (sum, item) =>
                    sum + parseNumber(item.total_price),
                0
            );

        const discount =
            parseNumber(
                document.getElementById(
                    'justek_discount'
                )?.value
            );

        const subtotalAfterDiscount =
            subtotal - discount;

        const taxRate =
            parseNumber(
                document.getElementById(
                    'justek_tax_rate'
                )?.value
            );

        const tax =
            subtotalAfterDiscount *
            (taxRate / 100);

        const shipping =
            parseNumber(
                document.getElementById(
                    'justek_shipping'
                )?.value
            );

        const grandTotal =
            subtotalAfterDiscount +
            tax +
            shipping;

        /*
        |--------------------------------------------------------------------------
        | DISPLAY
        |--------------------------------------------------------------------------
        */

        const subtotalDisplay =
            document.getElementById(
                'justek_subtotalDisplay'
            );

        if (subtotalDisplay) {
            subtotalDisplay.textContent =
                formatRupiah(subtotal);
        }

        const afterDiscountDisplay =
            document.getElementById(
                'justek_subAfterDiscountDisplay'
            );

        if (afterDiscountDisplay) {
            afterDiscountDisplay.textContent =
                formatRupiah(subtotalAfterDiscount);
        }

        const taxDisplay =
            document.getElementById(
                'justek_totalTaxDisplay'
            );

        if (taxDisplay) {
            taxDisplay.textContent =
                formatRupiah(tax);
        }

        const grandTotalDisplay =
            document.getElementById(
                'justek_grandTotalDisplay'
            );

        if (grandTotalDisplay) {
            grandTotalDisplay.textContent =
                formatRupiah(grandTotal);
        }

        const subtotalHidden = document.getElementById('justek_subtotal');

        if (subtotalHidden) {
            subtotalHidden.value =
                subtotal;
        }

        const afterDiscountHidden =
            document.getElementById(
                'justek_subAfterDiscount'
            );

        if (afterDiscountHidden) {
            afterDiscountHidden.value =
                subtotalAfterDiscount;
        }

        const taxHidden =
            document.getElementById(
                'justek_tax_total'
            );

        if (taxHidden) {
            taxHidden.value =
                tax;
        }

        const grandTotalHidden =
            document.getElementById(
                'justek_grand_total'
            );

        if (grandTotalHidden) {
            grandTotalHidden.value =
                grandTotal;
        }

    }

    function setupMoneyInput(displayId, hiddenId) {

        const display =
            document.getElementById(displayId);

        const hidden =
            document.getElementById(hiddenId);

        if (!display || !hidden) {
            return;
        }

        display.addEventListener('focus', function () {

            const value = parseNumber(hidden.value);

            display.value =
                value > 0
                    ? value.toLocaleString('id-ID')
                    : '';

        });

        display.addEventListener('input', function () {
            const value = parseNumber(display.value);
            hidden.value = value;
            calculateSummary();
        });

        display.addEventListener('blur', function () {
            const value = parseNumber(hidden.value);
            display.value = formatRupiah(value);
            calculateSummary();
        });
    }
    function recalculateJustekTotals() {

        const subtotal =
            justekItems.reduce(
                (sum, item) =>
                    sum + (Number(item.total_price) || 0),
                0
            );

        updateJustekTotals(subtotal);
    }

    const discountDisplay = document.getElementById('justek_discount_display');

    const discount = document.getElementById('justek_discount');

    const shippingDisplay = document.getElementById('justek_shipping_display');

    const shipping = document.getElementById('justek_shipping');

    const taxRate = document.getElementById('justek_tax_rate');

    discountDisplay?.addEventListener('input', function () {

        const value = parseDecimal(this.value);

        discount.value = value;

        this.value = formatRupiah(value);

        recalculateJustekTotals();
    });

    shippingDisplay?.addEventListener('input', function () {

        const value = parseDecimal(this.value);

        shipping.value = value;

        this.value = formatRupiah(value);

        recalculateJustekTotals();
    });

    taxRate?.addEventListener('input', function () {

        recalculateJustekTotals();
    });

    document.addEventListener('click', function (event) {

        const button =
            event.target.closest(
                '[data-remove-id]'
            );

        if (!button) {
            return;
        }

        const id =
            Number(button.dataset.removeId);

        removeJustekItem(id);

    });


    /*
    |--------------------------------------------------------------------------
    | EVENT - CHANGE TYPE
    |--------------------------------------------------------------------------
    */

    changeTypeEl?.addEventListener(
        'change',
        handleChangeType
    );


    /*
    |--------------------------------------------------------------------------
    | EVENT - RAB ITEM
    |--------------------------------------------------------------------------
    */

    rabItemEl?.addEventListener(
        'change',
        loadRabItem
    );


    /*
    |--------------------------------------------------------------------------
    | EVENT - VOLUME
    |--------------------------------------------------------------------------
    */

    changeVolumeEl?.addEventListener(
        'input',
        handleVolumeInput
    );


    /*
    |--------------------------------------------------------------------------
    | EVENT - PRICE
    |--------------------------------------------------------------------------
    */

    unitPriceDisplayEl?.addEventListener(
        'input',
        function () {

            const raw =
                unitPriceDisplayEl.value;

            const value =
                parseNumber(raw);

            if (unitPriceEl) {
                unitPriceEl.value =
                    value;
            }

            calculateModalTotal();
        }
    );

    unitPriceDisplayEl?.addEventListener(
        'blur',
        function () {

            const value =
                parseNumber(unitPriceEl?.value);

            unitPriceDisplayEl.value =
                formatRupiah(value);

            calculateModalTotal();
        }
    );


    /*
    |--------------------------------------------------------------------------
    | EVENT - DISCOUNT
    |--------------------------------------------------------------------------
    */

    setupMoneyInput(
        'justek_discount_display',
        'justek_discount'
    );


    /*
    |--------------------------------------------------------------------------
    | EVENT - SHIPPING
    |--------------------------------------------------------------------------
    */

    setupMoneyInput(
        'justek_shipping_display',
        'justek_shipping'
    );


    /*
    |--------------------------------------------------------------------------
    | EVENT - TAX
    |--------------------------------------------------------------------------
    */

    document
        .getElementById('justek_tax_rate')
        ?.addEventListener(
            'input',
            calculateSummary
        );


    /*
    |--------------------------------------------------------------------------
    | MODAL OPEN
    |--------------------------------------------------------------------------
    */

    window.openAddJustekItemModal =
        function () {

            resetJustekModal();

            const modal =
                getModalInstance();

            modal?.show();
        };


    /*
    |--------------------------------------------------------------------------
    | SAVE FUNCTION FOR INLINE ONCLICK
    |--------------------------------------------------------------------------
    */

    window.saveJustekItem = saveJustekItem;


    /*
    |--------------------------------------------------------------------------
    | MODAL HIDDEN
    |--------------------------------------------------------------------------
    */

    modalEl?.addEventListener(
        'hidden.bs.modal',
        function () {

            resetJustekModal();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | FORM SUBMIT
    |--------------------------------------------------------------------------
    */

    form.addEventListener(
        'submit',
        function (event) {

            if (justekItems.length === 0) {

                event.preventDefault();

                alert(
                    'Minimal harus ada 1 item Justifikasi Teknis.'
                );

                return;
            }

            renderJustekHiddenInputs();

            calculateSummary();

        }
    );

    renderJustekItems();

    renderJustekHiddenInputs();

    calculateSummary();

});