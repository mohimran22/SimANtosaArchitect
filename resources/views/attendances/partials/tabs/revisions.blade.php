@php

use Carbon\Carbon;

$labels = [

    'attendance_date' => 'Tanggal',

    'check_in' => 'Jam Masuk',

    'check_out' => 'Jam Pulang',

    'attendance_code' => 'Status',

    'work_minutes' => 'Jam Kerja',

    'overtime_minutes' => 'Lembur',

    'notes' => 'Catatan',

];

function auditValue($field,$value){

    if(blank($value))
        return '-';

    switch($field){

        case 'attendance_date':
            return Carbon::parse($value)
                ->translatedFormat('d F Y');

        case 'check_in':

        case 'check_out':
            return Carbon::parse($value)->format('H:i');

        case 'work_minutes':

            return floor($value/60).' Jam '.($value%60).' Menit';

        case 'overtime_minutes':

            return floor($value/60).' Jam '.($value%60).' Menit';

        default:

            return $value;
    }

}

@endphp
<div class="card mt-4 shadow-sm border-0">

    <div class="card-header bg-warning text-dark">
        <i class="ti ti-history me-2"></i>
        Riwayat Perubahan
    </div>

    <div class="card-body">
        @if($attendance->revisions->isEmpty())

        <div class="empty">
            Belum ada revisi.
        </div>

        @else

        @foreach($attendance->revisions as $revision)

        <div class="card mb-4">

            <div class="card-body">

                <div class="d-flex">

                    <div class="me-3">

                        <div class="avatar avatar-md bg-primary-lt">

                            {{ substr($revision->editor->fullname,0,1) }}

                        </div>

                    </div>

                    <div class="flex-fill">

                        <div class="d-flex justify-content-between">

                            <div>

                                <strong>
                                    {{ $revision->editor->fullname }}
                                </strong>

                                <div class="text-secondary small">

                                    {{ $revision->edited_at->locale('id')->translatedFormat('d F Y H:i') }}

                                </div>

                            </div>

                            {{-- <span class="badge bg-primary">
                                {{ ucfirst($revision->action) }}
                            </span> --}}
                            <span class="badge bg-primary">
                                Revisi
                            </span>

                        </div>

                        <div class="mt-3">

                            <strong>Alasan</strong>

                            <div class="text-secondary">

                                {{ $revision->edit_reason }}

                            </div>

                        </div>

                        <hr>

                        @foreach($labels as $field => $label)

                            @php
                                $old = $revision->old_data[$field] ?? null;
                                $new = $revision->new_data[$field] ?? null;
                            @endphp

                            @continue($old == $new)

                            <div class="row py-2 border-bottom">

                                <div class="col-md-3 fw-semibold">

                                    {{ $label }}

                                </div>

                                <div class="col-md-4 text-danger">

                                    {{ auditValue($field,$old) }}

                                </div>

                                <div class="col-md-1 text-center">

                                    <i class="ti ti-arrow-right"></i>

                                </div>

                                <div class="col-md-4 text-success fw-bold">

                                    {{ auditValue($field,$new) }}

                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>

            </div>

        </div>

        @endforeach

        @endif
    </div>
</div>