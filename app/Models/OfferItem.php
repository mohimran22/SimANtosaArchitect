<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class OfferItem extends Model
{
    use HasUuids;

    protected $table = 'offer_items';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
        protected $fillable = [
        'offer_id',
        'item_name',
        'category',
    ];



        public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

}