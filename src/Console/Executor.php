<?php

namespace Softworx\RocXolid\DevKit\Console;

use Artisan;

class Executor implements Contracts\Executor
{
    protected $artisan;

    protected $return_code;

    protected $output;

    public function __construct(Artisan $artisan)
    {
        $this->artisan = $artisan;
    }

    public function execute($command, $arguments = [])
    {
        $this->return_code = Artisan::call($command, $arguments); //, [ 'user' => 1, '--queue' => 'default' ]);

        $this->output = Artisan::output();

        return $this;
    }

    public function getReturnCode()
    {
        return $this->return_code;
    }

    public function getOutput()
    {
        return $this->output;
    }
}