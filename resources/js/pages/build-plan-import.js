import * as XLSX from 'xlsx';

document.addEventListener('DOMContentLoaded', () => {

    const fileInput = document.getElementById('importPlanFile');
    const form = document.getElementById('importPlanForm');

    const fileInfo = document.getElementById('importFileInfo');
    const fileName = document.getElementById('importFileName');

    const loading = document.getElementById('importPreviewLoading');
    const errorBox = document.getElementById('importPreviewError');

    const summary = document.getElementById('importPreviewSummary');
    const previewWrapper = document.getElementById('importPreviewWrapper');
    const previewBody = document.getElementById('importPreviewBody');

    const floorCount = document.getElementById('previewFloorCount');
    const categoryCount = document.getElementById('previewCategoryCount');
    const itemCount = document.getElementById('previewItemCount');
    const totalWeight = document.getElementById('previewTotalWeight');

    const btnImport = document.getElementById('btnImportPlan');

    if (!fileInput || !form) {
        return;
    }

    let importedRows = [];


    // =========================================================
    // HELPER
    // =========================================================

    function isEmpty(value) {
        return value === null ||
               value === undefined ||
               String(value).trim() === '';
    }


    function toNumber(value) {

        if (typeof value === 'number') {
            return value;
        }

        if (isEmpty(value)) {
            return null;
        }

        const normalized = String(value)
            .replace(/,/g, '')
            .trim();

        const number = Number(normalized);

        return Number.isFinite(number)
            ? number
            : null;
    }


    function formatPercent(value) {

        if (value === null || value === undefined) {
            return '-';
        }

        return `${(Number(value) * 100).toFixed(6)}%`;
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


    // =========================================================
    // CARI SHEET
    // =========================================================

    function findTimeScheduleSheet(workbook) {

        /*
         * File contoh kamu:
         *
         * REKAP
         * RAB
         * Sheet1          <-- Time Schedule
         * Sheet1 (2)
         * Berat Besi
         *
         * Prioritaskan sheet yang mempunyai
         * "WAKTU PELAKSANAAN PEKERJAAN".
         */

        for (const sheetName of workbook.SheetNames) {

            const sheet = workbook.Sheets[sheetName];

            const rows = XLSX.utils.sheet_to_json(sheet, {
                header: 1,
                defval: null
            });

            for (const row of rows) {

                const text = row
                    .filter(value => !isEmpty(value))
                    .map(value => String(value).toUpperCase())
                    .join(' ');

                if (
                    text.includes('WAKTU PELAKSANAAN PEKERJAAN') &&
                    text.includes('URAIAN PEKERJAAN')
                ) {
                    return {
                        name: sheetName,
                        sheet,
                        rows
                    };
                }
            }
        }

        return null;
    }


    // =========================================================
    // CARI HEADER
    // =========================================================

    function findHeader(rows) {

        for (let r = 0; r < rows.length; r++) {

            const row = rows[r];

            let noColumn = -1;
            let descriptionColumn = -1;
            let weightColumn = -1;

            row.forEach((value, index) => {

                if (isEmpty(value)) {
                    return;
                }

                const text =
                    String(value)
                        .trim()
                        .toUpperCase();

                if (text === 'NO') {
                    noColumn = index;
                }

                if (text === 'URAIAN PEKERJAAN') {
                    descriptionColumn = index;
                }

                if (text === 'BOBOT') {
                    weightColumn = index;
                }
            });


            if (
                noColumn !== -1 &&
                descriptionColumn !== -1 &&
                weightColumn !== -1
            ) {

                const weekRow =
                    rows[r + 1] || [];

                const weekColumns = {};


                /*
                * Cari M1-M25.
                *
                * Excel kamu:
                *
                * I6 = 1
                * J6 = 2
                * K6 = 3
                * ...
                * AG6 = 25
                */

                for (
                    let i = weightColumn + 1;
                    i < weekRow.length;
                    i++
                ) {

                    const weekNumber =
                        toNumber(weekRow[i]);

                    if (
                        weekNumber !== null &&
                        Number.isInteger(weekNumber) &&
                        weekNumber >= 1 &&
                        weekNumber <= 25
                    ) {

                        weekColumns[weekNumber] = i;
                    }
                }


                return {
                    headerRow: r,

                    noColumn,

                    descriptionColumn,

                    weightColumn,

                    weekColumns
                };
            }
        }

        return null;
    }


    // =========================================================
    // DETEKSI LANTAI
    // =========================================================

    function isFloorRow(no, description) {

        if (!isEmpty(no)) {
            return false;
        }

        if (isEmpty(description)) {
            return false;
        }

        return /^LANTAI\s+\d+/i.test(
            String(description).trim()
        );
    }


    // =========================================================
    // DETEKSI KATEGORI
    // =========================================================

    function isCategoryRow(no, description) {

        if (isEmpty(description)) {
            return false;
        }

        /*
         * Contoh:
         *
         * A | PEKERJAAN PERSIAPAN
         * B | PEKERJAAN TANAH
         * C | PEKERJAAN PONDASI
         */

        const noText = String(no ?? '').trim();

        return /^[A-Z]$/.test(noText);
    }


    // =========================================================
    // DETEKSI ITEM PEKERJAAN
    // =========================================================

    function isItemRow(no, description) {

        if (isEmpty(no) || isEmpty(description)) {
            return false;
        }

        const noText = String(no).trim();

        /*
         * Item:
         *
         * 1 | Pekerjaan ...
         * 2 | Pekerjaan ...
         */

        return /^\d+$/.test(noText);
    }

    function parseSheet(rows, header) {

        const result = [];

        let currentFloor = null;
        let currentCategory = null;
        let currentItem = null;

        /*
        * Mulai dari BARIS SETELAH HEADER.
        *
        * header Excel:
        * row 5 = header
        * row 6 = LANTAI 1
        */
        for (
            let r = header.headerRow + 1;
            r < rows.length;
            r++
        ) {

            const row = rows[r];

            const no = row[header.noColumn];
            const description = row[header.descriptionColumn];

            const weight = toNumber(
                row[header.weightColumn]
            );


            // =====================================================
            // 1. DETEKSI LANTAI
            // =====================================================

            if (
                !isEmpty(description) &&
                /^LANTAI\s+\d+/i.test(
                    String(description).trim()
                )
            ) {

                currentFloor = String(description).trim();

                currentCategory = null;
                currentItem = null;

                continue;
            }


            // =====================================================
            // 2. DETEKSI KATEGORI
            // =====================================================

            if (
                !isEmpty(no) &&
                /^[A-Z]$/.test(
                    String(no).trim()
                )
            ) {

                currentCategory =
                    String(description ?? '').trim();

                currentItem = null;

                continue;
            }


            // =====================================================
            // 3. ITEM PEKERJAAN UTAMA
            // =====================================================

            if (
                !isEmpty(no) &&
                /^\d+$/.test(
                    String(no).trim()
                ) &&
                !isEmpty(description)
            ) {

                currentItem = {
                    no: Number(no),

                    floor: currentFloor,

                    category: currentCategory,

                    description:
                        String(description).trim(),

                    bobot: weight || 0,

                    weeks: {}
                };


                // Ambil distribusi M1-M25
                Object.entries(header.weekColumns)
                    .forEach(([week, column]) => {

                        const value =
                            toNumber(row[column]);

                        if (value !== null) {

                            currentItem.weeks[week] =
                                (currentItem.weeks[week] || 0)
                                + value;
                        }
                    });


                result.push(currentItem);

                continue;
            }


            // =====================================================
            // 4. BARIS LANJUTAN / SUB-PEKERJAAN
            // =====================================================

            /*
            * Pada Excel kamu ada struktur seperti:
            *
            * 21 | Pekerjaan Pondasi Footplate
            *
            *    | Pekerjaan Beton K225 FP
            *    | Pekerjaan Pembesian FP
            *    | Pekerjaan Bekisting FP
            *
            * Deskripsi sub-pekerjaan berada di kolom F,
            * sedangkan kolom BOBOT berada di H.
            *
            * Karena currentItem sudah ada, nilai tersebut
            * dimasukkan ke item induk.
            */

            if (
                currentItem &&
                isEmpty(no)
            ) {

                /*
                * Bobot sub-item
                */
                if (weight !== null) {

                    currentItem.bobot += weight;
                }


                /*
                * Distribusi minggu sub-item
                */
                Object.entries(header.weekColumns)
                    .forEach(([week, column]) => {

                        const value =
                            toNumber(row[column]);

                        if (value !== null) {

                            currentItem.weeks[week] =
                                (currentItem.weeks[week] || 0)
                                + value;
                        }
                    });

                continue;
            }
        }

        return result;
    }

    function validateRows(rows) {

        const errors = [];

        rows.forEach((item, index) => {

            if (!item.floor) {
                errors.push(
                    `Baris pekerjaan ${index + 1}: lantai tidak ditemukan.`
                );
            }

            if (!item.category) {
                errors.push(
                    `Baris pekerjaan ${index + 1}: kategori tidak ditemukan.`
                );
            }

            if (!item.description) {
                errors.push(
                    `Baris pekerjaan ${index + 1}: uraian pekerjaan kosong.`
                );
            }

            if (item.bobot < 0) {
                errors.push(
                    `Pekerjaan "${item.description}": bobot tidak boleh negatif.`
                );
            }
        });

        return errors;
    }


    // =========================================================
    // RENDER PREVIEW
    // =========================================================

    function renderPreview(rows) {

        previewBody.innerHTML = '';

        rows.forEach((item, index) => {

            const tr = document.createElement('tr');

            let html = `
                <td class="text-center">
                    ${index + 1}
                </td>

                <td>
                    <div class="fw-bold">
                        ${escapeHtml(item.description)}
                    </div>
            `;

            if (item.subCategory) {

                html += `
                    <div class="text-muted small">
                        ${escapeHtml(item.subCategory)}
                    </div>
                `;
            }

            html += `
                </td>

                <td>
                    ${escapeHtml(item.floor)}
                </td>

                <td>
                    ${escapeHtml(item.category)}
                </td>

                <td class="text-end">
                    ${formatPercent(item.bobot)}
                </td>
            `;

            for (let week = 1; week <= 25; week++) {

                const value = item.weeks[week];

                html += `
                    <td class="text-center">
                        ${value !== undefined
                            ? formatPercent(value)
                            : '-'}
                    </td>
                `;
            }

            tr.innerHTML = html;

            previewBody.appendChild(tr);
        });
    }


    // =========================================================
    // SUMMARY
    // =========================================================

    function renderSummary(rows) {

        const floors = new Set(
            rows
                .map(row => row.floor)
                .filter(Boolean)
        );

        const categories = new Set(
            rows
                .map(row => row.category)
                .filter(Boolean)
        );

        const total = rows.reduce(
            (sum, row) => sum + (row.bobot || 0),
            0
        );

        floorCount.textContent = floors.size;
        categoryCount.textContent = categories.size;
        itemCount.textContent = rows.length;

        totalWeight.textContent =
            `${(total * 100).toFixed(6)}%`;

        summary.classList.remove('d-none');
    }


    // =========================================================
    // BACA EXCEL
    // =========================================================

    async function readExcel(file) {

        resetPreview();

        loading.classList.remove('d-none');

        try {

            const arrayBuffer =
                await file.arrayBuffer();

            const workbook =
                XLSX.read(arrayBuffer, {
                    type: 'array'
                });


            const scheduleSheet =
                findTimeScheduleSheet(workbook);

            if (!scheduleSheet) {
                throw new Error(
                    'Sheet Time Schedule tidak ditemukan.'
                );
            }


            const header =
                findHeader(scheduleSheet.rows);

            if (!header) {
                throw new Error(
                    'Header Excel tidak ditemukan. Pastikan file memiliki kolom NO, URAIAN PEKERJAAN dan BOBOT.'
                );
            }


            if (
                Object.keys(header.weekColumns).length === 0
            ) {
                throw new Error(
                    'Kolom minggu M1-M25 tidak ditemukan.'
                );
            }


            importedRows =
                parseSheet(
                    scheduleSheet.rows,
                    header
                );


            if (importedRows.length === 0) {
                throw new Error(
                    'Tidak ditemukan data pekerjaan yang dapat diimport.'
                );
            }


            const errors =
                validateRows(importedRows);

            if (errors.length > 0) {

                throw new Error(
                    errors.slice(0, 10).join('\n')
                );
            }


            renderSummary(importedRows);
            renderPreview(importedRows);

            previewWrapper.classList.remove('d-none');

            btnImport.disabled = false;

        } catch (error) {

            console.error(
                'Build Plan Excel Import:',
                error
            );

            errorBox.textContent =
                error.message ||
                'Gagal membaca file Excel.';

            errorBox.classList.remove('d-none');

            btnImport.disabled = true;

        } finally {

            loading.classList.add('d-none');
        }
    }


    // =========================================================
    // FILE CHANGE
    // =========================================================

    fileInput.addEventListener(
        'change',
        async function () {

            const file = this.files[0];

            resetPreview();

            if (!file) {
                return;
            }


            fileInfo.classList.remove('d-none');

            fileName.textContent =
                file.name;


            const extension =
                file.name
                    .split('.')
                    .pop()
                    .toLowerCase();


            if (!['xlsx', 'xls'].includes(extension)) {

                errorBox.textContent =
                    'File harus berformat .xlsx atau .xls.';

                errorBox.classList.remove('d-none');

                this.value = '';

                return;
            }


            await readExcel(file);
        }
    );


    // =========================================================
    // FORM SUBMIT
    // =========================================================

    form.addEventListener(
        'submit',
        function (event) {

            /*
             * Jangan kirim kalau preview belum berhasil.
             */
            if (
                !importedRows ||
                importedRows.length === 0
            ) {

                event.preventDefault();

                if (typeof Swal !== 'undefined') {

                    Swal.fire({
                        icon: 'warning',
                        title: 'Data belum siap',
                        text: 'Pilih file Excel yang valid terlebih dahulu.'
                    });

                }

                return;
            }


            event.preventDefault();


            const file = fileInput.files[0];


            const confirmImport = () => {

                btnImport.disabled = true;

                btnImport.innerHTML = `
                    <span class="spinner-border spinner-border-sm me-1"
                          role="status"></span>
                    Mengimport...
                `;
                
                form.submit();
            };


            if (typeof Swal !== 'undefined') {

                Swal.fire({
                    icon: 'question',
                    title: 'Import Build Plan?',
                    html: `
                        <div class="text-start">
                            <div>
                                File:
                                <strong>
                                    ${escapeHtml(file.name)}
                                </strong>
                            </div>

                            <div class="mt-1">
                                Pekerjaan:
                                <strong>
                                    ${importedRows.length}
                                </strong>
                            </div>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Import',
                    cancelButtonText: 'Batal'
                }).then(result => {

                    if (result.isConfirmed) {
                        confirmImport();
                    }
                });

            } else {

                if (
                    confirm(
                        `Import ${importedRows.length} pekerjaan dari ${file.name}?`
                    )
                ) {
                    confirmImport();
                }
            }

        }
    );

    function resetPreview() {

        importedRows = [];

        previewBody.innerHTML = '';

        summary.classList.add('d-none');
        previewWrapper.classList.add('d-none');
        errorBox.classList.add('d-none');

        btnImport.disabled = true;
    }

    const modal =
        document.getElementById('importPlanModal');

    if (modal) {

        modal.addEventListener(
            'hidden.bs.modal',
            function () {

                fileInput.value = '';

                fileInfo.classList.add('d-none');
                fileName.textContent = '';

                resetPreview();

                btnImport.innerHTML = `
                    <i class="ti ti-upload me-1"></i>
                    Import Data
                `;
            }
        );
    }

});