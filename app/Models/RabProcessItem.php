<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RabProcessItem extends Model
{
    protected $table = 'rab_process_items';
    public $timestamps = false;
    
    protected $fillable = [
    'rab_process_id',
    'job_category_id',
    'job_name',
    'satuan',
    'volume',
    'price',
    'total'
];

}
