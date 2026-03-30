@extends('tablar::page')

@section('content')

    <div class="page-header d-print-none mb-4">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col d-flex align-items-center">
                    <a href="{{ route('projects.index') }}" class="btn btn-dark d-flex align-items-center">
                        <i class="ti ti-arrow-left"></i>
                    </a>      
                        <h2 class="page-title mb-0">Tambah Proyek</h2> 
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            @include('projects.components.timeline-horizontal')
                @php
                    $rab = $project?->rab;
                    $planning = $project?->planning;
                    $disableEdit = $surveyWaiting;
                    use Illuminate\Support\Facades\Storage;
                    $final = $project?->finalDocument;
                    $ReadOnly = !$canEdit;
                @endphp
            @if($activeStep == 1)
            <div id="project" class="step-section">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body px-5 py-4">
                        <h3 class="mb-4 fw-bold">Buat Proyek Baru</h3>
                        @include('projects.steps.create-project')
                    </div>
                </div>
            </div>
            @endif
            @if($activeStep >= 2)
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body px-5 py-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="fw-bold m-0">Proyek</h3>
                            @can('ubah data proyek')
                            <div class="btn-group">
                                <button type="button" id="btn-edit-project"
                                    class="btn btn-sm btn-dark me-2"
                                    title="Edit Data">
                                    <i class="ti ti-edit"></i>
                                </button>
                                {{-- 
                                <a href="{{ route('projects.pdf', $project->id) }}"
                                class="btn btn-sm btn-dark"
                                target="_blank"
                                title="Download PDF">
                                    <i class="ti ti-download"></i>
                                </a> --}}
                            </div>
                            @endcan
                        </div>
                        <div id="project-view">
                            @include('projects.details.project')
                        </div>
                        <div id="project-edit" style="display:none;">
                            @include('projects.edit.project-form')    
                        </div>
                    </div>
                </div>

                @if($activeStep == 2)
                <div id="form-konsultasi" class="step-section">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body px-5 py-4">
                            <h3 class="mb-4 fw-bold">1. Form Konsultasi</h3>
                            @include('projects.steps.consultation-form')
                        </div>
                    </div>
                </div>  
                @endif
            @endif
            @if($activeStep >= 3)   
            <div id="detail-konsultasi" class="step-section">      
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body px-5 py-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="fw-bold m-0">1. Tahap Konsultasi</h3>
                            @can('ubah data proyek')
                            <div class="btn-group">
                                <button type="button" id="btn-edit-consultation" 
                                    class="btn btn-sm btn-dark me-2"
                                    title="Edit Data">
                                    <i class="ti ti-edit"></i>
                                </button>

                                {{-- <a href="{{ route('projects.pdf', $project->id) }}"
                                    class="btn btn-sm btn-dark"
                                    target="_blank"
                                    title="Download PDF">
                                    <i class="ti ti-download"></i>
                                </a> --}}
                            </div>
                            @endcan
                        </div>
                        <div id="consultation-view">
                            @include('projects.details.consultation')
                        </div>
                        <div id="consultation-edit" style="display:none;">
                            @include('projects.edit.consultation-form')    
                        </div>
                    </div>
                </div>
            </div>
            <div id="planning" class="step-section">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body px-5 py-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="fw-bold mb-0">2. Rencana Survei</h3>
                            @if($project->planning)
                                @can('ubah data proyek')
                                <button type="button"
                                    id="btn-edit-planning"
                                    class="btn btn-sm btn-dark {{ $disableEdit ? 'disabled' : '' }}"
                                    {{ $disableEdit ? 'disabled' : '' }}
                                    title="{{ $disableEdit ? 'Menunggu persetujuan biaya survei' : 'Edit Data' }}">
                                    <i class="ti ti-edit"></i>
                                </button>
                                @endcan
                            @endif
                        </div>

                        @if(!$project->planning)
                            
                            @include('projects.steps.planning-form')
                        @else
                            
                            <div id="planning-view">
                                @include('projects.details.planning')
                            </div>
                            @if(!$surveyWaiting && !$surveyApproved)
                                <div id="planning-edit" style="display:none;">
                                    @include('projects.edit.planning-form')
                                </div>
                            @endif
                        @endif

                        @if($surveyInvoice && $surveyInvoice->status === 'rejected')
                            <div class="alert alert-danger mt-4">
                                Biaya survei ditolak:<br>{{ $surveyInvoice->reject_note }}     
                            </div>
                        @elseif($surveyInvoice && $surveyInvoice->status === 'waiting_approval' && $surveyInvoice->amount > 0)
                            <div class="alert alert-warning mt-4">
                                Menunggu persetujuan biaya survei dari customer (via PDF)<br>
                                Data rencana survei tidak dapat diubah selama proses persetujuan.
                            </div>
                        @endif

                    </div>
                </div>
            </div>
            @endif
            <div id="survei" class="step-section">
                @if(
                    $activeStep == 4 &&
                    $planning &&
                    (
                        $project->levels->firstWhere('level_order', 3)?->is_started
                    )
                )
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body px-5 py-4">
                            <h3 class="mb-3 fw-bold">3. Form Survei Lapangan</h3>
                            @include('projects.steps.survey-form')
                        </div>
                    </div>
                @endif
                @if($activeStep >= 5)
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body px-5 py-4">       
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h3 class="fw-bold m-0">3. Survei</h3>
                                @can('ubah data proyek')
                                <div class="btn-group">
                                    <button type="button" id="btn-edit-survey"
                                        class="btn btn-sm btn-dark me-2"
                                        title="Edit Data">
                                        <i class="ti ti-edit"></i>
                                    </button>
                                </div>
                                @endcan
                            </div>
                            <div id="survey-view">
                                @include('projects.details.survey')
                            </div>
                            <div id="survey-edit" style="display:none;">
                                @include('projects.edit.survey-form')
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div id="offer" class="step-section">
                @if($activeStep == 5)
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body px-5 py-4">
                            @if($project->project_type == 1)
                                <h3 class="mb-3 fw-bold">4. Form Penawaran Jasa Desain</h3>
                                @include('projects.steps.desain-form')
                            @elseif($project->project_type == 2)
                                <h3 class="mb-3 fw-bold">4. Form Penawaran Pembuatan RAB</h3>
                                @include('projects.steps.rab-form')
                            @elseif($project->project_type == 3)
                                <h3 class="mb-3 fw-bold">4. Penawaran Jasa Build</h3>
                                @include('projects.steps.build-form')
                            @endif
                        </div>
                    </div>
                @endif
                @if($project && $project->offer)
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body px-5 py-4">

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h3 class="mb-3 fw-bold">
                                    @if($project->project_type == 1)
                                        4. Penawaran Jasa Desain
                                    @elseif($project->project_type == 2)
                                        4. Penawaran Jasa RAB
                                    @elseif($project->project_type == 3)
                                        4. Penawaran Jasa Build
                                    @endif
                                </h3>
                                @can('ubah data proyek')
                                <div class="btn-group">
                                    <button type="button"
                                            class="btn btn-sm btn-dark me-2 btn-toggle-offer"
                                            data-view="offer-view"
                                            data-edit="offer-edit"
                                            title="Edit Data">
                                        <i class="ti ti-edit"></i>
                                    </button>
                                </div>
                                @endcan
                            </div>
                            @if($project->offer)
                                <div id="offer-view">
                                    @if($project->project_type == 1)
                                        @include('projects.details.offer')
                                    @elseif($project->project_type == 2)
                                        @include('projects.details.raboffer')
                                    @elseif($project->project_type == 3)
                                        @include('projects.details.buildoffer')
                                    @endif
                                </div>
                            
                                <div id="offer-edit" style="display:none;">
                                    @if($project->project_type == 1)
                                        @include('projects.edit.offer-form')
                                    @elseif($project->project_type == 2)
                                        @include('projects.edit.raboffer-form')
                                    @elseif($project->project_type == 3)
                                        @include('projects.edit.buildoffer-form')
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>    
                @endif
            </div>
            @if($activeStep >= 6 && $project->offer && in_array($project->project_type, [1, 3]))
            <div id="kontrak" class="step-section">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body px-5 py-4">
                        <h3 class="mb-3 fw-bold">
                            5. Draft Kontrak Pelaksanaan Pekerjaan
                        </h3>
                        <div class="d-flex gap-2">

                            @if($project->project_type == 1)
                                <a href="{{ route('projects.contract.pdf', $project->id) }}"
                                class="btn btn-dark"
                                target="_blank">
                                    <i class="ti ti-download"></i>
                                    {{ $project->offer?->approved_at ? 'Download Kontrak Desain' : 'Download Draft Kontrak Desain' }}
                                </a>

                            @elseif($project->project_type == 3)
                                <a href="{{ route('projects.contract.buildpdf', $project->id) }}"
                                class="btn btn-dark"
                                target="_blank">
                                    <i class="ti ti-download"></i>
                                    {{ $project->offer?->approved_at ? 'Download Kontrak Build' : 'Download Draft Kontrak Build' }}
                                </a>
                            @endif

                            @if(!$project->offer?->approved_at)
                                    @if($project->project_type == 1)
                                        <form action="{{ route('projects.contract.approve', $project->id) }}"
                                            method="POST"
                                            class="approve-form">
                                            @csrf
                                            <button type="submit" class="btn btn-dark">
                                                <i class="ti ti-check"></i> Approve Kontrak
                                            </button>
                                        </form>
                                    @elseif($project->project_type == 3)
                                        <form action="{{ route('projects.contract.build.approve', $project->id) }}"
                                            method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-dark">
                                                <i class="ti ti-check"></i> Approve Kontrak Build
                                            </button>
                                        </form>
                                    @endif
                            @else
                                <span class="text-muted fst-italic d-flex align-items-center gap-1">
                                    <i class="ti ti-check"></i>
                                    Disetujui {{ $project->offer->approved_at->format('d M Y') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>    
            @endif
            @if(
                ($project?->project_type == 1 && $activeStep >= 7 && $project->offer->approved_at)
                ||
                ($project?->project_type == 2 && $activeStep >= 6)
                ||
                ($project?->project_type == 3 && $activeStep >= 7 && $project->offer->approved_at)
            )
            <div id="invoice" class="step-section">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body px-5 py-4">
                    <h3 class="mb-3 fw-bold">
                        @if($project->project_type == 1)
                            6. Invoice Pembayaran Desain (DP)
                        @elseif($project->project_type == 2)
                            5. Invoice Jasa Pembuatan RAB
                        @elseif($project->project_type == 3)
                            6. Invoice Pembayaran Tahap 1
                        @endif
                    </h3>
                        @php
                            $termin = $project->build_progress < 30 ? 1 :
                                    ($project->build_progress < 60 ? 2 :
                                    ($project->build_progress < 90 ? 3 : 4));
                            $invoice = $project->invoicebuilds()
                            ->where('termin', $termin)
                            ->whereNotNull('downloaded_at')
                            ->whereNull('approved_at')
                            ->first();
                        @endphp
                        <div class="d-flex gap-2">

                            @if($project->project_type == 1)
                                <a href="{{ route('projects.invoice.pdf', $project->id) }}"
                                class="btn btn-dark"
                                target="_blank">
                                    <i class="ti ti-download"></i>
                                    Download Invoice
                                </a>
                            @endif

                            @if($project->project_type == 2)
                                <a href="{{ route('projects.invoice.rab', $project->id) }}"
                                class="btn btn-dark"
                                target="_blank">
                                    <i class="ti ti-download"></i>
                                    Download Invoice
                                </a>
                            @endif

                            @if($project->project_type == 3)
                                <a href="{{ route('projects.invoice.build', [
                                    'project' => $project->id,
                                    'termin'  => $termin
                                ]) }}"
                                class="btn btn-dark"
                                target="_blank">
                                    <i class="ti ti-download"></i>
                                    Download Invoice Tahap {{ $termin }}
                                </a>
                            @endif
                            @if(
                                $invoiceDp?->invoice_dp_downloaded_at &&
                                !$invoiceDp?->invoice_dp_approved_at
                            )
                                <form action="{{ route('projects.invoice.approve', $project->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Lanjut ke tahap pengerjaan?')">
                                    @csrf
                                    <button class="btn btn-dark">
                                        <i class="ti ti-arrow-right"></i> Lanjut ke tahap berikutnya
                                    </button>
                                </form>
                            @endif
                            @if($invoiceRab && $invoiceRab->downloaded_at && !$invoiceRab->approved_at)
                                <form action="{{ route('projects.invoice.rab.approve', $project->id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-dark">
                                        <i class="ti ti-arrow-right"></i>
                                        Lanjut ke Tahap Berikutnya
                                    </button>
                                </form>
                            @endif
                            @if($invoice && $termin < 4)
                            <form action="{{ route('projects.invoice.build.approve', [$project->id, $invoice->id]) }}" method="POST">
                                @csrf
                                <button class="btn btn-dark">
                                    <i class="ti ti-arrow-right"></i>
                                    Lanjut ke Tahap Berikutnya
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if(
                ($project?->project_type == 1 && $activeStep >= 8 && $project->offer->approved_at)
                ||
                ($project?->project_type == 2 && $activeStep == 7)
                ||
                ($project?->project_type == 3 && $activeStep >= 8 && $project->offer->approved_at)
            )
            <div id="work" class="step-section">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body px-5 py-4">
                        @if($project->project_type == 1)
                            <h3 class="mb-3 fw-bold">7. Form Pengerjaan</h3>
                            @include('projects.steps.work-process')
                        @elseif($project->project_type == 2)
                            <h3 class="mb-3 fw-bold">6. Form Pembuatan RAB</h3>
                            @include('projects.steps.rab-process')
                        @elseif($project->project_type == 3)
                            <h3 class="mb-3 fw-bold">7. Form Kemajuan Pekerjaan</h3>
                            @include('projects.steps.build-process')
                        @endif
                    </div>
                </div>
            </div>
            @endif
            {{-- @if($activeStep >= 9) --}}
            @if(
                ($project?->project_type == 1 && $activeStep >= 9)
                ||
                ($project?->project_type == 2 && $activeStep >= 9)
            )
            <div id="invoice-final" class="step-section">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body px-5 py-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="mb-3 fw-bold">
                                {{ $project->project_type == 1 ? '8. Invoice Pelunasan Desain' : '6. Rencana Anggaran Biaya' }}
                            </h3>
                            @if($project->project_type == 2)
                            @can('ubah data proyek')
                            <div class="btn-group">
                                <button type="button" id="btn-edit-rab"
                                    class="btn btn-sm btn-dark me-2"
                                    title="Edit Data">
                                    <i class="ti ti-edit"></i>
                                </button>
                                    {{-- <a href="{{ route('projects.rab.pdf', $project->id) }}"
                                        class="btn btn-sm btn-dark"
                                        target="_blank"
                                        title="Download PDF">
                                            <i class="ti ti-download"></i>
                                    </a> --}}
                            </div>
                            @endcan
                            @endif
                        </div>
                        <div id="rab-view">
                            @if($project->project_type == 2)
                                @include('projects.details.rab-process')
                            @endif
                        </div>

                        <div id="rab-edit" style="display:none;">
                            @if($project->project_type == 2)
                                @include('projects.edit.rab-process')
                            @endif
                        </div>
                        @php
                            $invoiceFinal = $project->invoices
                                ->where('invoice_type', 'final')
                                ->first();
                        @endphp
                        @if($project->project_type == 1)
                        <div class="d-flex gap-2">
                            <a href="{{ route('projects.invoice.final', $project->id) }}"
                            class="btn btn-dark"
                            target="_blank">
                                <i class="ti ti-download"></i> Download Invoice Pelunasan
                            </a>

                            @if(
                                $invoiceFinal &&
                                $invoiceFinal->downloaded_at &&
                                !$invoiceFinal->approved_at
                            )
                                <form action="{{ route('projects.invoice.final.approve', $project->id) }}"
                                    method="POST"
                                    class="approve-form"
                                    data-title="Lanjut ke Tahap Berikutnya?"
                                    data-text="Invoice Pelunasan akan disetujui dan proses berlanjut.">
                                    @csrf
                                    <button type="submit" class="btn btn-dark">
                                        <i class="ti ti-check"></i> Konfirmasi Pelunasan
                                    </button>
                                </form>
                            @endif

                            @if($invoiceFinal?->approved_at)
                                <span class="text-muted fst-italic d-flex align-items-center gap-1">
                                    <i class="ti ti-check"></i>
                                    Pelunasan Selesai
                                </span>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
            
            @if(
                $project?->levels->firstWhere('level_order', 9)?->is_started
                && !$ReadOnly
            )
            <div id="final" class="step-section">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body px-5 py-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="mb-4 fw-bold">9. Hasil Proyek</h3>
                            @if($project->finalDocument)
                                @can('ubah data proyek')
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-dark"
                                            onclick="document.getElementById('form-reupload').classList.toggle('d-none')">
                                        <i class="ti ti-edit"></i>
                                    </button>
                                </div>
                                @endcan
                            @endif
                        </div>
                        @include('projects.steps.upload-final')
                        @if($final)
                        <div class="alert alert-success mt-4 d-flex align-items-center justify-content-between">
                            <div>
                                <i class="ti ti-check"></i>
                                Hasil proyek sudah diupload
                            </div>
                        </div>
                            <div class="d-flex gap-2">
                                {{-- Download --}}
                                <a href="{{ Storage::url($final->document_path) }}"
                                class="btn btn-dark"
                                target="_blank">
                                    <i class="ti ti-download"></i>Download File
                                </a>
                            </div>
                        <form id="form-reupload"
                            action="{{ route('projects.finals.store', $project->id) }}"
                            method="POST"
                            enctype="multipart/form-data"
                            class="d-none mt-3">

                            @csrf

                            <div class="col-md-6">
                                <label class="required">Upload Ulang File</label>
                                <input type="file"
                                    name="document"
                                    class="form-control"
                                    accept=".zip,.rar,.pdf"
                                    required>
                            </div>
                            <div class="mt-3">
                                <button class="btn btn-sm btn-dark">
                                    <i class="ti ti-upload"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endif
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
function initLocationCascade(config) {
    const {
        prefix = '',
        oldProvince,
        oldCity,
        oldDistrict,
        oldSub,
        oldPostal
    } = config;

    const $province = $('#' + prefix + 'province');
    const $city = $('#' + prefix + 'city');
    const $district = $('#' + prefix + 'district');
    const $sub = $('#' + prefix + 'sub_district');
    const $postal = $('#' + prefix + 'postal_code');

    function loadCities(provinceId, selected = null, callback = null) {
        if (!provinceId) return;
        $.get(`/api/cities/${provinceId}`, function (data) {
            $city.empty().append('<option value="">-- Pilih Kota --</option>');
            data.forEach(item => {
                $city.append(`<option value="${item.id}" ${selected == item.id ? 'selected' : ''}>${item.name}</option>`);
            });
            $city.trigger('change.select2');
            if (callback) callback();
        });
    }

    function loadDistricts(cityId, selected = null, callback = null) {
        if (!cityId) return;
        $.get(`/api/districts/${cityId}`, function (data) {
            $district.empty().append('<option value="">-- Pilih Kecamatan --</option>');
            data.forEach(item => {
                $district.append(`<option value="${item.id}" ${selected == item.id ? 'selected' : ''}>${item.name}</option>`);
            });
            $district.trigger('change.select2');
            if (callback) callback();
        });
    }

    function loadSubDistricts(districtId, selected = null, callback = null) {
        if (!districtId) return;
        $.get(`/api/sub_districts/${districtId}`, function (data) {
            $sub.empty().append('<option value="">-- Pilih Kelurahan --</option>');
            data.forEach(item => {
                $sub.append(`<option value="${item.id}" ${selected == item.id ? 'selected' : ''}>${item.name}</option>`);
            });
            $sub.trigger('change.select2');
            if (callback) callback();
        });
    }

    function loadPostalCodes(subId, selected = null) {
        if (!subId) return;
        $.get(`/api/postal_codes/${subId}`, function (data) {
            $postal.empty().append('<option value="">-- Pilih Kode Pos --</option>');
            data.forEach(item => {
                $postal.append(`<option value="${item.id}" ${selected == item.id ? 'selected' : ''}>${item.postal_code}</option>`);
            });
            $postal.trigger('change.select2');
        });
    }
    $province.on('change', function () {
        loadCities(this.value);
    });

    $city.on('change', function () {
        loadDistricts(this.value);
    });

    $district.on('change', function () {
        loadSubDistricts(this.value);
    });

    $sub.on('change', function () {
        loadPostalCodes(this.value);
    });

    // Restore old()
    if (oldProvince) {
        loadCities(oldProvince, oldCity, () => {
            loadDistricts(oldCity, oldDistrict, () => {
                loadSubDistricts(oldDistrict, oldSub, () => {
                    loadPostalCodes(oldSub, oldPostal);
                });
            });
        });
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

            let provinceText = $('#province option:selected').text();
            let cityText = $('#city option:selected').text();
            let districtText = $('#district option:selected').text();
            let subdistrictText = $('#sub_district option:selected').text();
            let postalText = $('#postal_code option:selected').text();

            $('#survey_province').append(new Option(provinceText, province, true, true)).trigger('change.select2');

            setTimeout(() => {
                $('#survey_city').append(new Option(cityText, city, true, true)).trigger('change.select2');
            }, 400);

            setTimeout(() => {
                $('#survey_district').append(new Option(districtText, district, true, true)).trigger('change.select2');
            }, 800);

            setTimeout(() => {
                $('#survey_sub_district').append(new Option(subdistrictText, subdistrict, true, true)).trigger('change.select2');
            }, 1200);

            setTimeout(() => {
                $('#survey_postal_code').append(new Option(postalText, postal, true, true)).trigger('change.select2');
            }, 1500);

            // Copy alamat, nama, dan telepon
            $('[name="project_location"]').val($('[name="survey_address"]').val());
            $('[name="shipping_name"]').val($('[name="fullname"]').val());
            $('[name="shipping_phone"]').val($('[name="phone"]').val());

            // Disable field
            $('#survey_province, #survey_city, #survey_district, #survey_sub_district, #survey_postal_code, [name="survey_address"], [name="shipping_name"], [name="shipping_phone"]')
                .attr('readonly', true)
                .addClass('bg-light text-muted');

        } else {
            // Aktifkan kembali jika user batal
            $('#survey_province, #survey_city, #survey_district, #survey_sub_district, #survey_postal_code, [name="survey_address"], [name="shipping_name"], [name="shipping_phone"]')
                .attr('readonly', false)
                .removeClass('bg-light text-muted');
        }
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {

    const fileStores = new Map();

    document.querySelectorAll('.image-input').forEach(input => {
        fileStores.set(input, new DataTransfer());

        input.addEventListener('change', () => {
            const previewEl = document.getElementById(input.dataset.preview);
            const store     = fileStores.get(input);

            const selectedFiles = Array.from(input.files);
            input.value = '';

            selectedFiles.forEach(file => {
                store.items.add(file);

                const reader = new FileReader();
                reader.onload = e => {
                    const div = document.createElement('div');
                    div.className = 'position-relative';
                    div.innerHTML = `
                        <img src="${e.target.result}"
                             class="rounded border"
                             style="width:120px;height:120px;object-fit:cover">
                        <button type="button"
                                class="btn btn-sm btn-danger position-absolute top-0 end-0 remove-image">
                            ×
                        </button>
                    `;

                    div.querySelector('.remove-image').addEventListener('click', () => {
                        const index = Array.from(store.files).findIndex(
                            f => f.name === file.name && f.size === file.size
                        );

                        if (index > -1) {
                            store.items.remove(index);
                            input.files = store.files;
                        }

                        div.remove();
                    });

                    previewEl.appendChild(div);
                };
                reader.readAsDataURL(file);
            });

            input.files = store.files;
        });
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {

    const fileStores = new Map();

    document.querySelectorAll('.pdf-input').forEach(input => {
        fileStores.set(input, new DataTransfer());

        input.addEventListener('change', () => {
            const previewEl = document.getElementById(input.dataset.preview);
            const store = fileStores.get(input);

            const files = Array.from(input.files);
            input.value = ''; // reset awal

            files.forEach(file => {
                store.items.add(file);

                const row = document.createElement('div');
                row.className = 'd-flex align-items-center justify-content-between border rounded p-2';

                row.innerHTML = `
                    <div class="d-flex align-items-center gap-2">
                        <i class="ti ti-file-text fs-4 text-danger"></i>
                        <span>${file.name}</span>
                        <small class="text-muted">(${(file.size/1024).toFixed(1)} KB)</small>
                    </div>
                    <button type="button" class="btn btn-sm btn-danger">Hapus</button>
                `;

                row.querySelector('button').addEventListener('click', () => {
                    const index = Array.from(store.files).findIndex(
                        f => f.name === file.name && f.size === file.size
                    );

                    if (index > -1) {
                        store.items.remove(index);
                        input.files = store.files;
                    }

                    row.remove();
                });

                previewEl.appendChild(row);
            });

            input.files = store.files;
        });
    });
});
</script>
<script>
document.addEventListener('click', function(e) {
    const btn = e.target.closest('.btn-delete');
    if (!btn) return;

    if (!confirm('Hapus file ini?')) return;

    const id   = btn.dataset.id;
    const type = btn.dataset.type;

    let url = '';
    if (type === 'document') url = `/survey-documents/${id}`;
    if (type === 'image') url = `/survey-images/${id}`;
    if (type === 'documentation') url = `/survey-documentations/${id}`;

fetch(url, {
    method: 'DELETE',
    headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
    }
})
.then(res => {
    if (!res.ok) throw new Error('Gagal hapus');
    btn.closest('.position-relative, .list-group-item').remove();
})
.catch(err => alert('Delete gagal'));
});
</script>

<script>
document.addEventListener('click', function (e) {

    const btn = e.target.closest('.add-row');
    if (!btn) return;

    const tableId = btn.dataset.target;
    const table   = document.getElementById(tableId);
    const tbody   = table.querySelector('tbody');

    const index = tbody.querySelectorAll('tr').length;

    const row = document.createElement('tr');
    row.innerHTML = `
        <td class="row-no text-center">${index + 1}</td>
        <td>
            <textarea name="items[${index}][description]"
                      class="form-control" rows="2"></textarea>
        </td>
        <td>
            <textarea name="items[${index}][remark]"
                      class="form-control" rows="2"></textarea>
        </td>
        <td>
            <button type="button"
                    class="btn btn-sm btn-danger remove-row">-</button>
        </td>
    `;

    tbody.appendChild(row);
});
</script>
<script>
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.remove-row');
    if (!btn) return;

    const row   = btn.closest('tr');
    if (row.dataset.fixed === "1") {
        alert('Baris ini tidak bisa dihapus');
        return;
    }
    const tbody = row.closest('tbody');

    row.remove();

    // re-numbering
    tbody.querySelectorAll('tr').forEach((tr, i) => {
        tr.querySelector('.row-no').textContent = i + 1;
    });
});
</script>
<script>
document.addEventListener("DOMContentLoaded", () => {

    const view = document.getElementById("rab-view");
    const edit = document.getElementById("rab-edit");

    const rabId = @json(optional($rab)->id)

    document.getElementById("btn-edit-rab")?.addEventListener("click", async () => {

        if(!rabId){
            console.warn("RAB belum ada")
            return
        }

        view.style.display = "none";
        edit.style.display = "block";

        try {
            const res = await fetch(`/rab/${rabId}/structure`)
            const data = await res.json()

            loadExistingRab(data)
            initRabEdit()

        } catch (err) {
            console.error(err)
        }

    });

    document.getElementById("btn-cancel-rab").addEventListener("click", () => {
        edit.style.display = "none";
        view.style.display = "block";
    });

});
</script>
<script>
document.addEventListener("DOMContentLoaded", () => {

    const view = document.getElementById("consultation-view");
    const edit = document.getElementById("consultation-edit");

    document.getElementById("btn-edit-consultation").addEventListener("click", () => {
        view.style.display = "none";
        edit.style.display = "block";
    });

    document.getElementById("btn-cancel-consultation").addEventListener("click", () => {
        edit.style.display = "none";
        view.style.display = "block";
    });

});
</script>
<script>
document.addEventListener("DOMContentLoaded", () => {

    const view = document.getElementById("planning-view");
    const edit = document.getElementById("planning-edit");

    document.getElementById("btn-edit-planning").addEventListener("click", () => {
        view.style.display = "none";
        edit.style.display = "block";
    });

    document.getElementById("btn-cancel-planning").addEventListener("click", () => {
        edit.style.display = "none";
        view.style.display = "block";
    });

});
</script>
<script>
document.addEventListener("DOMContentLoaded", () => {

    const view = document.getElementById("survey-view");
    const edit = document.getElementById("survey-edit");

    document.getElementById("btn-edit-survey").addEventListener("click", () => {
        view.style.display = "none";
        edit.style.display = "block";
    });

    document.getElementById("btn-cancel-survey").addEventListener("click", () => {
        edit.style.display = "none";
        view.style.display = "block";
    });

});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    function showEdit(viewId, editId) {
        const viewEl = document.getElementById(viewId);
        const editEl = document.getElementById(editId);

        if (!viewEl || !editEl) return;

        viewEl.style.display = 'none';
        editEl.style.display = 'block';
    }

    function showView(viewId, editId) {
        const viewEl = document.getElementById(viewId);
        const editEl = document.getElementById(editId);

        if (!viewEl || !editEl) return;

        editEl.style.display = 'none';
        viewEl.style.display = 'block';
    }

    // === BUTTON EDIT ===
    document.querySelectorAll('.btn-toggle-offer').forEach(button => {
        button.addEventListener('click', function () {
            showEdit(
                this.dataset.view || 'offer-view',
                this.dataset.edit || 'offer-edit'
            );
        });
    });

    // === BUTTON CANCEL ===
    document.addEventListener('click', function (e) {
        if (e.target.closest('.btn-cancel')) {
            e.preventDefault();
            showView('offer-view', 'offer-edit');
        }
    });

});
</script>


