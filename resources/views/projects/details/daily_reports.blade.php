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
                                        <a href="{{ route('build.daily.edit', $report->id) }}"
                                            class="btn btn-sm btn-dark" title="Ubah">
                                            <i class="ti ti-pencil"></i>
                                        </a>
                                        {{-- <button class="btn btn-sm btn-dark btn-edit" title="Ubah"
                                            data-id="{{ $report->id }}">
                                            <i class="ti ti-pencil"></i>
                                        </button> --}}

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
                @foreach($dailies as $daily)
                    <div class="modal fade" id="dailyModal" tabindex="-1">
                        <div class="modal-dialog modal-xl modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Detail Laporan Harian</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body" id="dailyModalBody">
                                    <div class="text-center py-5">
                                        Loading...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="fullEditModal" tabindex="-1">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title">Edit Laporan</h5>
                            <button class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body" id="editModalBody">
                            Loading...
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-primary" id="saveFullEdit">
                                Simpan
                            </button>
                        </div>

                        </div>
                    </div>
                    </div>
                @endforeach
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
                <div class="row mb-3">

                    <div class="col-md-3">
                        <strong>Tanggal:</strong><br>
                        ${new Date(data.tanggal).toLocaleDateString('id-ID')}
                    </div>

                    <div class="col-md-3">
                        <strong>Jam Kerja:</strong><br>
                        ${data.jam_mulai ?? '-'} 
                        s/d 
                        ${data.jam_selesai ?? '-'}
                    </div>
                    <div class="col-md-3">
                        <strong>Total Jam:</strong><br>
                        ${data.total_jam ?? '-'} 
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
                `;

                modal.show();
            });

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
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// ================= EDIT =================
$(document).on('click', '.btn-edit', function(){

    let id = $(this).data('id');

    $('#editModalBody').html('Loading...');

    $('#fullEditModal').modal('show');

    $.get('/reports/' + id + '/edit', function(html){

        $('#editModalBody').html(html);

    });

});

// SAVE UPDATE
$('#saveEdit').click(function(){

    let id = $('#edit_id').val();

    $.ajax({
        url: '/reports/' + id,
        type: 'PUT',
        data: {
            tanggal: $('#edit_tanggal').val(),
            is_libur: $('#edit_libur').is(':checked') ? 1 : 0
        },
        success: function(response){

            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: response.message,
                timer: 2000,
                showConfirmButton: false
            });

            location.reload(); // nanti bisa kita ganti tanpa reload
        }
    });

});

// ================= DELETE =================
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