@extends('tablar::page')

@section('content')
<div class="page-header d-print-none mb-4">
    <div class="container-xl">
        <div class="row align-items-center">
            <div class="col d-flex align-items-center">
                <a href="{{ route('attendances.index') }}" class="btn btn-dark d-flex align-items-center">
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

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">
                                    Karyawan
                                </label>

                                <select
                                    name="employee_id"
                                    id="employee_id"
                                    class="form-select select2"
                                    required>

                                    <option value="">Pilih Karyawan</option>

                                    @foreach($employees as $employee)
                                        <option 
                                            value="{{ $employee->id }}"
                                                @selected(old('employee_id') == $employee->id)>
                                                {{ $employee->user->fullname }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">
                                    Tanggal
                                </label>
                                <input
                                    type="date"
                                    class="form-control"
                                    name="attendance_date"
                                    value="{{ old('attendance_date') }}">
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-4 mb-3">
                                <label class="form-label required">
                                    Status
                                </label>

                                <select
                                    name="status"
                                    id="status"
                                    class="form-select select2"
                                    required>

                                    <option value="present">Hadir</option>
                                    <option value="permission">Izin</option>
                                    <option value="sick">Sakit</option>
                                    <option value="leave">Cuti</option>
                                    <option value="business_trip">Dinas Luar</option>
                                    <option value="alpha">Alpha</option>

                                </select>
                            </div>

                            <div class="col-md-4 mb-3 time-section">
                                <label class="form-label">
                                    Jam Masuk
                                </label>

                                <input
                                    type="time"
                                    class="form-control"
                                    name="check_in"
                                    value="{{ old('check_in') }}">
                            </div>

                            <div class="col-md-4 mb-3 time-section">
                                <label class="form-label">
                                    Jam Pulang
                                </label>

                                <input
                                    type="time"
                                    class="form-control"
                                    name="check_out"
                                    value="{{ old('check_out') }}">
                            </div>

                        </div>
                         
                        <div class="col-12 mb-3">
                            <label class="form-label">
                                Catatan
                            </label>
                            <textarea
                                class="form-control"
                                rows="3"
                                name="notes">{{ old('notes')}}</textarea>
                        </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-dark">
                            <i class="ti ti-device-floppy"></i>
                            Simpan
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
$('#employee_id').select2({
    placeholder: 'Pilih Karyawan',
    width: '100%'
});

$('#status').select2({
    minimumResultsForSearch: Infinity,
    width: '100%'
});

function toggleTimeSection() {

    const isPresent = $('#status').val() === 'present';

    $('.time-section').toggle(isPresent);

    $('input[name="check_in"]').prop('required', isPresent);
    $('input[name="check_out"]').prop('required', isPresent);
}

$('#status').on('change', toggleTimeSection);

toggleTimeSection();
</script>
@endpush
