<?php

namespace HiHaHo\LaravelJsStore;

use HiHaHo\LaravelJsStore\Console\MakeFrontendDataProviderCommand;
use HiHaHo\LaravelJsStore\Exceptions\InvalidResponseException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\View;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('js-store')
            ->hasConfigFile()
            ->hasViews()
            ->hasCommands([
                MakeFrontendDataProviderCommand::class,
            ]);
    }

    public function packageBooted(): void
    {
        Blade::include('js-store::script', 'frontend_store');

        View::macro('js', function ($key, $value = null): View {
            /** @var View $this */

            $data = is_array($key) ? $key : [$key => $value];

            app(Store::class)->merge($data);

            return $this;
        });

        Response::macro('js', function ($key, $value = null): Response {
            /** @var Response $this */

            if (! $this->getOriginalContent() instanceof View) {
                throw new InvalidResponseException('Some error');
            }

            $data = is_array($key) ? $key : [$key => $value];

            app(Store::class)->merge($data);

            return $this;
        });

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
