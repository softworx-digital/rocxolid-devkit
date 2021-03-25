<?php

namespace Softworx\RocXolid\DevKit\Console\Commands\Generate;

use Illuminate\Support\Str;
use Softworx\RocXolid\DevKit\Console\Commands\Generate\Migrations\NameParser;
use Softworx\RocXolid\DevKit\Console\Commands\Generate\Migrations\SchemaParser;
use Softworx\RocXolid\DevKit\Console\Commands\Generate\Migrations\SyntaxBuilder;
use Symfony\Component\Console\Input\InputOption;

class Migration extends AbstractCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'rocXolid:generate:migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new migration class, and apply schema at the same time. Usage php artisan rocXolid:generate:migration <action>_<model-name>. Action is from { create, add, remove }';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Migration';

    /**
     * Meta information for the requested migration.
     *
     * @var array
     */
    protected $meta;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->setSettings(strtolower($this->type));
        $this->meta = (new NameParser())->parse($this->argumentName());

        $name = $this->qualifyClass($this->getNameInput());
        $path = $this->getPath($name);

        if ($this->files->exists($path) && ($this->optionForce() === false)) {
            return $this->error($this->type . ' already exists!');
        }

        $this->makeDirectory($path);
        $this->files->put($path, $this->buildClass($name));

        $this->info($this->type . ' created successfully.');
        $this->info('- ' . $path);

        // if model is required
        if (($this->optionModel() === true) || ($this->optionModel() === 'true')) {
            $this->call('rocXolid:generate:model', [
                'name'     => $this->getModelName(),
                '--plain'  => $this->optionPlain(),
                '--force'  => $this->optionForce(),
                '--schema' => $this->optionSchema()
            ]);
        }
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

        $this->replaceNamespace($stub, $name);
        $this->replaceClassName($stub);
        $this->replaceSchema($stub);
        $this->replaceTableName($stub);

        return $stub;
    }

    /**
     * Replace the class name in the stub.
     *
     * @param  string $stub
     * @return $this
     */
    protected function replaceClassName(&$stub)
    {
        $class_name = ucwords(Str::camel($this->argumentName()));

        $stub = str_replace('{{class}}', $class_name, $stub);

        return $this;
    }

    /**
     * Replace the schema for the stub.
     *
     * @param  string $stub
     * @return $this
     */
    protected function replaceSchema(&$stub)
    {
        $schema = '';

        if (!$this->optionPlain()) {
            if ($schema = $this->optionSchema()) {
                $schema = (new SchemaParser())->parse($schema);
            }

            $schema = (new SyntaxBuilder())->create($schema, $this->meta);
        }

        $stub = str_replace(['{{schema_up}}', '{{schema_down}}'], $schema, $stub);

        return $this;
    }

    /**
     * Replace the table name in the stub.
     *
     * @param  string $stub
     * @return $this
     */
    protected function replaceTableName(&$stub)
    {
        $stub = str_replace('{{table}}', $this->meta['table'], $stub);

        return $this;
    }

    /**
     * Get the class name for the Eloquent model generator.
     *
     * @param null $name
     * @return string
     */
    protected function getModelName($name = null)
    {
        $model = '';
        $pieces = explode('_', str_singular($this->meta['table']));

        foreach ($pieces as $k => $str) {
            $model = $model . ucwords($str);
        }

        return $model;
    }

    /**
     * Get the path to where we should store the migration.
     *
     * @param  string $name
     * @return string
     */
    protected function getPath($name)
    {
        return sprintf('%s%s', $this->settings['path'], $this->getFileName($name));
    }

    /**
     * Get migration file name.
     *
     * @param  string $name
     * @return string
     */
    protected function getFileName($name)
    {
        return sprintf('%s_%s.php', date('Y_m_d_His'), $this->argumentName());
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        $param = ($this->input->hasOption('plain') && $this->option('plain') ? '_plain' : '');

        $stub = $this->getConfig(sprintf('%s%s_stub', strtolower($this->type), $param));

        return sprintf('%s/%s', $this->getConfig('stub_directory'), $stub);
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array_merge([[
            'model',
            'm',
            InputOption::VALUE_OPTIONAL,
            'Want a model for this table?',
            true
        ], [
            'schema',
            's',
            InputOption::VALUE_OPTIONAL,
            'Optional schema to be attached to the migration',
            null
        ]], parent::getOptions());
    }
}
