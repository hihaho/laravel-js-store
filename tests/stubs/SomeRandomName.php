<?php


namespace HiHaHo\LaravelJsStore\Tests\stubs;


use HiHaHo\LaravelJsStore\AbstractFrontendDataProvider;

class SomeRandomName extends AbstractFrontendDataProvider
{
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
