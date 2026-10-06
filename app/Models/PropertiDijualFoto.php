<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertiDijualFoto extends Model
{
    protected $table = 'properti_dijual_fotos';
    protected $guarded = ['id'];

    public function properti()
    {
        return $this->belongsTo(PropertiDijual::class, 'properti_dijual_id');
    }
}