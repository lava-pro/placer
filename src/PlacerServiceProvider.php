<?php

namespace Lava\Placer;

use Illuminate\Support\ServiceProvider;

class PlacerServiceProvider extends ServiceProvider
{
    /**
     * Console commands
     *
     * @var array
     */
    protected $commands = [
        'Lava\Placer\Commands\MakePackage',
    ];

    /**
     * Perform post-registration booting of services
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/placer.php' => config_path('placer.php'),
        ]);

        if ($this->app->runningInConsole()) {
            if ($commands = $this->commands)
                $this->commands($commands);
        }
    }

    /**
     * Register the package services and commands
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/placer.php', 'placer');
    }

}
