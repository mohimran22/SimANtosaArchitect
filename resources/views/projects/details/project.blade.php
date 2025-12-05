@if(isset($project))
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header fw-bold">
        Informasi Proyek
    </div>

    <div class="card-body">

        <div class="row g-4">

            {{-- Nama Proyek --}}
            <div class="col-md-4">
                <label class="fw-semibold">Nama Proyek</label>
                <input type="text" class="form-control"
                       value="{{ $project->project_name }}" readonly>
            </div>

            {{-- Jenis Proyek --}}
            <div class="col-md-2">
                <label class="fw-semibold">Jenis Proyek</label>
                <input type="text" class="form-control"
                       value="{{ $project->project_type == 1 ? 'Desain' : 'Build' }}"
                       readonly>
            </div>

            {{-- Tanggal Mulai --}}
            <div class="col-md-3">
                <label class="fw-semibold">Tanggal Mulai</label>
                <input type="text" class="form-control"
                       value="{{ $project->start_date }}" readonly>
            </div>

            {{-- Status Proyek --}}
            <div class="col-md-3">
                <label class="fw-semibold">Status Proyek</label>
                <input type="text" class="form-control"
                       value="{{ $projectStatus[$project->project_status] ?? '-' }}"
                       readonly>
            </div>


            {{-- Customer --}}
            <div class="col-md-4">
                <label class="fw-semibold">Customer</label>
                <input type="text" class="form-control"
                       value="{{ $project->customer->display_name ?? '-' }}" readonly>
            </div>

            {{-- Karyawan --}}
            <div class="col-md-4">
                <label class="fw-semibold">Karyawan</label>
                <input type="text" class="form-control"
                       value="{{ $project->employee->display_name ?? '-' }}" readonly>
            </div>

            {{-- Affiliator --}}
            <div class="col-md-4">
                <label class="fw-semibold">Affiliator</label>
                <input type="text" class="form-control"
                       value="{{ $project->affiliator->display_name ?? '-' }}" readonly>
            </div>


            {{-- Alamat --}}
            <div class="col-12 mt-3">
                <label class="fw-semibold">Alamat Lokasi</label>
                <textarea class="form-control" rows="3" readonly>
{{ $project->project_location }}
                </textarea>
            </div>

            {{-- Provinsi --}}
            <div class="col-md-6">
                <label class="fw-semibold">Provinsi</label>
                <input type="text" class="form-control"
                       value="{{ $project->province->name ?? '-' }}" readonly>
            </div>

            {{-- Kota --}}
            <div class="col-md-6">
                <label class="fw-semibold">Kabupaten/Kota</label>
                <input type="text" class="form-control"
                       value="{{ $project->city->name ?? '-' }}" readonly>
            </div>

            {{-- Kecamatan --}}
            <div class="col-md-5">
                <label class="fw-semibold">Kecamatan</label>
                <input type="text" class="form-control"
                       value="{{ $project->district->name ?? '-' }}" readonly>
            </div>

            {{-- Kelurahan --}}
            <div class="col-md-5">
                <label class="fw-semibold">Kelurahan</label>
                <input type="text" class="form-control"
                       value="{{ $project->subDistrict->name ?? '-' }}" readonly>
            </div>

            {{-- Kode Pos --}}
            <div class="col-md-2">
                <label class="fw-semibold">Kode Pos</label>
                <input type="text" class="form-control"
                       value="{{ $project->postalCode->postal_code ?? '-' }}" readonly>
            </div>

        </div>

    </div>
</div>
@endif
