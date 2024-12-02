<?php

namespace HiHaHo\LaravelJsStore\Tests;

use HiHaHo\LaravelJsStore\DataProviderCollection;
use HiHaHo\LaravelJsStore\Tests\stubs\InvalidDataProvider;
use HiHaHo\LaravelJsStore\Tests\stubs\ValidDataProvider;
use HiHaHo\LaravelJsStore\Tests\stubs\ValidEmptyDataProvider;
use Illuminate\Support\Collection;

class DataProviderCollectionTest extends TestCase
{
    public function test_invalid_data_providers_config(): void
    {
        $this->app['config']->set('js-store.test', 'broken');

        $providers = DataProviderCollection::fromConfig('js-store.test');

        $this->assertFalse($providers->hasData());
    }

    public function test_empty_data_providers_config(): void
    {
        $this->app['config']->set('js-store.test', []);

        $providers = DataProviderCollection::fromConfig('js-store.test');

        $this->assertFalse($providers->hasData());
    }

    public function test_invalid_data_provider(): void
    {
        $this->app['config']->set('js-store.test', [
            InvalidDataProvider::class,
        ]);

        $providers = DataProviderCollection::fromConfig('js-store.test');

        $this->assertFalse($providers->hasData());
    }

    public function test_valid_data_provider(): void
    {
        $this->app['config']->set('js-store.test', [
            ValidDataProvider::class,
        ]);

        $providers = DataProviderCollection::fromConfig('js-store.test');

        $this->assertTrue($providers->hasData());
    }

    public function test_empty_data_provider(): void
    {
        $this->app['config']->set('js-store.test', [
            ValidEmptyDataProvider::class,
        ]);

        $providers = DataProviderCollection::fromConfig('js-store.test');

        $this->assertFalse($providers->hasData());
    }

    public function test_invalid_config(): void
    {
        $this->app['config']->set('js-store.test', ValidDataProvider::class);

        $providers = DataProviderCollection::fromConfig('js-store.test');

        $this->assertFalse($providers->hasData());
    }

    public function test_provider_stores_data(): void
    {
        $this->app['config']->set('js-store.test', [
            ValidDataProvider::class,
        ]);

        $providers = DataProviderCollection::fromConfig('js-store.test');
        $providers->store();

        /** @var Collection $storeData */
        $storeData = $this->app->make('js-store')->data();

        $this->assertCount(1, $storeData);
        $this->assertArrayHasKey('valid', $storeData);
        $this->assertEquals(ValidDataProvider::DATA, $storeData->get('valid'));
    }
}
