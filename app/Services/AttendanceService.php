<?php

namespace App\Services;

use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceService
{
    public function calculate(Attendance $attendance): void
    {
        if ($attendance->status !== 'present') {
            $attendance->work_minutes = 0;
                $attendance->attendance_code = match ($attendance->status) {
                    'permission'    => 'I',
                    'sick'          => 'S',
                    'leave'         => 'C',
                    'business_trip' => 'DL',
                    'alpha'         => 'A',
                    default         => null,
                };
            $attendance->is_full_work = false;
            $attendance->notes = null;
            $attendance->save();

            return;
        }
        $workMinutes = $this->calculateWorkMinutes($attendance);

        $attendanceCode = $this->calculateAttendanceCode(
            $attendance,
            $workMinutes
        );

        $attendance->work_minutes = $workMinutes;
        $attendance->attendance_code = $attendanceCode;
        $attendance->is_full_work = $workMinutes >= 480;
        $attendance->notes = $workMinutes < 480
            ? 'Durasi kerja kurang dari 8 jam'
            : null;
        $attendance->save();
    }

    private function calculateWorkMinutes(Attendance $attendance): int
    {
        if (!$attendance->check_in || !$attendance->check_out) {
            return 0;
        }

        return Carbon::parse($attendance->check_in)
            ->diffInMinutes(Carbon::parse($attendance->check_out));
    }

    private function calculateAttendanceCode(
        Attendance $attendance,
        int $workMinutes
    ): ?string {

        if ($attendance->status !== 'present') {
            return null;
        }

        $time = Carbon::parse($attendance->check_in)->format('H:i:s');

        return match (true) {
            $time < '08:00:00'  => 'H',
            $time <= '08:10:00' => 'TL A',
            $time <= '08:20:00' => 'TL B',
            default             => 'TL C',
        };
    }
}