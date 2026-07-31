<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AttendanceOvertime extends Model
{
    use HasUuids;

    protected $fillable = [
        'attendance_id',
        'start_time',
        'start_photo',
        'start_lat',
        'start_lng',
        'end_time',
        'end_photo',
        'end_lat',
        'end_lng',
        'work_minutes',
        'reason',
        'type',
        'status',
        'approved_by',
        'approved_at',
        'approval_note',
        'hourly_rate',
        'overtime_pay',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
    public function getDurationAttribute(): string
{
    $hour = intdiv($this->work_minutes ?? 0, 60);
    $minute = ($this->work_minutes ?? 0) % 60;

    return "{$hour}j {$minute}m";
}
}