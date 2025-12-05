<form 
      action="{{ route('projects.consultations.store') }}"
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

    <div class="mb-3">
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

    {{-- @include('projects.steps.partials.consultation-upload') --}}
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
                                        src="" 
                                        alt="Preview" 
                                        style="display:none; width:120px; height:120px; object-fit:cover; border-radius:8px; border:1px solid #ddd;"
                                    >
                                </div>
                            </div>
                        </div>


    <div class="d-flex gap-2 mt-3">
        <button class="btn btn-dark">Simpan Konsultasi</button>

        <button type="button" class="btn btn-dark" id="btnToSurvey">
            Lanjut Ke Survei
        </button>

        <a href="#" id="print-preview" class="btn btn-outline-secondary" style="display:none;" target="_blank">
            Cetak / Preview PDF
        </a>
    </div>
</form>
