<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceOvertime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AttendanceOvertimeController extends Controller
{
    public function start(Request $request)
{
    $employee = Auth::user()->employee;

    $attendance = Attendance::where('employee_id', $employee->id)
        ->whereDate('attendance_date', today())
        ->firstOrFail();

    if ($attendance->overtime) {

        return back()->with('error', 'Lembur sudah dimulai.');

    }

    DB::transaction(function () use ($request, $attendance) {

        AttendanceOvertime::create([

            'id' => Str::uuid(),

            'attendance_id' => $attendance->id,

            'start_time' => now(),

            'start_photo' => $this->savePhoto($request->photo),

            'start_lat' => $request->start_lat,

            'start_lng' => $request->start_lng,

        ]);

    });

    return back()->with('success', 'Lembur dimulai.');
}
    public function finish(Request $request)
{
    $employee = Auth::user()->employee;

    $attendance = Attendance::where('employee_id', $employee->id)
        ->whereDate('attendance_date', today())
        ->firstOrFail();

    $overtime = $attendance->overtime;

    if (!$overtime) {

        return back()->with('error', 'Belum mulai lembur.');

    }

    if ($overtime->end_time) {

        return back()->with('error', 'Lembur sudah selesai.');

    }

    $end = now();

    $minutes = $overtime->start_time->diffInMinutes($end);

    $overtime->update([

        'end_time' => $end,

        'end_photo' => $this->savePhoto($request->photo),

        'end_lat' => $request->end_lat,

        'end_lng' => $request->end_lng,

        'work_minutes' => $minutes,

        'status' => 'pending',

    ]);

    return back()->with('success', 'Lembur selesai.');
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
}
