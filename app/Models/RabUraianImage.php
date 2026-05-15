<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RabUraianImage extends Model
{
    protected $fillable = [
        'rab_id',
        'uraian_id',
        'image_id'
    ];

    public function rab()
    {
        return $this->belongsTo(RabProcess::class);
    }

    public function image()
    {
        return $this->belongsTo(RabImage::class, 'image_id');
    }
}