<script>
document.addEventListener("DOMContentLoaded", () => {

    document.querySelectorAll('.btn-toggle-rab').forEach(btn => {
        btn.addEventListener('click', () => {
            const viewId = btn.dataset.view;
            const editId = btn.dataset.edit;

            const view = document.getElementById(viewId);
            const edit = document.getElementById(editId);

            if (!view || !edit) return;

            view.style.display = 'none';
            edit.style.display = 'block';
        });
    });
    document.querySelectorAll('.btn-cancel-rab').forEach(btn => {
        btn.addEventListener('click', () => {
            const viewId = btn.dataset.view;
            const editId = btn.dataset.edit;

            const view = document.getElementById(viewId);
            const edit = document.getElementById(editId);

            if (!view || !edit) return;

            edit.style.display = 'none';
            view.style.display = 'block';
        });
    });

});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const editBtn = document.getElementById('btn-edit-final');
    const fileInput = document.querySelector('input[name="document"]');

    if (editBtn && fileInput) {
        // awalnya disable
        fileInput.disabled = true;

        editBtn.addEventListener('click', function () {
            fileInput.disabled = false;
            fileInput.focus();
        });
    }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.approve-form').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            Swal.fire({
                title: form.dataset.title || 'Apakah Anda yakin?',
                text: form.dataset.text || 'Proses ini akan dilanjutkan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Lanjutkan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#212529',
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });
                    form.submit();
                }
            });
        });
    });
});
</script>

@endpush