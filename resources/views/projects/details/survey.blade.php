@if(isset($survey))
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header fw-bold">
        Detail Survei Lapangan
    </div>

    <div class="card-body">
        <div class="row g-4">

            <div class="col-md-4">
                <label class="fw-semibold">Tanggal Survei</label>
                <input type="text" class="form-control"
                       value="{{ $survey->survey_date }}" readonly>
            </div>

            <div class="col-md-4">
                <label class="fw-semibold">Waktu Survei</label>
                <input type="text" class="form-control"
                       value="{{ $survey->survey_time }}" readonly>
            </div>

            <div class="col-md-4">
                <label class="fw-semibold">Petugas Survei</label>

                <ul class="list-group">
                    @foreach($survey->employees as $emp)
                    <li class="list-group-item">
                        {{ $emp->display_name }}
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- ========================= --}}
            {{-- ALAMAT SURVEI --}}
            {{-- ========================= --}}
            <div class="col-12 mt-3">
                <label class="fw-semibold">Alamat Lengkap Survei</label>
                <textarea class="form-control" rows="3" readonly>{{ $survey->survey_location }}</textarea>
            </div>

            <div class="col-md-6">
                <label class="fw-semibold">Provinsi</label>
                <input type="text" class="form-control"
                       value="{{ $survey->province->name ?? '-' }}" readonly>
            </div>

            <div class="col-md-6">
                <label class="fw-semibold">Kabupaten/Kota</label>
                <input type="text" class="form-control"
                       value="{{ $survey->city->name ?? '-' }}" readonly>
            </div>

            <div class="col-md-5">
                <label class="fw-semibold">Kecamatan</label>
                <input type="text" class="form-control"
                       value="{{ $survey->district->name ?? '-' }}" readonly>
            </div>

            <div class="col-md-5">
                <label class="fw-semibold">Kelurahan</label>
                <input type="text" class="form-control"
                       value="{{ $survey->subDistrict->name ?? '-' }}" readonly>
            </div>

            <div class="col-md-2">
                <label class="fw-semibold">Kode Pos</label>
                <input type="text" class="form-control"
                       value="{{ $survey->postalCode->postal_code ?? '-' }}" readonly>
            </div>

            <div class="col-md-12 mt-3">
                <label class="fw-semibold">Catatan Survei</label>
                <textarea class="form-control" rows="3" readonly>{{ $survey->survey_notes }}</textarea>
            </div>

        </div>
    </div>
</div>
@endif
