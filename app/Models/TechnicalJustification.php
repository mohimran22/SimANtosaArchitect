<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TechnicalJustification extends Model
{
    protected $table = 'technical_justifications';

    protected $fillable = [
        'project_id',

        'justek_sequence',
        'justek_number',
        'justek_period',
        'offer_date',

        'contact_name',
        'job_location',
        'job_duration',

        'subtotal',
        'discount',
        'subtotal_after_discount',

        'tax_rate',
        'tax_total',

        'shipping',
        'profit',
        'overhead',

        'grand_total',
        'base_subtotal',

        'notes',
        'analisa_version',

        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'justek_sequence' => 'integer',

        'offer_date' => 'date',

        'subtotal' => 'decimal:2',
        'discount' => 'decimal:10',
        'subtotal_after_discount' => 'decimal:2',

        'tax_rate' => 'decimal:2',
        'tax_total' => 'decimal:2',

        'shipping' => 'decimal:2',
        'profit' => 'decimal:2',
        'overhead' => 'decimal:2',

        'grand_total' => 'decimal:2',
        'base_subtotal' => 'decimal:2',

        'analisa_version' => 'integer',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(
            TechnicalJustificationItem::class,
            'technical_justification_id'
        )->orderBy('order_no');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }

    public function invoice(): BelongsTo
{
    return $this->belongsTo(
        Invoice::class,
        'invoice_id'
    );
}
}