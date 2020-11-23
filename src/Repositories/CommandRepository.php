<?php

namespace Softworx\RocXolid\DevKit\Repositories;

use App;
use Softworx\RocXolid\DevKit\Components\Commander\Command\Tab;
use Symfony\Component\Console\Command\ListCommand;

class CommandRepository
{
    public function getTaggedCommands($tag)
    {
        $commands = [
            new ListCommand()
        ];

        return $commands + App::tagged($tag);
    }

    public function getTaggedCommandsTabs($tag)
    {
        $tabs = [];

        foreach ($this->getTaggedCommands($tag) as $command) {
            $tabs[] = new Tab($command); // @todo - classu asi hodit do parametrov metody a resolvovat / bindovat v service provideri
        }

        return $tabs;
    }

    public function getTaggedCommandByName($tag, $name)
    {
        foreach ($this->getTaggedCommands($tag) as $command) {
            if ($command->getName() == $name) {
                return $command;
            }
        }

        return null;
    }
}
