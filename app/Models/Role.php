<?php

namespace App\Models;

use App\Traits\HasUuid;
use Spatie\Permission\Models\Role as SpatieRole;


class Role extends SpatieRole
{
    use HasUuid;

    protected $keyType = 'string';
    public $incrementing = false;

        public function getRouteKeyName()
    {
        return 'id'; // default, tapi id-nya UUID string
    }

    protected $guarded = [];
}
