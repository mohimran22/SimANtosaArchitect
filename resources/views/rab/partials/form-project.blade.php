<div class="card mb-4">
    <div class="card-header fw-bold">Data Proyek</div>
    <div class="card-body">

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Nama Proyek <span class="text-danger">*</span></label>
                <input type="text" name="project_name" class="form-control" 
                    value="{{ old('project_name', $project->project_name) }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Jenis Proyek</label>
                <select name="project_type" class="form-select" value="{{ old('project_type') }}" required>
                    <option value="">-- Pilih --</option>
                    <option value="1" {{ old('project_type', $project->project_type) == 1 ? 'selected' : '' }}>Desain</option>
                    <option value="2" {{ old('project_type', $project->project_type) == 2 ? 'selected' : '' }}>Build</option>
                </select>
            </div>
        </div>

        <div class="row mb-3">

            <div class="col-md-6">
                <label class="form-label">Customer</label>
                <select name="customer_id" class="form-select select2">
                    <option value="">-- Pilih Customer --</option>
                    @foreach($customers as $cus)
                        <option value="{{ $cus->id }}"
                            {{ $cus->id == old('customer_id', $project->customer_id) ? 'selected' : '' }}>
                            {{ $cus->display_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Karyawan Penanggungjawab</label>
                <select name="employee_id" class="form-select select2">
                    <option value="">-- Pilih Karyawan --</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}"
                            {{ $emp->id == old('employee_id', $project->employee_id) ? 'selected' : '' }}>
                            {{ $emp->display_name }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control"
                    value="{{ old('start_date', $project->start_date) }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Lokasi Proyek</label>
                <input type="text" name="project_location" class="form-control"
                    value="{{ old('project_location', $project->project_location) }}">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label required">Provinsi</label>
                    <select name="province_id" id="province" class="form-select select2" required>
                        <option value="">-- Pilih Provinsi --</option>
                        @foreach($provinces as $province)
                            <option value="{{ $province->id }}"
                                {{ $project->province_id == $province->id ? 'selected' : '' }}>
                                {{ $province->name }}
                            </option>
                        @endforeach
                    </select>
            </div>

            <div class="col-md-6">
                <label class="form-label required">Kabupaten/Kota</label>
                    <select name="city_id" id="city" class="form-select select2" required>
                        <option value="">-- Pilih Kota --</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}"
                                {{ $project->city_id == $city->id ? 'selected' : '' }}>
                                {{ $city->name }}
                            </option>
                        @endforeach
                    </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-5">
                <label class="form-label required">Kecamatan</label>
                                                <select name="district_id" id="district" class="form-select select2" required>
                                                    <option value="">-- Pilih Kecamatan --</option>
                                                    @foreach($districts as $district)
                                                        <option value="{{ $district->id }}"
                                                            {{ $project->district_id == $district->id ? 'selected' : '' }}>
                                                            {{ $district->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
            </div>

            <div class="col-md-5">
                <label class="form-label required">Kelurahan</label>
                    <select name="sub_district_id" id="sub_district" class="form-select select2" required>
                        <option value="">-- Pilih kelurahan --</option>
                        @foreach($subDistricts as $sub_district)
                            <option value="{{ $sub_district->id }}"
                                {{ $project->sub_district_id == $sub_district->id ? 'selected' : '' }}>
                                {{ $sub_district->name }}
                            </option>
                        @endforeach
                    </select>
            </div>
            <div class="col-md-2">
                <label class="form-label required">Kode Pos</label>
                                                <select name="postal_code_id" id="postal_code" class="form-select select2" required>
                                                    <option value="">-- Pilih Kode Pos --</option>
                                                    @foreach($postalCodes as $postal_code)
                                                        <option value="{{ $postal_code->id }}"
                                                            {{ $project->postal_code_id == $postal_code->id ? 'selected' : '' }}>
                                                            {{ $postal_code->postal_code }}
                                                        </option>
                                                    @endforeach
                                                </select>
            </div>
        </div>


    </div>
</div>
