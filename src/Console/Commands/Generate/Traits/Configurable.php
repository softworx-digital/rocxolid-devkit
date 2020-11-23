<?php

namespace Softworx\RocXolid\DevKit\Console\Commands\Generate\Traits;

trait Configurable
{
    protected $prefix_pattern = 'rocXolid.devkit.generators.%s';

    public function getConfigPrefixPattern()
    {
        return $this->prefix_pattern;
    }

    public function setConfigPrefixPattern($prefix_pattern)
    {
        $this->prefix = $prefix_pattern;

        return $this;
    }

    protected function getConfig($key)
    {
        return config(sprintf($this->prefix_pattern, $key));
    }
}
