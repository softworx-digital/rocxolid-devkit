<?php

namespace Softworx\RocXolid\DevKit\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

/**
 * rocXolid routes service provider.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid\DevKit
 * @version 1.0.0
 */
class RouteServiceProvider extends IlluminateServiceProvider
{
    /**
     * Bootstrap rocXolid routing services.
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    public function boot()
    {
        $this
            ->load($this->app->router);

        return $this;
    }

    /**
     * Define the routes for the package.
     *
     * @param \Illuminate\Routing\Router $router Router to be used for routing.
     * @return \Illuminate\Support\ServiceProvider
     */
    private function load(Router $router): IlluminateServiceProvider
    {
        $router->group([
            'module' => 'rocXolid-devkit',
            'middleware' => [ 'web', 'rocXolid.auth' ],
            'namespace' => 'Softworx\RocXolid\DevKit\Http\Controllers',
            'prefix' => sprintf('%s/devkit', config('rocXolid.admin.general.routes.root', 'rocXolid')),
            'as' => 'rocxolid.devkit.',
        ], function ($router) {
            // devkit dashboard
            $router->get('/', 'Controller@index')->name('dashboard');
            // commander
            $router->group([
                'prefix' => 'commander',
                'as' => 'commander.',
            ], function ($router) {
                $router->get('/', 'CommanderController@index')->name('index');
                $router->get('/help/{command}', 'CommanderController@help')->name('help');
                $router->match(['GET', 'POST'], '/run/{command}', 'CommanderController@run')->name('run');
            });
        });

        return $this;
    }
}
