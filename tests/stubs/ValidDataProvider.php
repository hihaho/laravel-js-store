<?php

namespace HiHaHo\LaravelJsStore\Tests\stubs;

use HiHaHo\LaravelJsStore\AbstractFrontendDataProvider;

class ValidDataProvider extends AbstractFrontendDataProvider
{
    const DATA = 'Test value';

    /**
     * The data that will be JSON encoded
     *
     * @return mixed
     */
    public function data(): string
    {
        return self::DATA;
    }
}
