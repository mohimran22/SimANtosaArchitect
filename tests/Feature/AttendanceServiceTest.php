<?php

use App\Models\Attendance;
use App\Models\Employee;
use App\Services\AttendanceService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('calculates attendance code correctly', function () {

    $employee = Employee::find('ededd83f-5928-49de-bd7f-ee3bb3c39f04');

    $attendance = Attendance::create([
        'employee_id' => $employee->id,
        'attendance_date' => '2026-07-05',
        'check_in' => '2026-07-05 07:58:09',
        'check_out' => '2026-07-05 16:07:09',
    ]);

    app(AttendanceService::class)->calculate($attendance);

    $attendance->refresh();

    expect($attendance->attendance_code)->toBe('H');
    expect($attendance->work_minutes)->toBe(489);
    expect($attendance->is_full_work)->toBeTrue();
});
