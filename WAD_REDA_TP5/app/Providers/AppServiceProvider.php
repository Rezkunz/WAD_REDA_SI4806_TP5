<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        if (class_exists(Passport::class)) {
            Passport::routes();
            Passport::tokensExpireIn(now()->addHour());
            Passport::refreshTokensExpireIn(now()->addDays(30));
        }
    }
}
