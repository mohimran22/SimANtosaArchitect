<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;
    
    protected $fillable = [
        'employee_id',
        'attendance_date',
        'check_in',
        'check_out',
        'status',
        'check_in_photo',
        'check_out_photo',
        'check_in_lat',
        'check_in_lng',
        'check_out_lat',
        'check_out_lng',
        'work_minutes', 
        'attendance_code', 
        'is_full_work',
        'notes',
        'system_notes' 
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'check_in' => 'datetime',
        'check_out' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    public function revisions()
{
    return $this->hasMany(AttendanceRevision::class)
                ->latest('edited_at');
}
public function overtime()
{
    return $this->hasOne(AttendanceOverTime::class, 'attendance_id');
}
public function getWorkDurationAttribute(): string
{
    $hour = intdiv($this->work_minutes ?? 0, 60);
    $minute = ($this->work_minutes ?? 0) % 60;

    return "{$hour}j {$minute}m";
}
}