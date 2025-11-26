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
                                Tambah Data Penddikan
                            </p>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('license_holder_educations.store') }}" method="POST">
                                @csrf
                                

                                    <input type="hidden" name="license_holder_id" value="{{ $license_holder->id }}">

                                    <div class="mb-3">
                                        <label class="form-label">Nama Pemilik Lisensi</label>
                                        <input type="text" class="form-control" value="{{ $license_holder->fullname }}" disabled>
                                    </div>

                                <div class="mb-3">
                                        <label for="education_level" class="form-label">Jenjang Pendidikan <code>*</code></label>
                                        <select name="education_level" class="form-select" required>
                                            <option value="">-- Pilih Jenjang --</option>
                                            <option value="SD">SD</option>
                                            <option value="SMP">SMP</option>
                                            <option value="SMA">SMA</option>
                                            <option value="D3">D3</option>
                                            <option value="S1">S1</option>
                                            <option value="S2">S2</option>
                                            <option value="S3">S3</option>
                                        </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Nama Sekolah</label>
                                    <input type="text" name="institution_name" class="form-control" value="{{ old('institution_name') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Jurusan</label>
                                    <input type="text" name="major" class="form-control" value="{{ old('major') }}">
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tahun Masuk</label>
                                        <input type="number" name="start_year" class="form-control" value="{{ old('start_year') }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tahun Lulus</label>
                                        <input type="number" name="end_year" class="form-control" value="{{ old('end_year') }}">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="is_graduated" class="form-select">
                                        <option value="1">Lulus</option>
                                        <option value="0">Belum Lulus</option>
                                    </select>
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
