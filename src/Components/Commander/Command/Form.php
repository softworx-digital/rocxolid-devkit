<?php

namespace Softworx\RocXolid\DevKit\Components\Commander\Command;

use Symfony\Component\Console\Command\Command;
use Softworx\RocXolid\Components\Contracts\Formable;
use Softworx\RocXolid\Components\Forms\Form as RocXolidForm;

class Form extends RocXolidForm
{
    protected $view_package = 'rocXolid:devkit';

    protected $command;

    public function setCommand(Command $command): Formable
    {
        $this->command = $command;

        return $this;
    }

    public function getCommand(): Command
    {
        return $this->command;
    }
}
