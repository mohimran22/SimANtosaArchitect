<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RabProcessCategory extends Model
{
    protected $fillable = [
        'rab_process_id',
        'name',
        'order_no',
        'is_draft'
    ];
        public function uraians()
    {
        return $this->hasMany(RabProcessUraian::class, 'category_id');
    }

//     public function getLetterAttribute()
// {
//     return number_to_letters($this->order_no);
// }
}
