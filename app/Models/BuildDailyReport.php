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
        'pekerjaan',
        'catatan',
        'created_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
    ];

    // ======================

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

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
