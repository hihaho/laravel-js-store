<?php

if (! function_exists('frontend_store')) {
    /**
     * Set the specified value from the front-end store or get the front-end store.
     *
     * If a key and value are passed, we'll assume you want to put to the vue-store.
     *
     * @param  dynamic  key,value|null
     * @return \HiHaHo\LaravelJsStore\Store
     */
    function frontend_store()
    {
        $arguments = func_get_args();

        if (empty($arguments)) {
            return app('laravel-js-store');
        }

        return app('laravel-js-store')->put($arguments[0], $arguments[1]);
    }
}
