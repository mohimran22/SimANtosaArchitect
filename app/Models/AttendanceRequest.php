<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AttendanceRequest extends Model
{
    use HasUuids;

    protected $fillable = [
        'employee_id',
        'attendance_date',
        'request_type',
        'reason',
        'attachment',
        'status',
        'approved_by',
        'approved_at',
        'approval_note',
    ];

    protected $casts = [

        'attendance_date' => 'date',

        'approved_at' => 'datetime',

    ];

    public function license()
    {
        return $this->belongsTo(License::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}