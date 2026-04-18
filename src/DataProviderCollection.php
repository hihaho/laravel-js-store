<?php declare(strict_types=1);

namespace HiHaHo\LaravelJsStore;

use Illuminate\Support\Collection;

class DataProviderCollection
{
    protected Collection $items;

    public function __construct(array $providers)
    {
        $this->items = collect($providers)
            ->map(fn (string $provider) => app()->make($provider))
            ->filter(fn ($provider): bool => $provider instanceof AbstractFrontendDataProvider)
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
