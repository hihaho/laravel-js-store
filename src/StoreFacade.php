<?php

namespace HiHaHo\LaravelJsStore;

use Illuminate\Support\Facades\Facade;

/**
 * @see \HiHaHo\LaravelJsStore\Store
 */
class StoreFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-js-store';
    }
}
