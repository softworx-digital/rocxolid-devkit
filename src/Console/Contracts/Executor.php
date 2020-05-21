<?php

namespace Softworx\RocXolid\DevKit\Console\Contracts;

interface Executor
{
    public function execute($command, $arguments = []);

    public function getReturnCode();

    public function getOutput();
}