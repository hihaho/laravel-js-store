<?php


namespace HiHaHo\LaravelJsStore\Tests;

use HiHaHo\LaravelJsStore\Exceptions\InvalidResponseException;
use HiHaHo\LaravelJsStore\Exceptions\JsonEncodeStoreDataException;
use HiHaHo\LaravelJsStore\Store;
use Illuminate\Http\Response;
use Illuminate\View\View;

class StoreTest extends TestCase
{
    public function test_store_is_single_instance(): void
    {
        $store = $this->app->make(Store::class);
        $helperStore = frontend_store();
        $aliasStore = $this->app->make('js-store');

        $this->assertSame($store, $helperStore);
        $this->assertSame($store, $aliasStore);
    }

    public function test_helper_can_store_data(): void
    {
        frontend_store('user', true);

        $this->assertCount(1, $this->store->data());

        $this->assertTrue($this->store->data()->get('user'));
    }

    public function test_store_has_data(): void
    {
        $this->store->put('user', true);

        $this->assertCount(1, $this->store->data());

        $this->assertTrue($this->store->data()->get('user'));
    }

    public function test_store_returns_json(): void
    {
        $this->store->put('user', true);

        $this->assertJson($this->store->toJson());

        $this->assertJsonStringEqualsJsonString(json_encode([
            'user' => true,
        ]), $this->store->toJson());
    }

    public function test_store_is_stringable(): void
    {
        $this->store->put('user', true);

        $this->assertInstanceOf(Store::class, frontend_store());
        $this->assertIsString((string) frontend_store());
        $this->assertJson((string) frontend_store());
    }

    public function test_store_throws_exception_with_non_stringable_object(): void
    {
        $this->expectException(JsonEncodeStoreDataException::class);

        // An invalid UTF8 sequence which causes JSON_ERROR_UTF8
        $this->store->put('user', "\xB1\x31")->toJson();
    }

    public function test_view_macro_accepts_key_and_value()
    {
        $view = view('index')->js('foo', 'bar');

        $this->assertInstanceOf(View::class, $view);
        $this->assertSame('bar', $this->store->data()->get('foo'));
    }

    public function test_view_macro_accepts_array()
    {
        $view = view('index')->js([
            'foo' => 'bar',
        ]);

        $this->assertInstanceOf(View::class, $view);
        $this->assertSame('bar', $this->store->data()->get('foo'));
    }

    public function test_view_macro_overwrites_previous_values()
    {
        $this->store->put('foo', 'bar');

        view('index')->js('foo', 'baz');
        $this->assertSame('baz', $this->store->data()->get('foo'));

        view('index')->js([
            'foo' => 'fred',
        ]);
        $this->assertSame('fred', $this->store->data()->get('foo'));
    }

    public function test_response_macro_accepts_key_and_value()
    {
        $response = response()->view('index')->js('foo', 'bar');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame('bar', $this->store->data()->get('foo'));
    }

    public function test_response_macro_accepts_array()
    {
        $response = response()->view('index')->js([
            'foo' => 'bar',
        ]);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame('bar', $this->store->data()->get('foo'));
    }

    public function test_response_macro_throws_error_for_json_response()
    {
        $this->expectException(InvalidResponseException::class);

        response('FooBar')->js('baz', 'fred');
    }
}
