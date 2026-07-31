<div class="mb-3">

    <div class="d-flex align-items-center">

        <div class="avatar avatar-xl me-3">
            @if($employee->user->photo_url)
                <img src="{{ $employee->user->photo_url }}"
                    alt="{{ $employee->user->fullname }}"
                    class="avatar-img">
            @else
                {{ strtoupper(substr($employee->user->fullname,0,1)) }}
            @endif
        </div>

        <div>
            <h3 class="mb-0">
                {{ $employee->user->fullname }}
            </h3>

            <div class="text-secondary">
                {{ $employee->user->roles->pluck('name')->implode(', ') }}
            </div>
        </div>

    </div>

</div>
<div class="row mb-3 align-items-end" id="historyFilter">

    <div class="col-md-3">
        <label class="form-label">Dari Tanggal</label>
        <input
            type="date"
            class="form-control"
            id="history_start_date"
            value="{{ request('start_date') }}">
    </div>

    <div class="col-md-3">
        <label class="form-label">Sampai Tanggal</label>
        <input
            type="date"
            class="form-control"
            id="history_end_date"
            value="{{ request('end_date') }}">
    </div>

    <div class="col-md-2">
        <label class="form-label">Status</label>

        <select class="form-select" id="history_status">
            <option value="">Semua Status</option>
            <option value="H">Hadir</option>
            <option value="TL A">TL A</option>
            <option value="TL B">TL B</option>
            <option value="TL C">TL C</option>
            <option value="DL">Dinas Luar</option>
            <option value="I">Izin</option>
            <option value="S">Sakit</option>
            <option value="C">Cuti</option>
        </select>
    </div>

    <div class="col-md-2 d-grid">
        <button
            class="btn btn-dark btn-filter-history"
            data-employee="{{ $employee->id }}">
            Filter
        </button>
    </div>

    <div class="col-md-2 d-grid">
        <button
            class="btn btn-outline-dark btn-reset-history"
            data-employee="{{ $employee->id }}">
            Reset
        </button>
    </div>

</div>

<hr>
@if($attendances->isEmpty())

<div class="empty">

    <div class="empty-icon">
        <i class="ti ti-calendar-off fs-1"></i>
    </div>

    <p class="empty-title">
        Belum ada history absensi.
    </p>

</div>

@else

<div class="table-responsive">

<table class="table table-hover table-vcenter">

    <thead>

        <tr>

            <th width="140">Tanggal</th>

            <th width="80">Masuk</th>

            <th width="80">Pulang</th>

            <th width="90">Status</th>

            <th width="120">Jam Kerja</th>

            <th width="90">Lembur</th>

            {{-- <th width="80" class="text-center">
                Detail Absen
            </th> --}}
            <th width="110" class="text-center">
                Aksi
            </th>
        </tr>

    </thead>

    <tbody>

    @foreach($attendances as $attendance)

    @php

        $badge = match($attendance->attendance_code){

            'H' => 'bg-success',

            'TL A' => 'bg-warning',

            'TL B' => 'bg-orange',

            'TL C' => 'bg-danger',

            'DL' => 'bg-info',

            'I' => 'bg-secondary',

            'S' => 'bg-cyan',

            'C' => 'bg-purple',

            default => 'bg-dark'

        };

        $workMinutes = $attendance->work_minutes ?? 0;

        $workHour = floor($workMinutes / 60);

        $workMinute = $workMinutes % 60;

        $overtimeMinutes = $attendance->overtime_minutes ?? 0;

        $overtimeHour = floor($overtimeMinutes / 60);

        $overtimeMinute = $overtimeMinutes % 60;

    @endphp

    <tr>

        <td>

            {{ \Carbon\Carbon::parse($attendance->attendance_date)->translatedFormat('d F Y') }}

        </td>

        <td>

            {{ optional($attendance->check_in)->format('H:i') ?? '-' }}

        </td>

        <td>

            {{ optional($attendance->check_out)->format('H:i') ?? '-' }}

        </td>

        <td>

            <span class="badge {{ $badge }}">

                {{ $attendance->attendance_code ?? '-' }}

            </span>

        </td>

        <td>

            {{ $workHour }}j {{ $workMinute }}m

        </td>

        <td>

            @if($overtimeMinutes)

                {{ $overtimeHour }}j {{ $overtimeMinute }}m

            @else

                -

            @endif

        </td>

        {{-- <td class="text-center">

            <button
                class="btn btn-sm btn-outline-dark btn-detail"
                data-id="{{ $attendance->id }}"
                title="Detail Absen">

                <i class="ti ti-eye"></i>

            </button>

        </td> --}}
        <td class="text-center">
            <div class="dropdown">

                <button class="btn btn-sm btn-dark" data-bs-toggle="dropdown">
                    <i class="ti ti-dots"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item btn-detail" data-id="{{ $attendance->id }}">
                        <i class="ti ti-eye me-2"></i>
                        Detail
                    </a>
                    @role('Super-Admin')
                    <a class="dropdown-item btn-edit" data-id="{{ $attendance->id }}">
                        <i class="ti ti-edit me-2"></i>
                        Edit
                    </a>
                    <a class="dropdown-item text-danger btn-delete" data-id="{{ $attendance->id }}">
                        <i class="ti ti-trash me-2"></i>
                        Hapus
                    </a>
                    @endrole
                    <a class="dropdown-item btn-revisions" data-id="{{ $attendance->id }}">
                        <i class="ti ti-history me-2"></i>
                        Riwayat Revisi
                    </a>
                </div>

            </div>
            {{-- <button
                class="btn btn-sm btn-dark btn-edit"
                data-id="{{ $attendance->id }}"
                title="Edit">

                <i class="ti ti-edit"></i>

            </button>

            <button
                class="btn btn-sm btn-dark btn-delete"
                data-id="{{ $attendance->id }}"
                title="Hapus">

                <i class="ti ti-trash"></i>

            </button> --}}
        </td>
    </tr>

    @endforeach

    </tbody>

</table>

</div>

@endif