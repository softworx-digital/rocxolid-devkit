<?php

namespace Softworx\RocXolid\DevKit\Components\Commander\Dashboard;

use Softworx\RocXolid\DevKit\Components\AbstractComponent;
use Softworx\RocXolid\Contracts\Modellable;
use Softworx\RocXolid\Contracts\Controllable;
use Softworx\RocXolid\Traits\Modellable as ModellableTrait;
use Softworx\RocXolid\Traits\Controllable as ControllabeTrait;
// @todo - methodable
class Main extends AbstractComponent implements Modellable, Controllable
{
    use ModellableTrait,
        ControllabeTrait;
}