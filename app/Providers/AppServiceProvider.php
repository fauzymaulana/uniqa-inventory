<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Product;
use App\Observers\ProductObserver;

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
        Product::observe(ProductObserver::class);

        // Ensure storage symlink exists
        $link = public_path('storage');
        $target = storage_path('app/public');
        if (!file_exists($link) && file_exists($target)) {
            app()->make('files')->link($target, $link);
        }
    }
}
