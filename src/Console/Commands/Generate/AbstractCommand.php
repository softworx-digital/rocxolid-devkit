<?php

namespace Softworx\RocXolid\DevKit\Console\Commands\Generate;

// @TODO - settable upratat - mozno dat cele ako Softworx\RocXolid\Traits\Optionable

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Illuminate\Support\Composer;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Exception\RuntimeException;
// rocXolid services
use Softworx\RocXolid\Services\PackageService;
// rocXolid form contracts
use Softworx\RocXolid\Forms\Contracts\Formable;
// rocXolid devkit generate command traits
use Softworx\RocXolid\DevKit\Console\Commands\Generate\Traits\ArgumentsOptionsGettable as ArgumentsOptionsGettableTrait;
use Softworx\RocXolid\DevKit\Console\Commands\Generate\Traits\Settable as SettableTrait;
use Softworx\RocXolid\DevKit\Console\Commands\Generate\Traits\Configurable as ConfigurableTrait;
use Softworx\RocXolid\DevKit\Console\Commands\Generate\Traits\Formable as FormableTrait;
use Softworx\RocXolid\DevKit\Console\Commands\Generate\Migrations\SchemaParser;

abstract class AbstractCommand extends GeneratorCommand implements Formable
{
    use ArgumentsOptionsGettableTrait;
    use SettableTrait;
    use ConfigurableTrait;
    use FormableTrait;

    /**
     * @var \Illuminate\Support\Composer
     */
    protected $composer;

    /**
     * @var \Softworx\RocXolid\Services\PackageService
     */
    protected $package_service;

    /**
     * The package argument
     *
     * @var string
     */
    protected $package = '';

    /**
     * The resource argument
     *
     * @var string
     */
    protected $resource = '';

    /**
     * The lowercase resource argument
     *
     * @var string
     */
    protected $resourceLowerCase = '';

    public function __construct(Filesystem $files, Composer $composer, PackageService $package_service)
    {
        parent::__construct($files);

        $this->composer = $composer;
        $this->package_service = $package_service;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->call('rocXolid:generate:file', [
            'package' => $this->argumentPackage(),
            'name'    => $this->argumentName(),
            '--type'  => strtolower($this->type), // settings type
            '--plain' => $this->optionPlain(), // if plain stub
            '--force' => $this->optionForce(), // force override
            '--stub'  => $this->optionStub(), // custom stub name
            '--name'  => $this->optionName(), // custom name for file
        ]);
    }

    /**
     * Only return the name of the file
     * Ignore the path / namespace of the file
     *
     * @return array|mixed|string
     */
    protected function getArgumentNameOnly()
    {
        $name = $this->argumentName();

        if (Str::contains($name, '--')) { // package - resource delimiter
            $name = substr($name, strrpos($name, '--') + 2);
        }

        if (Str::contains($name, '/')) {
            $name = str_replace('/', '.', $name);
        }

        if (Str::contains($name, '\\')) {
            $name = str_replace('\\', '.', $name);
        }

        if (Str::contains($name, '.')) {
            return substr($name, strrpos($name, '.') + 1);
        }

        return $name;
    }

    /**
     * Only return the name of the file
     * Ignore the path / namespace of the file
     *
     * @return array|mixed|string
     */
    protected function getArgumentPackage()
    {
        $name = $this->argumentName();

        if (Str::contains($name, '--')) { // package - resource delimiter
            $name = substr($name, 0, strrpos($name, '--'));
            $name .= '.'; // to fake further parsing (expects model name as the last part)
        }

        if (Str::contains($name, '/')) {
            $name = str_replace('/', '.', $name);
        }

        if (Str::contains($name, '\\')) {
            $name = str_replace('\\', '.', $name);
        }

        if (Str::contains($name, '.')) {
            return substr($name, 0, strrpos($name, '.'));
        }

        return $name;
    }

    /**
     * Only return the name of the file
     * Ignore the path / namespace of the file
     *
     * @return array|mixed|string
     */
    protected function getArgumentSubPackage()
    {
        $name = $this->argumentName();

        if (Str::contains($name, '--')) { // package - resource delimiter
            $name = substr($name, strrpos($name, '--') + 2);

            if (Str::contains($name, '/')) {
                $name = str_replace('/', '.', $name);
            }

            if (Str::contains($name, '\\')) {
                $name = str_replace('\\', '.', $name);
            }

            if (Str::contains($name, '.')) {
                return substr($name, 0, strrpos($name, '.'));
            }
        }

        return false;
    }

    /**
     * Return the path of the file
     *
     * @param bool $with_name
     * @return array|mixed|string
     */
    protected function getArgumentPath($with_name = false)
    {
        $path = $this->argumentPath();

        /*
        if (Str::contains($path, '.'))
        {
            $path = str_replace('.', '/', $path);
        }

        if (Str::contains($path, '\\'))
        {
            $path = str_replace('\\', '/', $path);
        }

        // ucfirst char, for correct namespace
        $path = implode('/', array_map('ucfirst', explode('/', $path)));

        // if we need to keep lowercase
        if ($this->settingsDirectoryFormat() === 'strtolower')
        {
            $path = implode('/', array_map('strtolower', explode('/', $path)));
        }

        // if we want the path with name
        if ($with_name)
        {
            return $path . '/';
        }

        if (Str::contains($path, '/'))
        {
            return substr($path, 0, strripos($path, '/') + 1);
        }

        return '';
        */
        return $path . '/';
    }

