<?php

use HiHaHo\LaravelJsStore\Store;

if (! function_exists('frontend_store')) {
    /**
     * Set the specified value from the front-end store or get the front-end store.
     *
     * If a key and value are passed, we'll assume you want to put to the vue-store.
     *
     * @param  string|null $key
     * @param  mixed|null $value
     * @return \HiHaHo\LaravelJsStore\Store
     */
    function frontend_store(string $key = null, $value = null): Store
    {
        if (is_null($key)) {
            return app(Store::class);
        }

        return app(Store::class)->put($key, $value);
    }
}
