<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ProjectTaskFile extends Model
{
    use HasUuids;

    protected $fillable = [
        'project_task_id',
        'file_path',
        'file_name',
        'uploaded_by',
    ];

    public function task()
    {
        return $this->belongsTo(ProjectTask::class, 'project_task_id');
    }
}

