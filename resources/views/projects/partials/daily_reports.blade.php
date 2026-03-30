<form action="{{ route('build.daily.store') }}" method="POST" enctype="multipart/form-data">
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

<div class="card shadow-sm border-0 mb-3">
    <div class="card-header fw-bold d-flex justify-content-between align-items-center">
        Informasi Laporan Harian

        <button type="submit"
            name="is_libur"
            value="1"
            class="btn btn-dark btn-sm">
            Simpan Sebagai Hari Libur
        </button>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <label class="form-label">Proyek</label>
                    <input class="form-control" value="{{ $project->project_name }}" readonly>
            </div>
            <div class="col-md-3">
                <label class="form-label">Lokasi</label>
                    <input class="form-control" value="{{ $project->city?->name }}" readonly>
            </div>
            <div class="col-md-3">
                <label class="form-label required">Tanggal</label>
                    <input id="nextDate" class="form-control" 
                        value="{{ $nextDate->translatedFormat('d F Y') }}" readonly>
            </div>
            <div class="col-md-3">
                <label>Kontraktor</label>
                    <input name="kontraktor" class="form-control" value="Antosa Architect">
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-header fw-bold d-flex justify-content-between">
        <span>Tenaga Kerja & Alat Bantu</span>
            <button type="button" class="btn btn-sm btn-dark" id="addTenaga">
                + Tambah Tenaga
            </button>
    </div>
    <div class="card-body p-0">
        <table class="table table-bordered mb-4">
            <thead class="table-light">
                <tr>
                    <th width="300">Keahlian</th>
                    <th width="150">Jumlah (Org)</th>
                    <th>Alat Bantu</th>
                    <th width="40"></th>
                </tr>
            </thead>

            <tbody id="tenagaTable">
                <!-- BARIS DEFAULT -->
                <tr>
                    <td>
                        <select name="worker_id[]" class="form-select select2 worker-select">
                            <option value="">-- Pilih Tenaga Kerja --</option>

                            @foreach($workers as $worker)
                                <option value="{{ $worker->id }}">
                                    {{ $worker->user->fullname }}
                                </option>
                            @endforeach

                            <option value="manual">+ Manual Input</option>
                        </select>

                        <input type="text"
                            name="keahlian[]"
                            class="form-control mt-2 manual-input d-none"
                            placeholder="Isi keahlian manual">
                    </td>

                    <td>
                        <input type="number" name="jumlah[]" class="form-control">
                    </td>

                    <td>
                        <input name="alat[]" class="form-control">
                    </td>

                    <td>
                        <button type="button" class="btn btn-danger btn-sm removeTenaga">
                            ×
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
        <template id="tenagaTemplate">
            <tr>
                <td>
                    <select name="worker_id[]" class="form-select select2 worker-select">
                        <option value="">-- Pilih Tenaga Kerja --</option>

                        @foreach($workers as $worker)
                            <option value="{{ $worker->id }}">
                                {{ $worker->user->fullname }}
                            </option>
                        @endforeach

                        <option value="manual">+ Manual Input</option>
                    </select>

                    <input type="text"
                        name="keahlian[]"
                        class="form-control mt-2 manual-input d-none"
                        placeholder="Isi keahlian manual">
                </td>

                <td>
                    <input type="number" name="jumlah[]" class="form-control">
                </td>

                <td>
                    <input name="alat[]" class="form-control">
                </td>

                <td>
                    <button type="button" class="btn btn-danger btn-sm removeTenaga">
                        ×
                    </button>
                </td>
            </tr>
        </template>
        <div class="md-6 mb-4">
            <label class="fw-bold">File Upload</label>
            <div class="text-muted mb-2">Bisa berupa foto atau dokumen</div>
            <input type="file"
                name="documentation_tenaga[]"
                class="form-control image-input"
                data-preview="preview-tenaga"
                accept="image/*,application/pdf"
                multiple>

                <div id="preview-tenaga"
                    class="mt-3 d-flex flex-wrap gap-3"></div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-header fw-bold d-flex justify-content-between">
        <span>Pekerjaan Yang Diselenggarakan Hari Ini</span>
        <button type="button" class="btn btn-sm btn-dark" id="addWork">
            + Tambah Pekerjaan
        </button>
    </div>
    <div class="card-body p-0">
        <div class="mb-3 mt-4">
            <table class="table table-bordered mb-4">
                <thead class="table-light">
                    <tr>
                        <th>Uraian Pekerjaan</th>
                        <th width="120">Satuan</th>
                        <th width="120">Volume</th>
                        <th>Keterangan</th>
                        <th width="40"></th>
                    </tr>
                </thead>

                <tbody id="workTable">
                    <!-- BARIS DEFAULT -->
                    <tr>
                        <td>
                            <select name="rab_process_item_id[]" 
                                    class="form-select select2 rab-select">
                                <option value="">-- Pilih Dari RAB --</option>

                                @foreach($rabs as $rab)
                                    <option value="{{ $rab->id }}"
                                        data-volume="{{ $rab->volume }}"
                                        data-satuan="{{ $rab->satuan }}">
                                        {{ $rab->job_name }} ({{ $rab->rab->job_location }})
                                    </option>
                                @endforeach

                                <option value="manual">+ Manual Input</option>
                            </select>

                            <input type="text"
                                name="uraian_manual[]"
                                class="form-control mt-2 manual-rab d-none"
                                placeholder="Isi uraian manual">
                        </td>
                        <td>
                            <input name="daily[satuan][]" 
                                type="text"
                                class="form-control satuan-input">
                        </td>
                        <td>
                            <input name="daily[volume][]" 
                                type="number" 
                                step="0.01"
                                class="form-control volume-input">
                        </td>

                        <td>
                            <input name="ket[]" class="form-control">
                        </td>

                        <td>
                            <button type="button" 
                                    class="btn btn-danger btn-sm removeWork">
                                ×
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <template id="kerjaTemplate">
                <tr>
                    <td>
                        <select name="rab_process_item_id[]" 
                                class="form-select select2 rab-select">
                            <option value="">-- Pilih Dari RAB --</option>

                            @foreach($rabs as $rab)
                                <option value="{{ $rab->id }}"
                                    data-volume="{{ $rab->volume }}"
                                    data-satuan="{{ $rab->satuan }}">
                                    {{ $rab->job_name }} ({{ $rab->rab->job_location }})
                                </option>
                            @endforeach

                            <option value="manual">+ Manual Input</option>
                        </select>

                        <input type="text"
                            name="uraian_manual[]"
                            class="form-control mt-2 manual-rab d-none"
                            placeholder="Isi uraian manual">
                    </td>
                    <td>
                        <input name="daily[satuan][]" 
                            type="text"
                            class="form-control satuan-input">
                    </td>
                    <td>
                        <input name="daily[volume][]" 
                            type="number" 
                            step="0.01"
                            class="form-control volume-input">
                    </td>

                    <td>
                        <input name="ket[]" class="form-control">
                    </td>

                    <td>
                        <button type="button" 
                                class="btn btn-danger btn-sm removeWork">
                            ×
                        </button>
                    </td>
                </tr>
            </template>
        </div>
        <div class="md-6 mb-4">
            <label class="fw-bold">File Upload</label>
            <div class="text-muted mb-2">Bisa berupa foto atau dokumen</div>
            <input type="file"
                name="documentation_pekerjaan[]"
                class="form-control image-input"
                data-preview="preview-pekerjaan"
                accept="image/*,application/pdf"
                multiple>

            <div id="preview-pekerjaan"
                class="mt-3 d-flex flex-wrap gap-3"></div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-header fw-bold d-flex justify-content-between">
        <span>Bahan Yang Masuk</span>
        <button type="button" class="btn btn-sm btn-dark" id="addMaterial">
            + Tambah Bahan
        </button>
    </div>
    <div class="card-body p-0">
        <table class="table table-bordered mb-4">
            <thead class="table-light">
                <tr>
                    <th>Jenis Bahan</th>
                    <th width="150">Diterima</th>
                    <th width="150">Ditolak</th>
                    <th width="40"></th>
                </tr>
            </thead>
            <tbody id="materialTable">
                <tr>
                    <td>
                        <input name="bahan[]" class="form-control">
                    </td>
                    <td>
                        <input type="number" name="diterima[]" class="form-control">
                    </td>
                    <td>
                        <input type="number" name="ditolak[]" class="form-control">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm removeMaterial">
                            ×
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="md-6 mb-4">
            <label class="fw-bold">File Upload</label>
            <div class="text-muted mb-2">Bisa berupa foto atau dokumen</div>
            <input type="file"
    name="documentation_material[]"
    class="form-control image-input"
    data-preview="preview-material"
    accept="image/*,application/pdf"
    multiple>

