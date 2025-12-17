<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ProjectTask extends Model
{
    use HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;
    
        protected $fillable = [
        'project_id',
        'offer_id',
        'category',
        'task_name',
        'employee_id',
        'status',
        'progress',
        'updated_at',
        'created_at',
    ];

    public function files()
    {
        return $this->hasMany(ProjectTaskFile::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
