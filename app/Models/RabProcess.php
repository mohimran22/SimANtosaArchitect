<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RabProcess extends Model
{
    protected $table = 'rab_process';
    public $timestamps = false;

    protected $fillable = [
    'project_id',
    'contact_name',
    'job_location',
    'job_duration',
    'subtotal',
    'discount',
    'subtotal_after_discount',
    'tax_rate',
    'tax_total',
    'shipping',
    'grand_total',
    'notes',
    'overhead_percent',
    'profit_percent',
    'overhead_value',
    'profit_value',
];

        public function project()
    {
        return $this->belongsTo(Project::class);
    }
    
    public function items()
    {
        return $this->hasMany(RabProcessItem::class, 'rab_process_id');
    }

}
