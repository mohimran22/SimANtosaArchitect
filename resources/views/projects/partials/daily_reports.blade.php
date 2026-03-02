<form action="{{ route('build.daily.store') }}" method="POST">
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
    <div class="card-header fw-bold">
        Informasi Laporan Harian
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
                    <input type="text" id="tanggal" name="tanggal" class="form-control" 
                            placeholder="dd mm YYYY" value="{{ old('tanggal') }}" required>
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
                + Tambah
            </button>
    </div>
    <div class="card-body p-0">
        <table class="table table-bordered mb-0">
            <thead class="table-light">
                <tr>

                    <th width="300">Keahlian</th>
                    <th width="150">Jumlah (Org)</th>
                    <th>Alat Bantu</th>
                    <th width="40"></th>
                </tr>
            </thead>
            <tbody id="tenagaTable">
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
                                <input type="text" name="keahlian[]" class="form-control mt-4 manual-input d-none"
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
            </tbody>
        </table>
    </div>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-header fw-bold d-flex justify-content-between">
        <span>Pekerjaan Yang Diselenggarakan Hari Ini</span>
        <button type="button" class="btn btn-sm btn-dark" id="addWork">
            + Tambah
        </button>
    </div>
    <div class="card-body p-0">
        <table class="table table-bordered mb-0">
            <thead class="table-light">
                <tr>
                    <th>Uraian Pekerjaan</th>
                    <th>Volume</th>
                    <th>Satuan</th>
                    <th>Keterangan</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="workTable">
                <template id="kerjaTemplate">
                    <tr>
                        <td>
                            <select name="rab_process_item_id[]" class="form-select select2 rab-select">
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
                                class="form-control mt-4 manual-rab d-none"
                                placeholder="Isi uraian manual">
                        </td>
                        <td>
                            <input name="volume[]" type="number" step="0.01" class="form-control volume-input">
                        </td>
                        <td>
                            <input name="satuan[]" type="text" class="form-control satuan-input">
                        </td>
                        <td>
                            <input name="ket[]" class="form-control">
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm removeWork">
                                ×
                            </button>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-header fw-bold d-flex justify-content-between">
        <span>Bahan Yang Masuk</span>
        <button type="button" class="btn btn-sm btn-dark" id="addMaterial">

            + Tambah

        </button>
    </div>
    <div class="card-body p-0">
        <table class="table table-bordered mb-0">
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
                    <input type="number" name="total_jam" class="form-control">
            </div>

            <div class="col-md-3">
                <label>Cuaca</label>
                    <select name="cuaca" class="form-select">
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
                <input name="mk" class="form-control text-center" placeholder="Nama Side Manager">
            </div>

            <div class="col-md-6">

                Kontraktor

                    <br><br><br><br>

                <input name="kontraktor_ttd" class="form-control text-center" placeholder="Nama Project Manager">
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
flatpickr("#tanggal", {
    dateFormat: "Y-m-d",
    altInput: true,
    altFormat: "d m Y",
    locale: "id"
});
});
</script>
<script>
    document.getElementById('addTenaga').addEventListener('click', function() {

    let template = document.getElementById('tenagaTemplate');
    let row = template.content.cloneNode(true);
    document.querySelector('#tenagaTable').appendChild(row);

    $('.select2').select2({
    width:'100%'
    });

});

    document.getElementById('addWork').addEventListener('click', function() {

    let template = document.getElementById('kerjaTemplate');
    let row = template.content.cloneNode(true);
    document.querySelector('#workTable').appendChild(row);

    $('.select2').select2({
    width:'100%'
    });

});

document.getElementById('addMaterial').addEventListener('click', function() {

    let table = document.querySelector('#materialTable');
    let row = table.querySelector('tr').cloneNode(true);

    row.querySelectorAll('input')
    .forEach(i => i.value='');

    table.appendChild(row);

});

document.addEventListener('click', function(e) {

    if(e.target.classList.contains('removeTenaga')) {
        e.target.closest('tr').remove();
    }

    if(e.target.classList.contains('removeWork')) {
        e.target.closest('tr').remove();
    }

    if(e.target.classList.contains('removeMaterial')) {
        e.target.closest('tr').remove();
    }

});
</script>
<script>

$(document).on('change', '.worker-select', function(){

    let td = $(this).closest('td');
    let manualInput = td.find('.manual-input');

    if($(this).val() === 'manual'){
        manualInput.removeClass('d-none');
    }else{
        manualInput.addClass('d-none');
        manualInput.val('');
    }

});
$(document).on('change', '.rab-select', function(){
    let row = $(this).closest('tr');

    let option = $(this).find(':selected');

    let volume = option.data('volume');

    row.find('.volume-input').val(volume);
    let satuan = option.data('satuan');

    row.find('.satuan-input').val(satuan);

    let td = $(this).closest('td');
    let manualInput = td.find('.manual-rab');

    if($(this).val() === 'manual'){
        manualInput.removeClass('d-none');
    }else{
        manualInput.addClass('d-none');
        manualInput.val('');
    }

});

</script>
@endpush