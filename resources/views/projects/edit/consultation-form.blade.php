@php
    $consultation = $project->consultation;
@endphp


<div class="card mb-4">
    <div class="card-header fw-bold">Edit Data Konsultasi</div>
    <div class="card-body">
        <form action="{{ route('consultations.update', $consultation->id) }}" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="consultation_id" value="{{ $consultation->id }}">

            <div class="row g-4">

                <div class="col-md-4">
                    <label class="form-label">Nama Customer</label>
                    <input type="text" name="contact_name" class="form-control"
                        value="{{ old('contact_name', $consultation->contact_name) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">No HP</label>
                    <input type="text" name="contact_phone" class="form-control"
                        value="{{ old('contact_phone', $consultation->contact_phone) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Karyawan Penangan</label>
                    <select name="employee_id" class="form-select select2">
                        <option value="">-- Pilih Karyawan --</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}"
                                {{ $emp->id == old('employee_id', $consultation->employee_id) ? 'selected' : '' }}>
                                {{ $emp->display_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Ukuran Tanah</label>
                    <input type="text" name="site_area" class="form-control"
                        value="{{ old('site_area', $consultation->site_area) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Ukuran Bangunan</label>
                    <input type="text" name="building_area" class="form-control"
                        value="{{ old('building_area', $consultation->building_area) }}">
                </div>
            </div>

            <hr>

            <label class="form-label">Uraian Konsultasi</label>

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

                    @foreach($consultation->items as $i => $item)
                    <tr>
                        <td class="row-no text-center">{{ $i + 1 }}</td>

                        <td>
                            <textarea name="items[{{ $i }}][description]" class="form-control" rows="2">{{ trim($item->description) }}</textarea>
                        </td>

                        <td>
                            <textarea name="items[{{ $i }}][remark]" class="form-control" rows="2">{{ trim($item->remark) }}</textarea>
                        </td>

                        <td>
                            <button type="button" class="btn btn-sm btn-danger remove-row">−</button>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>

            <button type="button" id="add-row" class="btn btn-sm btn-dark">+ Tambah Uraian</button>

            <hr>

            <div class="row mt-3">
                <div class="col-md-6">
                    <label class="form-label">Tanda Tangan Konsultan</label><br>
                    <input type="checkbox" name="consultant_signed" value="1"
                        {{ $consultation->consultant_signed ? 'checked' : '' }}>
                    <span class="ms-2">Saya menyetujui</span>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Tanda Tangan Client</label><br>
                    <input type="checkbox" name="client_signed" value="1"
                        {{ $consultation->client_signed ? 'checked' : '' }}>
                    <span class="ms-2">Saya menyetujui</span>
                </div>
            </div>

            <div class="mt-3">
                <label class="form-label">Catatan Tambahan</label>
                <textarea name="notes" class="form-control" rows="3">
                    {{ old('notes', $consultation->notes) }}
                </textarea>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="documentation" class="form-label">Upload Foto Dokumentasi :</label>

                <div class="d-flex gap-3 align-items-start">
                    <!-- INPUT FILE -->
                    <div class="flex-fill">
                        <input 
                            type="file" 
                            name="documentation" 
                            id="documentation"
                            class="form-control" 
                            accept="image/*"
                            onchange="previewDocumentation(this)"
                        >

                        @error('documentation')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- PREVIEW -->
                    <div>
                        <img 
                            id="preview-documentation"
                            src="{{ isset($consultation) && $consultation->documentation 
                                    ? asset('storage/'.$consultation->documentation) 
                                    : '' }}"
                            style="width:120px; height:120px; object-fit:cover; border-radius:8px; border:1px solid #ddd; 
                            {{ empty($consultation->documentation) ? 'display:none;' : '' }}"
                        >
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <button class="btn btn-success btn-sm">Simpan</button>
                <button type="button" id="btn-cancel-consultation" class="btn btn-light btn-sm">Batal</button>
            </div>
        </form>
    </div>
</div>

@push('js')
<script>
document.getElementById('add-row').addEventListener('click', function () {
    let table = document.querySelector("#items-table tbody");
    let rowCount = table.rows.length;

    let row = `
        <tr>
            <td class="row-no text-center">${rowCount + 1}</td>
            <td><textarea name="items[${rowCount}][description]" class="form-control" rows="2"></textarea></td>
            <td><textarea name="items[${rowCount}][remark]" class="form-control" rows="2"></textarea></td>
            <td><button type="button" class="btn btn-sm btn-danger remove-row">−</button></td>
        </tr>
    `;

    table.insertAdjacentHTML('beforeend', row);
});

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
function previewDocumentation(input) {
    const preview = document.getElementById('preview-documentation');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };

        reader.readAsDataURL(input.files[0]);
    } else {
        preview.src = '';
        preview.style.display = 'none';
    }
}
</script>
@endpush