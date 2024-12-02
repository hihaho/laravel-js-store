<?php

namespace HiHaHo\LaravelJsStore\Console;

use HiHaHo\LaravelJsStore\AbstractFrontendDataProvider;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class MakeFrontendDataProviderCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:frontend-data-provider';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new frontend data provider class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'FrontendDataProvider';

    /**
     * Get the stub file for the generator.
     */
    protected function getStub(): string
    {
        return __DIR__.'/stubs/frontend-data-provider.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\Http\FrontendDataProviders';
    }

    protected function buildClass($name): string
    {
        $stub = parent::buildClass($name);
        $generatedKey = AbstractFrontendDataProvider::convertClassnameToKey($this->getNameInput());

        if ($this->confirm("Generated key: $generatedKey, would you like to use a custom key?")) {
            $customKey = $this->ask('Custom key');

            $replacement = $customKey === '' ? 'protected string $key;' : "protected string \$key = '$customKey';";

            $stub = str_replace('{{ CUSTOM_KEY }}', $replacement, $stub);
        } else {
            $stub = str_replace("    {{ CUSTOM_KEY }}\n\n", '', $stub);
        }

        return $stub;
    }

    protected function getOptions()
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Create the class even if the frontend data provider already exists'],
        ];
    }
}
