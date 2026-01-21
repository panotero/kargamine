<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Validator::extend('safe_text', function ($attribute, $value) {
            return preg_match('/^[a-zA-Z0-9][a-zA-Z0-9 ,.\'"-@&]*$/', $value);
        });
    }
}
