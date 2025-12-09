@if(isset($project))
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header fw-bold">
        Informasi Proyek
    </div>
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
                <textarea id="project_location" class="form-control" rows="3" readonly>{{ $project->project_location }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="fw-semibold">Provinsi</label>
                <input id="province" type="text" class="form-control" value="{{ $project->province->name }}" readonly>
            </div>
            <div class="col-md-6">
                <label class="fw-semibold">Kabupaten/Kota</label>
                <input id="city" type="text" class="form-control" value="{{ $project->city->name }}" readonly>
            </div>
            <div class="col-md-5">
                <label class="fw-semibold">Kecamatan</label>
                <input id="district" type="text" class="form-control" value="{{ $project->district->name }}" readonly>
            </div>
            <div class="col-md-5">
                <label class="fw-semibold">Kelurahan</label>
                <input id="sub_district" type="text" class="form-control" value="{{ $project->subDistrict->name }}" readonly>
            </div>
            <div class="col-md-2">
                <label class="fw-semibold">Kode Pos</label>
                <input id="postal_code" type="text" class="form-control" value="{{ $project->postalCode->postal_code }}" readonly>
            </div>

        </div>
    </div>
    
</div>
@endif
