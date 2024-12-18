<?php

namespace HiHaHo\LaravelJsStore;

use HiHaHo\LaravelJsStore\Exceptions\JsonEncodeStoreDataException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Collection;

class Store implements Arrayable, Jsonable
{
    protected Collection $data;

    public function __construct()
    {
        $this->data = new Collection;
    }

    public function put(string $key, $data): self
    {
        $this->data->put($key, $data);

        return $this;
    }

    public function merge(array $data): self
    {
        $this->data = $this->data->merge($data);

        return $this;
    }

    public function data(): Collection
    {
        return $this->data;
    }

    public function toArray(): array
    {
        return $this->data->toArray();
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int  $options
     *
     * @throws \Exception
     */
    public function toJson($options = 0): string
    {
        $json = json_encode($this->data, $options);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new JsonEncodeStoreDataException('Unable to encode store data: '.json_last_error_msg());
        }

        return $json;
    }

    /**
     * Convert the store to its string representation.
     */
    public function __toString(): string
    {
        return $this->toJson();
    }

    public function flushShared(): void
    {
        $this->data = new Collection;
    }
}
