<?php

namespace App\Providers;

use App\Helpers\LicenseChecker;
use Illuminate\Support\ServiceProvider;

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
        if (!LicenseChecker::isValid()) {
            // abort(403);
            die('throw new HttpException($code, $message, null, $headers);');
        }
    }
}
