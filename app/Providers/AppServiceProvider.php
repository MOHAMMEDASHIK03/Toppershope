<?php

namespace App\Providers;

use App\Services\CategoryService;
use App\Support\DompdfAutoload;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        DompdfAutoload::register();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Tell Laravel where the public folder is inside Hostinger's environment
        // Automatically checks if public_html exists to prevent local artisan serve from crashing
        if (is_dir(base_path('../public_html'))) {
            $this->app->usePublicPath(base_path('../public_html'));
        }

        View::composer('layouts.public', function ($view) {
            $view->with('navCategories', app(CategoryService::class)->activeCategoriesWithSubcategories());
        });
    }
}
