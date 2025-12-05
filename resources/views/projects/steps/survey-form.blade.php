<form action="{{ route('projects.surveys.store') }}" method="POST">
    @csrf

    <input type="hidden" name="project_id" value="{{ $project->id }}">

    <div class="row g-4">
        <div class="col-md-4">
            <label class="form-label">Rencana Petugas Survei</label>
            <select name="employee_id[]" class="form-select select2" multiple required>
                @foreach($employees as $employee)
                <option value="{{ $employee->id }}">{{ $employee->display_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Tanggal Survei</label>
            <input type="date" name="survey_date" class="form-control" required>
        </div>

        <div class="col-md-4">
            <label class="form-label">Waktu Survei</label>
            <input type="time" name="survey_time" class="form-control" required>
        </div>
    </div>
    <div class="section-block mb-5">
                                <h3 class="fw-semibold mb-3 border-bottom pb-2">Lokasi Proyek</h3>
                                <div class="row g-4">
                                    <div class="col-12">
                                        <label class="form-label required">Alamat Lengkap</label>
                                        <textarea name="project_location" rows="3" class="form-control @error('project_location') is-invalid @enderror" required>{{ old('project_location') }} </textarea>
                                        @error('project_location')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row g-4 mt-2">
                                    <div class="col-md-6">
                                        <label class="form-label required">Provinsi</label>
                                        <select name="province_id" id="province" 
                                                class="form-select select2 @error('province_id') is-invalid @enderror" required>
                                            <option value="">-- Pilih Provinsi --</option>
                                            @foreach($provinces as $province)
                                                <option value="{{ $province->id }}" {{ old('province_id') == $province->id ? 'selected' : '' }}>
                                                    {{ $province->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('province_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label required">Kabupaten/Kota</label>
                                        <select name="city_id" id="city" 
                                                class="form-select select2 @error('city_id') is-invalid @enderror" required>
                                            <option value="">-- Pilih Kota --</option>
                                        </select>
                                        @error('city_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-5">
                                        <label class="form-label required">Kecamatan</label>
                                        <select name="district_id" id="district" 
                                                class="form-select select2 @error('district_id') is-invalid @enderror" required>
                                            <option value="">-- Pilih Kecamatan --</option>
                                        </select>
                                        @error('district_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-5">
                                        <label class="form-label required">Kelurahan</label>
                                        <select name="sub_district_id" id="sub_district" 
                                                class="form-select select2 @error('sub_district_id') is-invalid @enderror" required>
                                            <option value="">-- Pilih Kelurahan --</option>
                                        </select>
                                        @error('sub_district_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label required">Kode Pos</label>
                                        <select name="postal_code_id" id="postal_code" 
                                                class="form-select select2 @error('postal_code_id') is-invalid @enderror" required>
                                            <option value="">-- Pilih Kode Pos --</option>
                                        </select>
                                        @error('postal_code_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

    
    {{-- @include('projects.partials.location-selector', [
        'prefix' => 'survey_',
        'locationOld' => old()
    ]) --}}


    <div class="mt-3">
        <label class="form-label">Catatan Survei</label>
        <textarea name="survey_notes" class="form-control" rows="3"></textarea>
    </div>

    <div class="text-end mt-4">
        <button class="btn btn-dark">Simpan Survei</button>
    </div>
</form>
