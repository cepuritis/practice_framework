<?php

namespace Core\Models\Data;

class DataObject
{
    protected array $data = [];

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

            return $this->getValue($key);
        }

        if (str_starts_with($name, 'set')) {
            $key = $this->methodToKeyName($name, 'set');
            $key = $this->resolveKey($key);
            $this->data[$key] = $arguments[0] ?? null;
            return $this;
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

    /**
     *  Resolve the actual key in $data.
     *  - Returns camelCase if it exists
     *  - Otherwise returns snake_case if it exists
     *  - Otherwise returns the camelCase key for new entries
     *
     * @param string $key
     * @return string
     */
    private function resolveKey(string $key): string
    {
        if (array_key_exists($key, $this->data)) {
            return $key;
        }

        $snake = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $key));
        if (array_key_exists($snake, $this->data)) {
            return $snake;
        }

        // Key doesn't exist yet → return camelCase
        return $key;
    }

    /**
     * @param string $key
     * @return mixed
     */
    private function getValue(string $key): mixed
    {
        return $this->data[$this->resolveKey($key)] ?? null;
    }
}
