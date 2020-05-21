<?php

namespace Softworx\RocXolid\DevKit\Console\Commands\Generate\Forms\View;

use Softworx\RocXolid\Forms\Fields\Type\FormFieldGroupAddable;
use Softworx\RocXolid\Forms\Fields\Type\Input;
use Softworx\RocXolid\DevKit\Console\Commands\Generate\Forms\AbstractForm as GenerateCommandAbstractForm;

class General extends GenerateCommandAbstractForm
{
    protected $translation_param = 'commands.generate.view';

    protected $fields = [
        'name' => [
            'type' => Input::class,
            'options' => [
                'placeholder' => [
                    'title' => 'name'
                ],
                'validation' => [
                    'rules' => [
                        'required',
                        'max:100',
                        'min:2',
                    ],
                ],
            ],
        ],
    ];
}