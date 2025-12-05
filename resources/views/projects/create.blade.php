@extends('tablar::page')

@section('content')

<div class="page-header d-print-none mb-4">
    <div class="container-xl">
        <div class="row align-items-center">
            <div class="col d-flex align-items-center">
                <a href="{{ route('projects.index') }}" class="btn btn-dark d-flex align-items-center" style="margin-left: 20px;">
                    <i class="ti ti-arrow-left"></i>
                </a>
                
                    <h2 class="page-title mb-0">Tambah Proyek</h2>
                
            </div>
        </div>
    </div>
</div>


<div class="page-body">
    <div class="container-xl">
        <div class="card shadow-sm border-0">
            <div class="card-body px-5 py-4">
                @if(!$project)
                    <h3 class="mb-4 fw-bold">Buat Proyek Baru</h3>
                        <form action="{{ route('projects.store') }}" method="POST">
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

                            <div class="mb-3">
                                <small class="text-danger fw-semibold">
                                    * : Wajib diisi
                                </small>
                            </div>
                            {{-- Buat Proyek Baru --}}
                            <div class="section-block mb-5"> 
                                <h3 class="fw-semibold mb-3 border-bottom pb-2">Informasi Proyek</h3>
                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <label class="form-label required">Nama Proyek</label>
                                        <input type="text" name="project_name" class="form-control @error('project_name') is-invalid @enderror"  value="{{ old('project_name') }}" required>
                                        @error('project_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-2">
                                        <label class="form-label required">Jenis Proyek</label>
                                        <select name="project_type" 
                                                class="form-select @error('project_type') is-invalid @enderror" 
                                                required>
                                            <option value="">-- Pilih --</option>
                                            <option value="1" {{ old('project_type') == '1' ? 'selected' : '' }}>Desain</option>
                                            <option value="2" {{ old('project_type') == '2' ? 'selected' : '' }}>Build</option>
                                        </select>
                                        @error('project_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label required">Tanggal Mulai Proyek</label>
                                                    <input type="date" name="start_date" class="form-control" required
                                                        value="{{ old('start_date') }}"
                                                        pattern="\d{4}-\d{2}-\d{2}" placeholder="YYYY-MM-DD">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Status Proyek</label>
                                        <select name="project_status" class="form-select">
                                            <option value="">-- Pilih Status --</option>
                                            @foreach($projectStatus as $key => $label)
                                                <option value="{{ $key }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{-- <div class="col-md-4">
                                        <label class="form-label">Tanggal Akhir Proyek</label>
                                                    <input type="date" name="end_date" class="form-control"
                                                        value="{{ old('end_date') }}"
                                                        pattern="\d{4}-\d{2}-\d{2}" placeholder="YYYY-MM-DD">
                                    </div> --}}
                                    <div class="col-md-4">
                                        <label class="form-label required">Customer</label>
                                        <select name="customer_id" 
                                                class="form-select select2 @error('customer_id') is-invalid @enderror" 
                                                required>
                                            <option value="">-- Pilih Customer --</option>
                                            @foreach($customers as $customer)
                                                <option value="{{ $customer->id }}" 
                                                    {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                    {{ $customer->display_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('customer_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label required">Karyawan</label>
                                        <select name="employee_id" 
                                                class="form-select select2 @error('employee_id') is-invalid @enderror" 
                                                required>
                                            <option value="">-- Pilih Karyawan --</option>
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee->id }}" 
                                                    {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                                    {{ $employee->display_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('employee_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Affiliator (Opsional)</label>
                                        <select name="affiliator_id" 
                                                class="form-select select2 @error('affiliator_id') is-invalid @enderror">
                                            <option value="">-- Pilih Affiliator --</option>
                                            @foreach($affiliators as $affiliator)
                                                <option value="{{ $affiliator->id }}" 
                                                    {{ old('affiliator_id') == $affiliator->id ? 'selected' : '' }}>
                                                    {{ $affiliator->display_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('affiliator_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
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

                            <div class="text-end mt-5">
                                <button type="submit" class="btn btn-dark px-4">
                                    <i class="ti ti-device-floppy me-1"></i> Buat Proyek baru
                                </button>
                            </div>
                        </form>
            </div>
            @else
                <div class="alert alert-success">Proyek berhasil dibuat.</div>

                <h3 class="mb-4 fw-bold">Detail Proyek</h3>

                <div class="card-body mb-4">
                    <div class="card-header fw-bold">Informasi Proyek</div>
                        <div class="card-body">

                            <div class="row g-4">

                                <div class="col-md-4">
                                    <label class="fw-semibold">Nama Proyek</label>
                                    <input type="text" class="form-control" value="{{ $project->project_name }}" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label class="fw-semibold">Customer</label>
                                    <input type="text" class="form-control" value="{{ $project->customer->display_name }}" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label class="fw-semibold">Karyawan</label>
                                    <input type="text" class="form-control" value="{{ $project->employee->display_name }}" readonly>
                                </div>

                                <div class="col-12 mt-3">
                                    <label class="fw-semibold">Alamat Lokasi</label>
                                    <textarea class="form-control" rows="3" readonly>{{ $project->project_location }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="fw-semibold">Provinsi</label>
                                    <input type="text" class="form-control" value="{{ $project->province->name }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="fw-semibold">Kabupaten/Kota</label>
                                    <input type="text" class="form-control" value="{{ $project->city->name }}" readonly>
                                </div>
                                <div class="col-md-5">
                                    <label class="fw-semibold">Kecamatan</label>
                                    <input type="text" class="form-control" value="{{ $project->district->name }}" readonly>
                                </div>
                                <div class="col-md-5">
                                    <label class="fw-semibold">Kelurahan</label>
                                    <input type="text" class="form-control" value="{{ $project->subDistrict->name }}" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label class="fw-semibold">Kode Pos</label>
                                    <input type="text" class="form-control" value="{{ $project->postalCode->postal_code }}" readonly>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div id="section-konsultasi" class="card-body px-5 py-4">
                    <h3 class="mb-4 fw-bold">Form Konsultasi</h3>
                    <form id="consultationForm" action="{{ route('projects.consultations.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="project_id" value="{{ $project->id }}">

                        <div class="row g-4">
                            <div class="col-md-4">
                                <label class="form-label">Nama Customer</label>
                                <input type="text" class="form-control" name="contact_name" value="{{ old('contact_name', $project->customer->user->fullname ?? '') }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">No HP</label>
                                <input type="text" class="form-control" name="contact_phone" value="{{ old('contact_phone', $project->customer->user->phone ?? '') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Karyawan</label>
                                <select name="employee_id" 
                                                    class="form-select select2 @error('employee_id') is-invalid @enderror" 
                                                    required>
                                                <option value="">-- Pilih Karyawan --</option>
                                                @foreach($employees as $employee)
                                                    <option value="{{ $employee->id }}" 
                                                        {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                                        {{ $employee->display_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('employee_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Ukuran Tanah</label>
                                <input type="text" class="form-control" name="site_area" value="{{ old('site_area') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Ukuran Bangunan</label>
                                <input type="text" class="form-control" name="building_area" value="{{ old('building_area') }}">
                            </div>
                        </div>

                        {{-- tabel uraian dinamis --}}
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

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Persetujuan Konsultan</label><br>
                                <label>
                                    <input type="checkbox" name="consultant_signed" value="1"
                                        {{ old('consultant_signed') ? 'checked' : '' }}>
                                    Saya sebagai Konsultan menyetujui hasil konsultasi ini
                                </label>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Persetujuan Customer</label><br>
                                <label>
                                    <input type="checkbox" name="client_signed" value="1"
                                        {{ old('client_signed') ? 'checked' : '' }}>
                                    Customer menyetujui hasil konsultasi ini
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Catatan Tambahan</label>
                            <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
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
                                        src="" 
                                        alt="Preview" 
                                        style="display:none; width:120px; height:120px; object-fit:cover; border-radius:8px; border:1px solid #ddd;"
                                    >
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button class="btn btn-dark">Simpan Konsultasi</button>
                            <button type="button" class="btn btn-dark" id="btnToSurvey">Lanjut Ke Survei</button>
                            <a href="{{ route('consultations.pdf', ['consultation' => 0]) }}" id="print-preview" class="btn btn-outline-secondary" style="display:none;" target="_blank">Cetak / Preview PDF</a>
                        </div>
                    </form>
                </div>

                <div id="section-detail-konsultasi" style="display:none;"></div>

                <div id="section-survey" style="display:none;" class="card-body px-5 py-4">
                    <h3 class="fw-bold mb-4">Form Rencana Survei Lapangan</h3>

                    <form action="{{ route('projects.surveys.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="project_id" value="{{ $project->id }}">

                        <div class="row g-4">
                            <div class="col-md-4">
                                <label class="form-label">ID</label>
                                <input type="text" name="id" class="form-control" required>
                            </div> 

                            <div class="col-md-4">
                                <label class="form-label">Rencana Petugas Survei</label>
                                <select name="employee_id" class="form-select select2" required>
                                    <option value="">-- Pilih Karyawan --</option>
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
                                <label class="form-label">Waktu Survei (Jam & Menit)</label>
                                <input type="time" name="survey_time" class="form-control" required>
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
                                        <textarea name="survey_location" rows="3" class="form-control @error('survey_location') is-invalid @enderror" required>{{ old('survey_location') }} </textarea>
                                        @error('survey_location')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row g-4 mt-2">
                                    <div class="col-md-6">
                                        <label class="form-label required">Provinsi</label>
                                        <select name="province_id" id="survey_province" 
                                                class="form-select select2 @error('survey_province_id') is-invalid @enderror" required>
                                            <option value="">-- Pilih Provinsi --</option>
                                            @foreach($provinces as $province)
                                                <option value="{{ $province->id }}" {{ old('survey_province_id') == $province->id ? 'selected' : '' }}>
                                                    {{ $province->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('survey_province_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label required">Kabupaten/Kota</label>
                                        <select name="city_id" id="survey_city" 
                                                class="form-select select2 @error('survey_city_id') is-invalid @enderror" required>
                                            <option value="">-- Pilih Kota --</option>
                                        </select>
                                        @error('survey_city_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-5">
                                        <label class="form-label required">Kecamatan</label>
                                        <select name="survey_district_id" id="survey_district" 
                                                class="form-select select2 @error('survey_district_id') is-invalid @enderror" required>
                                            <option value="">-- Pilih Kecamatan --</option>
                                        </select>
                                        @error('survey_district_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-5">
                                        <label class="form-label required">Kelurahan</label>
                                        <select name="sub_district_id" id="survey_sub_district" 
                                                class="form-select select2 @error('survey_sub_district_id') is-invalid @enderror" required>
                                            <option value="">-- Pilih Kelurahan --</option>
                                        </select>
                                        @error('survey_sub_district_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label required">Kode Pos</label>
                                        <select name="postal_code_id" id="survey_postal_code" 
                                                class="form-select select2 @error('survey_postal_code_id') is-invalid @enderror" required>
                                            <option value="">-- Pilih Kode Pos --</option>
                                        </select>
                                        @error('survey_postal_code_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                        <div class="mt-3">
                            <label class="form-label">Catatan Survei</label>
                            <textarea name="survey_notes" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="text-end mt-4">
                            <button class="btn btn-dark">Simpan Survei</button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>  
@endsection

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
    $('#survey_province').change(function () {
    var id = $(this).val();
    $('#survey_city').html('<option>Loading...</option>');
    $('#survey_district').html('<option value="">-- Pilih kecamatan --</option>');
    $('#survey_sub_district').html('<option value="">-- Pilih kelurahan --</option>');

    if (id) {
    $.get('/api/cities/' + id, function (data) {
    $('#survey_city').empty().append('<option value="">-- Pilih city --</option>');
    $.each(data, function (i, city) {
        $('#survey_city').append('<option value="' + city.id + '">' + city.name + '</option>');
            });
        });
        }
    });

    $('#survey_city').change(function () {
    var id = $(this).val();
    $('#survey_district').html('<option>Loading...</option>');
    $('#survey_sub_district').html('<option value="">-- Pilih kelurahan --</option>');

    if (id) {
        $.get('/api/districts/' + id, function (data) {
            $('#survey_district').empty().append('<option value="">-- Pilih kecamatan --</option>');
            $.each(data, function (i, district) {
                $('#survey_district').append('<option value="' + district.id + '">' + district.name + '</option>');
                    });
                });
            }
        });

    $('#survey_district').change(function () {
    var id = $(this).val();
    $('#survey_sub_district').html('<option>Loading...</option>');

        if (id) {
            $.get('/api/sub_districts/' + id, function (data) {
                $('#survey_sub_district').empty().append('<option value="">-- Pilih kelurahan --</option>');
                $.each(data, function (i, sub_district) {
                    $('#survey_sub_district').append('<option value="' + sub_district.id + '">' + sub_district.name + '</option>');
                });
            });
        }
    });

    $('#survey_sub_district').change(function () {
    var id = $(this).val();
    $('#survey_postal_code').html('<option>Loading...</option>');

    if (id) {
        $.get('/api/postal_codes/' + id, function (data) {
            $('#survey_postal_code').empty().append('<option value="">-- Pilih kode pos --</option>');
            $.each(data, function (i, postal_code) {
                $('#survey_postal_code').append('<option value="' + postal_code.id + '">' + postal_code.postal_code + '</option>');
            });
        });
        }
    });
</script>
                                        <script>
                                            $('#province').change(function () {
                                            var id = $(this).val();
                                            $('#city').html('<option>Loading...</option>');
                                            $('#district').html('<option value="">-- Pilih kecamatan --</option>');
                                            $('#sub_district').html('<option value="">-- Pilih kelurahan --</option>');

                                            if (id) {
                                            $.get('/api/cities/' + id, function (data) {
                                            $('#city').empty().append('<option value="">-- Pilih city --</option>');
                                            $.each(data, function (i, city) {
                                                $('#city').append('<option value="' + city.id + '">' + city.name + '</option>');
                                                    });
                                                });
                                                }
                                            });

                                            $('#city').change(function () {
                                            var id = $(this).val();
                                            $('#district').html('<option>Loading...</option>');
                                            $('#sub_district').html('<option value="">-- Pilih kelurahan --</option>');

                                            if (id) {
                                                $.get('/api/districts/' + id, function (data) {
                                                    $('#district').empty().append('<option value="">-- Pilih kecamatan --</option>');
                                                    $.each(data, function (i, district) {
                                                        $('#district').append('<option value="' + district.id + '">' + district.name + '</option>');
                                                            });
                                                        });
                                                    }
                                                });

                                            $('#district').change(function () {
                                            var id = $(this).val();
                                            $('#sub_district').html('<option>Loading...</option>');

                                                if (id) {
                                                    $.get('/api/sub_districts/' + id, function (data) {
                                                        $('#sub_district').empty().append('<option value="">-- Pilih kelurahan --</option>');
                                                        $.each(data, function (i, sub_district) {
                                                            $('#sub_district').append('<option value="' + sub_district.id + '">' + sub_district.name + '</option>');
                                                        });
                                                    });
                                                }
                                            });

                                            $('#sub_district').change(function () {
                                            var id = $(this).val();
                                            $('#postal_code').html('<option>Loading...</option>');

                                            if (id) {
                                                $.get('/api/postal_codes/' + id, function (data) {
                                                    $('#postal_code').empty().append('<option value="">-- Pilih kode pos --</option>');
                                                    $.each(data, function (i, postal_code) {
                                                        $('#postal_code').append('<option value="' + postal_code.id + '">' + postal_code.postal_code + '</option>');
                                                    });
                                                });
                                              }
                                         });
                                        </script>

<script>
    $(document).ready(function () {
    // 🟢 Ambil nilai lama (old) dari Blade
    let oldProvince  = "{{ old('province_id') }}";
    let oldCity      = "{{ old('city_id') }}";
    let oldDistrict  = "{{ old('district_id') }}";
    let oldSub       = "{{ old('sub_district_id') }}";
    let oldPostal    = "{{ old('postal_code_id') }}";

    if (oldProvince) {
    setTimeout(() => {
        loadCities(oldProvince, oldCity, function () {
            if (oldCity) loadDistricts(oldCity, oldDistrict, function () {
                if (oldDistrict) loadSubDistricts(oldDistrict, oldSub, function () {
                    if (oldSub) loadPostalCodes(oldSub, oldPostal);
                });
            });
        });
    }, 200); // tunggu 200ms
}


    console.log({
    oldProvince,
    oldCity,
    oldDistrict,
    oldSub,
    oldPostal
});


    // 🔹 Event saat provinsi berubah
    $('#province').on('change', function () {
        loadCities(this.value);
    });

    // 🔹 Event saat kota berubah
    $('#city').on('change', function () {
        loadDistricts(this.value);
    });

    // 🔹 Event saat kecamatan berubah
    $('#district').on('change', function () {
        loadSubDistricts(this.value);
    });

    // 🔹 Event saat kelurahan berubah
    $('#sub_district').on('change', function () {
        loadPostalCodes(this.value);
    });

    // ==============================
// Fungsi AJAX versi sesuai route kamu
// ==============================
function loadCities(provinceId, selected = null, callback = null) {
    if (!provinceId) return;
    $.get(`/api/cities/${provinceId}`, function (data) {
        $('#city').empty().append('<option value="">-- Pilih Kota --</option>');
        $.each(data, function (i, city) {
            $('#city').append(
                `<option value="${city.id}" ${selected == city.id ? 'selected' : ''}>${city.name}</option>`
            );
        });
        if (callback) callback();
    });
}

function loadDistricts(cityId, selected = null, callback = null) {
    if (!cityId) return;
    $.get(`/api/districts/${cityId}`, function (data) {
        $('#district').empty().append('<option value="">-- Pilih Kecamatan --</option>');
        $.each(data, function (i, district) {
            $('#district').append(
                `<option value="${district.id}" ${selected == district.id ? 'selected' : ''}>${district.name}</option>`
            );
        });
        if (callback) callback();
    });
}

function loadSubDistricts(districtId, selected = null, callback = null) {
    if (!districtId) return;
    $.get(`/api/sub_districts/${districtId}`, function (data) {
        $('#sub_district').empty().append('<option value="">-- Pilih Kelurahan --</option>');
        $.each(data, function (i, sub) {
            $('#sub_district').append(
                `<option value="${sub.id}" ${selected == sub.id ? 'selected' : ''}>${sub.name}</option>`
            );
        });
        if (callback) callback();
    });
}

function loadPostalCodes(subId, selected = null) {
    if (!subId) return;
    $.get(`/api/postal_codes/${subId}`, function (data) {
        $('#postal_code').empty().append('<option value="">-- Pilih Kode Pos --</option>');
        $.each(data, function (i, code) {
            $('#postal_code').append(
                `<option value="${code.id}" ${selected == code.id ? 'selected' : ''}>${code.postal_code}</option>`
            );
        });
    });
}

});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // add row
    const addBtn = document.getElementById('add-row');
    const table = document.querySelector('#items-table tbody');

    function renumber() {
        table.querySelectorAll('tr').forEach((tr, idx) => {
            tr.querySelector('.row-no').textContent = idx + 1;
            // update input names
            tr.querySelectorAll('textarea, input[type="text"]').forEach(el => {
                if (el.name.includes('items')) {
                    const field = el.name.split(']')[1]; // like [description] or [remark]
                    el.name = `items[${idx}]${field}`;
                }
            });
        });
    }

    addBtn.addEventListener('click', function () {
        const idx = table.querySelectorAll('tr').length;
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="row-no text-center">${idx + 1}</td>
            <td><textarea name="items[${idx}][description]" class="form-control" rows="2"></textarea></td>
            <td><textarea name="items[${idx}][remark]" class="form-control" rows="2"></textarea></td>
            <td><button type="button" class="btn btn-sm btn-danger remove-row">-</button></td>
        `;
        table.appendChild(tr);
    });

    // remove row
    table.addEventListener('click', function (e) {
        if (e.target.matches('.remove-row')) {
            const tr = e.target.closest('tr');
            tr.remove();
            renumber();
        }
    });

    // signature previews
    function readPreview(input, previewId) {
        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = (e) => {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.style.display = 'none';
        }
    }

    document.getElementById('doc-preview').addEventListener('change', function () {
        readPreview(this, 'doc-preview');
    });

    // document.getElementById('client-sign').addEventListener('change', function () {
    //     readPreview(this, 'client-preview');
    // });

});
</script>

<script>
document.getElementById('btnToSurvey').addEventListener('click', function() {
    document.getElementById('section-konsultasi').style.display = 'none';
    document.getElementById('section-survey').style.display = 'block';

    // Scroll ke atas biar user lihat bagian survei
    window.scrollTo({ top: 0, behavior: 'smooth' });
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
<script>
$(document).ready(function() {
    $('#same_address').on('change', function() {
        if ($(this).is(':checked')) {

            // Ambil data dari wilayah user
            let province = $('#province').val();
            let city = $('#city').val();
            let district = $('#district').val();
            let subdistrict = $('#sub_district').val();
            let postal = $('#postal_code').val();

            // Ambil data teks (untuk select2 append manual)
            let provinceText = $('#province option:selected').text();
            let cityText = $('#city option:selected').text();
            let districtText = $('#district option:selected').text();
            let subdistrictText = $('#sub_district option:selected').text();
            let postalText = $('#postal_code option:selected').text();

            // 1️⃣ Province
            $('#survey_province').append(new Option(provinceText, province, true, true)).trigger('change.select2');

            // 2️⃣ Tunggu AJAX kota selesai, lalu isi City
            setTimeout(() => {
                $('#survey_city').append(new Option(cityText, city, true, true)).trigger('change.select2');
            }, 400);

            // 3️⃣ Isi District
            setTimeout(() => {
                $('#survey_district').append(new Option(districtText, district, true, true)).trigger('change.select2');
            }, 800);

            // 4️⃣ Isi SubDistrict
            setTimeout(() => {
                $('#survey_sub_district').append(new Option(subdistrictText, subdistrict, true, true)).trigger('change.select2');
            }, 1200);

            // 5️⃣ Isi Postal Code
            setTimeout(() => {
                $('#survey_postal_code').append(new Option(postalText, postal, true, true)).trigger('change.select2');
            }, 1500);

            // Copy alamat, nama, dan telepon
            $('[name="project_location"]').val($('[name="survey_location"]').val());
            $('[name="shipping_name"]').val($('[name="fullname"]').val());
            $('[name="shipping_phone"]').val($('[name="phone"]').val());

            // Disable field
            $('#survey_province, #survey_city, #survey_district, #survey_sub_district, #survey_postal_code, [name="survey_location"], [name="shipping_name"], [name="shipping_phone"]')
                .attr('readonly', true)
                .addClass('bg-light text-muted');

        } else {
            // Aktifkan kembali jika user batal
            $('#survey_province, #survey_city, #survey_district, #survey_sub_district, #survey_postal_code, [name="survey_location"], [name="shipping_name"], [name="shipping_phone"]')
                .attr('readonly', false)
                .removeClass('bg-light text-muted');
        }
    });
});
</script>
<script>
document.getElementById("btnToSurvey").addEventListener("click", function () {

    let form = document.getElementById("consultationForm");
    let url = form.action;

    let formData = new FormData(form);
    formData.append("go_to_survey", 1);

    fetch(url, {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(res => {

        if(res.success) {

            // 1. Sembunyikan form konsultasi
            document.getElementById("section-konsultasi").style.display = "none";

            // 2. Tampilkan detail konsultasi yang baru disimpan
            document.getElementById("section-detail-konsultasi").style.display = "block";
            document.getElementById("section-detail-konsultasi").innerHTML = res.detail_html;

            // 3. Tampilkan form survei
            document.getElementById("section-survey").style.display = "block";

        } else {
            alert("Gagal menyimpan konsultasi!");
        }
    })
    .catch(err => console.error(err));
});
</script>



                                    @endpush