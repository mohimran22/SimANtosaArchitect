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
                        Pendidikan
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
                                Edit Data Penddikan
                            </p>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('license_holder_workers.store', ) }}" method="POST">
                                @csrf
                            

                                <input type="hidden" name="license_holder_id" value="{{ $license_holder->id }}">

                                <div class="mb-3">
                                    <label class="form-label">Nama Pemilik Lisensi</label>
                                    <input type="text" class="form-control" value="{{ $license_holder->fullname }}" disabled>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Nama Lembaga</label>
                                    <input type="text" name="company_name" class="form-control" value="{{ old('company_name') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Kota</label>
                                    <input type="text" name="city" class="form-control" value="{{ old('city') }}">
                                </div>

                                 <div class="mb-3">
                                    <label class="form-label">Telepon Kantor</label>
                                    <input type="number" name="phone" class="form-control" value="{{ old('phone') }}">
                                </div>

                                 <div class="mb-3">
                                    <label class="form-label">Posisi</label>
                                    <input type="text" name="position" class="form-control" value="{{ old('position') }}">
                                </div>

                                 <div class="mb-3">
                                    <label class="form-label">Status Karyawan</label>
                                    <select name="employment_type" class="form-select">
                                        <option value="">-- Pilih Tipe --</option>
                                        <option value="1">Penuh Waktu</option>
                                        <option value="2">Sampingan</option>
                                        <option value="3">Magang</option>
                                        <option value="4">Pekerja Lepas</option>
                            
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tanggal Mulai Kerja</label>
                                        <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tanggal Berakhir Kerja</label>
                                        <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Masih Bekerja?</label>
                                    <select name="is_current" class="form-select">
                                        <option value="">-- Pilih Tipe --</option>
                                        <option value="1">Masih Bekerja</option>
                                        <option value="0">Sudah Keluar</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                            <label class="form-label">Kompetensi Utama</label>
                                            <input type="text" name="skills_used" class="form-control" value="{{ old('skills_used') }}">
                                </div>

                                <div class="mb-3">
                                            <label class="form-label">Deskripsi Tanggungjawab</label>
                                            <input type="text" name="job_description" class="form-control" value="{{ old('job_description') }}">
                                </div>

                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('license_holders.show', $license_holder->id) }}" class="btn btn-secondary">Batal</a>
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