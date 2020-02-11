<?php

namespace Softworx\RocXolid\DevKit;

use View;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Foundation\AliasLoader;
use Softworx\RocXolid\DevKit\Console\Contracts\Executor as ExecutorContract;
use Softworx\RocXolid\DevKit\Console\Executor;

class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this
            ->bindContracts()
            ->bindAliases(AliasLoader::getInstance());
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this
            ->configure()
            ->load()
            ->publish()
            ->setRoutes($this->app->router)
            ->setComposers()
            ->setCommads();
    }

    /**
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    private function configure()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/main.php', 'rocXolid.devkit');
        $this->mergeConfigFrom(__DIR__ . '/../config/generators.php', 'rocXolid.devkit.generators');

        return $this;
    }

    /**
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    private function load()
    {
        // customized views preference
        $this->loadViewsFrom(resource_path('views/vendor/rocXolid/DevKit'), 'rocXolid:devkit');
        // pre-defined views fallback
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'rocXolid:devkit');

        return $this;
    }

    /**
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    private function publish()
    {
        // customizable config
        $this->publishes([
            __DIR__ . '/../config/customizable.php' => config_path('rocXolid/devkit.php'),
        ], 'config');

        // assets
        $this->publishes([
            __DIR__ . '/../resources/assets' => public_path('vendor/rocXolid'),
        ], 'public');

        return $this;
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router $router
     * @return \Illuminate\Support\ServiceProvider
     */
    private function setRoutes(Router $router)
    {
        // zatial takto, inac zrejme classu na to / dynamicky setovat nejako
        $router->group([
            'module' => 'rocXolid-devkit',
            'middleware' => [ 'web', 'auth.rocXolid' ],
            'namespace' => 'Softworx\RocXolid\DevKit\Http\Controllers',
            'prefix' => sprintf('%s/devkit', config('rocXolid.main.admin-path', 'rocXolid')),
            'as' => 'rocxolid.devkit.',
        ], function ($router) {
            // devkit dashboard
            $router->get('/', 'Controller@index')->name('dashboard');
            // commander
            $router->group([
                'prefix' => 'commander',
                'as' => 'commander.',
            ], function($router) {
                $router->get('/', 'CommanderController@index')->name('index');
                $router->get('/help/{command}', 'CommanderController@help')->name('help');
                $router->match(['GET', 'POST'], '/run/{command}', 'CommanderController@run')->name('run');
            });
        });

        return $this;
    }

    private function setComposers()
    {
        // toto zrejme pichnut do configu - podla switchov, resp cez nejaky foreach <key> => <composer-class>
        View::composer('rocXolid:devkit::*', Composers\ViewComposer::class);

        return $this;
    }

    private function setCommads()
    {
        foreach (config('rocXolid.devkit.commands') as $command => $handler)
        {
            $this->registerCommand(sprintf(config('rocXolid.devkit.command-binding-pattern'), $command), $handler);
            //$this->commands($handler);
        }

        return $this;
    }

    private function registerCommand($binding, $handler)
    {
        $this->app->singleton($binding, function($app) use ($handler)
        {
            return $app[$handler];
        });

        $this->app->tag($binding, config('rocXolid.devkit.command-binding-tag'));

        $this->commands($binding);

        return $this;
    }

    /**
     * Bind contracts / facades, so they don't have to be added to config/app.php.
     *
     * Usage:
     *      $this->app->bind(<SomeContract>::class, <SomeImplementation>::class);
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    private function bindContracts()
    {
        $this->app->bind(ExecutorContract::class, Executor::class);

        return $this;
    }

    /**
     * Bind aliases, so they don't have to be added to config/app.php.
     *
     * Usage:
     *      $loader->alias('<alias>', <Facade/>Contract>::class);
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    private function bindAliases(AliasLoader $loader)
    {
        // ...
        return $this;
    }
}