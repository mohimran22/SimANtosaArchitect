@php
    $survey = $project->survey;

    // Ambil employees dari survey level (level 3)
    $surveyLevel = $project->levels->firstWhere('level_order', 3);
    $surveyEmployees = $surveyLevel ? $surveyLevel->employees : collect();
@endphp

<div class="card mb-4">
    <div class="card-header fw-bold">Edit Data Survei</div>
    <div class="card-body">
        <form 
            action="{{ route('surveys.update', $survey->id) }}"
            method="POST"
            enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <input type="hidden" name="project_id" value="{{ $project->id }}">

            <div class="row g-4">

                {{-- CUSTOMER --}}
                <div class="col-md-4">
                    <label class="form-label">Nama Customer</label>
                    <input type="text" class="form-control"
                        name="contact_name"
                        value="{{ old('contact_name', $survey->contact_name) }}">
                </div>

                {{-- PETUGAS SURVEI --}}
                <div class="col-md-4">
                    <label class="form-label">Petugas Survei</label>
                    <select name="employee_id[]" class="form-select select2" multiple required>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}"
                                {{ $surveyEmployees->contains('id', $emp->id) ? 'selected' : '' }}>
                                {{ $emp->display_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- TANGGAL --}}
                <div class="col-md-2">
                    <label class="form-label">Tanggal Survei</label>
                    <input type="date" 
                           name="survey_date" 
                           class="form-control" 
                           required
                           value="{{ old('survey_date', $survey->survey_date) }}">
                </div>

                {{-- WAKTU --}}
                <div class="col-md-2">
                    <label class="form-label">Waktu Survei</label>
                    <input type="time" 
                           name="survey_time" 
                           class="form-control" 
                           required
                           value="{{ old('survey_time', $survey->survey_time) }}">
                </div>

                {{-- SITE AREA --}}
                <div class="col-md-4">
                    <label class="form-label">Ukuran Tanah (Aktual)</label>
                    <input type="text" class="form-control" name="site_area"
                           value="{{ old('site_area', $survey->site_area) }}">
                </div>

                {{-- BUILDING --}}
                <div class="col-md-4">
                    <label class="form-label">Ukuran Bangunan (Aktual)</label>
                    <input type="text" class="form-control" name="building_area"
                           value="{{ old('building_area', $survey->building_area) }}">
                </div>

            </div>

            
            {{-- <div class="mt-4">
                <label class="fw-bold">Foto Hasil Survei / Denah</label>
                <div class="text-muted mb-2">Foto seperti sketsa atau kondisi lapangan</div>
                @if($survey->surveyimages)
                    <div class="d-flex flex-wrap gap-3 mb-3">
                        @foreach(($survey->surveyimages) as $img)
                            <div class="position-relative">
                                <img src="{{ asset('storage/' . $img->file_path) }}" 
                                    class="rounded border" 
                                    width="120" height="120" 
                                    style="object-fit: cover;">
                            </div>
                        @endforeach
                    </div>
                @endif

                <input type="file" 
                    name="result_images[]" 
                    id="result-images-input"
                    class="form-control" 
                    accept="image/*" 
                    multiple>

                <div id="preview-result-images" class="mt-3 d-flex flex-wrap gap-3"></div>
            </div> --}}
            <div class="mt-4">
                <label class="fw-bold">Foto Hasil Survei / Denah</label>
                <div class="text-muted mb-2">Foto seperti sketsa atau kondisi lapangan</div>

                {{-- FOTO LAMA --}}
                @if($survey->surveyimages)
                    <div id="old-result-images" class="d-flex flex-wrap gap-3 mb-3">
                        @foreach($survey->surveyimages as $img)
                            <img src="{{ asset('storage/' . $img->file_path) }}"
                                width="120" height="120" class="rounded border"
                                style="object-fit: cover;">
                        @endforeach
                    </div>
                @endif

                {{-- INPUT FILE --}}
                <input type="file"
                    name="result_images[]"
                    id="result-images-input"
                    class="form-control"
                    accept="image/*"
                    multiple>

                {{-- PREVIEW BARU --}}
                <div id="preview-result-images"
                    class="mt-3 d-flex flex-wrap gap-3"></div>
            </div>

            {{-- ITEM URAIAN --}}
            <div class="mb-3 mt-3">
                <label class="form-label">Uraian</label>

                <table class="table table-sm table-bordered" id="items-table">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Uraian</th>
                            <th>Keterangan</th>
                            <th width="1%"></th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($survey->items as $i => $item)
                            <tr>
                                <td class="row-no text-center">{{ $i + 1 }}</td>
                                <td>
                                    <textarea name="items[{{ $i }}][description]" 
                                              class="form-control" rows="2">{{ $item->description }}</textarea>
                                </td>
                                <td>
                                    <textarea name="items[{{ $i }}][remark]" 
                                              class="form-control" rows="2">{{ $item->remark }}</textarea>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-danger remove-row">−</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <button type="button" id="tambah-bariss" class="btn btn-sm btn-dark">+ Tambah Uraian</button>
            </div>

            <div class="mt-4">

                <label class="fw-bold">Foto Dokumentasi</label>
                <div class="text-muted mb-2">Upload foto proses survey (opsional)</div>

                @if($survey->documentations)
                    <div id="old-documentation" class="d-flex flex-wrap gap-3 mb-3">
                        @foreach($survey->documentations as $doc)
                            <img src="{{ asset('storage/' . $doc->file_path) }}"
                                width="120" height="120" class="rounded border"
                                style="object-fit: cover;">
                        @endforeach
                    </div>
                @endif

                <input type="file"
                    name="documentation[]"
                    id="documentation-input"
                    class="form-control"
                    accept="image/*"
                    multiple>

                <div id="preview-documentation"
                    class="mt-3 d-flex flex-wrap gap-3"></div>
            </div>
            {{-- TTD --}}
            <div class="row mt-3">
                <div class="col-md-6">
                    <label class="form-label">Tanda Tangan Surveyor</label><br>
                    <input type="checkbox" name="consultant_signed" value="1"
                        {{ $survey->consultant_signed ? 'checked' : '' }}>
                    <span class="ms-2">Saya menyetujui</span>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Tanda Tangan Client</label><br>
                    <input type="checkbox" name="client_signed" value="1"
                        {{ $survey->client_signed ? 'checked' : '' }}>
                    <span class="ms-2">Saya menyetujui</span>
                </div>
            </div>

            {{-- CATATAN --}}
            <div class="mb-3 mt-3">
                <label class="form-label">Catatan Tambahan</label>
                <textarea name="notes" class="form-control" rows="3">{{ old('notes', $survey->notes) }}</textarea>    
            </div>

            {{-- SUBMIT --}}
            <div class="mt-4">
                <button class="btn btn-success">Simpan</button>
                <button type="button" id="btn-cancel-survey" class="btn btn-light">Batal</button>
            </div>

        </form>
    </div>
