<?php

namespace Softworx\RocXolid\DevKit\Console\Commands\Generate\Traits;

trait Settable
{
    /**
     * Settings of the file type to be generated
     *
     * @var array
     */
    protected $settings = [];

    /**
     * Find the type's settings and set local var
     */
    public function setSettings($type)
    {
        if (array_key_exists($type, $this->getConfig('settings'))) {
            $settings = $this->getConfig('settings')[$type];
        } else {
            $this->error(sprintf('No settings key by the type name [%s] provided', $type));
        }

        $this->settings = array_merge($this->getConfig('defaults'), $settings);
    }

    /**
     * Return false or the value for given key from the settings
     *
     * @param $key
     *
     * @return bool
     */
    public function settingsKey($key)
    {
        if (!is_array($this->settings) || !isset($this->settings[$key])) {
            return false;
        }

        return $this->settings[$key];
    }

    /**
     * Get the directory format setting's value
     */
    protected function settingsDirectoryFormat()
    {
        return $this->settingsKey('directory_format') ? $this->settings['directory_format'] : false;
    }

    /**
     * Get the directory format setting's value
     */
    protected function settingsDirectoryNamespace()
    {
        return $this->settingsKey('directory_namespace') ? $this->settings['directory_namespace'] : false;
    }
}
