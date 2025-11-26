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
                                Tambah Data Penddikan
                            </p>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('employee_workers.store') }}" method="POST">
                                @csrf
                                

                                <input type="hidden" name="employee_id" value="{{ $employee->id }}">

                                <div class="mb-3">
                                    <label class="form-label">Nama Pemilik Lisensi</label>
                                    <input type="text" class="form-control" value="{{ $employee->fullname }}" disabled>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Nama Lembaga</label>
                                    <input type="text" name="company_name" class="form-control" value="{{ old('company_name') }}" required>
                                </div>

                                 <div class="mb-3">
                                    <label class="form-label">Posisi</label>
                                    <input type="text" name="last_position" class="form-control" value="{{ old('last_position') }}">
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
                                            <label class="form-label">Gaji Terakhir</label>
                                            <input type="number" name="last_salary" class="form-control" value="{{ old('last_salary') }}">
                                </div>

                                <div class="mb-3">
                                            <label class="form-label">Alasan Keluar</label>
                                            <input type="text" name="reason_for_leaving" class="form-control" value="{{ old('reason_for_leaving') }}">
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