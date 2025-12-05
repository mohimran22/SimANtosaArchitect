<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

class ProjectLevel  extends Model
{
    use HasFactory;


    public $timestamps = false;

    protected $fillable = [
        'project_id',
        'level_order',
        'level_name',
        'is_completed',
        'is_started',
        'employee_id',
    ];

public function project()
    {
        return $this->belongsTo(Project::class);
    }
    
public function employee()
{
    return $this->belongsTo(Employee::class);
}

}
