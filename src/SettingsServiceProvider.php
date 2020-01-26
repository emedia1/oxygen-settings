<?php

namespace EMedia\Settings;

use Illuminate\Support\ServiceProvider;

/**
 *
 */
class SettingsServiceProvider extends ServiceProvider
{
    /**
     *
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations')
        ], 'migrations');

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'settings');
    }

    /**
     *
     */
    public function register()
    {
        // code...
    }
}
