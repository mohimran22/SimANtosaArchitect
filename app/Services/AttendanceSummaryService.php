<?php

namespace App\Services;

use App\Services\AttendanceOvertimeService;
use App\Models\Employee;
use Carbon\Carbon;

class AttendanceSummaryService
{
    public function __construct(
        protected AttendanceOvertimeService $overtimeService
    ) {}
    public function summaries(int $month, int $year): array
    {
        $employees = Employee::with([
            'attendances' => function ($q) use ($month, $year) {
                $q->whereMonth('attendance_date', $month)
                  ->whereYear('attendance_date', $year);
            }
        ])->get();

        $result = [];

        $workingDays = $this->workingDays($month, $year);

        foreach ($employees as $employee) {

            $summary = [

                'H' => 0,
                'TL A' => 0,
                'TL B' => 0,
                'TL C' => 0,

                'DL' => 0,
                'I' => 0,
                'S' => 0,
                'C' => 0,
                'A' => 0,

                'total_hari_kerja' => 0,
                'total_hari_kehadiran' => 0,

                'kehadiran' => 0,
                'ketepatan_waktu' => 0,

                'total_jam_lembur' => 0,

                'keterangan' => '',
            ];

            foreach ($employee->attendances as $attendance) {

                if (isset($summary[$attendance->attendance_code])) {
                    $summary[$attendance->attendance_code]++;
                }

                switch ($attendance->status) {

                    case 'business_trip':
                        $summary['DL']++;
                        break;

                    case 'permission':
                        $summary['I']++;
                        break;

                    case 'sick':
                        $summary['S']++;
                        break;

                    case 'leave':
                        $summary['C']++;
                        break;

                    case 'alpha':
                        $summary['A']++;
                        break;
                }

            }

            $summary['total_hari_kerja'] =
                collect([
                    'H',
                    'TL A',
                    'TL B',
                    'TL C',
                    'DL',
                    'I',
                    'S',
                    'C',
                    'A',
                ])->sum(fn ($code) => $summary[$code]);

            $summary['total_hari_kehadiran'] =
                $summary['H']
                + $summary['TL A']
                + $summary['TL B']
                + $summary['TL C']
                + $summary['DL'];

            $summary['total_jam_lembur'] =
                $this->overtimeService->totalMinutes(
                    $employee,
                    $month,
                    $year
                );

            if ($summary['total_hari_kerja'] > 0) {
                $summary['kehadiran'] = round(
                    ($summary['total_hari_kehadiran']
                        / $summary['total_hari_kerja']) * 100,
                    1
                );
            }

            if ($summary['total_hari_kehadiran'] > 0) {

                $tepatWaktu =
                    $summary['H']
                    + $summary['TL A']
                    + $summary['TL B']
                    + $summary['TL C'];

                $summary['ketepatan_waktu'] = round(
                    ($tepatWaktu / $summary['total_hari_kehadiran']) * 100,
                    1
                );

            }

            $result[$employee->id] = $summary;
        }

        return $result;
    }

    private function workingDays(int $month, int $year): int
    {
        $date = Carbon::create($year, $month, 1);

        $count = 0;

        while ($date->month == $month) {

            if (!$date->isWeekend()) {
                $count++;
            }

            $date->addDay();
        }

        return $count;
    }
}