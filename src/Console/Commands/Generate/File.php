<?php

namespace Softworx\RocXolid\DevKit\Console\Commands\Generate;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Exception\RuntimeException;
use Illuminate\Console\DetectsApplicationNamespace;

class File extends AbstractCommand
{
    use DetectsApplicationNamespace;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'rocXolid:generate:file';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a file from a stub in the config';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'File';

    /**
     * Get the filename of the file to generate
     *
     * @return string
     */
    private function getFileName()
    {
        // pre-set name in the option
        if ($this->option('name'))
        {
            return $this->option('name') . $this->settings['file_type'];
        }

        $name = $this->getArgumentNameOnly();

        switch ($this->option('type'))
        {
            case 'view':
                $name = $this->getViewName($name);
                break;
            case 'model':
                $name = $this->getModelName($name);
                break;
            case 'controller':
                $name = $this->getControllerName($name);
                break;
            case 'repository':
                $name = $this->getRepositoryName($name);
                break;
            case 'form-create':
                $name = $this->getFormName($name);
                break;
            case 'form-update':
                $name = $this->getFormName($name);
                break;
            case 'seed':
                $name = $this->getSeedName($name);
                break;
            default:
                throw new \InvalidArgumentException(sprintf('Invalid file generation type [%s] given', $this->option('type')));
        }

        return $this->settings['prefix'] . $name . $this->settings['postfix'] . $this->settings['file_type'];
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $name = $this->argumentName();
        // setup
        $this->setSettings($this->option('type'));
        $this->getResourceName($name);
        // check the path where to create and save file
        $path = $this->getPath('');

        if ($this->files->exists($path) && ($this->optionForce() === false))
        {
            return $this->error($this->type . ' already exists!');
        }

        // make all the directories
        $this->makeDirectory($path);

        // build file and save it at location
        $this->files->put($path, $this->buildClass($name));

        // output to console
        $this->info(ucfirst($this->option('type')) . ' created successfully.');
        $this->info('- ' . $name . ' > ' . $path);

        // if we need to run "composer dump-autoload"
        if ($this->settings['dump_autoload'] === true)
        {
            $this->composer->dumpAutoloads();
            $this->info('Autoloads dumped');
        }
    }

    /**
     * Get the destination class path.
     *
     * @param  string $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = $this->getFileName();
        $with_name = boolval($this->option('name'));

        //$path = $this->settings['path'];//base_path()
        // $path = base_path();
        // $path .= '/';
        $path = '';

        if ($this->settingsDirectoryNamespace() === true)
        {
            $path .= $this->getArgumentPath($with_name);
        }

        //$path .= '/';
        $path .= $this->settings['dir'];

        if ($subpackage = $this->getArgumentSubPackage())
        {
            $path .= $subpackage;
            $path .= '/';
        }

        $path .= $name;

        return $path;
    }

    /**
     * Build the class with the given name.
     *
     * @param  string $name
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        // examples used for the placeholders is for name = 'foo.bar'

        // App\Foo
        $stub = str_replace('{{namespace}}', $this->getNamespace($name), $stub);
        // App\Foo
        $stub = str_replace('{{package}}', $this->getPackage($name), $stub);
        // App\Foo
        $stub = str_replace('{{subpackage}}', $this->getSubPackage($name), $stub);
        // App\
        $stub = str_replace('{{rootNamespace}}', $this->getAppNamespace(), $stub);
        // Bar
        $stub = str_replace('{{class}}', $this->getClassName(), $stub);
        // /foo/bar
        $stub = str_replace('{{url}}', $this->getUrl(), $stub);
        // bars
        $stub = str_replace('{{collection}}', $this->getCollectionName(), $stub);
        // Bars
        $stub = str_replace('{{collectionUpper}}', $this->getCollectionUpperName(), $stub);
        // Bar
        $stub = str_replace('{{model}}', $this->getModelName(), $stub);
        // Bar
        $stub = str_replace('{{resource}}', $this->resource, $stub);
        // bar
        $stub = str_replace('{{resourceLowercase}}', $this->resourceLowerCase, $stub);
        // foo-bar
        $stub = str_replace('{{resourceKebabCase}}', Str::kebab($this->resource), $stub);
        // ./resources/views/foo/bar.blade.php
        $stub = str_replace('{{path}}', $this->getPath(''), $stub);
        // foos.bars
        $stub = str_replace('{{view}}', $this->getViewPath($this->getUrl(false)), $stub);
        // bars
        $stub = str_replace('{{table}}', $this->getTableName($this->getUrl()), $stub);
        //
        $stub = str_replace('{{fillable}}', $this->getFillableAttributes($this->optionSchema()), $stub);

        return $stub;
    }

    /**
     * Get the full namespace name for a given class.
     *
     * @param  string $name
     * @param bool    $withApp
     * @return string
     */
    protected function getNamespace($name, $withApp = true)
    {
        $path = (strlen($this->settings['namespace']) >= 2 ? $this->settings['namespace'] . '\\' : '');

        // dont add the default namespace if specified not to in config
        if ($this->settingsDirectoryNamespace() === true)
        {
            $path .= str_replace('/', '\\', $this->getArgumentPath());
        }

        $pieces = array_map('ucfirst', explode('/', $path));

        $namespace = ($withApp === true ? $this->getAppNamespace() : '') . implode('\\', $pieces);

        $namespace = rtrim(ltrim(str_replace('\\\\', '\\', $namespace), '\\'), '\\');
        //$namespace = str_replace('--', '', $namespace);

        return $namespace;
    }

