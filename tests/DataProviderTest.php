<?php


namespace HiHaHo\LaravelJsStore\Tests;


use HiHaHo\LaravelJsStore\Tests\stubs\SomeRandomName;
use HiHaHo\LaravelJsStore\Tests\stubs\UserFrontendDataProvider;
use HiHaHo\LaravelJsStore\Tests\stubs\UserProviderWithKey;
use HiHaHo\LaravelJsStore\Tests\stubs\ValidDataProvider;
use Illuminate\Support\Collection;

class DataProviderTest extends TestCase
{
    public function test_provider_generates_key_from_base_name()
    {
        $validDataProvider = $this->app->make(SomeRandomName::class);

        $validDataProvider->store();

        /** @var Collection $storeData */
        $storeData = $this->app->make('js-store')->data();

        $this->assertTrue($storeData->contains('some-random-name', []));
    }

    public function test_provider_generates_key_from_class_with_data_provider_suffix()
    {
        $validDataProvider = $this->app->make(ValidDataProvider::class);

        $validDataProvider->store();

        /** @var Collection $storeData */
        $storeData = $this->app->make('js-store')->data();

        $this->assertTrue($storeData->contains('valid', []));
    }

    public function test_provider_generates_key_from_class_with_frontend_data_provider_suffix()
    {
        $validDataProvider = $this->app->make(UserFrontendDataProvider::class);

        $validDataProvider->store();

        /** @var Collection $storeData */
        $storeData = $this->app->make('js-store')->data();

        $this->assertTrue($storeData->contains('user', []));
    }

    public function test_provider_uses_key_property()
    {
        $validDataProvider = $this->app->make(UserProviderWithKey::class);

        $validDataProvider->store();

        /** @var Collection $storeData */
        $storeData = $this->app->make('js-store')->data();

        $this->assertTrue($storeData->contains('unit-test-user', []));
    }
}
