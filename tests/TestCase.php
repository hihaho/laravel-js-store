<?php


namespace HiHaHo\LaravelJsStore\Tests;


use HiHaHo\LaravelJsStore\ServiceProvider;
use HiHaHo\LaravelJsStore\Store;
use HiHaHo\LaravelJsStore\Tests\stubs\ValidDataProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected Store $store;

    protected function getPackageProviders($app): array
    {
        return [
            ServiceProvider::class,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->store = $this->app->make(Store::class);
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('js-store.data-providers', [
            ValidDataProvider::class,
        ]);
    }
}
