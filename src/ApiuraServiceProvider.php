<?php

namespace Apiura;

use Apiura\Http\Middleware\EnsureApiuraAccess;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class ApiuraServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/apiura.php', 'apiura');
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/apiura.php');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'apiura');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->app['router']->aliasMiddleware('apiura.access', EnsureApiuraAccess::class);

        RateLimiter::for('apiura', function ($request) {
            return Limit::perMinute(config('apiura.rate_limit', 60));
        });

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/apiura.php' => config_path('apiura.php'),
            ], 'apiura-config');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/apiura'),
            ], 'apiura-views');

            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'apiura-migrations');

            $this->commands([
                Console\Commands\GenerateApiDocs::class,
            ]);
        }
    }
}