    /**
     * Get the package name for a given class.
     *
     * @param  string $name
     * @param bool    $withApp
     * @return string
     */
    protected function getPackage($name, $withApp = false)
    {
        // dont add the default namespace if specified not to in config
        if ($this->settingsDirectoryNamespace() === true)
        {
            $path = str_replace('.', '\\', $this->getArgumentPackage());
        }

        $pieces = array_map('ucfirst', explode('/', $path));

        $package = ($withApp === true ? $this->getAppNamespace() : '') . implode('\\', $pieces);

        $package = rtrim(ltrim(str_replace('\\\\', '\\', $package), '\\'), '\\');

        return $package;
    }

    /**
     * Get the subpackage name for a given class.
     *
     * @param  string $name
     * @param bool    $withApp
     * @return string
     */
    protected function getSubPackage($name, $withApp = false)
    {
        // dont add the default namespace if specified not to in config
        if ($this->settingsDirectoryNamespace() === true)
        {
            $path = str_replace('.', '\\', $this->getArgumentSubPackage());
        }

        $pieces = array_map('ucfirst', explode('/', $path));

        $subpackage = ($withApp === true ? $this->getAppNamespace() : '') . implode('\\', $pieces);

        $subpackage = rtrim(ltrim(str_replace('\\\\', '\\', $subpackage), '\\'), '\\');

        if (!empty($subpackage))
        {
            $subpackage = sprintf('\%s', $subpackage);
        }

        return $subpackage;
    }

    /**
     * Get the url for the given name
     *
     * @param bool $lowercase
     * @return string
     */
    protected function getUrl($lowercase = true)
    {
        if ($lowercase)
        {
            $url = '/' . rtrim(implode('/', array_map('Str::snake', explode('/', $this->getArgumentPath(true)))), '/');
            $url = (implode('/', array_map('Str::slug', explode('/', $url))));
            return $url;
        }

        return '/' . rtrim(implode('/', explode('/', $this->getArgumentPath(true))), '/');
    }

    /**
     * Get the class name
     * @return mixed
     */
    protected function getClassName()
    {
        return ucwords(Str::camel(str_replace([$this->settings['file_type']], [''], $this->getFileName())));
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [[
            'path',
            InputArgument::REQUIRED,
            'The path of class being generated.'
        ],[
            'name',
            InputArgument::REQUIRED,
            'The name of class being generated.'
        ]];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array_merge([/*[
            'path',
            null,
            InputOption::VALUE_OPTIONAL,
            'The path of file',
            'view'
        ],*/[
            'schema',
            null,
            InputOption::VALUE_OPTIONAL,
            'Model schema',
            'view'
        ], [
            'type',
            null,
            InputOption::VALUE_OPTIONAL,
            'The type of file: model, view, controller, migration, seed',
            'view'
        ]], parent::getOptions());
    }
}