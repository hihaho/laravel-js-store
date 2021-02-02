<?php


namespace HiHaHo\LaravelJsStore\Console;

use Exception;
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
     *
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__.'/stubs/frontend-data-provider.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\Http\FrontendDataProviders';
    }

    protected function buildClass($name): string
    {
        $stub = parent::buildClass($name);
        $key = AbstractFrontendDataProvider::convertClassnameToKey($this->getNameInput());

        if ($this->confirm("Generated key: $key, would you like to use a custom key?")) {
            $key = $this->ask('Custom key');

            $value = 'protected string $key';

            if ($key !== '') {
                $value .= " = '$key'";
            }

            $stub = str_replace("{{ CUSTOM_KEY }}", "$value;", $stub);
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
