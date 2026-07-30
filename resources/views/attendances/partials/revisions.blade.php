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
                ->locale('id')
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
<button class="btn btn-secondary mb-4 btn-back-history" data-employee="{{ $attendance->employee_id }}">
    <i class="ti ti-arrow-left"></i>
    Kembali
</button>

<h3>

    Riwayat Perubahan

</h3>

<hr>

@if($revisions->isEmpty())

<div class="empty">

    Belum ada revisi.

</div>

@else
@foreach($revisions as $revision)
<div class="card mb-4 shadow-sm">
<div class="card-body">
<div class="d-flex justify-content-between align-items-start">
<div>
<h3 class="mb-1">
{{ $revision->editor->fullname }}
</h3>
<div class="text-secondary">
<i class="ti ti-clock"></i>
{{ $revision->edited_at->locale('id')->translatedFormat('d F Y H:i') }}
</div>
</div>
<span class="badge bg-primary">
Revisi
</span>
</div>
<hr>
<div>
<strong>
Alasan Perubahan
</strong>
<div class="text-secondary">
{{ $revision->edit_reason }}
</div>
</div>
<hr>

@foreach($labels as $field=>$label)

@php
    $old=$revision->old_data[$field] ?? null;
    $new=$revision->new_data[$field] ?? null;
@endphp

@if($old!=$new)

<div class="mb-4">

<div class="fw-bold mb-2">
    {{ $label }}
</div>

<div class="ps-3 border-start border-4 border-primary">

<div class="text-danger">

{{ auditValue($field,$old) }}

</div>

<div class="my-1">

<i class="ti ti-arrow-down fs-3 text-primary"></i>

</div>

<div class="text-success fw-bold">

{{ auditValue($field,$new) }}

</div>

</div>

</div>

@endif

@endforeach

</div>

</div>

@endforeach
@endif
{{-- @foreach($revisions as $revision)

<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <div>
                <strong>
                    {{ $revision->editor->fullname }}
                </strong>
                <br>
                <small>
                    {{ $revision->edited_at->translatedFormat('d F Y H:i') }}
                </small>
            </div>
            <span class="badge bg-primary">
                Revisi
            </span>
        </div>
        <hr>
        <strong>
            Alasan
        </strong>
        <p>
            {{ $revision->edit_reason }}
        </p>
        @php
            $labels = [
                'check_in' => 'Jam Masuk',
                'check_out' => 'Jam Pulang',
                'attendance_code' => 'Status',
                'work_minutes' => 'Jam Kerja',
                'notes' => 'Catatan',
            ];
        @endphp
        <table class="table table-sm">

<thead>

<tr>

<th>Field</th>

<th>Sebelum</th>

<th></th>

<th>Sesudah</th>

</tr>

</thead>

<tbody>

@foreach($labels as $field=>$label)

@php

$old = $revision->old_data[$field] ?? null;

$new = $revision->new_data[$field] ?? null;

@endphp

@if($old != $new)

<tr>

<td>

{{ $label }}

</td>

<td>

{{ $old }}

</td>

<td>

<i class="ti ti-arrow-right"></i>

</td>

<td>

<strong>

{{ $new }}

</strong>

</td>

</tr>

@endif

@endforeach

</tbody>

</table>
    </div>

</div>

@endforeach

@endif --}}