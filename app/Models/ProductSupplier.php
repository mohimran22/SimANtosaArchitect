<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductSupplier extends Pivot
{
    public $incrementing = false;
    public $timestamps = true;

    protected $table = 'product_supplier';

    protected $fillable = [
        'supplier_id',
        'product_id',
        'stock',
        'buying_prices',
        'tax_percentage',
        'discount',
    ];
}
