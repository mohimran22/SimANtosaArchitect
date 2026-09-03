<x-collapse-card title="Justifikasi Teknis" target="tambah-justek-body">
<form id="justekForm" action="{{ route('projects.justek.store') }}" method="POST" enctype="multipart/form-data">
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

    <h4 class="fw-bold mb-3">Informasi Pembuatan Justifikasi Teknis</h4>
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Nomor Penawaran</label>
            <input type="text" name="offer_number" class="form-control" value="{{ old('offer_number') ?? '' }}" placeholder="AUTO GENARATE" readonly>

        </div>
        <div class="col-md-4">
            <label class="form-label required">Tanggal Penawaran</label>
            <input type="date" name="offer_date" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Nama Customer</label>
            <input type="text" name="contact_name" value="{{ $project->customer->user->fullname }}" class="form-control" readonly>
        </div>
    </div>
    <div class="row mb-4 mt-3">

        <div class="rab-detail-header mb-3">

            <h4 class="fw-bold mb-0">
                Rincian Pekerjaan
            </h4>

            <div class="rab-action-buttons">

                <button type="button"
                        id="tombolUbah"
                        class="btn btn-dark btn-sm">
                    ✏️ Mode Edit
                </button>

                <button type="button"
                        id="tombolGeser"
                        class="btn btn-outline-secondary btn-sm">
                    🔀 Urutkan Daftar Pekerjaan
                </button>

                <button type="button"
                        class="btn btn-dark btn-sm"
                        onclick="openAddJustekItemModal()">
                    + Tambah Item
                </button>
                <button type="button"
                        class="btn btn-dark btn-sm"
                        onclick="openImportJustekItemModal()">
                    + Impor dari Excel
                </button>

            </div>

        </div>

        <div class="table-responsive">

            <table class="table table-bordered align-middle" id="justekItemsTable">

                <colgroup>
                    <col style="width: 60px">
                    <col>
                    <col style="width: 100px">
                    <col style="width: 130px">
                    <col style="width: 180px">
                    <col style="width: 200px">
                    <col style="width: 60px">
                </colgroup>

                <thead>
                    <tr>
                        <th>NO</th>
                        <th>URAIAN PEKERJAAN</th>
                        <th>SAT</th>
                        <th>VOL</th>
                        <th>HARGA SATUAN</th>
                        <th>JUMLAH HARGA</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody id="justek_offerItemsBody">
                </tbody>

                <tfoot>
                    <tr>
                        <th colspan="5" class="text-end">
                            SUBTOTAL
                        </th>

                        <th id="justek_subtotalDisplay">
                            Rp 0
                        </th>
                        <th></th>
                    </tr>

                    <tr>
                        <th colspan="5" class="text-end">
                            DISCOUNT
                        </th>

                        <th>
                            <input type="text"
                                class="form-control"
                                id="justek_discount_display">

                            <input type="hidden"
                                name="discount"
                                id="justek_discount">
                        </th>
                        <th></th>
                    </tr>

                    <tr>
                        <th colspan="5" class="text-end">
                            SUBTOTAL AFTER DISCOUNT
                        </th>

                        <th id="justek_subAfterDiscountDisplay">
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
                                id="justek_tax_rate"
                                min="0"
                                step="0.01">
                        </th>
                        <th></th>
                    </tr>

                    <tr>
                        <th colspan="5" class="text-end">
                            TOTAL TAX
                        </th>

                        <th id="justek_totalTaxDisplay">
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
                                id="justek_shipping_display">

                            <input type="hidden"
                                name="shipping"
                                id="justek_shipping">
                        </th>
                        <th></th>
                    </tr>

                    <tr>
                        <th colspan="5" class="text-end">
                            GRAND TOTAL
                        </th>

                        <th id="justek_grandTotalDisplay">
                            Rp 0
                        </th>
                        <th></th>
                    </tr>

                </tfoot>

            </table>

        </div>

    </div>
        <div class="modal fade" id="addRabItemModal" tabindex="-1" aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content">

                    <div class="modal-header border-0">

                        <div>
                            <h5 class="modal-title fw-bold">
                                Tambah Item RAB
                            </h5>

                            <small class="text-muted">
                                Masukkan pekerjaan yang akan ditambahkan ke RAB
                            </small>
                        </div>

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal">
                        </button>

                    </div>


                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Lantai
                            </label>
                            <div id="floorSelectWrapper">
                                <select id="justek_item_floor" class="form-select">
                                    <option value="">
                                        -- Pilih Lantai --
                                    </option>
                                </select>

                            </div>
                            <div id="floorInputWrapper" class="d-none">
                                <div class="input-group">
                                    <input type="text"
                                        id="justek_item_floor_new"
                                        class="form-control"
                                        placeholder="Contoh: Lantai 2">

                                    <button type="button"
                                            class="btn btn-outline-secondary"
                                            onclick="cancelNewFloor()">
                                        Batal
                                    </button>

                                </div>

                            </div>

                        </div>

                        <div class="mb-3">

                            <label class="form-label required fw-semibold">
                                Kategori
                            </label>

                            <div id="categorySelectWrapper">
                                <select id="justek_item_category" class="form-select">
                                    <option value="">
                                        -- Pilih Kategori --
                                    </option>
                                </select>
                            </div>

                            <div id="categoryInputWrapper" class="d-none">
                                <div class="input-group">
                                    <input type="text"
                                        id="justek_item_category_new"
                                        class="form-control"
                                        placeholder="Contoh: PEKERJAAN STRUKTUR">

                                    <button type="button"
                                            class="btn btn-outline-secondary"
                                            onclick="cancelNewCategory()">
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
                                id="justek_item_job_name"
                                class="form-control"
                                placeholder="Contoh: Pekerjaan Pembersihan Lapangan">

                        </div>

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Deskripsi Pekerjaan
                            </label>

                            <textarea id="justek_item_description"
                                    class="form-control"
                                    rows="2"
                                    placeholder="Keterangan pekerjaan (opsional)"></textarea>

                        </div>

                        <div class="row g-3 mb-3">

                            <div class="col-md-7">

                                <label class="form-label required fw-semibold">
                                    Volume
                                </label>

                                <input type="text"
                                    id="justek_item_volume"
                                    class="form-control"
                                    inputmode="decimal"
                                    placeholder="0">

                            </div>

                            <div class="col-md-5">

                                <label class="form-label required fw-semibold">
                                    Satuan
                                </label>

                                <input type="text"
                                    id="justek_item_satuan"
                                    class="form-control"
                                    placeholder="m2">

                            </div>

                        </div>

                        <div class="mb-3">

                            <label class="form-label required fw-semibold">
                                Harga Satuan Dasar
                            </label>

                            <input type="text"
                                id="justek_item_price_display"
                                class="form-control"
                                inputmode="decimal"
                                placeholder="Rp 0,00">

                            <input type="hidden"
                                id="justek_item_price">

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
                                onclick="saveRabItem()">
                            Simpan Item
                        </button>

                    </div>

                </div>

            </div>

        </div>
        <div class="modal fade"
            id="importRabItemModal"
            tabindex="-1"
            aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content">

                    <div class="modal-header border-0">

                        <div>
                            <h5 class="modal-title fw-bold">
                                Import RAB dari Excel
                            </h5>

                            <small class="text-muted">
                                Import item RAB menggunakan file Excel
                            </small>
                        </div>

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal">
                        </button>

                    </div>


                    <div class="modal-body">

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                File Excel
                            </label>

                            <input type="file"
                                id="justek_import_file"
                                class="form-control"
                                accept=".xlsx,.xls">

                            <div class="form-text">
                                Format yang diperbolehkan: .xlsx atau .xls
                            </div>

                        </div>


                        <div id="rabImportPreview">
                        </div>
                        <div id="rabImportError"
                            class="alert alert-danger d-none mt-3">
                        </div>
                    </div>


                    <div class="modal-footer border-0">

                        <button type="button"
                                class="btn btn-light"
                                data-bs-dismiss="modal">
                            Batal
                        </button>

                        <button type="button"
                                id="btnConfirmImportRab"
                                class="btn btn-dark"
                                onclick="importRabFromExcel()"
                                disabled>
                            Import RAB
                        </button>

                    </div>

                </div>

            </div>

        </div>

        <input type="hidden" name="subtotal" id="justek_subtotal">
        <input type="hidden" name="subtotal_after_discount" id="justek_subAfterDiscount">
        <input type="hidden" name="tax_total" id="justek_tax_total">
        <input type="hidden" name="grand_total" id="justek_grand_total">                  
    <div id="justekItemsContainer"></div>
    <h4 class="fw-bold mb-3">Keterangan</h4>

    <textarea name="notes" rows="3" class="form-control"></textarea>
    <div class="d-flex justify-content-end gap-2 mt-5">
        <button
            type="submit"
            class="btn btn-dark px-4"
        >
            <i class="ti ti-device-floppy me-1"></i>
            Simpan Justifikasi Teknis
        </button>

    </div>
