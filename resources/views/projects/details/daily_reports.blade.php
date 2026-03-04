@php
    $dailies = $project->dailyReports;
@endphp

@if($dailies->count())
    <div class="card shadow-sm border-0 mt-4">

        <div class="card-header fw-bold mb-0">
        Riwayat Laporan Harian
        </div>

        <div class="card-body">
            <div class="border rounded p-3 mb-4">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="20">No</th>
                            <th>Tanggal</th>
                            <th>Minggu</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>

                    @foreach($reports as $minggu => $items)

                        <tbody>

                            {{-- HEADER MINGGU --}}
                            <tr class="table-secondary">
                                <td colspan="4"
                                    class="fw-bold week-header"
                                    data-week="{{ $minggu }}"
                                    style="cursor:pointer;">

                                    <span class="week-icon">▼</span>
                                    Minggu ke-{{ $minggu }}
                                    ({{ $items->where('is_libur', false)->count() }} Hari Kerja)
                                </td>
                            </tr>

                            {{-- ISI MINGGU --}}
                            @foreach($items as $report)
                                <tr class="week-row week-{{ $minggu }} {{ $report->is_libur ? 'table-warning' : '' }}">
                                    <td>{{ $loop->iteration }}</td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($report->tanggal)->format('d-m-Y') }}

                                        @if($report->is_libur)
                                            <span class="badge bg-warning text-dark ms-2">
                                                Libur
                                            </span>
                                        @endif
                                    </td>

                                    <td>Minggu ke-{{ $report->minggu }}</td>

                                    <td>
                                        <button class="btn btn-sm btn-dark btn-detail" title="Detail"
                                            data-id="{{ $report->id }}">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-dark btn-hapus" title="Hapus"
                                            data-id="{{ $report->id }}">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>

                    @endforeach
                </table>
                {{-- @foreach($dailies as $daily) --}}
                        <div class="modal fade" id="dailyModal" tabindex="-1">
                            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title">Detail Laporan Harian</h5>

                                        <div class="ms-auto d-flex gap-2">
                                            <button class="btn btn-dark" id="btnEdit">Edit</button>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                    </div>

                                    <div class="modal-body" id="dailyModalBody">
                                        <div class="text-center py-5">
                                            Loading...
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                {{-- @endforeach --}}
                    {{-- <div class="row text-center">
                        <div class="col-md-6">
                            Side Manager
                            <br><br><br><br>
                            <input name="mk" class="form-control text-center" value="{{ $daily->mk }}">
                        </div>

                        <div class="col-md-6">

                            Kontraktor

                                <br><br><br><br>

                            <input name="kontraktor_ttd" class="form-control text-center" placeholder="Nama Project Manager">
                        </div>
                    </div> --}}
            </div>
        </div>
    </div>
