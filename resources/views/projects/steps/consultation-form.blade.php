<form 
      action="{{ route('projects.consultations.store') }}"
      method="POST"
      enctype="multipart/form-data"
      data-project-type="{{ $project->project_type }}">

    @csrf
    <input type="hidden" name="project_id" value="{{ $project->id }}">

    <div class="row g-4">
        <div class="col-md-4">
            <label class="form-label">Nama Customer</label>
            <input type="text" class="form-control"
                   name="contact_name"
                   value="{{ old('contact_name', $project->customer->user->fullname ?? '') }}">
        </div>

        <div class="col-md-4">
            <label class="form-label">No HP</label>
            <input type="text" class="form-control"
                   name="contact_phone"
                   value="{{ old('contact_phone', $project->customer->user->phone ?? '') }}">
        </div>

        <div class="col-md-4">
            <label class="form-label">Karyawan</label>
            <select name="employee_id" class="form-select select2" required>
                <option value="">-- Pilih Karyawan --</option>
                @foreach($employees as $employee)
                <option value="{{ $employee->id }}">
                    {{ $employee->display_name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Ukuran Tanah</label>
            <input type="text" class="form-control" name="site_area">
        </div>

        <div class="col-md-4">
            <label class="form-label">Ukuran Bangunan</label>
            <input type="text" class="form-control" name="building_area">
        </div>
    </div>

    <div class="mb-3 mt-4">
        <label class="form-label">Uraian</label>

        <table class="table table-sm table-bordered" id="consultation-items-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Uraian</th>
                    <th>Keterangan</th>
                    <th width="1%"></th>
                </tr>
            </thead>
            <tbody id="consultation-items-body"
                  data-project-type="{{ $project->project_type }}">
                @if(old('items'))
                    @foreach(old('items', [ ['description' => '', 'remark' => ''] ]) as $i => $it)
                        <tr>
                            <td class="row-no text-center">{{ $i + 1 }}</td>

                            <td>
                                <textarea name="items[{{ $i }}][description]" class="form-control" rows="2">{{ data_get($it, 'description') }}</textarea>
                            </td>

                            <td>
                                <textarea name="items[{{ $i }}][remark]" class="form-control" rows="2">{{ data_get($it, 'remark') }}</textarea>
                            </td>

                            <td>
                                <button type="button" class="btn btn-sm btn-danger remove-row">-</button>
                            </td>
                        </tr>
                    @endforeach

                @else
                    <tr>
                        <td class="row-no text-center">1</td>
                        <td><textarea name="items[0][description]" class="form-control" rows="2"></textarea></td>
                        <td><textarea name="items[0][remark]" class="form-control" rows="2"></textarea></td>
                        <td><button type="button" class="btn btn-sm btn-danger remove-row">-</button></td>
                    </tr>
                @endif
            </tbody>
        </table>

        <button type="button" data-target="consultation-items-table" class="btn btn-sm btn-dark add-row">+ Tambah Uraian</button>
    </div>
    
    <div class="col-md-6 mb-3">
        <label class="fw-bold">Upload Dokumen (PDF)</label>
        <input type="file"
            name="documents[]"
            class="form-control pdf-input"
            data-preview="preview-documents"
            accept="application/pdf"
            multiple>
        <div id="preview-documents" class="mt-3 d-flex flex-column gap-2"></div>
    </div> 

    <div class="row mb-3 mt-4">
        <div class="col-md-6">
            <label class="form-label fw-bold">Persetujuan Konsultan</label><br>
            <label>
                <input type="checkbox" name="consultant_signed" value="1">
                Saya sebagai Konsultan menyetujui hasil konsultasi ini
            </label>
        </div>

        <div class="col-md-6">
            <label class="form-label fw-bold">Persetujuan Customer</label><br>
            <label>
                <input type="checkbox" name="client_signed" value="1">
                Customer menyetujui hasil konsultasi ini
            </label>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Catatan Tambahan</label>
        <textarea name="notes" class="form-control" rows="3"></textarea>
    </div>

    <div class="md-6 mb-4">
            <label class="fw-bold">Upload Dokumen</label>
            <input type="file"
                name="documentation[]"
                class="form-control pdf-input"
                data-preview="preview-documents"
                accept="application/pdf"
                multiple>

            <div id="preview-documents"
                class="mt-3 d-flex flex-wrap gap-3"></div>
    </div>

    <div class="d-flex gap-2 mt-3">
        <button class="btn btn-dark">Simpan Konsultasi</button>
    </div>
</form>
@push('js')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "-- Pilih --",
            width: '100%'
        });
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const tbody = document.getElementById('consultation-items-body');
    if (!tbody) return;

    const projectType = tbody.dataset.projectType;
    if (projectType != '2') return; // hanya RAB

    // kosongkan default row
    tbody.innerHTML = '';

    const templates = [
        'Desain Denah',
        'Desain 3D',
        'Desain DED'
    ];

    templates.forEach((label, i) => {
        const row = document.createElement('tr');
        row.dataset.fixed = "1"; // 🔒 FLAG FIXED

        row.innerHTML = `
            <td class="row-no text-center">${i + 1}</td>

            <td>
                <input type="hidden"
                       name="items[${i}][description]"
                       value="${label}">
                ${label}
            </td>

            <td>
                <label class="me-3">
                    <input type="radio"
                           name="items[${i}][remark]"
                           value="Ada"> Ada
                </label>
                <label>
                    <input type="radio"
                           name="items[${i}][remark]"
                           value="Tidak"> Tidak
                </label>
            </td>

            <td></td> <!-- ⛔ TANPA REMOVE -->
        `;
        tbody.appendChild(row);
    });
});
</script>
<script>
document.addEventListener('submit', function (e) {

    const form = e.target;
    if (!form.closest('form')) return;

    const tbody = document.getElementById('consultation-items-body');
    if (!tbody) return;

    let valid = true;
    let messageShown = false;

    tbody.querySelectorAll('tr[data-fixed="1"]').forEach((row, index) => {
        const radios = row.querySelectorAll('input[type="radio"]');
        const checked = Array.from(radios).some(r => r.checked);

        if (!checked) {
            valid = false;
            if (!messageShown) {
                alert(`Uraian "${row.querySelector('strong').innerText}" wajib dipilih (Ada / Tidak)`);
                messageShown = true;
            }
        }
    });

    if (!valid) {
        e.preventDefault();
    }
});
</script>


@endpush