    /**
     * Get the resource name
     *
     * @param      $name
     * @param bool $format
     * @return string
     */
    protected function getResourceName($name, $format = true)
    {
        if ($name && ($format === false)) {
            return $name;
        }

        $name = isset($name) ? $name : $this->resource;

        if (Str::contains($name, '--')) { // package - resource delimiter
            $name = substr($name, strrpos($name, '--') + 2);
        }

        $this->resource = lcfirst(Str::singular(class_basename($name)));
        $this->resourceLowerCase = strtolower($name);

        return $this->resource;
    }

    /**
     * Get the name for the model
     *
     * @param null $name
     * @return string
     */
    protected function getModelName($name = null)
    {
        $name = isset($name) ? $name : $this->resource;

        //return ucwords(Str::camel($this->getResourceName($name)));

        return Str::singular(ucwords(Str::camel(class_basename($name))));
    }

    /**
     * Get the name for the controller
     *
     * @param null $name
     * @return string
     */
    protected function getControllerName($name = null)
    {
        return ucwords(Str::camel(str_replace($this->settings['postfix'], '', $name)));
    }

    /**
     * Get the name for the repository
     *
     * @param null $name
     * @return string
     */
    protected function getRepositoryName($name = null)
    {
        return ucwords(Str::camel(str_replace($this->settings['postfix'], '', $name)));
    }

    /**
     * Get the name for the form
     *
     * @param null $name
     * @return string
     */
    protected function getFormName($name = null)
    {
        return ucwords(Str::camel(str_replace($this->settings['postfix'], '', $name)));
    }

    /**
     * Get the name for the seed
     *
     * @param null $name
     * @return string
     */
    protected function getSeedName($name = null)
    {
        return ucwords(Str::camel(str_replace($this->settings['postfix'], '', $this->getResourceName($name))));
    }

    /**
     * Get the name of the collection
     *
     * @param null $name
     * @return string
     */
    protected function getCollectionName($name = null)
    {
        return Str::plural($this->getResourceName($name));
    }

    /**
     * Get the plural uppercase name of the resouce
     * @param null $name
     * @return null|string
     */
    protected function getCollectionUpperName($name = null)
    {
        $name = Str::plural($this->getResourceName($name));

        $pieces = explode('_', $name);
        $name = '';

        foreach ($pieces as $k => $str) {
            $name .= ucfirst($str);
        }

        return $name;
    }

    /**
     * Get the path to the view file
     *
     * @param $name
     * @return string
     */
    protected function getViewPath($name)
    {
        $pieces = explode('/', $name);

        // dont plural if reserve word
        foreach ($pieces as $k => $value) {
            if (!in_array($value, $this->getConfig('reserve_words'))) {
                $pieces[$k] = Str::plural(Str::snake($pieces[$k]));
            }
        }

        $name = implode('.', $pieces);

        //$name = implode('.', array_map('Str::plural', explode('/', $name)));

        return strtolower(rtrim(ltrim($name, '.'), '.'));
    }

    /**
     * Get the table name
     *
     * @param $name
     * @return string
     */
    protected function getTableName($name)
    {
        return str_replace('-', '_', Str::plural(Str::snake(class_basename($name))));
    }

    /**
     * Get the table name
     *
     * @param $name
     * @return string
     */
    protected function getFillableAttributes($schema)
    {
        $names = [];

        if ($schema) {
            $schema = (new SchemaParser())->parse($schema);

            foreach ($schema as $field) {
                $names[] = sprintf('\'%s\'', $field['name']);
            }
        }

        return implode(",\n        ", $names);
    }

    /**
     * Get name of file/class with the pre and post fix
     *
     * @param $name
     * @return string
     */
    protected function getFileNameComplete($name)
    {
        return $this->settings['prefix'] . $name . $this->settings['postfix'];
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($root_namespace)
    {
        return $root_namespace . $this->getConfig(strtolower($this->type) . '_namespace');
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        $key = $this->getOptionStubKey();

        $stub = $this->getConfig($key);

        if (is_null($stub)) {
            $this->error(sprintf('No stub in config for "%s"', $key));
        }

        return sprintf('%s/%s', $this->getConfig('stub_directory'), $stub);
    }

    /**
     * Get the key where the stub is located
     *
     * @return string
     */
    protected function getOptionStubKey()
    {
        $plain = $this->option('plain');
        $stub = $this->option('stub') . ($plain ? '_plain' : '') . '_stub';

        if (is_null($this->option('stub'))) {
            $stub = $this->option('type') . ($plain ? '_plain' : '') . '_stub';
        }

        return $stub;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [/*[
            'package',
            InputArgument::REQUIRED,
            'The package of class being generated.'
        ], */[
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
        return [[
            'plain',
            null,
            InputOption::VALUE_NONE,
            'Generate an empty class.'
        ], [
            'force',
            null,
            InputOption::VALUE_NONE,
            'Warning: Override file if it already exist'
        ], [
            'stub',
            null,
            InputOption::VALUE_OPTIONAL,
            'The name of the view stub you would like to generate.'
        ], [
            'name',
            null,
            InputOption::VALUE_OPTIONAL,
            'If you want to override the name of the file that will be generated'
        ]];
    }

    /**
     * Handle error output.
     *
     * @param  string  $string
     * @param  null|int|string  $verbosity
     * @return void
     */
    public function error($string, $verbosity = null)
    {
        throw new RuntimeException($string);
    }
}
