<?php


namespace HiHaHo\LaravelJsStore\Tests;


use HiHaHo\LaravelJsStore\ServiceProvider;
use HiHaHo\LaravelJsStore\Store;
use HiHaHo\LaravelJsStore\Tests\stubs\ValidDataProvider;
use Illuminate\Support\Facades\View;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    /** @var Store */
    protected $store;

    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->store = $this->app->make(Store::class);

        View::addLocation(__DIR__.'/stubs/views');
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('js-store.data-providers', [
            ValidDataProvider::class,
        ]);
    }
}
