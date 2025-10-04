<?php

namespace Core\Models\Data;

class DataCollection extends DataObject implements \ArrayAccess
{

    /**
     * @param DataObject|null $other
     * @return $this
     */
    public function merge(?DataObject $other): DataCollection
    {
        if (!is_null($other)) {
            $this->data = array_merge($this->data, $other->getArray());
        }

        return $this;
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->data[$offset]);
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->data[$offset];
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->data[$offset] = $value;
    }

    /**
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->data[$offset]);
    }

    /**
     * @param string $key
     * @return string
     */
    public function e(string $key): string
    {
        return htmlspecialchars($this->data[$key] ?? '', ENT_QUOTES, 'UTF-8');
    }
}
