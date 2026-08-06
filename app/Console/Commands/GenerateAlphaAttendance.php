<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateAlphaAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:generate-alpha';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate alpha attendance for employees who have not checked in';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $today = today();
        if ($today->isWeekend()) {
            $this->info('Weekend. Generate Alpha dibatalkan.');
            return self::SUCCESS;
        }
        Employee::query()
            ->chunkById(100, function ($employees) use ($today) {

                foreach ($employees as $employee) {

                    $exists = Attendance::where('employee_id', $employee->id)
                        ->whereDate('attendance_date', $today)
                        ->exists();

                    if ($exists) {
                        continue;
                    }

                    Attendance::create([
                        'license_id'      => $employee->license_id,
                        'employee_id'     => $employee->id,
                        'attendance_date' => $today,
                        'status'          => 'alpha',
                    ]);
                }

            });

        $this->info('Generate Alpha Success.');

        return self::SUCCESS;
    }
}
