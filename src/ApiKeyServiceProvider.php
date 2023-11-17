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
     */
    public function boot(Router $router): void
    {
        $router->middlewareGroup('auth.apikey', [AuthorizeApiKey::class]);
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->publishes([
            __DIR__.'/../stubs/ApiKey.stub' => $this->app->basePath('app/Nova/ApiKey.php'),
        ], 'laravel-api-key-nova');
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
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
}
