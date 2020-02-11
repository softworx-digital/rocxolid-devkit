<?php

namespace Softworx\RocXolid\DevKit\Console\Commands\Generate;

use DB;
use Route;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Exception\RuntimeException;
use Illuminate\Http\Request;
use Softworx\RocXolid\UserManagement\Models\Permission;
use Softworx\RocXolid\Http\Controllers\Contracts\Crudable;
use Spatie\Permission\PermissionRegistrar;

// @todo - types (return)
// @todo - toto dat niekam inam (as Seed), lebo negeneruje file, ale na to treba aj upravit AbstractCommand
class ResourceControllerPermissions extends AbstractCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'rocXolid:generate:resource-controller-permissions';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create permissions for CRUD controllers and their actions';
    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Permission';
    /**
     * Subgeneration calls confirmation need switch.
     *
     * @var bool
     */
    private $needConfirmation = false;

    private static $controller_method_groups = [
        'read-only',
        'write',
    ];

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $controllers = [];

        if ($this->option('replace') === true)
        {
            app(PermissionRegistrar::class)->forgetCachedPermissions();

            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            DB::table('model_has_permissions')->truncate();
            DB::table('permissions')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            $this->info('Permissions truncated!');
        }

        foreach (Route::getRoutes()->getRoutes() as $route)
        {
            $action = $route->getAction();

            if (array_key_exists('controller', $action))
            {
                list($controller, $method) = explode('@', $action['controller']);

                if (in_array(Crudable::class, class_implements($controller)))
                {
                    //$controllers[] = $action['controller'];
                    $controllers[] = $controller;
                }
            }
        }

        $controllers = array_unique($controllers);
        $permissions = [];

        foreach ($controllers as $controller)
        {
            $controller_split = explode('\\', $controller);

            end($controller_split);

            $controller_shortened = prev($controller_split);

            foreach (static::$controller_method_groups as $method_group)
            {
                $name = sprintf('%s.%s', snake_case($controller_shortened), $method_group);

                try
                {
                    //$permission = Permission::create([
                    $permission = Permission::firstOrNew([
                        'name' => $name,
                        'guard_name' => $this->getGuardName(),
                        'controller_class' => $controller,
                        'controller_method_group' => $method_group,
                        //'controller_method',
                    ]);

                    $permission->save();
                }
                catch (\Exception $e)
                {
                    throw new RuntimeException($e->getMessage());
                }

                $permissions[] = $name;
            }
        }

        $this->info(sprintf('Created permissions: %s', print_r($permissions, true)));
    }

    public function getRequestArguments(Request $request, $arguments = [], $options = [])
    {
        //$form = $command->getForm();
        // zatial takto, inac pofiltrovat ako prienik argumentov commandu a fieldov + zohladnit arguments v parametroch metody
        //$arguments += $request->all();
        /*
        $arguments += [
            'name' => $request->input('name')
        ];
        */
        $arguments += [
            'guard_name' => $request->input('_data.guard_name'),
        ];

        $options += [
            '--replace'   => $request->has('_data.replace'),
        ];

        return $arguments + $options;
    }

    /**
     * @return string
     */
    private function getGuardName()
    {
        $name = $this->argument('guard_name');

        return $name;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [[
            'guard_name',
            InputArgument::REQUIRED,
            'The middleware guard name.'
        ]];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array_merge(parent::getOptions(), [[
            'replace',
            null,
            InputOption::VALUE_OPTIONAL,
            'Flag to replace existing permissions',
            null
        ]]);
    }
}