<div id="preview-material"
    class="mt-3 d-flex flex-wrap gap-3"></div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-header fw-bold">
        Jam Kerja & Cuaca
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <label>Mulai</label>
                    <input type="time" name="jam_mulai" class="form-control">
            </div>
            <div class="col-md-3">
                <label>Selesai</label>
                    <input type="time" name="jam_selesai" class="form-control">
            </div>

            <div class="col-md-3">
                <label>Total Jam</label>
                    <input type="number" name="total_jam" class="form-control" readonly>
            </div>

            <div class="col-md-3">
                <label>Cuaca</label>
                    <select name="cuaca" class="form-select select2">
                        <option value="cuaca">-- Pilih Cuaca --</option>
                        <option>Baik</option>
                        <option>Mendung</option>
                        <option>Hujan</option>
                    </select>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-header fw-bold">
        Catatan / Perintah Konsultan MK
    </div>
    <div class="card-body">
        <textarea name="catatan" class="form-control" rows="5"></textarea>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header fw-bold">
        Pengesahan
    </div>
    <div class="card-body">
        <div class="row text-center">
            <div class="col-md-6">
                Side Manager
                <br><br><br><br>
                <select name="mk_id" class="form-select select2">
                    <option value="mk_id">-- Pilih Side Manager --</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">
                            {{ $emp->user->fullname }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                Project Manager
                    <br><br><br><br>
                <select name="kontraktor_ttd_id" class="form-select select2">
                    <option value="kontraktor_ttd_id">-- Pilih Project Manager --</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">
                            {{ $emp->user->fullname }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>
    <div class="text-end mt-4">
        <button class="btn btn-dark">Simpan Laporan Harian</button>
    </div>
</form>
@push('js')
<script>
document.addEventListener("DOMContentLoaded", function() {
    $('.select2').select2({ width:'100%' });
    document.getElementById('addTenaga').addEventListener('click', function() {
        let template = document.getElementById('tenagaTemplate');
        let clone = template.content.cloneNode(true);
        document.querySelector('#tenagaTable').appendChild(clone);
        $('#tenagaTable .select2').last().select2({ width:'100%' });
    });
    document.addEventListener('click', function(e) {

        if(e.target.classList.contains('removeTenaga')) {

            let rows = document.querySelectorAll('#tenagaTable tr');

            if(rows.length > 1){
                e.target.closest('tr').remove();
            } else {
                alert('Minimal 1 baris harus ada');
            }
        }

    });
    $(document).on('change', '.worker-select', function(){

        let row = $(this).closest('tr');
        let manualInput = row.find('.manual-input');

        if($(this).val() === 'manual'){
            manualInput.removeClass('d-none');
        } else {
            manualInput.addClass('d-none');
            manualInput.val('');
        }

    });
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {

    // INIT SELECT2
    $('.select2').select2({ width:'100%' });

    // TAMBAH PEKERJAAN
    document.getElementById('addWork').addEventListener('click', function() {

        let template = document.getElementById('kerjaTemplate');
        let clone = template.content.cloneNode(true);

        document.querySelector('#workTable').appendChild(clone);

        // init select2 hanya yang baru
        $('#workTable .select2').last().select2({ width:'100%' });

    });

    // HAPUS PEKERJAAN (minimal 1 baris)
    document.addEventListener('click', function(e) {

        if(e.target.classList.contains('removeWork')) {

            let rows = document.querySelectorAll('#workTable tr');

            if(rows.length > 1){
                e.target.closest('tr').remove();
            } else {
                alert('Minimal 1 baris harus ada');
            }
        }

    });

    $(document).on('change', '.rab-select', function(){

        let row = $(this).closest('tr');
        let selectedOption = $(this).find(':selected');

        let volumeInput = row.find('.volume-input');
        let satuanInput = row.find('.satuan-input');
        let manualInput = row.find('.manual-rab');

        if($(this).val() === 'manual'){

            manualInput.removeClass('d-none');
            volumeInput.val('');
            satuanInput.val('');

        } else {

            manualInput.addClass('d-none');
            manualInput.val('');

            let volume = selectedOption.data('volume');
            let satuan = selectedOption.data('satuan');

            volumeInput.val(volume ?? '');
            satuanInput.val(satuan ?? '');

        }

    });

});
</script>
<script>
    document.getElementById('addMaterial').addEventListener('click', function() {

        let table = document.querySelector('#materialTable');
        let row = table.querySelector('tr').cloneNode(true);

        row.querySelectorAll('input')
        .forEach(i => i.value='');

        table.appendChild(row);

    });

    document.addEventListener('click', function(e) {
        if(e.target.classList.contains('removeMaterial')) {

            let rows = document.querySelectorAll('#materialTable tr');

            if(rows.length > 1){
                e.target.closest('tr').remove();
            }

        }
    });
</script>
<script>
document.addEventListener("change", function(e){

    if(e.target.classList.contains("image-input")){

        let previewId = e.target.dataset.preview;
        let previewContainer = document.getElementById(previewId);

        previewContainer.innerHTML = "";

        Array.from(e.target.files).forEach(file => {

            let div = document.createElement("div");
            div.style.width = "120px";

            // JIKA IMAGE
            if(file.type.startsWith("image/")){

                let reader = new FileReader();

                reader.onload = function(event){

                    div.innerHTML = `
                        <img src="${event.target.result}" 
                             class="img-fluid rounded shadow-sm mb-2">
                        <small class="d-block text-truncate">${file.name}</small>
                    `;

                    previewContainer.appendChild(div);
                }

                reader.readAsDataURL(file);

            }
            // JIKA PDF
            else if(file.type === "application/pdf"){

                div.innerHTML = `
                    <div class="border rounded p-3 text-center shadow-sm">
                        📄
                        <div class="small mt-2 text-truncate">${file.name}</div>
                    </div>
                `;

                previewContainer.appendChild(div);
            }

        });

    }

});
</script>
<script>
document.querySelectorAll('[name="jam_mulai"], [name="jam_selesai"]')
.forEach(input => {
    input.addEventListener('change', function(){

        let mulai = document.querySelector('[name="jam_mulai"]').value;
        let selesai = document.querySelector('[name="jam_selesai"]').value;

        if(mulai && selesai){
            let start = new Date(`1970-01-01T${mulai}`);
            let end = new Date(`1970-01-01T${selesai}`);

            let diff = (end - start) / 3600000;

            document.querySelector('[name="total_jam"]').value = diff;
        }

    });
});
</script>
@endpush