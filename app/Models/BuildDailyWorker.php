<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuildDailyWorker extends Model
{
    protected $fillable = [
        'daily_report_id',
        'keahlian',
        'jumlah',
    ];

    public function report()
    {
        return $this->belongsTo(BuildDailyReport::class, 'daily_report_id');
    }
}
