{{-- @extends('tablar::page')

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
                    <p class="page-title">
                       Karyawan
                    </p>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                 
                        <a href=" {{ route("employees.index") }} " class="btn btn-dark d-none d-sm-inline-block" >
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
                                            Tambah Data Karyawan
                                        </p>
                                    </div>

                            <div class="card-body px-4 pb-4">
                                <form class="font-normal" action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="text-center bg-light py-4 position-relative">
                                    <div class="position-relative d-inline-block">
                                        
                                            @if ($user->photo)
                                                {{-- Kalau user punya foto 
                                                <img
                                                    id="previewImage" 
                                                    src="{{ asset('storage/'.$user->photo) }}" 
                                                    alt="Profile" 
                                                    class="border rounded-3 shadow-sm"
                                                    width="150" height="150"
                                                    style="object-fit: cover;">
                                            @else
                                                {{-- Kalau belum punya foto, tampilkan ikon 
                                                <div id="previewImage" 
                                                    class="border rounded-3 shadow-sm d-flex align-items-center justify-content-center bg-light"
                                                    style="width:150px; height:150px;">
                                                    <i class="ti ti-user" style="font-size: 64px; color:#aaa;"></i>
                                                </div>
                                            @endif
                                        
                                        
                                        <label for="photo"
                                            class="btn btn-sm btn-primary position-absolute bottom-0 end-0 translate-middle rounded-circle"
                                            title="Ganti Foto">
                                            <i class="ti ti-camera"></i>
                                        </label>
                                    </div>
                                    <input type="file" id="photo" name="photo" class="d-none" accept="image/*">
                                </div>

                                <section class="pb-3 border-bottom mb-4">
                                    <h3 class="fw-bold text-dark mb-3">Informasi Pribadi</h3>
                                    <div class="row g-3">
                                        <div class="col-md-6 mb-3">
                                            <label for="fullname" class="">Nama Lengkap</label>
                                            <input type="text" name="fullname" id="fullname" class="form-control"
                                                value="{{ old('fullname') }}" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="nickname" class="">Nama Panggilan</label>
                                            <input type="text" name="nickname" id="nickname" class="form-control"
                                                value="{{ old('nickname') }}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label>Jenis Kelamin *</label>
                                            <select name="gender" class="form-select" required>
                                                <option value="">-- Pilih Jenis Kelamin --</option>
                                                <option value="1">Laki - Laki</option>
                                                <option value="2">Perempuan</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="birth_place" class="">Tempat Lahir</label>
                                            <input type="text" name="birth_place" id="birth_place" class="form-control"
                                                value="{{ old('birth_place') }}">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="required">Tanggal Lahir</label>
                                                <input type="date" name="birth_date" class="form-control" required
                                                    value="{{ old('birth_date') }}"
                                                    pattern="\d{4}-\d{2}-\d{2}" placeholder="YYYY-MM-DD">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="required" for="religion_id">Agama</label>
                                            <select name="religion_id" class="form-select" required>
                                                <option value="">-- Pilih Agama --</option>
                                                @foreach($religions as $religion)
                                                    <option value="{{ $religion->id }}" {{ old('religion_id') == $religion->id ? 'selected' : '' }}>
                                                        {{ $religion->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="identity_number" class="">Nomor KTP</label>
                                            <input type="number" name="identity_number" id="identity_number" class="form-control"
                                                value="{{ old('identity_number') }}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="npwp" class="">NPWP</label>
                                            <input type="number" name="npwp" id="npwp" class="form-control"
                                                value="{{ old('npwp') }}">
                                        </div>
                                    </div>
                                </section>

                                <section class="pb-3 border-bottom mb-4">
                                        <h3 class="fw-bold text-dark mb-3">Kontak & Alamat</h3>
                                        <div class="row g-3">
                                            <div class="col-md-6 mb-3">
                                                <label for="phone" class="">Nomor Telepon</label>
                                                <input type="text" name="phone" id="phone" class="form-control"
                                                    value="{{ old('phone') }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="email" class="">Email</label>
                                                <input type="email" name="email" id="email" class="form-control"
                                                    value="{{ old('email') }}">
                                            </div>
                                        </div>

                                        <div class="mt-3">
                                            <label for="address" class="">Alamat Lengkap</label>
                                            <textarea name="address" id="address" rows="2" class="form-control">{{ old('address') }}</textarea>
                                        </div>

                                        <div class="row g-3 mt-2">
                                            <div class="col-md-6 mb-3">
                                            <label class="required">Provinsi</label>
                                            <select name="province_id" id="province" class="form-select select2" required>
                                                <option value="">-- Pilih Provinsi --</option>
                                                @foreach($provinces as $province)
                                                    <option value="{{ $province->id }}">{{ $province->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="required">Kabupaten/Kota</label>
                                            <select name="city_id" id="city" class="form-select select2" required>
                                                <option value="city">-- Pilih Kota --</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="required">Kecamatan</label>
                                            <select name="district_id" id="district" class="form-select select2" required>
                                                <option value="district">-- Pilih Kecamatan --</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="required">Desa</label>
                                            <select name="sub_district_id" id="sub_district" class="form-select select2" required>
                                                <option value="sub_district">-- Pilih Desa --</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="required">Kode Pos</label>
                                            <select name="postal_code_id" id="postal_code" class="form-select select2" required>
                                                <option value="postal_code">-- Pilih Desa --</option>
                                            </select>
                                        </div>
                                        </div>
                                    </section>

                                    <section class="pb-3 mb-4">
                                    <h3 class="fw-bold text-dark mb-3">Data Bank</h3>
                                    <p class="small text-muted mt-2 mb-0">Diperlukan bila terjadi pengembalian dana</p>
                                    <div class="row g-3">
                                        <div class="col-md-6 mb-3">
                                            <label for="bank_name" class="">Nama Bank</label>
                                            <input type="text" name="bank_name" id="bank_name" class="form-control"
                                                value="{{ old('bank_name') }}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="account_number" class="">Nomor Rekening</label>
                                            <input type="number" name="account_number" id="account_number" class="form-control"
                                                value="{{ old('account_number') }}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="account_holder" class="">Atas Nama</label>
                                            <input type="text" name="account_holder" id="account_holder" class="form-control"
                                                value="{{ old('account_holder') }}">
                                        </div>
                                    </div>
                                </section>
                                    
                                    <h2 class="mt-4 mb-3">Data Kepegawaian</h2>
                                    <div class="row mb-4">

                                        
                                        <div class="col-md-6 mb-3">
                                            <label class="required">NIK :</label>
                                            <input type="number" class="form-control @error('nik') is-invalid @enderror" id="nik" name="nik" value="{{ old('nik') }}" required>
                                            @error('nik')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="required">Jabatan :</label>
                                            <input type="text" class="form-control @error('position') is-invalid @enderror" id="position" name="position" value="{{ old('position') }}" required>
                                            @error('position')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- <div class="col-md-6 mb-3">
                                            <label class="required">Jabatan </label>
                                            <select name="position[]" class="form-control select2" multiple required>
                                                <option value="Komisaris">Komisaris</option>
                                                <option value="Direktur">Direktur</option>
                                                <option value="Manager">Manager</option>
                                                <option value="Supervisor">Supervisor</option>
                                                <option value="Staff">Staff</option>
                                            </select>
                                        </div> 

                                        <div class="col-md-6 mb-3">
                                            <label class="required">Departemen :</label>
                                            <input type="text" class="form-control @error('department') is-invalid @enderror" id="department" name="department" value="{{ old('department') }}" required>
                                            @error('department')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="required">Unit Kerja :</label>
                                            <input type="text" class="form-control @error('unit') is-invalid @enderror" id="unit" name="unit" value="{{ old('unit') }}" required>
                                            @error('unit')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                         <div class="col-md-6 mb-3">
                                            <label for="employment_status" class="required">Status Karyawan <code>*</code></label>
                                            <select name="employment_status" class="form-select" required>
                                                <option value="">-- Pilih Status --</option>
                                                <option value="Tetap">Tetap</option>
                                                <option value="Kontrak">Kontrak</option>
                                                <option value="Magang">Magang</option>
                                            </select>
                                         </div>

                                         <div class="col-md-6 mb-3">
                                            <label for="role" class="required">Role :</label>
                                            <select name="role" class="form-select" required>
                                                <option value="">-- Pilih Role --</option>
                                                @foreach (\Spatie\Permission\Models\Role::all() as $role)
                                                    <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="required">Tanggal Mulai Kerja :</label>
                                            <input type="date" name="start_date" class="form-control" required
                                                value="{{ old('start_date') }}"
                                                pattern="\d{4}-\d{2}-\d{2}" placeholder="YYYY-MM-DD">
                                        </div>

                                        

                                    </div>

                                    
                                    <h2 class="mt-4 mb-3">Data Pernikahan</h2>
                                    <div class="row mb-4">
                                        <div class="col-md-6 mb-3">
                                            <label class="required">Status Pernikahan :</label>
                                            <select name="marital_status" class="form-select">
                                                <option value="">-- Pilih Tipe --</option>
                                                <option value="1">Lajang</option>
                                                <option value="2">Menikah</option>
                                                <option value="3">Duda</option>
                                                <option value="4">Janda</option>
                                            </select>
                                        </div>

                                    </div>

                                    
                                    <h2 class="mt-4 mb-3">Data Pekerjaan</h2>
                                    <div class="row mb-4">
                                        <div class="col-md-6 mb-3">
                                            <label class="required">Gaji Pokok :</label>
                                            <input type="number" class="form-control @error('basic_salary') is-invalid @enderror" id="basic_salary" name="basic_salary" value="{{ old('basic_salary') }}" required>
                                            @error('basic_salary')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label>Tunjangan *</label>
                                            <input type="number" class="form-control @error('allowance') is-invalid @enderror" id="allowance" name="allowance" value="{{ old('allowance') }}" required>
                                            @error('allowance')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label>Potongan *</label>
                                            <input type="number" class="form-control @error('deduction') is-invalid @enderror" id="deduction" name="deduction" value="{{ old('deduction') }}" required>
                                            @error('deduction')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label>Bonus *</label>
                                            <input type="number" class="form-control @error('bonus') is-invalid @enderror" id="bonus" name="bonus" value="{{ old('bonus') }}" required>
                                            @error('bonus')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label>THR *</label>
                                            <input type="number" class="form-control @error('thr') is-invalid @enderror" id="thr" name="thr" value="{{ old('thr') }}" required>
                                            @error('thr')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                    </div>

                                    
                                        <div class="text-end">
                                        <button type="submit" class="btn btn-dark">Simpan</button>
                                        </div>
                                </form>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection --}}

@extends('tablar::page')

@section('content')
<div class="page-header d-print-none mb-4">
    <div class="container-xl">
        <div class="row align-items-center">
            <div class="col d-flex align-items-center">
                <a href="{{ route('employees.index') }}" class="btn btn-dark d-flex align-items-center" style="margin-left: 30px;">
                    <i class="ti ti-arrow-left"></i>
                </a>
                
                    <h2 class="page-title mb-0">Tambah Karyawan</h2>
                
            </div>
        </div>
    </div>
</div>


<div class="page-body">
    <div class="container-xl">
        <div class="card shadow-sm border-0">
            <div class="card-body px-5 py-4">
                <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
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

                    <div class="text-center mb-5">
                    <div class="position-relative d-inline-block">
                        @if ($user->photo)
                            <img id="previewImage" src="{{ asset('storage/'.$user->photo) }}" alt="Profile" 
                                 class="rounded-3 shadow-sm border" width="150" height="150"
                                 style="object-fit: cover;">
                        @else
                            <div id="previewImage"
                                 class="rounded-3 shadow-sm bg-light d-flex align-items-center justify-content-center"
                                 style="width:150px; height:150px;">
                                 <i class="ti ti-user" style="font-size: 64px; color:#aaa;"></i>
                            </div>
                        @endif
                        <label for="photo"
                               class="btn btn-sm btn-dark position-absolute bottom-0 end-0 translate-middle rounded-circle"
                               title="Ganti Foto">
                            <i class="ti ti-camera"></i>
                        </label>
                    </div>
                    <input type="file" id="photo" name="photo" class="d-none" accept="image/*">
                </div>
                    <div class="mb-3">
                        <small class="text-danger fw-semibold">
                            * : Wajib diisi
                        </small>
                    </div>

                    {{-- ========== SECTION 1: INFORMASI PRIBADI ========== --}}
                    <div class="section-block mb-5">
                        <h3 class="fw-semibold mb-3 border-bottom pb-2">🧍 Informasi Pribadi</h3>
                        <div class="row g-4">
                            <div class="col-md-5">
                                <label class="form-label required">Nama Lengkap</label>
                                <input type="text" name="fullname" class="form-control @error('fullname') is-invalid @enderror"  value="{{ old('fullname') }}" required>
                                @error('fullname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">Nama Panggilan</label>
                                <input type="text" name="nickname" class="form-control @error('nickname') is-invalid @enderror" value="{{ old('nickname') }}">
                                @error('nickname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label class="form-label required">Jenis Kelamin</label>
                                <select name="gender" class="form-select" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="1" {{ old('gender') == '1' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="2" {{ old('gender') == '2' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label required">Tempat Lahir</label>
                                <input type="text" name="birth_place" class="form-control @error('birth_place') is-invalid @enderror" value="{{ old('birth_place') }}">
                                @error('birth_place')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label required">Tanggal Lahir</label>
                                            <input type="date" name="birth_date" class="form-control" required
                                                value="{{ old('birth_date') }}"
                                                pattern="\d{4}-\d{2}-\d{2}" placeholder="YYYY-MM-DD">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label required">Agama</label>
                                <select name="religion_id" class="form-select select2" required>
                                    <option value="">-- Pilih Agama --</option>
                                    @foreach($religions as $religion)
                                        <option value="{{ $religion->id }}" {{ old('religion_id') == $religion->id ? 'selected' : '' }}>
                                            {{ $religion->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('religion_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label required">Nomor KTP</label>
                                <input type="text" name="identity_number" class="form-control @error('identity_number') is-invalid @enderror" value="{{ old('identity_number') }}" required>
                                @error('identity_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">NPWP</label>
                                <input type="text" name="npwp" class="form-control @error('npwp') is-invalid @enderror" value="{{ old('npwp') }}">
                                @error('npwp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                            <label class="form-label">Status Pernikahan :</label>
                                            <select name="marital_status" class="form-select">
                                                <option value="">-- Pilih Status --</option>
                                                <option value="1">Lajang</option>
                                                <option value="2">Menikah</option>
                                                <option value="3">Duda</option>
                                                <option value="4">Janda</option>
                                            </select>
                            </div>
                        </div>
                    </div>

                    {{-- ========== SECTION 2: KONTAK & ALAMAT ========== --}}
                    <div class="section-block mb-5">
                        <h3 class="fw-semibold mb-3 border-bottom pb-2">📞 Kontak & Alamat</h3>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label required">Nomor Telepon</label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required">Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label required">Alamat Lengkap</label>
                                <textarea name="address" rows="2" class="form-control @error('address') is-invalid @enderror" required>{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row g-4 mt-2">
                            <div class="col-md-6">
                                <label class="form-label required">Provinsi</label>
                                <select name="province_id" id="province" 
                                        class="form-select select2 @error('province_id') is-invalid @enderror" 
                                        required>
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
                                <select name="city_id" id="city" class="form-select select2" required>
                                    <option value="">-- Pilih Kota --</option>
                                    
                                </select>
                                @error('city_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-5">
                                <label class="form-label required">Kecamatan</label>
                                <select name="district_id" id="district" class="form-select select2" required>
                                    <option value="">-- Pilih Kecamatan --</option>
                                    
                                </select>
                                @error('district_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-5">
                                <label class="form-label required">Kelurahan</label>
                                <select name="sub_district_id" id="sub_district" class="form-select select2" required>
                                    <option value="">-- Pilih Kelurahan --</option>
                                    
                                </select>
                                @error('sub_district_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label class="form-label required">Kode Pos</label>
                                <select name="postal_code_id" id="postal_code" class="form-select select2" required>
                                    <option value="">-- Pilih Kode Pos --</option>
                                    
                                </select>
                                @error('postal_code_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="section-block mb-5">
                        <h3 class="fw-semibold mb-3 border-bottom pb-2">🏦 Data Bank</h3>
                        <p class="small text-muted mb-3">Diperlukan bila terjadi pengembalian dana</p>
                        <div class="row g-4">
                            <div class="col-md-4">
                                <label class="form-label" for="bank_id">Nama Bank</label>
                                <select id="bank_id" name="bank_id" class="form-select">
                                    <option value="">Pilih Bank</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Nomor Rekening</label>
                                <input type="text" id="account_number" name="account_number" class="form-control @error('account_number') is-invalid @enderror" value="{{ old('account_number') }}">
                                @error('account_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Atas Nama</label>
                                <input type="text" id="account_holder" name="account_holder" class="form-control @error('account_holder') is-invalid @enderror" value="{{ old('account_holder') }}">
                                @error('account_holder')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- ========== SECTION 4: DATA KEPEGAWAIAN ========== --}}
                    <div class="section-block mb-5">
                        <h3 class="fw-semibold mb-3 border-bottom pb-2">💼 Data Kepegawaian</h3>
                        <div class="row g-4">
                            <div class="col-md-4">
                                <label class="form-label required">NIK</label>
                                <input type="text" id="nik" name="nik" class="form-control" required readonly>
                                @error('nik')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label required">Status Karyawan</label>
                                <select name="employment_status" class="form-select" value="{{ old('employment_status') }}" required>
                                    <option value="">-- Pilih Status --</option>
                                    <option value="Tetap" {{ old('employment_status') == 'Tetap' ? 'selected' : '' }}>Tetap</option>
                                    <option value="Kontrak" {{ old('employment_status') == 'Kontrak' ? 'selected' : '' }}>Kontrak</option>
                                    <option value="Magang" {{ old('employment_status') == 'Magang' ? 'selected' : '' }}>Magang</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                            <label class="form-label">Tanggal Mulai Kerja :</label>
                                            <input type="date" name="start_date" class="form-control" 
                                                value="{{ old('start_date') }}"
                                                pattern="\d{4}-\d{2}-\d{2}" placeholder="YYYY-MM-DD">
                                        </div>
                            
                            <div class="col-md-12">
                                <label class="form-label required" for="role">Posisi :</label>
                                <select class="form-select select2" name="role[]" multiple required>
                                    @foreach (config('employee_roles.roles') as $role)
                                        <option value="{{ $role }}" 
                                            {{ in_array($role, old('role', [])) ? 'selected' : '' }}>
                                            {{ ucfirst($role) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="section-block mb-5"> 
                                <h3 class="fw-semibold mb-3 border-bottom pb-2">📎 Dokumen Karyawan</h3>   
                                <div class="row g-4">
                                    <div class="col-md-6 mb-3">
                                        <label for="contract_letter_file" class="required">Upload Surat Perjanjian Kerja (PDF)</label>
                                        <input type="file" name="contract_letter_file" class="form-control" accept="application/pdf" required>
                                        @error('contract_letter_file')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="training_certificate">Upload Sertifikat (kalau ada)</label>
                                        <input type="file" name="training_certificate" class="form-control" accept="application/pdf">
                                        @error('training_certificate')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ========== SECTION 5: PENGHASILAN ========== --}}
                    <div class="section-block mb-4">
                        <h3 class="fw-semibold mb-3 border-bottom pb-2">💰 Data Penghasilan</h3>
                        <div class="row g-4">
                            <div class="col-md">
                                <label class="form-label">Gaji Pokok</label>
                                <input type="number" id="basic_salary" name="basic_salary" class="form-control @error('basic_salary') is-invalid @enderror" value="{{ old('basic_salary') }}" required>
                                @error('basic_salary')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md">
                                <label class="form-label">Tunjangan</label>
                                <input type="number" name="allowance" class="form-control" value="{{ old('allowance') }}">
                            </div>
                            <div class="col-md">
                                <label class="form-label">Potongan</label>
                                <input type="number" name="deduction" class="form-control" value="{{ old('deduction') }}">
                            </div>
                            <div class="col-md">
                                <label class="form-label">Bonus</label>
                                <input type="number" name="bonus" class="form-control" value="{{ old('bonus') }}">
                            </div>
                            <div class="col-md">
                                <label class="form-label">THR</label>
                                <input type="number" name="thr" class="form-control" value="{{ old('thr') }}">
                            </div>
                        </div>
                    </div>

                    {{-- SUBMIT --}}
                    <div class="text-end mt-5">
                        <button type="submit" class="btn btn-dark px-4">
                            <i class="ti ti-device-floppy me-1"></i> Simpan Data
                        </button>
                    </div>
                </form>

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
                width: '100%',
                allowClear: true
            });
        });
    </script>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('{{ route('employees.generateNik') }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('nik').value = data.nik;
        })
        .catch(error => console.error('Error:', error));
});
</script>


    <script>
        document.getElementById('photo').addEventListener('change', function (event) {
        const input = event.target;
        const file = input.files[0];
        const previewContainer = document.getElementById('previewImage');

        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                // Jika sebelumnya preview berupa ikon <div>, ganti jadi <img>
                if (previewContainer.tagName.toLowerCase() === 'div') {
                    const img = document.createElement('img');
                    img.id = 'previewImage';
                    img.src = e.target.result;
                    img.className = 'border rounded-3 shadow-sm';
                    img.width = 150;
                    img.height = 150;
                    img.style.objectFit = 'cover';
                    previewContainer.replaceWith(img);
                } else {
                    previewContainer.src = e.target.result;
                }
            };
            reader.readAsDataURL(file);
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
    // Ambil old value dari Blade (jika ada)
    const oldBankId = "{{ old('bank_id') }}";

    fetch(`${window.location.origin}/api/banks`)
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('bank_id');
            data.forEach(bank => {
                const option = document.createElement('option');
                option.value = bank.id;
                option.text = `${bank.name} (${bank.code})`;

                // Cek apakah bank.id sama dengan oldBankId
                if (bank.id == oldBankId) {
                    option.selected = true;
                }

                select.appendChild(option);

                $('#bank_id').val(oldBankId).trigger('change');
            });
        });
});
</script>
@endpush