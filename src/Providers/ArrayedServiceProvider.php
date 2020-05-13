<?php

namespace Transprime\Arrayed\Providers;

use Illuminate\Support\ServiceProvider;
use Transprime\Arrayed\Arrayed;
use Transprime\Arrayed\Interfaces\ArrayedInterface;

class ArrayedServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/migrations-snapshot.php' => config_path('arrayed'),
        ], 'arrayed');

        $this->app->bind(ArrayedInterface::class, Arrayed::class);
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/arrayed.php', 'arrayed');
    }
}
