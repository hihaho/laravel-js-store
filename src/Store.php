<?php

namespace HiHaHo\LaravelJsStore;

use HiHaHo\LaravelJsStore\Exceptions\JsonEncodeStoreDataException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Collection;

class Store implements Jsonable, Arrayable
{
    protected $data;

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

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->data->toArray();
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int $options
     * @return string
     * @throws \Exception
     */
    public function toJson($options = 0)
    {
        $json = json_encode($this->data, $options);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new JsonEncodeStoreDataException('Unable to encode store data: '. json_last_error_msg());
        }

        return $json;
    }

    /**
     * Convert the store to its string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }
}
