<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
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
        // Register Blade Components
        Blade::component('components.bottom-nav', 'bottom-nav');
        Blade::component('components.header', 'header');

        // Custom Blade Directives (optional)
        Blade::directive('rupiah', function ($expression) {
            return "<?php echo 'Rp ' . number_format($expression, 0, ',', '.'); ?>";
        });

        Blade::directive('tanggal', function ($expression) {
            return "<?php echo format_tanggal_indonesia($expression); ?>";
        });
    }
}
