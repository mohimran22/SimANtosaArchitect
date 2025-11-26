<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class AccountingAccount extends Model
{
    use HasUuid;

    protected $table = 'accounting_accounts';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    
    protected $fillable = [
       'license_id',
        'account_code',
        'account_name',
        'account_type',
        'is_parent',
        'parent_id',
        'balance_type',
        'initial_balance',
        'is_active',
    ];

          public function license()
    {
        return $this->belongsTo(License::class);
    }

      public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
