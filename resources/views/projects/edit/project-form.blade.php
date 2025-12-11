<div class="card shadow-sm border-0 mb-4">
    <div class="card-body px-5 py-4">
    <h3 class="mb-4 fw-bold">Edit Data Proyek</h3>
        <form id="project-edit-form"
            action="{{ route('projects.update', $project->id) }}"
            method="POST">

            @csrf
            @method('PUT')

            <div class="row g-4">

                <div class="col-md-4">
                    <label class="fw-semibold">Nama Proyek</label>
                    <input type="text" name="project_name" class="form-control"
                        value="{{ $project->project_name }}">
                </div>

                <div class="col-md-4">
                    <label class="fw-semibold">Customer</label>
                    <input type="text" class="form-control"
                        value="{{ $project->customer->display_name }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="fw-semibold">Karyawan</label>
                    <input type="text" class="form-control"
                        value="{{ $project->employee->display_name }}" readonly>
                </div>

                <div class="col-12 mt-3">
                    <label class="fw-semibold">Alamat Lokasi</label>
                    <textarea name="project_location" class="form-control" rows="3">{{ $project->project_location }}</textarea>
                </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label required">Provinsi</label>
                    <select id="edit_province" name="province_id" class="form-select select2">
                        <option value="">-- Pilih Provinsi --</option>
                        @foreach ($provinces as $prov)
                            <option value="{{ $prov->id }}" {{ $prov->id == $project->province_id ? 'selected' : '' }}>
                                {{ $prov->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label required">Kabupaten/Kota</label>
                        <select id="edit_city" name="city_id" class="form-select select2">
                            <option value="">-- Pilih Kota --</option>
                        </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-5">
                    <label class="form-label required">Kecamatan</label>
                        <select id="edit_district" name="district_id" class="form-select select2">
                            <option value="">-- Pilih Kecamatan --</option>
                        </select>
                </div>

                <div class="col-md-5">
                    <label class="form-label required">Kelurahan</label>
                        <select id="edit_sub_district" name="sub_district_id" class="form-select select2"></select>
                </div>
                <div class="col-md-2">
                    <label class="form-label required">Kode Pos</label>
                        <select id="edit_postal_code" name="postal_code_id" class="form-select select2"></select>
                </div>
            </div>
            </div>

            <div class="mt-4">
                <button class="btn btn-success btn-sm">Simpan</button>
                <button type="button" id="btn-cancel-project" class="btn btn-light btn-sm">Batal</button>
            </div>

        </form>
    </div>
</div>

