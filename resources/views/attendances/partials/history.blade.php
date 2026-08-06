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
<div class="row g-3 align-items-end mb-3" id="historyFilter">

    <div class="col-12 col-md-3">
        <label class="form-label">Dari Tanggal</label>
        <input
            type="date"
            class="form-control"
            id="history_start_date"
            value="{{ $filters['start_date'] }}">
    </div>

    <div class="col-12 col-md-3">
        <label class="form-label">Sampai Tanggal</label>
        <input
            type="date"
            class="form-control"
            id="history_end_date"
            value="{{ $filters['end_date'] }}">
    </div>

    <div class="col-12 col-md-2">
        <label class="form-label">Status</label>
            <select class="form-select" id="history_status">
                <option value=""
                    {{ empty($filters['attendance_code']) ? 'selected' : '' }}>
                    Semua Status
                </option>

                <option value="H"
                    {{ $filters['attendance_code']=='H' ? 'selected' : '' }}>
                    Hadir
                </option>

                <option value="TL A"
                    {{ $filters['attendance_code']=='TL A' ? 'selected' : '' }}>
                    TL A
                </option>

                <option value="TL B"
                    {{ $filters['attendance_code']=='TL B' ? 'selected' : '' }}>
                    TL B
                </option>

                <option value="TL C"
                    {{ $filters['attendance_code']=='TL C' ? 'selected' : '' }}>
                    TL C
                </option>

                <option value="DL"
                    {{ $filters['attendance_code']=='DL' ? 'selected' : '' }}>
                    Dinas Luar
                </option>

                <option value="I"
                    {{ $filters['attendance_code']=='I' ? 'selected' : '' }}>
                    Izin
                </option>

                <option value="S"
                    {{ $filters['attendance_code']=='S' ? 'selected' : '' }}>
                    Sakit
                </option>

                <option value="C"
                    {{ $filters['attendance_code']=='C' ? 'selected' : '' }}>
                    Cuti
                </option>

                <option value="A"
                    {{ $filters['attendance_code']=='A' ? 'selected' : '' }}>
                    Alpha
                </option>
            </select>
    </div>

    <div class="col-12 col-md-4">
        <div class="d-flex gap-2 history-action">
            <button
                class="btn btn-dark flex-fill btn-filter-history"
                data-employee="{{ $employee->id }}">
                <i class="ti ti-filter me-1"></i> Filter
            </button>

            <button
                class="btn btn-outline-dark flex-fill btn-reset-history"
                data-employee="{{ $employee->id }}">
                <i class="ti ti-refresh me-1"></i> Reset
            </button>

            <a href="#"
            class="btn btn-danger btn-history-pdf"
            target="_blank">

                <i class="ti ti-file-type-pdf"></i>
                PDF
            </a>
        </div>
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
            <th width="80">Tanggal</th>
            <th width="80">Masuk</th>
            <th width="80">Pulang</th>
            <th width="60">Status</th>
            <th width="80">Jam Kerja</th>
            <th width="80">Lembur</th>
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
            {{ $attendance->work_duration }}
        </td>
        <td>{{ $attendance->overtime?->duration ?? '-' }}</td>
        <td class="text-center">
            <div class="d-flex justify-content-center align-items-center gap-2 flex-wrap">
                <button
                    class="btn btn-sm btn-dark btn-detail"
                    data-id="{{ $attendance->id }}"
                    title="Detail">
                    <i class="ti ti-eye"></i>
                </button>

                @role('Super-Admin')
                    <button
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
                    </button>

                    {{-- <button
                        class="btn btn-sm btn-dark btn-revisions"
                        data-id="{{ $attendance->id }}"
                        title="Riwayat Revisi">
                        <i class="ti ti-history"></i>
                    </button> --}}
                @endrole
            </div>
        </td>
    </tr>

    @endforeach

    </tbody>

</table>

</div>

@endif