<?php

namespace Softworx\RocXolid\DevKit\Console\Commands\Generate;

class View extends AbstractCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'rocXolid:generate:view';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new blade view file';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'View';
}
