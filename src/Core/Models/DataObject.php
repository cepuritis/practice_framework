<?php

namespace Core\Models;

class DataObject
{
    private array $data = [];

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            $this->data[lcfirst($key)] = $value;
        }
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed|null
     */
    public function __call($name, $arguments)
    {
        if (str_starts_with($name, 'get')) {
            $key = $this->methodToKeyName($name, 'get');

            return $this->data[$key] ?? null;
        }

        if (str_starts_with($name, 'set')) {
            $key = $this->methodToKeyName($name, 'set');
            return $this->data[$key] = $arguments[0] ?? null;
        }

        return null;
    }

    /**
     * @param string $method
     * @param string $prefix
     * @return string
     */
    private function methodToKeyName(string $method, string $prefix): string
    {
        return lcfirst(substr($method, strlen($prefix)));
    }

    public function getArray()
    {
        return $this->data;
    }

    public function merge(?DataObject $other): DataObject
    {
        if (!is_null($other)) {
            $this->data = array_merge($this->data, $other->getArray());
        }

        return $this;
    }
}