</form>
</x-collapse-card>
@if($technicalJustifications->count())
<x-collapse-card title="Riwayat Justifikasi Teknis" target="riwayat-justek-body">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th width="50">NO</th>
                        <th>NOMOR PENAWARAN</th>
                        <th>TANGGAL PENAWARAN</th>
                        <th>NAMA CUSTOMER</th>
                        <th class="text-end">GRAND TOTAL</th>
                        <th width="110" class="text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($technicalJustifications as $i => $justek)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $justek->justek_number }}</td>
                            <td>{{ \Carbon\Carbon::parse($justek->offer_date)->format('d/m/Y') }}</td>
                            <td>{{ $justek->contact_name }}</td>
                            <td class="text-end">Rp {{ number_format($justek->grand_total, 2, ',', '.') }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">

                                    {{-- Lihat --}}
                                    <button type="button"
                                            class="btn btn-icon btn-sm btn-dark btn-lihat-justek"
                                            data-id="{{ $justek->id }}"
                                            title="Lihat Detail">
                                        <i class="ti ti-eye"></i>
                                    </button>

                                    {{-- Edit --}}
                                    @if(!$ReadOnly)
                                        <button type="button"
                                                class="btn btn-icon btn-sm btn-dark btn-edit-justek"
                                                data-id="{{ $justek->id }}"
                                                title="Edit Justifikasi Teknis">
                                            <i class="ti ti-edit"></i>
                                        </button>

                                        {{-- Hapus --}}
                                        <form action="{{ route('projects.justek.destroy', $justek->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Hapus justek {{ $justek->justek_number }}?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-icon btn-sm btn-dark"
                                                    title="Hapus">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada data justifikasi teknis</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-collapse-card>
@endif

<div class="modal fade" id="modalDetailJustek" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Justifikasi Teknis</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalDetailJustekBody">
                <div class="text-center py-5">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const modalEl   = document.getElementById('modalDetailJustek');
    const modalBody = document.getElementById('modalDetailJustekBody');
    const modal     = new bootstrap.Modal(modalEl);

    function showLoading() {

        modalBody.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">
                        Loading...
                    </span>
                </div>
            </div>
        `;
    }

    function loadDetail(id) {

        showLoading();

        modal.show();

        fetch(`/projects/justek/${id}/detail`)
            .then(res => {

                if (!res.ok) {
                    throw new Error('Gagal memuat detail');
                }

                return res.text();
            })
            .then(html => {

                modalBody.innerHTML = html;

                bindDetailActions(id);
            })
            .catch(error => {

                console.error(error);

                modalBody.innerHTML = `
                    <div class="alert alert-danger mb-0">
                        Gagal memuat detail justifikasi teknis.
                    </div>
                `;
            });
    }

    function loadEdit(id) {

        showLoading();

        modal.show();

        fetch(`/projects/justek/${id}/edit`)
            .then(res => {

                if (!res.ok) {
                    throw new Error('Gagal memuat form edit');
                }

                return res.text();
            })
            .then(html => {

                modalBody.innerHTML = html;

                const scripts = modalBody.querySelectorAll('script[data-justek-edit-script]');

                scripts.forEach(function (oldScript) {

                    const newScript = document.createElement('script');

                    newScript.textContent =
                        oldScript.textContent;

                    document.body.appendChild(newScript);

                    oldScript.remove();

                });

                bindEditForm(id);
                bindCancelEdit(id);
                        window.loadJustekDetailAfterEdit =
                function () {

                    loadDetail(id);

                };

            })
            .catch(error => {

                console.error(error);

                modalBody.innerHTML = `
                    <div class="alert alert-danger mb-0">
                        Gagal memuat form edit justifikasi teknis.
                    </div>
                `;

            });
    }

    function bindDetailActions(id) {

        const btnEdit = modalBody.querySelector('.btn-modal-edit-justek');

        if (btnEdit) {

            btnEdit.addEventListener('click', function () {

                loadEdit(id);

            });
        }
    }
    function bindCancelEdit(id) {

        const btnCancel = modalBody.querySelector('.btn-modal-cancel-edit');

        if (btnCancel) {

            btnCancel.addEventListener('click', function () {

                loadDetail(id);

            });
        }
    }

    // function bindEditForm(id) {

    //     const form = modalBody.querySelector('#editJustekForm');

    //     if (!form) {
    //         return;
    //     }

    //     form.addEventListener('submit', function (event) {

    //         event.preventDefault();

    //         const submitButton =
    //             form.querySelector('[type="submit"]');

    //         if (submitButton) {

    //             submitButton.disabled = true;

    //             submitButton.innerHTML = `
    //                 <span class="spinner-border spinner-border-sm me-1"></span>
    //                 Menyimpan...
    //             `;
    //         }

    //         const formData = new FormData(form);

    //         fetch(form.action, {

    //             method: 'POST',

    //             headers: {
    //                 'X-CSRF-TOKEN':
    //                     document.querySelector('meta[name="csrf-token"]')
    //                     ?.getAttribute('content') ?? ''
    //             },

    //             body: formData

    //         })
    //         .then(async response => {

    //             const data = await response.json();

    //             if (!response.ok) {

    //                 throw {
    //                     validation: true,
    //                     data: data
    //                 };
    //             }

    //             return data;
    //         })
    //         .then(data => {

    //             if (data.success) {

    //                 // Setelah berhasil update,
    //                 // langsung kembali ke DETAIL modal.
    //                 loadDetail(id);

    //                 // Optional: refresh halaman jika ingin
    //                 // tabel riwayat langsung mengambil data baru.
    //                 //
    //                 // location.reload();
    //             }

    //         })
    //         .catch(error => {

    //             console.error(error);

    //             if (submitButton) {

    //                 submitButton.disabled = false;

    //                 submitButton.innerHTML = `
    //                     <i class="ti ti-device-floppy me-1"></i>
    //                     Simpan Perubahan
    //                 `;
    //             }

    //             let message =
    //                 'Gagal menyimpan perubahan justifikasi teknis.';

    //             if (
    //                 error.validation &&
    //                 error.data &&
    //                 error.data.errors
    //             ) {

    //                 message = Object.values(error.data.errors)
    //                     .flat()
    //                     .join('<br>');
    //             }

    //             const errorBox =
    //                 form.querySelector('#editJustekError');

    //             if (errorBox) {

    //                 errorBox.innerHTML = message;

    //                 errorBox.classList.remove('d-none');
    //             }
    //         });
    //     });
    // }

    document
        .querySelectorAll('.btn-lihat-justek')
        .forEach(function (btn) {

            btn.addEventListener('click', function () {

                const id = this.dataset.id;

                loadDetail(id);

            });

        });

    document
        .querySelectorAll('.btn-edit-justek')
        .forEach(function (btn) {

            btn.addEventListener('click', function () {

                const id = this.dataset.id;

                loadEdit(id);

            });

        });

});
</script>
@endpush