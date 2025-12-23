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
            @include('projects.components.timeline-horizontal')
                @php
                    $planning = $project?->planning;
                    $disableEdit = $surveyWaiting;
                @endphp
            @if($activeStep == 1)
            <div id="step-1" class="step-section">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body px-5 py-4">
                        <h3 class="mb-4 fw-bold">Buat Proyek Baru</h3>
                        @include('projects.steps.create-project')
                    </div>
                </div>
            </div>
            @endif
            @if($activeStep >= 2)
            <div id="step-2" class="step-section">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body px-5 py-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="fw-bold m-0">Proyek</h3>

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

                        </div>
                    </div>
                        <div id="project-view">
                            @include('projects.details.project')
                        </div>

                        <div id="project-edit" style="display:none;">
                            @include('projects.edit.project-form')    
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @if($activeStep == 2)
            <div id="step-2" class="step-section">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body px-5 py-4">
                        <h3 class="mb-4 fw-bold">1. Form Konsultasi</h3>
                        @include('projects.steps.consultation-form')
                    </div>
                </div>
            </div>
            @endif
            @if($activeStep >= 3)
            <div id="step-3" class="step-section">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body px-5 py-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="fw-bold m-0">1. Tahap Konsultasi</h3>
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
                        </div>
                    </div>
                        <div id="consultation-view">
                            @include('projects.details.consultation')
                        </div>

                        <div id="consultation-edit" style="display:none;">
                            @include('projects.edit.consultation-form')    
                        </div>
                </div>
            </div>
            @endif
            @if($activeStep >= 3)
            <div id="step-planning" class="step-section">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body px-5 py-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="fw-bold mb-0">2. Rencana Survei</h3>

                        <div class="d-flex align-items-center gap-2">
                            <button type="button"
                                id="btn-edit-planning"
                                class="btn btn-sm btn-dark {{ $disableEdit ? 'disabled' : '' }}"
                                {{ $disableEdit ? 'disabled' : '' }}
                                title="{{ $disableEdit ? 'Menunggu persetujuan biaya survei' : 'Edit Data' }}">
                                <i class="ti ti-edit"></i>
                            </button>

                            {{-- @if($surveyInvoice && $surveyInvoice->amount > 0)
                                <a href="{{ route('projects.planning-survey.pdf', $project->id) }}"
                                    class="btn btn-sm btn-dark"
                                    target="_blank"
                                    title="Lihat PDF Rencana Survei">
                                    <i class="ti ti-file-text"></i>
                                </a>
                            @endif
                            @if($surveyInvoice && $surveyInvoice->status === 'approved')
                                <a href="{{ route('projects.invoice-survey', $project->id) }}"
                                    class="btn btn-dark"
                                    target="_blank"
                                    title="Cetak Invoice Rencana Survei">
                                    <i class="ti ti-receipt"></i>
                                </a>
                            @endif --}}
                        </div>
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

                            @if($surveyInvoice && $surveyInvoice->status === 'rejected')
                                <div class="alert alert-danger mt-4">
                                    Biaya survei ditolak:<br>{{ $surveyInvoice->reject_note }}
                                    
                                </div>

                            @elseif($surveyInvoice && $surveyInvoice->status === 'waiting_approval' && $surveyInvoice->amount > 0)
                                <div class="alert alert-warning mt-4">
                                    Menunggu persetujuan biaya survei dari customer (via PDF)<br>Data rencana survei tidak dapat diubah selama proses persetujuan.
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
            @endif
            @if(
                $activeStep == 4 &&
                $planning &&
                (
                    $project->levels->firstWhere('level_order', 3)?->is_started
                )
            )
            <div id="step-survey" class="step-section">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body px-5 py-4">
                        <h3 class="mb-3 fw-bold">3. Form Survei Lapangan</h3>
                        @include('projects.steps.survey-form')
                    </div>
                </div>
            </div>
            @endif
            @if($activeStep >= 5)
            <div id="step-5" class="step-section">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body px-5 py-4">       
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="fw-bold m-0">3. Survei</h3>
                            <div class="btn-group">
                                <button type="button" id="btn-edit-survey"
                                    class="btn btn-sm btn-dark me-2"
                                    title="Edit Data">
                                    <i class="ti ti-edit"></i>
                                </button>
                            </div>
                        </div>

                        {{-- SISI VIEW --}}
                        <div id="survey-view">
                            @include('projects.details.survey')
                        </div>

                        {{-- SISI EDIT --}}
                        <div id="survey-edit" style="display:none;">
                            @include('projects.edit.survey-form')
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @if($activeStep == 5)
            <div id="step-5" class="step-section">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body px-5 py-4">
                        <h3 class="mb-3 fw-bold">4. Form Penawaran Jasa Desain</h3>
                        @include('projects.steps.desain-form')
                    </div>
                </div>
            </div>
            @endif
            @if($activeStep >= 6)
            <div id="step-6" class="step-section">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body px-5 py-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="mb-3 fw-bold">4. Penawaran Jasa Desain</h3>
                            <div class="btn-group">
                                <button type="button" id="btn-edit-offer"
                                    class="btn btn-sm btn-dark me-2"
                                    title="Edit Data">
                                    <i class="ti ti-edit"></i>
                                </button>
                                @if($project->offer?->id)
                                <a href="{{ route('projects.offers.pdf', $project->offer->id) }}"
                                    class="btn btn-sm btn-dark"
                                    target="_blank"
                                    title="Download PDF">
                                    <i class="ti ti-download"></i>
                                </a>
                                @endif
                            </div>
                        </div>
                        <div id="offer-view">
                            @include('projects.details.offer')
                        </div>
                        <div id="offer-edit" style="display:none;">
                            @include('projects.edit.offer-form')    
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @if($activeStep >= 6)
            <div id="step-6" class="step-section">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body px-5 py-4">
                        <h3 class="mb-3 fw-bold">5. Draft Kontrak Pelaksanaan Pekerjaan</h3>

                        <div class="d-flex gap-2">

                            <a href="{{ route('projects.contract.pdf', $project->id) }}"
                            class="btn btn-dark"
                            target="_blank">
                                <i class="ti ti-download"></i> Download Draft Kontrak
                            </a>

                            @if(!$project->offer->approved_at)
                                <form action="{{ route('projects.contract.approve', $project->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Approve kontrak dan lanjut ke tahap Invoice DP?')">
                                    @csrf
                                    <button class="btn btn-dark">
                                        <i class="ti ti-check"></i> Approve Kontrak
                                    </button>
                                </form>
                            @else
                                <span class="badge bg-dark align-self-center">
                                    <i class="ti ti-check"></i>
                                    Disetujui {{ $project->offer->approved_at->format('d M Y') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @if($activeStep >= 7)
            <div id="step-7" class="step-section">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body px-5 py-4">
                        <h3 class="mb-3 fw-bold">6. Invoice Pembayaran Desain DP</h3>
                            
                                <div class="d-flex gap-2">
                                    @php
                                        $invoice = $invoiceDp;
                                    @endphp

                                
                                    @if(!$invoice?->invoice_dp_downloaded_at)
                                        <a href="{{ route('projects.invoice.pdf', $project->id) }}"
                                        class="btn btn-dark"
                                        target="_blank">
                                            <i class="ti ti-download"></i> Download Invoice
                                        </a>
                                    @else
                                        <a href="{{ route('projects.invoice.pdf', $project->id) }}"
                                            class="btn btn-dark"
                                            target="_blank">
                                            <i class="ti ti-download"></i>Download Invoice
                                        </a>
                                        <span class="btn btn-outline-dark disabled">
                                            <i class="ti ti-check"></i> Sudah didownload
                                        </span>
                                    @endif

                                    @if(
                                        $invoice?->invoice_dp_downloaded_at &&
                                        !$invoice?->invoice_dp_approved_at
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
                                </div>     
                    </div>
                </div>
            </div>
            @endif
            @if($activeStep >= 8)
            <div id="step-8" class="step-section">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body px-5 py-4">
                        <h3 class="mb-3 fw-bold">7. Form Pengerjaan</h3>
                        @include('projects.steps.work-process')
                    </div>
                </div>
            </div>
            @endif
            @if($activeStep >= 9)
            <div id="step-9" class="step-section">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body px-5 py-4">
                        <h3 class="mb-3 fw-bold">8. Invoice Pelunasan Desain</h3>

                        @php
                            $invoiceFinal = $project->invoices
                                ->where('invoice_type', 'final')
                                ->first();
                        @endphp

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
                                    onsubmit="return confirm('Selesaikan proyek?')">
                                    @csrf
                                    <button class="btn btn-success">
                                        <i class="ti ti-check"></i> Konfirmasi Pelunasan
                                    </button>
                                </form>
                            @endif

                            @if($invoiceFinal?->approved_at)
                                <span class="btn btn-outline-success disabled">
                                    <i class="ti ti-check"></i> Pelunasan Selesai
                                </span>
                            @endif
                        </div>
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
function previewMultipleImages(input) {
    const previewWrapper = document.getElementById('preview-wrapper');
    previewWrapper.innerHTML = ''; // reset preview lama

    if (!input.files) return;

    Array.from(input.files).forEach(file => {
        if (!file.type.startsWith('image/')) return;

        const reader = new FileReader();

        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.width = '90px';
            img.style.height = '90px';
            img.style.objectFit = 'cover';
            img.style.borderRadius = '6px';
            img.style.border = '1px solid #ddd';

            previewWrapper.appendChild(img);
        };

        reader.readAsDataURL(file);
    });
}
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

    const view = document.getElementById("project-view");
    const edit = document.getElementById("project-edit");

    document.getElementById("btn-edit-project").addEventListener("click", () => {
        view.style.display = "none";
        edit.style.display = "block";
    });

    document.getElementById("btn-cancel-project").addEventListener("click", () => {
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
document.addEventListener("DOMContentLoaded", () => {

    const view = document.getElementById("offer-view");
    const edit = document.getElementById("offer-edit");

    document.getElementById("btn-edit-offer").addEventListener("click", () => {
        view.style.display = "none";
        edit.style.display = "block";
    });

    document.getElementById("btn-cancel-offer").addEventListener("click", () => {
        edit.style.display = "none";
        view.style.display = "block";
    });

});
</script>

@endpush