<?php

namespace HiHaHo\LaravelJsStore;

use HiHaHo\LaravelJsStore\Console\MakeFrontendDataProviderCommand;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-js-store')
            ->hasConfigFile()
            ->hasViews()
            ->hasCommands([
                MakeFrontendDataProviderCommand::class
            ]);
    }

    public function packageBooted(): void
    {
        Blade::include('js-store::script', 'frontend_store');

        view()->composer('js-store::script', function (): void {
            $providers = DataProviderCollection::fromConfig('js-store.data-providers');

            if (! $providers->hasData()) {
                return;
            }

            $providers->store();
        });
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(Store::class, function (): Store {
            return new Store;
        });
        $this->app->alias(Store::class, 'js-store');
    }
}
