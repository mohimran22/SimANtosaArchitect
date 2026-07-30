<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class AttendanceRevision extends Model
{
    use HasUuid;

    protected $fillable = [
        'attendance_id',
        'edited_by',
        'edited_at',
        'edit_reason',
        'old_data',
        'new_data',
    ];

    protected $casts = [
        'edited_at' => 'datetime',
        'old_data' => 'array',
        'new_data' => 'array',
    ];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'edited_by');
    }
}
