<?php

namespace Softworx\RocXolid\DevKit\Console\Commands\Generate;

class Notification extends AbstractCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'rocXolid:generate:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new notification class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Notification';
}