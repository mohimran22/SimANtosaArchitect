@extends('tablar::page')

@section('content')
<div class="page-header d-print-none mb-4">
    <div class="container-xl">
        <div class="row align-items-center">
            <div class="col d-flex align-items-center">
                <a href="{{ route('attendances.index') }}" class="btn btn-dark d-flex align-items-center" style="margin-left: 30px;">
                    <i class="ti ti-arrow-left"></i>
                </a>
                
                    <h2 class="page-title mb-0">Tambah Data Absensi</h2>
                
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="card shadow-sm border-0">
            <div class="card-body px-5 py-4">
                <form action="{{ route('attendances.store') }}" method="POST">
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

                    <input type="hidden" id="attendance_id" value="{{ $attendance->id }}">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">
                                Tanggal
                            </label>
                            <input
                                type="date"
                                class="form-control"
                                name="attendance_date"
                                value="">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                Jam Masuk
                            </label>

                            <input
                                type="time"
                                class="form-control"
                                name="check_in"
                                value="">

                        </div>

                        <div class="col-md-4 mb-3">

                            <label class="form-label">

                                Jam Pulang

                            </label>

                            <input
                                type="time"
                                class="form-control"
                                name="check_out"
                                value="">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">
                                Catatan
                            </label>
                            <textarea
                                class="form-control"
                                rows="3"
                                name="notes">{{ $attendance->notes }}</textarea>
                        </div>
                        <div class="mb-3">

                            <label class="form-label required">
                                Alasan Perubahan
                            </label>

                            <textarea
                                name="edit_reason"
                                rows="3"
                                class="form-control"
                                placeholder="Contoh: Karyawan lupa checkout karena aplikasi force close."
                                required></textarea>

                        </div>
                    </div>
                    <div class="text-end">
                        <button class="btn btn-secondary btn-back-history" data-employee="{{ $attendance->employee_id }}">
                            <i class="ti ti-arrow-left"></i>
                            Kembali
                        </button>
                        <button type="submit" class="btn btn-dark">
                            <i class="ti ti-device-floppy"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection