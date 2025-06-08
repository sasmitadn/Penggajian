<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $userCategories = Category::where('status', 'active')->where('is_paid', 0)->get();
            $jobCategories = Category::where('status', 'active')->where('is_paid', 1)->get();

            $view->with(compact('userCategories', 'jobCategories'));
        });
    }
}
