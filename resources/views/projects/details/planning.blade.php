@php
    // Level_order 2 = rencana survei
    $planning = $project->planning;
    
    $planningLevel = $project->levels->firstWhere('level_order', 2);

    $planningEmployees = $planningLevel ? $planningLevel->employees : collect();
    $surveyInvoice = $project?->latestSurveyInvoice();
@endphp

@if(isset($planning))
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header fw-bold">
        Detail Rencana Survei Lapangan
    </div>

    <div class="card-body">
        <div class="row g-4">

            <div class="col-md-3">
                <label class="fw-semibold"> Rencana Tanggal Survei</label>
                <input type="text" class="form-control"
                    value="{{ \Carbon\Carbon::parse($planning->planning_date)->format('d/m/Y') }}" readonly>
            </div>

            <div class="col-md-3">
                <label class="fw-semibold"> Rencana Waktu Survei</label>
                <input type="text" class="form-control"
                       value="{{ $planning->planning_time }}" readonly>
            </div>

<div class="col-md-4">
    <label class="fw-semibold">Rencana Petugas Survei</label>

    <div class="d-flex flex-wrap gap-2 mt-1">
        @forelse($planningEmployees as $emp)
            <span class="badge bg-dark text-white px-3 py-2">
                {{ $emp->display_name }}
            </span>
        @empty
            <span class="text-muted">Tidak ada petugas survei</span>
        @endforelse
    </div>
</div>
            <div class="col-12 mt-3">
                <label class="fw-semibold">Alamat Lengkap Survei</label>
                <textarea class="form-control" rows="3" readonly>{{ $planning->survey_address }}</textarea>
            </div>

            <div class="col-md-6">
                <label class="fw-semibold">Provinsi</label>
                <input type="text" class="form-control"
                       value="{{ $planning->province->name ?? '-' }}" readonly>
            </div>

            <div class="col-md-6">
                <label class="fw-semibold">Kabupaten/Kota</label>
                <input type="text" class="form-control"
                       value="{{ $planning->city->name ?? '-' }}" readonly>
            </div>

            <div class="col-md-5">
                <label class="fw-semibold">Kecamatan</label>
                <input type="text" class="form-control"
                       value="{{ $planning->district->name ?? '-' }}" readonly>
            </div>

            <div class="col-md-5">
                <label class="fw-semibold">Kelurahan</label>
                <input type="text" class="form-control"
                       value="{{ $planning->subDistrict->name ?? '-' }}" readonly>
            </div>

            <div class="col-md-2">
                <label class="fw-semibold">Kode Pos</label>
                <input type="text" class="form-control"
                       value="{{ $planning->postalCode->postal_code ?? '-' }}" readonly>
            </div>

            <div class="col-md-12 mt-3">
                <label class="fw-semibold">Catatan Rencana Survei</label>
                <textarea class="form-control" rows="3" readonly>{{ $planning->planning_notes }}</textarea>
            </div>

            <div class="section-block mb-5">
                <h3 class="fw-semibold mb-3 mt-3 border-bottom pb-2">
                    Biaya Survei
                </h3>

                <div class="col-md-4">
                    <label class="fw-semibold">Biaya Survei</label>
                    @if($surveyInvoice)
                    <input type="text" class="form-control"
                        value="Rp {{ number_format($surveyInvoice->amount, 0, ',', ',') }}" readonly>
                    @else
                    <input type="text" class="form-control"
                        value="Belum ditentukan" readonly>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endif
