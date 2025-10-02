<?php

namespace Core\Contracts\Session;

interface SessionStorageInterface
{
    /**
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function set(string $key, mixed $value): mixed;

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed;

    /**
     * @param string $key
     * @return mixed
     */
    public function remove(string $key): mixed;
}