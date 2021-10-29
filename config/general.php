<?php

return [
    /**
     * View composers.
     */
    'composers' => [
        'rocXolid:devkit::*' => Softworx\RocXolid\Devkit\Composers\ViewComposer::class,
    ],
    'command-binding-pattern' => 'command.rocXolid.devkit.%s',
    'command-binding-tag' => 'command.rocXolid.devkit',

    'command-namespace' => [
        'generator' => 'rocXolid:generate',
    ],

    'commands' => [
        'publish' => Softworx\RocXolid\DevKit\Console\Commands\Generate\Publish::class,
        'resource' => Softworx\RocXolid\DevKit\Console\Commands\Generate\Resource::class,
        'model' => Softworx\RocXolid\DevKit\Console\Commands\Generate\Model::class,
        'view' => Softworx\RocXolid\DevKit\Console\Commands\Generate\View::class,
        'controller' => Softworx\RocXolid\DevKit\Console\Commands\Generate\Controller::class,
        'migration' => Softworx\RocXolid\DevKit\Console\Commands\Generate\Migration::class,
        'migrate.pivot' => Softworx\RocXolid\DevKit\Console\Commands\Generate\MigrationPivot::class,
        'seed' => Softworx\RocXolid\DevKit\Console\Commands\Generate\Seed::class,
        'notification' => Softworx\RocXolid\DevKit\Console\Commands\Generate\Notification::class,
        'event' => Softworx\RocXolid\DevKit\Console\Commands\Generate\Event::class,
        'listener' => Softworx\RocXolid\DevKit\Console\Commands\Generate\Listener::class,
        'event.listener' => Softworx\RocXolid\DevKit\Console\Commands\Generate\EventListener::class,
        'file' => Softworx\RocXolid\DevKit\Console\Commands\Generate\File::class,
        'permissions' => Softworx\RocXolid\DevKit\Console\Commands\Generate\ResourceControllerPermissions::class,
    ],
];