</div>

@push('js')
<script>
document.getElementById('tambah-bariss').addEventListener('click', function () {
    let table = document.querySelector("#items-table tbody");
    let index = table.rows.length;

    table.insertAdjacentHTML('beforeend', `
        <tr>
            <td class="row-no text-center">${index + 1}</td>
            <td><textarea name="items[${index}][description]" class="form-control" rows="2"></textarea></td>
            <td><textarea name="items[${index}][remark]" class="form-control" rows="2"></textarea></td>
            <td><button type="button" class="btn btn-sm btn-danger remove-row">−</button></td>
        </tr>
    `);
});

// remove row
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-row')) {
        e.target.closest('tr').remove();
        document.querySelectorAll("#items-table tbody tr").forEach((tr, i) => {
            tr.querySelector('.row-no').innerText = i + 1;
        });
    }
});
</script>

<script>
document.getElementById('result-images-input')
    .addEventListener('change', function(e) {
  document.getElementById('old-result-images')?.remove();
    const preview = document.getElementById('preview-result-images');
    preview.innerHTML = ""; // hapus preview gambar lama

    const files = e.target.files;
    [...files].forEach(file => {
        let reader = new FileReader();
        reader.onload = function(ev) {
            preview.insertAdjacentHTML(
                'beforeend',
                `<img src="${ev.target.result}" width="120" height="120" class="rounded border me-2 mb-2" style="object-fit:cover;">`
            );
        };
        reader.readAsDataURL(file);
    });
});
</script>

// PREVIEW DOKUMENTASI
<script>
document.getElementById('documentation-input')
    .addEventListener('change', function(e) {
          document.getElementById('old-documentation')?.remove();
    const preview = document.getElementById('preview-documentation');
    preview.innerHTML = ""; // hapus preview lama

    const files = e.target.files;
    [...files].forEach(file => {
        let reader = new FileReader();
        reader.onload = function(ev) {
            preview.insertAdjacentHTML(
                'beforeend',
                `<img src="${ev.target.result}" width="120" height="120" class="rounded border me-2 mb-2" style="object-fit:cover;">`
            );
        };
        reader.readAsDataURL(file);
    });
});
</script>

@endpush
