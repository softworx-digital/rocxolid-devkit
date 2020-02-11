<?php

return [

    /*
    |--------------------------------------------------------------------------
    | The singular resource words that will not be pluralized
    | For Example: $ php artisan generate:resource admin.bar
    | The url will be /admin/bars and not /admins/bars
    |--------------------------------------------------------------------------
    */

    'reserve_words' => [
        'app',
        'website',
        'admin'
    ],

    /*
    |--------------------------------------------------------------------------
    | The default keys and values for the settings of each type to be generated
    |--------------------------------------------------------------------------
    */

    'defaults' => [
        'namespace'           => '',
        'path'                => base_path() . '/app/',
        'prefix'              => '',
        'postfix'             => '',
        'file_type'           => '.php',
        'dump_autoload'       => false,
        'directory_format'    => '',
        'directory_namespace' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Types of files that can be generated
    |--------------------------------------------------------------------------
    */

    'settings' => [
        'view' => [
            'path'                => base_path() . '/resources/views/',
            'file_type'           => '.blade.php',
            'directory_format'    => 'strtolower',
            'directory_namespace' => true,
        ],
        'controller' => [
            'namespace'           => '\Http\Controllers',
            'path'                => base_path() . '/app/Http/Controllers/',
            'postfix'             => '/Controller',
            'directory_namespace' => true,
            //'dump_autoload'       => true,
            'dir'                 => 'Http/Controllers/',
        ],
        'repository' => [
            'namespace' => '\Repositories',
            'path'      => base_path() . '/app/Repositories',
            'dir'       => 'Repositories/',
            'postfix'   => '/Repository',
        ],
        'model' => [
            'namespace' => '\Models',
            'path'      => base_path() . '/app/Models/',
            'dir'       => 'Models/',
        ],
        'form-create' => [
            'namespace' => '\Models\Forms',
            'path'      => base_path() . '/app/Models/Forms',
            'dir'       => 'Models/Forms/',
            'postfix'   => '/Create',
        ],
        'form-update' => [
            'namespace' => '\Models\Forms',
            'path'      => base_path() . '/app/Models/Forms',
            'dir'       => 'Models/Forms/',
            'postfix'   => '/Update',
        ],
        'notification' => [
            'namespace' => '\App\Notifications',
            'path'      => base_path() . '/app/Notifications/',
        ],
        'event' => [
            'namespace' => '\App\Events',
            'path'      => base_path() . '/app/Events/',
        ],
        'listener' => [
            'namespace' => '\App\Listeners',
            'path'      => base_path() . '/app/Listeners/',
        ],
        'migration' => [
            'path'      => base_path() . '/database/migrations/',
        ],
        'seed' => [
            'path'      => base_path() . '/database/seeds/',
            'postfix'   => 'TableSeeder',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resource Views [stub_key | name of the file]
    |--------------------------------------------------------------------------
    */

    'resource_views' => [
        'view_index'    => 'index',
        'view_add_edit' => 'add_edit',
        'view_show'     => 'show',
    ],

    /*
    |--------------------------------------------------------------------------
    | Where the stubs for the generators are stored
    |--------------------------------------------------------------------------
    */

    'stub_directory'                => dirname(__FILE__) . '/../resources/stubs',

    'example_stub'                  => 'example.stub',
    'model_stub'                    => 'model.stub',
    'form-create_stub'              => 'form-create.stub',
    'form-update_stub'              => 'form-update.stub',
    'model_plain_stub'              => 'model.plain.stub',
    'migration_stub'                => 'migration.stub',
    'migration_plain_stub'          => 'migration.plain.stub',
    'repository_stub'               => 'repository.stub',
    'controller_stub'               => 'controller.stub',
    'controller_plain_stub'         => 'controller.plain.stub',
    'pivot_stub'                    => 'pivot.stub',
    'seed_stub'                     => 'seed.stub',
    'seed_plain_stub'               => 'seed.plain.stub',
    'view_stub'                     => 'view.stub',
    'view_index_stub'               => 'view.index.stub',
    'view_add_edit_stub'            => 'view.add_edit.stub',
    'view_show_stub'                => 'view.show.stub',
    'schema_create_stub'            => 'schema-create.stub',
    'schema_change_stub'            => 'schema-change.stub',
    'notification_stub'             => 'notification.stub',
    'event_stub'                    => 'event.stub',
    'listener_stub'                 => 'listener.stub',
    'many_many_relationship_stub'   => 'many_many_relationship.stub',
];