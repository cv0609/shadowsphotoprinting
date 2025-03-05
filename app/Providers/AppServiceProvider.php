<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Request;
use Carbon\Carbon;

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
        $timezone = Cookie::get('user_timezone', config('app.timezone')); // Read from cookie

        if ($timezone) {
            Config::set('app.timezone', $timezone);
            date_default_timezone_set($timezone);
            Carbon::setTestNow(Carbon::now($timezone)); // Ensures Carbon respects timezone
        }
        \Log::info('Current Laravel Timezone: ' . config('app.timezone'));
        \Log::info('PHP Timezone: ' . date_default_timezone_get());
        \Log::info('Carbon Now: ' . Carbon::now());
    }
}
