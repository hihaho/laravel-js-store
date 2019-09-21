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
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-js-store');

        Blade::include('laravel-js-store::script', 'frontend_store');

        $this->bindDataProviders();

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('laravel-js-store.php'),
            ], 'laravel-js-store-config');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-js-store'),
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
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'laravel-js-store');

        $this->app->singleton(Store::class, function () {
            return new Store;
        });
        $this->app->alias(Store::class, 'laravel-js-store');
    }

    protected function bindDataProviders()
    {
        $data = config('laravel-js-store.data-providers', []);

        if (!is_array($data)) {
            return;
        }

        /** @var Collection $providers */
        $providers = collect($data)->map(function (string $provider) {
            return app()->make($provider);
        })->filter(function ($provider) {
            return $provider instanceof AbstractFrontendDataProvider;
        })->filter->hasData();

        if ($providers->isEmpty()) {
            return;
        }

        view()->composer('*', function() use ($providers) {
            $providers->each->store();
        });
    }
}
