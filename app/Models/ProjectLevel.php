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
        'completed_at',
    ];

    protected $casts = [
    'is_completed' => 'boolean',
    'completed_at' => 'datetime',
];

protected static function booted()
{
    static::saving(function ($level) {
        if ($level->isDirty('is_completed')) {
            $level->completed_at = $level->is_completed ? now() : null;
        }
    });
}

public static function complete(string $projectId, int $order): void
{
    $level = static::where(['project_id' => $projectId, 'level_order' => $order])->first();

    if ($level && !$level->is_completed) {
        $level->update(['is_completed' => true]);
    }
}

public function project()
    {
        return $this->belongsTo(Project::class);
    }
    
    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'project_level_employee');
    }

}
