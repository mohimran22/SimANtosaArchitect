<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RabProcessCategory extends Model
{
    protected $fillable = [
        'rab_process_id',
        'name'
    ];
        public function uraians()
    {
        return $this->hasMany(RabProcessUraian::class, 'category_id');
    }
}
