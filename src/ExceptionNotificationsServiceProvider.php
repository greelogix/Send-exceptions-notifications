<?php

namespace Greelogix\ExceptionNotifications;

use Illuminate\Support\ServiceProvider;

class ExceptionNotificationsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/exceptionNotifications.php',
            'exceptionNotifications'
        );
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/exceptionNotifications.php' => config_path('exceptionNotifications.php'),
        ], 'config');
    }
}
