<?php

namespace Cable8mm\LaravelApiKey;

use Cable8mm\LaravelApiKey\Console\Commands\ActivateApiKey;
use Cable8mm\LaravelApiKey\Console\Commands\DeactivateApiKey;
use Cable8mm\LaravelApiKey\Console\Commands\DeleteApiKey;
use Cable8mm\LaravelApiKey\Console\Commands\GenerateApiKey;
use Cable8mm\LaravelApiKey\Console\Commands\ListApiKeys;
use Cable8mm\LaravelApiKey\Http\Middleware\AuthorizeApiKey;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class ApiKeyServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->registerMiddleware($router);
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            ActivateApiKey::class,
            DeactivateApiKey::class,
            DeleteApiKey::class,
            GenerateApiKey::class,
            ListApiKeys::class,
        ]);
    }

    /**
     * Register middleware
     *
     * Support added for different Laravel versions
     */
    protected function registerMiddleware(Router $router)
    {
        $versionComparison = version_compare($this->app->version(), '5.4.0');

        if ($versionComparison >= 0) {
            $router->aliasMiddleware('auth.apikey', AuthorizeApiKey::class);
        } else {
            $router->middleware('auth.apikey', AuthorizeApiKey::class);
        }
    }
}
