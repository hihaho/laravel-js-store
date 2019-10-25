<?php


namespace HiHaHo\LaravelJsStore;

use Illuminate\Support\Collection;

class DataProviderCollection
{
    /**
     * @var Collection
     */
    protected $items;

    public function __construct(array $providers)
    {
        $this->items = collect($providers)->map(function (string $provider) {
            return app()->make($provider);
        })->filter(function ($provider) {
            return $provider instanceof AbstractFrontendDataProvider;
        })->filter->hasData();
    }

    public static function fromConfig(string $configPath): self
    {
        $data = config($configPath, []);

        if (!is_array($data)) {
            $data = [];
        }

        return new self($data);
    }

    public function store()
    {
        $this->items->each->store();
    }

    public function hasData(): bool
    {
        return $this->items->isNotEmpty();
    }
}
