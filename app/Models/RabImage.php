<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RabImage extends Model
{
    protected $table = 'rab_images';

    protected $fillable = [
        'path',
    ];
}
