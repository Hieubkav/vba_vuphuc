<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\FilamentOptimizationService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Filament\Facades\Filament;

/**
 * Service Provider cho Filament Optimization
 * 
 * Đăng ký và cấu hình các service tối ưu hóa Filament
 */
class FilamentOptimizationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Đăng ký FilamentOptimizationService
        $this->app->singleton(FilamentOptimizationService::class, function ($app) {
            return new FilamentOptimizationService();
        });

        // Merge config
        $this->mergeConfigFrom(
            __DIR__.'/../../config/filament-optimization.php',
            'filament-optimization'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish config
        $this->publishes([
            __DIR__.'/../../config/filament-optimization.php' => config_path('filament-optimization.php'),
        ], 'filament-optimization-config');

        // Đăng ký commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                \App\Console\Commands\OptimizeFilamentCommand::class,
            ]);
        }

        // Tối ưu hóa Filament khi boot
        $this->optimizeFilament();

        // Middleware đã được đăng ký trong AdminPanelProvider
        // $this->registerOptimizationMiddleware();

        // Tối ưu hóa views
        $this->optimizeViews();
    }

    /**
     * Tối ưu hóa Filament
     */
    protected function optimizeFilament(): void
    {
        // Chỉ chạy khi có Filament
        if (!class_exists(\Filament\Facades\Filament::class)) {
            return;
        }

        // Tối ưu hóa asset loading
        $this->optimizeAssets();

        // Tối ưu hóa database queries
        $this->optimizeQueries();

        // Tối ưu hóa memory
        $this->optimizeMemory();
    }

    /**
     * Tối ưu hóa assets
     */
    protected function optimizeAssets(): void
    {
        if (!config('filament.optimization.enable_asset_optimization', true)) {
            return;
        }

        // Defer non-critical CSS
        Blade::directive('deferCSS', function ($expression) {
            return "<?php echo '<link rel=\"preload\" href=\"' . {$expression} . '\" as=\"style\" onload=\"this.onload=null;this.rel=\'stylesheet\'\">'; ?>";
        });

        // Preload critical resources
        Blade::directive('preloadResource', function ($expression) {
            return "<?php echo '<link rel=\"preload\" href=\"' . {$expression} . '\" as=\"script\">'; ?>";
        });
    }

    /**
     * Tối ưu hóa queries
     */
    protected function optimizeQueries(): void
    {
        if (!config('filament.optimization.enable_query_caching', true)) {
            return;
        }

        // Đăng ký query optimization cho Eloquent
        \Illuminate\Database\Eloquent\Model::preventLazyLoading(!app()->isProduction());
        
        // Log slow queries trong development
        if (!app()->isProduction()) {
            \Illuminate\Support\Facades\DB::listen(function ($query) {
                if ($query->time > 100) { // > 100ms
                    \Illuminate\Support\Facades\Log::warning('Slow query detected', [
                        'sql' => $query->sql,
                        'time' => $query->time,
                        'bindings' => $query->bindings,
                    ]);
                }
            });
        }
    }

    /**
     * Tối ưu hóa memory
     */
    protected function optimizeMemory(): void
    {
        if (!config('filament.optimization.enable_memory_optimization', true)) {
            return;
        }

        // Tự động garbage collection
        register_shutdown_function(function () {
            if (function_exists('gc_collect_cycles')) {
                gc_collect_cycles();
            }
        });

        // Tối ưu memory cho large datasets
        ini_set('memory_limit', '512M');
    }

    /**
     * Đăng ký middleware tối ưu hóa
     */
    protected function registerOptimizationMiddleware(): void
    {
        // Middleware để tối ưu response
        $this->app['router']->pushMiddlewareToGroup('web', \App\Http\Middleware\FilamentOptimizationMiddleware::class);
    }

    /**
     * Tối ưu hóa views
     */
    protected function optimizeViews(): void
    {
        // Cache view composers
        View::composer('filament::*', function ($view) {
            // Tối ưu hóa data truyền vào view
            $optimizationService = app(FilamentOptimizationService::class);
            
            // Cache common data
            $view->with('optimized_data', $optimizationService->cacheQuery(
                'common_view_data',
                function () {
                    return [
                        'app_name' => config('app.name'),
                        'app_version' => config('app.version', '1.0.0'),
                        'current_time' => now()->format('Y-m-d H:i:s'),
                    ];
                },
                3600 // Cache 1 giờ
            ));
        });

        // Tối ưu hóa Blade templates
        Blade::directive('optimizedInclude', function ($expression) {
            return "<?php 
                \$optimizationService = app(\\App\\Services\\FilamentOptimizationService::class);
                echo \$optimizationService->cacheQuery(
                    'blade_include_' . {$expression},
                    function() use (\$__data) {
                        return view({$expression}, \$__data)->render();
                    },
                    300
                );
            ?>";
        });
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [
            FilamentOptimizationService::class,
        ];
    }
}
