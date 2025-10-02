<?php

namespace Tests\Fixtures\Session;

use Core\Contracts\Session\SessionStorageInterface;

class MockSessionStorage implements SessionStorageInterface
{
    private array $session;

    public function set(string $key, mixed $value): mixed
    {
        return $this->session[$key] = $value;
    }

    public function get(string $key): mixed
    {
        return $this->session[$key] ?? null;
    }

    public function remove(string $key): mixed
    {
        if (isset($this->session[$key])) {
            $value = $this->session[$key];
            unset($this->session[$key]);

            return $this->session[$key];
        }

        return null;
    }
}
