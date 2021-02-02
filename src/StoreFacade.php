<?php

namespace HiHaHo\LaravelJsStore;

use Illuminate\Support\Facades\Facade;

/**
 * @see \HiHaHo\LaravelJsStore\Store
 */
class StoreFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'js-store';
    }
}
