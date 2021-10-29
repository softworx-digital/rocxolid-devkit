<?php

namespace Softworx\RocXolid\DevKit\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
// rocXolid devkit package provider
use Softworx\RocXolid\DevKit\ServiceProvider as PackageServiceProvider;

/**
 * rocXolid views & composers service provider.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid\DevKit
 * @version 1.0.0
 */
class ViewServiceProvider extends IlluminateServiceProvider
{
    /**
     * Bootstrap rocXolid view services.
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    public function boot()
    {
        $this
            ->load()
            ->setComposers();

        return $this;
    }

    /**
     * Load views.
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    private function load(): IlluminateServiceProvider
    {
        // customized views preference
        $this->loadViewsFrom(PackageServiceProvider::viewsPublishPath(), 'rocXolid:devkit');
        // pre-defined views fallback
        $this->loadViewsFrom(PackageServiceProvider::viewsSourcePath(dirname(dirname(__DIR__))), 'rocXolid:devkit');

        return $this;
    }

    /**
     * Set view composers for blade templates.
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    private function setComposers(): IlluminateServiceProvider
    {
        foreach (config('rocXolid.devkit.general.composers', []) as $view => $composer) {
            View::composer($view, $composer);
        }

        return $this;
    }
}
