<?php


namespace HiHaHo\LaravelJsStore\Tests;


use Hihaho\LaravelJsStore\DataProviderCollection;
use HiHaHo\LaravelJsStore\Exceptions\JsonEncodeStoreDataException;
use HiHaHo\LaravelJsStore\Store;
use HiHaHo\LaravelJsStore\Tests\stubs\InvalidDataProvider;
use HiHaHo\LaravelJsStore\Tests\stubs\ValidDataProvider;
use HiHaHo\LaravelJsStore\Tests\stubs\ValidEmptyDataProvider;

class DataProviderCollectionTest extends TestCase
{
    public function test_invalid_data_providers_config()
    {
        $this->app['config']->set('laravel-js-store.test', 'broken');

        $providers = DataProviderCollection::fromConfig('laravel-js-store.test');

        $this->assertFalse($providers->hasData());
    }

    public function test_empty_data_providers_config()
    {
        $this->app['config']->set('laravel-js-store.test', []);

        $providers = DataProviderCollection::fromConfig('laravel-js-store.test');

        $this->assertFalse($providers->hasData());
    }

    public function test_invalid_data_provider()
    {
        $this->app['config']->set('laravel-js-store.test', [
            InvalidDataProvider::class,
        ]);

        $providers = DataProviderCollection::fromConfig('laravel-js-store.test');

        $this->assertFalse($providers->hasData());
    }

    public function test_valid_data_provider()
    {
        $this->app['config']->set('laravel-js-store.test', [
            ValidDataProvider::class,
        ]);

        $providers = DataProviderCollection::fromConfig('laravel-js-store.test');

        $this->assertTrue($providers->hasData());
    }

    public function test_empty_data_provider()
    {
        $this->app['config']->set('laravel-js-store.test', [
            ValidEmptyDataProvider::class,
        ]);

        $providers = DataProviderCollection::fromConfig('laravel-js-store.test');

        $this->assertFalse($providers->hasData());
    }
}
