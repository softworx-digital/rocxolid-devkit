<?php

namespace Softworx\RocXolid\DevKit\Console\Commands\Generate\Forms;

use Softworx\RocXolid\Forms\Contracts\Form;
use Softworx\RocXolid\Forms\Contracts\Formable as FormableContract;
use Softworx\RocXolid\Forms\AbstractForm as RocXolidAbstractForm;
use Softworx\RocXolid\Forms\Fields\Type\ButtonSubmit;
use Softworx\RocXolid\Forms\Fields\Type\ButtonAnchor;
use Softworx\RocXolid\Forms\Fields\Type\ButtonGroup;
use Softworx\RocXolid\Forms\Fields\Type\ButtonToolbar;

class AbstractForm extends RocXolidAbstractForm
{
    protected $command;

    protected $options = [
        'method' => 'POST',
        'route' => 'rocxolid.devkit.commander.run',
        'class' => 'form-horizontal form-label-left',
    ];

    protected $fieldgroups = true;

    protected $buttontoolbars = true;

    protected $buttongroups = [
        'help' => [
            'type' => ButtonGroup::class,
            'options' => [
                'toolbar' => ButtonToolbar::DEFAULT_NAME,
                'wrapper' => false,
                'attributes' => [
                    'class' => 'btn-group pull-right'
                ],
            ],
        ],
        'run' => [
            'type' => ButtonGroup::class,
            'options' => [
                'toolbar' => ButtonToolbar::DEFAULT_NAME,
                'wrapper' => false,
                'attributes' => [
                    'class' => 'btn-group pull-right'
                ],
            ],
        ],
    ];

    protected $buttons = [
        // run group
        'run' => [
            'type' => ButtonSubmit::class,
            'options' => [
                'group' => 'run',
                'label' => [
                    'title' => 'Run',
                ],
                'attributes' => [
                    'class' => 'btn btn-warning'
                ],
            ],
        ],
        'run-ajax' => [
            'type' => ButtonSubmit::class,
            'options' => [
                'group' => 'run',
                'ajax' => true,
                'label' => [
                    'title' => 'Run AJAX',
                ],                
                'attributes' => [
                    'class' => 'btn btn-warning',
                ],
            ],
        ],
        // help group
        'help' => [
            'type' => ButtonAnchor::class,
            'options' => [
                'group' => 'help',
                'route-form' => 'rocxolid.devkit.commander.help',
                'label' => [
                    'title' => 'Help',
                ],
                'attributes' => [
                    'class' => 'btn btn-primary'
                ],
            ],
        ],
        'help-ajax' => [
            'type' => ButtonAnchor::class,
            'options' => [
                'group' => 'help',
                'route-form' => 'rocxolid.devkit.commander.help',
                'ajax' => true,
                'label' => [
                    'title' => 'Help AJAX',
                ],
                'attributes' => [
                    'class' => 'btn btn-primary'
                ],
            ],
        ],
    ];

    public function setHolderProperties(FormableContract $command): Form
    {
        $this->command = $command;

        return $this;
    }

    public function getCommand()
    {
        return $this->command;
    }

    public function makeRoute($route_name)
    {
        return route($route_name, $this->getCommand()->getName());
    }
}