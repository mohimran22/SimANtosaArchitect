<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuildProcessItem extends Model
{
    protected $fillable = [
        'project_id',
        'rab_item_id',
        'category_name',
        'floor_name',
        'job_name',
        'volume',
        'satuan',
        'base_price',
        'bobot_percent',
        'price',
        'total',
        'is_tambahan',
        'parent_id',
        'sumber',
        'order_no',
    ];

    protected $casts = [
        'volume' => 'decimal:2',
        'price' => 'decimal:2',
        'total' => 'decimal:2',
        'bobot_percent' => 'decimal:6',
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
    return $this->hasMany(BuildWeeklyProgress::class, 'build_process_item_id');
}

    public function getTotalProgressAttribute()
    {
        return $this->weeklyProgresses()->sum('progress_percent');
    }
    public function parent()
{
    return $this->belongsTo(BuildProcessItem::class, 'parent_id');
}

public function tambahan()
{
    return $this->hasMany(BuildProcessItem::class, 'parent_id');
}
}

