@php
    $dailies = $project->dailyReports;
@endphp

@if($dailies->count())
    <div class="card shadow-sm border-0 mt-4">

        <div class="card-header fw-bold">
        Riwayat Laporan Harian
        </div>

        <div class="card-body">
            <div class="border rounded p-3 mb-4">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Minggu</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dailies as $i=>$daily)
                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($daily->tanggal)->format('d-m-Y') }}</td>
                                <td>{{ $daily->week }}</td>
                                <td>
                                    <button 
                                        class="btn btn-sm btn-dark btn-detail"
                                        data-id="{{ $daily->id }}">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
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

                <div class="mt-3">
                <label class="fw-semibold">Catatan</label>
                <textarea class="form-control" readonly>${data.catatan ?? ''}</textarea>
                </div>
                `;

                modal.show();
            });

        });

    });

});
</script>
@endpush