<?php


namespace HiHaHo\LaravelJsStore\Tests\stubs;


use HiHaHo\LaravelJsStore\AbstractFrontendDataProvider;

class UserProviderWithKey extends AbstractFrontendDataProvider
{
    protected string $key = 'unit-test-user';

    /**
     * The data that will be JSON encoded
     *
     * @return mixed
     */
    public function data()
    {
        return [];
    }
}
