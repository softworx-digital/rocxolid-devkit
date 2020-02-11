<?php

namespace Softworx\RocXolid\DevKit\Console\Commands\Generate\Forms\ResourceControllerPermissions;

use Softworx\RocXolid\Forms\Fields\Type\Input;
use Softworx\RocXolid\Forms\Fields\Type\Select;
use Softworx\RocXolid\Forms\Fields\Type\Switchery;
use Softworx\RocXolid\Forms\Fields\Type\Radio;
use Softworx\RocXolid\Forms\Fields\Type\Checkbox;
use Softworx\RocXolid\Forms\Fields\Type\FormFieldGroup;
use Softworx\RocXolid\Forms\Fields\Type\FormFieldGroupAddable;
use Softworx\RocXolid\DevKit\Console\Commands\Generate\Forms\AbstractForm as GenerateCommandAbstractForm;

class General extends GenerateCommandAbstractForm
{
    protected static $translation_param = 'commands.generate.resource-controller-permissions';

    protected $fieldgroups = [
        FormFieldGroup::DEFAULT_NAME => [
            'type' => FormFieldGroup::class,
            'options' => []
        ],
    ];

    protected $fields = [
        'guard_name' => [
            'type' => Select::class,
            'options' => [
                'group' => FormFieldGroup::DEFAULT_NAME,
                'placeholder' => [
                    'title' => 'guard_name'
                ],
                'validation' => [
                    'rules' => [
                        'required',
                    ],
                    'error-messages' => [
                        'required' => 'custom-messages-required',
                    ],
                ],
                'choices' => [
                    'auth.rocXolid' => 'auth.rocXolid',
                ]
            ],
        ],
        'replace' => [
            'type' => Checkbox::class,
            'options' => [
                'group' => FormFieldGroup::DEFAULT_NAME,
                'label' => [
                    'title' => 'replace'
                ],
            ],
        ],
    ];

/*
    protected function getButtonGroupsDefinition()
    {
        return $this->buttongroups + [
            'control' => [
                'type' => ButtonGroup::class,
                'options' => [
                    'toolbar' => ButtonToolbar::DEFAULT_NAME,
                    'wrapper' => false,
                    'attributes' => [
                        'class' => 'btn-group pull-left'
                    ],
                ],
            ],
        ];
    }
*/
/*
    protected function getButtonsDefinition()
    {
        return $this->buttons + [
            // controls group
            'add' => [
                'type' => Button::class,
                'options' => [
                    'group' => 'control',
                    'dom-data' => [
                        'duplicate-form-row' => ':form-first'
                    ],
                    'label' => [
                        'title' => 'Add attribute',
                    ],
                    'attributes' => [
                        'class' => 'btn btn-primary'
                    ],
                ],
            ],
        ];
    }
*/
}