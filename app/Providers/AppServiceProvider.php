<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Midtrans\Config;

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
        //
      Config::$serverKey = config('midtrans.server_key');
      Config::$isProduction = config('midtrans.is_production');
      Config::$isSanitized = config('midtrans.is_sanitized');
      Config::$is3ds = config('midtrans.is_3ds');
      if (config('database.default') === 'sqlite') {
        DB::statement('PRAGMA foreign_keys = ON');
      }
    }
}
