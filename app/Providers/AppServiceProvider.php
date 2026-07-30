<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use App\Helpers\ActiveRole;
use Carbon\Carbon;
use TakiElias\Tablar\Tablar;


class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (app()->environment('production')) {
                URL::forceScheme('https');
            }
            view()->composer('*', function ($view) {
                $tablar = app(Tablar::class);
                $view->with('tablar', $tablar);
            });

        Blade::if('activerole', function ($roleName) {
            return strtolower(ActiveRole::name()) === strtolower($roleName);
        });

        Blade::if('activeperm', function ($permission) {
            return ActiveRole::hasPermission($permission);
        });
        Carbon::setLocale('id');
    }
}