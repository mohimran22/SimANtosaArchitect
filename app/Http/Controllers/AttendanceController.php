<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        $request->validate([
            'photo' => 'required|string',
            'check_in_lat' => 'required|numeric',
            'check_in_lng' => 'required|numeric',
        ]);
        $employee = auth()->user()->employee;

        if (!$employee) {
            return back()->with('error', 'Data karyawan tidak ditemukan.');
        }

        $today = Carbon::today();

        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('attendance_date', $today)
            ->first();

        if ($attendance) {
            return back()->with('warning', 'Anda sudah melakukan absensi hari ini.');
        }

        $photoPath = null;

        if ($request->filled('photo')) {

            $image = $request->photo;

            $image = preg_replace('/^data:image\/\w+;base64,/', '', $image);
            $image = str_replace(' ', '+', $image);

            $filename = 'attendance/checkin/' . Str::uuid() . '.jpg';

            Storage::disk('public')->put(
                $filename,
                base64_decode($image)
            );

            $photoPath = $filename;
        }

        Attendance::create([
            'employee_id'      => $employee->id,
            'attendance_date'  => $today,
            'check_in'         => now(),
            'status'           => 'present',
            'check_in_photo'   => $photoPath,
            'check_in_lat'     => $request->check_in_lat,
            'check_in_lng'     => $request->check_in_lng,
        ]);

        return back()->with('success', 'Berhasil melakukan absensi masuk.');
    }

    public function checkOut(Request $request)
    {
        $request->validate([
            'photo' => 'required|string',
            'check_out_lat' => 'required|numeric',
            'check_out_lng' => 'required|numeric',
        ]);

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

        // Simpan foto
        $photoPath = null;

        if ($request->filled('photo')) {

            $image = $request->photo;

            $image = preg_replace('/^data:image\/\w+;base64,/', '', $image);
            $image = str_replace(' ', '+', $image);

            $filename = 'attendance/checkout/' . Str::uuid() . '.jpg';

            Storage::disk('public')->put(
                $filename,
                base64_decode($image)
            );

            $photoPath = $filename;
        }

        $attendance->update([
            'status'           => 'go_home',
            'check_out'        => now(),
            'check_out_photo'  => $photoPath,
            'check_out_lat'    => $request->check_out_lat,
            'check_out_lng'    => $request->check_out_lng,
        ]);
        app(AttendanceService::class)->calculate($attendance);
        return back()->with('success', 'Berhasil melakukan absensi pulang.');
    }
}