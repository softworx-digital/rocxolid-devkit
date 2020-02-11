<?php

namespace Softworx\RocXolid\DevKit\Console\Commands\Generate\Forms\Resource;

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
    protected static $translation_param = 'commands.generate.resource';

    protected $fieldgroups = [
        FormFieldGroup::DEFAULT_NAME => [
            'type' => FormFieldGroup::class,
            'options' => []
        ],
        FormFieldGroupAddable::DEFAULT_NAME => [
            'type' => FormFieldGroupAddable::class,
            'options' => []
        ]
    ];

    protected $fields = [
        /*'namespace' => [
            'type' => Input::class,
            'options' => [
                'group' => FormFieldGroupAddable::DEFAULT_NAME,
                'array' => true,
                'placeholder' => [
                    'title' => 'namespace'
                ],
                'validation' => [
                    'rules' => [
                        'required',
                        'max:255',
                        'min:2',
                    ],
                ],
            ],
        ],*/
        'package' => [
            'type' => Select::class,
            'options' => [
                'group' => FormFieldGroup::DEFAULT_NAME,
                'validation' => [
                    'rules' => [
                        'required',
                    ],
                    'error-messages' => [
                        'required' => 'custom-messages-required',
                    ],
                ],
                'choices' => [
                    'App' => 'App',
                    'rxCommon' => 'Softworx\RocXolid\Common',
                    'rxCMS' => 'Softworx\RocXolid\CMS',
                    'rxCommerce' => 'Softworx\RocXolid\Commerce',
                    'rxCommunication' => 'Softworx\RocXolid\Communication',
                ],
            ],
        ],
        'resource' => [
            'type' => Input::class,
            'options' => [
                'group' => FormFieldGroup::DEFAULT_NAME,
                'placeholder' => [
                    'title' => 'resource'
                ],
                'validation' => [
                    'rules' => [
                        'required',
                        'max:255',
                        'min:2',
                    ],
                ],
            ],
        ],
        'force' => [
            'type' => Checkbox::class,
            'options' => [
                'group' => FormFieldGroup::DEFAULT_NAME,
                'label' => [
                    'title' => 'force'
                ],
            ],
        ],
        'migrate' => [
            'type' => Checkbox::class,
            'options' => [
                'group' => FormFieldGroup::DEFAULT_NAME,
                'label' => [
                    'title' => 'migrate'
                ],
            ],
        ],
        'attribute' => [
            'type' => Input::class,
            'options' => [
                'group' => FormFieldGroupAddable::DEFAULT_NAME,
                'array' => true,
                'placeholder' => [
                    'title' => 'attribute',
                ],/*
                'wrapper' => [
                    'attributes' => [
                        'class' => 'col-xs-4',
                    ],
                ],*/
                'validation' => [
                    'rules' => [
                        'required',
                        'max:255',
                        'min:2',
                    ],
                ],
            ],
        ],
        'type' => [
            'type' => Select::class,
            'options' => [
                'group' => FormFieldGroupAddable::DEFAULT_NAME,
                'array' => true,
                'placeholder' => [
                    'title' => 'type'
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
                    'string'                => 'string',
                    'integer'               => 'integer',
                    'unsignedInteger'       => 'unsignedInteger',
                    'boolean'               => 'boolean',
                    'date'                  => 'date',
                    'dateTime'              => 'dateTime',
                    'decimal'               => 'decimal',
                    'text'                  => 'text',
                    ''                      => '-----',
                    'morphs'                => 'morphs',
                    'nullableMorphs'        => 'nullableMorphs',
                    ''                      => '-----',
                    'ipAddress'             => 'ipAddress',
                    'macAddress'            => 'macAddress',
                    'json'                  => 'json',
                    'jsonb'                 => 'jsonb',
                    'rememberToken'         => 'rememberToken',
                    'nullableTimestamps'    => 'nullableTimestamps',
                    ''                      => '-----',
                    'binary'                => 'binary',
                    'char'                  => 'char',
                    'enum'                  => 'enum',
                    ''                      => '-----',
                    'bigIncrements'         => 'bigIncrements',
                    'smallIncrements'       => 'smallIncrements',
                    'mediumIncrements'      => 'mediumIncrements',
                    ''                      => '-----',
                    'bigInteger'            => 'bigInteger',
                    'unsignedBigInteger'    => 'unsignedBigInteger',
                    'mediumInteger'         => 'mediumInteger',
                    'unsignedMediumInteger' => 'unsignedMediumInteger',
                    'smallInteger'          => 'smallInteger',
                    'unsignedSmallInteger'  => 'unsignedSmallInteger',
                    'tinyInteger'           => 'tinyInteger',
                    'unsignedTinyInteger'   => 'unsignedTinyInteger',
                    'double'                => 'double',
                    'float'                 => 'float',
                    ''                      => '-----',
                    'longText'              => 'longText',
                    'mediumText'            => 'mediumText',
                    ''                      => '-----',
                    'dateTimeTz'            => 'dateTimeTz',
                    'time'                  => 'time',
                    'timeTz'                => 'timeTz',
                    'timestamp'             => 'timestamp',
                    'timestampTz'           => 'timestampTz',
                    'timestamps'            => 'timestamps',
                    'timestampsTz'          => 'timestampsTz',
                ]
            ],
        ],/*
        'length' => [
            'type' => Input::class,
            'options' => [
                'group' => FormFieldGroupAddable::DEFAULT_NAME,
                'array' => true,
                'placeholder' => [
                    'title' => 'length'
                ],
                'validation' => [
                    'rules' => [
                        'max:255',
                    ],
                ],
            ],
        ],
        'default_value' => [
            'type' => Input::class,
            'options' => [
                'group' => FormFieldGroupAddable::DEFAULT_NAME,
                'array' => true,
                'placeholder' => [
                    'title' => 'default_value'
                ],
                'validation' => [
                    'rules' => [
                        'max:255',
                    ],
                ],
            ],
        ],*//*
        'index_type' => [
            'type' => Select::class,
            'options' => [
                'group' => FormFieldGroupAddable::DEFAULT_NAME,
                'array' => true,
                'placeholder' => [
                    'title' => 'index_type'
                ],
            ],
        ],*/
        'nullable' => [
            'type' => Checkbox::class,
            'options' => [
                'group' => FormFieldGroupAddable::DEFAULT_NAME,
                'array' => true,
                'label' => [
                    'title' => 'nullable',
                ],
            ],
        ],        
        'multilanguage' => [
            'type' => Checkbox::class,
            'options' => [
                'group' => FormFieldGroupAddable::DEFAULT_NAME,
                'array' => true,
                'label' => [
                    'title' => 'multilanguage',
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