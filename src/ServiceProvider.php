<?php

namespace HiHaHo\LaravelJsStore;

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

        Blade::directive('frontend_store', function () {
            return "@include('laravel-js-store::script')";
        });

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('laravel-js-store.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-js-store'),
            ], 'views');
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
}
