<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TechnicalJustificationItem extends Model
{
    protected $table = 'technical_justification_items';

    protected $fillable = [
        'technical_justification_id',

        'floor_name',
        'category_name',

        'job_name',
        'description',

        'volume',
        'satuan',

        'price',
        'total',

        'profit',
        'overhead',

        'base_price',

        'volume1',
        'volume2',

        'is_draft',
        'order_no',
    ];

    protected $casts = [
        'volume' => 'decimal:5',

        'price' => 'decimal:2',
        'total' => 'decimal:2',

        'profit' => 'decimal:2',
        'overhead' => 'decimal:2',

        'base_price' => 'decimal:2',

        'volume1' => 'decimal:2',
        'volume2' => 'decimal:5',

        'is_draft' => 'boolean',
        'order_no' => 'integer',
    ];

    public function technicalJustification(): BelongsTo
    {
        return $this->belongsTo(
            TechnicalJustification::class,
            'technical_justification_id'
        );
    }
}