<?php

namespace Kwidoo\RemoteUser;

use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Kwidoo\RemoteUser\Contracts\AuthService;
use Kwidoo\RemoteUser\Contracts\RemoteUser;

class RemoteUserServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('iam.php'),
            ], 'config');
        }

        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        $this->app->bind(AuthService::class, config('iam.auth_service_class'));
        $this->app->bind(RemoteUser::class, config('iam.user_class', User::class));

        $this->app['auth']->provider('remote', function ($app) {
            return $app->make(RemoteUserProvider::class);
        });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'iam');
    }
}
