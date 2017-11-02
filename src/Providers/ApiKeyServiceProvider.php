<?php

namespace Ejarnutowski\LaravelApiKey\Providers;

use Ejarnutowski\LaravelApiKey\Console\Commands\GenerateApiKey;
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
        $this->loadMigrationsFrom(__DIR__ . '/../Migrations');

        $this->commands([GenerateApiKey::class,]);

        $router->middleware('auth.apikey', AuthorizeApiKey::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    }
}