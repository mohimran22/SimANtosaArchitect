<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasUuid;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $casts = [
        'invoice_date' => 'date'
    ];

    protected $fillable = [
        'project_id',
        'invoice_number',
        'invoice_date',
        'invoice_dp_downloaded_at',
        'invoice_dp_approved_at',
        // 'created_by',
    ];

    public function items()
    {
        return $this->hasMany(OfferItem::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function package()
    {
        return $this->belongsTo(DesignPackage::class, 'design_package_id');
    }


}
