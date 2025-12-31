<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AhspGroup extends Model
{
    protected $fillable = [
        'bidang',
        'kode',
        'nama',
    ];

    public function ahsps()
    {
        return $this->hasMany(Ahsp::class);
    }
}
