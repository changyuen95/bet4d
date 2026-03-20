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
     * Namespace for website (public frontend) controllers.
     *
     * @var string|null
     */
    protected $website_namespace = 'App\Http\Controllers\Website';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            // Website Routes (Public Frontend) — stc4d.com
            // When WEBSITE_DOMAIN is set, only that domain serves the website.
            // When not set (local dev), website routes work on any domain.
            $websiteRoutes = Route::middleware('web')
                ->namespace($this->website_namespace);
            if (env('WEBSITE_DOMAIN')) {
                $websiteRoutes->domain(env('WEBSITE_DOMAIN'));
            }
            $websiteRoutes->group(function () {
                require base_path('routes/website.php');
            });

            // Admin Routes — equalciety.com (or APP_ADMIN_URL)
            // In production: domain-based separation (no prefix needed)
            // In local dev: use /admin prefix to avoid conflict with website routes
            $adminRoutes = Route::middleware('web')
                ->namespace($this->admin_namespace)
                ->name('admin.');
            if (env('APP_ADMIN_URL')) {
                $adminRoutes->domain(env('APP_ADMIN_URL'));
            } else {
                $adminRoutes->prefix('admin');
            }
            $adminRoutes->group(function () {
                Log::info('Admin routes loaded');
                require base_path('routes/admin.php');
            });

            // API Routes — same domain as admin
            $apiRoutes = Route::middleware('api')
                ->namespace($this->namespace)
                ->prefix('api');
            if (env('APP_ADMIN_URL')) {
                $apiRoutes->domain(env('APP_ADMIN_URL'));
            }
            $apiRoutes->group(base_path('routes/api.php'));
        });


    }
}
