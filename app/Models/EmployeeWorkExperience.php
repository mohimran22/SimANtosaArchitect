<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

class EmployeeWorkExperience extends Model
{
    use HasFactory, HasUuid;

    
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'employee_id',
        'company_name',
        'start_date',
        'end_date',
        'last_position',
        'last_salary',
        'reason_for_leaving',
    ];

    public function employee()
{
    return $this->belongsTo(Employee::class);
}

    public function getStartDateFormattedAttribute()
    {
        return $this->start_date ? Carbon::parse($this->start_date)->format('d/m/Y') : '-';
    }

    public function getEndDateFormattedAttribute()
    {
        return $this->end_date ? Carbon::parse($this->end_date)->format('d/m/Y') : '-';
    }
}
