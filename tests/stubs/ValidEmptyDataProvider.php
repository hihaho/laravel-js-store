<?php


namespace HiHaHo\LaravelJsStore\Tests\stubs;


use HiHaHo\LaravelJsStore\AbstractFrontendDataProvider;

class ValidEmptyDataProvider extends AbstractFrontendDataProvider
{
    /**
     * The data that will be JSON encoded
     *
     * @return mixed
     */
    public function data(): array
    {
        return [];
    }

    public function hasData(): bool
    {
        return false;
    }
}
