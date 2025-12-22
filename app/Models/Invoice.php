<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasUuid;

    const TYPE_SURVEY = 'survey';
    const TYPE_DP = 'dp';

    const STATUS_DRAFT = 'draft';
    const STATUS_WAITING = 'waiting_approval';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $casts = [
        'invoice_date' => 'date',
        'approved_at'  => 'datetime',
        'rejected_at'  => 'datetime',
    ];

    protected $fillable = [
        'project_id',
        'invoice_number',
        'invoice_date',
        'invoice_type',
        'amount',
        'status',
        'approved_at',
        'approved_by',
        'rejected_at',
        'rejected_by',
        'reject_note',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
