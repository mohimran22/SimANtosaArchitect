<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuildCategoryPlan extends Model
{
    protected $fillable = [
        'project_id',
        'week_no',
        'bobot_percent',
        'category_order'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function progresses()
    {
        return $this->hasMany(BuildWeeklyProgress::class, 'weekly_report_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getWeightedProgressAttribute()
    {
        return $this->progresses()
            ->with('item')
            ->get()
            ->sum(function ($p) {
                return $p->progress_percent * ($p->item->bobot_percent / 100);
            });
    }
}
