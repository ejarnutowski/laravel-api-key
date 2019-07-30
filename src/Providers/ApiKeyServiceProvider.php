<?php

namespace Ejarnutowski\LaravelApiKey\Providers;

use Ejarnutowski\LaravelApiKey\Console\Commands\ActivateApiKey;
use Ejarnutowski\LaravelApiKey\Console\Commands\DeactivateApiKey;
use Ejarnutowski\LaravelApiKey\Console\Commands\DeleteApiKey;
use Ejarnutowski\LaravelApiKey\Console\Commands\GenerateApiKey;
use Ejarnutowski\LaravelApiKey\Console\Commands\ListApiKeys;
use Ejarnutowski\LaravelApiKey\Http\Middleware\AuthorizeApiKey;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class ApiKeyServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param Router $router
     * @return void
     */
    public function boot(Router $router)
    {
        $this->registerMiddleware($router);

        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ActivateApiKey::class,
                DeactivateApiKey::class,
                DeleteApiKey::class,
                GenerateApiKey::class,
                ListApiKeys::class,
            ]);
        }
    }

    /**
     * Register middleware
     *
     * Support added for different Laravel versions
     *
     * @param Router $router
     */
    protected function registerMiddleware(Router $router)
    {
        $router->aliasMiddleware('auth.apikey', AuthorizeApiKey::class);
    }
}
