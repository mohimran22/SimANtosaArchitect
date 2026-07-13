<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendances = Attendance::where('employee_id', auth()->user()->id)
            ->latest('attendance_date')
            ->paginate(20);

        return view('attendances.index', compact('attendances'));
    }

    public function checkIn(Request $request)
    {
        $employee = auth()->user()->employee;

        if (!$employee) {
            return back()->with('error', 'Data karyawan tidak ditemukan.');
        }

        $today = Carbon::today();

        // Sudah check in hari ini?
        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('attendance_date', $today)
            ->first();

        if ($attendance) {
            return back()->with('warning', 'Anda sudah melakukan absensi hari ini.');
        }

        Attendance::create([
            'employee_id'     => $employee->id,
            'attendance_date' => $today,
            'check_in'        => now(),
            'status'          => 'present',
        ]);

        return back()->with('success', 'Berhasil melakukan absensi masuk.');
    }

    public function checkOut(Request $request)
    {
        $employee = auth()->user()->employee;

        if (!$employee) {
            return back()->with('error', 'Data karyawan tidak ditemukan.');
        }

        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('attendance_date', today())
            ->first();

        if (!$attendance) {
            return back()->with('error', 'Anda belum melakukan absensi masuk.');
        }

        if ($attendance->check_out) {
            return back()->with('warning', 'Anda sudah melakukan absensi pulang.');
        }

        $attendance->update([
            'status'    => 'go_home',
            'check_out' => now(),
        ]);

        return back()->with('success', 'Berhasil melakukan absensi pulang.');
    }
}