<?php

namespace Softworx\RocXolid\DevKit\Console\Commands\Generate\Traits;

use Softworx\RocXolid\Forms\Contracts\Formable as FormableContract;
use Softworx\RocXolid\Forms\Traits\Formable as RocXolidFormable;
use Softworx\RocXolid\Components\Contracts\Formable as FormableComponent;
use Softworx\RocXolid\DevKit\Components\Commander\Command\Form as FormComponent;

trait Formable
{
    use RocXolidFormable;

    public function getFormComponent($param = FormableContract::FORM_PARAM): FormableComponent
    {
        if (!isset($this->form_components[$param])) {
            $this->form_components[$param] = (new FormComponent())
                ->setForm($this->getForm($param))
                ->setCommand($this);
        }

        return $this->form_components[$param];
    }
}
