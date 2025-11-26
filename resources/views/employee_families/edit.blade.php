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
                        Data Keluarga
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                  
                        <a href=" {{ route("employees.index") }} " class="btn btn-primary d-none d-sm-inline-block" >
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
                                Edit Data Keluarga
                            </p>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('employee_families.update', $families->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <input type="hidden" name="employee_id" value="{{ $employee->id }}">

                                <div class="mb-3">
                                    <label class="form-label">Nama Karyawan</label>
                                    <input type="text" class="form-control" value="{{ $employee->fullname }}" disabled>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nama Lengkap</label>
                                        <input type="text" name="name" class="form-control" value="{{ old('name', $families->name) }}" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Hubungan dengan karyawan</label>
                                        <select name="relationship" class="form-select">
                                            <option value="">-- Pilih salah satu --</option>
                                            <option value="1" {{ $families->relationship == 1 ? 'selected' : '' }}>Suami</option>
                                            <option value="2" {{ $families->relationship == 2 ? 'selected' : '' }}>Istri</option>
                                            <option value="3" {{ $families->relationship == 3 ? 'selected' : '' }}>Anak</option>
                                            <option value="4" {{ $families->relationship == 4 ? 'selected' : '' }}>Orang Tua</option>
                                            <option value="5" {{ $families->relationship == 5 ? 'selected' : '' }}>Famili Lain</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Jenis Kelamin</label>
                                        <select name="gender" class="form-select">
                                            <option value="">-- Pilih kelamin --</option>
                                            <option value="1" {{ $families->gender == 1 ? 'selected' : '' }}>Laki - Laki</option>
                                            <option value="2" {{ $families->gender == 2 ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                                <label>Tanggal Lahir *</label>
                                                <input type="date" name="birth_date" class="form-control" required
                                                    value="{{ old('birth_date', $families->birth_date) }}"
                                                    pattern="\d{4}-\d{2}-\d{2}" placeholder="YYYY-MM-DD">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Pekerjaan</label>
                                        <input type="text" name="job" class="form-control" value="{{ old('job', $families->job) }}">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Telepon Kantor</label>
                                        <input type="number" name="job_phone" class="form-control" value="{{ old('job_phone', $families->job_phone) }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                            <label for="last_education_level" class="form-label">Jenjang Pendidikan <code>*</code></label>
                                            <select name="last_education_level" class="form-select" required>
                                                <option value="">-- Pilih Jenjang --</option>
                                                <option value="SD" {{ $families->last_education_level == 'SD' ? 'selected' : '' }}>SD</option>
                                                <option value="SMP" {{ $families->last_education_level == 'SMP' ? 'selected' : '' }}>SMP</option>
                                                <option value="SMA" {{ $families->last_education_level == 'SMA' ? 'selected' : '' }}>SMA</option>
                                                <option value="D3" {{ $families->last_education_level == 'D3' ? 'selected' : '' }}>D3</option>
                                                <option value="S1" {{ $families->last_education_level == 'S1' ? 'selected' : '' }}>S1</option>
                                                <option value="S2" {{ $families->last_education_level == 'S2' ? 'selected' : '' }}>S2</option>
                                                <option value="S3" {{ $families->last_education_level == 'S3' ? 'selected' : '' }}>S3</option>
                                            </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Pekerjaan</label>
                                        <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $families->company_name) }}">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                                <label class="form-label">Nama Sekolah</label>
                                                <input type="text" name="institution_name" class="form-control" value="{{ old('institution_name', $families->institution_name) }}">
                                    </div>
                                </div>


                        <div class="d-flex justify-content-between">
                            <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                        </div>
                    </form>
 

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection