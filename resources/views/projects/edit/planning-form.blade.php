{{-- <form action="{{ route('plannings.update') }}" method="POST">
    @csrf
    @method('put')

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
            <input type="date" name="planning_date" class="form-control" required>
        </div>

        <div class="col-md-4">
            <label class="form-label">Waktu Survei</label>
            <input type="time" name="planning_time" class="form-control" required>
        </div>
    </div>
    <div class="section-block mb-3 mt-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="same_address" name="same_address">
                                <label class="form-check-label fw-semibold" for="same_address">
                                    Lokasi Survei sama dengan Lokasi proyek?
                                </label>
                            </div>
                        </div>
    <div class="section-block mb-5">
                                <h3 class="fw-semibold mb-3 border-bottom pb-2">Lokasi Proyek</h3>
                                <div class="row g-4">
                                    <div class="col-12">
                                        <label class="form-label required">Alamat Lengkap</label>
                                        <textarea name="survey_address" rows="3" class="form-control @error('survey_address') is-invalid @enderror" required>{{ old('survey_address') }} </textarea>
                                        @error('survey_address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row g-4 mt-2">
                                    <div class="col-md-6">
                                        <label class="form-label required">Provinsi</label>
                                        <select name="province_id" id="survey_province" 
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
                                        <select name="city_id" id="survey_city" 
                                                class="form-select select2 @error('city_id') is-invalid @enderror" required>
                                            <option value="">-- Pilih Kota --</option>
                                        </select>
                                        @error('city_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-5">
                                        <label class="form-label required">Kecamatan</label>
                                        <select name="district_id" id="survey_district" 
                                                class="form-select select2 @error('district_id') is-invalid @enderror" required>
                                            <option value="">-- Pilih Kecamatan --</option>
                                        </select>
                                        @error('district_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-5">
                                        <label class="form-label required">Kelurahan</label>
                                        <select name="sub_district_id" id="survey_sub_district" 
                                                class="form-select select2 @error('sub_district_id') is-invalid @enderror" required>
                                            <option value="">-- Pilih Kelurahan --</option>
                                        </select>
                                        @error('sub_district_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label required">Kode Pos</label>
                                        <select name="postal_code_id" id="survey_postal_code" 
                                                class="form-select select2 @error('postal_code_id') is-invalid @enderror" required>
                                            <option value="">-- Pilih Kode Pos --</option>
                                        </select>
                                        @error('postal_code_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

    



    <div class="mt-3">
        <label class="form-label">Catatan Survei</label>
        <textarea name="planning_notes" class="form-control" rows="3"></textarea>
    </div>

    <div class="text-end mt-4">
        <button class="btn btn-dark">Simpan Form</button>
    </div>
</form> --}}
