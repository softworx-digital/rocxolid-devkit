<?php

namespace Softworx\RocXolid\DevKit\Console\Commands\Generate;

class Seed extends AbstractCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'rocXolid:generate:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new database seed class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Seed';
}
