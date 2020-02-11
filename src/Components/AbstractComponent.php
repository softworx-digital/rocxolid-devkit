<?php

namespace Softworx\RocXolid\DevKit\Components;

use Softworx\RocXolid\Components\AbstractComponent as RocXolidAbstractComponent;

abstract class AbstractComponent extends RocXolidAbstractComponent
{
    protected $view_package = 'rocXolid:devkit';

    protected $view_directory = '';
}