<?php


namespace HiHaHo\LaravelJsStore;

use Illuminate\Support\Str;

abstract class AbstractFrontendDataProvider
{
    /**
     * @var string Optional key that will be used when this provider is json encoded
     */
    protected string $key;

    /**
     * The data that will be JSON encoded
     *
     * @return mixed
     */
    abstract public function data();

    public function store(): void
    {
        app()->make('js-store')->put($this->key(), $this->data());
    }

    protected function key(): string
    {
        if (isset($this->key)) {
            return $this->key;
        }

        $class = (new \ReflectionClass($this))->getShortName();

        if (Str::endsWith($class, 'FrontendDataProvider')) {
            $name = Str::before($class, 'FrontendDataProvider');
        } elseif (Str::endsWith($class, 'DataProvider')) {
            $name = Str::before($class, 'DataProvider');
        }

        return Str::snake($name ?? $class);
    }

    public function hasData(): bool
    {
        return true;
    }
}
