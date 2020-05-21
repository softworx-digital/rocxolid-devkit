<?php

namespace Softworx\RocXolid\DevKit\Console\Commands\Generate;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

// @todo - types (return)
class Resource extends AbstractCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'rocXolid:generate:resource';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new resource (model, views, controller, migration, forms, seed) for given namespace. @TODO - formulare rozdelit na standardne CRUD - zohladnit metody';
    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Resource';
    /**
     * Subgeneration calls confirmation need switch.
     *
     * @var bool
     */
    private $needConfirmation = false;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->package = $this->getPackageOnly();
        $this->resource = $this->getResourceOnly();
        $this->settings = $this->getConfig('defaults');

        $this->callMigration();
        $this->callModel();
        $this->callController();
        $this->callForms();
        $this->callRepository();
        //$this->callView();
        //$this->callSeed();

        if ($this->option('migrate'))
        {
            $this->callMigrate();
        }

        $this->info('All Done!');
        $this->info('Remember to add ' . "`Route::resource('" . str_replace('_', '-', $this->getCollectionName()) . "', '" . $this->getResourceControllerName() . "');`" . ' in `routes\\web.php`');
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
            'package' => $request->input('_data.package'),
            'resource' => $request->input('_data.resource'),
        ];
        $schema = [];

        if ($request->has('_datagroup'))
        {
            foreach ($request->input('_datagroup') as $input)
            {
                $schema[] = implode(':', array_filter([
                    $input['attribute'],
                    $input['type'],
                ]));
            }
        }

        $options += [
            '--migrate' => $request->has('_data.migrate'),
            '--force'   => $request->has('_data.force'),
            '--schema'  => implode(', ', $schema),
        ];

        return $arguments + $options;
    }

    /**
     * Call the rocXolid:generate:model command
     */
    private function callModel()
    {
        $name = $this->getModelName();

        $resourceString = $this->getResourceOnly();
        $resourceStringLength = strlen($this->getResourceOnly());

        if ($resourceStringLength > 18)
        {
            $ans = !$this->needConfirmation || $this->confirm("Your resource {$resourceString} may have too many characters to use for many to many relationships. The length is {$resourceStringLength}. Continue? [yes|no]");

            if ($ans === false)
            {
                echo "rocXolid:generate:resource cancelled!";
                die;
            }
        }

        if (!$this->needConfirmation || $this->confirm("Create a $name model? [yes|no]"))
        {
            $this->callCommandFile('model', $this->getPackagePath());
        }
    }

    /**
     * Generate the resource views
     */
    private function callView()
    {
        if (!$this->needConfirmation || $this->confirm("Create crud views for the $this->resource resource? [yes|no]"))
        {
            $views = $this->getConfig('resource_views');

            foreach ($views as $key => $name)
            {
                $resource = $this->argument('resource');

                if (Str::contains($resource, '.'))
                {
                    $resource = str_replace('.', '/', $resource);
                }

                $this->callCommandFile('view', $this->getViewPath($resource), $key, ['--name' => $name]);
            }
        }
    }

    /**
     * Generate the resource controller
     */
    private function callController()
    {
        $name = $this->getResourceControllerName();

        if (!$this->needConfirmation || $this->confirm("Create a controller ($name) for the $this->resource resource? [yes|no]"))
        {
            $arg = $this->getArgumentResource();
            $name = substr_replace($arg, Str::plural($this->resource), strrpos($arg, $this->resource), strlen($this->resource));

            //$this->callCommandFile('controller', $name);
            $this->callCommandFile('controller', $this->getPackagePath());
        }
    }

    /**
     * Generate the resource forms
     */
    private function callForms()
    {
        if (!$this->needConfirmation || $this->confirm("Create forms for the $this->resource resource? [yes|no]"))
        {
            $this->callCommandFile('form-create', $this->getPackagePath());
            $this->callCommandFile('form-update', $this->getPackagePath());
        }
    }

    /**
     * Generate the resource controller
     */
    private function callRepository()
    {
        $name = $this->getResourceRepositoryName();

        if (!$this->needConfirmation || $this->confirm("Create a repository ($name) for the $this->resource resource? [yes|no]"))
        {
            $arg = $this->getArgumentResource();
            $name = substr_replace($arg, Str::plural($this->resource), strrpos($arg, $this->resource), strlen($this->resource));

            //$this->callCommandFile('repository', $name);
            $this->callCommandFile('repository', $this->getPackagePath());
        }
    }

    /**
     * Call the rocXolid:generate:migration command
     */
    private function callMigration()
    {
        $name = $this->getMigrationName($this->option('migration'));

        if (!$this->needConfirmation || $this->confirm("Create a migration ($name) for the $this->resource resource? [yes|no]"))
        {
            $this->callCommand('migration', $name, [
                '--model'  => false,
                '--schema' => $this->option('schema')
            ]);
        }
    }

    /**
     * Call the rocXolid:generate:seed command
     */
    private function callSeed()
    {
        $name = $this->getSeedName() . $this->getConfig('settings.seed.postfix');

        if (!$this->needConfirmation || $this->confirm("Create a seed ($name) for the $this->resource resource? [yes|no]"))
        {
            $this->callCommandFile('seed');
        }
    }

    /**
     * Call the migrate command
     */
    protected function callMigrate()
    {
        if (!$this->needConfirmation || $this->confirm('Migrate the database? [yes|no]'))
        {
            $this->call('migrate');
        }
    }

    /**
     * @param       $command
     * @param       $name
     * @param array $options
     */
    private function callCommand($command, $name, $options = [])
    {
        $options = array_merge($options, [
            'name'    => $name,
            '--force' => $this->optionForce(),
            '--plain' => $this->optionPlain(),
        ]);

        /*
        dump(sprintf('rocXolid:generate:%s', $command));
        dump($options);
        */

        $this->call(sprintf('rocXolid:generate:%s', $command), $options);
    }

    /**
     * Call the rocXolid:generate:file command to generate the given file
     *
     * @param       $type
     * @param null  $name
     * @param null  $stub
     * @param array $options
     */
    private function callCommandFile($type, $path = null, $name = null, $stub = null, $schema = null, $options = [])
    {
        $name = sprintf('%s--%s', $this->getPackageNamespace(), ($name ? $name : $this->argument('resource')));
// @todo zahrnut package, zatial tmp riesenie oddelenie '--'
        $options = array_merge($options, [
            //'package' => ($package ? $package : $this->argument('package')),
            'path'     => ($path ? $path : $this->argument('path')),
            'name'     => $name,
            '--type'   => $type,
            '--force'  => $this->optionForce(),
            '--plain'  => $this->optionPlain(),
            '--stub'   => ($stub ? $stub : $this->optionStub()),
            '--schema' => ($schema ? $schema : $this->optionSchema()),
        ]);

        /*
        dump('rocXolid:generate:file');
        dump($options);
        */

        $this->call('rocXolid:generate:file', $options);
    }

    /**
     * If there are '.' in the name, get the last occurence
     *
     * @return string
     */
    private function getPackageOnly()
    {
        $name = $this->argument('package');

        if (!Str::contains($name, '.'))
        {
            return $name;
        }

        return substr($name, strripos($name, '.') + 1);
    }

    /**
     * The resource argument
     * Lowercase and singular each word
     *
     * @return array|mixed|string
     */
    private function getArgumentResource()
    {
        $name = $this->argument('resource');

        if (Str::contains($name, '/'))
        {
            $name = str_replace('/', '.', $name);
        }

        if (Str::contains($name, '\\'))
        {
            $name = str_replace('\\', '.', $name);
        }

        if (Str::contains($name, '.'))
        {
            $parts = [];
            $names = explode('.', $name);

            foreach ($names as $part)
            {
                $parts[] = Str::kebab($part);
            }

            end($parts);
            $key = key($parts);
            $parts[$key] = Str::singular($parts[$key]);
            reset($parts);

            $name = implode('.', $parts);
        }
        else
        {
            $name = Str::kebab(Str::singular($name));
        }

        return $name;
    }

    /**
     * If there are '.' in the name, get the last occurence
     *
     * @return string
     */
    private function getResourceOnly()
    {
        $name = $this->getArgumentResource();

        if (!Str::contains($name, '.'))
        {
            return $name;
        }

        return substr($name, strripos($name, '.') + 1);
    }

    /**
     * Get the Controller name for the resource
     *
     * @return string
     */
    private function getResourceControllerName()
    {
        return $this->getControllerName(Str::plural($this->resource), false) . $this->getConfig('settings.controller.postfix');
    }

    /**
     * Get the Repository name for the resource
     *
     * @return string
     */
    private function getResourceRepositoryName()
    {
        return $this->getRepositoryName(Str::plural($this->resource), false) . $this->getConfig('settings.repository.postfix');
    }

    /**
     * Get the Form name for the resource
     *
     * @return string
     */
    private function getResourceFormName($param)
    {
        return $this->getFormName(Str::plural($this->resource), false) . $this->getConfig(sprintf('settings.form-%s.postfix', $param));
    }

    /**
     * Get the name for the migration
     *
     * @param null $name
     * @return string
     */
    private function getMigrationName($name = null)
    {
        return 'create_' . Str::plural(str_replace('-', '_', $this->getResourceName($name))) . '_table';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [[
            'package',
            InputArgument::REQUIRED,
            'The package of the resource being generated.'
        ], [
            'resource',
            InputArgument::REQUIRED,
            'The name of the resource being generated.'
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
            'migration',
            null,
            InputOption::VALUE_OPTIONAL,
            'Optional migration name',
            null
        ], [
            'migrate',
            null,
            InputOption::VALUE_OPTIONAL,
            'Apply migration flag',
            null
        ], [
            'schema',
            's',
            InputOption::VALUE_OPTIONAL,
            'Optional schema to be attached to the migration',
            null
        ]]);
    }

    /**
     * Get the path to the package
     *
     * @param $name
     * @return string
     */
    // @todo - do settings
    private function getPackagePath()
    {
        switch ($this->package)
        {
            case 'App':
                return 'app';
            default:
                if ($package_service_provider = $this->package_service->get($this->package)) {
                    $reflection = new \ReflectionClass($package_service_provider);

                    return dirname($reflection->getFileName());
                } else {
                    throw new \InvalidArgumentException(sprintf('Invalid package [%s]', $this->package));
                }
        }
    }

    /**
     * Get the path to the package
     *
     * @param $name
     * @return string
     */
    // @todo - do settings
    private function getPackageNamespace()
    {
        switch ($this->package)
        {
            case 'App':
                return 'App';
            default:
                if ($package_service_provider = $this->package_service->get($this->package)) {
                    $reflection = new \ReflectionClass($package_service_provider);

                    return $reflection->getNamespaceName();
                } else {
                    throw new \InvalidArgumentException(sprintf('Invalid package [%s]', $this->package));
                }
        }
    }
}