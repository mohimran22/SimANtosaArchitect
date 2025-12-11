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

        {{-- STEP 1 – Create Project --}}
        @if($activeStep == 1)
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body px-5 py-4">
                <h3 class="mb-4 fw-bold">Buat Proyek Baru</h3>
                @include('projects.steps.create-project')
            </div>
        </div>
        @endif


        {{-- DETAIL PROJECT (muncul setelah step 1 selesai) --}}
        @if($activeStep >= 2)
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body px-5 py-4">
                <h3 class="mb-4 fw-bold">1. Proyek</h3>
                @include('projects.details.project')
            </div>
        </div>
        @endif


        {{-- STEP 2 – Form Konsultasi --}}
        @if($activeStep == 2)
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body px-5 py-4">
                <h3 class="mb-4 fw-bold">Form Konsultasi</h3>
                @include('projects.steps.consultation-form')
            </div>
        </div>
        @endif


        {{-- DETAIL KONSULTASI --}}
        @if($activeStep >= 3)
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body px-5 py-4">
                <h3 class="mb-3 fw-bold">2. Tahap Konsultasi</h3>
                @include('projects.details.consultation')
            </div>
        </div>
        @endif


        {{-- STEP 3 – Form Rencana Survei --}}
        @if($activeStep == 3)
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body px-5 py-4">
                <h3 class="fw-bold mb-4">Form Rencana Survei Lapangan</h3>
                @include('projects.steps.planning-form')
            </div>
        </div>
        @endif


        {{-- DETAIL RENCANA SURVEI --}}
        @if($activeStep >= 4)
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body px-5 py-4">
                <h3 class="mb-3 fw-bold">3. Rencana Survei</h3>
                @include('projects.details.planning')
            </div>
        </div>
        @endif


        {{-- STEP 4 – Form Survei --}}
        @if($activeStep == 4)
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body px-5 py-4">
                <h3 class="mb-3 fw-bold">Form Survei Lapangan</h3>
                @include('projects.steps.survey-form')
            </div>
        </div>
        @endif


        {{-- DETAIL SURVEI --}}
        @if($activeStep >= 5)
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body px-5 py-4">
                <h3 class="mb-3 fw-bold">4. Survei</h3>
                @include('projects.details.survey')
            </div>
        </div>
        @endif


        {{-- STEP 5 – Form Penawaran Desain --}}
        @if($activeStep == 5)
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body px-5 py-4">
                <h3 class="mb-3 fw-bold">Form Penawaran Jasa Desain</h3>
                @include('projects.steps.desain-form')
            </div>
        </div>
        @endif


        {{-- DETAIL PENAWARAN DESAIN --}}
        @if($activeStep >= 6)
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body px-5 py-4">
                <h3 class="mb-3 fw-bold">5. Penawaran Jasa Desain</h3>
                @include('projects.details.offer')
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
$('#survey_province').change(function () 
{
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
// FORM KONSULTASI → Ke Survei
document.getElementById('btnToSurvey')
    .addEventListener('click', function() {
        document.getElementById('go_to_survey').value = 1;
        this.closest('form').submit();
    });

// FORM SURVEI → Ke Desain
document.getElementById('btnToDesain')
    .addEventListener('click', function() {
        document.getElementById('go_to_desain').value = 1;
        this.closest('form').submit();
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
function previewImages(input, previewContainerId) {
    const container = document.getElementById(previewContainerId);
    container.innerHTML = "";

    if (input.files) {
        [...input.files].forEach(file => {
            const reader = new FileReader();
            reader.onload = e => {
                container.innerHTML += `
                    <div class="border rounded p-1" style="width:120px; height:120px; overflow:hidden;">
                        <img src="${e.target.result}" 
                             style="width:100%; height:100%; object-fit:cover;">
                    </div>
                `;
            };
            reader.readAsDataURL(file);
        });
    }
}

document.querySelector('input[name="documentation[]"]').addEventListener("change", function() {
    previewImages(this, "preview-documentation");
});

document.querySelector('input[name="result_images[]"]').addEventListener("change", function() {
    previewImages(this, "preview-result-images");
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
@endpush