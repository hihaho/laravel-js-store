<?php

namespace HiHaHo\LaravelJsStore;

use HiHaHo\LaravelJsStore\Console\MakeFrontendDataProviderCommand;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'js-store');

        Blade::include('js-store::script', 'frontend_store');

        $this->bindDataProviders();

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('js-store.php'),
            ], 'laravel-js-store-config');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/js-store'),
            ], 'laravel-js-store-views');

            $this->commands([
                MakeFrontendDataProviderCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'js-store');

        $this->app->singleton(Store::class, function () {
            return new Store;
        });
        $this->app->alias(Store::class, 'js-store');
    }

    protected function bindDataProviders()
    {
        view()->composer('js-store::script', function () {
            $providers = DataProviderCollection::fromConfig('js-store.data-providers');

            if (!$providers->hasData()) {
                return;
            }

            $providers->store();
        });
    }
}
