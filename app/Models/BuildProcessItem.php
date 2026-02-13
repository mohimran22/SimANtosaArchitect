<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuildProcessItem extends Model
{
    protected $fillable = [
        'project_id',
        'rab_item_id',
        'uraian',
        'volume',
        'job_category_id',
        'satuan',
        'bobot_percent',
        'price',
        'total'
    ];

    protected $casts = [
        'volume' => 'decimal:2',
        'bobot_percent' => 'decimal:2',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function jobCategory()
{
    return $this->belongsTo(JobCategory::class,'job_category_id');
}
    public function weeklyProgresses()
    {
        return $this->hasMany(BuildWeeklyProgress::class);
    }

    public function getTotalProgressAttribute()
    {
        return $this->weeklyProgresses()->sum('progress_percent');
    }
}
