<?php

namespace Softworx\RocXolid\DevKit\Providers;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Illuminate\Console\Command;
use Softworx\RocXolid\DevKit\Commands\CreateRootUser;

/**
 * rocXolid CLI commands service provider.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid\DevKit
 * @version 1.0.0
 */
class CommandServiceProvider extends IlluminateServiceProvider
{
    /**
     * Extend the default request validator.
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    public function boot()
    {
        $this
            ->setCommads();

        return $this;
    }

    /**
     * Read commands config and register found commands with assigned handlers.
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    private function setCommads(): IlluminateServiceProvider
    {
        foreach (config('rocXolid.devkit.general.commands') as $command => $handler)
        {
            $this->registerCommand(sprintf(config('rocXolid.devkit.general.command-binding-pattern'), $command), $handler);
        }

        return $this;
    }

    /**
     * Register CLI command.
     *
     * @param string $binding
     * @param \Illuminate\Console\Command $handler
     * @return \Illuminate\Support\ServiceProvider
     */
    private function registerCommand(string $binding, string $handler): IlluminateServiceProvider
    {
        $this->app->bind($binding, $handler);

        $this->app->tag($binding, config('rocXolid.devkit.general.command-binding-tag'));

        $this->commands($binding);

        return $this;
    }
}
