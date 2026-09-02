    let rabItems = [];
    let currentMode = 'edit';
    let sortableInstance = null;
    let itemCounter = 0;
    let importedRabItems = [];

    document.addEventListener('DOMContentLoaded', function () {

        const fileInput =
            document.getElementById('justek_import_file');

        if (!fileInput) {
            console.error(
                '#justek_import_file tidak ditemukan.'
            );
            return;
        }

        fileInput.addEventListener(
            'change',
            handleRabExcelFile
        );

    });

    async function handleRabExcelFile(event) {

        const file = event.target.files[0];

        if (!file) {
            return;
        }

        const errorElement = document.getElementById('rabImportError');

        // const previewElement = document.getElementById('rabImportPreview');

        const confirmButton = document.getElementById('btnConfirmImportRab');

        // if (!previewElement) {
        //     console.error(
        //         '#rabImportPreview tidak ditemukan.'
        //     );
        //     return;
        // }

        if (errorElement) {
            errorElement.classList.add('d-none');
            errorElement.innerHTML = '';
        }

        if (confirmButton) {
            confirmButton.disabled = true;
        }

        // previewElement.innerHTML = `
        //     <div class="text-muted">
        //         Membaca file Excel...
        //     </div>
        // `;

        try {

            const buffer = await file.arrayBuffer();

            const workbook =
                XLSX.read(buffer, {
                    type: 'array'
                });

            if (!workbook.SheetNames.length) {

                throw new Error(
                    'File Excel tidak memiliki sheet.'
                );

            }

            const firstSheetName =
                workbook.SheetNames.find(
                    name => normalizeExcelHeader(name) === 'rab'
                ) ||
                workbook.SheetNames[1] ||
                workbook.SheetNames[0];

            const worksheet = workbook.Sheets[firstSheetName];

            console.log('Sheet yang dipakai:', firstSheetName);

            importedRabItems = validateRabExcelWorksheet(worksheet);

            // renderRabImportPreview(
            //     importedRabItems
            // );

            if (confirmButton) {

                confirmButton.disabled =
                    importedRabItems.length === 0;
            }

        } catch (error) {
            console.error(
                'Error import Excel:',
                error
            );

            importedRabItems = [];

            if (errorElement) {

                errorElement.innerHTML =
                    escapeHtml(
                        error.message ||
                        'Terjadi kesalahan saat membaca file Excel.'
                    );

                errorElement.classList.remove(
                    'd-none'
                );

            }

            // previewElement.innerHTML = `
            //     <div class="alert alert-danger mb-0">
            //         ${escapeHtml(
            //             error.message ||
            //             'Terjadi kesalahan saat membaca file Excel.'
            //         )}
            //     </div>
            // `;

            if (confirmButton) {
                confirmButton.disabled = true;
            }

        }
    }

    function resolveMergedCellValue(worksheet, colLetter, rowNumber) {

        if (!colLetter) {
            return undefined;
        }

        const directCell =
            worksheet[`${colLetter}${rowNumber}`];

        if (directCell && directCell.v !== undefined && directCell.v !== '') {
            return directCell.v;
        }

        const merges = worksheet['!merges'] || [];

        const colIndex =
            XLSX.utils.decode_col(colLetter);

        const rowIndex =
            rowNumber - 1;

        for (const merge of merges) {

            const withinRow =
                rowIndex >= merge.s.r &&
                rowIndex <= merge.e.r;

            const withinCol =
                colIndex >= merge.s.c &&
                colIndex <= merge.e.c;

            if (withinRow && withinCol) {

                const anchorCol =
                    XLSX.utils.encode_col(merge.s.c);

                const anchorRow =
                    merge.s.r + 1;

                const anchorCell =
                    worksheet[`${anchorCol}${anchorRow}`];

                return anchorCell?.v;
            }
        }

        return directCell?.v;
    }

    function getUraianCellValue(worksheet, excelRow, columns) {

        const startColIndex =
            XLSX.utils.decode_col(columns.uraian);

        const boundaryColIndexes = [];

        if (columns.satuan) {
            boundaryColIndexes.push(
                XLSX.utils.decode_col(columns.satuan)
            );
        }

        if (columns.volume) {
            boundaryColIndexes.push(
                XLSX.utils.decode_col(columns.volume)
            );
        }

        const endColIndex =
            boundaryColIndexes.length
                ? Math.min(...boundaryColIndexes) - 1
                : startColIndex + 5;

        for (
            let colIndex = startColIndex;
            colIndex <= endColIndex;
            colIndex++
        ) {

            const colLetter =
                XLSX.utils.encode_col(colIndex);

            const value =
                resolveMergedCellValue(
                    worksheet,
                    colLetter,
                    excelRow
                );

            if (
                value !== undefined &&
                value !== null &&
                String(value).trim() !== ''
            ) {
                return value;
            }
        }

        return '';
    }

    function normalizeExcelHeader(value) {

        return String(value ?? '')
            .trim()
            .toLowerCase()
            .replace(/\s+/g, '_');
    }
    function normalizeExcelCell(value) {

        return String(value ?? '')
            .replace(/\u00A0/g, ' ')
            .replace(/\r?\n/g, ' ')
            .replace(/\s+/g, ' ')
            .trim()
            .toUpperCase();

    }

    function findRabColumnMap(worksheet, range) {

        const headerAliases = {
            no:      ['no'],
            uraian:  ['uraian_pekerjaan', 'uraian', 'pekerjaan', 'uraian_pekerjaan.'],
            satuan:  ['sat', 'satuan'],
            volume:  ['vol', 'volume'],
            harga:   ['harga_bahan', 'harga_satuan', 'harga'],
            jumlah:  ['jumlah_harga', 'jumlah', 'total_harga', 'total']
        };

        const maxHeaderScanRow =
            Math.min(range.e.r, range.s.r + 100); // batasi scan 100 baris pertama saja

        for (
            let rowIndex = range.s.r;
            rowIndex <= maxHeaderScanRow;
            rowIndex++
        ) {

            const excelRow = rowIndex + 1;
            const found = {};

            for (
                let colIndex = range.s.c;
                colIndex <= range.e.c;
                colIndex++
            ) {

                const colLetter = XLSX.utils.encode_col(colIndex);

                const cell = worksheet[`${colLetter}${excelRow}`];

                const normalized = normalizeExcelHeader(cell?.v);

                if (!normalized) {
                    continue;
                }

                for (const [key, aliases] of Object.entries(headerAliases)) {

                    if (found[key]) {
                        continue; // Kolom untuk key ini sudah ketemu, jangan ditimpa
                    }

                    const isMatch = aliases.some(alias =>
                        normalized === alias ||
                        normalized.includes(alias)
                    );

                    if (isMatch) {
                        found[key] = colLetter;
                    }
                }
            }

            if (
                found.no &&
                found.uraian &&
                found.volume &&
                found.harga
            ) {

                console.log(
                    `Header RAB ditemukan di baris ${excelRow}:`,
                    found
                );

                return {
                    headerRow: excelRow,
                    columns: {
                        no: found.no,
                        uraian: found.uraian,
                        satuan: found.satuan || null,
                        volume: found.volume,
                        harga: found.harga,
                        jumlah: found.jumlah || null
                    }
                };
            }
        }

        throw new Error(
            'Header Excel (NO, URAIAN PEKERJAAN, SAT, VOL, HARGA) tidak ' +
            'ditemukan pada 100 baris pertama. Pastikan format file sesuai ' +
            'template RAB.'
        );
    }

    function validateRabExcelWorksheet(worksheet) {

        const result = [];

        let currentFloor = '';
        let currentCategory = '';
        let currentJobType = '';

        const range = XLSX.utils.decode_range(
            worksheet['!ref']
        );

        const { headerRow, columns } = findRabColumnMap(worksheet, range);
        console.log('Kolom yang terdeteksi:', columns);
        console.log('Baris header:', headerRow);

        for (
            let rowIndex = range.s.r;
            rowIndex <= range.e.r;
            rowIndex++
        ) {

            const excelRow = rowIndex + 1;

            // Lewati semua baris judul/deskripsi sebelum dan termasuk baris header
            if (excelRow <= headerRow) {
                continue;
            }

            const getCellValue = (column) => {

                if (!column) {
                    return '';
                }

                return resolveMergedCellValue(
                    worksheet,
                    column,
                    excelRow
                ) ?? '';

            };

            const no =
                String(
                    getCellValue(columns.no)
                ).trim();

            const uraian =
                String(
                    getUraianCellValue(worksheet, excelRow, columns)
                ).trim();

            const satuan =
                String(
                    getCellValue(columns.satuan)
                ).trim();

            const volumeRaw =
                getCellValue(columns.volume);

            const hargaRaw =
                getCellValue(columns.harga);

            console.log(
                `Excel row ${excelRow}:`,
                {
                    no,
                    uraian,
                    satuan,
                    volumeRaw,
                    hargaRaw
                }
            );

            if (
                !no &&
                !uraian &&
                !satuan &&
                volumeRaw === '' &&
                hargaRaw === ''
            ) {

                continue;

            }

            const normalizedNo =
                no.toUpperCase();

            const normalizedUraian =
                uraian
                    .toUpperCase()
                    .replace(/\s+/g, ' ')
                    .trim();

            const normalizedSatuan =
                satuan.toUpperCase();

            const normalizedVolume =
                String(volumeRaw)
                    .toUpperCase()
                    .trim();


            if (
                normalizedNo === 'NO' ||
                normalizedUraian === 'URAIAN PEKERJAAN' ||
                normalizedSatuan === 'SAT' ||
                normalizedVolume === 'VOL'
            ) {

                console.log(
                    `Skip header row ${excelRow}`
                );

                continue;

            }

            const floorText =
                no || uraian;


            if (
                /^LANTAI\s+/i.test(
                    floorText
                )
            ) {

                currentFloor =
                    floorText.trim();

                currentCategory = '';
                currentJobType = '';

                console.log(
                    'LANTAI:',
                    currentFloor
                );

                continue;

            }

            if (
                /^[A-Z]+$/.test(no) &&
                uraian
            ) {

                currentCategory =
                    uraian.trim();

                currentJobType = '';

                console.log(
                    'KATEGORI:',
                    currentCategory
                );

                continue;

            }

            const isNumberNo =
                /^\d+$/.test(no);


            const hasNoSatuan =
                !satuan;


            const hasNoVolume =
                volumeRaw === '' ||
                volumeRaw === null ||
                volumeRaw === undefined;


            if (
                isNumberNo &&
                uraian &&
                hasNoSatuan &&
                hasNoVolume
            ) {

                currentJobType =
                    uraian.trim();

                console.log(
                    'TIPE PEKERJAAN:',
                    currentJobType
                );

                continue;

            }

            const hasUraian =
                !!uraian;

            const hasSatuan =
                !!satuan;

            const hasVolume =
                volumeRaw !== '' &&
                volumeRaw !== null &&
                volumeRaw !== undefined;


            if (
                !hasUraian ||
                !hasSatuan ||
                !hasVolume
            ) {

                continue;

            }

            if (isNumberNo) {
                currentJobType = '';
            }

            const floorName = currentFloor || null;

            if (!currentCategory) {

                throw new Error(
                    `Baris ${excelRow}: Kategori belum ditemukan untuk "${uraian}".`
                );

            }

            const volume =
                parseExcelNumber(
                    volumeRaw
                );

            const basePrice =
                parseExcelNumber(
                    hargaRaw
                );

            if (
                !Number.isFinite(volume) ||
                volume <= 0
            ) {

                throw new Error(
                    `Baris ${excelRow}: Volume "${uraian}" tidak valid.`
                );

            }

            if (
                !Number.isFinite(basePrice) ||
                basePrice < 0
            ) {

                throw new Error(
                    `Baris ${excelRow}: Harga satuan "${uraian}" tidak valid.`
                );

            }

            const price = calculateItemPrice(basePrice);

            const total = volume * price;

            result.push({
                temp_id: 'item_' + (++itemCounter),
                floor_name: floorName,
                category_name: currentCategory,
                job_name: uraian,
                description: currentJobType || '',
                satuan: satuan,
                volume: volume,
                base_price: basePrice,
                price: price,
                total: total,
                order_no: rabItems.length + result.length + 1
            });


            console.log(
                'ITEM IMPORT:',
                result[result.length - 1]
            );

        }

        if (!result.length) {

            throw new Error(
                'Tidak ditemukan detail item pekerjaan pada Excel.'
            );

        }

        console.log(
            'TOTAL ITEM IMPORT:',
            result.length
        );

        return result;
    }
    function parseExcelNumber(value) {

        if (typeof value === 'number') {
            return value;
        }

        if (value === null ||
            value === undefined ||
            value === '') {

            return 0;
        }

        let str =
            String(value)
                .trim()
                .replace(/Rp/gi, '')
                .replace(/\s/g, '');

        // Format Indonesia: 1.500.000,50
        if (str.includes(',') &&
            str.includes('.')) {

            str = str
                .replace(/\./g, '')
                .replace(',', '.');

        } else if (str.includes(',')) {

            str = str.replace(',', '.');

        } else {

            // Angka seperti 1.500.000
            if (
                /^\d{1,3}(\.\d{3})+$/.test(str)
            ) {
                str = str.replace(/\./g, '');
            }
        }

        const number =
            parseFloat(str);

        return Number.isFinite(number)
            ? number
            : 0;
    }

    function importRabFromExcel() {

        if (!importedRabItems.length) {
            alert('Tidak ada data yang dapat diimport.');
            return;
        }

        rabItems.push(
            ...importedRabItems
        );

        // Rapikan nomor urut
        rabItems.forEach((item, index) => {
            item.order_no = index + 1;
        });

        renderRabItems();

        renderFloorOptions();

        renderCategoryOptions();

        calculateSummary();

        const modalElement = document.getElementById('importRabItemModal');

        const modal = bootstrap.Modal.getInstance(modalElement);

        if (modal) {
            modal.hide();
        }

        importedRabItems = [];
    }
    window.importRabFromExcel = importRabFromExcel;
    function parseRupiah(value) {
        if (value === null || value === undefined || value === '') {
            return 0;
        }

        let str = String(value)
            .trim()
            .replace(/Rp/gi, '')
            .replace(/\s/g, '');

    
        if (str.includes(',')) {
            str = str.replace(/\./g, '');
            str = str.replace(',', '.');
        }

        return parseFloat(str) || 0;
    }
    function formatRupiah(value) {

        value = Number(value) || 0;

        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(value);
    }

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
    function initRabSelect2() {

        const floorSelect = $('#justek_item_floor');

        if (floorSelect.hasClass('select2-hidden-accessible')) {
            floorSelect.select2('destroy');
        }

        floorSelect.select2({
            theme: 'bootstrap-5',
            width: '100%',
            dropdownParent: $('#addRabItemModal'),
            placeholder: '-- Pilih Lantai --',
            allowClear: true
        });


        const categorySelect = $('#justek_item_category');

        if (categorySelect.hasClass('select2-hidden-accessible')) {
            categorySelect.select2('destroy');
        }

        categorySelect.select2({
            theme: 'bootstrap-5',
            width: '100%',
            dropdownParent: $('#addRabItemModal'),
            placeholder: '-- Pilih Kategori --',
            allowClear: true
        });

    }
    function getRabFloors() {

        return [
            ...new Set(
                rabItems
                    .map(item => item.floor_name)
                    .filter(value => value && value.trim() !== '')
            )
        ];
    }

    function getRabCategories(floor = null) {

        let items = rabItems;

        if (floor && floor.trim() !== '') {
            items = items.filter(item =>
                (item.floor_name || '').trim() === floor.trim()
            );
        }

        return [
            ...new Set(
                items
                    .map(item => (item.category_name || '').trim())
                    .filter(value => value !== '')
            )
        ];
    }
    function renderFloorOptions(selectedValue = '') {

        const select =
            document.getElementById('justek_item_floor');

        if (!select) return;

        const floors = getRabFloors();

        select.innerHTML = `
            <option value="">
                -- Pilih Lantai --
            </option>
        `;

        // Lantai yang sudah pernah digunakan
        floors.forEach(floor => {

            const option =
                document.createElement('option');

            option.value = floor;
            option.textContent = floor;

            select.appendChild(option);

        });

        // Selalu tampilkan pilihan tambah lantai
        const newOption =
            document.createElement('option');

        newOption.value = '__new__';
        newOption.textContent = '+ Tambah Lantai Baru';

        select.appendChild(newOption);


        if (selectedValue) {

            select.value = selectedValue;

        }

    }
    function renderCategoryOptions(floor = null) {

        const select =
            document.getElementById('justek_item_category');

        if (!select) return;

        const categories =
            getRabCategories(floor);

        select.innerHTML = `
            <option value="">
                -- Pilih Kategori --
            </option>
        `;

        categories.forEach(category => {

            const option =
                document.createElement('option');

            option.value = category;
            option.textContent = category;

            select.appendChild(option);

        });

        const newOption =
            document.createElement('option');

        newOption.value = '__new__';
        newOption.textContent =
            '+ Tambah Kategori Baru';

        select.appendChild(newOption);
    }
    function handleFloorChange() {

        const select =
            document.getElementById('justek_item_floor');

        const value = select.value;

        if (value === '__new__') {

            showNewFloorInput();

            return;

        }

        renderCategoryOptions(value);

    }
    function showNewFloorInput() {

        document.getElementById('floorSelectWrapper').classList.add('d-none');

        document
            .getElementById('floorInputWrapper')
            .classList.remove('d-none');

        document
            .getElementById('justek_item_floor_new')
            .value = '';

        document
            .getElementById('justek_item_floor_new')
            .focus();

    }
    function cancelNewFloor() {

        document
            .getElementById('floorInputWrapper')
            .classList.add('d-none');

        document
            .getElementById('floorSelectWrapper')
            .classList.remove('d-none');

        renderFloorOptions();

    }
    window.cancelNewFloor = cancelNewFloor;
    function showNewCategoryInput() {

        document
            .getElementById('categorySelectWrapper')
            .classList.add('d-none');

        document
            .getElementById('categoryInputWrapper')
            .classList.remove('d-none');

        document
            .getElementById('justek_item_category_new')
            .value = '';

        document
            .getElementById('justek_item_category_new')
            .focus();

    }
    function cancelNewCategory() {

        document
            .getElementById('categoryInputWrapper')
            .classList.add('d-none');

        document
            .getElementById('categorySelectWrapper')
            .classList.remove('d-none');

        const floor =
            document.getElementById('justek_item_floor').value;

        renderCategoryOptions(floor);

    }
    window.cancelNewCategory = cancelNewCategory;
    function getSelectedFloor() {

        const select =
            document.getElementById('justek_item_floor');

        const newInput =
            document.getElementById('justek_item_floor_new');

        if (
            !document
                .getElementById('floorInputWrapper')
                .classList.contains('d-none')
        ) {

            return newInput.value.trim();

        }

        return select.value.trim();
    }
    function getSelectedCategory() {

        const select =
            document.getElementById('justek_item_category');

        const newInput =
            document.getElementById('justek_item_category_new');

        if (
            !document
                .getElementById('categoryInputWrapper')
                .classList.contains('d-none')
        ) {

            return newInput.value.trim();

        }

        return select.value.trim();
    }
    function openAddJustekItemModal() {
        renderFloorOptions();

        renderCategoryOptions();

        document.getElementById('justek_item_floor').value = '';
        document.getElementById('justek_item_category').value = '';
        document.getElementById('justek_item_job_name').value = '';
        document.getElementById('justek_item_description').value = '';
        document.getElementById('justek_item_volume').value = '';
        document.getElementById('justek_item_satuan').value = '';

        const price = document.getElementById('justek_item_price_display');

        price.value = '';
        price.dataset.value = 0;

        document.getElementById('justek_item_price').value = '';

        const modal = new bootstrap.Modal(
            document.getElementById('addRabItemModal')
        );

        modal.show();
    }
    window.openAddJustekItemModal = openAddJustekItemModal;
    function openImportJustekItemModal() {
        const modalElement = document.getElementById('importRabItemModal');

        if (!modalElement) {
            console.error('Modal importRabItemModal tidak ditemukan!');
            return;
        }

        // Reset input file
        const fileInput = document.getElementById('justek_import_file');

        if (fileInput) {
            fileInput.value = '';
        }

        const preview = document.getElementById('rabImportPreview');

        if (preview) {
            preview.innerHTML = '';
        }

        const modal = new bootstrap.Modal(modalElement);

        modal.show();
    }
        window.openImportJustekItemModal = openImportJustekItemModal;
    function saveRabItem() {

        const floor = getSelectedFloor();

        const category = getSelectedCategory();

        const jobName = document
            .getElementById('justek_item_job_name')
            .value
            .trim();

        const description = document
            .getElementById('justek_item_description')
            .value
            .trim();
        const volumeInput = document.getElementById('justek_item_volume').value;
        const volume = parseDecimal(volumeInput);
        const satuan = document.getElementById('justek_item_satuan').value.trim();
        const basePrice = parseRupiah(document.getElementById('justek_item_price_display').value);

        if (!category) {
            alert('Kategori pekerjaan wajib diisi.');
            document.getElementById('justek_item_category').focus();
            return;
        }

        if (!jobName) {
            alert('Nama pekerjaan wajib diisi.');
            document.getElementById('justek_item_job_name').focus();
            return;
        }

        if (!satuan) {
            alert('Satuan wajib diisi.');
            document.getElementById('justek_item_satuan').focus();
            return;
        }

        if (volume <= 0) {
            alert('Volume harus lebih besar dari 0.');
            document.getElementById('justek_item_volume').focus();
            return;
        }

        if (basePrice < 0) {
            alert('Harga satuan tidak valid.');
            document.getElementById('justek_item_price_display').focus();
            return;
        }

        const price = calculateItemPrice(basePrice);

        const total = volume * price;

        rabItems.push({
            temp_id: 'item_' + (++itemCounter),
            floor_name: floor,
            category_name: category,
            job_name: jobName,
            description: description,
            satuan: satuan,
            volume: volume,
            base_price: basePrice,
            price: price,
            total: total,
            order_no: rabItems.length + 1
        });

        renderRabItems();
        renderFloorOptions();
        renderCategoryOptions(floor);
        calculateSummary();

        const modalElement = document.getElementById('addRabItemModal');

        const modal = bootstrap.Modal.getInstance(modalElement);

        if (modal) {
            modal.hide();
        }
    }
    window.saveRabItem = saveRabItem;
    function calculateItemPrice(basePrice) {

        basePrice = Number(basePrice) || 0;

        const profit =
            Number(
                document.getElementById('justek_profit_display')?.value
            ) || 0;

        const overhead =
            Number(
                document.getElementById('justek_overhead_display')?.value
            ) || 0;

        const overheadAmount =
            basePrice * overhead / 100;

        const profitAmount =
            basePrice * profit / 100;

        return basePrice
            + overheadAmount
            + profitAmount;
    }
    function recalculateAllItems() {

        rabItems.forEach(item => {

            const basePrice =
                Number(item.base_price) || 0;

            item.price =
                calculateItemPrice(basePrice);

            item.total =
                Number(item.volume || 0) *
                item.price;

        });

        renderRabItems();

        calculateSummary();
    }
    function renderRabItems() {

        const tbody =
            document.getElementById('justek_offerItemsBody');

        if (!tbody) return;

        tbody.innerHTML = '';

        if (rabItems.length === 0) {

            tbody.innerHTML = `
                <tr class="empty-rab-row">
                    <td colspan="7"
                        class="text-center text-muted py-5">

                        Belum ada pekerjaan.
                        <br>

                    </td>
                </tr>
            `;

            return;
        }

        const floors = [];

        rabItems.forEach(item => {

            const floorName =
                item.floor_name || 'Tanpa Lantai';

            if (!floors.includes(floorName)) {
                floors.push(floorName);
            }

        });


        let globalNo = 0;
        let categoryLetterIndex = 0;


        floors.forEach(floorName => {

            tbody.insertAdjacentHTML(
                'beforeend',
                `
                <tr class="floor-row table-light fw-bold"
                    data-floor="${escapeHtml(floorName)}">

                    <td colspan="7"
                        class="py-2">

                        <span class="me-2">
                            🏢
                        </span>

                        ${escapeHtml(floorName)}

                    </td>

                </tr>
                `
            );

            const floorItems =
                rabItems.filter(item => {

                    const itemFloor =
                        item.floor_name?.trim() || 'Tanpa Lantai';

                    return itemFloor === floorName;

                });

            const categories = [];

            floorItems.forEach(item => {

                const category =
                    item.category_name || 'Tanpa Kategori';

                if (!categories.includes(category)) {
                    categories.push(category);
                }

            });


            categories.forEach(categoryName => {

                const categoryItems =
                    floorItems.filter(item =>
                        item.category_name === categoryName
                    );


                const letter =
                    numberToLetters(categoryLetterIndex);

                categoryLetterIndex++;

                const categoryTotal =
                    categoryItems.reduce(
                        (sum, item) =>
                            sum + Number(item.total || 0),
                        0
                    );


                tbody.insertAdjacentHTML(
                    'beforeend',
                    `
                    <tr class="category-row table-secondary fw-bold"
                        data-floor="${escapeHtml(floorName)}"
                        data-category="${escapeHtml(categoryName)}">

                        <td>
                            <span class="drag-handle me-2"
                                style="cursor:move">

                                <i class="ti ti-grip-vertical"></i>

                            </span>

                            ${letter}
                        </td>

                        <td colspan="4">

                            ${escapeHtml(categoryName)}

                        </td>

                        <td>

                            <input type="text"
                                class="form-control subtotal-category"
                                value="${formatRupiah(categoryTotal)}"
                                readonly>

                        </td>

                        <td>

                            <button type="button"
                                    class="btn btn-sm btn-danger"
                                    onclick="removeCategory(
                                        '${escapeAttribute(floorName)}',
                                        '${escapeAttribute(categoryName)}'
                                    )">

                                −

                            </button>

                        </td>

                    </tr>
                    `
                );

                let displayNo = 0;
                let previousDescription = null;
 
                categoryItems.forEach((item) => {
 
                    const currentDescription =
                        item.description || '';
 
                    const isNewGroup =
                        !currentDescription ||
                        currentDescription !== previousDescription;
 
                    if (isNewGroup) {
                        displayNo++;
                    }
 
                    previousDescription = currentDescription;
 
                    globalNo++;
 
                    tbody.insertAdjacentHTML(
                        'beforeend',
                        `
                        <tr class="job-row"
                            id="${item.temp_id}"
                            data-id="${item.temp_id}"
                            data-floor="${escapeHtml(item.floor_name)}"
                            data-category="${escapeHtml(item.category_name)}">
 
                            <td class="text-center">
 
                                ${displayNo}
 
                            </td>
 
                            <td>
 
                                <div class="d-flex align-items-start gap-2">
 
                                    <span class="drag-handle"
                                        style="cursor:move">
 
                                        <i class="ti ti-grip-vertical"></i>
 
                                    </span>
 
                                    <div>
 
                                        <div class="fw-medium">
 
                                            ${escapeHtml(item.job_name)}
 
                                        </div>
 
                                        ${
                                            item.description
                                                ?
                                            `
                                            <small class="text-muted">
                                                ${escapeHtml(item.description)}
                                            </small>
                                            `
                                                :
                                            ''
                                        }
 
                                    </div>
 
                                </div>
 
                            </td>
 
 
                            <td>
 
                                ${escapeHtml(item.satuan)}
 
                            </td>
 
 
                            <td>
 
                                <input type="number"
                                    class="form-control vol"
                                    value="${item.volume}"
                                    min="0"
                                    step="any"
                                    onchange="updateItemVolume(
                                        '${item.temp_id}',
                                        this.value
                                    )">
 
                            </td>
 
 
                            <td>
 
                                <input type="text"
                                    class="form-control harga"
                                    value="${formatRupiah(item.price)}"
                                    onchange="updateItemPrice(
                                        '${item.temp_id}',
                                        this
                                    )">
 
                            </td>
 
                            <td>
 
                                <input type="text"
                                    class="form-control total"
                                    value="${formatRupiah(item.total)}"
                                    readonly>
 
                            </td>
 
 
                            <td>
 
                                <button type="button"
                                        class="btn btn-sm btn-danger"
                                        onclick="removeRabItem(
                                            '${item.temp_id}'
                                        )">
 
                                    −
 
                                </button>
 
                            </td>
 
                        </tr>
                        `
                    );
 
                });
 

            });

        });

        updateSortable();
    }

    function updateItemVolume(id, value) {
        const item =
            rabItems.find(item =>
                item.temp_id === id
            );
        if (!item) return;
        item.volume = Number(value) || 0;
        item.total = item.volume * item.price;
        renderRabItems();
        calculateSummary();
    }

    function updateItemPrice(id, element) {
        const item = rabItems.find(item =>item.temp_id === id);
        if (!item) return;
        const basePrice = parseRupiah(element.value);
        item.base_price = basePrice;
        item.price = calculateItemPrice(basePrice);
        item.total = Number(item.volume || 0) * item.price;
        renderRabItems();
        calculateSummary();
    }

    function removeRabItem(id) {
        const index =
            rabItems.findIndex(item =>
                item.temp_id === id
            );
        if (index === -1) return;
        rabItems.splice(index, 1);
        normalizeOrder();
        renderRabItems();
        calculateSummary();
    }

    function removeCategory(floorName, categoryName) {

        if (!confirm(
            `Hapus seluruh pekerjaan kategori "${categoryName}"?`
        )) {
            return;
        }

        rabItems = rabItems.filter(item => {

            return !(
                item.floor_name === floorName &&
                item.category_name === categoryName
            );

        });

        normalizeOrder();

        renderRabItems();

        calculateSummary();
    }

    function normalizeOrder() {

        rabItems.forEach((item, index) => {

            item.order_no = index + 1;

        });

    }

    function numberToLetters(num) {

        let letters = '';

        num = num + 1;

        while (num > 0) {

            const rem =
                (num - 1) % 26;

            letters =
                String.fromCharCode(
                    65 + rem
                ) + letters;

            num =
                Math.floor(
                    (num - 1) / 26
                );
        }

        return letters;
    }

    function calculateSummary() {

        const subtotal = rabItems.reduce(
            (sum, item) => {

                return sum +
                    (Number(item.total) || 0);

            },
            0
        );

        const discount =
            parseRupiah(
                document.getElementById('justek_discount_display')?.value
            ) || 0;


        const subtotalAfterDiscount =
            Math.max(
                0,
                subtotal - discount
            );

        const taxRate =
            parseFloat(
                document.getElementById('justek_tax_rate')?.value
            ) || 0;


        const taxTotal =
            subtotalAfterDiscount *
            taxRate /
            100;

        const shipping =
            parseRupiah(
                document.getElementById('justek_shipping_display')?.value
            ) || 0;

        const grandTotal =
            subtotalAfterDiscount +
            taxTotal +
            shipping;

        document.getElementById('justek_subtotalDisplay').textContent = formatRupiah(subtotal);

        document.getElementById('justek_subAfterDiscountDisplay').textContent = formatRupiah(subtotalAfterDiscount);

        document.getElementById('justek_totalTaxDisplay').textContent = formatRupiah(taxTotal);

        document.getElementById('justek_grandTotalDisplay').textContent = formatRupiah(grandTotal);

        document.getElementById('justek_subtotal').value = subtotal;

        document.getElementById('justek_discount').value = discount;

        document.getElementById('justek_subAfterDiscount').value = subtotalAfterDiscount;

        document.getElementById('justek_tax_total').value = taxTotal;

        document.getElementById('justek_shipping').value = shipping;

        document.getElementById('justek_grand_total').value = grandTotal;
    }

    function initRupiahInputs() {

        const discountInput = document.getElementById('justek_discount_display');

        if (discountInput) {

            discountInput.addEventListener('input', function () {

                const value = parseRupiah(this.value);

                document.getElementById('justek_discount').value = value;

                calculateSummary();
            });

            discountInput.addEventListener('blur', function () {

                const value = parseRupiah(this.value);

                this.value = value > 0
                    ? formatRupiah(value)
                    : '';

                document.getElementById('justek_discount').value = value;
            });
        }

        const shippingInput = document.getElementById('justek_shipping_display');

        if (shippingInput) {

            shippingInput.addEventListener('input', function () {

                const value = parseRupiah(this.value);

                document.getElementById('justek_shipping').value = value;

                calculateSummary();
            });

            shippingInput.addEventListener('blur', function () {

                const value = parseRupiah(this.value);

                this.value = value > 0
                    ? formatRupiah(value)
                    : '';

                document.getElementById('justek_shipping').value = value;
            });
        }

        const price = document.getElementById('justek_item_price_display');

        if (price) {
            price.addEventListener('input', function () {
                this.value = this.value.replace(/[^0-9.,]/g, '');
            });

            price.addEventListener('blur', function () {
                const value = parseRupiah(this.value);
                this.dataset.value = value;
                this.value = formatRupiah(value);
            });
        }
    }

    function updateSortable() {

        if (sortableInstance) {

            sortableInstance.destroy();

            sortableInstance = null;

        }


        if (currentMode !== 'drag') {
            return;
        }

        const tbody = document.getElementById(
                'justek_offerItemsBody'
            );

        if (!tbody) return;


        sortableInstance =
            new Sortable(tbody, {

                animation: 150,

                handle: '.drag-handle',

                draggable: '.job-row',

                onEnd: function () {

                    const rows =
                        tbody.querySelectorAll(
                            '.job-row'
                        );


                    const newOrder = [];


                    rows.forEach(row => {

                        const item =
                            rabItems.find(
                                item =>
                                    item.temp_id ===
                                    row.dataset.id
                            );

                        if (item) {

                            newOrder.push(item);

                        }

                    });

                    rabItems = newOrder;

                    normalizeOrder();

                    renderRabItems();

                }

            });

    }

    function setModeCreate(mode) {

        currentMode = mode;


        const btnEdit =
            document.getElementById(
                'tombolUbah'
            );

        const btnDrag =
            document.getElementById(
                'tombolGeser'
            );


        if (btnEdit) {

            btnEdit.classList.toggle(
                'btn-dark',
                mode === 'edit'
            );

            btnEdit.classList.toggle(
                'btn-outline-secondary',
                mode !== 'edit'
            );

        }


        if (btnDrag) {

            btnDrag.classList.toggle(
                'btn-dark',
                mode === 'drag'
            );

            btnDrag.classList.toggle(
                'btn-outline-secondary',
                mode !== 'drag'
            );

        }


        if (mode === 'drag') {

            document.body.classList.add(
                'drag-mode'
            );

        } else {

            document.body.classList.remove(
                'drag-mode'
            );

        }
        updateSortable();
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

                const div =
        document.createElement('div');

    div.textContent =
        value ?? '';

    return div.innerHTML;

    }

    function escapeAttribute(value) {

        return String(value || '')
            .replace(/\\/g, '\\\\')
            .replace(/'/g, "\\'");

    }

    function prepareRabItemsForSubmit() {
        const profitDisplay = document.getElementById('justek_profit_display');

        const overheadDisplay = document.getElementById('justek_overhead_display');

        document.getElementById('justek_profit').value = Number(profitDisplay?.value || 0);

        document.getElementById('justek_overhead').value = Number(overheadDisplay?.value || 0);
        const container =
            document.getElementById(
                'justekItemsContainer'
            );

        if (!container) return;


        container.innerHTML = '';


        rabItems.forEach(
            (item, index) => {
                const fields = {
                    floor_name: item.floor_name,
                    category_name: item.category_name,
                    job_name: item.job_name,
                    description: item.description || '',
                    satuan: item.satuan,
                    volume: item.volume,
                    base_price: item.base_price,
                    price: item.price,
                    total: item.total,
                    order_no: index + 1
                };

                Object.entries(fields)
                    .forEach(
                        ([key, value]) => {

                            const input =
                                document.createElement(
                                    'input'
                                );

                            input.type =
                                'hidden';

                            input.name =
                                `items[${index}][${key}]`;

                            input.value =
                                value ?? '';

                            container.appendChild(
                                input
                            );

                        }
                    );

            }
        );

    }

    document.addEventListener('DOMContentLoaded', function () {
        initRupiahInputs();
        calculateSummary();
        const profitInput = document.getElementById('justek_profit_display');

        if (profitInput) {

            profitInput.addEventListener(
                'input',
                recalculateAllItems
            );

        }

        const overheadInput = document.getElementById('justek_overhead_display');
        if (overheadInput) {
            overheadInput.addEventListener(
                'input',
                recalculateAllItems
            );
        }

        const discountInput = document.getElementById('justek_discount_display');
        if (discountInput) {

            discountInput.addEventListener(
                'input',
                calculateSummary
            );

        }

        const taxInput = document.getElementById('justek_tax_rate');

        if (taxInput) {

            taxInput.addEventListener(
                'input',
                calculateSummary
            );

        }

        const shippingInput = document.getElementById('justek_shipping_display');

        if (shippingInput) {

            shippingInput.addEventListener(
                'input',
                calculateSummary
            );

        }

        const editButton = document.getElementById('tombolUbah');

        if (editButton) {

            editButton.addEventListener(
                'click',
                function () {

                    setModeCreate('edit');

                }
            );

        }

        const dragButton = document.getElementById('tombolGeser');

        if (dragButton) {

            dragButton.addEventListener(
                'click',
                function () {

                    setModeCreate('drag');

                }
            );

        }

        const form = document.getElementById('justekForm');

        if (form) {

            form.addEventListener(
                'submit',
                function () {

                    prepareRabItemsForSubmit();

                }
            );

        }
        const floorSelect = document.getElementById('justek_item_floor');

        if (floorSelect) {

            floorSelect.addEventListener(
                'change',
                handleFloorChange
            );

        }

        const categorySelect = document.getElementById('justek_item_category');

        if (categorySelect) {
            categorySelect.addEventListener(
                'change',
                function () {

                    if (this.value === '__new__') {

                        showNewCategoryInput();

                    }

                }
            );

        }
        renderRabItems();

    });