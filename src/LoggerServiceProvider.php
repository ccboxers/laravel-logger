<?php

namespace Layman\LaravelLogger;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Layman\LaravelLogger\Middleware\Authenticate;
use Layman\LaravelLogger\Middleware\RedirectIfAuthenticated;
use Layman\LaravelLogger\Models\LoggerUser;
use Layman\LaravelLogger\Servers\LoggerServer;

class LoggerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        config([
            'auth.guards.logger' => [
                'driver' => 'session',
                'provider' => 'logger_users',
            ],
            'auth.providers.logger_users' => [
                'driver' => 'eloquent',
                'model' => LoggerUser::class,
            ],
        ]);
        $this->app->bind('logger', function ($app) {
            return new LoggerServer();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $router = $this->app['router'];
        $router->aliasMiddleware('logger.auth', Authenticate::class);
        $router->aliasMiddleware('logger.guest', RedirectIfAuthenticated::class);
        $this->loadViewsFrom(__DIR__ . '/Views', 'logger');
        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
        $this->loadRoutesFrom(__DIR__ . '/Routes/logger.php');
        $this->publishes([
            __DIR__ . '/../config/logger.php' => config_path('logger.php'),
        ], 'logger');
        Paginator::useBootstrapFive();
    }
}
