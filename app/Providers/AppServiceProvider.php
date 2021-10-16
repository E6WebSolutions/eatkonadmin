<?php

namespace App\Providers;

use App\Application;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\ServiceProvider;

/**
 *
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Builder::defaultStringLength(191);
        view()->share('account_info', Application::all()->first());
    }
}
