<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuildDailyReport extends Model
{
    protected $fillable = [
        'project_id',
        'tanggal',
        'cuaca',
        'jam_mulai',
        'jam_selesai',
        'total_jam',
        'pekerjaan',
        'catatan',
        'mk',
        'kontraktor_ttd',
        'created_by',
        'is_libur'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function workers()
    {
        return $this->hasMany(BuildDailyWorker::class, 'daily_report_id');
    }

    public function materials()
    {
        return $this->hasMany(BuildDailyMaterial::class, 'daily_report_id');
    }

        public function works()
    {
        return $this->hasMany(BuildDailyWork::class, 'build_daily_report_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function documentations()
{
    return $this->hasMany(DailyDocumentation::class);
}

public function getMingguAttribute()
{
    $start = \Carbon\Carbon::parse($this->project->start_date);
    $current = \Carbon\Carbon::parse($this->tanggal);

    return floor($start->diffInDays($current) / 7) + 1;
}
}
