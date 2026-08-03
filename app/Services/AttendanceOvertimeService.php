<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\AttendanceOvertime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AttendanceOvertimeService
{
    public function start(Request $request): void
    {
        $employee = Auth::user()->employee;

        $attendance = Attendance::with('overtime')
            ->where('employee_id', $employee->id)
            ->whereDate('attendance_date', today())
            ->firstOrFail();

        if ($attendance->overtime) {
            throw new \Exception('Lembur sudah dimulai.');
        }

        DB::transaction(function () use ($attendance, $request) {

            AttendanceOvertime::create([

                'id' => Str::uuid(),

                'attendance_id' => $attendance->id,

                'start_time' => now(),

                'start_photo' => $this->savePhoto($request->photo),

                'start_lat' => $request->start_lat,

                'start_lng' => $request->start_lng,

                'type' => $this->determineType(),

            ]);

        });
    }

    public function finish(Request $request): void
    {
        $employee = Auth::user()->employee;

        $attendance = Attendance::with('overtime')
            ->where('employee_id', $employee->id)
            ->whereDate('attendance_date', today())
            ->firstOrFail();

        $overtime = $attendance->overtime;

        if (!$overtime) {
            throw new \Exception('Belum mulai lembur.');
        }

        if ($overtime->end_time) {
            throw new \Exception('Lembur sudah selesai.');
        }

        $overtime->end_time = now();

        $overtime->work_minutes = $this->calculateWorkMinutes(
            $overtime->start_time,
            $overtime->end_time
        );

        $overtime->end_photo = $this->savePhoto($request->photo);

        $overtime->end_lat = $request->end_lat;
        $overtime->end_lng = $request->end_lng;

        $overtime->status = 'pending';

        $overtime->save();
    }

    public function calculateWorkMinutes($start, $end): int
    {
        return intdiv(
            (int) $start->diffInSeconds($end),
            60
        );
    }

    public function determineType(): string
    {
        return now()->isWeekend()
            ? 'holiday'
            : 'weekday';
    }

    private function savePhoto(string $base64): string
    {
        $image = preg_replace('/^data:image\/\w+;base64,/', '', $base64);

        $image = str_replace(' ', '+', $image);

        $filename = 'attendance/overtimes/' . uniqid() . '.jpg';

        Storage::disk('public')->put(
            $filename,
            base64_decode($image)
        );

        return $filename;
    }
        public function totalMinutes(Employee $employee, int $month, int $year): int
    {
        return AttendanceOvertime::query()

            ->whereHas('attendance', function ($q) use ($employee, $month, $year) {

                $q->where('employee_id', $employee->id)
                  ->whereMonth('attendance_date', $month)
                  ->whereYear('attendance_date', $year);

            })

            ->where('status', 'approved')

            ->sum('work_minutes');
    }
}