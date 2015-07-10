<?php

namespace Formandsystem\Api;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class ApiServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // use this if your package has views
        # $this->loadViewsFrom(realpath(__DIR__.'/resources/views'), 'skeleton');

        // use this if your package has routes
        $this->setupRoutes($this->app->router);

        // use this if your package needs a config file
        $this->publishes([
                __DIR__.'/config/config.php' => config_path('formandsystemapi.php'),
        ]);

        // use the vendor configuration file as fallback
        $this->mergeConfigFrom(
            __DIR__.'/config/config.php', 'formandsystemapi'
        );
        dd('testing');
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function setupRoutes(Router $router)
    {
        $router->group(['namespace' => 'Formandsystem\Api\Http\Controllers'], function($router)
        {
            require __DIR__.'/Http/routes.php';
        });
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerApi();

        // config([
        //         'config/formandsystemapi.php',
        // ]);
    }

    private function registerApi()
    {
        $this->app->bind('api',function($app){
            return new Api($app);
        });
    }
}
