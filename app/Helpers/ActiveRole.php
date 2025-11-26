<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class ActiveRole
{
    public static function role()
    {
        return Auth::user()->activeRole;
    }

    public static function name()
    {
        return self::role()?->name;
    }

    public static function permissions()
    {
        $role = self::role();
        if (!$role) return [];

        return $role->permissions->pluck('name')->toArray();
    }

    public static function hasPermission($permission)
    {
        return in_array($permission, self::permissions());
    }
}