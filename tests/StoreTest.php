<?php


namespace HiHaHo\LaravelJsStore\Tests;


use HiHaHo\LaravelJsStore\Exceptions\JsonEncodeStoreDataException;
use HiHaHo\LaravelJsStore\Store;

class StoreTest extends TestCase
{
    public function test_store_is_single_instance()
    {
        $store = $this->app->make(Store::class);
        $helperStore = frontend_store();
        $aliasStore = $this->app->make('laravel-js-store');

        $this->assertSame($store, $helperStore);
        $this->assertSame($store, $aliasStore);
    }

    public function test_helper_can_store_data()
    {
        frontend_store('user', true);

        $this->assertCount(1, $this->store->data());

        $this->assertTrue($this->store->data()->get('user'));
    }

    public function test_store_has_data()
    {
        $this->store->put('user', true);

        $this->assertCount(1, $this->store->data());

        $this->assertTrue($this->store->data()->get('user'));
    }

    public function test_store_returns_json()
    {
        $this->store->put('user', true);

        $this->assertJson($this->store->toJson());

        $this->assertJsonStringEqualsJsonString(json_encode([
            'user' => true,
        ]), $this->store->toJson());
    }

    public function test_store_is_stringable()
    {
        $this->store->put('user', true);

        $this->assertIsString((string) frontend_store());
        $this->assertJson((string) frontend_store());
    }

    public function test_store_throws_exception_with_non_stringable_object()
    {
        $this->expectException(JsonEncodeStoreDataException::class);

        // An invalid UTF8 sequence which causes JSON_ERROR_UTF8
        $this->store->put('user', "\xB1\x31")->toJson();
    }
}
