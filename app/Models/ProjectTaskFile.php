<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectTaskFile extends Model
{
    protected $fillable = [
        'project_task_id',
        'file_path',
    ];

        public function task()
    {
        return $this->belongsTo(ProjectTask::class);
    }
}
