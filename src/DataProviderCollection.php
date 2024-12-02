<?php

namespace HiHaHo\LaravelJsStore;

use Illuminate\Support\Collection;

class DataProviderCollection
{
    protected Collection $items;

    public function __construct(array $providers)
    {
        $this->items = collect($providers)
            ->map(function (string $provider) {
                return app()->make($provider);
            })
            ->filter(function ($provider): bool {
                return $provider instanceof AbstractFrontendDataProvider;
            })
            ->filter->hasData();
    }

    public static function fromConfig(string $configPath): self
    {
        $data = config($configPath, []);

        if (! is_array($data)) {
            $data = [];
        }

        return new self($data);
    }

    public function store(): void
    {
        $this->items->each->store();
    }

    public function hasData(): bool
    {
        return $this->items->isNotEmpty();
    }
}
