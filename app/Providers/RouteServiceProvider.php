<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Log;
class RouteServiceProvider extends ServiceProvider
{
    /**
     * Namespace for default controllers.
     *
     * @var string|null
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Namespace for admin controllers.
     *
     * @var string|null
     */
    protected $admin_namespace = 'App\Http\Controllers\Admin';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            // Web Routes
            // Route::middleware('web')
            //     ->domain(env('APP_URL'))
            //     ->namespace($this->namespace)
            //     ->group(function () {
            //         Log::info('web routes loaded');
            //         require base_path('routes/web.php');
            //     });
            // Admin Routes
            Route::middleware('web')
                ->namespace($this->admin_namespace)
                // ->domain(env('APP_ADMIN_URL'))
                ->name('admin.')
                ->group(function () {
                    Log::info('Admin routes loaded');
                    require base_path('routes/admin.php');
                });

            // API Routes
            Route::middleware('api')
                ->namespace($this->namespace)
                ->prefix('api')
                ->group(base_path('routes/api.php'));
        });


    }
}
