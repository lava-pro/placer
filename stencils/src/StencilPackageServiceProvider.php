<?php

namespace Lava\:uc:package;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class :uc:packageServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Console commands
     *
     * @var array
     */
    protected $commands = [];

    /**
     * Perform post-registration booting of services
     *
     * @return void
     */
    public function boot()
    {
    	$this->registerResources();

        if ($this->app->runningInConsole()) {
            $this->registerPublishing();

            if ($commands = $this->commands)
                $this->commands($commands);
        }
    }

    /**
     * Register the package services and commands
     *
     * @return void
     */
    public function register()
    {
		$this->mergeConfigFrom(__DIR__.'/../config/:lc:package.php', ':lc:package');

        $this->app->singleton(':lc:package', function ($app) {
            return new :uc:package;
        });
    }

    /**
     * Get the services provided by the provider
     *
     * @return array
     */
    public function provides()
    {
        return [':lc:package'];
    }

    /**
     * Register the package resources such as routes, views...
     *
     * @return void
     */
    protected function registerResources()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', ':lc:package');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', ':lc:package');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // $this->registerRoutes();
    }

    /**
     * Register the package routes
     *
     * @return void
     */
    protected function registerRoutes()
    {
    	$config = $this->app['config']->get(':lc:package', []);

        $options = [
            'namespace'  => 'Lava\:uc:package\Http\Controllers',
            'prefix'     => $config['prefix'] ?? null,
            'as'         => ':lc:package',
            'middleware' => ':lc:package',
        ];

        Route::group($options, function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });
    }

    /**
     * Register the package's publishable resources
     *
     * @return void
     */
    protected function registerPublishing()
    {
        $this->publishes([
            __DIR__.'/../config/:lc:package.php' => config_path(':lc:package.php'),
        ], ':lc:package-config');

        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/:lc:package'),
        ], ':lc:package-lang');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/:lc:package'),
        ], ':lc:package-views');
    }

}
