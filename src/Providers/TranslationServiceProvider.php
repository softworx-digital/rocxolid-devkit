<?php

namespace Softworx\RocXolid\DevKit\Providers;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
// rocXolid devkit package provider
use Softworx\RocXolid\DevKit\ServiceProvider as PackageServiceProvider;

/**
 * rocXolid translation service provider.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid\DevKit
 * @version 1.0.0
 */
class TranslationServiceProvider extends IlluminateServiceProvider
{
    /**
     * Bootstrap rocXolid translation services.
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    public function boot()
    {
        $this
            ->load();

        return $this;
    }

    /**
     * Load translations.
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    private function load()
    {
        $this->loadTranslationsFrom(PackageServiceProvider::translationsSourcePath(dirname(dirname(__DIR__))), 'rocXolid-devkit');

        return $this;
    }
}
