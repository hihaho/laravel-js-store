<?php declare(strict_types=1);

namespace HiHaHo\LaravelJsStore;

use Illuminate\Support\Facades\Facade;

/**
 * @see Store
 */
class StoreFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'js-store';
    }
}
