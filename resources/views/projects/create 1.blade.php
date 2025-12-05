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

                {{-- ========================================================= --}}
                {{-- STEP 1 — FORM CREATE PROJECT --}}
                {{-- ========================================================= --}}
                @if($activeStep == 1)
                    <h3 class="mb-4 fw-bold">Buat Proyek Baru</h3>

                    @include('projects.steps.create-project')
                @endif


                {{-- ========================================================= --}}
                {{-- STEP >= 2 — ALWAYS SHOW PROJECT DETAILS --}}
                {{-- ========================================================= --}}
                @if($activeStep >= 2)
                    <div class="alert alert-success">Proyek berhasil dibuat.</div>

                    <h3 class="mb-4 fw-bold">Detail Proyek</h3>

                    @include('projects.details.project')
                @endif


                {{-- ========================================================= --}}
                {{-- STEP 2 — FORM KONSULTASI --}}
                {{-- ========================================================= --}}
                @if($activeStep == 2)
                    <div class="card-body px-2 py-4">
                        <h3 class="mb-4 fw-bold">Form Konsultasi</h3>

                        @include('projects.steps.consultation-form')
                    </div>
                @endif


                {{-- ========================================================= --}}
                {{-- STEP >= 3 — TAMPILKAN DETAIL KONSULTASI --}}
                {{-- ========================================================= --}}
                @if($activeStep >= 3)
                    <div class="mt-4">
                        <h3 class="mb-3 fw-bold">Detail Konsultasi</h3>

                        @include('projects.details.consultation')
                    </div>
                @endif


                {{-- ========================================================= --}}
                {{-- STEP 3 — FORM SURVEI --}}
                {{-- ========================================================= --}}
                @if($activeStep == 3)
                    <div id="section-survey" class="card-body px-2 py-4 mt-4">
                        <h3 class="fw-bold mb-4">Form Rencana Survei Lapangan</h3>

                        @include('projects.steps.survey-form')
                    </div>
                @endif


                {{-- ========================================================= --}}
                {{-- STEP >= 4 — DETAIL SURVEI --}}
                {{-- ========================================================= --}}
                @if($activeStep >= 4)
                    <div class="mt-4">
                        <h3 class="mb-3 fw-bold">Detail Survei</h3>

                        @include('projects.details.survey')
                    </div>
                @endif


            </div>
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
{{-- <script>
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
</script> --}}



                                    @endpush