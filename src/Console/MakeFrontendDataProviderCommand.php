<?php


namespace HiHaHo\LaravelJsStore\Console;

use Illuminate\Console\GeneratorCommand;

class MakeFrontendDataProviderCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected string $name = 'make:frontend-data-provider';

    /**
     * The console command description.
     *
     * @var string
     */
    protected string $description = 'Create a new frontend data provider class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected string $type = 'FrontendDataProvider';

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
}
