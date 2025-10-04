<?php

namespace Core\Models\Data;

class DataCollection extends DataObject implements \ArrayAccess
{
    /**
     * @param string $method
     * @param string $prefix
     * @return string
     */

    public function getArray(): array
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

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->data[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->data[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->data[$offset]);
    }

    public function e(string $key): string
    {
        return htmlspecialchars($this->data[$key] ?? '', ENT_QUOTES, 'UTF-8');
    }
}