@endif
@push('js')
<script>
document.addEventListener('DOMContentLoaded', function(){

    const modal = new bootstrap.Modal(document.getElementById('dailyModal'));
    const modalBody = document.getElementById('dailyModalBody');
    const btnEdit = document.getElementById('btnEdit');

    document.querySelectorAll('.btn-detail').forEach(btn => {

        btn.addEventListener('click', function(){

            const id = this.dataset.id;

            modalBody.innerHTML = "Loading...";

            fetch(`/daily/${id}/detail`)
            .then(res => res.json())
            .then(data => {

                let pekerjaanRows = '';
                data.works.forEach((w,i)=>{
                    pekerjaanRows += `
                    <tr>
                        <td>${i+1}</td>
                        <td>${w.rab_process_item 
                            ? w.rab_process_item.job_name 
                            : (w.uraian_manual ?? '-')}</td>
                        <td>${w.volume}</td>
                        <td>${w.satuan}</td>
                        <td>${w.keterangan ?? '-'}</td>
                    </tr>`;
                });

                let workerRows = '';
                data.workers.forEach((w,i)=>{
                    workerRows += `
                    <tr>
                        <td>${i+1}</td>
                        <td>${w.worker 
                            ? w.worker.user.fullname 
                            : (w.keahlian ?? '-')}</td>
                        <td>${w.jumlah}</td>
                        <td>${w.alat ?? '-'}</td>
                    </tr>`;
                });

                let materialRows = '';
                data.materials.forEach((m,i)=>{
                    materialRows += `
                    <tr>
                        <td>${i+1}</td>
                        <td>${m.nama_bahan}</td>
                        <td>${m.diterima}</td>
                        <td>${m.ditolak}</td>
                    </tr>`;
                });

                modalBody.innerHTML = `
                <div id="viewMode">
                <div class="row mb-3">

                    <div class="col-md-3">
                        <strong>Tanggal:</strong><br>
                        ${new Date(data.tanggal_formatted).toLocaleDateString('id-ID')}
                    </div>

                    <div class="col-md-3">
                        <strong>Jam Kerja:</strong><br>
                        ${data.jam_mulai ?? '-'} 
                        s/d 
                        ${data.jam_selesai ?? '-'}
                    </div>
                    <div class="col-md-3">
                        <strong>Total Jam:</strong><br>
                        ${parseFloat(data.total_jam).toFixed(2)} 
                    </div>

                    <div class="col-md-3">
                        <strong>Cuaca:</strong><br>
                        ${data.cuaca ?? '-'}
                    </div>

                </div>

                <hr>
                <h6>Pekerjaan</h6>
                <table class="table table-bordered mb-4">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Uraian</th>
                            <th>Volume</th>
                            <th>Satuan</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>${pekerjaanRows}</tbody>
                </table>
                <h6 class="mt-4">File Upload</h6>
                ${renderDocumentation(data, 'tenaga')}

                <h6>Tenaga Kerja</h6>
                <table class="table table-bordered mb-4">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama / Keahlian</th>
                            <th>Jumlah</th>
                            <th>Alat</th>
                        </tr>
                    </thead>
                    <tbody>${workerRows}</tbody>
                </table>
                <h6 class="mt-4">File Upload</h6>
                ${renderDocumentation(data, 'pekerjaan')}
                <h6>Bahan Masuk</h6>
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Bahan</th>
                            <th>Diterima</th>
                            <th>Ditolak</th>
                        </tr>
                    </thead>
                    <tbody>${materialRows}</tbody>
                </table>
                <h6 class="mt-4">File Upload</h6>
                ${renderDocumentation(data, 'material')}
                <div class="mt-3">
                    <label class="fw-semibold">Catatan</label>
                    <textarea class="form-control" readonly>${data.catatan ?? ''}</textarea>
                </div>
                <hr>
                <h6 class="mt-4">Pengesahan</h6>

                <div class="row mt-4 text-center">

                    <div class="col-md-6">
                        <p class="fw-semibold">Side Manager</p>
                        <br><br>
                        <u>${
                            data.mk_employee?.user?.fullname ?? '____________________'
                        }</u>
                    </div>

                    <div class="col-md-6">
                        <p class="fw-semibold">Project Manager</p>
                        <br><br>
                        <u>${
                            data.kontraktor_employee?.user?.fullname ?? '____________________'
                        }</u>
                    </div>

                </div>
                </div>
                <div id="editMode" style="display:none;">
                    <form id="editDailyForm">
                        <input type="hidden" name="id" value="${data.id}">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label>Tanggal</label>
                                <input type="date" name="tanggal" class="form-control"
                                    value="${data.tanggal_formatted ?? ''}" required>
                            </div>
                            <div class="col-md-3">
                                <label>Jam Mulai</label>
                                <input type="time" name="jam_mulai" class="form-control"
                                    value="${data.jam_mulai ?? ''}">
                            </div>
                            <div class="col-md-3">
                                <label>Jam Selesai</label>
                                <input type="time" name="jam_selesai" class="form-control"
                                    value="${data.jam_selesai ?? ''}">
                            </div>
                            <div class="col-md-3">
                                <label>Cuaca</label>
                                <input type="text" name="cuaca" class="form-control"
                                    value="${data.cuaca ?? ''}">
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">Pekerjaan</h6>
                            <button type="button" class="btn btn-sm btn-dark" id="btnAddWork">
                                + Tambah
                            </button>
                        </div>
                        <table class="table table-bordered" id="worksTable">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Uraian</th>
                                    <th>Volume</th>
                                    <th>Satuan</th>
                                    <th>Keterangan</th>
                                    <th width="80">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${data.works.map((w,i)=>`
                                <tr>
                                    <td>${i+1}</td>
                                    <td>
                                        <input type="hidden" name="works[${i}][id]" value="${w.id}">
                                        <input type="text"
                                            name="works[${i}][uraian_manual]"
                                            class="form-control"
                                            value="${
                                                w.rab_process_item 
                                                ? w.rab_process_item.job_name 
                                                : (w.uraian_manual ?? '')
                                            }">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01"
                                            name="works[${i}][volume]"
                                            class="form-control"
                                            value="${w.volume}">
                                    </td>
                                    <td>
                                        <input type="text"
                                            name="works[${i}][satuan]"
                                            class="form-control"
                                            value="${w.satuan}">
                                    </td>
                                    <td>
                                        <input type="text"
                                            name="works[${i}][keterangan]"
                                            class="form-control"
                                            value="${w.keterangan ?? '-'}">
                                    </td>
                                    <td></td>
                                </tr>
                                `).join('')}
                            </tbody>
                        </table>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">Tenaga Kerja</h6>
                            <button type="button" 
                                    class="btn btn-sm btn-dark" 
                                    id="btnAddWorker">
                                + Tambah
                            </button>
                        </div>
                        <table class="table table-bordered" id="workersTable">
                        <thead class="table-light">
                            <tr>
                                <th width="50">No</th>
                                <th>Nama / Keahlian</th>
                                <th width="120">Jumlah</th>
                                <th width="150">Alat</th>
                                <th width="80">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        ${data.workers.map((w,i)=>`
                        <tr>
                            <td>${i+1}</td>
                            <td>
                                ${w.worker 
                                    ? w.worker.user.fullname
                                    : (w.keahlian ?? '-')}
                                <input type="hidden" name="workers[${i}][id]" value="${w.id}">
                            </td>
                            <td>
                                <input type="number"
                                    name="workers[${i}][jumlah]"
                                    class="form-control"
                                    value="${w.jumlah}">
                            </td>
                            <td>
                                <input type="text"
                                    name="workers[${i}][alat]"
                                    class="form-control"
                                    value="${w.alat ?? ''}">
                            </td>
                            <td></td>
                        </tr>
                        `).join('')}
                        </tbody>
                        </table>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">Material</h6>
                            <button type="button" class="btn btn-sm btn-dark" id="btnAddMaterial">
                                + Tambah
                            </button>
                        </div>
                        <table class="table table-bordered" id="materialsTable">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Bahan</th>
                                <th>Diterima</th>
                                <th>Ditolak</th>
                                <th width="80">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        ${data.materials.map((m,i)=>`
                        <tr>
                            <td>${i+1}</td>
                            <td>
                                ${m.nama_bahan}
                                <input type="hidden" name="materials[${i}][id]" value="${m.id}">
                            </td>
                            <td>
                                <input type="number"
                                    name="materials[${i}][diterima]"
                                    class="form-control"
                                    value="${m.diterima}">
                            </td>
                            <td>
                                <input type="number"
                                    name="materials[${i}][ditolak]"
                                    class="form-control"
                                    value="${m.ditolak}">
                            </td>
                            <td></td>
                        </tr>
                        `).join('')}
                        </tbody>
                        </table>
                        <div class="mb-3">
                            <label>Catatan</label>
                            <textarea name="catatan" class="form-control">${data.catatan ?? ''}</textarea>
                        </div>
                        <button type="button" class="btn btn-dark" id="btnSaveAll">
                            Simpan
                        </button>
                        <button type="button" class="btn btn-secondary" id="btnCancelEdit">
                            Batal
                        </button>
                    </form>
                </div>
                `;
                    // masuk mode edit
                    document.getElementById('btnEdit').addEventListener('click', function(){
                        document.getElementById('viewMode').style.display = 'none';
                        document.getElementById('editMode').style.display = 'block';
                        this.style.display = 'none';
                    });

                    // batal edit
                    document.getElementById('btnCancelEdit').addEventListener('click', function(){
                        document.getElementById('editMode').style.display = 'none';
                        document.getElementById('viewMode').style.display = 'block';
                        document.getElementById('btnEdit').style.display = 'inline-block';
                    });

                let workIndex = data.works.length;
                let workerIndex = data.workers.length;
                let materialIndex = data.materials.length;

                // tombol tambah (pakai event delegation biar aman)
                modalBody.addEventListener('click', function(e){
                    if(e.target.id === 'btnAddWork') {

                        const tbody = document.querySelector('#worksTable tbody');

                        const row = `
                        <tr>
                            <td class="row-number"></td>

                            <td>
                                <input type="text"
                                    name="works[${workIndex}][uraian_manual]"
                                    class="form-control">
                            </td>

                            <td>
                                <input type="number" step="0.01"
                                    name="works[${workIndex}][volume]"
                                    class="form-control">
                            </td>

                            <td>
                                <input type="text"
                                    name="works[${workIndex}][satuan]"
                                    class="form-control">
                            </td>

                            <td>
                                <input type="text"
                                    name="works[${workIndex}][keterangan]"
                                    class="form-control">
                            </td>

                            <td>
                                <button type="button"
                                    class="btn btn-sm btn-danger btn-remove-work">
                                    Hapus
                                </button>
                            </td>
                        </tr>`;

                        tbody.insertAdjacentHTML('beforeend', row);
                        workIndex++;
                        refreshNumber('#worksTable');
                    }

                    if(e.target.classList.contains('btn-remove-work')){
                        e.target.closest('tr').remove();
                        refreshNumber('#worksTable');
                    }
                    if(e.target.id === 'btnAddWorker'){

                        const tbody = document.querySelector('#workersTable tbody');

                        const row = `
                        <tr>
                            <td class="row-number"></td>

                            <td>
                                <input type="text"
                                    name="workers[${workerIndex}][nama]"
                                    class="form-control">
                            </td>

                            <td>
                                <input type="number"
                                    name="workers[${workerIndex}][jumlah]"
                                    class="form-control" value="1">
                            </td>

                            <td>
                                <input type="text"
                                    name="workers[${workerIndex}][alat]"
                                    class="form-control">
                            </td>

                            <td>
                                <button type="button"
                                    class="btn btn-sm btn-danger btn-remove-worker">
                                    Hapus
                                </button>
                            </td>
                        </tr>`;

                        tbody.insertAdjacentHTML('beforeend', row);
                        workerIndex++;
                        refreshNumber('#workersTable');
                    }

                    if(e.target.classList.contains('btn-remove-worker')){
                        e.target.closest('tr').remove();
                        refreshNumber('#workersTable');
                    }
                    if(e.target.id === 'btnAddMaterial'){

                        const tbody = document.querySelector('#materialsTable tbody');

                        const row = `
                        <tr>
                            <td class="row-number"></td>

                            <td>
                                <input type="text"
                                    name="materials[${materialIndex}][nama_bahan]"
                                    class="form-control">
                            </td>

                            <td>
                                <input type="number"
                                    name="materials[${materialIndex}][diterima]"
                                    class="form-control">
                            </td>

                            <td>
                                <input type="number"
                                    name="materials[${materialIndex}][ditolak]"
                                    class="form-control">
                            </td>

                            <td>
                                <button type="button"
                                    class="btn btn-sm btn-danger btn-remove-material">
                                    Hapus
                                </button>
                            </td>
                        </tr>`;

                        tbody.insertAdjacentHTML('beforeend', row);
                        materialIndex++;
                        refreshNumber('#materialsTable');
                    }

                    if(e.target.classList.contains('btn-remove-material')){
                        e.target.closest('tr').remove();
                        refreshNumber('#materialsTable');
                    }

                });
                modal.show();
            });
function refreshNumber(tableId){
    document.querySelectorAll(`${tableId} tbody tr`)
        .forEach((row,index)=>{
            const cell = row.querySelector('.row-number');
            if(cell){
                cell.innerText = index + 1;
            }
        });
}

        });

    });
    function renderDocumentation(data, category) {
            let docs = (data.documentations || []).filter(doc =>
                doc.category.toLowerCase() === category.toLowerCase()
            );

        if(docs.length === 0){
            return `<p class="text-muted">Tidak ada File Upload</p>`;
        }

        let html = `<div class="d-flex flex-wrap gap-3">`;

        docs.forEach(doc => {

            let fileUrl = "/storage/" + doc.file_path;

            // Jika image
            if(doc.file_type.startsWith("image")){

                html += `
                    <div style="width:120px">
                        <a href="${fileUrl}" target="_blank">
                            <img src="${fileUrl}" 
                                class="img-fluid rounded shadow-sm mb-1">
                        </a>
                        <small class="d-block text-truncate">${doc.file_name}</small>
                    </div>
                `;
            }
            // Jika PDF
            else if(doc.file_type === "application/pdf"){

                html += `
                    <div style="width:120px">
                        <a href="${fileUrl}" 
                        target="_blank"
                        class="text-decoration-none">
                            <div class="border rounded p-3 text-center shadow-sm">
                                📄
                            </div>
                            <small class="d-block text-truncate mt-1">
                                ${doc.file_name}
                            </small>
                        </a>
                    </div>
                `;
            }

        });

        html += `</div>`;

        return html;
    }
    document.addEventListener('click', function(e){

        if(e.target.id === 'btnSaveAll'){

            const form = document.getElementById('editDailyForm');
            const formData = new FormData(form);
            const id = formData.get('id');

            fetch(`/daily/${id}/update-all`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        .content,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => res.json())
            .then(res => {
                if(res.success){
                    alert('Semua data berhasil diupdate');
                    location.reload();
                }
            });

        }

    });
    function updateHeaderView(data){

        document.querySelector('#viewMode .col-md-3:nth-child(1)').innerHTML =
            `<strong>Tanggal:</strong><br>
            ${new Date(data.tanggal).toLocaleDateString('id-ID')}`;

        document.querySelector('#viewMode .col-md-3:nth-child(2)').innerHTML =
            `<strong>Jam Kerja:</strong><br>
            ${(data.jam_mulai)} s/d ${(data.jam_selesai)}`;

        document.querySelector('#viewMode .col-md-3:nth-child(3)').innerHTML =
            `<strong>Total Jam:</strong><br>
            ${parseFloat(data.total_jam).toFixed(2)}`;

        document.querySelector('#viewMode .col-md-3:nth-child(4)').innerHTML =
            `<strong>Cuaca:</strong><br>
            ${data.cuaca ?? '-'}`;

        document.querySelector('#viewMode textarea').value =
            data.catatan ?? '';
    }
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function(){

    const storageKey = "weeklyCollapseState";

    // ambil state dari localStorage
    let savedState = JSON.parse(localStorage.getItem(storageKey)) || {};

    document.querySelectorAll(".week-header").forEach(function(header){

        let week = header.getAttribute("data-week");
        let rows = document.querySelectorAll(".week-" + week);
        let icon = header.querySelector(".week-icon");

        // 🔹 Restore state saat reload
        if(savedState[week] === true){
            rows.forEach(row => row.style.display = "");
            icon.innerHTML = "▼";
        } else {
            rows.forEach(row => row.style.display = "none");
            icon.innerHTML = "▶";
        }

        // 🔹 Saat diklik
        header.addEventListener("click", function(){

            let isOpen = rows[0].style.display !== "none";

            rows.forEach(function(row){
                row.style.display = isOpen ? "none" : "";
            });

            icon.innerHTML = isOpen ? "▶" : "▼";

            // Simpan ke localStorage
            savedState[week] = !isOpen;
            localStorage.setItem(storageKey, JSON.stringify(savedState));

        });

    });

});
</script>
<script>
$(document).on('click', '.btn-hapus', function(){

    let id = $(this).data('id');

    Swal.fire({
        title: 'Yakin hapus?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus'
    }).then((result) => {

        if(result.isConfirmed){

            $.ajax({
                url: '/reports/' + id,
                type: 'DELETE',
                success: function(response){

                    Swal.fire({
                        icon: 'success',
                        title: 'Terhapus',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });

                    location.reload(); // nanti kita bikin tanpa reload
                }
            });

        }

    });

});
</script>
@endpush