<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TechnicalJustificationCounter extends Model
{
    protected $table = 'technical_justification_counters';

    protected $fillable = [
        'period',
        'last_sequence',
    ];

    protected $casts = [
        'last_sequence' => 'integer',
    ];
}