{{-- Penting --}}
@extends('tablar::page')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">
                        Overview
                    </div>
                    <h2 class="page-title">
                        Pemilik Lisensi
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                  
                        <a href=" {{ route("license_holders.index") }} " class="btn btn-primary d-none d-sm-inline-block" >
                            Kembali
                        </a>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <p class="text-center mb-4" style="font-size: 1.5rem; font-weight: 400; font-family: 'Poppins', sans-serif;">
                                Edit Data Pemilik Lisensi
                            </p>
                        </div>

                        <div class="card-body">
                            <form  class="font-normal" style="font-weight: 400; font-family: 'Poppins', sans-serif;" action="{{ route('license_holders.update', $license_holder->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('put')

                                    <h2 class="mt-4 mb-3">Data Pemilik</h2>
                                    <div class="row mb-4">

                                        <div class="col-md-6 mb-3">
                                            <label for="licenses">Pilih Lisensi <code>*</code></label>
                                            <select name="licenses[]" class="form-control" multiple required
                                                @role('Pemilik Lisensi') disabled @endrole>
                                                @foreach($allLicenses as $license)
                                                    <option value="{{ $license->id }}"
                                                         {{ collect(old('licenses', $license_holder->user->licenses->pluck('id')->toArray()))->contains($license->id) ? 'selected' : '' }}>
                                                        {{ $license->name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            @role('Pemilik Lisensi')
                                                @foreach($employee->user->licenses as $license)
                                                    <input type="hidden" name="licenses[]" value="{{ $license->id }}">
                                                @endforeach
                                            @endrole
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label>Nama Pemilik *</label>
                                            <input type="text" class="form-control @error('fullname') is-invalid @enderror" id="fullname" name="fullname" value="{{ old('fullname', $license_holder->fullname) }}" required>
                                            @error('fullname')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label>Nama Panggilan *</label>
                                            <input type="text" class="form-control @error('nickname') is-invalid @enderror" id="nickname" name="nickname" value="{{ old('nickname', $license_holder->nickname) }}" required>
                                            @error('nickname')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label>Jenis Kelamin *</label>
                                            <select name="gender" class="form-select" required>
                                                <option value="">-- Pilih Jenis Kelamin --</option>
                                                <option value="1" {{ $license_holder->gender == 1 ? 'selected' : '' }}>Laki - Laki</option>
                                                <option value="2" {{ $license_holder->gender == 2 ? 'selected' : '' }}>Perempuan</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="email">Email: *</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $license_holder->user->email) }}"
                                            @if(auth()->user()->hasRole('Pemilik Lisensi')) readonly @endif>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="religion_id">Agama *</label>
                                            <select name="religion_id" class="form-control" required>
                                                <option value="">-- Pilih Agama --</option>
                                                @foreach($religions as $religion)
                                                    <option value="{{ $religion->id }}" {{ old('religion_id', $license_holder->religion_id) == $religion->id ? 'selected' : '' }}>
                                                        {{ $religion->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label>Nomor KTP *</label>
                                            <input type="number" class="form-control @error('identity_number') is-invalid @enderror" id="identity_number" name="identity_number" maxlength="16" value="{{ old('identity_number', $license_holder->identity_number) }}" required>
                                            @error('identity_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label>Nomor SIM</label>
                                            <input type="number" class="form-control @error('driver_license_number') is-invalid @enderror" id="driver_license_number" name="driver_license_number" value="{{ old('driver_license_number', $license_holder->driver_license_number) }}">
                                            @error('driver_license_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    
                                        <div class="col-md-6 mb-3">
                                            <label>Tempat Lahir *</label>
                                            <input type="text" class="form-control @error('birth_place') is-invalid @enderror" id="birth_place" name="birth_place" value="{{ old('birth_place', $license_holder->birth_place) }}" required>
                                            @error('birth_place')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    

                                        <div class="col-md-6 mb-3">
                                            <label>Tanggal Lahir *</label>
                                            <input type="date" name="birth_date" class="form-control" required
                                                value="{{ old('birth_date', $license_holder->birth_date) }}"
                                                pattern="\d{4}-\d{2}-\d{2}" placeholder="YYYY-MM-DD">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label>Alamat *</label>
                                            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $license_holder->address) }}" required>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label>Provinsi *</label>
                                            <select name="province_id" id="province" class="form-select select2" required>
                                                <option value="">-- Pilih Provinsi --</option>
                                                @foreach($provinces as $province)
                                                    <option value="{{ $province->id }}"
                                                        {{ $license_holder->province_id == $province->id ? 'selected' : '' }}>
                                                        {{ $province->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label>Kabupaten/Kota *</label>
                                            <select name="city_id" id="city" class="form-select select2" required>
                                                <option value="">-- Pilih Kota --</option>
                                                @foreach($cities as $city)
                                                    <option value="{{ $city->id }}"
                                                        {{ $license_holder->city_id == $city->id ? 'selected' : '' }}>
                                                        {{ $city->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label>Kecamatan *</label>
                                            <select name="district_id" id="district" class="form-select select2" required>
                                                <option value="">-- Pilih Kecamatan --</option>
                                                @foreach($districts as $district)
                                                    <option value="{{ $district->id }}"
                                                        {{ $license_holder->district_id == $district->id ? 'selected' : '' }}>
                                                        {{ $district->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label>Desa *</label>
                                            <select name="sub_district_id" id="sub_district" class="form-select select2" required>
                                                <option value="">-- Pilih Desa --</option>
                                                @foreach($subDistricts as $sub_district)
                                                    <option value="{{ $sub_district->id }}"
                                                        {{ $license_holder->sub_district_id == $sub_district->id ? 'selected' : '' }}>
                                                        {{ $sub_district->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label>Kode Pos *</label>
                                            <select name="postal_code_id" id="postal_code" class="form-select select2" required>
                                                <option value="">-- Pilih Desa --</option>
                                                @foreach($postalCodes as $postal_code)
                                                    <option value="{{ $postal_code->id }}"
                                                        {{ $license_holder->postal_code_id == $postal_code->id ? 'selected' : '' }}>
                                                        {{ $postal_code->postal_code }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label>Telepon *</label>
                                            <input type="number" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $license_holder->phone) }}" required>
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label>Hobi *</label>
                                            <input type="text" class="form-control @error('hobby') is-invalid @enderror" id="hobby" name="hobby" value="{{ old('hobby', $license_holder->hobby) }}" required>
                                            @error('hobby')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label>Foto Saat Ini:</label><br>
                                            @if ($license_holder->photo)
                                                <img src="{{ asset('storage/photos/' . $license_holder->photo) }}" class="rounded mb-2" width="150">
                                            @else
                                            <div class="me-3">
                                                <p class="text-muted mb-0">Belum ada foto</p>
                                            </div>
                                            @endif
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="photo" class="form-label">Ganti Foto</label>
                                            <input type="file" name="photo" class="form-control" accept="image/*">
                                            @error('photo')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                    </div>

                                    {{-- SECTION 2: Data Kontak --}}
                                    <h2 class="mt-4 mb-3">Data Pernikahan</h2>
                                    <div class="row mb-4">
                                        <div class="col-md-6 mb-3">
                                            <label>Status Pernikahan *</label>
                                            <select name="marital_status" class="form-control" required>
                                                <option value="">-- Pilih Tipe --</option>
                                                <option value="1" {{ $license_holder->marital_status == 1 ? 'selected' : '' }}>Lajang</option>
                                                <option value="2" {{ $license_holder->marital_status == 2 ? 'selected' : '' }}>Menikah</option>
                                                <option value="3" {{ $license_holder->marital_status == 3 ? 'selected' : '' }}>Duda</option>
                                                <option value="4" {{ $license_holder->marital_status == 4 ? 'selected' : '' }}>Janda</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label>Tanggal Pernikahan</label>
                                            <input type="date" name="married_date" class="form-control"
                                                value="{{ old('married_date', $license_holder->married_date) }}"
                                                pattern="\d{4}-\d{2}-\d{2}" placeholder="YYYY-MM-DD">
                                        </div>
                                    </div>

                                    {{-- SECTION 5: Sosial Media --}}
                                    <h2 class="mt-4 mb-3">Kemampuan Bahasa</h2>
                                    <div class="row mb-4">
                                        <div class="col-md-6 mb-3">
                                            <label>Bahasa Indonesia (Baca/Tulis) </label>
                                            <select name="indonesian_literacy" class="form-control">
                                                <option value="">-- Pilih Tipe --</option>
                                                <option value="1" {{ $license_holder->indonesian_literacy == 1 ? 'selected' : '' }}>Lancar</option>
                                                <option value="2" {{ $license_holder->indonesian_literacy == 2 ? 'selected' : '' }}>Tidak Lancar</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label>Bahasa Indonesia (Bicara) </label>
                                            <select name="indonesian_proficiency" class="form-control">
                                                <option value="">-- Pilih Tipe --</option>
                                                <option value="1" {{ $license_holder->indonesian_proficiency == 1 ? 'selected' : '' }}>Lancar</option>
                                                <option value="2" {{ $license_holder->indonesian_proficiency == 2 ? 'selected' : '' }}>Tidak Lancar</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label>Bahasa Arab (Baca/Tulis)</label>
                                            <select name="arabic_literacy" class="form-control">
                                                <option value="">-- Pilih Tipe --</option>
                                                <option value="1" {{ $license_holder->arabic_literacy == 1 ? 'selected' : '' }}>Lancar</option>
                                                <option value="2" {{ $license_holder->arabic_literacy == 2 ? 'selected' : '' }}>Tidak Lancar</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label>Bahasa Arab (Bicara)</label>
                                            <select name="arabic_proficiency" class="form-control">
                                                <option value="">-- Pilih Tipe --</option>
                                                <option value="1" {{ $license_holder->arabic_proficiency == 1 ? 'selected' : '' }}>Lancar</option>
                                                <option value="2" {{ $license_holder->arabic_proficiency == 2 ? 'selected' : '' }}>Tidak Lancar</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label>Bahasa Inggris (Baca/Tulis)</label>
                                            <select name="english_literacy" class="form-control">
                                                <option value="">-- Pilih Tipe --</option>
                                                <option value="1" {{ $license_holder->english_literacy == 1 ? 'selected' : '' }}>Lancar</option>
                                                <option value="2" {{ $license_holder->english_literacy == 2 ? 'selected' : '' }}>Tidak Lancar</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label>Bahasa Inngris (Bicara)</label>
                                            <select name="english_proficiency" class="form-control">
                                                <option value="">-- Pilih Tipe --</option>
                                                <option value="1" {{ $license_holder->english_proficiency == 1 ? 'selected' : '' }}>Lancar</option>
                                                <option value="2" {{ $license_holder->english_proficiency == 2 ? 'selected' : '' }}>Tidak Lancar</option>
                                            </select>
                                        </div>

                                    </div>

                                <button type="submit" class="btn btn-primary mt-4">Update</button>
                             </form>

                        </div>
                    </div>
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
                                        $('#sub_district').html('<option value="">-- Pilih Kelurahan --</option>');

                                            if (id) {
                                            $.get('/api/cities/' + id, function (data) {
                                            $('#city').empty().append('<option value="">-- Pilih Kabupaten --</option>');
                                            $.each(data, function (i, city) {
                                            $('#city').append('<option value="' + city.id + '">' + city.name + '</option>');
                                                        });
                                                    });
                                                }
                                            });

                                            $('#city').change(function () {
                                                var id = $(this).val();
                                                $('#district').html('<option>Loading...</option>');
                                                $('#sub_district').html('<option value="">-- Pilih Kelurahan --</option>');

                                                if (id) {
                                                    $.get('/api/districts/' + id, function (data) {
                                                        $('#district').empty().append('<option value="">-- Pilih Kecamatan --</option>');
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
                                                        $('#sub_district').empty().append('<option value="">-- Pilih Kelurahan --</option>');
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
                                                        $('#postal_code').empty().append('<option value="">-- Pilih Kode Pos --</option>');
                                                        $.each(data, function (i, postal_code) {
                                                            $('#postal_code').append('<option value="' + postal_code.id + '">' + postal_code.postal_code + '</option>');
                                                        });
                                                    });
                                                }
                                            });
                                        </script>
                                    @endpush