<?php

namespace Softworx\RocXolid\DevKit\Components\Commander\Command;

use Softworx\RocXolid\DevKit\Components\AbstractActiveComponent;
use Symfony\Component\Console\Command\Command;
use Softworx\RocXolid\Forms\Contracts\Formable;

// @todo navratove typy
class Tab extends AbstractActiveComponent
{
    protected $command;

    protected $form_component;

    public function __construct(Command $command)
    {
        $this->command = $command;

        if ($this->isCommandFormable() && $this->hasForm()) {
            $this->form_component = $this->getCommand()->getFormComponent();
        }
    }

    public function getCommand()
    {
        return $this->command;
    }

    public function isCommandFormable()
    {
        return $this->getCommand() instanceof Formable;
    }

    public function hasForm()
    {
        return $this->getCommand()->hasFormAssigned()
            || $this->getCommand()->hasFormClass();
    }

    public function getFormComponent()
    {
        return $this->form_component;
    }
}
