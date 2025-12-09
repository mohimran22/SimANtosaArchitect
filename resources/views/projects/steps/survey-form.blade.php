<form 
      action="{{ route('projects.surveys.store') }}"
      method="POST"
      enctype="multipart/form-data">

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
            <label class="form-label">Petugas Survei</label>
            <select name="employee_id[]" class="form-select select2" multiple required>
                @foreach($employees as $employee)
                <option value="{{ $employee->id }}">{{ $employee->display_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label">Tanggal Survei</label>
            <input type="date" name="survey_date" class="form-control" required>
        </div>

        <div class="col-md-2">
            <label class="form-label">Waktu Survei</label>
            <input type="time" name="survey_time" class="form-control" required>
        </div>

        

        <div class="col-md-4">
            <label class="form-label">Ukuran Tanah (Aktual) </label>
            <input type="text" class="form-control" name="site_area">
        </div>

        <div class="col-md-4">
            <label class="form-label">Ukuran Bangunan (Aktual) </label>
            <input type="text" class="form-control" name="building_area">
        </div>
    </div>

    <div class="mt-4">
        <label class="fw-bold">Foto Hasil Survei / Denah</label>
        <div class="text-muted mb-2">Foto hasil survei seperti sketsa, denah, atau kondisi lapangan</div>

        <input type="file" 
            name="result_images[]" 
            class="form-control" 
            accept="image/*" 
            multiple>

        <div id="preview-result-images" class="mt-3 d-flex flex-wrap gap-3"></div>
    </div>

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
                                    @if(old('items'))
                                        @foreach(old('items') as $i => $it)
                                            <tr>
                                                <td class="row-no text-center">{{ $i + 1 }}</td>
                                                <td><textarea name="items[{{ $i }}][description]" class="form-control" rows="2">{{ $it['description'] }}</textarea></td>
                                                <td><textarea name="items[{{ $i }}][remark]" class="form-control" rows="2">{{ $it['remark'] }}</textarea></td>
                                                <td><button type="button" class="btn btn-sm btn-danger remove-row">-</button></td>
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

                            <button type="button" id="add-row" class="btn btn-sm btn-dark">+ Tambah Uraian</button>
                        </div>

    <div class="mt-4">
        <label class="fw-bold">Foto Dokumentasi</label>
        <div class="text-muted mb-2">Upload foto proses survey (opsional)</div>

        <input type="file" 
            name="documentation[]" 
            class="form-control" 
            accept="image/*" 
            multiple>

        <div id="preview-documentation" class="mt-3 d-flex flex-wrap gap-3"></div>
    </div>
    <div class="row mb-3 mt-4">
        <div class="col-md-6">
            <label class="form-label fw-bold">Persetujuan Petugas Survei</label><br>
            <label>
                <input type="checkbox" name="consultant_signed" value="1">
                Saya sebagai Petugas Survei menyetujui hasil survei ini
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

    <div class="d-flex gap-2 mt-3">
        <button class="btn btn-dark text-end">Simpan Form Survei</button>

        <button type="button" class="btn btn-dark" id="btnToDesain">
            Lanjut Ke Penawaran Jasa Desain
        </button>
        
        <input type="hidden" name="go_to_desain" id="go_to_desain">

        <a href="#" id="print-preview" class="btn btn-outline-secondary" style="display:none;" target="_blank">
            Cetak / Preview PDF
        </a>
    </div>
</form>
