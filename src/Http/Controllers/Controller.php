<?php

namespace Softworx\RocXolid\DevKit\Http\Controllers;

use Softworx\RocXolid\DevKit\Components\Dashboard\Main as MainDashboard;

class Controller extends AbstractController
{
    public function index()
    {
        return (new MainDashboard($this))->render();
    }
}
